<?php

require_once 'Configuration.php';

abstract class Model {

    private static ?PDO $pdo = null;

    //se connecte à la DB et renvoie son instance PDO
    //si on est déjà connecté, renvoie l'instance existante
    private static function connect() : PDO {
        if (self::$pdo == null) {
            $dbtype = Configuration::get("dbtype");
            $dbhost = Configuration::get("dbhost");
            $dbname = Configuration::get("dbname");
            $dsn = "{$dbtype}:host={$dbhost};dbname={$dbname};charset=utf8";
            $dbuser = Configuration::get("dbuser");
            $dbpassword = Configuration::get("dbpassword");

            self::$pdo = new PDO($dsn, $dbuser, $dbpassword);
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$pdo->query("SET sql_mode=(SELECT CONCAT(@@sql_mode,',ONLY_FULL_GROUP_BY'));");
        }
        return self::$pdo;
    }

    //exécute une requête 
    protected static function execute(string $sql, array $params) : PDOStatement {
        $stmt = self::connect()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    //renvoie l'ID de la dernière insertion
    protected static function lastInsertId() : int {
        return self::connect()->lastInsertId();
    }



}
