<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Football Gabonais</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --gabon-green: #009E60;
            --gabon-yellow: #FCD116;
            --gabon-blue: #3A75C4;
            --dark-green: #006B3F;
            --sidebar-width: 280px;
        }

        * {
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e8eff5 100%);
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

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }

        /* Top Navbar */
        .top-navbar {
            background: white;
            border-bottom: 4px solid var(--gabon-yellow);
            padding: 1.2rem 2rem;
            position: sticky;
            top: 0;
            z-index: 999;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .flag-colors {
            display: flex;
            height: 5px;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
        }

        .flag-green { flex: 1; background: var(--gabon-green); }
        .flag-yellow { flex: 1; background: var(--gabon-yellow); }
        .flag-blue { flex: 1; background: var(--gabon-blue); }

        /* Stats Cards */
        .stats-card {
            border: none;
            border-radius: 1.25rem;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            height: 100%;
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
        }

        .stats-card.primary::before { background: var(--gabon-blue); }
        .stats-card.success::before { background: var(--gabon-green); }
        .stats-card.warning::before { background: var(--gabon-yellow); }
        .stats-card.info::before { background: #17a2b8; }

        .stats-card:hover {
            transform: translateY(-12px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0,0,0,0.2) !important;
        }

        .stats-card .card-body {
            padding: 2rem;
            background: white;
        }

        .stats-icon {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .stats-card.primary .stats-icon {
            background: linear-gradient(135deg, var(--gabon-blue) 0%, #2d5fa3 100%);
            color: white;
        }

        .stats-card.success .stats-icon {
            background: linear-gradient(135deg, var(--gabon-green) 0%, var(--dark-green) 100%);
            color: white;
        }

        .stats-card.warning .stats-icon {
            background: linear-gradient(135deg, var(--gabon-yellow) 0%, #e0b614 100%);
            color: #856404;
        }

        .stats-card.info .stats-icon {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white;
        }

        /* Custom Cards */
        .custom-card {
            border: none;
            border-radius: 1.25rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            transition: all 0.3s;
            overflow: hidden;
        }

        .custom-card:hover {
            box-shadow: 0 10px 35px rgba(0,0,0,0.15);
            transform: translateY(-5px);
        }

        .card-header-custom {
            background: linear-gradient(135deg, var(--gabon-green) 0%, var(--dark-green) 100%);
            color: white;
            border: none;
            padding: 1.5rem 1.75rem;
            position: relative;
            overflow: hidden;
        }

        .card-header-custom::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        }

        .card-header-custom h5 {
            margin: 0;
            font-weight: 600;
            position: relative;
            z-index: 1;
        }

        .card-header-custom i {
            color: var(--gabon-yellow);
        }

        /* Team Logos */
        .team-logo {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.85rem;
            margin-right: 12px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.15);
            background: white;
            border: 3px solid;
        }

        .team-logo.fc {
            border-color: var(--gabon-green);
            color: var(--gabon-green);
            background: linear-gradient(135deg, rgba(0,158,96,0.1) 0%, white 100%);
        }
        .team-logo.ms {
            border-color: var(--gabon-blue);
            color: var(--gabon-blue);
            background: linear-gradient(135deg, rgba(58,117,196,0.1) 0%, white 100%);
        }
        .team-logo.ap {
            border-color: var(--gabon-yellow);
            color: #856404;
            background: linear-gradient(135deg, rgba(252,209,22,0.2) 0%, white 100%);
        }
        .team-logo.bs {
            border-color: #dc3545;
            color: #dc3545;
            background: linear-gradient(135deg, rgba(220,53,69,0.1) 0%, white 100%);
        }
        .team-logo.ub {
            border-color: #6f42c1;
            color: #6f42c1;
            background: linear-gradient(135deg, rgba(111,66,193,0.1) 0%, white 100%);
        }
        .team-logo.sm {
            border-color: #fd7e14;
            color: #fd7e14;
            background: linear-gradient(135deg, rgba(253,126,20,0.1) 0%, white 100%);
        }

        /* Position Badges */
        .position-badge {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.95rem;
            box-shadow: 0 3px 10px rgba(0,0,0,0.2);
        }

        .badge-gold {
            background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
            color: #856404;
            border: 2px solid #FFD700;
        }

        .badge-silver {
            background: linear-gradient(135deg, #C0C0C0 0%, #A8A8A8 100%);
            color: #495057;
            border: 2px solid #C0C0C0;
        }

        .badge-bronze {
            background: linear-gradient(135deg, #CD7F32 0%, #B8732D 100%);
            color: #fff;
            border: 2px solid #CD7F32;
        }

        /* Match Items */
        .match-item {
            border-left: 6px solid;
            border-radius: 1rem;
            transition: all 0.3s;
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .match-item:hover {
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            transform: translateX(10px);
        }

        .match-item.result {
            border-left-color: #dc3545;
            background: linear-gradient(90deg, rgba(220,53,69,0.1) 0%, white 20%);
        }

        .match-item.upcoming {
            border-left-color: var(--gabon-green);
            background: linear-gradient(90deg, rgba(0,158,96,0.1) 0%, white 20%);
        }

        .match-score {
            font-size: 2rem;
            font-weight: 700;
            color: #212529;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }

        /* News Cards */
        .news-card {
            border: none;
            border-radius: 1.25rem;
            overflow: hidden;
            transition: all 0.4s;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            background: white;
        }

        .news-card:hover {
            transform: translateY(-15px) scale(1.02);
            box-shadow: 0 20px 50px rgba(0,0,0,0.25);
        }

        .news-card img {
            height: 220px;
            object-fit: cover;
            transition: transform 0.5s;
        }

        .news-card:hover img {
            transform: scale(1.1);
        }

        .news-img-container {
            overflow: hidden;
            height: 220px;
            position: relative;
        }

        .news-img-container::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(180deg, transparent 0%, rgba(0,0,0,0.3) 100%);
        }

        /* Table Styling */
        .table-custom {
            margin: 0;
        }

        .table-custom thead th {
            background: linear-gradient(135deg, var(--gabon-green) 0%, var(--dark-green) 100%);
            color: white;
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: none;
            padding: 1rem;
        }

        .table-custom tbody tr {
            transition: all 0.2s;
            border-bottom: 1px solid #f0f0f0;
        }

        .table-custom tbody tr:hover {
            background: linear-gradient(90deg, rgba(0,158,96,0.08) 0%, transparent 100%);
            transform: scale(1.01);
        }

        .table-custom tbody td {
            padding: 1rem;
            vertical-align: middle;
        }

        /* Badges */
        .badge-gabon {
            background: linear-gradient(135deg, var(--gabon-green) 0%, var(--dark-green) 100%);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            font-weight: 600;
            font-size: 0.85rem;
            box-shadow: 0 3px 10px rgba(0,158,96,0.3);
        }

        .badge-competition {
            background: linear-gradient(135deg, var(--gabon-yellow) 0%, #f0c000 100%);
            color: #856404;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            font-weight: 600;
            font-size: 0.85rem;
            box-shadow: 0 3px 10px rgba(252,209,22,0.3);
        }

        /* Responsive */
        @media (max-width: 991.98px) {
            .sidebar { margin-left: calc(-1 * var(--sidebar-width)); }
            .sidebar.show { margin-left: 0; }
            .main-content { margin-left: 0; }
            .mobile-toggle { display: block !important; }
        }

        .mobile-toggle { display: none; }

        /* Animations */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(-30px); }
            to { opacity: 1; transform: translateX(0); }
        }

        .animate-fade-in {
            animation: fadeInUp 0.6s ease-out;
        }

        .animate-slide-in {
            animation: slideInRight 0.6s ease-out;
        }

        /* Scrollbar */
        .sidebar::-webkit-scrollbar { width: 6px; }
        .sidebar::-webkit-scrollbar-track { background: rgba(255,255,255,0.1); }
        .sidebar::-webkit-scrollbar-thumb {
            background: var(--gabon-yellow);
            border-radius: 3px;
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
        <nav class="top-navbar">
            <div class="flag-colors">
                <div class="flag-green"></div>
                <div class="flag-yellow"></div>
                <div class="flag-blue"></div>
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <button class="btn btn-link mobile-toggle d-lg-none text-dark" onclick="toggleSidebar()">
                        <i class="fas fa-bars fs-4"></i>
                    </button>
                    <h4 class="mb-0 d-inline-block fw-bold">Tableau de bord</h4>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <button class="btn btn-light position-relative">
                        <i class="fas fa-bell"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            3
                        </span>
                    </button>
                    <div class="dropdown">
                        <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-2"></i> Administrateur
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i> Mon Profil</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i> Paramètres</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="#"><i class="fas fa-sign-out-alt me-2"></i> Déconnexion</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Content Area -->
        <div class="container-fluid p-4">
            <!-- Statistics Cards -->
            <div class="row g-4 mb-4 animate-fade-in">
                <div class="col-lg-3 col-md-6">
                    <div class="card stats-card primary shadow-lg">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-2 text-muted fw-medium">Total Équipes</p>
                                <h2 class="mb-1 fw-bold" style="color: var(--gabon-blue)">16</h2>
                                <small class="text-success fw-semibold"><i class="fas fa-arrow-up"></i> +2 cette saison</small>
                            </div>
                            <div class="stats-icon">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="card stats-card success shadow-lg">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-2 text-muted fw-medium">Total Matchs</p>
                                <h2 class="mb-1 fw-bold" style="color: var(--gabon-green)">124</h2>
                                <small class="text-success fw-semibold"><i class="fas fa-arrow-up"></i> 18 ce mois</small>
                            </div>
                            <div class="stats-icon">
                                <i class="fas fa-futbol"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="card stats-card warning shadow-lg">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-2 text-muted fw-medium">Cette Semaine</p>
                                <h2 class="mb-1 fw-bold" style="color: #856404">8</h2>
                                <small class="text-info fw-semibold"><i class="fas fa-calendar"></i> 3 à venir</small>
                            </div>
                            <div class="stats-icon">
                                <i class="fas fa-calendar-week"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="card stats-card info shadow-lg">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-2 text-muted fw-medium">Actualités</p>
                                <h2 class="mb-1 fw-bold" style="color: #17a2b8">42</h2>
                                <small class="text-success fw-semibold"><i class="fas fa-arrow-up"></i> 5 aujourd'hui</small>
                            </div>
                            <div class="stats-icon">
                                <i class="fas fa-newspaper"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts and Tables Row -->
            <div class="row g-4 mb-4">
                <!-- Classement -->
                <div class="col-lg-6 animate-slide-in">
                    <div class="card custom-card h-100">
                        <div class="card-header-custom d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-trophy fs-4 me-2"></i>
                                <h5>Classement Championnat D1</h5>
                            </div>
                            <span class="badge bg-warning text-dark">En Direct</span>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-custom mb-0">
                                    <thead>
                                        <tr>
                                            <th style="width: 60px;">Pos</th>
                                            <th>Équipe</th>
                                            <th class="text-center" style="width: 60px;">MJ</th>
                                            <th class="text-center" style="width: 50px;">V</th>
                                            <th class="text-center" style="width: 50px;">N</th>
                                            <th class="text-center" style="width: 50px;">D</th>
                                            <th class="text-center" style="width: 80px;">Pts</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><span class="position-badge badge-gold">1</span></td>
                                            <td class="fw-semibold">
                                                <span class="team-logo fc">FC</span>
                                                FC 105 Libreville
                                            </td>
                                            <td class="text-center fw-semibold">15</td>
                                            <td class="text-center">9</td>
                                            <td class="text-center">1</td>
                                            <td class="text-center">5</td>
                                            <td class="text-center"><span class="badge badge-gabon">28</span></td>
                                        </tr>
                                        <tr>
                                            <td><span class="position-badge bg-light text-dark border">5</span></td>
                                            <td class="fw-semibold">
                                                <span class="team-logo ub">UB</span>
                                                US Bitam
                                            </td>
                                            <td class="text-center fw-semibold">15</td>
                                            <td class="text-center">8</td>
                                            <td class="text-center">2</td>
                                            <td class="text-center">5</td>
                                            <td class="text-center"><span class="badge badge-gabon">26</span></td>
                                        </tr>
                                        <tr>
                                            <td><span class="position-badge bg-light text-dark border">6</span></td>
                                            <td class="fw-semibold">
                                                <span class="team-logo sm">SM</span>
                                                AS Stade Mandji
                                            </td>
                                            <td class="text-center fw-semibold">15</td>
                                            <td class="text-center">7</td>
                                            <td class="text-center">3</td>
                                            <td class="text-center">5</td>
                                            <td class="text-center"><span class="badge badge-gabon">24</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer bg-light text-center border-0 py-3">
                            <a href="#" class="text-decoration-none fw-semibold" style="color: var(--gabon-green)">
                                Voir le classement complet <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Chart -->
                <div class="col-lg-6 animate-slide-in">
                    <div class="card custom-card h-100">
                        <div class="card-header-custom d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-chart-bar fs-4 me-2"></i>
                                <h5>Statistiques des matchs</h5>
                            </div>
                            <span class="badge bg-light text-dark">6 derniers mois</span>
                        </div>
                        <div class="card-body">
                            <canvas id="matchsChart" height="300"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Matchs Row -->
            <div class="row g-4 mb-4">
                <!-- Derniers Résultats -->
                <div class="col-lg-6">
                    <div class="card custom-card">
                        <div class="card-header-custom d-flex align-items-center">
                            <i class="fas fa-history fs-4 me-2"></i>
                            <h5>Derniers Résultats</h5>
                        </div>
                        <div class="card-body">
                            <div class="match-item result p-3 mb-3">
                                <div class="row align-items-center">
                                    <div class="col-5">
                                        <div class="fw-bold d-flex align-items-center">
                                            <span class="team-logo fc">FC</span>
                                            <div>
                                                <div>FC 105 Libreville</div>
                                                <small class="text-muted">vs Mangasport</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3 text-center">
                                        <div class="match-score">2 - 1</div>
                                        <small class="text-muted fw-semibold">28 Sept 2024</small>
                                    </div>
                                    <div class="col-4 text-end">
                                        <span class="badge badge-competition mb-1">Championnat D1</span>
                                        <div><small class="text-success"><i class="fas fa-check-circle"></i> Terminé</small></div>
                                    </div>
                                </div>
                            </div>

                            <div class="match-item result p-3 mb-3">
                                <div class="row align-items-center">
                                    <div class="col-5">
                                        <div class="fw-bold d-flex align-items-center">
                                            <span class="team-logo ap">AP</span>
                                            <div>
                                                <div>AS Pelican</div>
                                                <small class="text-muted">vs Bouenguidi Sport</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3 text-center">
                                        <div class="match-score">1 - 1</div>
                                        <small class="text-muted fw-semibold">27 Sept 2024</small>
                                    </div>
                                    <div class="col-4 text-end">
                                        <span class="badge badge-competition mb-1">Championnat D1</span>
                                        <div><small class="text-success"><i class="fas fa-check-circle"></i> Terminé</small></div>
                                    </div>
                                </div>
                            </div>

                            <div class="match-item result p-3 mb-3">
                                <div class="row align-items-center">
                                    <div class="col-5">
                                        <div class="fw-bold d-flex align-items-center">
                                            <span class="team-logo ub">UB</span>
                                            <div>
                                                <div>US Bitam</div>
                                                <small class="text-muted">vs AS Stade Mandji</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3 text-center">
                                        <div class="match-score">3 - 0</div>
                                        <small class="text-muted fw-semibold">26 Sept 2024</small>
                                    </div>
                                    <div class="col-4 text-end">
                                        <span class="badge badge-competition mb-1">Championnat D1</span>
                                        <div><small class="text-success"><i class="fas fa-check-circle"></i> Terminé</small></div>
                                    </div>
                                </div>
                            </div>

                            <div class="match-item result p-3">
                                <div class="row align-items-center">
                                    <div class="col-5">
                                        <div class="fw-bold d-flex align-items-center">
                                            <span class="team-logo ms">MS</span>
                                            <div>
                                                <div>Mangasport</div>
                                                <small class="text-muted">vs AS Pelican</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3 text-center">
                                        <div class="match-score">2 - 2</div>
                                        <small class="text-muted fw-semibold">25 Sept 2024</small>
                                    </div>
                                    <div class="col-4 text-end">
                                        <span class="badge badge-competition mb-1">Championnat D1</span>
                                        <div><small class="text-success"><i class="fas fa-check-circle"></i> Terminé</small></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Prochains Matchs -->
                <div class="col-lg-6">
                    <div class="card custom-card">
                        <div class="card-header-custom d-flex align-items-center">
                            <i class="fas fa-calendar-check fs-4 me-2"></i>
                            <h5>Prochains Matchs</h5>
                        </div>
                        <div class="card-body">
                            <div class="match-item upcoming p-3 mb-3">
                                <div class="row align-items-center">
                                    <div class="col-5">
                                        <div class="fw-bold d-flex align-items-center">
                                            <span class="team-logo fc">FC</span>
                                            <div>
                                                <div>FC 105 Libreville</div>
                                                <small class="text-muted">vs AS Pelican</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3 text-center">
                                        <div class="match-score">VS</div>
                                        <small class="text-muted fw-semibold">05 Oct 2024</small>
                                        <div><small class="badge bg-info text-white mt-1"><i class="fas fa-clock"></i> 15:00</small></div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <span class="badge badge-gabon mb-1">Championnat D1</span>
                                        <div><small class="text-warning"><i class="fas fa-calendar"></i> À venir</small></div>
                                    </div>
                                </div>
                            </div>

                            <div class="match-item upcoming p-3 mb-3">
                                <div class="row align-items-center">
                                    <div class="col-5">
                                        <div class="fw-bold d-flex align-items-center">
                                            <span class="team-logo ms">MS</span>
                                            <div>
                                                <div>Mangasport</div>
                                                <small class="text-muted">vs Bouenguidi Sport</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3 text-center">
                                        <div class="match-score">VS</div>
                                        <small class="text-muted fw-semibold">06 Oct 2024</small>
                                        <div><small class="badge bg-info text-white mt-1"><i class="fas fa-clock"></i> 17:00</small></div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <span class="badge badge-gabon mb-1">Championnat D1</span>
                                        <div><small class="text-warning"><i class="fas fa-calendar"></i> À venir</small></div>
                                    </div>
                                </div>
                            </div>

                            <div class="match-item upcoming p-3 mb-3">
                                <div class="row align-items-center">
                                    <div class="col-5">
                                        <div class="fw-bold d-flex align-items-center">
                                            <span class="team-logo ub">UB</span>
                                            <div>
                                                <div>US Bitam</div>
                                                <small class="text-muted">vs FC 105 Libreville</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3 text-center">
                                        <div class="match-score">VS</div>
                                        <small class="text-muted fw-semibold">07 Oct 2024</small>
                                        <div><small class="badge bg-info text-white mt-1"><i class="fas fa-clock"></i> 16:30</small></div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <span class="badge badge-gabon mb-1">Championnat D1</span>
                                        <div><small class="text-warning"><i class="fas fa-calendar"></i> À venir</small></div>
                                    </div>
                                </div>
                            </div>

                            <div class="match-item upcoming p-3">
                                <div class="row align-items-center">
                                    <div class="col-5">
                                        <div class="fw-bold d-flex align-items-center">
                                            <span class="team-logo sm">SM</span>
                                            <div>
                                                <div>AS Stade Mandji</div>
                                                <small class="text-muted">vs AS Pelican</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3 text-center">
                                        <div class="match-score">VS</div>
                                        <small class="text-muted fw-semibold">08 Oct 2024</small>
                                        <div><small class="badge bg-info text-white mt-1"><i class="fas fa-clock"></i> 14:00</small></div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <span class="badge bg-danger text-white mb-1"><i class="fas fa-trophy"></i> Coupe du Gabon</span>
                                        <div><small class="text-warning"><i class="fas fa-calendar"></i> À venir</small></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actualités -->
            <div class="row">
                <div class="col-12">
                    <div class="card custom-card">
                        <div class="card-header-custom d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-newspaper fs-4 me-2"></i>
                                <h5>Dernières Actualités</h5>
                            </div>
                            <a href="#" class="btn btn-sm text-white fw-semibold" style="background: var(--gabon-yellow); color: #856404 !important;">
                                <i class="fas fa-plus me-1"></i> Nouvelle actualité
                            </a>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-4">
                                <div class="col-lg-4 col-md-6">
                                    <div class="card news-card">
                                        <div class="news-img-container">
                                            <img src="https://images.unsplash.com/photo-1574629810360-7efbbe195018?w=800&q=80" alt="Football Match">
                                        </div>
                                        <div class="card-body p-4">
                                            <div class="d-flex gap-2 mb-3">
                                                <span class="badge badge-gabon"><i class="fas fa-futbol"></i> Sport</span>
                                                <span class="badge bg-light text-dark"><i class="fas fa-fire"></i> Populaire</span>
                                            </div>
                                            <h6 class="card-title fw-bold mb-3">Victoire éclatante du FC 105 face à Mangasport</h6>
                                            <p class="card-text text-muted small mb-3">
                                                Le FC 105 Libreville a remporté une victoire importante contre Mangasport (2-1) lors de la 15ème journée du championnat national...
                                            </p>
                                            <hr class="my-3">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex align-items-center">
                                                    <img src="https://i.pravatar.cc/40?img=12" class="rounded-circle me-2" width="30" height="30" alt="Author">
                                                    <small class="text-muted">Jean Dupont</small>
                                                </div>
                                                <small class="text-muted"><i class="fas fa-clock me-1"></i> 2h</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6">
                                    <div class="card news-card">
                                        <div class="news-img-container">
                                            <img src="https://images.unsplash.com/photo-1579952363873-27f3bade9f55?w=800&q=80" alt="National Team">
                                        </div>
                                        <div class="card-body p-4">
                                            <div class="d-flex gap-2 mb-3">
                                                <span class="badge badge-competition"><i class="fas fa-flag"></i> National</span>
                                                <span class="badge bg-danger text-white"><i class="fas fa-bolt"></i> Urgent</span>
                                            </div>
                                            <h6 class="card-title fw-bold mb-3">Les Panthers se préparent pour la Coupe d'Afrique</h6>
                                            <p class="card-text text-muted small mb-3">
                                                L'équipe nationale du Gabon intensifie ses préparatifs en vue de la prochaine Coupe d'Afrique des Nations...
                                            </p>
                                            <hr class="my-3">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex align-items-center">
                                                    <img src="https://i.pravatar.cc/40?img=27" class="rounded-circle me-2" width="30" height="30" alt="Author">
                                                    <small class="text-muted">Marie Ondo</small>
                                                </div>
                                                <small class="text-muted"><i class="fas fa-clock me-1"></i> 5h</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6">
                                    <div class="card news-card">
                                        <div class="news-img-container">
                                            <img src="https://images.unsplash.com/photo-1508098682722-e99c43a406b2?w=800&q=80" alt="Stadium">
                                        </div>
                                        <div class="card-body p-4">
                                            <div class="d-flex gap-2 mb-3">
                                                <span class="badge bg-info text-white"><i class="fas fa-building"></i> Infrastructure</span>
                                                <span class="badge bg-success text-white"><i class="fas fa-check"></i> Nouveau</span>
                                            </div>
                                            <h6 class="card-title fw-bold mb-3">Nouveau stade inauguré à Port-Gentil</h6>
                                            <p class="card-text text-muted small mb-3">
                                                Un nouveau stade ultramoderne de 15 000 places a été inauguré à Port-Gentil, renforçant les infrastructures sportives du pays...
                                            </p>
                                            <hr class="my-3">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex align-items-center">
                                                    <img src="https://i.pravatar.cc/40?img=33" class="rounded-circle me-2" width="30" height="30" alt="Author">
                                                    <small class="text-muted">Paul Nguema</small>
                                                </div>
                                                <small class="text-muted"><i class="fas fa-clock me-1"></i> 1j</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Toggle Sidebar for Mobile
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
        }

        // Chart.js Configuration
        const ctx = document.getElementById('matchsChart').getContext('2d');

        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(0, 158, 96, 0.8)');
        gradient.addColorStop(1, 'rgba(0, 107, 63, 0.4)');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre', 'Janvier'],
                datasets: [{
                    label: 'Nombre de matchs',
                    data: [12, 18, 16, 14, 10, 8],
                    backgroundColor: gradient,
                    borderColor: 'rgba(0, 158, 96, 1)',
                    borderWidth: 2,
                    borderRadius: 10,
                    hoverBackgroundColor: 'rgba(0, 158, 96, 1)',
                    hoverBorderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 107, 63, 0.95)',
                        padding: 15,
                        cornerRadius: 10,
                        titleFont: {
                            size: 15,
                            weight: 'bold',
                            family: 'Poppins'
                        },
                        bodyFont: {
                            size: 14,
                            family: 'Poppins'
                        },
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return '⚽ Matchs joués: ' + context.parsed.y;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 3,
                            font: {
                                size: 12,
                                family: 'Poppins'
                            },
                            color: '#6c757d'
                        },
                        grid: {
                            color: 'rgba(0, 158, 96, 0.1)',
                            drawBorder: false
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 12,
                                family: 'Poppins',
                                weight: '500'
                            },
                            color: '#495057'
                        },
                        grid: {
                            display: false
                        }
                    }
                },
                animation: {
                    duration: 2000,
                    easing: 'easeInOutQuart'
                }
            }
        });

        // Animation au scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -100px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '0';
                    entry.target.style.transform = 'translateY(30px)';

                    setTimeout(() => {
                        entry.target.style.transition = 'all 0.6s ease-out';
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }, 100);
                }
            });
        }, observerOptions);

        document.querySelectorAll('.custom-card, .news-card, .match-item').forEach((el, index) => {
            el.style.transitionDelay = `${index * 0.1}s`;
            observer.observe(el);
        });
    </script>
</body>
</html>
