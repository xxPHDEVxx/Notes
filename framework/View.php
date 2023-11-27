<?php

require_once 'Configuration.php';

class View
{

    private string $file;

    public function __construct(string $action) {
        $this->file = "view/view_$action.php";
    }

    //affiche la vue en lui passant les données reçues
    //sous forme de variables
    public function show(array $data = array()) : void {
        if (file_exists($this->file)) {
            extract($data);
            $web_root = Configuration::get("web_root");
            require $this->file;
        } else {
            throw new Exception("File '$this->file' does'nt exist");
        }
    }

}
