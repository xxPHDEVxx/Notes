<?php

require_once 'Configuration.php';

abstract class Controller {

    public function __construct() {
        session_start();
    }

    //connecte l'utilisateur donné et redirige vers la page d'acceuil
    protected function log_user(object $member, string $controller = "", string $action = "index") : void {
        $_SESSION["user"] = $member;
        $this->redirect($controller, $action);
        //see http://codingexplained.com/coding/php/solving-concurrent-request-blocking-in-php
        session_write_close();
    }

    //déconnecte l'utilisateur et redirige vers l'accueil
    public function logout() : void {
        $_SESSION = array();
        session_destroy();
        $this->redirect();
    }

    /*
     * Redirige le navigateur vers l'action demandée.
     * Remarque : si un des paramètres est vide (null ou string vide), les suivants sont ignorés.
     */

    public function redirect(string $controller = "", string $action = "index", string $param1 = "", string $param2 = "", string $param3 = "", int $statusCode = 303) : void {
        $web_root = Configuration::get("web_root");
        $default_controller = Configuration::get("default_controller");
        if (empty($controller)) {
            $controller = $default_controller;
        }

        $header = "Location: $web_root$controller/$action";
        if (!empty($param1)) {
            $header .= "/$param1";
            if (!empty($param2)) {
                $header .= "/$param2";
                if (!empty($param3)) {
                    $header .= "/$param3";
                }
            }
        }
        header($header, true, $statusCode);
        die();
    }

    //indique si un l'utilisateur est connecté
    public function user_logged() : bool
    {
        return isset($_SESSION['user']);
    }

    //renvoie l'utilisateur connecté ou false personne n'est connecté
    public function get_user_or_false() : object|false
    {
        return $this->user_logged() ? $_SESSION['user'] : false;
    }

    //renvoie l'utilisateur connecté ou redige vers l'accueil
    public function get_user_or_redirect() : object
    {
        $user = $this->get_user_or_false();
        if ($user === FALSE) 
            $this->redirect();
        return $user;
        
    }

    //tout controlleur doit posséder une méthode index, c'est son action
    //par défaut
    public abstract function index() : void;
}
