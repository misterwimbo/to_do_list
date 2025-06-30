<?php
/**
 * API REST simple pour la gestion des tâches
 * Gère les requêtes AJAX du frontend
 */

// Configuration des en-têtes pour les réponses JSON et CORS
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Gestion des requêtes OPTIONS (CORS preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Inclusion des classes nécessaires
require_once 'classes/TaskManager.php';

/**
 * Classe pour gérer les réponses de l'API
 */
class ApiResponse {
    public static function success($data = null, $message = '') {
        return [
            'success' => true,
            'data' => $data,
            'message' => $message
        ];
    }
    
    public static function error($message, $code = 400) {
        http_response_code($code);
        return [
            'success' => false,
            'message' => $message
        ];
    }
}

/**
 * Fonction pour nettoyer et valider les données d'entrée
 * Ne fait que nettoyer sans encoder (l'encodage se fait à l'affichage)
 */
function sanitizeInput($data) {
    if (is_array($data)) {
        return array_map('sanitizeInput', $data);
    }
    // Nettoyer les espaces et retours à la ligne, mais ne pas encoder
    return trim($data);
}

/**
 * Fonction pour échapper les données de sortie (protection XSS)
 */
function escapeOutput($data) {
    if (is_array($data)) {
        return array_map('escapeOutput', $data);
    }
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

/**
 * Validation simple du token CSRF (protection basique)
 */
function validateCSRF() {
    // Pour cette version simple, on utilise une validation basique
    // En production, utilisez un vrai système de tokens CSRF
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $referer = $_SERVER['HTTP_REFERER'] ?? '';
    
    // Vérification basique : la requête doit venir du même domaine
    if (!empty($referer)) {
        $host = $_SERVER['HTTP_HOST'] ?? '';
        if (strpos($referer, $host) === false) {
            return false;
        }
    }
    
    return true;
}

// Début du traitement de la requête
try {
    // Protection CSRF basique
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !validateCSRF()) {
        echo json_encode(ApiResponse::error('Requête non autorisée', 403));
        exit();
    }
    
    // Récupération et nettoyage de l'action demandée
    $action = sanitizeInput($_REQUEST['action'] ?? '');
    
    if (empty($action)) {
        echo json_encode(ApiResponse::error('Action non spécifiée'));
        exit();
    }
    
    // Initialisation du gestionnaire de tâches
    $taskManager = new TaskManager();
    $response = null;
    
    // Traitement selon l'action demandée
    switch ($action) {
        case 'list':
            // Récupérer toutes les tâches
            $tasks = $taskManager->getAllTasks();
            $response = ApiResponse::success($tasks);
            break;
            
        case 'add':
            // Ajouter une nouvelle tâche
            $title = sanitizeInput($_POST['title'] ?? '');
            
            if (empty($title)) {
                $response = ApiResponse::error('Le titre de la tâche est obligatoire');
            } else {
                $taskId = $taskManager->addTask($title);
                if ($taskId) {
                    $response = ApiResponse::success(['id' => $taskId], 'Tâche ajoutée avec succès');
                } else {
                    $response = ApiResponse::error('Erreur lors de l\'ajout de la tâche');
                }
            }
            break;
            
        case 'toggle':
            // Basculer le statut d'une tâche
            $id = (int)sanitizeInput($_POST['id'] ?? 0);
            $isDone = (int)sanitizeInput($_POST['is_done'] ?? 0);
            
            if ($id <= 0) {
                $response = ApiResponse::error('ID de tâche invalide');
            } elseif (!$taskManager->taskExists($id)) {
                $response = ApiResponse::error('Tâche introuvable');
            } else {
                $success = $taskManager->toggleTask($id, $isDone);
                if ($success) {
                    $status = $isDone ? 'terminée' : 'en cours';
                    $response = ApiResponse::success(null, "Tâche marquée comme $status");
                } else {
                    $response = ApiResponse::error('Erreur lors de la mise à jour');
                }
            }
            break;
            
        case 'update':
            // Mettre à jour le titre d'une tâche
            $id = (int)sanitizeInput($_POST['id'] ?? 0);
            $title = sanitizeInput($_POST['title'] ?? '');
            
            if ($id <= 0) {
                $response = ApiResponse::error('ID de tâche invalide');
            } elseif (empty($title)) {
                $response = ApiResponse::error('Le titre de la tâche est obligatoire');
            } elseif (!$taskManager->taskExists($id)) {
                $response = ApiResponse::error('Tâche introuvable');
            } else {
                $success = $taskManager->updateTask($id, $title);
                if ($success) {
                    $response = ApiResponse::success(null, 'Tâche modifiée avec succès');
                } else {
                    $response = ApiResponse::error('Erreur lors de la modification');
                }
            }
            break;
            
        case 'delete':
            // Supprimer une tâche
            $id = (int)sanitizeInput($_POST['id'] ?? 0);
            
            if ($id <= 0) {
                $response = ApiResponse::error('ID de tâche invalide');
            } elseif (!$taskManager->taskExists($id)) {
                $response = ApiResponse::error('Tâche introuvable');
            } else {
                $success = $taskManager->deleteTask($id);
                if ($success) {
                    $response = ApiResponse::success(null, 'Tâche supprimée avec succès');
                } else {
                    $response = ApiResponse::error('Erreur lors de la suppression');
                }
            }
            break;
            
        case 'clean':
            // Nettoyer les entités HTML encodées dans la base de données
            try {
                $cleaned = $taskManager->cleanEncodedTitles();
                $response = ApiResponse::success(['cleaned' => $cleaned], 
                    $cleaned > 0 ? "$cleaned tâche(s) nettoyée(s) avec succès" : "Aucune tâche à nettoyer");
            } catch (Exception $e) {
                $response = ApiResponse::error('Erreur lors du nettoyage : ' . $e->getMessage());
            }
            break;
            
        default:
            $response = ApiResponse::error('Action non reconnue');
            break;
    }
    
    // Envoi de la réponse
    echo json_encode($response);
    
} catch (Exception $e) {
    // Gestion des erreurs
    echo json_encode(ApiResponse::error('Erreur serveur : ' . $e->getMessage(), 500));
}
?>
