<?php

class PageController extends Controller
{
    public function about()
    {
        $data = [
            'title' => 'About Us - Zay Shop',
            'meta_description' => 'Learn more about Zay Shop and our mission'
        ];

        return $this->renderWithLayout('pages/about', $data);
    }

    public function contact()
    {
        $data = [
            'title' => 'Contact Us - Zay Shop',
            'meta_description' => 'Get in touch with Zay Shop'
        ];

        return $this->renderWithLayout('pages/contact', $data);
    }

    public function sendContact()
    {
        // Validar CSRF
        $token = $_POST['csrf_token'] ?? '';
        if (!Session::validateCsrf($token)) {
            Session::flash('error', 'Token de seguridad inválido');
            return $this->redirect('/contact');
        }
        
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $subject = trim($_POST['subject'] ?? '');
        $message = trim($_POST['message'] ?? '');

        // Guardar datos para repoblar el formulario
        Session::set('old_name', $name);
        Session::set('old_email', $email);
        Session::set('old_subject', $subject);
        Session::set('old_message', $message);

        // Validación básica
        if (empty($name) || empty($email) || empty($subject) || empty($message)) {
            Session::flash('error', 'Por favor complete todos los campos');
            return $this->redirect('/contact');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Session::flash('error', 'Por favor ingrese un email válido');
            return $this->redirect('/contact');
        }

        // Aquí iría la lógica para enviar el email
        // Por ahora guardamos en log
        $this->saveContactMessage($name, $email, $subject, $message);
        
        // Limpiar datos del formulario
        Session::remove('old_name');
        Session::remove('old_email');
        Session::remove('old_subject');
        Session::remove('old_message');
        
        Session::flash('success', 'Mensaje enviado correctamente. Nos pondremos en contacto contigo pronto.');
        return $this->redirect('/contact');
    }
    
    private function saveContactMessage($name, $email, $subject, $message)
    {
        $logMessage = "[" . date('Y-m-d H:i:s') . "] Contact Form Submission\n";
        $logMessage .= "Name: $name\n";
        $logMessage .= "Email: $email\n";
        $logMessage .= "Subject: $subject\n";
        $logMessage .= "Message: $message\n";
        $logMessage .= "IP: " . ($_SERVER['REMOTE_ADDR'] ?? 'Unknown') . "\n\n";
        
        $logFile = dirname(__DIR__, 2) . '/logs/contact.log';
        file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX);
    }

    public function terms()
    {
        $data = [
            'title' => 'Terms & Conditions - Zay Shop',
            'meta_description' => 'Terms and conditions of use for Zay Shop'
        ];

        return $this->renderWithLayout('pages/terms', $data);
    }

    public function privacy()
    {
        $data = [
            'title' => 'Privacy Policy - Zay Shop',
            'meta_description' => 'Privacy policy for Zay Shop'
        ];

        return $this->renderWithLayout('pages/privacy', $data);
    }

    public function faq()
    {
        $data = [
            'title' => 'FAQ - Zay Shop',
            'meta_description' => 'Frequently asked questions'
        ];

        return $this->renderWithLayout('pages/faq', $data);
    }
}