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
            if(isset($this->request->post['submit'])) {
                $name = $this->request->post['name'];
                $password = $this->request->post['password'];
                if(empty($name) || empty($password)) {
                    $auth->generateError('login_error', array('Veuillez renseigner tous les champs.', 'red'), 'viewLogin');
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
                        $auth->generateError('login_error', array($e->getMessage(), 'red'), 'viewLogin');
                    }
                }
            } else {
                $router = new \mf\router\Router();
                header('Location:' . $router->urlFor('viewLogin'));
                exit;
            }
        }
    }

    public function signup() {
        $auth = new \mediaphoto\auth\MediaPhotoAuthentification();
        if($auth->logged_in) {
            $router = new \mf\router\Router();
            header('Location:' . $router->urlFor('home'));
            exit;
        } else {
            $view = new \mediaphoto\view\MediaPhotoView([]);
            return $view->render('renderViewSignup');
        }
    }

    public function checkSignup() {
        $auth = new \mediaphoto\auth\MediaPhotoAuthentification();
        if($auth->logged_in) {
            $router = new \mf\router\Router();
            header('Location:' . $router->urlFor('home'));
            exit;
        } else {
            if(isset($this->request->post['submit'])) {
                $username = $this->request->post['username'];
                $name = $this->request->post['name'];
                $password = $this->request->post['password'];
                $password_confirmation = $this->request->post['password_confirmation'];
                if(empty($username) || empty($name) || empty($password) || empty($password_confirmation)) {
                    $auth->generateError('signup_error', array('Veuillez renseigner tous les champs.', 'red'), 'viewSignup');
                } else {
                    try {
                        $username = filter_var($username, FILTER_SANITIZE_SPECIAL_CHARS);
                        $name = filter_var($name, FILTER_SANITIZE_SPECIAL_CHARS);
                        $password = filter_var($password, FILTER_SANITIZE_SPECIAL_CHARS);
                        $password_confirmation = filter_var($password_confirmation, FILTER_SANITIZE_SPECIAL_CHARS);

                        if($auth->passwordConfirmation($password, $password_confirmation)) {
                            $auth->createUser($username, $name, $password);

                            // Suppression du message d'erreur stocké en mémoire
                            unset($_SESSION['signup_error']);
                            
                            $router = new \mf\router\Router();
                            header('Location:' . $router->urlFor('home'));
                            exit;
                        } else {
                            $auth->generateError('signup_error', array('Les mots de passe ne corresspondent pas.', 'red'), 'viewSignup');
                        }
                    } catch(\mf\auth\exception\AuthentificationException $e) {
                        $auth->generateError('signup_error', array($e->getMessage(), 'red'), 'viewSignup');
                    }
                }
            } else {
                $router = new \mf\router\Router();
                header('Location:' . $router->urlFor('viewSignup'));
                exit;
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