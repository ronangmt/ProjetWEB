<?php
// Database.php

require_once 'config.php';

class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $options = [
            // Mode d'erreur : les exceptions PHP sont levées en cas de problème
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            // Mode de récupération par défaut : récupération sous forme de tableau associatif
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            // Désactiver l'émulation des requêtes préparées (meilleures performances et sécurité)
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        
        try {
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (\PDOException $e) {
            // En cas d'échec de la connexion, le script s'arrête
            die("Erreur de connexion à la base de données : " . $e->getMessage());
        }
    }

    // Méthode pour obtenir l'instance de la connexion (Singleton)
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    // Méthode publique pour obtenir l'objet PDO
    public function getConnection() {
        return $this->pdo;
    }
}
?>