<?php

class Product extends Model
{
    protected $table = 'products';
    protected $fillable = [
        'name', 'description', 'short_description', 'price', 'sale_price', 
        'sku', 'category_id', 'brand_id', 'stock', 'weight', 'dimensions',
        'featured', 'active', 'meta_title', 'meta_description', 'slug'
    ];

    public function getFeatured($limit = 6)
    {
        $sql = "SELECT p.*, c.name as category_name, b.name as brand_name,
                       (SELECT image_url FROM product_images WHERE product_id = p.id AND is_main = 1 LIMIT 1) as main_image
                FROM {$this->table} p 
                LEFT JOIN categories c ON p.category_id = c.id 
                LEFT JOIN brands b ON p.brand_id = b.id 
                WHERE p.featured = 1 AND p.active = 1 
                ORDER BY p.created_at DESC 
                LIMIT {$limit}";
        
        return $this->db->select($sql);
    }

    public function getSpecialOffers($limit = 3)
    {
        $sql = "SELECT p.*, c.name as category_name, b.name as brand_name,
                       (SELECT image_url FROM product_images WHERE product_id = p.id AND is_main = 1 LIMIT 1) as main_image,
                       ROUND(((p.price - p.sale_price) / p.price) * 100) as discount_percentage
                FROM {$this->table} p 
                LEFT JOIN categories c ON p.category_id = c.id 
                LEFT JOIN brands b ON p.brand_id = b.id 
                WHERE p.sale_price IS NOT NULL AND p.sale_price < p.price AND p.active = 1 
                ORDER BY discount_percentage DESC 
                LIMIT {$limit}";
        
        return $this->db->select($sql);
    }

    public function getProductDetails($id)
    {
        $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug, 
                       b.name as brand_name, b.slug as brand_slug,
                       AVG(r.rating) as average_rating,
                       COUNT(r.id) as reviews_count
                FROM {$this->table} p 
                LEFT JOIN categories c ON p.category_id = c.id 
                LEFT JOIN brands b ON p.brand_id = b.id 
                LEFT JOIN reviews r ON p.id = r.product_id AND r.approved = 1
                WHERE p.id = :id AND p.active = 1
                GROUP BY p.id";
        
        $product = $this->db->selectOne($sql, ['id' => $id]);
        
        if ($product) {
            // Obtener imágenes del producto
            $product['images'] = $this->getProductImages($id);
            
            // Obtener tallas disponibles
            $product['sizes'] = $this->getProductSizes($id);
            
            // Obtener colores disponibles
            $product['colors'] = $this->getProductColors($id);
        }
        
        return $product;
    }

    public function getProductImages($productId)
    {
        $sql = "SELECT * FROM product_images WHERE product_id = :product_id ORDER BY is_main DESC, sort_order ASC";
        return $this->db->select($sql, ['product_id' => $productId]);
    }

    public function getProductSizes($productId)
    {
        $sql = "SELECT s.* FROM sizes s 
                INNER JOIN product_sizes ps ON s.id = ps.size_id 
                WHERE ps.product_id = :product_id AND ps.stock > 0
                ORDER BY s.sort_order";
        return $this->db->select($sql, ['product_id' => $productId]);
    }

    public function getProductColors($productId)
    {
        $sql = "SELECT DISTINCT color_name, color_code FROM product_variants 
                WHERE product_id = :product_id AND stock > 0
                ORDER BY color_name";
        return $this->db->select($sql, ['product_id' => $productId]);
    }

    public function getRelated($categoryId, $excludeId, $limit = 4)
    {
        $sql = "SELECT p.*, c.name as category_name,
                       (SELECT image_url FROM product_images WHERE product_id = p.id AND is_main = 1 LIMIT 1) as main_image
                FROM {$this->table} p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.category_id = :category_id AND p.id != :exclude_id AND p.active = 1 
                ORDER BY RAND() 
                LIMIT {$limit}";
        
        return $this->db->select($sql, [
            'category_id' => $categoryId,
            'exclude_id' => $excludeId
        ]);
    }

