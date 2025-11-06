<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Gestion des Interviews - Football Gabonais</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --gabon-green: #009E60;
            --gabon-yellow: #FCD116;
            --gabon-blue: #3A75C4;
            --dark-green: #006B3F;
            --sidebar-width: 280px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e8eff5 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--gabon-green) 0%, var(--dark-green) 100%);
            box-shadow: 4px 0 20px rgba(0,0,0,0.15);
            z-index: 1000;
            overflow-y: auto;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-brand {
            padding: 2rem 1.5rem;
            border-bottom: 2px solid rgba(252, 209, 22, 0.3);
            background: linear-gradient(135deg, rgba(0,0,0,0.2) 0%, transparent 100%);
            text-align: center;
        }

        .sidebar-brand img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: 3px solid var(--gabon-yellow);
            padding: 5px;
            background: white;
            margin-bottom: 10px;
        }

        .sidebar-brand h3 {
            color: white;
            font-size: 1.4rem;
            font-weight: 700;
            margin: 0;
            text-shadow: 2px 2px 6px rgba(0,0,0,0.3);
        }

        .sidebar-brand .text-muted {
            color: var(--gabon-yellow) !important;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .nav-item {
            margin: 0.25rem 0.75rem;
        }

        .nav-link {
            color: rgba(255,255,255,0.85);
            padding: 0.95rem 1.2rem;
            border-radius: 0.75rem;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            font-weight: 500;
            font-size: 0.95rem;
            border: none;
            background: transparent;
            width: 100%;
            text-align: left;
            cursor: pointer;
            text-decoration: none;
        }

        .nav-link:hover {
            background: rgba(252, 209, 22, 0.2);
            color: white;
            transform: translateX(8px);
            box-shadow: 0 4px 12px rgba(252, 209, 22, 0.2);
        }

        .nav-link.active {
            background: linear-gradient(135deg, var(--gabon-yellow) 0%, #f0c000 100%);
            color: var(--dark-green);
            font-weight: 600;
            box-shadow: 0 6px 15px rgba(252, 209, 22, 0.4);
            transform: translateX(5px);
        }

        .nav-link.logout-btn {
            background: rgba(220, 53, 69, 0.15);
            color: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(220, 53, 69, 0.3);
        }

        .nav-link.logout-btn:hover {
            background: rgba(220, 53, 69, 0.9);
            color: white;
            border-color: #dc3545;
            transform: translateX(8px);
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.4);
        }

        .nav-link i {
            width: 22px;
            margin-right: 0.85rem;
            font-size: 1.1rem;
        }

        .nav-separator {
            margin: 1.5rem 0.75rem 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }

        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            padding: 30px;
        }

        .top-navbar {
            background: white;
            border-bottom: 4px solid var(--gabon-yellow);
            padding: 1.5rem 2rem;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .page-title {
            color: var(--gabon-green);
            font-weight: 700;
            font-size: 1.8rem;
            margin: 0;
        }

        .stats-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
            border-left: 5px solid var(--gabon-green);
            transition: transform 0.3s;
            height: 100%;
        }

        .stats-card:hover {
            transform: translateY(-5px);
        }

        .stats-card.yellow-border {
            border-left-color: var(--gabon-yellow);
        }

        .stats-card.blue-border {
            border-left-color: var(--gabon-blue);
        }

        .stats-card.red-border {
            border-left-color: #dc3545;
        }

        .stats-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 15px;
        }

        .stats-icon.green {
            background: rgba(0,158,96,0.1);
            color: var(--gabon-green);
        }

        .stats-icon.yellow {
            background: rgba(252,209,22,0.1);
            color: #e6bd00;
        }

        .stats-icon.blue {
            background: rgba(58,117,196,0.1);
            color: var(--gabon-blue);
        }

        .stats-icon.red {
            background: rgba(220,53,69,0.1);
            color: #dc3545;
        }

        .stats-number {
            font-size: 2rem;
            font-weight: 700;
            color: #2c3e50;
        }

        .stats-label {
            color: #7f8c8d;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .content-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
            overflow: hidden;
            margin-bottom: 30px;
        }

        .card-header-custom {
            background: linear-gradient(135deg, var(--gabon-green) 0%, var(--dark-green) 100%);
            color: white;
            padding: 20px 25px;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .card-body-custom {
            padding: 25px;
        }

        .form-label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
        }

        .form-control, .form-select {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 12px 15px;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--gabon-green);
            box-shadow: 0 0 0 0.2rem rgba(0,158,96,0.15);
        }

        .btn-gabon {
            background: linear-gradient(135deg, var(--gabon-green) 0%, var(--dark-green) 100%);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-gabon:hover {
            background: linear-gradient(135deg, var(--dark-green) 0%, #005a3a 100%);
            color: white;
            transform: translateY(-2px);
        }

        .btn-cancel {
            background: #6c757d;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-cancel:hover {
            background: #5a6268;
            color: white;
            transform: translateY(-2px);
        }

        .table-custom {
            border-collapse: separate;
            border-spacing: 0 10px;
        }

        .table-custom thead th {
            background: var(--gabon-green);
            color: white;
            font-weight: 600;
            padding: 15px;
            border: none;
            text-transform: uppercase;
            font-size: 0.85rem;
        }

        .table-custom thead th:first-child {
            border-radius: 10px 0 0 10px;
        }

        .table-custom thead th:last-child {
            border-radius: 0 10px 10px 0;
        }

        .table-custom tbody tr {
            background: white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            transition: all 0.3s;
        }

        .table-custom tbody tr:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            transform: scale(1.01);
        }

        .table-custom tbody td {
            padding: 15px;
            vertical-align: middle;
            border: none;
        }

        .table-custom tbody tr td:first-child {
            border-radius: 10px 0 0 10px;
        }

        .table-custom tbody tr td:last-child {
            border-radius: 0 10px 10px 0;
        }

        .preview-img {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 10px;
            border: 3px solid var(--gabon-green);
        }

        .media-preview {
            max-width: 150px;
            max-height: 150px;
            border-radius: 10px;
            border: 3px solid var(--gabon-green);
            margin-top: 10px;
            padding: 10px;
            background: white;
        }

        .badge-publie {
            background: var(--gabon-green);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .badge-brouillon {
            background: var(--gabon-yellow);
            color: var(--dark-green);
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .badge-archive {
            background: #6c757d;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .badge-categorie {
            background: var(--gabon-blue);
            color: white;
            padding: 6px 12px;
            border-radius: 15px;
            font-weight: 500;
            font-size: 0.8rem;
        }

        .btn-action {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-action.edit {
            background: rgba(252,209,22,0.15);
            color: #e6bd00;
        }

        .btn-action.edit:hover {
            background: var(--gabon-yellow);
            color: var(--dark-green);
        }

        .btn-action.delete {
            background: rgba(220,53,69,0.15);
            color: #dc3545;
        }

        .btn-action.delete:hover {
            background: #dc3545;
            color: white;
        }

        .alert {
            border-radius: 10px;
            border: none;
            padding: 15px 20px;
            margin-bottom: 20px;
        }

        .alert-success {
            background: rgba(0,158,96,0.1);
            color: var(--dark-green);
            border-left: 4px solid var(--gabon-green);
        }

        .alert-danger {
            background: rgba(220,53,69,0.1);
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        .alert-info {
            background: rgba(58,117,196,0.1);
            color: #0c5460;
            border-left: 4px solid var(--gabon-blue);
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #7f8c8d;
        }

        .empty-state i {
            font-size: 64px;
            color: #bdc3c7;
            margin-bottom: 20px;
        }

        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <nav class="sidebar">
        <div class="sidebar-brand text-center">
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/0/04/Flag_of_Gabon.svg/200px-Flag_of_Gabon.svg.png" alt="Gabon">
            <h3><i class="fas fa-futbol"></i> Football Gabon</h3>
            <small class="text-muted">Administration Sportive</small>
        </div>

        <ul class="nav flex-column mt-4">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('dashboard') }}">
                    <i class="fas fa-chart-line"></i> Tableau de bord
                </a>
            </li>
            <li class="nav-item mt-3">
                <a class="nav-link" href="{{ route('equipes.store') }}">
                    <i class="fas fa-shield-alt"></i> Équipes
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('actualites.store') }}">
                    <i class="fas fa-newspaper"></i> Actualités
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('interview.store') }}">
                    <i class="fas fa-microphone"></i> Interviews
                </a>
            </li>
            <li class="nav-item mt-3">
                <a class="nav-link" href="{{ route('matchs.store') }}">
                    <i class="fas fa-medal"></i> Résultats-Matchs
                </a>
            </li>

            <li class="nav-separator"></li>

            <li class="nav-item">
                <form action="{{ route('deconnexion') }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" class="nav-link logout-btn">
                        <i class="fas fa-sign-out-alt"></i> Déconnexion
                    </button>
                </form>
            </li>
        </ul>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar -->
        <div class="top-navbar">
            <h1 class="page-title">
                <i class="fas fa-microphone me-2"></i>Gestion des Interviews
            </h1>
        </div>

        <!-- Notifications -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <strong>Erreurs de validation :</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <div id="alertContainer"></div>

        <!-- Stats -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stats-card">
                    <div class="stats-icon green">
                        <i class="fas fa-microphone"></i>
                    </div>
                    <div class="stats-number">{{ $totalInterviews ?? 0 }}</div>
                    <div class="stats-label">Interviews Total</div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stats-card yellow-border">
                    <div class="stats-icon yellow">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stats-number">{{ $publishedInterviews ?? 0 }}</div>
                    <div class="stats-label">Publiés</div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stats-card blue-border">
                    <div class="stats-icon blue">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stats-number">{{ $draftInterviews ?? 0 }}</div>
                    <div class="stats-label">Brouillons</div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stats-card red-border">
                    <div class="stats-icon red">
                        <i class="fas fa-archive"></i>
                    </div>
                    <div class="stats-number">{{ $archivedInterviews ?? 0 }}</div>
                    <div class="stats-label">Archivés</div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Formulaire -->
            <div class="col-xl-4 col-lg-5 mb-4">
                <div class="content-card">
                    <div class="card-header-custom">
                        <i class="fas fa-plus-circle me-2"></i><span id="formTitle">Nouvelle Interview</span>
                    </div>
                    <div class="card-body-custom">
                        <form id="interviewForm">
                            <input type="hidden" id="interviewId">
                            <input type="hidden" id="currentImage">

                            <div class="mb-3">
                                <label class="form-label">Administrateur *</label>
                                <select class="form-select" name="admin_id" id="admin_id" required>
                                    <option value="">Choisir...</option>
                                    @foreach($admins as $admin)
                                    <option value="{{ $admin->id }}">{{ $admin->nom }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Titre *</label>
                                <input type="text" class="form-control" name="titre" id="titre" placeholder="Titre de l'interview..." required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Image</label>
                                <input type="file" class="form-control" name="image" id="image" accept="image/*">
                                <small class="text-muted">JPG, PNG, GIF (Max 5Mo)</small>
                                <div id="imagePreview" class="mt-2"></div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Nom de l'interviewé *</label>
                                <input type="text" class="form-control" name="nom_interviewe" id="nom_interviewe" placeholder="Ex: Pierre-Emerick Aubameyang" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Poste/Fonction *</label>
                                <input type="text" class="form-control" name="poste_interviewe" id="poste_interviewe" placeholder="Ex: Attaquant" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Catégorie *</label>
                                <select class="form-select" name="categorie" id="categorie" required>
                                    <option value="">Choisir...</option>
                                    <option value="joueur">Joueur</option>
                                    <option value="entraineur">Entraîneur</option>
                                    <option value="dirigeant">Dirigeant</option>
                                    <option value="autre">Autre</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Contenu de l'interview *</label>
                                <textarea class="form-control" name="contenu" id="contenu" rows="4" placeholder="Questions et réponses..." required></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Date de l'interview *</label>
                                <input type="date" class="form-control" name="date_interview" id="date_interview" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Statut *</label>
                                <select class="form-select" name="statut" id="statut" required>
                                    <option value="brouillon">Brouillon</option>
                                    <option value="publie">Publié</option>
                                    <option value="archive">Archivé</option>
                                </select>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-gabon flex-fill">
                                    <i class="fas fa-save me-2"></i><span id="btnText">Enregistrer</span>
                                </button>
                                <button type="button" class="btn btn-cancel" id="cancelBtn" style="display:none;" onclick="cancelEdit()">
                                    <i class="fas fa-times me-2"></i>Annuler
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Liste des interviews -->
            <div class="col-xl-8 col-lg-7 mb-4">
                <div class="content-card">
                    <div class="card-header-custom">
                        <i class="fas fa-list me-2"></i>Liste des Interviews (<span id="interviewCount">{{ $interviews->count() }}</span>)
                    </div>
                    <div class="card-body-custom">
                        <div class="table-responsive">
                            <table class="table table-custom">
                                <thead>
                                    <tr>
                                        <th>Image</th>
                                        <th>Titre</th>
                                        <th>Interviewé</th>
                                        <th>Catégorie</th>
                                        <th>Statut</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="interviewsTable">
                                    @forelse($interviews as $interview)
                                    <tr data-interview-id="{{ $interview->id }}">
                                        <td>
                                            @if($interview->image)
                                            <img src="{{ asset('storage/' . $interview->image) }}" class="preview-img" alt="Interview">
                                            @else
                                            <div class="text-center">
                                                <i class="fas fa-user-circle fa-3x text-muted"></i>
                                            </div>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ Str::limit($interview->titre, 50) }}</strong><br>
                                            <small class="text-muted">Par {{ $interview->admin->nom ?? 'Inconnu' }}</small>
                                        </td>
                                        <td>
                                            <strong>{{ $interview->nom_interviewe }}</strong><br>
                                            <small class="text-muted">{{ $interview->poste_interviewe }}</small>
                                        </td>
                                        <td>
                                            <span class="badge-categorie">{{ ucfirst($interview->categorie) }}</span>
                                        </td>
                                        <td>
                                            @if($interview->statut == 'publie')
                                                <span class="badge-publie">Publié</span>
                                            @elseif($interview->statut == 'brouillon')
                                                <span class="badge-brouillon">Brouillon</span>
                                            @else
                                                <span class="badge-archive">Archivé</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small>{{ \Carbon\Carbon::parse($interview->date_interview)->format('d/m/Y') }}</small>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <button class="btn-action edit" onclick="editInterview({{ $interview->id }})" title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn-action delete" onclick="deleteInterview({{ $interview->id }})" title="Supprimer">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7">
                                            <div class="empty-state">
                                                <i class="fas fa-inbox"></i>
                                                <h5>Aucune interview disponible</h5>
                                                <p>Créez votre première interview avec le formulaire ci-contre</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script>
        // Configuration CSRF
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

        // Set today's date as default
        document.getElementById('date_interview').valueAsDate = new Date();

        // Preview image on selection
        document.getElementById('image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('imagePreview');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" class="media-preview" alt="Aperçu">`;
                };
                reader.readAsDataURL(file);
            } else {
                preview.innerHTML = '';
            }
        });

        // Submit form (Create or Update)
        document.getElementById('interviewForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const interviewId = document.getElementById('interviewId').value;
            const formData = new FormData(this);

            // URL et méthode selon le mode
            let url, method;
            if (interviewId) {
                url = `/interview/${interviewId}`;
                method = 'POST';
                formData.append('_method', 'PUT');
            } else {
                url = '/interview';
                method = 'POST';
            }

            // Afficher le loader
            const btnText = document.getElementById('btnText');
            const originalText = btnText.textContent;
            btnText.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>En cours...';

            try {
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const result = await response.json();

                btnText.textContent = originalText;

                if (result.success || response.ok) {
                    showAlert('success', interviewId ? 'Interview modifiée avec succès!' : 'Interview créée avec succès!');
                    cancelEdit();
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    if (result.errors) {
                        const errorMessages = Object.values(result.errors).flat().join('<br>');
                        showAlert('danger', errorMessages);
                    } else {
                        showAlert('danger', result.message || 'Erreur lors de l\'enregistrement');
                    }
                }
            } catch (error) {
                btnText.textContent = originalText;
                console.error('Erreur:', error);
                showAlert('danger', 'Erreur lors de la communication avec le serveur');
            }
        });

        // Edit interview
        async function editInterview(id) {
            try {
                const response = await fetch(`/interview/${id}/edit`);

                if (!response.ok) {
                    throw new Error('Erreur lors du chargement');
                }

                const data = await response.json();

                // Remplir le formulaire
                document.getElementById('interviewId').value = data.id;
                document.getElementById('admin_id').value = data.admin_id;
                document.getElementById('titre').value = data.titre;
                document.getElementById('nom_interviewe').value = data.nom_interviewe;
                document.getElementById('poste_interviewe').value = data.poste_interviewe;
                document.getElementById('categorie').value = data.categorie;
                document.getElementById('contenu').value = data.contenu;
                document.getElementById('date_interview').value = data.date_interview;
                document.getElementById('statut').value = data.statut;

                // Afficher l'image actuelle si elle existe
                if (data.image) {
                    document.getElementById('currentImage').value = data.image;
                    document.getElementById('imagePreview').innerHTML =
                        `<img src="/storage/${data.image}" class="media-preview" alt="Image actuelle">`;
                }

                // Modifier l'interface
                document.getElementById('formTitle').textContent = 'Modifier Interview';
                document.getElementById('btnText').textContent = 'Mettre à jour';
                document.getElementById('cancelBtn').style.display = 'block';

                // Scroll vers le formulaire
                document.querySelector('.content-card').scrollIntoView({ behavior: 'smooth', block: 'start' });

            } catch (error) {
                console.error('Erreur:', error);
                showAlert('danger', 'Erreur lors du chargement de l\'interview');
            }
        }

        // Cancel edit
        function cancelEdit() {
            document.getElementById('interviewForm').reset();
            document.getElementById('interviewId').value = '';
            document.getElementById('currentImage').value = '';
            document.getElementById('imagePreview').innerHTML = '';
            document.getElementById('formTitle').textContent = 'Nouvelle Interview';
            document.getElementById('btnText').textContent = 'Enregistrer';
            document.getElementById('cancelBtn').style.display = 'none';
            document.getElementById('date_interview').valueAsDate = new Date();
        }

        // Delete interview
        async function deleteInterview(id) {
            if (!confirm('Êtes-vous sûr de vouloir supprimer cette interview ?')) {
                return;
            }

            try {
                const response = await fetch(`/interview/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });

                const result = await response.json();

                if (result.success || response.ok) {
                    showAlert('success', 'Interview supprimée avec succès!');

                    // Animation de suppression
                    const interviewRow = document.querySelector(`[data-interview-id="${id}"]`);
                    if (interviewRow) {
                        interviewRow.style.transition = 'all 0.3s';
                        interviewRow.style.opacity = '0';
                        interviewRow.style.transform = 'translateX(-20px)';

                        setTimeout(() => {
                            interviewRow.remove();

                            // Vérifier s'il reste des interviews
                            const remainingRows = document.querySelectorAll('#interviewsTable tr[data-interview-id]');
                            if (remainingRows.length === 0) {
                                document.getElementById('interviewsTable').innerHTML = `
                                    <tr>
                                        <td colspan="7">
                                            <div class="empty-state">
                                                <i class="fas fa-inbox"></i>
                                                <h5>Aucune interview disponible</h5>
                                                <p>Créez votre première interview avec le formulaire ci-contre</p>
                                            </div>
                                        </td>
                                    </tr>`;
                            }

                            // Mettre à jour le compteur
                            const count = parseInt(document.getElementById('interviewCount').textContent) - 1;
                            document.getElementById('interviewCount').textContent = Math.max(0, count);
                        }, 300);
                    }
                } else {
                    showAlert('danger', result.message || 'Erreur lors de la suppression');
                }
            } catch (error) {
                console.error('Erreur:', error);
                showAlert('danger', 'Erreur lors de la communication avec le serveur');
            }
        }

        // Show alert
        function showAlert(type, message) {
            const alertContainer = document.getElementById('alertContainer');
            const alertClass = type === 'success' ? 'alert-success' : (type === 'info' ? 'alert-info' : 'alert-danger');
            const icon = type === 'success' ? 'fa-check-circle' : (type === 'info' ? 'fa-info-circle' : 'fa-exclamation-circle');

            const alert = document.createElement('div');
            alert.className = `alert ${alertClass} alert-dismissible fade show`;
            alert.role = 'alert';
            alert.innerHTML = `
                <i class="fas ${icon} me-2"></i>${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;

            alertContainer.appendChild(alert);

            // Auto-hide après 5 secondes
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000);
        }
    </script>
</body>
</html>
