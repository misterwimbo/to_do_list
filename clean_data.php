<?php
/**
 * Script utilitaire pour nettoyer les entités HTML encodées dans la base de données
 * Exécutez ce script une seule fois pour corriger les données existantes
 */

require_once 'classes/TaskManager.php';

try {
    $taskManager = new TaskManager();
    $cleaned = $taskManager->cleanEncodedTitles();
    
    echo "Nettoyage terminé !\n";
    echo "Nombre de tâches nettoyées : $cleaned\n";
    
    if ($cleaned > 0) {
        echo "Les caractères spéciaux ont été corrigés dans $cleaned tâche(s).\n";
    } else {
        echo "Aucune tâche n'avait besoin d'être nettoyée.\n";
    }
    
} catch (Exception $e) {
    echo "Erreur lors du nettoyage : " . $e->getMessage() . "\n";
}
?>
