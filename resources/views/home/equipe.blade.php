<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Équipes - Actu Foot Gabon</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
            gap: 2.5rem;
            max-width: 1400px;
            margin: 0 auto;
        }

        @media (max-width: 768px) {
            .team-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
        }

        .team-card {
            background: white;
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            width: 100%;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .team-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }

        .team-header {
            padding: 2.5rem 2rem;
            text-align: center;
            position: relative;
            background: linear-gradient(135deg, var(--color-start), var(--color-end));
            min-height: 350px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        @media (max-width: 768px) {
            .team-header {
                min-height: 300px;
            }
        }

        .team-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 30% 50%, rgba(255,255,255,0.1) 0%, transparent 50%);
            pointer-events: none;
        }

        .team-logo-container {
            width: 140px;
            height: 140px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            padding: 0;
            box-shadow: 0 8px 16px rgba(0,0,0,0.15);
            transition: transform 0.4s ease;
            position: relative;
            z-index: 2;
            overflow: hidden;
        }

        .team-card:hover .team-logo-container {
            transform: scale(1.1) rotate(5deg);
        }

        .team-logo-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        .team-logo-container i {
            font-size: 4rem;
            color: #d1d5db;
        }

        .team-name {
            font-size: 1.75rem;
            font-weight: 800;
            color: white;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
            margin: 0;
            position: relative;
            z-index: 2;
            text-align: center;
        }

        .team-body {
            padding: 2rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .team-info {
            margin-bottom: 1.5rem;
        }

        .info-item {
            display: flex;
            align-items: flex-start;
            padding: 0.875rem 1rem;
            background: #f9fafb;
            border-radius: 0.75rem;
            margin-bottom: 0.75rem;
            transition: all 0.3s;
        }

        .info-item:hover {
            background: #f3f4f6;
            transform: translateX(5px);
        }

        .info-item i {
            color: #16a34a;
            font-size: 1.25rem;
            margin-right: 1rem;
            min-width: 24px;
            margin-top: 2px;
        }

        .info-content {
            flex: 1;
        }

        .info-label {
            font-weight: 600;
            color: #374151;
            font-size: 0.95rem;
            margin-bottom: 0.25rem;
        }

        .info-value {
            color: #6b7280;
            font-size: 0.9rem;
        }

        .team-description {
            color: #6b7280;
            font-size: 0.95rem;
            line-height: 1.7;
            margin-bottom: 1.5rem;
            flex: 1;
        }

        .team-btn {
            width: 100%;
            background: linear-gradient(135deg, #16a34a, #22c55e);
            color: white;
            padding: 1rem;
            border-radius: 0.75rem;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .team-btn:hover {
            background: linear-gradient(135deg, #15803d, #16a34a);
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(22, 163, 74, 0.3);
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: white;
            border-radius: 1rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            grid-column: 1 / -1;
        }

        /* Couleurs prédéfinies pour les équipes */
        .color-blue { --color-start: #2563eb; --color-end: #1d4ed8; }
        .color-green { --color-start: #16a34a; --color-end: #15803d; }
        .color-red { --color-start: #dc2626; --color-end: #b91c1c; }
        .color-purple { --color-start: #9333ea; --color-end: #7e22ce; }
        .color-orange { --color-start: #ea580c; --color-end: #c2410c; }
        .color-indigo { --color-start: #4f46e5; --color-end: #4338ca; }
        .color-pink { --color-start: #ec4899; --color-end: #db2777; }
        .color-teal { --color-start: #14b8a6; --color-end: #0f766e; }
        .color-yellow { --color-start: #eab308; --color-end: #ca8a04; }
        .color-cyan { --color-start: #06b6d4; --color-end: #0891b2; }

        .gradient-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 50%;
            background: linear-gradient(to top, rgba(0,0,0,0.3), transparent);
            pointer-events: none;
        }

        .ville-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: white;
            font-size: 0.9rem;
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            z-index: 10;
            font-weight: 600;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans">
    <!-- Header -->
    <header class="bg-blue-800 text-white shadow-lg">
        <div class="container mx-auto px-4 py-6">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="flex items-center mb-4 md:mb-0">
                    <i class="fas fa-futbol text-3xl mr-3"></i>
                    <h1 class="text-2xl md:text-3xl font-bold">Actu Foot Gabon</h1>
                </div>
                <nav class="flex space-x-1 md:space-x-6">
                    <a href="{{ route('home') }}" class="px-3 py-2 rounded hover:bg-green-700 font-medium transition">Accueil</a>
                    <a href="{{ route('match') }}" class="px-3 py-2 rounded hover:bg-green-700 font-medium transition">Matchs</a>
                    <a href="{{ route('interviews') }}" class="px-3 py-2 rounded bg-green-700 font-medium transition">Interviews</a>
                    <a href="{{ route('about') }}" class="px-3 py-2 rounded hover:bg-green-700 font-medium transition">A propos</a>
                    {{-- <a href="{{ route('equipe') }}" class="px-3 py-2 rounded hover:bg-green-700 font-medium transition">Equipes</a> --}}
                </nav>
            </div>
        </div>
    </header>

    <!-- Hero -->
    <section class="relative text-white py-16" style="background-image: url('https://images.unsplash.com/photo-1574629810360-7efbbe195018?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1920&q=80'); background-size: cover; background-position: center; background-repeat: no-repeat;">
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <div class="container mx-auto px-4 relative z-10">
            <div class="flex flex-col items-center text-center">
                <div class="max-w-3xl">
                    <h2 class="text-3xl md:text-4xl font-bold mb-4">Nos Équipes</h2>
                    <p class="text-lg mb-6">Découvrez les clubs du football gabonais et leurs palmarès.</p>
                    <div class="flex space-x-4 justify-center">
                        <button class="bg-white text-blue-800 px-6 py-3 rounded-lg font-bold hover:bg-gray-200 transition">
                            <i class="fas fa-bell mr-2"></i>Alertes Scores
                        </button>
                        <button class="border-2 border-white px-6 py-3 rounded-lg font-bold hover:bg-white hover:text-blue-900 transition">
                            <i class="fas fa-star mr-2"></i>Favoris
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-12">
        <!-- Teams Section -->
        <section class="mb-12">
            <div class="flex items-center justify-between mb-8 max-w-900px mx-auto">
                <div class="flex items-center">
                    <div class="w-1 h-10 bg-green-600 mr-4"></div>
                    <div>
                        <h2 class="text-4xl font-bold text-gray-800">Les Équipes</h2>
                        <p class="text-gray-500 mt-1">Explorez les clubs du championnat gabonais</p>
                    </div>
                </div>
                <span class="text-gray-500 bg-white px-4 py-2 rounded-full shadow-sm">
                    <i class="fas fa-shield-alt mr-2"></i>{{ $equipes->count() }} équipe(s)
                </span>
            </div>

            <!-- Teams Grid -->
            <div class="team-grid">
                @php
                    $colors = ['blue', 'green', 'red', 'purple', 'orange', 'indigo', 'pink', 'teal', 'yellow', 'cyan'];
                @endphp

                @forelse($equipes as $index => $equipe)
                <article class="team-card">
                    <div class="team-header color-{{ $colors[$index % count($colors)] }}">
                        <div class="gradient-overlay"></div>

                        @if($equipe->ville)
                        <div class="ville-badge">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>{{ $equipe->ville }}</span>
                        </div>
                        @endif

                        <div class="team-logo-container">
                            @if($equipe->logo)
                                <img src="{{ asset('storage/' . $equipe->logo) }}" alt="{{ $equipe->nom }}">
                            @else
                                <i class="fas fa-shield-alt"></i>
                            @endif
                        </div>
                        <h3 class="team-name">{{ $equipe->nom }}</h3>
                    </div>

                    <div class="team-body">
                        <div class="team-info">
                            @if($equipe->fondation)
                            <div class="info-item">
                                <i class="fas fa-calendar-alt"></i>
                                <div class="info-content">
                                    <div class="info-label">Année de création</div>
                                    <div class="info-value">{{ $equipe->fondation }}</div>
                                </div>
                            </div>
                            @endif

                            @if($equipe->stade)
                            <div class="info-item">
                                <i class="fas fa-building"></i>
                                <div class="info-content">
                                    <div class="info-label">Stade</div>
                                    <div class="info-value">{{ $equipe->stade }}</div>
                                </div>
                            </div>
                            @endif

                            @if($equipe->entraineur)
                            <div class="info-item">
                                <i class="fas fa-user-tie"></i>
                                <div class="info-content">
                                    <div class="info-label">Entraîneur</div>
                                    <div class="info-value">{{ $equipe->entraineur }}</div>
                                </div>
                            </div>
                            @endif
                        </div>

                        @if($equipe->description)
                        <p class="team-description">
                            {{ Str::limit($equipe->description, 150) }}
                        </p>
                        @else
                        <p class="team-description">
                            Club de football gabonais basé à {{ $equipe->ville ?? 'Gabon' }}.
                        </p>
                        @endif

                        <button class="team-btn" onclick="alert('Détails de {{ $equipe->nom }} - Fonctionnalité à venir')">
                        </button>
                    </div>
                </article>
                @empty
                <!-- Empty State -->
                <div class="empty-state">
                    <i class="fas fa-shield-alt fa-5x text-gray-300 mb-6"></i>
                    <h3 class="font-bold text-2xl mb-3 text-gray-600">Aucune équipe disponible</h3>
                    <p class="text-gray-500">
                        Les équipes seront affichées ici une fois ajoutées depuis l'administration.
                    </p>
                </div>
                @endforelse
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white pt-12 pb-6">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <div>
                    <h3 class="text-lg font-bold mb-4">Actu Foot Gabon</h3>
                    <p class="text-gray-400 mb-4">Le site de référence pour suivre toute l'actualité footballistique au Gabon.</p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white transition">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-4">Compétitions</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Championnat National D1</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Championnat National D2</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Coupe du Gabon</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Équipe Nationale</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-4">Clubs</h3>
                    <ul class="space-y-2">
                        @foreach($equipes->take(5) as $equipe)
                        <li><a href="#" class="text-gray-400 hover:text-white transition">{{ $equipe->nom }}</a></li>
                        @endforeach
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-4">Newsletter</h3>
                    <p class="text-gray-400 mb-4">Abonnez-vous pour recevoir les dernières actualités directement dans votre boîte mail.</p>
                    <div class="flex">
                        <input type="email" placeholder="Votre email" class="px-4 py-2 rounded-l text-gray-900 w-full focus:outline-none focus:ring-2 focus:ring-green-600">
                        <button class="bg-blue-600 px-4 py-2 rounded-r hover:bg-blue-700 transition">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-6 flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400 text-sm mb-4 md:mb-0">© 2024 Actu Foot Gabon. Tous droits réservés.</p>
                <div class="flex space-x-6">
                    <a href="#" class="text-gray-400 hover:text-white text-sm transition">Mentions légales</a>
                    <a href="#" class="text-gray-400 hover:text-white text-sm transition">Politique de confidentialité</a>
                    <a href="#" class="text-gray-400 hover:text-white text-sm transition">Conditions d'utilisation</a>
                    <a href="#" class="text-gray-400 hover:text-white text-sm transition">Contact</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
