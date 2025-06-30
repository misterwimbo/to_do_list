<?php
require_once 'config/database.php';

/**
 * Classe pour gérer les tâches (CRUD operations)
 */
class TaskManager {
    private $pdo;
    
    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }
    
    /**
     * Récupérer toutes les tâches
     */
    public function getAllTasks() {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM tasks ORDER BY created_at DESC");
            $stmt->execute();
            $tasks = $stmt->fetchAll();
            
            // Décoder les entités HTML dans les titres pour corriger l'ancien encodage
            foreach ($tasks as &$task) {
                $task['title'] = html_entity_decode($task['title'], ENT_QUOTES, 'UTF-8');
            }
            
            return $tasks;
        } catch (PDOException $e) {
            throw new Exception('Erreur lors de la récupération des tâches : ' . $e->getMessage());
        }
    }
    
    /**
     * Ajouter une nouvelle tâche
     */
    public function addTask($title) {
        // Validation basique
        if (empty(trim($title))) {
            throw new Exception('Le titre de la tâche ne peut pas être vide');
        }
        
        if (strlen($title) > 255) {
            throw new Exception('Le titre de la tâche ne peut pas dépasser 255 caractères');
        }
        
        try {
            $stmt = $this->pdo->prepare("INSERT INTO tasks (title) VALUES (:title)");
            $stmt->bindParam(':title', $title, PDO::PARAM_STR);
            
            if ($stmt->execute()) {
                return $this->pdo->lastInsertId();
            }
            return false;
        } catch (PDOException $e) {
            throw new Exception('Erreur lors de l\'ajout de la tâche : ' . $e->getMessage());
        }
    }
    
    /**
     * Mettre à jour le statut d'une tâche (terminée/en cours)
     */
    public function toggleTask($id, $isDone) {
        // Validation de l'ID
        if (!is_numeric($id) || $id <= 0) {
            throw new Exception('ID de tâche invalide');
        }
        
        // Validation du statut
        $isDone = $isDone ? 1 : 0;
        
        try {
            $stmt = $this->pdo->prepare("UPDATE tasks SET is_done = :is_done WHERE id = :id");
            $stmt->bindParam(':is_done', $isDone, PDO::PARAM_INT);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception('Erreur lors de la mise à jour de la tâche : ' . $e->getMessage());
        }
    }
    
    /**
     * Mettre à jour le titre d'une tâche
     */
    public function updateTask($id, $title) {
        // Validation de l'ID
        if (!is_numeric($id) || $id <= 0) {
            throw new Exception('ID de tâche invalide');
        }
        
        // Validation du titre
        if (empty(trim($title))) {
            throw new Exception('Le titre de la tâche ne peut pas être vide');
        }
        
        if (strlen($title) > 255) {
            throw new Exception('Le titre de la tâche ne peut pas dépasser 255 caractères');
        }
        
        try {
            $stmt = $this->pdo->prepare("UPDATE tasks SET title = :title WHERE id = :id");
            $stmt->bindParam(':title', $title, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception('Erreur lors de la modification de la tâche : ' . $e->getMessage());
        }
    }
    
    /**
     * Supprimer une tâche
     */
    public function deleteTask($id) {
        // Validation de l'ID
        if (!is_numeric($id) || $id <= 0) {
            throw new Exception('ID de tâche invalide');
        }
        
        try {
            $stmt = $this->pdo->prepare("DELETE FROM tasks WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception('Erreur lors de la suppression de la tâche : ' . $e->getMessage());
        }
    }
    
    /**
     * Vérifier si une tâche existe
     */
    public function taskExists($id) {
        try {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM tasks WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Nettoyer les entités HTML encodées dans la base de données
     * Cette méthode corrige les données existantes qui ont été encodées avec htmlspecialchars
     */
    public function cleanEncodedTitles() {
        try {
            // Récupérer toutes les tâches
            $stmt = $this->pdo->prepare("SELECT id, title FROM tasks");
            $stmt->execute();
            $tasks = $stmt->fetchAll();
            
            $cleaned = 0;
            
            foreach ($tasks as $task) {
                $cleanTitle = html_entity_decode($task['title'], ENT_QUOTES, 'UTF-8');
                
                // Si le titre a changé après décodage, le mettre à jour
                if ($cleanTitle !== $task['title']) {
                    $updateStmt = $this->pdo->prepare("UPDATE tasks SET title = :title WHERE id = :id");
                    $updateStmt->bindParam(':title', $cleanTitle, PDO::PARAM_STR);
                    $updateStmt->bindParam(':id', $task['id'], PDO::PARAM_INT);
                    
                    if ($updateStmt->execute()) {
                        $cleaned++;
                    }
                }
            }
            
            return $cleaned;
        } catch (PDOException $e) {
            throw new Exception('Erreur lors du nettoyage des données : ' . $e->getMessage());
        }
    }
}
?>
