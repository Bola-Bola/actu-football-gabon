<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>À Propos - Actu Foot Gabon</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #1e40af;
            --secondary-color: #16a34a;
            --dark-color: #1f2937;
            --light-color: #f9fafb;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light-color);
            overflow-x: hidden;
        }

        /* Stats Section */
        .stats-section {
            background: white;
            padding: 4rem 0;
            margin-top: -3rem;
            position: relative;
            z-index: 3;
            border-radius: 2rem 2rem 0 0;
        }

        .stat-card {
            text-align: center;
            padding: 2rem;
            border-radius: 1rem;
            background: linear-gradient(135deg, var(--secondary-color), #22c55e);
            color: white;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(22, 163, 74, 0.2);
            height: 100%;
        }

        .stat-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 25px rgba(22, 163, 74, 0.3);
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            line-height: 1;
        }

        .stat-label {
            font-size: 1rem;
            font-weight: 500;
            opacity: 0.95;
            line-height: 1.4;
        }

        /* Mission Section */
        .mission-section {
            padding: 5rem 0;
            background: linear-gradient(to bottom, white, var(--light-color));
        }

        .mission-card {
            background: white;
            border-radius: 1.5rem;
            padding: 3rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            border-left: 5px solid var(--secondary-color);
        }

        .mission-icon {
            font-size: 3rem;
            color: var(--secondary-color);
            margin-bottom: 1.5rem;
        }

        /* Values Section */
        .values-section {
            padding: 5rem 0;
            background: white;
        }

        .value-card {
            background: linear-gradient(135deg, #f9fafb, white);
            border-radius: 1.5rem;
            padding: 2.5rem;
            text-align: center;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .value-card:hover {
            border-color: var(--secondary-color);
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(22, 163, 74, 0.15);
        }

        .value-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--secondary-color), #22c55e);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: white;
            font-size: 2rem;
            flex-shrink: 0;
        }

        /* Team Section */
        .team-section {
            padding: 5rem 0;
            background: var(--light-color);
        }

        .team-card {
            background: white;
            border-radius: 1.5rem;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .team-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }

        .team-photo {
            width: 100%;
            height: 300px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .team-content {
            padding: 2rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .team-name {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
        }

        .team-position {
            color: var(--secondary-color);
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .team-bio {
            color: #6b7280;
            line-height: 1.6;
            flex: 1;
        }

        /* Timeline Section */
        .timeline-section {
            padding: 5rem 0;
            background: white;
        }

        .timeline-item {
            padding: 2rem;
            background: var(--light-color);
            border-radius: 1rem;
            border-left: 4px solid var(--secondary-color);
            margin-bottom: 2rem;
            transition: all 0.3s ease;
        }

        .timeline-item:hover {
            transform: translateX(10px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .timeline-item:last-child {
            margin-bottom: 0;
        }

        .timeline-year {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--secondary-color);
            margin-bottom: 0.5rem;
        }

        /* CTA Section */
        .cta-section {
            background: linear-gradient(135deg, var(--primary-color), #3b82f6);
            color: white;
            padding: 5rem 0;
            text-align: center;
        }

        .cta-button {
            background: var(--secondary-color);
            color: white;
            padding: 1rem 3rem;
            border-radius: 3rem;
            font-weight: 600;
            border: none;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            box-shadow: 0 5px 20px rgba(22, 163, 74, 0.3);
            text-decoration: none;
            display: inline-block;
            cursor: pointer;
        }

        .cta-button:hover {
            background: #15803d;
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(22, 163, 74, 0.4);
            color: white;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--dark-color);
            margin-bottom: 1rem;
            position: relative;
            display: inline-block;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 60px;
            height: 4px;
            background: var(--secondary-color);
            border-radius: 2px;
        }

        .section-subtitle {
            color: #6b7280;
            font-size: 1.1rem;
            margin-bottom: 3rem;
        }

        /* Social Links */
        .social-links a {
            color: white;
            font-size: 1.5rem;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .social-links a:hover {
            color: var(--secondary-color);
            transform: scale(1.1);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .stat-number {
                font-size: 2rem;
            }

            .stat-label {
                font-size: 0.875rem;
            }

            .section-title {
                font-size: 2rem;
            }

            .section-subtitle {
                font-size: 1rem;
            }

            .mission-card,
            .value-card {
                padding: 2rem;
            }

            .mission-icon {
                font-size: 2.5rem;
            }

            .value-icon {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }

            .team-photo {
                height: 250px;
            }

            .team-content {
                padding: 1.5rem;
            }

            .team-name {
                font-size: 1.25rem;
            }

            .timeline-item {
                padding: 1.5rem;
            }

            .timeline-year {
                font-size: 1.25rem;
            }

            .cta-button {
                padding: 0.75rem 2rem;
                font-size: 1rem;
                width: 100%;
                max-width: 300px;
            }

            .stats-section,
            .mission-section,
            .values-section,
            .team-section,
            .timeline-section,
            .cta-section {
                padding: 3rem 0;
            }
        }

        @media (max-width: 576px) {
            .section-title {
                font-size: 1.75rem;
            }

            .stat-number {
                font-size: 1.75rem;
            }

            .mission-icon {
                font-size: 2rem;
            }

            .timeline-item:hover {
                transform: translateX(5px);
            }
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
                    <a href="{{ route('home') }}" class="px-3 py-2 rounded hover:bg-green-700 font-medium transition">Accueil</a>
                    <a href="{{ route('match') }}" class="px-3 py-2 rounded hover:bg-green-700 font-medium transition">Matchs</a>
                    <a href="{{ route('interviews') }}" class="px-3 py-2 rounded bg-green-700 font-medium transition">Interviews</a>
                    <a href="{{ route('about') }}" class="px-3 py-2 rounded hover:bg-green-700 font-medium transition">A propos</a>
                    {{-- <a href="{{ route('equipe') }}" class="px-3 py-2 rounded hover:bg-green-700 font-medium transition">Equipes</a> --}}
                </nav>
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
                    <h2 class="text-3xl md:text-4xl font-bold mb-4">Suivez toute l'actualité footballistique</h2>
                    <p class="text-lg mb-6">Résultats en direct, classements, analyses et toutes les dernières infos sur vos clubs et joueurs préférés.</p>
                    <div class="flex space-x-4 justify-center flex-wrap gap-3">
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

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <div class="stat-number">500+</div>
                        <div class="stat-label">Articles Publiés</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <div class="stat-number">50+</div>
                        <div class="stat-label">Équipes Couvertes</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <div class="stat-number">200+</div>
                        <div class="stat-label">Matchs Commentés</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <div class="stat-number">10K+</div>
                        <div class="stat-label">Utilisateurs Actifs</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Mission Section -->
    <section class="mission-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0 text-center">
                    <i class="fas fa-bullseye mission-icon"></i>
                    <h2 class="section-title">Notre Mission</h2>
                </div>
                <div class="col-lg-6">
                    <div class="mission-card">
                        <p class="lead mb-4">
                            <strong>ActuFootGabon</strong> est un média numérique fondé en 2022, dédié à la couverture complète de l'actualité du football gabonais.
                        </p>
                        <p class="mb-3">
                            Notre mission principale est d'<strong>informer</strong>, de <strong>sensibiliser</strong> et de <strong>fédérer</strong> les amateurs de football autour de la passion pour ce sport. Nous nous consacrons à fournir une information de qualité sur les joueurs gabonais, les équipes locales et les compétitions internationales.
                        </p>
                        <p class="mb-0">
                            Grâce à une forte présence sur les réseaux sociaux, notamment Facebook et WhatsApp, ActuFootGabon met un point d'honneur à diffuser des informations en temps réel et à interagir avec ses abonnés pour créer une véritable communauté autour du football au Gabon.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Values Section -->
    <section class="values-section">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Nos Valeurs</h2>
                <p class="section-subtitle">Les piliers qui guident notre action quotidienne</p>
            </div>
            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="value-card">
                        <div class="value-icon">
                            <i class="fas fa-certificate"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Qualité</h4>
                        <p class="text-muted">Nous privilégions des contenus fiables et bien documentés pour offrir la meilleure information.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="value-card">
                        <div class="value-icon">
                            <i class="fas fa-bolt"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Réactivité</h4>
                        <p class="text-muted">Nous sommes à l'affût de toutes les actualités pour vous informer en temps réel.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="value-card">
                        <div class="value-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Communauté</h4>
                        <p class="text-muted">Nous créons un espace d'échange et de partage pour tous les passionnés de football.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="value-card">
                        <div class="value-icon">
                            <i class="fas fa-heart"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Passion</h4>
                        <p class="text-muted">Notre amour du football gabonais nous motive à donner le meilleur chaque jour.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="team-section">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Notre Équipe</h2>
                <p class="section-subtitle">Des passionnés au service du football gabonais</p>
            </div>
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="team-card">
                        <div class="team-photo">
                            <i class="fas fa-user fa-5x text-white opacity-50"></i>
                        </div>
                        <div class="team-content">
                            <h4 class="team-name">Jean Dupont</h4>
                            <p class="team-position">Rédacteur en Chef</p>
                            <p class="team-bio">Passionné de football depuis plus de 15 ans, Jean coordonne l'équipe éditoriale et veille à la qualité de nos contenus.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="team-card">
                        <div class="team-photo">
                            <i class="fas fa-user fa-5x text-white opacity-50"></i>
                        </div>
                        <div class="team-content">
                            <h4 class="team-name">Marie Martin</h4>
                            <p class="team-position">Journaliste Sportive</p>
                            <p class="team-bio">Spécialiste des compétitions locales, Marie couvre tous les matchs et interview les acteurs du football gabonais.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="team-card">
                        <div class="team-photo">
                            <i class="fas fa-user fa-5x text-white opacity-50"></i>
                        </div>
                        <div class="team-content">
                            <h4 class="team-name">Pierre Nkoghe</h4>
                            <p class="team-position">Community Manager</p>
                            <p class="team-bio">Pierre anime nos réseaux sociaux et maintient le lien avec notre communauté de fans.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Timeline Section -->
    <section class="timeline-section">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Notre Histoire</h2>
                <p class="section-subtitle">Les moments clés de notre développement</p>
            </div>
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="timeline-item">
                        <div class="timeline-year">2022</div>
                        <h5 class="fw-bold mb-2">Fondation d'ActuFootGabon</h5>
                        <p class="mb-0 text-muted">Lancement du média numérique avec une stratégie de communication à 360° sur les réseaux sociaux.</p>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-year">2023</div>
                        <h5 class="fw-bold mb-2">Expansion de la couverture</h5>
                        <p class="mb-0 text-muted">Extension de nos services pour accompagner les joueurs professionnels gabonais dans la gestion de leur communication.</p>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-year">2024</div>
                        <h5 class="fw-bold mb-2">Lancement du site web</h5>
                        <p class="mb-0 text-muted">Développement d'une plateforme web moderne pour centraliser toute l'actualité footballistique gabonaise.</p>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-year">2025</div>
                        <h5 class="fw-bold mb-2">Objectifs futurs</h5>
                        <p class="mb-0 text-muted">Renforcer nos partenariats avec les clubs et fédérations, et devenir la référence incontournable du football gabonais.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <h2 class="display-5 fw-bold mb-4">Rejoignez Notre Communauté</h2>
                    <p class="lead mb-5">
                        Ne manquez plus aucune actualité du football gabonais. Suivez-nous sur nos réseaux sociaux et abonnez-vous à notre newsletter !
                    </p>
                    <div class="d-flex flex-wrap gap-3 justify-content-center mb-5">
                        <a href="#" class="cta-button">
                            <i class="fas fa-home me-2"></i>Retour à l'Accueil
                        </a>
                        <button class="cta-button">
                            <i class="fas fa-bell me-2"></i>S'Abonner
                        </button>
                    </div>
                    <div class="social-links d-flex justify-content-center gap-4">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white pt-12 pb-6">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-8">
                <div>
                    <h3 class="text-lg font-bold mb-4">Actu Foot Gabon</h3>
                    <p class="text-gray-400 mb-4">Le site de référence pour suivre toute l'actualité footballistique au Gabon.</p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-youtube"></i></a>
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
</body>
</html>
