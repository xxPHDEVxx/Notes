<?php
require_once "framework/Controller.php";
require_once "framework/View.php";

class ControllerMain extends Controller {
    public function index() : void {
        (new View("index"))->show();
    }
}