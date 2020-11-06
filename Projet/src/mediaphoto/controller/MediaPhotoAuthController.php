<?php

namespace mediaphoto\controller;

class MediaPhotoAuthController extends \mf\control\AbstractController {
    public function __construct()
    {
        parent::__construct();
    }

    public function login() {
        $auth = new \mediaphoto\auth\MediaPhotoAuthentification();
        if($auth->logged_in) {
            $router = new \mf\router\Router();
            header('Location:' . $router->urlFor('home'));
            exit;
        } else {
            $view = new \mediaphoto\view\MediaPhotoView([]);
            return $view->render('renderViewLogin');
        }
    }

    public function checkLogin() {
        $auth = new \mediaphoto\auth\MediaPhotoAuthentification();
        if($auth->logged_in) {
            $router = new \mf\router\Router();
            header('Location:' . $router->urlFor('home'));
            exit;
        } else {
            $name = $this->request->post['name'];
            $password = $this->request->post['password'];

            if(isset($name) && isset($password)) {
                if(empty($name) || empty($password)) {
                    $_SESSION['login_error'] = array('Veuillez renseigner tous les champs.', 'red');
                    \mf\router\router::executeRoute('viewLogin');
                    unset($_SESSION['login_error']);
                } else {
                    try {
                        $name = filter_var($name, FILTER_SANITIZE_SPECIAL_CHARS);
                        $password = filter_var($password, FILTER_SANITIZE_SPECIAL_CHARS);
    
                        $auth->loginUser($name, $password);
    
                        // Suppression du message d'erreur stocké en mémoire
                        unset($_SESSION['login_error']);
    
                        $router = new \mf\router\Router();
                        header('Location:' . $router->urlFor('home'));
                        exit;
                    } catch (\mf\auth\exception\AuthentificationException $e) {
                        $_SESSION['login_error'] = array($e->getMessage(), 'red');
                        \mf\router\router::executeRoute('viewLogin');
                        unset($_SESSION['login_error']);
                    }
                }
            } else {
                $_SESSION['login_error'] = array('Erreur lors tu traitement de vos données, veuillez réessayer plus tard', 'red');
                \mf\router\router::executeRoute('viewLogin');
                unset($_SESSION['login_error']);
            }
        }
    }

    public function logout() {
        $auth = new \mediaphoto\auth\MediaPhotoAuthentification();
        $auth->logout();
        $router = new \mf\router\Router();
        header('Location:' . $router->urlFor('home'));
        exit;
    }
}