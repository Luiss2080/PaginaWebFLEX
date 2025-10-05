<?php

class HomeController extends Controller
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
        // Usar datos de ejemplo temporalmente
        $sampleData = $this->productModel->getSampleProducts(['per_page' => 6]);
        $featuredProducts = array_slice($sampleData['data'], 0, 6);
        
        // Obtener categorías de ejemplo
        $categories = $this->productModel->getSampleCategories();
        
        // Ofertas especiales (productos con descuento)
        $specialOffers = array_filter($sampleData['data'], function($product) {
            return !empty($product['sale_price']);
        });
        $specialOffers = array_slice($specialOffers, 0, 3);

        $data = [
            'title' => 'Zay Shop - Home',
            'featured_products' => $featuredProducts,
            'categories' => $categories,
            'special_offers' => $specialOffers,
            'hero_banners' => [
                [
                    'image' => 'img/banner_img_01.jpg',
                    'title' => 'Zay eCommerce',
                    'subtitle' => 'Tiny and Perfect eCommerce Template',
                    'description' => 'Zay Shop is an eCommerce HTML5 CSS template with latest version of Bootstrap 5 (beta 1). This template is 100% free provided by TemplateMo website. Image credits go to Freepik Stories, Unsplash and Icons 8.',
                    'button_text' => 'Start Shopping',
                    'button_link' => '/products'
                ],
                [
                    'image' => 'img/banner_img_02.jpg',
                    'title' => 'Proident occaecat',
                    'subtitle' => 'Aliquip ex ea commodo consequat',
                    'description' => 'You are permitted to use this Zay CSS template for your commercial websites. You are not permitted to re-distribute the template ZIP file in any kind of template collection websites.',
                    'button_text' => 'Start Shopping',
                    'button_link' => '/products'
                ],
                [
                    'image' => 'img/banner_img_03.jpg',
                    'title' => 'Repr in voluptate',
                    'subtitle' => 'Ullamco laboris nisi ut',
                    'description' => 'We bring you 100% free CSS templates for your websites. If you wish to support TemplateMo, please make a small contribution via PayPal or tell your friends about our website. Thank you.',
                    'button_text' => 'Start Shopping',
                    'button_link' => '/products'
                ]
            ]
        ];

        return $this->renderWithLayout('home/index', $data);
    }
}