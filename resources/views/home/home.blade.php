<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actu Foot Gabon</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .news-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        @media (min-width: 768px) {
            .news-grid {
                gap: 2.5rem;
            }
        }

        .news-card {
            background: white;
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            width: 100%;
            display: flex;
            flex-direction: column;
        }

        .news-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }

        .media-container {
            position: relative;
            width: 100%;
            padding-bottom: 56.25%; /* Ratio 16:9 */
            overflow: hidden;
            background: #1f2937;
        }

        @media (min-width: 768px) {
            .media-container {
                padding-bottom: 50%; /* Ratio plus large sur desktop */
            }
        }

        @media (min-width: 1024px) {
            .media-container {
                padding-bottom: 45%;
            }
        }

        .media-container img,
        .media-container video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .news-card:hover .media-container img:not(.playing),
        .news-card:hover .media-container video:not(.playing) {
            transform: scale(1.05);
        }

        .category-badge {
            position: absolute;
            top: 0.75rem;
            left: 0.75rem;
            z-index: 10;
            backdrop-filter: blur(10px);
            background: rgba(22, 163, 74, 0.95);
            padding: 0.4rem 0.8rem;
            border-radius: 2rem;
            font-weight: bold;
            font-size: 0.75rem;
            color: white;
            box-shadow: 0 4px 6px rgba(0,0,0,0.2);
        }

        @media (min-width: 768px) {
            .category-badge {
                top: 1rem;
                left: 1rem;
                padding: 0.5rem 1rem;
                font-size: 0.875rem;
            }
        }

        .play-button {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
            z-index: 5;
            pointer-events: auto;
        }

        @media (min-width: 768px) {
            .play-button {
                width: 80px;
                height: 80px;
            }
        }

        .play-button:hover {
            background: white;
            transform: translate(-50%, -50%) scale(1.1);
        }

        .play-button i {
            color: #16a34a;
            font-size: 1.3rem;
            margin-left: 3px;
        }

        @media (min-width: 768px) {
            .play-button i {
                font-size: 1.8rem;
                margin-left: 4px;
            }
        }

        .play-button.hidden {
            opacity: 0;
            pointer-events: none;
        }

        .video-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.3);
            pointer-events: none;
            transition: opacity 0.3s;
        }

        .video-overlay.hidden {
            opacity: 0;
        }

        .gradient-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 50%;
            background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);
            pointer-events: none;
        }

        .news-content {
            padding: 1.5rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        @media (min-width: 768px) {
            .news-content {
                padding: 2rem;
            }
        }

        @media (min-width: 1024px) {
            .news-content {
                padding: 2.5rem;
            }
        }

        .news-title {
            font-size: 1.5rem;
            line-height: 1.3;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 1rem;
            transition: color 0.3s;
        }

        @media (min-width: 768px) {
            .news-title {
                font-size: 1.75rem;
                margin-bottom: 1.25rem;
            }
        }

        @media (min-width: 1024px) {
            .news-title {
                font-size: 2rem;
                margin-bottom: 1.5rem;
            }
        }

        .news-card:hover .news-title {
            color: #16a34a;
        }

        .news-excerpt {
            font-size: 0.95rem;
            line-height: 1.8;
            color: #4b5563;
            margin-bottom: 1.25rem;
            flex: 1;
            text-align: justify;
        }

        @media (min-width: 768px) {
            .news-excerpt {
                font-size: 1.05rem;
                line-height: 1.9;
                margin-bottom: 1.5rem;
            }
        }

        /* Formatage du contenu texte */
        .news-excerpt p {
            margin-bottom: 1rem;
            color: #4b5563;
        }

        .news-excerpt p:last-child {
            margin-bottom: 0;
        }

        .news-excerpt strong,
        .news-excerpt b {
            font-weight: 700;
            color: #1f2937;
        }

        .news-excerpt em,
        .news-excerpt i {
            font-style: italic;
        }

        .news-excerpt h1,
        .news-excerpt h2,
        .news-excerpt h3,
        .news-excerpt h4,
        .news-excerpt h5,
        .news-excerpt h6 {
            font-weight: 700;
            color: #1f2937;
            margin-top: 1.5rem;
            margin-bottom: 0.75rem;
            line-height: 1.3;
        }

        .news-excerpt h1 { font-size: 1.75rem; }
        .news-excerpt h2 { font-size: 1.5rem; }
        .news-excerpt h3 { font-size: 1.25rem; }
        .news-excerpt h4 { font-size: 1.1rem; }
        .news-excerpt h5 { font-size: 1rem; }
        .news-excerpt h6 { font-size: 0.95rem; }

        .news-excerpt ul,
        .news-excerpt ol {
            margin: 1rem 0;
            padding-left: 1.5rem;
        }

        .news-excerpt li {
            margin-bottom: 0.5rem;
            color: #4b5563;
        }

        .news-excerpt ul li {
            list-style-type: disc;
        }

        .news-excerpt ol li {
            list-style-type: decimal;
        }

        .news-excerpt blockquote {
            border-left: 4px solid #16a34a;
            padding-left: 1rem;
            margin: 1.5rem 0;
            font-style: italic;
            color: #6b7280;
            background: #f9fafb;
            padding: 1rem;
            border-radius: 0.5rem;
        }

        .news-excerpt img {
            max-width: 100%;
            height: auto;
            border-radius: 0.5rem;
            margin: 1.5rem 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .news-excerpt br {
            content: "";
            display: block;
            margin: 0.5rem 0;
        }

        .stats-container {
            display: flex;
            gap: 0.75rem;
            margin-bottom: 1rem;
            flex-wrap: wrap;
        }

        @media (min-width: 768px) {
            .stats-container {
                gap: 1rem;
            }
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.6rem 0.8rem;
            background: #f9fafb;
            border-radius: 0.5rem;
            flex: 1;
            min-width: 120px;
        }

        @media (min-width: 768px) {
            .stat-item {
                padding: 0.75rem 1rem;
            }
        }

        .stat-item i {
            color: #16a34a;
            font-size: 1rem;
        }

        .read-more-btn {
            width: 100%;
            background: linear-gradient(135deg, #16a34a, #22c55e);
            color: white;
            padding: 0.9rem;
            border-radius: 0.75rem;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 0.95rem;
        }

        @media (min-width: 768px) {
            .read-more-btn {
                padding: 1rem;
                font-size: 1rem;
            }
        }

        .read-more-btn:hover {
            background: linear-gradient(135deg, #15803d, #16a34a);
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(22, 163, 74, 0.3);
        }

        .empty-state {
            text-align: center;
            padding: 3rem 1.5rem;
            background: white;
            border-radius: 1rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        @media (min-width: 768px) {
            .empty-state {
                padding: 4rem 2rem;
            }
        }

        .date-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            color: #6b7280;
            font-size: 0.75rem;
            position: absolute;
            top: 0.75rem;
            right: 0.75rem;
            background: rgba(255, 255, 255, 0.95);
            padding: 0.4rem 0.8rem;
            border-radius: 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            z-index: 10;
        }

        @media (min-width: 768px) {
            .date-badge {
                top: 1rem;
                right: 1rem;
                gap: 0.5rem;
                font-size: 0.85rem;
                padding: 0.5rem 1rem;
            }
        }

        .section-header {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .hero-content {
            padding: 0 1rem;
        }

        @media (max-width: 640px) {
            .hero-content h2 {
                font-size: 1.75rem;
            }

            .hero-content p {
                font-size: 1rem;
            }

            .hero-content .flex {
                flex-direction: column;
            }

            .hero-content button {
                width: 100%;
            }
        }

        /* Contrôles vidéo personnalisés */
        video::-webkit-media-controls {
            display: flex !important;
        }

        video::-webkit-media-controls-panel {
            background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
        }

        /* Styles pour articles sans image */
        .no-image-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        .no-image-badge {
            position: relative !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
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
            <div class="flex flex-col items-center text-center hero-content">
                <div class="max-w-3xl">
                    <h2 class="text-3xl md:text-4xl font-bold mb-4">Suivez toute l'actualité footballistique</h2>
                    <p class="text-lg mb-6">Résultats en direct, classements, analyses et toutes les dernières infos sur vos clubs et joueurs préférés.</p>
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
        <!-- Actualités Section -->
        <section class="mb-12">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-8 section-header gap-4">
                <div class="flex items-center">
                    <div class="w-1 h-10 bg-green-600 mr-4"></div>
                    <div>
                        <h2 class="text-3xl md:text-4xl font-bold text-gray-800">Actualités</h2>
                        <p class="text-gray-500 mt-1 text-sm md:text-base">Les dernières nouvelles du football gabonais</p>
                    </div>
                </div>
                <span class="text-gray-500 bg-white px-4 py-2 rounded-full shadow-sm text-sm md:text-base">
                    <i class="fas fa-newspaper mr-2"></i>{{ $actualites->count() }} article(s)
                </span>
            </div>

            <!-- News Grid -->
            <div class="news-grid">
                @forelse($actualites as $actu)
                <article class="news-card">
                    @if($actu->image)
                        <div class="media-container {{ $actu->isVideo() ? 'video-container' : '' }}">
                            @if($actu->isVideo())
                                <!-- Vidéo -->
                                <video class="news-video" preload="metadata" playsinline>
                                    <source src="{{ asset('storage/' . $actu->image) }}" type="video/mp4">
                                    Votre navigateur ne supporte pas la vidéo.
                                </video>

                                <!-- Overlay vidéo -->
                                <div class="video-overlay"></div>

                                <!-- Bouton Play -->
                                <div class="play-button">
                                    <i class="fas fa-play"></i>
                                </div>
                            @else
                                <!-- Image -->
                                <img src="{{ asset('storage/' . $actu->image) }}" alt="{{ $actu->titre }}" loading="lazy">
                            @endif

                            <div class="gradient-overlay"></div>

                            <span class="category-badge">
                                <i class="fas fa-tag mr-1"></i>{{ $actu->categorie }}
                            </span>

                            <div class="date-badge">
                                <i class="far fa-calendar"></i>
                                <span>{{ $actu->date_publication->diffForHumans() }}</span>
                            </div>
                        </div>
                    @endif

                    <div class="news-content" style="{{ !$actu->image ? 'padding-top: 2rem;' : '' }}">
                        @if(!$actu->image)
                            <div class="no-image-header">
                                <span class="category-badge no-image-badge">
                                    <i class="fas fa-tag mr-1"></i>{{ $actu->categorie }}
                                </span>
                                <div class="date-badge no-image-badge">
                                    <i class="far fa-calendar"></i>
                                    <span>{{ $actu->date_publication->diffForHumans() }}</span>
                                </div>
                            </div>
                        @endif

                        <h3 class="news-title">
                            {{ $actu->titre }}
                        </h3>

                        <div class="news-excerpt">
                            {!! strip_tags($actu->contenu, '<p><br><strong><em><ul><ol><li><h1><h2><h3><h4><h5><h6><blockquote><img>') !!}
                        </div>

                        <div class="stats-container">
                            <div class="stat-item">
                                <i class="far fa-eye"></i>
                                <span class="text-sm text-gray-600">{{ number_format($actu->vues ?? rand(100, 5000)) }}</span>
                            </div>
                            <div class="stat-item">
                                <i class="fas fa-user"></i>
                                <span class="text-sm text-gray-600">{{ Str::limit($actu->admin->nom ?? 'Admin', 20) }}</span>
                            </div>
                        </div>
                    </div>
                </article>
                @empty
                <!-- Si aucune actualité -->
                <div class="empty-state">
                    <i class="fas fa-newspaper fa-4x md:fa-5x text-gray-300 mb-6"></i>
                    <h3 class="font-bold text-xl md:text-2xl mb-3 text-gray-600">Aucune actualité disponible</h3>
                    <p class="text-gray-500 text-sm md:text-base">
                        Les actualités seront affichées ici une fois publiées depuis l'administration.
                    </p>
                </div>
                @endforelse
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white pt-12 pb-6">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-8">
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
                        <li><a href="#" class="text-gray-400 hover:text-white transition">FC 105 Libreville</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Mangasport</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">AS Pelican</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Bouenguidi Sport</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">US Bitam</a></li>
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
                <div class="flex flex-wrap justify-center gap-4 md:gap-6">
                    <a href="#" class="text-gray-400 hover:text-white text-sm transition">Mentions légales</a>
                    <a href="#" class="text-gray-400 hover:text-white text-sm transition">Politique de confidentialité</a>
                    <a href="#" class="text-gray-400 hover:text-white text-sm transition">Conditions d'utilisation</a>
                    <a href="#" class="text-gray-400 hover:text-white text-sm transition">Contact</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    <script>
        // Gestion de la lecture vidéo
        document.addEventListener('DOMContentLoaded', function() {
            const videoContainers = document.querySelectorAll('.video-container');

            videoContainers.forEach(container => {
                const video = container.querySelector('video');
                const playButton = container.querySelector('.play-button');
                const overlay = container.querySelector('.video-overlay');

                if (!video || !playButton) return;

                // Clic sur le bouton play
                playButton.addEventListener('click', function(e) {
                    e.stopPropagation();
                    playVideo();
                });

                // Clic sur l'overlay
                if (overlay) {
                    overlay.addEventListener('click', function(e) {
                        e.stopPropagation();
                        if (video.paused) {
                            playVideo();
                        }
                    });
                }

                // Fonction pour lancer la vidéo
                function playVideo() {
                    video.play();
                    playButton.classList.add('hidden');
                    if (overlay) overlay.classList.add('hidden');
                    video.classList.add('playing');
                    video.setAttribute('controls', 'controls');
                }

                // Quand la vidéo est en pause
                video.addEventListener('pause', function() {
                    if (video.currentTime === 0 || video.ended) {
                        playButton.classList.remove('hidden');
                        if (overlay) overlay.classList.remove('hidden');
                        video.classList.remove('playing');
                    }
                });

                // Quand la vidéo est en lecture
                video.addEventListener('play', function() {
                    playButton.classList.add('hidden');
                    if (overlay) overlay.classList.add('hidden');
                    video.classList.add('playing');
                });

                // Quand la vidéo se termine
                video.addEventListener('ended', function() {
                    playButton.classList.remove('hidden');
                    if (overlay) overlay.classList.remove('hidden');
                    video.classList.remove('playing');
                    video.removeAttribute('controls');
                });
            });

            // Empêcher les clics sur la carte de rediriger
            document.querySelectorAll('.news-card').forEach(card => {
                card.style.cursor = 'default';
            });
        });
    </script>
</body>
</html>
