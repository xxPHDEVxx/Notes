<?php
require_once "framework/Controller.php";
require_once "framework/View.php";

class ControllerNote extends Controller {
    public function index() : void {
        $user = 
        (new View("notes"))->show();
    }
}