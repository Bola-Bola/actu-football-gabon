<?php
namespace App\Http\Controllers;

use App\Models\Actualite;
use App\Models\ResultatMatch;
use App\Models\Equipe;
use App\Models\Classement;
use App\Models\Saison;
use App\Models\Competition;
use App\Models\Interview;
use App\Services\ClassementService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    protected $classementService;

    public function __construct(ClassementService $classementService)
    {
        $this->classementService = $classementService;
    }

    /**
     * Page d'accueil
     */
    public function home()
    {
        $actualites = Actualite::with('admin')
            ->where('publie', true)
            ->orderBy('date_publication', 'desc')
            ->limit(10)
            ->get();

        return view('home.home', compact('actualites'));
    }

    /**
     * Page équipes
     */
    public function equipe()
    {
        $equipes = Equipe::orderBy('nom', 'asc')->get();
        return view('home.equipe', compact('equipes'));
    }

    /**
     * Page interviews
     */
    public function interviews()
    {
        $interviews = Interview::where('statut', 'publie')
            ->orderBy('date_interview', 'desc')
            ->paginate(12);

        return view('home.interviews', compact('interviews'));
    }

    /**
     * Afficher une interview spécifique
     */
    public function showInterview($id)
    {
        $interview = Interview::where('statut', 'publie')
            ->findOrFail($id);

        // Incrémenter les vues
        $interview->increment('vues');

        // Interviews similaires (même catégorie ou récentes)
        $interviewsSimilaires = Interview::where('statut', 'publie')
            ->where('id', '!=', $id)
            ->where(function($query) use ($interview) {
                $query->where('categorie', $interview->categorie)
                      ->orWhere('date_interview', '>=', now()->subMonths(3));
            })
            ->orderBy('date_interview', 'desc')
            ->limit(3)
            ->get();

        return view('home.interview-detail', compact('interview', 'interviewsSimilaires'));
    }

    /**
     * Page matchs avec classement
     */
    public function match()
    {
        try {
            $now = Carbon::now();

            // 1️⃣ SAISON ACTIVE
            $saisonActive = $this->getSaisonActive();
            Log::info("✅ Saison active: " . ($saisonActive ? $saisonActive->annee : 'Aucune'));

            // 2️⃣ COMPÉTITION PRINCIPALE
            $competitionPrincipale = $this->getCompetitionPrincipale();
            Log::info("✅ Compétition: " . ($competitionPrincipale ? $competitionPrincipale->nom : 'Aucune'));

            // 3️⃣ MATCHS EN DIRECT
            $matchsEnDirect = ResultatMatch::with(['equipeDomicile', 'equipeExterieur', 'competition'])
                ->where(function($query) use ($now) {
                    $query->where('statut', 'en_cours')
                          ->orWhere(function($q) use ($now) {
                              $q->whereDate('date_match', $now->toDateString())
                                ->where('statut', '!=', 'termine');
                          });
                })
                ->orderBy('date_match', 'desc')
                ->get();

            // 4️⃣ DERNIERS RÉSULTATS
            $matchsRecents = ResultatMatch::with(['equipeDomicile', 'equipeExterieur', 'competition'])
                ->where('statut', 'termine')
                ->where('date_match', '<', $now)
                ->orderBy('date_match', 'desc')
                ->limit(10)
                ->get();

            // 5️⃣ PROCHAINS MATCHS
            $prochainsMatchs = ResultatMatch::with(['equipeDomicile', 'equipeExterieur', 'competition'])
                ->where('date_match', '>', $now)
                ->whereIn('statut', ['en_cours', 'reporte'])
                ->orderBy('date_match', 'asc')
                ->limit(10)
                ->get();

            // 6️⃣ CLASSEMENT - CRUCIAL !
            $classement = collect(); // Collection vide par défaut

            if ($saisonActive && $competitionPrincipale) {
                // Récupérer le classement
                $classement = Classement::where('saison_id', $saisonActive->id)
                    ->where('competition_id', $competitionPrincipale->id)
                    ->with('equipe')
                    ->orderBy('points', 'desc')
                    ->orderBy('difference_buts', 'desc')
                    ->orderBy('buts_pour', 'desc')
                    ->get();

                Log::info("📊 Classement trouvé: {$classement->count()} équipes");

                // Si le classement est vide, tenter un recalcul automatique
                if ($classement->isEmpty()) {
                    Log::warning("⚠️ Classement vide, tentative de recalcul automatique...");

                    try {
                        $this->classementService->recalculerClassementComplet(
                            $competitionPrincipale->id,
                            $saisonActive->id
                        );

                        // Récupérer à nouveau le classement
                        $classement = Classement::where('saison_id', $saisonActive->id)
                            ->where('competition_id', $competitionPrincipale->id)
                            ->with('equipe')
                            ->orderBy('points', 'desc')
                            ->orderBy('difference_buts', 'desc')
                            ->orderBy('buts_pour', 'desc')
                            ->get();

                        Log::info("✅ Recalcul terminé: {$classement->count()} équipes");
                    } catch (\Exception $e) {
                        Log::error("❌ Erreur recalcul: " . $e->getMessage());
                    }
                }
            } else {
                Log::warning("⚠️ Impossible de charger le classement: saison ou compétition manquante");
            }

            return view('home.match', compact(
                'matchsEnDirect',
                'matchsRecents',
                'prochainsMatchs',
                'classement',
                'saisonActive'
            ));

        } catch (\Exception $e) {
            Log::error("❌ Erreur dans HomeController::match: " . $e->getMessage());
            Log::error("Stack: " . $e->getTraceAsString());

            // Retourner une vue avec des collections vides
            return view('home.match', [
                'matchsEnDirect' => collect(),
                'matchsRecents' => collect(),
                'prochainsMatchs' => collect(),
                'classement' => collect(),
                'saisonActive' => null
            ])->with('error', 'Erreur lors du chargement des données');
        }
    }

    /**
     * Récupérer ou créer la saison active
     */
    private function getSaisonActive()
    {
        // Chercher une saison active
        $saison = Saison::where('active', true)->first();

        // Si pas de saison active, prendre la plus récente
        if (!$saison) {
            $saison = Saison::orderBy('annee', 'desc')->first();
        }

        // Si toujours pas de saison, en créer une
        if (!$saison) {
            $anneeActuelle = date('Y');
            $saison = Saison::create([
                'nom' => "Saison {$anneeActuelle}-" . ($anneeActuelle + 1),
                'annee' => $anneeActuelle,
                'date_debut' => Carbon::create($anneeActuelle, 9, 1),
                'date_fin' => Carbon::create($anneeActuelle + 1, 6, 30),
                'active' => true
            ]);

            Log::info("✅ Saison créée: {$saison->nom}");
        }

        return $saison;
    }

    /**
     * Récupérer ou créer la compétition principale
     */
    private function getCompetitionPrincipale()
    {
        // Chercher une compétition championnat
        $competition = Competition::where('nom', 'like', '%Championnat%')
            ->orWhere('nom', 'like', '%D1%')
            ->orWhere('type', 'championnat')
            ->first();

        // Si pas trouvée, prendre la première
        if (!$competition) {
            $competition = Competition::first();
        }

        // Si toujours rien, en créer une
        if (!$competition) {
            $competition = Competition::create([
                'nom' => 'Championnat National D1',
                'type' => 'championnat',
                'pays' => 'Gabon',
                'description' => 'Championnat national de première division du Gabon'
            ]);

            Log::info("✅ Compétition créée: {$competition->nom}");
        }

        return $competition;
    }

    /**
     * AJAX pour actualiser les matchs en direct
     */
    public function getLiveMatches()
    {
        $now = Carbon::now();

        $matchsEnDirect = ResultatMatch::with(['equipeDomicile', 'equipeExterieur', 'competition'])
            ->where(function($query) use ($now) {
                $query->where('statut', 'en_cours')
                      ->orWhere(function($q) use ($now) {
                          $q->whereDate('date_match', $now->toDateString())
                            ->where('statut', '!=', 'termine');
                      });
            })
            ->orderBy('date_match', 'desc')
            ->limit(10)
            ->get()
            ->map(function($match) {
                return [
                    'id' => $match->id,
                    'equipe_domicile' => $match->equipeDomicile->nom ?? 'N/A',
                    'equipe_exterieur' => $match->equipeExterieur->nom ?? 'N/A',
                    'logo_domicile' => $match->equipeDomicile?->logo ? asset('storage/' . $match->equipeDomicile->logo) : null,
                    'logo_exterieur' => $match->equipeExterieur?->logo ? asset('storage/' . $match->equipeExterieur->logo) : null,
                    'score_domicile' => $match->buts_domicile ?? 0,
                    'score_exterieur' => $match->buts_exterieur ?? 0,
                    'date_match' => $match->date_match ? $match->date_match->format('d/m/Y - H:i') : null,
                    'competition' => $match->competition->nom ?? 'N/A',
                    'lieu' => $match->lieu ?? 'Non précisé',
                    'statut' => $match->statut,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $matchsEnDirect
        ]);
    }
}
