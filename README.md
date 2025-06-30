# 📋 Gestionnaire de Tâches Pro

*Parce que même les tâches les plus simples méritent une application qui en jette* ✨

## 🚀 Description

Une application web moderne de gestion de tâches développée avec soin et une pointe de perfectionnisme. Cette petite merveille vous permet de créer, modifier, supprimer et organiser vos tâches avec style, tout en gardant un œil sur vos statistiques de productivité.

*Disclaimer : Cette app ne vous fera pas aimer le lundi matin, mais au moins vous pourrez cocher "Survivre au lundi" de votre liste !*

## 🛠️ Technologies Utilisées

### Backend
- **PHP 8.x** - Le serveur qui ne dort jamais
- **MySQL/MariaDB** - Pour stocker vos tâches (et vos procrastinations)
- **PDO** - Connexion base de données sécurisée
- **Architecture MVC simplifiée** - Parce qu'on aime quand c'est bien organisé

### Frontend
- **HTML5** - La base, tout simplement
- **Bootstrap 5.3.2** - Pour que ça soit beau sans effort
- **jQuery 3.7.1** - Le vieux de la vieille qui fonctionne toujours
- **DataTables** - Parce que les tableaux méritent mieux qu'une simple `<table>`
- **Font Awesome 6.4** - Les icônes qui font la différence
- **CSS3 personnalisé** - 3 thèmes modernes et sobres (fini le flashy !)

### Fonctionnalités
- ✅ **CRUD complet** - Create, Read, Update, Delete (les 4 piliers de la productivité)
- 🎨 **3 thèmes** - Clair, Sombre, Bleu (pour tous les goûts et toutes les heures)
- 📊 **Statistiques en temps réel** - Parce qu'on aime voir nos progrès
- 🔍 **Filtres avancés** - Toutes, En cours, Terminées
- 📱 **Design responsive** - Ça marche sur votre téléphone aussi !
- 🔒 **Sécurité XSS** - Protection contre les méchants
- ⚡ **AJAX** - Pas de rechargement de page (on n'est plus en 2005)

## 📦 Installation

### Prérequis
- **Laragon** (ou XAMPP/WAMP si vous êtes nostalgique)
- **PHP 8.0+** 
- **MySQL 5.7+** ou **MariaDB 10.3+**
- Un navigateur qui date de moins de 10 ans

### Étapes d'installation

#### 1. Cloner/Télécharger le projet
```bash
# Si vous utilisez Git (les pros)
git clone [url-du-repo] C:\laragon\www\to_do_list

# Ou simplement extraire le ZIP dans C:\laragon\www\to_do_list
```

#### 2. Configurer la base de données

1. **Démarrer Laragon** (le bouton "Start All" est votre ami)

2. **Créer la base de données** :
   - Ouvrir phpMyAdmin : `http://localhost/phpmyadmin`
   - Créer une nouvelle base : `todo_app`
   - Importer le fichier : `database/create_database.sql`

3. **Configurer la connexion** :
   - Ouvrir `config/database.php`
   - Vérifier/modifier les paramètres :
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'todo_app');
   define('DB_USER', 'root');
   define('DB_PASS', ''); // Vide par défaut avec Laragon
   ```

#### 3. Nettoyer les données (si nécessaire)

Si vous avez des caractères spéciaux qui s'affichent bizarrement (genre `l&#039;emploi`), exécutez :

```bash
# Dans le terminal, se placer dans le dossier du projet
cd C:\laragon\www\to_do_list

# Lancer le script de nettoyage
php clean_data.php
```

#### 4. Accéder à l'application

Ouvrir votre navigateur préféré et aller sur :
```
http://localhost/to_do_list
```

*Et voilà ! Vous êtes prêt à procrastiner... euh, à être productif !*

## 🎯 Utilisation

### Interface principale
- **Ajouter une tâche** : Tapez dans le champ en haut et cliquez "Ajouter"
- **Marquer comme terminée** : Cochez la case à côté de la tâche
- **Modifier** : Cliquez sur l'icône crayon
- **Supprimer** : Cliquez sur l'icône poubelle
- **Filtrer** : Utilisez les boutons "Toutes", "En cours", "Terminées"
- **Changer de thème** : Menu déroulant en haut à droite

### Raccourcis clavier
- **Entrée** dans le champ d'ajout = Ajouter la tâche
- **Échap** dans le modal d'édition = Fermer sans sauvegarder

## 🗂️ Structure du projet

```
to_do_list/
├── 📁 classes/
│   └── TaskManager.php          # Logique métier des tâches
├── 📁 config/
│   └── database.php             # Configuration BDD
├── 📁 css/
│   ├── style.css                # Styles personnalisés
│   └── themes.css               # Les 3 thèmes sobres
├── 📁 database/
│   └── create_database.sql      # Structure de la BDD
├── 📁 js/
│   └── app.js                   # Logique frontend
├── 📄 api.php                   # API REST pour AJAX
├── 📄 index.html                # Interface utilisateur
├── 📄 clean_data.php            # Script de nettoyage
└── 📄 README.md                 # Ce magnifique fichier
```

## 🔧 Personnalisation

### Ajouter un nouveau thème
1. Modifiez `css/themes.css`
2. Ajoutez vos variables CSS dans un nouveau bloc `[data-theme="montheme"]`
3. Ajoutez l'option dans le sélecteur de thème (`index.html`)

### Modifier les couleurs
Toutes les couleurs sont définies via des variables CSS dans `themes.css`. 
Pas besoin de chercher dans 50 fichiers !

## 🐛 Dépannage

### "Erreur de connexion à la base de données"
- Vérifiez que Laragon est démarré
- Contrôlez les paramètres dans `config/database.php`
- Assurez-vous que la base `todo_app` existe

### "Les caractères spéciaux s'affichent mal"
- Exécutez `php clean_data.php`
- Vérifiez que votre BDD est en UTF-8

### "DataTable ne s'affiche pas"
- Vérifiez votre connexion internet (pour les CDN)
- Regardez la console du navigateur (F12)

### "Ça marche pas du tout"
- Café ☕
- Redémarrage de Laragon
- Vérification des logs d'erreur PHP
- Encore du café ☕☕

## 📝 Notes du développeur

Cette application a été conçue avec amour, quelques nuits blanches, et beaucoup de refactoring. 

*Fun fact* : Il y a eu plus de commits pour corriger l'affichage des apostrophes que pour toutes les autres fonctionnalités réunies. Les détails comptent !

## 🤝 Contribution

Les pull requests sont les bienvenues ! Surtout si vous avez des idées pour :
- Améliorer le responsive
- Ajouter des animations plus subtiles
- Optimiser les performances
- Corriger mes fautes de frappe dans ce README 😅

## 📄 Licence

Ce projet est sous licence "Faites-en ce que vous voulez, mais si ça casse, c'est pas ma faute" (MIT License).

---

*Développé avec ❤️ et une quantité déraisonnable de café*

**Version** : 1.0.0  
**Dernière mise à jour** : Juin 2025

