<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Gestion des Résultats de Matchs - Football Gabonais</title>
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
        }

        .sidebar-brand {
            padding: 2rem 1.5rem;
            border-bottom: 2px solid rgba(252, 209, 22, 0.3);
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
        }

        .nav-link {
            color: rgba(255,255,255,0.85);
            padding: 0.95rem 1.2rem;
            border-radius: 0.75rem;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            font-weight: 500;
            margin: 0.25rem 0.75rem;
            text-decoration: none;
        }

        .nav-link:hover {
            background: rgba(252, 209, 22, 0.2);
            color: white;
            transform: translateX(8px);
        }

        .nav-link.active {
            background: linear-gradient(135deg, var(--gabon-yellow) 0%, #f0c000 100%);
            color: var(--dark-green);
            font-weight: 600;
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

        .match-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
            margin-bottom: 20px;
            transition: all 0.3s;
        }

        .match-card:hover {
            box-shadow: 0 4px 20px rgba(0,0,0,0.12);
            transform: translateY(-3px);
        }

        .match-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e9ecef;
        }

        .match-competition {
            background: var(--gabon-blue);
            color: white;
            padding: 6px 14px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .match-date {
            color: #7f8c8d;
            font-weight: 500;
        }

        .match-body {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
        }

        .team-section {
            flex: 1;
            text-align: center;
        }

        .team-logo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 3px solid var(--gabon-green);
            margin-bottom: 10px;
            object-fit: cover;
        }

        .team-name {
            font-weight: 700;
            color: #2c3e50;
            font-size: 1.1rem;
            margin-bottom: 5px;
        }

        .score-section {
            text-align: center;
            padding: 0 30px;
        }

        .score-display {
            font-size: 3rem;
            font-weight: 700;
            color: var(--gabon-green);
            line-height: 1;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .score-separator {
            font-size: 2rem;
            color: #bdc3c7;
        }

        .match-status {
            margin-top: 10px;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .status-termine {
            background: rgba(0,158,96,0.15);
            color: var(--dark-green);
        }

        .status-en_cours {
            background: rgba(252,209,22,0.15);
            color: #e6bd00;
        }

        .status-reporte {
            background: rgba(58,117,196,0.15);
            color: var(--gabon-blue);
        }

        .status-annule {
            background: rgba(220,53,69,0.15);
            color: #dc3545;
        }

        .match-details {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #e9ecef;
            display: flex;
            justify-content: space-around;
            text-align: center;
        }

        .detail-item {
            flex: 1;
        }

        .detail-label {
            color: #7f8c8d;
            font-size: 0.85rem;
            margin-bottom: 5px;
        }

        .detail-value {
            color: #2c3e50;
            font-weight: 700;
            font-size: 1.1rem;
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

        .filter-section {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
            margin-bottom: 30px;
        }

        .score-input {
            width: 100%;
            text-align: center;
            font-size: 1.2rem;
            font-weight: 600;
        }

        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
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
                        <i class="fas fa-medal"></i> Résultats-Match
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
            <h1 class="page-title">
                <i class="fas fa-trophy me-2"></i>Gestion des Résultats de Matchs
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

        <div id="alertContainer"></div>

        <!-- Stats -->
        <div class="row mb-4" id="statsContainer">
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stats-card">
                    <div class="stats-icon green">
                        <i class="fas fa-futbol"></i>
                    </div>
                    <div class="stats-number" id="totalMatchs">{{ $matchs->total() }}</div>
                    <div class="stats-label">Matchs Total</div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stats-card yellow-border">
                    <div class="stats-icon yellow">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stats-number" id="totalTermines">-</div>
                    <div class="stats-label">Terminés</div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stats-card blue-border">
                    <div class="stats-icon blue">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stats-number" id="totalEnCours">-</div>
                    <div class="stats-label">En cours</div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stats-card red-border">
                    <div class="stats-icon red">
                        <i class="fas fa-calendar-times"></i>
                    </div>
                    <div class="stats-number" id="totalReportes">-</div>
                    <div class="stats-label">Reportés</div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="filter-section">
            <div class="row g-3">
                <div class="col-md-3">
                    <select class="form-select" id="filterCompetition">
                        <option value="">Toutes les compétitions</option>
                        @foreach($competitions as $competition)
                        <option value="{{ $competition->id }}">{{ $competition->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" id="filterStatut">
                        <option value="">Tous les statuts</option>
                        <option value="termine">Terminé</option>
                        <option value="en_cours">En cours</option>
                        <option value="reporte">Reporté</option>
                        <option value="annule">Annulé</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" id="filterSaison">
                        <option value="">Toutes les saisons</option>
                        @foreach($saisons as $saison)
                        <option value="{{ $saison->id }}">{{ $saison->annee }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="filterEquipe">
                        <option value="">Toutes les équipes</option>
                        @foreach($equipes as $equipe)
                        <option value="{{ $equipe->id }}">{{ $equipe->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-gabon w-100" onclick="applyFilters()">
                        <i class="fas fa-filter me-2"></i>Filtrer
                    </button>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Formulaire d'ajout/modification -->
            <div class="col-xl-4 col-lg-5 mb-4">
                <div class="content-card">
                    <div class="card-header-custom">
                        <i class="fas fa-plus-circle me-2"></i><span id="formTitle">Enregistrer un Résultat</span>
                    </div>
                    <div class="card-body-custom">
                        <form id="matchForm">
                            <input type="hidden" id="matchId" name="match_id">

                            <div class="mb-3">
                                <label class="form-label">Compétition *</label>
                                <select class="form-select" name="competition_id" id="competition_id">
                                    <option value="">Choisir...</option>
                                    @foreach($competitions as $competition)
                                    <option value="{{ $competition->id }}">{{ $competition->nom }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Saison *</label>
                                <select class="form-select" name="saison_id" id="saison_id" required>
                                    <option value="">Choisir...</option>
                                    @foreach($saisons as $saison)
                                    <option value="{{ $saison->id }}">{{ $saison->annee }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Date et Heure *</label>
                                <input type="datetime-local" class="form-control" name="date_match" id="date_match" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Équipe Domicile *</label>
                                <select class="form-select" name="equipe_domicile_id" id="equipe_domicile_id" required>
                                    <option value="">Choisir...</option>
                                    @foreach($equipes as $equipe)
                                    <option value="{{ $equipe->id }}">{{ $equipe->nom }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Score Domicile *</label>
                                <input type="number" class="form-control score-input" name="buts_domicile" id="buts_domicile" min="0" max="50" placeholder="0" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Équipe Extérieur *</label>
                                <select class="form-select" name="equipe_exterieur_id" id="equipe_exterieur_id" required>
                                    <option value="">Choisir...</option>
                                    @foreach($equipes as $equipe)
                                    <option value="{{ $equipe->id }}">{{ $equipe->nom }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Score Extérieur *</label>
                                <input type="number" class="form-control score-input" name="buts_exterieur" id="buts_exterieur" min="0" max="50" placeholder="0" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Lieu</label>
                                <input type="text" class="form-control" name="lieu" id="lieu" placeholder="Ex: Stade Omar Bongo">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Type de Match</label>
                                <select class="form-select" name="type_match" id="type_match">
                                    <option value="officiel" selected>Officiel</option>
                                    <option value="amical">Amical</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Statut *</label>
                                <select class="form-select" name="statut" id="statut" required>
                                    <option value="termine">Terminé</option>
                                    <option value="en_cours">En cours</option>
                                    <option value="reporte">Reporté</option>
                                    <option value="annule">Annulé</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Notes</label>
                                <textarea class="form-control" name="notes" id="notes" rows="3" placeholder="Remarques sur le match..." maxlength="1000"></textarea>
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

            <!-- Liste des résultats -->
            <div class="col-xl-8 col-lg-7 mb-4">
                <div class="content-card">
                    <div class="card-header-custom">
                        <i class="fas fa-list me-2"></i>Derniers Résultats (<span id="matchCount">{{ $matchs->count() }}</span>)
                    </div>
                    <div class="card-body-custom">
                        <div id="matchsContainer">
                            @forelse($matchs as $match)
                            <div class="match-card" data-match-id="{{ $match->id }}">
                                <div class="match-header">
                                    <span class="match-competition">{{ $match->competition->nom ?? 'Sans compétition' }}</span>
                                    <span class="match-date">
                                        <i class="far fa-calendar me-2"></i>{{ $match->date_match ? $match->date_match->format('d M Y - H:i') : 'Date non définie' }}
                                    </span>
                                </div>
                                <div class="match-body">
                                    <div class="team-section">
                                        @if($match->equipeDomicile && $match->equipeDomicile->logo)
                                        <img src="{{ asset('storage/' . $match->equipeDomicile->logo) }}" alt="{{ $match->equipeDomicile->nom }}" class="team-logo">
                                        @else
                                        <img src="https://via.placeholder.com/80" alt="Logo" class="team-logo">
                                        @endif
                                        <div class="team-name">{{ $match->equipeDomicile->nom ?? 'Équipe inconnue' }}</div>
                                    </div>
                                    <div class="score-section">
                                        <div class="score-display">
                                            <span>{{ $match->buts_domicile ?? 0 }}</span>
                                            <span class="score-separator">-</span>
                                            <span>{{ $match->buts_exterieur ?? 0 }}</span>
                                        </div>
                                        <div class="match-status status-{{ $match->statut }}">
                                            @if($match->statut == 'termine') Terminé
                                            @elseif($match->statut == 'en_cours') En cours
                                            @elseif($match->statut == 'reporte') Reporté
                                            @else Annulé
                                            @endif
                                        </div>
                                    </div>
                                    <div class="team-section">
                                        @if($match->equipeExterieur && $match->equipeExterieur->logo)
                                        <img src="{{ asset('storage/' . $match->equipeExterieur->logo) }}" alt="{{ $match->equipeExterieur->nom }}" class="team-logo">
                                        @else
                                        <img src="https://via.placeholder.com/80" alt="Logo" class="team-logo">
                                        @endif
                                        <div class="team-name">{{ $match->equipeExterieur->nom ?? 'Équipe inconnue' }}</div>
                                    </div>
                                </div>
                                <div class="match-details">
                                    <div class="detail-item">
                                        <div class="detail-label">Stade</div>
                                        <div class="detail-value">{{ $match->lieu ?? 'Non précisé' }}</div>
                                    </div>
                                    <div class="detail-item">
                                        <div class="detail-label">Type</div>
                                        <div class="detail-value">{{ ucfirst($match->type_match ?? 'officiel') }}</div>
                                    </div>
                                    <div class="detail-item">
                                        <div class="detail-label">Actions</div>
                                        <div>
                                            <button class="btn-action edit" onclick="editMatch({{ $match->id }})" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn-action delete" onclick="deleteMatch({{ $match->id }})" title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="empty-state">
                                <i class="fas fa-inbox"></i>
                                <h5>Aucun match disponible</h5>
                                <p>Créez votre premier match avec le formulaire ci-contre</p>
                            </div>
                            @endforelse
                        </div>

                        <!-- Pagination -->
                        @if($matchs->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $matchs->links() }}
                        </div>
                        @endif
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

        // Charger les statistiques au chargement de la page
        document.addEventListener('DOMContentLoaded', function() {
            loadStats();
        });

        // Charger les statistiques
        function loadStats() {
            fetch('/matchs/stats', {
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('totalMatchs').textContent = data.data.total;
                    document.getElementById('totalTermines').textContent = data.data.termines;
                    document.getElementById('totalEnCours').textContent = data.data.en_cours;
                    document.getElementById('totalReportes').textContent = data.data.reportes;
                }
            })
            .catch(error => console.error('Erreur lors du chargement des stats:', error));
        }

        // Soumission du formulaire
        document.getElementById('matchForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const matchId = document.getElementById('matchId').value;
            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());

            // URL et méthode selon le mode (création ou modification)
            let url, method;
            if (matchId) {
                url = `/matchs/${matchId}`;
                method = 'POST'; // Laravel utilise POST avec _method
                data._method = 'PUT'; // Spoofing de méthode pour Laravel
            } else {
                url = '/matchs';
                method = 'POST';
            }

            // Afficher un loader
            const btnText = document.getElementById('btnText');
            const originalText = btnText.textContent;
            btnText.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>En cours...';

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                btnText.textContent = originalText;

                if (data.success) {
                    showAlert('success', data.message);
                    cancelEdit(); // Réinitialiser le formulaire

                    // Recharger les matchs et stats
                    loadMatches();
                    loadStats();
                } else {
                    if (data.errors) {
                        const errorMessages = Object.values(data.errors).flat().join('<br>');
                        showAlert('danger', errorMessages);
                    } else {
                        showAlert('danger', data.message || 'Erreur lors de l\'enregistrement');
                    }
                }
            })
            .catch(error => {
                btnText.textContent = originalText;
                console.error('Erreur:', error);
                showAlert('danger', 'Erreur lors de la communication avec le serveur');
            });
        });

        // Charger tous les matchs avec filtres
        function loadMatches() {
            const params = new URLSearchParams({
                competition_id: document.getElementById('filterCompetition').value,
                statut: document.getElementById('filterStatut').value,
                saison_id: document.getElementById('filterSaison').value,
                equipe_id: document.getElementById('filterEquipe').value
            });

            fetch(`/matchs/all?${params}`, {
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    renderMatches(data.data);
                    document.getElementById('matchCount').textContent = data.data.length;
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showAlert('danger', 'Erreur lors du chargement des matchs');
            });
        }

        // Afficher les matchs
        function renderMatches(matchs) {
            const container = document.getElementById('matchsContainer');

            if (matchs.length === 0) {
                container.innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <h5>Aucun match trouvé</h5>
                        <p>Aucun match ne correspond à vos critères de recherche</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = matchs.map(match => {
                const statusLabels = {
                    'termine': 'Terminé',
                    'en_cours': 'En cours',
                    'reporte': 'Reporté',
                    'annule': 'Annulé'
                };

                const logoHome = match.logo_domicile || 'https://via.placeholder.com/80';
                const logoAway = match.logo_exterieur || 'https://via.placeholder.com/80';

                return `
                    <div class="match-card" data-match-id="${match.id}">
                        <div class="match-header">
                            <span class="match-competition">${match.competition}</span>
                            <span class="match-date">
                                <i class="far fa-calendar me-2"></i>${formatDate(match.date_match)}
                            </span>
                        </div>
                        <div class="match-body">
                            <div class="team-section">
                                <img src="${logoHome}" alt="${match.equipe_domicile}" class="team-logo">
                                <div class="team-name">${match.equipe_domicile}</div>
                            </div>
                            <div class="score-section">
                                <div class="score-display">
                                    <span>${match.score_domicile}</span>
                                    <span class="score-separator">-</span>
                                    <span>${match.score_exterieur}</span>
                                </div>
                                <div class="match-status status-${match.statut}">
                                    ${statusLabels[match.statut] || match.statut}
                                </div>
                            </div>
                            <div class="team-section">
                                <img src="${logoAway}" alt="${match.equipe_exterieur}" class="team-logo">
                                <div class="team-name">${match.equipe_exterieur}</div>
                            </div>
                        </div>
                        <div class="match-details">
                            <div class="detail-item">
                                <div class="detail-label">Stade</div>
                                <div class="detail-value">${match.lieu}</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Compétition</div>
                                <div class="detail-value">${match.competition}</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Actions</div>
                                <div>
                                    <button class="btn-action edit" onclick="editMatch(${match.id})" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn-action delete" onclick="deleteMatch(${match.id})" title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }

        // Formater la date
        function formatDate(dateString) {
            if (!dateString) return 'Date non définie';
            const date = new Date(dateString);
            const options = { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' };
            return date.toLocaleDateString('fr-FR', options);
        }

        // Appliquer les filtres
        function applyFilters() {
            loadMatches();
        }

        // Éditer un match
        function editMatch(id) {
            fetch(`/matchs/${id}`, {
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const match = data.data;

                    document.getElementById('matchId').value = match.id;
                    document.getElementById('competition_id').value = match.competition_id || '';
                    document.getElementById('saison_id').value = match.saison_id;
                    document.getElementById('date_match').value = match.date_match;
                    document.getElementById('equipe_domicile_id').value = match.equipe_domicile_id;
                    document.getElementById('buts_domicile').value = match.score_domicile;
                    document.getElementById('equipe_exterieur_id').value = match.equipe_exterieur_id;
                    document.getElementById('buts_exterieur').value = match.score_exterieur;
                    document.getElementById('lieu').value = match.lieu || '';
                    document.getElementById('type_match').value = match.type_match || 'officiel';
                    document.getElementById('statut').value = match.statut;
                    document.getElementById('notes').value = match.notes || '';

                    document.getElementById('formTitle').textContent = 'Modifier le Match';
                    document.getElementById('btnText').textContent = 'Mettre à jour';
                    document.getElementById('cancelBtn').style.display = 'block';

                    // Scroll vers le formulaire
                    document.querySelector('.content-card').scrollIntoView({ behavior: 'smooth' });
                } else {
                    showAlert('danger', 'Erreur lors du chargement du match');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showAlert('danger', 'Erreur lors de la communication avec le serveur');
            });
        }

        // Annuler l'édition
        function cancelEdit() {
            document.getElementById('matchForm').reset();
            document.getElementById('matchId').value = '';
            document.getElementById('formTitle').textContent = 'Enregistrer un Résultat';
            document.getElementById('btnText').textContent = 'Enregistrer';
            document.getElementById('cancelBtn').style.display = 'none';
        }

        // Supprimer un match
        function deleteMatch(id) {
            if (!confirm('Êtes-vous sûr de vouloir supprimer ce match ?')) {
                return;
            }

            fetch(`/matchs/${id}`, {
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
                    const matchCard = document.querySelector(`[data-match-id="${id}"]`);
                    if (matchCard) {
                        matchCard.style.transition = 'all 0.3s';
                        matchCard.style.opacity = '0';
                        matchCard.style.transform = 'translateX(-20px)';

                        setTimeout(() => {
                            matchCard.remove();
                            loadStats();

                            // Vérifier s'il reste des matchs
                            if (document.querySelectorAll('.match-card').length === 0) {
                                document.getElementById('matchsContainer').innerHTML = `
                                    <div class="empty-state">
                                        <i class="fas fa-inbox"></i>
                                        <h5>Aucun match disponible</h5>
                                        <p>Créez votre premier match avec le formulaire ci-contre</p>
                                    </div>
                                `;
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

            // Auto-hide après 5 secondes
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000);
        }

        // Validation côté client pour empêcher les mêmes équipes
        document.getElementById('equipe_domicile_id').addEventListener('change', validateTeams);
        document.getElementById('equipe_exterieur_id').addEventListener('change', validateTeams);

        function validateTeams() {
            const domicile = document.getElementById('equipe_domicile_id').value;
            const exterieur = document.getElementById('equipe_exterieur_id').value;

            if (domicile && exterieur && domicile === exterieur) {
                showAlert('danger', 'Les deux équipes doivent être différentes');
                document.getElementById('equipe_exterieur_id').value = '';
            }
        }

    </script>
</body>
</html>
