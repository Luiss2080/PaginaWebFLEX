<?php

// Definir rutas de la aplicación
$routes = [
    // Página principal
    '' => 'HomeController@index',
    '/' => 'HomeController@index',
    'home' => 'HomeController@index',
    
    // Páginas estáticas
    'about' => 'PageController@about',
    'contact' => 'PageController@contact',
    
    // Productos
    'shop' => 'ProductController@index',
    'shop/category/{id}' => 'ProductController@category',
    'shop/product/{id}' => 'ProductController@show',
    
    // Cart
    'cart' => 'CartController@index',
    'cart/add' => 'CartController@add',
    'cart/update' => 'CartController@update',
    'cart/remove' => 'CartController@remove',
    
    // API endpoints
    'api/search' => 'ProductController@search',
    'api/cart/count' => 'CartController@count',
    
    // POST routes
    'contact/send' => 'PageController@sendContact',
];

return $routes;