<?php
require_once "framework/Controller.php";

class ControllerMain extends Controller {
    public function index() : void {
        (new View("login"))->show();
    }
}