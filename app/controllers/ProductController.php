<?php

class ProductController extends Controller
{
    private $productModel;
    private $categoryModel;

    public function __construct()
    {
        parent::__construct();
        $this->productModel = new Product();
        $this->categoryModel = new Category();
    }

    public function index()
    {
        // Parámetros de filtro
        $page = (int)($_GET['page'] ?? 1);
        $category = $_GET['category'] ?? null;
        $sort = $_GET['sort'] ?? 'featured';
        $search = $_GET['search'] ?? null;
        
        // Usar datos de ejemplo temporalmente
        $filters = [
            'page' => $page,
            'per_page' => 9,
            'category' => $category,
            'search' => $search,
            'sort' => $sort
        ];
        
        $productsResult = $this->productModel->getSampleProducts($filters);
        $categories = $this->productModel->getSampleCategories();

        $data = [
            'title' => 'Shop - Zay Shop',
            'meta_description' => 'Shop the latest products at Zay Shop',
            'products' => $productsResult['data'],
            'categories' => $categories,
            'pagination' => [
                'current_page' => $page,
                'per_page' => 9,
                'total' => $productsResult['total'],
                'has_more' => $productsResult['has_more']
            ],
            'filters' => [
                'category' => $category,
                'sort' => $sort,
                'search' => $search
            ]
        ];

        return $this->renderWithLayout('products/shop', $data);
    }

    public function show($id)
    {
        if (!$id) {
            return $this->redirect('/shop');
        }

        // Obtener producto de los datos de ejemplo
        $sampleProducts = $this->productModel->getSampleProducts([]);
        $product = null;
        
        foreach ($sampleProducts['data'] as $sampleProduct) {
            if ($sampleProduct['id'] == $id) {
                $product = $sampleProduct;
                break;
            }
        }
        
        if (!$product) {
            http_response_code(404);
            $data = ['title' => '404 - Product Not Found'];
            return $this->renderWithLayout('errors/404', $data);
        }

        // Productos relacionados (de la misma categoría)
        $relatedProducts = [];
        foreach ($sampleProducts['data'] as $sampleProduct) {
            if ($sampleProduct['category_id'] == $product['category_id'] && $sampleProduct['id'] != $id) {
                $relatedProducts[] = $sampleProduct;
            }
        }

        $data = [
            'title' => $product['name'] . ' - Zay Shop',
            'meta_description' => substr($product['description'], 0, 160),
            'product' => $product,
            'related_products' => $relatedProducts
        ];

        return $this->renderWithLayout('products/single', $data);
    }

    public function byCategory()
    {
        $categorySlug = $this->request->getParam('category');
        
        $category = $this->categoryModel->findBySlug($categorySlug);
        
        if (!$category) {
            $this->redirect('/error/404');
        }

        // Obtener productos de la categoría
        $page = (int)$this->getQuery('page', 1);
        $products = $this->productModel->paginate($page, 12, 'category_id = :category', ['category' => $category['id']]);

        $data = [
            'title' => $category['name'] . ' - Zay Shop',
            'category' => $category,
            'products' => $products,
            'breadcrumb' => [
                ['title' => 'Home', 'url' => '/'],
                ['title' => 'Shop', 'url' => '/products'],
                ['title' => $category['name']]
            ]
        ];

        return $this->renderWithLayout('categories/show', $data);
    }
}