<?php

namespace App\Controllers;

use Core\Auth;

class BaseController
{
    protected $viewPath = __DIR__ . '/../../resources/views/';

    protected function view($view, $data = [])
    {
        $viewFile = $this->viewPath . str_replace('.', '/', $view) . '.php';
        
        if (!file_exists($viewFile)) {
            die("Vue non trouvée: $view");
        }
        
        extract($data);
        include $viewFile;
    }

    protected function render($html)
    {
        echo $html;
    }

    protected function redirect($path, $message = null)
    {
        if ($message) {
            $_SESSION['message'] = $message;
        }
        header("Location: $path");
        exit;
    }

    protected function json($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
        exit;
    }

    protected function requireAuth()
    {
        if (!Auth::check()) {
            $this->redirect('/login', 'Veuillez vous connecter');
        }
    }

    protected function authorize($permission)
    {
        $this->requireAuth();
        if (!Auth::can($permission)) {
            http_response_code(403);
            die('Accès refusé');
        }
    }

    protected function validate($data, $rules)
    {
        $errors = [];
        
        foreach ($rules as $field => $fieldRules) {
            $rules_array = explode('|', $fieldRules);
            
            foreach ($rules_array as $rule) {
                if (strpos($rule, ':') !== false) {
                    [$ruleName, $ruleValue] = explode(':', $rule);
                } else {
                    $ruleName = $rule;
                    $ruleValue = null;
                }
                
                $value = $data[$field] ?? '';
                
                switch ($ruleName) {
                    case 'required':
                        if (empty($value)) {
                            $errors[$field] = "$field est obligatoire";
                        }
                        break;
                    case 'email':
                        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                            $errors[$field] = "$field doit être une adresse email valide";
                        }
                        break;
                    case 'min':
                        if (strlen($value) < $ruleValue) {
                            $errors[$field] = "$field doit contenir au moins $ruleValue caractères";
                        }
                        break;
                    case 'numeric':
                        if (!is_numeric($value)) {
                            $errors[$field] = "$field doit être numérique";
                        }
                        break;
                }
            }
        }
        
        return $errors;
    }
}