    public function getReviews($productId, $limit = 10)
    {
        $sql = "SELECT r.*, u.name as user_name, u.avatar 
                FROM reviews r 
                LEFT JOIN users u ON r.user_id = u.id 
                WHERE r.product_id = :product_id AND r.approved = 1 
                ORDER BY r.created_at DESC 
                LIMIT {$limit}";
        
        return $this->db->select($sql, ['product_id' => $productId]);
    }

    public function search($query, $filters = [])
    {
        $conditions = ['p.active = 1'];
        $params = [];

        // Búsqueda por texto
        if (!empty($query)) {
            $conditions[] = "(p.name LIKE :query OR p.description LIKE :query OR p.short_description LIKE :query)";
            $params['query'] = "%{$query}%";
        }

        // Filtro por categoría
        if (!empty($filters['category_id'])) {
            $conditions[] = "p.category_id = :category_id";
            $params['category_id'] = $filters['category_id'];
        }

        // Filtro por marca
        if (!empty($filters['brand_id'])) {
            $conditions[] = "p.brand_id = :brand_id";
            $params['brand_id'] = $filters['brand_id'];
        }

        // Filtro por precio
        if (!empty($filters['min_price'])) {
            $conditions[] = "COALESCE(p.sale_price, p.price) >= :min_price";
            $params['min_price'] = $filters['min_price'];
        }

        if (!empty($filters['max_price'])) {
            $conditions[] = "COALESCE(p.sale_price, p.price) <= :max_price";
            $params['max_price'] = $filters['max_price'];
        }

        $whereClause = implode(' AND ', $conditions);

        $sql = "SELECT p.*, c.name as category_name, b.name as brand_name,
                       (SELECT image_url FROM product_images WHERE product_id = p.id AND is_main = 1 LIMIT 1) as main_image,
                       COALESCE(p.sale_price, p.price) as final_price
                FROM {$this->table} p 
                LEFT JOIN categories c ON p.category_id = c.id 
                LEFT JOIN brands b ON p.brand_id = b.id 
                WHERE {$whereClause}
                ORDER BY p.featured DESC, p.created_at DESC";

        return $this->db->select($sql, $params);
    }

    public function updateStock($productId, $quantity)
    {
        return $this->update($productId, ['stock' => $quantity]);
    }

    public function decrementStock($productId, $quantity)
    {
        $sql = "UPDATE {$this->table} SET stock = stock - :quantity WHERE id = :id AND stock >= :quantity";
        $result = $this->db->query($sql, ['quantity' => $quantity, 'id' => $productId]);
        return $result->rowCount() > 0;
    }

    public function incrementStock($productId, $quantity)
    {
        $sql = "UPDATE {$this->table} SET stock = stock + :quantity WHERE id = :id";
        $this->db->query($sql, ['quantity' => $quantity, 'id' => $productId]);
    }
    
