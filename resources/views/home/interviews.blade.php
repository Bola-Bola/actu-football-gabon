<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interviews - Actu Foot Gabon</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .interview-card {
            background: white;
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .interview-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }

        .interview-image-container {
            position: relative;
            width: 100%;
            height: 300px;
            overflow: hidden;
            background: linear-gradient(135deg, #1e40af, #16a34a);
        }

        .interview-image-container img,
        .interview-image-container video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .interview-card:hover .interview-image-container img,
        .interview-card:hover .interview-image-container video {
            transform: scale(1.05);
        }

        .interview-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
            padding: 2rem 1.5rem 1rem;
        }

        .category-badge {
            position: absolute;
            top: 1rem;
            left: 1rem;
            background: rgba(22, 163, 74, 0.95);
            backdrop-filter: blur(10px);
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            font-weight: bold;
            font-size: 0.875rem;
            color: white;
            box-shadow: 0 4px 6px rgba(0,0,0,0.2);
            z-index: 10;
        }

        .interviewe-badge {
            background: white;
            padding: 0.75rem 1.25rem;
            border-radius: 0.75rem;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .interviewe-badge .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1e40af, #16a34a);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }

        .filter-btn {
            padding: 0.75rem 1.5rem;
            border-radius: 2rem;
            border: 2px solid #e5e7eb;
            background: white;
            transition: all 0.3s;
            cursor: pointer;
            font-weight: 500;
        }

        .filter-btn:hover,
        .filter-btn.active {
            background: #16a34a;
            color: white;
            border-color: #16a34a;
        }

        .stats-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: #f9fafb;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            color: #6b7280;
        }

        .stats-badge i {
            color: #16a34a;
        }

        .play-button {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 70px;
            height: 70px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
            z-index: 5;
        }

        .play-button:hover {
            background: white;
            transform: translate(-50%, -50%) scale(1.1);
        }

        .play-button i {
            color: #16a34a;
            font-size: 1.8rem;
            margin-left: 4px;
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

        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-blue-800 text-white shadow-lg">
        <div class="container mx-auto px-4 py-6">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="flex items-center mb-4 md:mb-0">
                    <i class="fas fa-futbol text-3xl mr-3"></i>
                    <h1 class="text-2xl md:text-3xl font-bold">Actu Foot Gabon</h1>
                </div>
                <nav class="flex space-x-1 md:space-x-6">
                    <a href="{{ route('home') }}" class="px-3 py-2 rounded hover:bg-green-600 font-medium transition">Accueil</a>
                    <a href="{{ route('match') }}" class="px-3 py-2 rounded hover:bg-green-600 font-medium transition">Matchs</a>
                    <a href="{{ route('interviews') }}" class="px-3 py-2 rounded bg-green-600 font-medium transition">Interviews</a>
                    <a href="{{ route('about') }}" class="px-3 py-2 rounded hover:bg-green-600 font-medium transition">A propos</a>
                </nav>
            </div>
        </div>
    </header>

    <!-- Hero -->
    <section class="relative text-white py-16" style="background-image: url('https://images.unsplash.com/photo-1574629810360-7efbbe195018?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80'); background-size: cover; background-position: center;">
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <div class="container mx-auto px-4 relative z-10">
            <div class="flex flex-col items-center text-center">
                <div class="max-w-3xl">
                    <h2 class="text-3xl md:text-4xl font-bold mb-4">Nos Interviews</h2>
                    <p class="text-lg mb-6">Découvrez les interviews exclusives des acteurs du football gabonais.</p>
                    <div class="flex space-x-4 justify-center">
                        <button class="bg-white text-blue-800 px-6 py-3 rounded-lg font-bold hover:bg-gray-200 transition">
                            <i class="fas fa-bell mr-2"></i>Alertes
                        </button>
                        <button class="border-2 border-white px-6 py-3 rounded-lg font-bold hover:bg-white hover:text-blue-900 transition">
                            <i class="fas fa-star mr-2"></i>Favoris
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

   

    <!-- Interviews Grid -->
    <main class="container mx-auto px-4 py-12">
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center">
                <div class="w-1 h-10 bg-green-600 mr-4"></div>
                <div>
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-800">Nos Interviews</h2>
                    <p class="text-gray-500 mt-1">
                        <span id="interviews-count">{{ $interviews->count() }}</span> interview(s) disponible(s)
                    </p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="interviews-grid">
            @forelse($interviews as $interview)
            <article class="interview-card" data-category="{{ $interview->categorie }}">
                <!-- Image/Video Container -->
                <div class="interview-image-container">
                    @if($interview->image)
                        @if($interview->isVideo())
                            <div class="video-container h-full">
                                <video preload="metadata" class="w-full h-full object-cover">
                                    <source src="{{ asset('storage/' . $interview->image) }}" type="video/mp4">
                                    Votre navigateur ne supporte pas la lecture de vidéos.
                                </video>
                                <div class="play-button">
                                    <i class="fas fa-play"></i>
                                </div>
                                <div class="video-overlay"></div>
                            </div>
                        @else
                            <img src="{{ asset('storage/' . $interview->image) }}"
                                 alt="{{ $interview->titre }}"
                                 loading="lazy"
                                 onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'w-full h-full flex items-center justify-center\'><i class=\'fas fa-microphone text-6xl text-white opacity-50\'></i></div>';">
                        @endif
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <i class="fas fa-microphone text-6xl text-white opacity-50"></i>
                        </div>
                    @endif

                    <span class="category-badge">
                        <i class="fas fa-tag mr-1"></i>{{ ucfirst($interview->categorie) }}
                    </span>

                    <div class="interview-overlay">
                        <div class="interviewe-badge">
                            <div class="avatar">
                                {{ strtoupper(mb_substr($interview->nom_interviewe, 0, 1)) }}
                            </div>
                            <div>
                                <div class="font-bold text-gray-800">{{ $interview->nom_interviewe }}</div>
                                <div class="text-sm text-gray-600">
                                    {{ $interview->poste_interviewe }}
                                    @if($interview->club_equipe)
                                        - {{ $interview->club_equipe }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-6 flex-1 flex flex-col">
                    <h3 class="text-xl font-bold text-gray-800 mb-3 hover:text-green-600 transition">
                        {{ $interview->titre }}
                    </h3>

                    @if($interview->sous_titre)
                    <p class="text-gray-600 mb-4 font-medium">{{ $interview->sous_titre }}</p>
                    @endif

                    @if($interview->introduction)
                    <p class="text-gray-600 mb-4 line-clamp-3">
                        {{ Str::limit(strip_tags($interview->introduction), 120) }}
                    </p>
                    @endif

                    <div class="mt-auto">
                        <div class="flex items-center justify-between mb-4">
                            <div class="stats-badge">
                                <i class="far fa-calendar"></i>
                                <span>{{ \Carbon\Carbon::parse($interview->date_interview)->format('d/m/Y') }}</span>
                            </div>
                            <div class="stats-badge">
                                <i class="far fa-eye"></i>
                                <span>{{ number_format($interview->vues, 0, ',', ' ') }}</span>
                            </div>
                        </div>

                        @if($interview->auteur)
                        <div class="mb-4 text-sm text-gray-500">
                            <i class="fas fa-user-edit mr-1"></i>
                            Par {{ $interview->auteur }}
                        </div>
                        @endif

                        <a href="{{ route('interviews', $interview->id) }}"
                           class="block w-full text-center bg-gradient-to-r from-green-600 to-green-500 text-white py-3 rounded-lg font-semibold hover:from-green-700 hover:to-green-600 transition">
                            <i class="fas fa-play-circle mr-2"></i>Lire l'interview
                        </a>
                    </div>
                </div>
            </article>
            @empty
            <!-- Empty State -->
            <div class="col-span-full text-center py-12 bg-white rounded-lg shadow">
                <i class="fas fa-microphone-alt-slash text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-2xl font-bold text-gray-600 mb-2">Aucune interview disponible</h3>
                <p class="text-gray-500">Les interviews seront bientôt publiées. Revenez plus tard !</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination (optionnel) -->
        @if($interviews instanceof \Illuminate\Pagination\LengthAwarePaginator && $interviews->hasPages())
        <div class="mt-12">
            {{ $interviews->links() }}
        </div>
        @endif
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
                    <p class="text-gray-400 mb-4">Abonnez-vous pour recevoir les dernières actualités.</p>
                    <div class="flex">
                        <input type="email" placeholder="Votre email" class="px-4 py-2 rounded-l text-gray-900 w-full focus:outline-none">
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
                    <a href="#" class="text-gray-400 hover:text-white text-sm transition">Contact</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Gestion des filtres
            const filterBtns = document.querySelectorAll('.filter-btn');
            const interviewCards = document.querySelectorAll('.interview-card');
            const countElement = document.getElementById('interviews-count');

            filterBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const filter = this.getAttribute('data-filter');

                    // Active button
                    filterBtns.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');

                    // Filter cards et compter les visibles
                    let visibleCount = 0;
                    interviewCards.forEach(card => {
                        const category = card.getAttribute('data-category');
                        if (filter === 'tous' || category === filter) {
                            card.style.display = 'flex';
                            visibleCount++;
                        } else {
                            card.style.display = 'none';
                        }
                    });

                    // Mettre à jour le compteur
                    if (countElement) {
                        countElement.textContent = visibleCount;
                    }
                });
            });

            // Gestion de la lecture vidéo
            const videoContainers = document.querySelectorAll('.video-container');

            videoContainers.forEach(container => {
                const video = container.querySelector('video');
                const playButton = container.querySelector('.play-button');
                const overlay = container.querySelector('.video-overlay');

                if (!video || !playButton) return;

                // Lecture au clic sur le bouton play
                playButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    video.play().then(() => {
                        playButton.classList.add('hidden');
                        if (overlay) overlay.classList.add('hidden');
                        video.setAttribute('controls', 'controls');
                    }).catch(err => {
                        console.error('Erreur de lecture vidéo:', err);
                    });
                });

                // Réafficher le bouton play si vidéo en pause
                video.addEventListener('pause', function() {
                    if (video.currentTime === 0 || video.ended) {
                        playButton.classList.remove('hidden');
                        if (overlay) overlay.classList.remove('hidden');
                    }
                });

                // Gérer la fin de la vidéo
                video.addEventListener('ended', function() {
                    playButton.classList.remove('hidden');
                    if (overlay) overlay.classList.remove('hidden');
                    video.removeAttribute('controls');
                    video.currentTime = 0;
                });

                // Gérer les erreurs de chargement
                video.addEventListener('error', function() {
                    console.error('Erreur de chargement de la vidéo');
                    container.innerHTML = '<div class="w-full h-full flex items-center justify-center bg-gray-200"><i class="fas fa-exclamation-triangle text-4xl text-gray-400"></i></div>';
                });
            });
        });
    </script>
</body>
</html>
