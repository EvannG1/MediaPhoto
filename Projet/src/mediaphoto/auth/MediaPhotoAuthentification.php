<?php

namespace mediaphoto\auth;

use \mediaphoto\model\User;

class MediaPhotoAuthentification extends \mf\auth\Authentification {

    const ACCESS_LEVEL_USER = 100;

    public function __construct()
    {
        parent::__construct();
    }

    public function createUser($username, $name, $pass, $level = self::ACCESS_LEVEL_USER) {
        if(User::where('nom', '=', $username)->first()) {
            throw new \mf\auth\exception\AuthentificationException("Ce nom d'utilisateur n'est pas disponible.");
        } else {
            $hashedPass = $this->hashPassword($pass);
            $user = new User();
            $user->nom = $username;
            $user->nom_complet = $name;
            $user->mdp = $hashedPass;
            $user->level = $level;
            $user->save();
            $this->updateSession($name, $level);
        }
    }

    public function passwordConfirmation($password, $password_confirmation) {
        if ($password == $password_confirmation)
        {
            return true;
        } else {
            return false;
        }
    }

    public function loginUser($name, $password) {
        $user = User::select()->where('nom', '=', $name)->first();
        if(!$user) {
            throw new \mf\auth\exception\AuthentificationException("Ce nom d'utilisateur n'existe pas.");
        } else {
            $this->login($user->nom, $user->mdp, $password, $user->level);
        }
    }

    public function generateError($error_name, $args, $route) {
        $_SESSION[$error_name] = $args;
        \mf\router\Router::executeRoute($route);
        unset($_SESSION[$error_name]);
    }
}