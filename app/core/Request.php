<?php

class Request
{
    private $method;
    private $uri;
    private $params = [];
    private $query = [];
    private $post = [];
    private $files = [];
    private $headers = [];

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $this->uri = $this->parseUri();
        $this->query = $_GET ?? [];
        $this->post = $_POST ?? [];
        $this->files = $_FILES ?? [];
        $this->headers = $this->parseHeaders();
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function getQuery($key = null, $default = null)
    {
        if ($key === null) {
            return $this->query;
        }
        return $this->query[$key] ?? $default;
    }

    public function getPost($key = null, $default = null)
    {
        if ($key === null) {
            return $this->post;
        }
        return $this->post[$key] ?? $default;
    }

    public function getFiles($key = null)
    {
        if ($key === null) {
            return $this->files;
        }
        return $this->files[$key] ?? null;
    }

    public function getHeader($name)
    {
        return $this->headers[strtolower($name)] ?? null;
    }

    public function isPost()
    {
        return $this->method === 'POST';
    }

    public function isGet()
    {
        return $this->method === 'GET';
    }

    public function isAjax()
    {
        return strtolower($this->getHeader('X-Requested-With')) === 'xmlhttprequest';
    }

    public function setParams($params)
    {
        $this->params = $params;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function getParam($key, $default = null)
    {
        return $this->params[$key] ?? $default;
    }

    private function parseUri()
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        
        // Remover query string
        if (($pos = strpos($uri, '?')) !== false) {
            $uri = substr($uri, 0, $pos);
        }
        
        // Remover el directorio del proyecto si existe
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $basePath = dirname(dirname($scriptName)); // Remover /public/index.php
        
        if ($basePath !== '/' && strpos($uri, $basePath) === 0) {
            $uri = substr($uri, strlen($basePath));
        }
        
        // Remover /public si está presente
        if (strpos($uri, '/public') === 0) {
            $uri = substr($uri, 7);
        }
        
        // Normalizar URI
        $uri = trim($uri, '/');
        return $uri === '' ? '/' : '/' . $uri;
    }

    private function parseHeaders()
    {
        $headers = [];
        
        if (function_exists('getallheaders')) {
            foreach (getallheaders() as $name => $value) {
                $headers[strtolower($name)] = $value;
            }
        } else {
            foreach ($_SERVER as $key => $value) {
                if (substr($key, 0, 5) === 'HTTP_') {
                    $header = str_replace('_', '-', substr($key, 5));
                    $headers[strtolower($header)] = $value;
                }
            }
        }
        
        return $headers;
    }
}