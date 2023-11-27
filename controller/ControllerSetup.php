<?php
require_once "framework/Controller.php";
require_once "framework/Configuration.php";
require_once "framework/Tools.php";

class ControllerSetup extends Controller {
    public function index() : void {
        $this->install();
    }
    
    public function install() : void {
        echo "<p>Importation des données en cours...</p>";
        try {
            $webroot = Configuration::get("web_root");
            $dbtype = Configuration::get("dbtype");
            $dbhost = Configuration::get("dbhost");
            $dbname = Configuration::get("dbname");
            $dbuser = Configuration::get("dbuser");
            $dbpassword = Configuration::get("dbpassword");
            $pdo = new PDO("{$dbtype}:host={$dbhost};charset=utf8", $dbuser, $dbpassword);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = file_get_contents("database/{$dbname}.sql");
            $query = $pdo->prepare($sql);
            
            if($query->execute()) {
                echo "<p>La base de données a été correctement créée</p>";
            } else {
                echo "<p>Problème lors de la création de la base de données</p>";
            }
            
            if(file_exists("database/{$dbname}_dump.sql")) {
                $sql = file_get_contents("database/{$dbname}_dump.sql");
                $query = $pdo->prepare($sql);

                if($query->execute()) {
                    echo "<p>Les données correctement importées</p>";
                } else {
                    echo "<p>Problème lors de l'importation des données</p>";
                }
            }
            echo "<a href='{$webroot}'>Retour à l'index</a>";
        }
        catch (Exception $exc) {
            Tools::abort("Erreur lors de l'accès à la base de données : ".$exc->getMessage());
        }
        
    }

    
    public function export() : void {
        echo "<p>Exportation des données en cours...</p>";
        //from https://gist.github.com/micc83/fe6b5609b3a280e5516e2a3e9f633675
        $mysql_path = Configuration::get("mysql_path");
        $webroot = Configuration::get("web_root");
        $dbhost = Configuration::get("dbhost");
        $dbname = Configuration::get("dbname");
        $dbuser = Configuration::get("dbuser");
        $dbpassword = Configuration::get("dbpassword");
        
        $file = dirname(__FILE__) . "/../database/{$dbname}_dump.sql"; 
        
        $output = [];
        exec("{$mysql_path}mysqldump --user={$dbuser} --password={$dbpassword} --host={$dbhost} {$dbname} --result-file={$file} 2>&1", $output);
        if(count($output) == 0){
            echo "<p>Les données ont été importées dans le fichier `<code>{$file}</code>`</p>";
        } else {
            throw new Exception(json_encode($output));
        }
        echo "<a href='{$webroot}'>Retour à l'index</a>";
    }
}

