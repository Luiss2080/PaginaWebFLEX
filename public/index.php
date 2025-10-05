<?php

// Autoload de clases
spl_autoload_register(function ($class) {
    // Convertir namespace a ruta de archivo
    $classPath = str_replace(['\\', '_'], '/', $class);
    
    // Buscar en app/core primero
    $coreFile = __DIR__ . '/../app/core/' . $class . '.php';
    if (file_exists($coreFile)) {
        require_once $coreFile;
        return;
    }
    
    // Buscar en app/controllers
    if (strpos($class, 'Controller') !== false) {
        $file = __DIR__ . '/../app/controllers/' . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
    
    // Buscar en app/models
    $modelFile = __DIR__ . '/../app/models/' . $class . '.php';
    if (file_exists($modelFile)) {
        require_once $modelFile;
        return;
    }
    
    // Buscar en app/middlewares
    if (strpos($class, 'Middleware') !== false) {
        $file = __DIR__ . '/../app/middlewares/' . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
    
    // Buscar en app/services
    if (strpos($class, 'Service') !== false) {
        $file = __DIR__ . '/../app/services/' . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
    
    // Buscar en app/helpers
    $helperFile = __DIR__ . '/../app/helpers/' . $class . '.php';
    if (file_exists($helperFile)) {
        require_once $helperFile;
        return;
    }
});

// Cargar funciones auxiliares
require_once __DIR__ . '/../app/helpers/functions.php';

// Inicializar y ejecutar aplicación
try {
    $app = App::getInstance();
    $app->run();
} catch (Exception $e) {
    // Log del error
    error_log($e->getMessage());
    
    // Mostrar error según el entorno
    if ($_ENV['APP_DEBUG'] ?? false) {
        echo "<pre>";
        echo "Error: " . $e->getMessage() . "\n";
        echo "Archivo: " . $e->getFile() . "\n";
        echo "Línea: " . $e->getLine() . "\n";
        echo "Stack trace:\n" . $e->getTraceAsString();
        echo "</pre>";
    } else {
        echo "Ha ocurrido un error. Por favor, inténtelo más tarde.";
    }
}