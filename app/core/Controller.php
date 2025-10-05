<?php

class Controller
{
    protected $view;
    protected $request;
    protected $response;

    public function __construct()
    {
        $this->view = new View();
        $this->request = App::getInstance()->getRequest();
        $this->response = App::getInstance()->getResponse();
    }

    /**
     * Renderizar vista
     */
    protected function render($view, $data = [])
    {
        return $this->view->render($view, $data);
    }

    /**
     * Renderizar vista con layout
     */
    protected function renderWithLayout($view, $data = [], $layout = 'layouts/main')
    {
        return $this->view->renderWithLayout($view, $data, $layout);
    }

    /**
     * Redireccionar
     */
    protected function redirect($url, $statusCode = 302)
    {
        $this->response->redirect($url, $statusCode);
    }

    /**
     * Respuesta JSON
     */
    protected function json($data, $statusCode = 200)
    {
        return $this->response->json($data, $statusCode);
    }

    /**
     * Verificar si es petición AJAX
     */
    protected function isAjax()
    {
        return $this->request->isAjax();
    }

    /**
     * Obtener datos POST
     */
    protected function getPost($key = null, $default = null)
    {
        return $this->request->getPost($key, $default);
    }

    /**
     * Obtener datos GET
     */
    protected function getQuery($key = null, $default = null)
    {
        return $this->request->getQuery($key, $default);
    }

    /**
     * Validar token CSRF
     */
    protected function validateCsrf()
    {
        $token = $this->getPost('csrf_token');
        if (!Session::validateCsrf($token)) {
            throw new Exception('Token CSRF inválido');
        }
    }
}