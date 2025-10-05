<?php

class Category extends Model
{
    protected $table = 'categories';
    protected $fillable = [
        'name', 'slug', 'description', 'image', 'parent_id', 
        'active', 'sort_order', 'meta_title', 'meta_description'
    ];

    public function getMainCategories($limit = null)
    {
        // Datos de ejemplo temporales
        $categories = [
            ['id' => 1, 'name' => 'Sport', 'slug' => 'sport', 'image' => 'category_img_01.jpg'],
            ['id' => 2, 'name' => 'Fashion', 'slug' => 'fashion', 'image' => 'category_img_02.jpg'],
            ['id' => 3, 'name' => 'Luxury', 'slug' => 'luxury', 'image' => 'category_img_03.jpg']
        ];
        
        return $limit ? array_slice($categories, 0, $limit) : $categories;
    }

    public function getSubcategories($parentId)
    {
        $sql = "SELECT c.*, 
                       COUNT(p.id) as products_count
                FROM {$this->table} c 
                LEFT JOIN products p ON c.id = p.category_id AND p.active = 1
                WHERE c.parent_id = :parent_id AND c.active = 1 
                GROUP BY c.id
                ORDER BY c.sort_order ASC, c.name ASC";
        
        return $this->db->select($sql, ['parent_id' => $parentId]);
    }

    public function findBySlug($slug)
    {
        return $this->findBy('slug', $slug);
    }

    public function getHierarchy()
    {
        // Obtener todas las categorías activas
        $categories = $this->where('active = 1', [], 'sort_order ASC, name ASC');
        
        // Organizar en jerarquía
        return $this->buildHierarchy($categories);
    }

    private function buildHierarchy($categories, $parentId = null)
    {
        $result = [];
        
        foreach ($categories as $category) {
            if ($category['parent_id'] == $parentId) {
                $category['children'] = $this->buildHierarchy($categories, $category['id']);
                $result[] = $category;
            }
        }
        
        return $result;
    }

    public function getBreadcrumb($categoryId)
    {
        $breadcrumb = [];
        $category = $this->find($categoryId);
        
        while ($category) {
            array_unshift($breadcrumb, $category);
            $category = $category['parent_id'] ? $this->find($category['parent_id']) : null;
        }
        
        return $breadcrumb;
    }

    public function getPopular($limit = 6)
    {
        $sql = "SELECT c.*, 
                       COUNT(p.id) as products_count,
                       (SELECT image_url FROM category_images WHERE category_id = c.id LIMIT 1) as image_url
                FROM {$this->table} c 
                LEFT JOIN products p ON c.id = p.category_id AND p.active = 1
                WHERE c.active = 1 
                GROUP BY c.id
                HAVING products_count > 0
                ORDER BY products_count DESC, c.sort_order ASC
                LIMIT {$limit}";
        
        return $this->db->select($sql);
    }

    public function getFeatured($limit = 3)
    {
        $sql = "SELECT c.*, 
                       COUNT(p.id) as products_count,
                       (SELECT image_url FROM category_images WHERE category_id = c.id LIMIT 1) as image_url
                FROM {$this->table} c 
                LEFT JOIN products p ON c.id = p.category_id AND p.active = 1
                WHERE c.featured = 1 AND c.active = 1 
                GROUP BY c.id
                ORDER BY c.sort_order ASC, c.name ASC
                LIMIT {$limit}";
        
        return $this->db->select($sql);
    }

    public function getWithProductCount()
    {
        $sql = "SELECT c.*, 
                       COUNT(p.id) as products_count
                FROM {$this->table} c 
                LEFT JOIN products p ON c.id = p.category_id AND p.active = 1
                WHERE c.active = 1 
                GROUP BY c.id
                ORDER BY c.sort_order ASC, c.name ASC";
        
        return $this->db->select($sql);
    }

    public function createSlug($name)
    {
        $slug = strtolower(trim($name));
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = trim($slug, '-');
        
        // Verificar si el slug ya existe
        $originalSlug = $slug;
        $counter = 1;
        
        while ($this->findBySlug($slug)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }
}