    // Método temporal para obtener productos de ejemplo
    public function getSampleProducts($filters = [])
    {
        $sampleProducts = [
            [
                'id' => 1,
                'name' => 'Gym Weight',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit',
                'price' => 240.00,
                'sale_price' => null,
                'image' => 'shop_01.jpg',
                'category_id' => 1,
                'category_name' => 'Sport',
                'size' => 'M',
                'stock' => 10
            ],
            [
                'id' => 2,
                'name' => 'Cloud Nike Shoes',
                'description' => 'Aenean gravida dignissim finibus',
                'price' => 480.00,
                'sale_price' => null,
                'image' => 'shop_02.jpg',
                'category_id' => 2,
                'category_name' => 'Fashion',
                'size' => 'L',
                'stock' => 15
            ],
            [
                'id' => 3,
                'name' => 'Summer Addides Shoes',
                'description' => 'Nullam eget luctu magna',
                'price' => 360.00,
                'sale_price' => 280.00,
                'image' => 'shop_03.jpg',
                'category_id' => 2,
                'category_name' => 'Fashion',
                'size' => 'XL',
                'stock' => 8
            ],
            [
                'id' => 4,
                'name' => 'Lovely Proxy',
                'description' => 'Curabitur cursus suscipit suscipit',
                'price' => 75.00,
                'sale_price' => null,
                'image' => 'shop_04.jpg',
                'category_id' => 3,
                'category_name' => 'Luxury',
                'size' => 'S',
                'stock' => 20
            ],
            [
                'id' => 5,
                'name' => 'Galaxy Shoes',
                'description' => 'Proin pharetra luctus diam',
                'price' => 450.00,
                'sale_price' => 380.00,
                'image' => 'shop_05.jpg',
                'category_id' => 2,
                'category_name' => 'Fashion',
                'size' => 'M',
                'stock' => 12
            ],
            [
                'id' => 6,
                'name' => 'Speed Memory Foam',
                'description' => 'Vestibulum et metus nulla',
                'price' => 320.00,
                'sale_price' => null,
                'image' => 'shop_06.jpg',
                'category_id' => 1,
                'category_name' => 'Sport',
                'size' => 'L',
                'stock' => 6
            ],
            [
                'id' => 7,
                'name' => 'Adidas Ultrab',
                'description' => 'Sed et ex ante ipsum sed',
                'price' => 200.00,
                'sale_price' => null,
                'image' => 'shop_07.jpg',
                'category_id' => 1,
                'category_name' => 'Sport',
                'size' => 'XL',
                'stock' => 18
            ],
            [
                'id' => 8,
                'name' => 'Jewelry Stone',
                'description' => 'Mauris suscipit suscipit hac',
                'price' => 890.00,
                'sale_price' => 750.00,
                'image' => 'shop_08.jpg',
                'category_id' => 3,
                'category_name' => 'Luxury',
                'size' => 'S',
                'stock' => 4
            ],
            [
                'id' => 9,
                'name' => 'Miami Shoes',
                'description' => 'Aliquam lorem ante dapibus',
                'price' => 150.00,
                'sale_price' => null,
                'image' => 'shop_09.jpg',
                'category_id' => 2,
                'category_name' => 'Fashion',
                'size' => 'M',
                'stock' => 25
            ]
        ];
        
        // Aplicar filtros si existen
        $filteredProducts = $sampleProducts;
        
        if (!empty($filters['category'])) {
            $filteredProducts = array_filter($filteredProducts, function($product) use ($filters) {
                return $product['category_id'] == $filters['category'];
            });
        }
        
        if (!empty($filters['search'])) {
            $filteredProducts = array_filter($filteredProducts, function($product) use ($filters) {
                return stripos($product['name'], $filters['search']) !== false ||
                       stripos($product['description'], $filters['search']) !== false;
            });
        }
        
        // Aplicar ordenamiento
        if (!empty($filters['sort'])) {
            switch ($filters['sort']) {
                case 'name_asc':
                    usort($filteredProducts, function($a, $b) { return strcmp($a['name'], $b['name']); });
                    break;
                case 'name_desc':
                    usort($filteredProducts, function($a, $b) { return strcmp($b['name'], $a['name']); });
                    break;
                case 'price_asc':
                    usort($filteredProducts, function($a, $b) { 
                        $priceA = $a['sale_price'] ?? $a['price'];
                        $priceB = $b['sale_price'] ?? $b['price'];
                        return $priceA <=> $priceB;
                    });
                    break;
                case 'price_desc':
                    usort($filteredProducts, function($a, $b) { 
                        $priceA = $a['sale_price'] ?? $a['price'];
                        $priceB = $b['sale_price'] ?? $b['price'];
                        return $priceB <=> $priceA;
                    });
                    break;
            }
        }
        
        // Paginación
        $page = $filters['page'] ?? 1;
        $perPage = $filters['per_page'] ?? 9;
        $offset = ($page - 1) * $perPage;
        
        $total = count($filteredProducts);
        $paginatedProducts = array_slice($filteredProducts, $offset, $perPage);
        
        return [
            'data' => $paginatedProducts,
            'total' => $total,
            'has_more' => ($offset + $perPage) < $total
        ];
    }
    
    public function getSampleCategories()
    {
        return [
            ['id' => 1, 'name' => 'Sport', 'slug' => 'sport'],
            ['id' => 2, 'name' => 'Fashion', 'slug' => 'fashion'],
            ['id' => 3, 'name' => 'Luxury', 'slug' => 'luxury']
        ];
    }
}