<?php
require_once "framework/Controller.php";

class ControllerTest extends Controller {
    public function index() : void {
        echo "<h1>Hello !</h1>";
    }
}