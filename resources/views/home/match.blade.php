<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Matchs - Actu Foot Gabon</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .match-card {
            background: white;
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .match-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
        }

        .live-badge {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        .team-logo {
            width: 60px;
            height: 60px;
            object-fit: contain;
        }

        .standing-row {
            transition: all 0.3s ease;
        }

        .standing-row:hover {
            background-color: #f9fafb;
            transform: translateX(5px);
        }

        .highlight-team {
            background: linear-gradient(90deg, rgba(79,70,229,0.1) 0%, transparent 100%);
            border-left: 4px solid #4f46e5;
        }

        .stat-card {
            background: white;
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.15);
        }

        .stat-card.leader {
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            color: white;
        }

        .score-display {
            font-size: 2.5rem;
            font-weight: 800;
            color: #1f2937;
        }

        .vs-text {
            font-size: 1.5rem;
            font-weight: 700;
            color: #6b7280;
        }

        .section-container {
            background: white;
            border-radius: 1.5rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            overflow: hidden;
            margin-bottom: 3rem;
        }

        .match-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(450px, 1fr));
            gap: 2rem;
        }

        @media (max-width: 768px) {
            .match-grid {
                grid-template-columns: 1fr;
            }
            .team-logo {
                width: 40px;
                height: 40px;
            }
            .score-display {
                font-size: 2rem;
            }
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
    <section class="relative text-white py-16" style="background-image: url('https://images.unsplash.com/photo-1574629810360-7efbbe195018?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1920&q=80'); background-size: cover; background-position: center;">
        <div class="absolute inset-0 bg-black bg-opacity-60"></div>
        <div class="container mx-auto px-4 relative z-10">
            <div class="flex flex-col items-center text-center">
                <div class="max-w-3xl">
                    <h2 class="text-3xl md:text-4xl font-bold mb-4">Matchs & Classements</h2>
                    <p class="text-lg mb-6">Suivez les résultats en direct et le classement du championnat.</p>
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

    <main class="container mx-auto px-4 py-12">

        @php
            // Calculer les statistiques
            $totalMatchsJoues = $matchsRecents->count();
            $totalMatchsEnDirect = $matchsEnDirect->count();
            $totalMatchsAVenir = $prochainsMatchs->count();

            // Calculer le leader depuis le classement
            $leader = 'N/A';
            if ($classement && $classement->count() > 0) {
                $leader = $classement->first()->equipe->nom ?? 'N/A';
            }

            // Nombre total d'équipes (estimation basée sur le classement)
            $totalEquipes = $classement ? $classement->count() : 16;
        @endphp

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
            <div class="stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-gray-500 text-sm font-semibold mb-2 uppercase">Équipes</h3>
                        <p class="text-4xl font-bold text-blue-600">{{ $totalEquipes }}</p>
                    </div>
                    <i class="fas fa-users text-5xl text-blue-200"></i>
                </div>
            </div>

            <div class="stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-gray-500 text-sm font-semibold mb-2 uppercase">Matchs joués</h3>
                        <p class="text-4xl font-bold text-green-600">{{ $totalMatchsJoues }}</p>
                    </div>
                    <i class="fas fa-futbol text-5xl text-green-200"></i>
                </div>
            </div>

            <div class="stat-card leader">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-indigo-100 text-sm font-semibold mb-2 uppercase">Leader</h3>
                        <p class="text-3xl font-bold">{{ $leader }}</p>
                    </div>
                    <i class="fas fa-trophy text-5xl text-indigo-300"></i>
                </div>
            </div>

            <div class="stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-gray-500 text-sm font-semibold mb-2 uppercase">À venir</h3>
                        <p class="text-4xl font-bold text-purple-600">{{ $totalMatchsAVenir }}</p>
                    </div>
                    <i class="fas fa-calendar-check text-5xl text-purple-200"></i>
                </div>
            </div>
        </div>

        @php
            $matchsEnDirect_display = $matchsEnDirect;
            $derniersResultats = $matchsRecents->take(4);
            $prochainsMatchs_display = $prochainsMatchs->take(4);
        @endphp

        <!-- Matchs en direct -->
        @if($matchsEnDirect_display->count() > 0)
        <div class="section-container mb-12">
            <div class="bg-gradient-to-r from-red-500 to-red-600 px-8 py-6">
                <h2 class="text-3xl font-bold text-white flex items-center">
                    <i class="fas fa-circle live-badge mr-3"></i>Matchs en direct
                </h2>
                <p class="text-red-100 mt-2">Suivez les scores en temps réel</p>
            </div>
            <div class="p-8">
                <div class="match-grid">
                    @foreach($matchsEnDirect_display as $match)
                    <div class="match-card p-8">
                        <div class="flex justify-between items-center mb-6">
                            <span class="text-sm font-semibold text-gray-600">EN DIRECT</span>
                            <span class="bg-red-100 text-red-700 px-4 py-2 rounded-full text-sm font-bold live-badge flex items-center">
                                <i class="fas fa-circle mr-2 text-xs"></i>EN DIRECT
                            </span>
                        </div>

                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center flex-1">
                                @if($match->equipeDomicile && $match->equipeDomicile->logo)
                                    <img src="{{ asset('storage/' . $match->equipeDomicile->logo) }}" alt="{{ $match->equipeDomicile->nom }}" class="w-16 h-16 object-contain rounded-full mr-4">
                                @else
                                    <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mr-4">
                                        <i class="fas fa-shield-alt text-gray-400 text-2xl"></i>
                                    </div>
                                @endif
                                <div>
                                    <span class="font-bold text-xl block">{{ $match->equipeDomicile->nom ?? 'N/A' }}</span>
                                    <span class="text-gray-500 text-sm">{{ $match->lieu ?? 'Domicile' }}</span>
                                </div>
                            </div>

                            <div class="flex items-center gap-6 mx-6">
                                <span class="score-display {{ $match->buts_domicile > $match->buts_exterieur ? 'text-green-600' : '' }}">{{ $match->buts_domicile ?? 0 }}</span>
                                <span class="text-gray-400 text-3xl font-bold">-</span>
                                <span class="score-display {{ $match->buts_exterieur > $match->buts_domicile ? 'text-green-600' : '' }}">{{ $match->buts_exterieur ?? 0 }}</span>
                            </div>

                            <div class="flex items-center justify-end flex-1">
                                <div class="text-right">
                                    <span class="font-bold text-xl block">{{ $match->equipeExterieur->nom ?? 'N/A' }}</span>
                                    <span class="text-gray-500 text-sm">Extérieur</span>
                                </div>
                                @if($match->equipeExterieur && $match->equipeExterieur->logo)
                                    <img src="{{ asset('storage/' . $match->equipeExterieur->logo) }}" alt="{{ $match->equipeExterieur->nom }}" class="w-16 h-16 object-contain rounded-full ml-4">
                                @else
                                    <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center ml-4">
                                        <i class="fas fa-shield-alt text-gray-400 text-2xl"></i>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="pt-6 border-t flex items-center justify-center gap-8 text-sm text-gray-600">
                            <span><i class="fas fa-calendar-alt mr-2"></i>{{ $match->date_match ? $match->date_match->format('d/m/Y') : 'N/A' }}</span>
                            <span><i class="fas fa-clock mr-2"></i>{{ $match->date_match ? $match->date_match->format('H:i') : 'N/A' }}</span>
                            <span class="bg-blue-100 text-blue-700 px-4 py-2 rounded-full text-xs font-semibold">
                                {{ $match->competition->nom ?? 'Sans compétition' }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Derniers résultats -->
        @if($derniersResultats->count() > 0)
        <div class="section-container mb-12">
            <div class="bg-gradient-to-r from-green-500 to-green-600 px-8 py-6">
                <h2 class="text-3xl font-bold text-white flex items-center">
                    <i class="fas fa-check-circle mr-3"></i>Derniers résultats
                </h2>
                <p class="text-green-100 mt-2">Les matchs terminés récemment</p>
            </div>
            <div class="p-8">
                <div class="match-grid">
                    @foreach($derniersResultats as $match)
                    <div class="match-card p-6">
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-xs font-semibold text-gray-500">{{ $match->date_match ? $match->date_match->format('d/m/Y - H:i') : 'N/A' }}</span>
                            <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-xs font-semibold">
                                Terminé
                            </span>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center flex-1">
                                @if($match->equipeDomicile && $match->equipeDomicile->logo)
                                    <img src="{{ asset('storage/' . $match->equipeDomicile->logo) }}" alt="{{ $match->equipeDomicile->nom }}" class="w-12 h-12 object-contain rounded-full mr-3">
                                @else
                                    <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-shield-alt text-gray-400"></i>
                                    </div>
                                @endif
                                <span class="font-semibold">{{ $match->equipeDomicile->nom ?? 'N/A' }}</span>
                            </div>

                            <div class="flex items-center gap-4 mx-4">
                                <span class="text-2xl font-bold {{ $match->buts_domicile > $match->buts_exterieur ? 'text-green-600' : 'text-gray-600' }}">{{ $match->buts_domicile ?? 0 }}</span>
                                <span class="text-gray-400 text-xl">-</span>
                                <span class="text-2xl font-bold {{ $match->buts_exterieur > $match->buts_domicile ? 'text-green-600' : 'text-gray-600' }}">{{ $match->buts_exterieur ?? 0 }}</span>
                            </div>

                            <div class="flex items-center justify-end flex-1">
                                <span class="font-semibold">{{ $match->equipeExterieur->nom ?? 'N/A' }}</span>
                                @if($match->equipeExterieur && $match->equipeExterieur->logo)
                                    <img src="{{ asset('storage/' . $match->equipeExterieur->logo) }}" alt="{{ $match->equipeExterieur->nom }}" class="w-12 h-12 object-contain rounded-full ml-3">
                                @else
                                    <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center ml-3">
                                        <i class="fas fa-shield-alt text-gray-400"></i>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Prochains matchs -->
        @if($prochainsMatchs_display->count() > 0)
        <div class="section-container mb-12">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-8 py-6">
                <h2 class="text-3xl font-bold text-white flex items-center">
                    <i class="fas fa-calendar-alt mr-3"></i>Prochains matchs
                </h2>
                <p class="text-blue-100 mt-2">Les rencontres à venir</p>
            </div>
            <div class="p-8">
                <div class="match-grid">
                    @foreach($prochainsMatchs_display as $match)
                    <div class="match-card p-6">
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-xs font-semibold text-gray-500">{{ $match->date_match ? $match->date_match->format('d/m/Y - H:i') : 'N/A' }}</span>
                            <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-semibold">
                                À venir
                            </span>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center flex-1">
                                @if($match->equipeDomicile && $match->equipeDomicile->logo)
                                    <img src="{{ asset('storage/' . $match->equipeDomicile->logo) }}" alt="{{ $match->equipeDomicile->nom }}" class="w-12 h-12 object-contain rounded-full mr-3">
                                @else
                                    <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-shield-alt text-gray-400"></i>
                                    </div>
                                @endif
                                <span class="font-semibold">{{ $match->equipeDomicile->nom ?? 'N/A' }}</span>
                            </div>

                            <div class="vs-text mx-4">VS</div>

                            <div class="flex items-center justify-end flex-1">
                                <span class="font-semibold">{{ $match->equipeExterieur->nom ?? 'N/A' }}</span>
                                @if($match->equipeExterieur && $match->equipeExterieur->logo)
                                    <img src="{{ asset('storage/' . $match->equipeExterieur->logo) }}" alt="{{ $match->equipeExterieur->nom }}" class="w-12 h-12 object-contain rounded-full ml-3">
                                @else
                                    <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center ml-3">
                                        <i class="fas fa-shield-alt text-gray-400"></i>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

<div class="section-container">
    <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 px-8 py-6">
        <h2 class="text-3xl font-bold text-white flex items-center">
            <i class="fas fa-trophy mr-3"></i>Classement Championnat
        </h2>
        <p class="text-indigo-100 mt-2">Saison {{ $saisonActive ? $saisonActive->annee : date('Y') . '-' . (date('Y') + 1) }}</p>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Pos</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Équipe</th>
                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">MJ</th>
                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">G</th>
                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">N</th>
                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">P</th>
                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">BP</th>
                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">BC</th>
                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">DB</th>
                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">PTS</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @if($classement && $classement->count() > 0)
                    @foreach($classement as $index => $position)
                    <tr class="standing-row {{ $index === 0 ? 'highlight-team' : '' }}">
                        <!-- Position -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-full {{ $index === 0 ? 'bg-indigo-600 text-white' : ($index < 3 ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600') }} font-bold">
                                {{ $position->position ?? ($index + 1) }}
                            </span>
                        </td>

                        <!-- Équipe -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                @if($position->equipe && $position->equipe->logo)
                                    <img src="{{ asset('storage/' . $position->equipe->logo) }}" alt="{{ $position->equipe->nom }}" class="w-10 h-10 object-contain rounded-full mr-3">
                                @else
                                    <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-shield-alt text-gray-400"></i>
                                    </div>
                                @endif
                                <span class="text-sm font-bold text-gray-900">{{ $position->equipe->nom ?? 'Équipe inconnue' }}</span>
                            </div>
                        </td>

                        <!-- Matchs Joués -->
                        <td class="px-6 py-4 text-center font-semibold text-gray-700">
                            {{ $position->matches_joues ?? 0 }}
                        </td>

                        <!-- Victoires (G = Gagné) -->
                        <td class="px-6 py-4 text-center font-semibold text-green-600">
                            {{ $position->victoires ?? 0 }}
                        </td>

                        <!-- Nuls -->
                        <td class="px-6 py-4 text-center font-semibold text-gray-600">
                            {{ $position->nuls ?? 0 }}
                        </td>

                        <!-- Défaites (P = Perdu) -->
                        <td class="px-6 py-4 text-center font-semibold text-red-600">
                            {{ $position->defaites ?? 0 }}
                        </td>

                        <!-- Buts Pour -->
                        <td class="px-6 py-4 text-center font-semibold text-blue-600">
                            {{ $position->buts_pour ?? 0 }}
                        </td>

                        <!-- Buts Contre -->
                        <td class="px-6 py-4 text-center font-semibold text-orange-600">
                            {{ $position->buts_contre ?? 0 }}
                        </td>

                        <!-- Différence de Buts -->
                        <td class="px-6 py-4 text-center font-bold {{ ($position->difference_buts ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ ($position->difference_buts ?? 0) > 0 ? '+' : '' }}{{ $position->difference_buts ?? 0 }}
                        </td>

                        <!-- Points -->
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center justify-center px-4 py-2 rounded-full text-sm font-bold {{ $index === 0 ? 'bg-indigo-600 text-white' : 'bg-indigo-100 text-indigo-700' }}">
                                {{ $position->points ?? 0 }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                @else
                    <!-- Message si aucun classement -->
                    <tr>
                        <td colspan="10" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-400">
                                <i class="fas fa-table text-6xl mb-4"></i>
                                <p class="text-lg font-semibold">Aucun classement disponible</p>
                                <p class="text-sm mt-2">Le classement sera mis à jour automatiquement après les matchs officiels.</p>
                                @if(!$saisonActive)
                                    <p class="text-sm mt-2 text-red-500">⚠️ Aucune saison active n'est définie.</p>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <!-- Légende -->
    @if($classement && $classement->count() > 0)
    <div class="px-8 py-4 bg-gray-50 border-t">
        <div class="flex flex-wrap gap-4 text-xs text-gray-600">
            <div class="flex items-center">
                <span class="w-6 h-6 rounded-full bg-indigo-600 mr-2"></span>
                <span>1ère place</span>
            </div>
            <div class="flex items-center">
                <span class="w-6 h-6 rounded-full bg-green-100 mr-2"></span>
                <span>Qualifié pour la Coupe d'Afrique</span>
            </div>
            <div class="flex items-center ml-auto">
                <strong class="mr-2">Dernière mise à jour :</strong>
                <span>{{ $classement->first()->last_updated ? $classement->first()->last_updated->diffForHumans() : 'N/A' }}</span>
            </div>
        </div>
    </div>
    @endif
</div>

    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white pt-12 pb-6 mt-12">
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
                        @if($classement && $classement->count() > 0)
                            @foreach($classement->take(5) as $item)
                                <li><a href="#" class="text-gray-400 hover:text-white transition">{{ $item->equipe->nom }}</a></li>
                            @endforeach
                        @else
                            <li><a href="#" class="text-gray-400 hover:text-white transition">FC 105 Libreville</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white transition">Mangasport</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white transition">AS Pélican</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white transition">Bouenguidi Sport</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white transition">US Bitam</a></li>
                        @endif
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
                <p class="text-gray-400 text-sm mb-4 md:mb-0">© {{ date('Y') }} Actu Foot Gabon. Tous droits réservés.</p>
                <div class="flex space-x-6">
                    <a href="#" class="text-gray-400 hover:text-white text-sm transition">Mentions légales</a>
                    <a href="#" class="text-gray-400 hover:text-white text-sm transition">Politique de confidentialité</a>
                    <a href="#" class="text-gray-400 hover:text-white text-sm transition">Conditions d'utilisation</a>
                    <a href="#" class="text-gray-400 hover:text-white text-sm transition">Contact</a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
