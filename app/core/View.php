<?php

class View
{
    private $viewsPath;
    private $data = [];

    public function __construct($viewsPath = null)
    {
        $this->viewsPath = $viewsPath ?: __DIR__ . '/../views/';
    }

    public function render($view, $data = [])
    {
        $this->data = array_merge($this->data, $data);
        
        $viewFile = $this->viewsPath . $view . '.php';
        
        if (!file_exists($viewFile)) {
            throw new Exception("Vista $view no encontrada en $viewFile");
        }
        
        // Extraer variables para la vista
        extract($this->data);
        
        // Capturar salida
        ob_start();
        include $viewFile;
        return ob_get_clean();
    }

    public function renderWithLayout($view, $data = [], $layout = 'layouts/main')
    {
        // Renderizar contenido de la vista
        $content = $this->render($view, $data);
        
        // Agregar contenido a los datos del layout
        $layoutData = array_merge($this->data, $data, ['content' => $content]);
        
        return $this->render($layout, $layoutData);
    }

    public function setGlobalData($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function setGlobalDataArray($data)
    {
        $this->data = array_merge($this->data, $data);
    }

    public function exists($view)
    {
        $viewFile = $this->viewsPath . $view . '.php';
        return file_exists($viewFile);
    }

    public function share($key, $value)
    {
        $this->setGlobalData($key, $value);
    }

    // Helpers para vistas
    public static function escape($string)
    {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }

    public static function asset($path)
    {
        $baseUrl = $_ENV['APP_URL'] ?? 'http://localhost/PaginaWebFLEX';
        return $baseUrl . '/public/' . ltrim($path, '/');
    }

    public static function url($path = '')
    {
        $baseUrl = $_ENV['APP_URL'] ?? 'http://localhost/PaginaWebFLEX';
        return $baseUrl . '/' . ltrim($path, '/');
    }

    public static function route($name, $params = [])
    {
        // Implementar cuando se agregue routing con nombres
        return self::url($name);
    }

    public static function csrf()
    {
        return Session::getCsrf();
    }

    public static function old($key, $default = '')
    {
        return Session::get('old_input')[$key] ?? $default;
    }

    public static function errors($key = null)
    {
        $errors = Session::get('errors', []);
        
        if ($key === null) {
            return $errors;
        }
        
        return $errors[$key] ?? null;
    }

    public static function hasErrors($key = null)
    {
        $errors = self::errors();
        
        if ($key === null) {
            return !empty($errors);
        }
        
        return isset($errors[$key]);
    }
}