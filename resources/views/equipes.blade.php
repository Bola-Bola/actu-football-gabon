<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Gestion des Équipes - Football Gabonais</title>
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
            overflow-x: hidden;
        }

        /* Sidebar Styling */
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

        .sidebar-brand small {
            color: var(--gabon-yellow);
            font-size: 0.85rem;
            font-weight: 500;
        }

        .nav {
            padding: 1rem 0;
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

        .nav-link i {
            width: 22px;
            margin-right: 0.85rem;
            font-size: 1.1rem;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            padding: 30px;
        }

        /* Top Navbar */
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

        /* Stats Cards */
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
            box-shadow: 0 5px 20px rgba(0,0,0,0.12);
        }

        .stats-card.yellow-border {
            border-left-color: var(--gabon-yellow);
        }

        .stats-card.blue-border {
            border-left-color: var(--gabon-blue);
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

        .stats-number {
            font-size: 2rem;
            font-weight: 700;
            color: #2c3e50;
            margin: 10px 0 5px 0;
        }

        .stats-label {
            color: #7f8c8d;
            font-size: 0.9rem;
            font-weight: 500;
        }

        /* Content Cards */
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
            border: none;
        }

        .card-header-custom.yellow {
            background: linear-gradient(135deg, var(--gabon-yellow) 0%, #e6bd00 100%);
            color: var(--dark-green);
        }

        .card-body-custom {
            padding: 25px;
        }

        /* Forms */
        .form-label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
        }

        .form-control, .form-select {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 12px 15px;
            transition: all 0.3s;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--gabon-green);
            box-shadow: 0 0 0 0.2rem rgba(0,158,96,0.15);
        }

        /* Buttons */
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
            box-shadow: 0 5px 15px rgba(0,158,96,0.3);
        }

        .btn-gabon-yellow {
            background: linear-gradient(135deg, var(--gabon-yellow) 0%, #e6bd00 100%);
            color: var(--dark-green);
            border: none;
            padding: 10px 25px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-gabon-yellow:hover {
            background: linear-gradient(135deg, #e6bd00 0%, #ccaa00 100%);
            color: var(--dark-green);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(252,209,22,0.3);
        }

        .btn-cancel {
            background: #6c757d;
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-cancel:hover {
            background: #5a6268;
            color: white;
            transform: translateY(-2px);
        }

        /* Logo Preview */
        .logo-preview {
            max-width: 150px;
            max-height: 150px;
            border-radius: 10px;
            border: 3px solid var(--gabon-green);
            margin-top: 10px;
            padding: 10px;
            background: white;
        }

        .team-logo {
            width: 60px;
            height: 60px;
            object-fit: contain;
            border-radius: 8px;
            border: 2px solid var(--gabon-green);
            padding: 5px;
            background: white;
        }

        /* Table */
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
            letter-spacing: 0.5px;
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

        /* Badges */
        .badge-ville {
            background: var(--gabon-blue);
            color: white;
            padding: 6px 12px;
            border-radius: 15px;
            font-weight: 500;
            font-size: 0.8rem;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .btn-action {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
            font-size: 16px;
            cursor: pointer;
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

        /* Empty State */
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

        /* Alerts */
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

        /* Scrollbar */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }
        .sidebar::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.1);
        }
        .sidebar::-webkit-scrollbar-thumb {
            background: var(--gabon-yellow);
            border-radius: 3px;
        }

        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
        }

        /* Responsive */
        @media (max-width: 991.98px) {
            .sidebar {
                margin-left: calc(-1 * var(--sidebar-width));
            }
            .main-content {
                margin-left: 0;
            }
        }
         /* Sidebar Styling */
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

        /* Section séparateur */
        .nav-separator {
            margin: 1.5rem 0.75rem 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }

    </style>
</head>
<body>
    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-brand text-center">
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/0/04/Flag_of_Gabon.svg/200px-Flag_of_Gabon.svg.png" alt="Gabon">
            <h3><i class="fas fa-futbol"></i> Football Gabon</h3>
            <small class="text-muted">Administration Sportive</small>
        </div>

        <ul class="nav flex-column mt-4">
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('dashboard') }}">
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
                <a class="nav-link" href="{{ route('interview.store') }}">
                    <i class="fas fa-newspaper"></i> Interviews
                </a>
            </li>
            <li class="nav-item mt-3">
                <a class="nav-link" href="{{ route('matchs.store') }}">
                    <i class="fas fa-medal"></i> Résultats-Matchs
                </a>
            </li>

            <!-- Séparateur -->
            <li class="nav-separator"></li>

            <!-- Formulaire de déconnexion stylisé -->
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
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="page-title mb-0">
                    <i class="fas fa-shield-alt me-2"></i>Gestion des Équipes
                </h1>
                <div class="d-flex align-items-center">
                    <span class="text-muted me-3">
                        <i class="far fa-calendar-alt me-2"></i>
                        <span id="currentDate"></span>
                    </span>
                    <div class="bg-light rounded-circle p-2">
                        <i class="fas fa-user-circle fa-2x text-muted"></i>
                    </div>
                </div>
            </div>
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

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stats-card">
                    <div class="stats-icon green">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div class="stats-number" id="totalEquipes">{{ $equipes->count() }}</div>
                    <div class="stats-label">Équipes Total</div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stats-card yellow-border">
                    <div class="stats-icon yellow">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="stats-number" id="totalVilles">-</div>
                    <div class="stats-label">Villes Représentées</div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stats-card blue-border">
                    <div class="stats-icon blue">
                        <i class="fas fa-calendar"></i>
                    </div>
                    <div class="stats-number" id="moyenneAge">-</div>
                    <div class="stats-label">Âge moyen (ans)</div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stats-card">
                    <div class="stats-icon green">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <div class="stats-number" id="plusAncienne" style="font-size: 1.2rem;">-</div>
                    <div class="stats-label">Plus Ancienne</div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Add Team Form -->
            <div class="col-xl-4 col-lg-5 mb-4">
                <div class="content-card">
                    <div class="card-header-custom">
                        <i class="fas fa-plus-circle me-2"></i><span id="formTitle">Nouvelle Équipe</span>
                    </div>
                    <div class="card-body-custom">
                        <form id="equipeForm">
                            <input type="hidden" id="equipeId">

                            <div class="mb-3">
                                <label class="form-label">Nom de l'équipe *</label>
                                <input type="text" class="form-control" name="nom" id="nom" placeholder="Ex: AS Mangasport" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Stade</label>
                                <input type="text" class="form-control" name="stade" id="stade" placeholder="Ex: Stade d'Angondjé">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Logo de l'équipe</label>
                                <input type="file" class="form-control" name="logo" id="logo" accept="image/*">
                                <small class="text-muted">Formats: JPG, PNG, GIF, SVG (Max 2Mo)</small>
                                <div id="logoPreview" class="mt-2"></div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Ville</label>
                                <input type="text" class="form-control" name="ville" id="ville" placeholder="Ex: Libreville">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Année de fondation</label>
                                <input type="number" class="form-control" name="fondation" id="fondation" placeholder="Ex: 1962" min="1900" max="{{ date('Y') }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Entraîneur</label>
                                <input type="text" class="form-control" name="entraineur" id="entraineur" placeholder="Ex: Jean Dupont">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="description" id="description" rows="4" placeholder="Histoire et informations sur l'équipe..."></textarea>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-gabon flex-fill" id="submitBtn">
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

            <!-- Teams List -->
            <div class="col-xl-8 col-lg-7 mb-4">
                <div class="content-card">
                    <div class="card-header-custom">
                        <i class="fas fa-list me-2"></i>Liste des Équipes (<span id="equipeCount">{{ $equipes->count() }}</span>)
                    </div>
                    <div class="card-body-custom">
                        <div class="table-responsive">
                            <table class="table table-custom">
                                <thead>
                                    <tr>
                                        <th>Logo</th>
                                        <th>Nom</th>
                                        <th>Stade</th>
                                        <th>Ville</th>
                                        <th>Année</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="equipesTable">
                                    @forelse($equipes as $equipe)
                                    <tr data-equipe-id="{{ $equipe->id }}">
                                        <td>
                                            @if($equipe->logo)
                                            <img src="{{ asset('storage/' . $equipe->logo) }}" class="team-logo" alt="{{ $equipe->nom }}">
                                            @else
                                            <div style="width:60px;height:60px;background:#f0f0f0;border-radius:8px;display:flex;align-items:center;justify-content:center;border:2px solid var(--gabon-green);">
                                                <i class="fas fa-shield-alt fa-2x text-muted"></i>
                                            </div>
                                            @endif
                                        </td>
                                        <td>
                                            <strong style="color:#2c3e50;">{{ $equipe->nom }}</strong>
                                            @if($equipe->entraineur)
                                            <br><small class="text-muted"><i class="fas fa-user-tie me-1"></i>{{ $equipe->entraineur }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($equipe->stade)
                                            <i class="fas fa-stadium text-muted me-1"></i>{{ $equipe->stade }}
                                            @else
                                            <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($equipe->ville)
                                            <span class="badge-ville">{{ $equipe->ville }}</span>
                                            @else
                                            <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($equipe->fondation)
                                            <strong style="color:#555;">{{ $equipe->fondation }}</strong>
                                            @else
                                            <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="btn-action edit" onclick="editEquipe({{ $equipe->id }})" title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn-action delete" onclick="deleteEquipe({{ $equipe->id }})" title="Supprimer">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6">
                                            <div class="empty-state">
                                                <i class="fas fa-shield-alt"></i>
                                                <h5>Aucune équipe disponible</h5>
                                                <p>Commencez par créer votre première équipe</p>
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

        // Définir la date actuelle
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        document.getElementById('currentDate').textContent = new Date().toLocaleDateString('fr-FR', options);

        // Masquer automatiquement les alertes après 5 secondes
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

        // Charger les statistiques
        function loadStats() {
            fetch('/equipes/stats', {
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('totalEquipes').textContent = data.data.total;
                    document.getElementById('totalVilles').textContent = data.data.villes;
                    document.getElementById('moyenneAge').textContent = data.data.moyenne_age;
                    document.getElementById('plusAncienne').textContent = data.data.plus_ancienne;
                }
            })
            .catch(error => console.error('Erreur:', error));
        }

        // Soumettre le formulaire (Créer ou Mettre à jour)
        document.getElementById('equipeForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const equipeId = document.getElementById('equipeId').value;
            const formData = new FormData(this);

            // URL et méthode selon le mode
            let url, method;
            if (equipeId) {
                url = `/equipes/${equipeId}`;
                method = 'POST';
                formData.append('_method', 'PUT');
            } else {
                url = '/equipes';
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

                if (result.success) {
                    showAlert('success', result.message);
                    cancelEdit();
                    loadEquipes();
                    loadStats();
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

        // Charger toutes les équipes
        function loadEquipes() {
            fetch('/equipes', {
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayEquipes(data.data);
                    document.getElementById('equipeCount').textContent = data.data.length;
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showAlert('danger', 'Erreur lors du chargement des équipes');
            });
        }

        // Afficher les équipes
        function displayEquipes(equipes) {
            const tbody = document.getElementById('equipesTable');

            if (equipes.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <i class="fas fa-shield-alt"></i>
                                <h5>Aucune équipe disponible</h5>
                                <p>Commencez par créer votre première équipe</p>
                            </div>
                        </td>
                    </tr>`;
                return;
            }

            tbody.innerHTML = equipes.map(equipe => `
                <tr data-equipe-id="${equipe.id}">
                    <td>
                        ${equipe.logo ?
                            `<img src="/storage/${equipe.logo}" class="team-logo" alt="${equipe.nom}">` :
                            `<div style="width:60px;height:60px;background:#f0f0f0;border-radius:8px;display:flex;align-items:center;justify-content:center;border:2px solid var(--gabon-green);">
                                <i class="fas fa-shield-alt fa-2x text-muted"></i>
                            </div>`}
                    </td>
                    <td>
                        <strong style="color:#2c3e50;">${equipe.nom}</strong>
                        ${equipe.entraineur ? `<br><small class="text-muted"><i class="fas fa-user-tie me-1"></i>${equipe.entraineur}</small>` : ''}
                    </td>
                    <td>
                        ${equipe.stade ? `<i class="fas fa-stadium text-muted me-1"></i>${equipe.stade}` : '<span class="text-muted">-</span>'}
                    </td>
                    <td>
                        ${equipe.ville ? `<span class="badge-ville">${equipe.ville}</span>` : '<span class="text-muted">-</span>'}
                    </td>
                    <td>
                        ${equipe.fondation ? `<strong style="color:#555;">${equipe.fondation}</strong>` : '<span class="text-muted">-</span>'}
                    </td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn-action edit" onclick="editEquipe(${equipe.id})" title="Modifier">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn-action delete" onclick="deleteEquipe(${equipe.id})" title="Supprimer">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');
        }

        // Modifier une équipe
        function editEquipe(id) {
            fetch(`/equipes/${id}`, {
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const equipe = data.data;

                    document.getElementById('equipeId').value = equipe.id;
                    document.getElementById('nom').value = equipe.nom;
                    document.getElementById('stade').value = equipe.stade || '';
                    document.getElementById('ville').value = equipe.ville || '';
                    document.getElementById('fondation').value = equipe.fondation || '';
                    document.getElementById('entraineur').value = equipe.entraineur || '';
                    document.getElementById('description').value = equipe.description || '';

                    const preview = document.getElementById('logoPreview');
                    if (equipe.logo) {
                        // CORRECTION: Utilisation du chemin correct pour le stockage
                        preview.innerHTML = `<img src="/storage/${equipe.logo}" class="logo-preview" alt="Logo actuel">`;
                    } else {
                        preview.innerHTML = '';
                    }

                    document.getElementById('formTitle').textContent = 'Modifier l\'Équipe';
                    document.getElementById('btnText').textContent = 'Mettre à jour';
                    document.getElementById('cancelBtn').style.display = 'block';

                    // Faire défiler vers le formulaire
                    document.querySelector('.content-card').scrollIntoView({ behavior: 'smooth' });
                } else {
                    showAlert('danger', 'Erreur lors du chargement de l\'équipe');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showAlert('danger', 'Erreur lors de la communication avec le serveur');
            });
        }

        // Annuler la modification
        function cancelEdit() {
            document.getElementById('equipeForm').reset();
            document.getElementById('equipeId').value = '';
            document.getElementById('logoPreview').innerHTML = '';
            document.getElementById('formTitle').textContent = 'Nouvelle Équipe';
            document.getElementById('btnText').textContent = 'Enregistrer';
            document.getElementById('cancelBtn').style.display = 'none';
        }

        // Supprimer une équipe
        function deleteEquipe(id) {
            if (!confirm('Êtes-vous sûr de vouloir supprimer cette équipe ?')) {
                return;
            }

            fetch(`/equipes/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('success', data.message);

                    // Animation de suppression
                    const equipeRow = document.querySelector(`[data-equipe-id="${id}"]`);
                    if (equipeRow) {
                        equipeRow.style.transition = 'all 0.3s';
                        equipeRow.style.opacity = '0';
                        equipeRow.style.transform = 'translateX(-20px)';

                        setTimeout(() => {
                            equipeRow.remove();
                            loadStats();

                            // Vérifier s'il reste des équipes
                            if (document.querySelectorAll('#equipesTable tr').length === 0) {
                                document.getElementById('equipesTable').innerHTML = `
                                    <tr>
                                        <td colspan="6">
                                            <div class="empty-state">
                                                <i class="fas fa-shield-alt"></i>
                                                <h5>Aucune équipe disponible</h5>
                                                <p>Commencez par créer votre première équipe</p>
                                            </div>
                                        </td>
                                    </tr>`;
                            }
                        }, 300);
                    }
                } else {
                    showAlert('danger', data.message || 'Erreur lors de la suppression');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showAlert('danger', 'Erreur lors de la communication avec le serveur');
            });
        }

        // Afficher une alerte
        function showAlert(type, message) {
            const alertContainer = document.getElementById('alertContainer');
            const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
            const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';

            const alert = document.createElement('div');
            alert.className = `alert ${alertClass} alert-dismissible fade show`;
            alert.role = 'alert';
            alert.innerHTML = `
                <i class="fas ${icon} me-2"></i>${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;

            alertContainer.appendChild(alert);

            // Masquer automatiquement après 5 secondes
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000);
        }
    </script>
</body>
</html>
