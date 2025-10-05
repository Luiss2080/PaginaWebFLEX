<?php

class App
{
    private static $instance = null;
    private $router;
    private $request;
    private $response;
    private $config;

    private function __construct()
    {
        $this->loadEnvironment();
        $configPath = dirname(__DIR__, 2) . '/config/app.php';
        $this->config = file_exists($configPath) ? require_once $configPath : [];
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);
        
        // Iniciar sesión
        Session::start();
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function run()
    {
        try {
            // Configurar rutas
            $this->setupRoutes();
            
            // Ejecutar la ruta
            $this->router->dispatch();
            
        } catch (Exception $e) {
            if ($this->config['debug'] ?? true) {
                echo "<pre>";
                echo "Error: " . $e->getMessage() . "\n";
                echo "File: " . $e->getFile() . "\n";
                echo "Line: " . $e->getLine() . "\n";
                echo "Stack trace:\n" . $e->getTraceAsString();
                echo "</pre>";
            } else {
                echo "Ha ocurrido un error interno.";
            }
        }
    }
    
    private function setupRoutes()
    {
        // Páginas principales
        $this->router->get('/', 'HomeController@index');
        $this->router->get('/home', 'HomeController@index');
        
        // Páginas estáticas
        $this->router->get('/about', 'PageController@about');
        $this->router->get('/contact', 'PageController@contact');
        $this->router->post('/contact/send', 'PageController@sendContact');
        
        // Productos
        $this->router->get('/shop', 'ProductController@index');
        $this->router->get('/shop/category/{id}', 'ProductController@category');
        $this->router->get('/shop/product/{id}', 'ProductController@show');
        
        // Cart
        $this->router->get('/cart', 'CartController@index');
        $this->router->post('/cart/add', 'CartController@add');
        $this->router->post('/cart/update', 'CartController@update');
        $this->router->post('/cart/remove', 'CartController@remove');
        
        // API endpoints
        $this->router->get('/api/search', 'ProductController@search');
        $this->router->get('/api/cart/count', 'CartController@count');
    }

    private function loadEnvironment()
    {
        $envFile = __DIR__ . '/../../.env';
        if (file_exists($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos(trim($line), '#') === 0) {
                    continue;
                }
                list($name, $value) = explode('=', $line, 2);
                $_ENV[trim($name)] = trim($value);
            }
        }
    }

    public function getRouter()
    {
        return $this->router;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function getConfig()
    {
        return $this->config;
    }
}