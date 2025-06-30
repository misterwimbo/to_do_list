$(document).ready(function() {
    // Variables globales
    let currentFilter = 'all';
    let editModal = null;
    let tasksTable = null;
    
    // Initialiser le modal Bootstrap quand il est disponible
    function initModal() {
        if (typeof bootstrap !== 'undefined' && document.getElementById('editTaskModal')) {
            editModal = new bootstrap.Modal(document.getElementById('editTaskModal'));
        }
    }
    
    // Initialiser DataTable
    function initDataTable() {
        if ($.fn.DataTable) {
            tasksTable = $('#tasksTable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json'
                },
                responsive: true,
                pageLength: 10,
                order: [[3, 'desc']], // Trier par date de création (desc)
                columnDefs: [
                    { orderable: false, targets: [0, 5] }, // Désactiver le tri sur les colonnes checkbox et actions
                    { className: 'text-center', targets: [0, 1, 5] }
                ],
                dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                     '<"row"<"col-sm-12"tr>>' +
                     '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>'
            });
        }
    }
    
    // Attendre que Bootstrap soit disponible
    if (typeof bootstrap !== 'undefined') {
        initModal();
    } else {
        setTimeout(initModal, 100);
    }
    
    // Initialiser DataTable après jQuery et DataTables sont chargés
    if (typeof $.fn.DataTable !== 'undefined') {
        initDataTable();
    } else {
        setTimeout(initDataTable, 200);
    }
    
    // Charger les tâches au démarrage
    loadTasks();
    
    // Charger le thème sauvegardé
    loadTheme();
    
    // === GESTION DES ÉVÉNEMENTS ===
    
    // Ajouter une nouvelle tâche
    $('#taskForm').on('submit', function(e) {
        e.preventDefault();
        const title = $('#taskTitle').val().trim();
        
        if (title) {
            addTask(title);
        }
    });
    
    // Basculer le statut d'une tâche (terminée/en cours)
    $(document).on('change', '.task-checkbox', function() {
        const taskId = $(this).data('id');
        const isChecked = $(this).is(':checked');
        toggleTask(taskId, isChecked);
    });
    
    // Supprimer une tâche
    $(document).on('click', '.delete-btn', function() {
        const taskId = $(this).data('id');
        const taskTitle = $(this).data('title');
        
        if (confirm(`Êtes-vous sûr de vouloir supprimer la tâche "${taskTitle}" ?`)) {
            deleteTask(taskId);
        }
    });
    
    // Modifier une tâche (ouvrir le modal)
    $(document).on('click', '.edit-btn', function() {
        const taskId = $(this).data('id');
        const taskTitle = $(this).data('title');
        
        $('#editTaskId').val(taskId);
        // Le titre vient maintenant directement non-encodé depuis l'API
        $('#editTaskTitle').val(taskTitle);
        
        // S'assurer que le modal est initialisé
        if (!editModal) {
            initModal();
        }
        
        if (editModal) {
            editModal.show();
        } else {
            // Fallback si Bootstrap n'est toujours pas disponible
            $('#editTaskModal').modal('show');
        }
    });
    
    // Sauvegarder la modification
    $('#editTaskForm').on('submit', function(e) {
        e.preventDefault();
        const taskId = $('#editTaskId').val();
        const newTitle = $('#editTaskTitle').val().trim();
        
        if (newTitle) {
            updateTask(taskId, newTitle);
        }
    });
    
    // Ancien gestionnaire pour compatibilité
    $('#saveEdit').on('click', function() {
        const taskId = $('#editTaskId').val();
        const newTitle = $('#editTaskTitle').val().trim();
        
        if (newTitle) {
            updateTask(taskId, newTitle);
        }
    });
    
    // Gestion des filtres DataTable
    $(document).on('click', '.filter-status', function() {
        $('.filter-status').removeClass('active');
        $(this).addClass('active');
        const status = $(this).data('status');
        
        if (status === 'all') {
            currentFilter = 'all';
        } else if (status === 'pending') {
            currentFilter = 'pending';
        } else if (status === 'completed') {
            currentFilter = 'done';
        }
        
        applyFilter();
    });
    
    // Gestion des filtres (ancien système pour compatibilité)
    $('.filter-btn').on('click', function() {
        $('.filter-btn').removeClass('active');
        $(this).addClass('active');
        currentFilter = $(this).data('filter');
        applyFilter();
    });
    
    // Changement de thème
    $('#themeSelector').on('change', function() {
        const theme = $(this).val();
        changeTheme(theme);
    });
    
    // === FONCTIONS AJAX ===
    
    // Charger toutes les tâches
    function loadTasks() {
        $.ajax({
            url: 'api.php',
            method: 'GET',
            data: { action: 'list' },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    displayTasks(response.data);
                    updateCounters(response.data);
                } else {
                    showError('Erreur lors du chargement des tâches');
                }
            },
            error: function() {
                showError('Erreur de connexion au serveur');
            }
        });
    }
    
    // Ajouter une nouvelle tâche
    function addTask(title) {
        $.ajax({
            url: 'api.php',
            method: 'POST',
            data: { 
                action: 'add',
                title: title
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#taskTitle').val(''); // Vider le champ
                    loadTasks(); // Recharger la liste
                    showSuccess('Tâche ajoutée avec succès !');
                } else {
                    showError(response.message || 'Erreur lors de l\'ajout');
                }
            },
            error: function() {
                showError('Erreur de connexion au serveur');
            }
        });
    }
    
    // Basculer le statut d'une tâche
    function toggleTask(taskId, isDone) {
        $.ajax({
            url: 'api.php',
            method: 'POST',
            data: { 
                action: 'toggle',
                id: taskId,
                is_done: isDone ? 1 : 0
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    loadTasks(); // Recharger pour mettre à jour l'affichage
                    showSuccess(isDone ? 'Tâche marquée comme terminée !' : 'Tâche remise en cours');
                } else {
                    showError(response.message || 'Erreur lors de la mise à jour');
                    // Remettre la checkbox dans son état précédent
                    $(`input[data-id="${taskId}"]`).prop('checked', !isDone);
                }
            },
            error: function() {
                showError('Erreur de connexion au serveur');
                $(`input[data-id="${taskId}"]`).prop('checked', !isDone);
            }
        });
    }
    
    // Mettre à jour une tâche
    function updateTask(taskId, newTitle) {
        $.ajax({
            url: 'api.php',
            method: 'POST',
            data: { 
                action: 'update',
                id: taskId,
                title: newTitle
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    if (editModal) {
                        editModal.hide();
                    } else {
                        $('#editTaskModal').modal('hide');
                    }
                    loadTasks();
                    showSuccess('Tâche modifiée avec succès !');
                } else {
                    showError(response.message || 'Erreur lors de la modification');
                }
            },
            error: function() {
                showError('Erreur de connexion au serveur');
            }
        });
    }
    
    // Supprimer une tâche
    function deleteTask(taskId) {
        $.ajax({
            url: 'api.php',
            method: 'POST',
            data: { 
                action: 'delete',
                id: taskId
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    loadTasks();
                    showSuccess('Tâche supprimée avec succès !');
                } else {
                    showError(response.message || 'Erreur lors de la suppression');
                }
            },
            error: function() {
                showError('Erreur de connexion au serveur');
            }
        });
    }
    
    // === FONCTIONS D'AFFICHAGE ===
    
    // Afficher les tâches
    function displayTasks(tasks) {
        if (!tasksTable) {
            // Si DataTable n'est pas encore initialisé, réessayer après un délai
            setTimeout(() => displayTasks(tasks), 100);
            return;
        }
        
        // Vider la table
        tasksTable.clear();
        
        // Ajouter les nouvelles données
        tasks.forEach(function(task) {
            const row = createTaskRow(task);
            tasksTable.row.add(row);
        });
        
        // Redessiner la table
        tasksTable.draw();
        
        // Mettre à jour les statistiques
        updateStatistics(tasks);
    }
    
    // Créer une ligne de tâche pour DataTable
    function createTaskRow(task) {
        const isCompleted = task.is_done == 1;
        const createdDate = new Date(task.created_at).toLocaleDateString('fr-FR');
        const updatedDate = task.updated_at ? new Date(task.updated_at).toLocaleDateString('fr-FR') : createdDate;
        
        const statusBadge = isCompleted 
            ? '<span class="badge bg-success"><i class="fas fa-check"></i> Terminée</span>'
            : '<span class="badge bg-warning"><i class="fas fa-clock"></i> En cours</span>';
        
        // Échapper le titre pour la sécurité lors de l'affichage
        const safeTitle = escapeHtml(task.title);
        
        const actions = `
            <div class="btn-group btn-group-sm" role="group">
                <button class="btn btn-outline-primary edit-btn" 
                        data-id="${task.id}" data-title="${task.title.replace(/"/g, '&quot;')}"
                        title="Modifier">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-outline-danger delete-btn" 
                        data-id="${task.id}" data-title="${task.title.replace(/"/g, '&quot;')}"
                        title="Supprimer">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;
        
        return [
            `<input type="checkbox" class="form-check-input task-checkbox" data-id="${task.id}" ${isCompleted ? 'checked' : ''}>`,
            statusBadge,
            `<span class="${isCompleted ? 'text-muted text-decoration-line-through' : ''}">${safeTitle}</span>`,
            createdDate,
            updatedDate,
            actions
        ];
    }
    
    // Appliquer le filtre pour DataTable
    function applyFilter() {
        if (!tasksTable) return;
        
        if (currentFilter === 'all') {
            tasksTable.column(1).search('').draw();
        } else if (currentFilter === 'pending') {
            tasksTable.column(1).search('En cours').draw();
        } else if (currentFilter === 'done') {
            tasksTable.column(1).search('Terminée').draw();
        }
    }
    
    // Mettre à jour les statistiques
    function updateStatistics(tasks) {
        const total = tasks.length;
        const completed = tasks.filter(task => task.is_done == 1).length;
        const pending = total - completed;
        const completionRate = total > 0 ? Math.round((completed / total) * 100) : 0;
        
        $('#totalTasks').text(total);
        $('#completedTasks').text(completed);
        $('#pendingTasks').text(pending);
        $('#completionRate').text(completionRate + '%');
    }
    
    // Mettre à jour les compteurs (pour compatibilité)
    function updateCounters(tasks) {
        updateStatistics(tasks);
    }
    
    // === GESTION DES THÈMES ===
    
    function changeTheme(theme) {
        document.documentElement.setAttribute('data-theme', theme);
        localStorage.setItem('todo-theme', theme);
    }
    
    function loadTheme() {
        const savedTheme = localStorage.getItem('todo-theme') || 'light';
        $('#themeSelector').val(savedTheme);
        changeTheme(savedTheme);
    }
    
    // === FONCTIONS UTILITAIRES ===
    
    // Échapper le HTML pour éviter les XSS
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    // Afficher un message de succès
    function showSuccess(message) {
        // Simple alert pour cette version, peut être amélioré avec des toasts
        // alert('✅ ' + message);
        console.log('Succès:', message);
    }
    
    // Afficher un message d'erreur
    function showError(message) {
        alert('❌ ' + message);
    }
});
