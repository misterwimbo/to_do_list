-- ============================================================================
-- Fichier : init database.sql
-- Description : Script d'initialisation de la base de données "todolist".
-- Ce script crée la table "tasks" pour gérer les tâches d'une liste à faire.
-- 
-- Table "tasks" :
--   - id         : Identifiant unique de la tâche (clé primaire, auto-incrémenté)
--   - title      : Titre ou description de la tâche (obligatoire)
--   - is_done    : Statut d'accomplissement de la tâche (0 = non fait, 1 = fait)
--   - created_at : Date et heure de création de la tâche (défaut : maintenant)
--
-- À utiliser pour initialiser la base de données "todolist".
-- ============================================================================
-- init database  

CREATE TABLE tasks (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  is_done TINYINT(1) DEFAULT 0,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);