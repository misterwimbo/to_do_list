# 📋 Gestionnaire de Tâches Pro

Une application moderne et intuitive de gestion de tâches développée en PHP, JavaScript et MySQL avec une interface utilisateur professionnelle.

## ✨ Fonctionnalités

- **Gestion complète des tâches** : Ajout, modification, suppression et basculement de statut
- **Interface moderne** : Design responsive avec Bootstrap 5 et thèmes personnalisables
- **Système de filtrage** : DataTables pour la recherche et le tri avancés
- **API REST** : Interface API complète pour les intégrations
- **Sécurité** : Protection CSRF et validation des données
- **Nettoyage automatique** : Outils de maintenance des données
- **Interface de test API** : Page dédiée pour tester l'API (style Swagger)

## 🛠 Technologies utilisées

- **Backend** : PHP 7.4+
- **Base de données** : MySQL 5.7+ / MariaDB
- **Frontend** : HTML5, CSS3, JavaScript (ES6+)
- **Frameworks** : Bootstrap 5, DataTables
- **Icônes** : Font Awesome 6
- **Architecture** : MVC pattern, API REST

## 📁 Structure du projet

```
to_do_list/
├── 📄 index.html              # Interface principale de l'application
├── 📄 api.php                 # API REST pour la gestion des tâches
├── 📄 apitest.php             # Interface de test API (style Swagger)
├── 📄 clean_data.php          # Script de nettoyage des données
├── 📄 php_infos.php           # Informations PHP (développement)
├── 📄 init database.sql       # Script d'initialisation de la BDD
├── 📄 README.md               # Documentation du projet
├── 📁 classes/
│   └── 📄 TaskManager.php     # Classe de gestion des tâches
├── 📁 config/
│   └── 📄 database.php        # Configuration de la base de données
├── 📁 css/
│   ├── 📄 style.css           # Styles principaux
│   └── 📄 themes.css          # Thèmes personnalisables
├── 📁 database/
│   └── 📄 create_database.sql # Script de création de la BDD
└── 📁 js/
    └── 📄 app.js              # Logique JavaScript de l'application
```

## 🚀 Installation

### Prérequis

- **Serveur web** : Apache 2.4+ ou Nginx
- **PHP** : Version 7.4 ou supérieure
- **Base de données** : MySQL 5.7+ ou MariaDB 10.3+
- **Extensions PHP** : PDO, PDO_MySQL

### Étapes d'installation

#### 1. Téléchargement du projet

```bash
# Cloner le repository ou télécharger les fichiers
# Placer le projet dans votre répertoire web (ex: htdocs, www)
```

#### 2. Configuration de la base de données

**Créer la base de données :**
```sql
CREATE DATABASE to_do_list CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

**Importer la structure :**
```bash
# Exécuter le script SQL
mysql -u root -p to_do_list < "init database.sql"
```

#### 3. Configuration PHP

Modifier le fichier `config/database.php` :

```php
class DatabaseConfig {
    const HOST = '127.0.0.1';
    const DB_NAME = 'to_do_list';
    const USERNAME = 'root';
    const PASSWORD = 'VOTRE_MOT_DE_PASSE'; // Remplacer par votre mot de passe
    const CHARSET = 'utf8mb4';
}
```

#### 4. Permissions (Linux/Mac)

```bash
# Donner les permissions appropriées
chmod 755 /chemin/vers/to_do_list
chmod 644 /chemin/vers/to_do_list/*.php
```

#### 5. Vérification de l'installation

1. Accéder à `http://localhost/to_do_list/` pour l'interface principale
2. Accéder à `http://localhost/to_do_list/apitest.php` pour tester l'API
3. Vérifier les informations PHP : `http://localhost/to_do_list/php_infos.php`

## 🎯 Utilisation

### Interface principale

1. **Ajouter une tâche** : Utiliser le formulaire en haut de la page
2. **Modifier une tâche** : Double-cliquer sur le titre ou utiliser le bouton d'édition
3. **Marquer comme terminée** : Cocher la case correspondante
4. **Supprimer une tâche** : Utiliser le bouton de suppression
5. **Filtrer/Rechercher** : Utiliser les outils DataTables intégrés

### API REST

L'application expose une API REST complète accessible via `api.php` :

- **GET** `?action=list` - Récupérer toutes les tâches
- **POST** `?action=add` - Ajouter une nouvelle tâche
- **POST** `?action=update` - Modifier une tâche existante
- **POST** `?action=toggle` - Basculer le statut d'une tâche
- **POST** `?action=delete` - Supprimer une tâche
- **POST** `?action=clean` - Nettoyer les entités HTML encodées

### Interface de test API

Accéder à `apitest.php` pour une interface de test style Swagger permettant de :
- Tester tous les endpoints de l'API
- Visualiser les réponses JSON
- Valider le fonctionnement de l'API
- **Note** : Un token d'authentification est requis pour utiliser l'interface de test

## 🔧 Maintenance

### Nettoyage des données

Exécuter `clean_data.php` pour nettoyer les entités HTML encodées dans la base de données.

### Sauvegarde

```bash
# Sauvegarder la base de données
mysqldump -u root -p to_do_list > backup_todolist.sql
```

## 🛡 Sécurité

- **Protection CSRF** : Validation des requêtes
- **Échappement des données** : Protection contre les injections XSS
- **Validation des entrées** : Nettoyage des données utilisateur
- **Requêtes préparées** : Protection contre les injections SQL

## 🐛 Dépannage

### Erreurs courantes

1. **Erreur de connexion à la base de données**
   - Vérifier les paramètres dans `config/database.php`
   - S'assurer que MySQL est démarré

2. **Erreur 500**
   - Consulter les logs d'erreur du serveur web
   - Vérifier les permissions des fichiers

3. **Interface non fonctionnelle**
   - Vérifier que JavaScript est activé
   - Contrôler la console du navigateur pour les erreurs

## 📝 Licence

Ce projet est fourni sous licence MIT. Voir le fichier LICENSE pour plus de détails.

## 👨‍💻 Développement

### Environnement de développement recommandé

- **XAMPP/WAMP/LARAGON** pour Windows
- **MAMP** pour macOS  
- **LAMP** pour Linux

### Contribution

Les contributions sont les bienvenues ! N'hésitez pas à :
1. Fork le projet
2. Créer une branche pour votre fonctionnalité
3. Commiter vos changements
4. Pousser vers la branche
5. Ouvrir une Pull Request

---

*Développé avec ❤️ et un peu de cafeine*

