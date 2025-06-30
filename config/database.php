<?php
/**
 * Configuration de la base de données
 * Modifiez ces valeurs selon votre environnement
 */
class DatabaseConfig {
    const HOST = 'localhost';
    const DB_NAME = 'to_do_list';
    const USERNAME = 'root';
    const PASSWORD = '';
    const CHARSET = 'utf8mb4';
}

/**
 * Classe pour gérer la connexion à la base de données
 */
class Database {
    private static $instance = null;
    private $pdo;
    
    private function __construct() {
        $dsn = 'mysql:host=' . DatabaseConfig::HOST . ';dbname=' . DatabaseConfig::DB_NAME . ';charset=' . DatabaseConfig::CHARSET;
        
        try {
            $this->pdo = new PDO($dsn, DatabaseConfig::USERNAME, DatabaseConfig::PASSWORD, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]);
        } catch (PDOException $e) {
            die('Erreur de connexion à la base de données : ' . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->pdo;
    }
}
?>
