<?php

namespace App\Http\Controllers;

use App\Models\Interview;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class InterviewController extends Controller
{
   public function index()
{
    $admins = Admin::all(); // récupère tous les admins

    // Récupère uniquement les interviews publiées
    $interviews = Interview::where('statut', 'publie')
        ->latest()
        ->get();

    // Statistiques (sur TOUTES les interviews pour l'admin)
    $totalInterviews = Interview::count();
    $publishedInterviews = Interview::where('statut', 'publie')->count();
    $draftInterviews = Interview::where('statut', 'brouillon')->count();
    $archivedInterviews = Interview::where('statut', 'archive')->count();

    return view('interview', compact(
        'admins',
        'interviews',
        'totalInterviews',
        'publishedInterviews',
        'draftInterviews',
        'archivedInterviews'
    ));
}
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('interview');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'titre' => 'required|string|max:255',
                'sous_titre' => 'nullable|string|max:255',
                'nom_interviewe' => 'required|string|max:255',
                'poste_interviewe' => 'required|string|max:255',
                'club_equipe' => 'nullable|string|max:255',
                'categorie' => 'required|in:joueur,entraineur,dirigeant,arbitre,journaliste,autre',
                'date_interview' => 'required|date',
                'date_publication' => 'nullable|date',
                'auteur' => 'nullable|string|max:255',
                'introduction' => 'nullable|string|max:500',
                'contenu' => 'required|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
                'statut' => 'required|in:brouillon,publie,archive'
            ], [
                'titre.required' => 'Le titre est obligatoire',
                'nom_interviewe.required' => 'Le nom de l\'interviewé est obligatoire',
                'poste_interviewe.required' => 'Le poste est obligatoire',
                'categorie.required' => 'La catégorie est obligatoire',
                'categorie.in' => 'Catégorie invalide',
                'date_interview.required' => 'La date de l\'interview est obligatoire',
                'contenu.required' => 'Le contenu est obligatoire',
                'statut.in' => 'Statut invalide',
                'image.max' => 'L\'image ne doit pas dépasser 5Mo'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur de validation',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $request->except(['image']);
            $data['vues'] = 0;

            // Gestion de l'image
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('interviews', 'public');
                $data['image'] = $path;
            }

            // Si statut est publié et pas de date de publication, mettre la date actuelle
            if ($data['statut'] === 'publie' && empty($data['date_publication'])) {
                $data['date_publication'] = now();
            }

            $interview = Interview::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Interview créée avec succès',
                'data' => $interview
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de l\'interview',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $interview = Interview::findOrFail($id);

            // Incrémenter le nombre de vues
            $interview->increment('vues');

            $imageUrl = $interview->image ? asset('storage/' . $interview->image) : null;

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $interview->id,
                    'titre' => $interview->titre,
                    'sous_titre' => $interview->sous_titre,
                    'nom_interviewe' => $interview->nom_interviewe,
                    'poste_interviewe' => $interview->poste_interviewe,
                    'club_equipe' => $interview->club_equipe,
                    'categorie' => $interview->categorie,
                    'date_interview' => $interview->date_interview,
                    'date_publication' => $interview->date_publication,
                    'auteur' => $interview->auteur,
                    'introduction' => $interview->introduction,
                    'contenu' => $interview->contenu,
                    'image' => $imageUrl,
                    'statut' => $interview->statut,
                    'vues' => $interview->vues,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Interview non trouvée'
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $interview = Interview::findOrFail($id);
        return view('interview', compact('interview'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $interview = Interview::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'titre' => 'required|string|max:255',
                'sous_titre' => 'nullable|string|max:255',
                'nom_interviewe' => 'required|string|max:255',
                'poste_interviewe' => 'required|string|max:255',
                'club_equipe' => 'nullable|string|max:255',
                'categorie' => 'required|in:joueur,entraineur,dirigeant,arbitre,journaliste,autre',
                'date_interview' => 'required|date',
                'date_publication' => 'nullable|date',
                'auteur' => 'nullable|string|max:255',
                'introduction' => 'nullable|string|max:500',
                'contenu' => 'required|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
                'statut' => 'required|in:brouillon,publie,archive'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur de validation',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $request->except(['image', '_method']);

            // Gestion de la nouvelle image
            if ($request->hasFile('image')) {
                // Supprimer l'ancienne image
                if ($interview->image) {
                    Storage::disk('public')->delete($interview->image);
                }

                $path = $request->file('image')->store('interviews', 'public');
                $data['image'] = $path;
            }

            // Si statut change à publié et pas de date de publication
            if ($data['statut'] === 'publie' && !$interview->date_publication && empty($data['date_publication'])) {
                $data['date_publication'] = now();
            }

            $interview->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Interview mise à jour avec succès',
                'data' => $interview
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $interview = Interview::findOrFail($id);

            // Supprimer l'image associée
            if ($interview->image) {
                Storage::disk('public')->delete($interview->image);
            }

            $interview->delete();

            return response()->json([
                'success' => true,
                'message' => 'Interview supprimée avec succès'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression'
            ], 500);
        }
    }

    /**
     * Get all interviews with filters (API endpoint)
     */
    public function all(Request $request)
    {
        try {
            $query = Interview::query()->orderBy('date_interview', 'desc');

            // Filtres
            if ($request->filled('categorie')) {
                $query->where('categorie', $request->categorie);
            }

            if ($request->filled('statut')) {
                $query->where('statut', $request->statut);
            } else {
                // Par défaut, afficher uniquement les interviews publiées
                $query->where('statut', 'publie');
            }

            if ($request->filled('recherche')) {
                $search = $request->recherche;
                $query->where(function($q) use ($search) {
                    $q->where('titre', 'LIKE', "%{$search}%")
                      ->orWhere('nom_interviewe', 'LIKE', "%{$search}%")
                      ->orWhere('sous_titre', 'LIKE', "%{$search}%");
                });
            }

            $interviews = $query->get()->map(function($interview) {
                return [
                    'id' => $interview->id,
                    'titre' => $interview->titre,
                    'sous_titre' => $interview->sous_titre,
                    'nom_interviewe' => $interview->nom_interviewe,
                    'poste_interviewe' => $interview->poste_interviewe,
                    'club_equipe' => $interview->club_equipe,
                    'categorie' => $interview->categorie,
                    'date_interview' => $interview->date_interview,
                    'date_publication' => $interview->date_publication,
                    'auteur' => $interview->auteur,
                    'introduction' => $interview->introduction,
                    'contenu' => $interview->contenu,
                    'image' => $interview->image ? asset('storage/' . $interview->image) : null,
                    'statut' => $interview->statut,
                    'vues' => $interview->vues,
                    'created_at' => $interview->created_at,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $interviews
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du chargement des interviews',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get statistics (API endpoint)
     */
    public function stats()
    {
        try {
            $total = Interview::where('statut', 'publie')->count();
            $parCategorie = Interview::where('statut', 'publie')
                ->selectRaw('categorie, COUNT(*) as count')
                ->groupBy('categorie')
                ->get();
            $vues = Interview::where('statut', 'publie')->sum('vues');
            $recentes = Interview::where('statut', 'publie')
                ->orderBy('date_publication', 'desc')
                ->take(5)
                ->count();

            return response()->json([
                'success' => true,
                'data' => [
                    'total' => $total,
                    'par_categorie' => $parCategorie,
                    'vues_totales' => $vues,
                    'recentes' => $recentes
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du chargement des statistiques'
            ], 500);
        }
    }
}
