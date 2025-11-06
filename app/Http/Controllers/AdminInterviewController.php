<?php

namespace App\Http\Controllers;

use App\Models\Interview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AdminInterviewController extends Controller
{
    /**
     * Afficher la liste des interviews
     */
    public function index()
    {
        $interviews = Interview::orderBy('created_at', 'desc')->paginate(15);

        $stats = [
            'total' => Interview::count(),
            'publies' => Interview::where('statut', 'publie')->count(),
            'brouillons' => Interview::where('statut', 'brouillon')->count(),
            'archives' => Interview::where('statut', 'archive')->count(),
        ];

        return view('admin.interviews.index', compact('interviews', 'stats'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        return view('admin.interviews.create');
    }

    /**
     * Enregistrer une nouvelle interview
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'titre' => 'required|string|max:255',
            'sous_titre' => 'nullable|string|max:255',
            'image' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,webm,mov|max:51200',
            'nom_interviewe' => 'required|string|max:255',
            'poste_interviewe' => 'required|string|max:255',
            'club_equipe' => 'nullable|string|max:255',
            'introduction' => 'nullable|string',
            'contenu' => 'required|string',
            'date_interview' => 'required|date',
            'date_publication' => 'nullable|date',
            'auteur' => 'nullable|string|max:255',
            'categorie' => 'required|in:joueur,entraineur,dirigeant,arbitre,journaliste,autre',
            'statut' => 'required|in:brouillon,publie,archive'
        ], [
            'titre.required' => 'Le titre est obligatoire',
            'nom_interviewe.required' => 'Le nom de l\'interviewé est obligatoire',
            'poste_interviewe.required' => 'Le poste est obligatoire',
            'contenu.required' => 'Le contenu de l\'interview est obligatoire',
            'date_interview.required' => 'La date de l\'interview est obligatoire',
            'categorie.required' => 'La catégorie est obligatoire',
            'statut.required' => 'Le statut est obligatoire',
            'image.max' => 'Le fichier ne doit pas dépasser 50 Mo',
            'image.mimes' => 'Format de fichier non supporté'
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $request->except('image');

            // Gérer l'upload de l'image/vidéo
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $data['image'] = $file->storeAs('interviews', $filename, 'public');
            }

            // Si le statut est "publié" et qu'il n'y a pas de date de publication
            if ($data['statut'] === 'publie' && empty($data['date_publication'])) {
                $data['date_publication'] = now();
            }

            Interview::create($data);

            return redirect()
                ->route('interviews')
                ->with('success', 'Interview créée avec succès !');

        } catch (\Exception $e) {
            return back()
                ->with('error', 'Erreur lors de la création : ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Afficher une interview
     */
    public function show($id)
    {
        $interview = Interview::findOrFail($id);
        return view('admin.interviews.show', compact('interview'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit($id)
    {
        $interview = Interview::findOrFail($id);
        return view('admin.interviews.edit', compact('interview'));
    }

    /**
     * Mettre à jour une interview
     */
    public function update(Request $request, $id)
    {
        $interview = Interview::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'titre' => 'required|string|max:255',
            'sous_titre' => 'nullable|string|max:255',
            'image' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,webm,mov|max:51200',
            'nom_interviewe' => 'required|string|max:255',
            'poste_interviewe' => 'required|string|max:255',
            'club_equipe' => 'nullable|string|max:255',
            'introduction' => 'nullable|string',
            'contenu' => 'required|string',
            'date_interview' => 'required|date',
            'date_publication' => 'nullable|date',
            'auteur' => 'nullable|string|max:255',
            'categorie' => 'required|in:joueur,entraineur,dirigeant,arbitre,journaliste,autre',
            'statut' => 'required|in:brouillon,publie,archive'
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $request->except('image');

            // Gérer l'upload de la nouvelle image/vidéo
            if ($request->hasFile('image')) {
                // Supprimer l'ancienne image si elle existe
                if ($interview->image) {
                    Storage::disk('public')->delete($interview->image);
                }

                $file = $request->file('image');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $data['image'] = $file->storeAs('interviews', $filename, 'public');
            }

            // Si le statut passe à "publié" et qu'il n'y a pas de date de publication
            if ($data['statut'] === 'publie' && empty($data['date_publication']) && $interview->statut !== 'publie') {
                $data['date_publication'] = now();
            }

            $interview->update($data);

            return redirect()
                ->route('interviews')
                ->with('success', 'Interview mise à jour avec succès !');

        } catch (\Exception $e) {
            return back()
                ->with('error', 'Erreur lors de la mise à jour : ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Supprimer une interview
     */
    public function destroy($id)
    {
        $interview = Interview::findOrFail($id);

        try {
            // Supprimer l'image/vidéo associée
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
                'message' => 'Erreur lors de la suppression : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Changer le statut d'une interview
     */
    public function changeStatus(Request $request, $id)
    {
        $interview = Interview::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'statut' => 'required|in:brouillon,publie,archive'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $data = ['statut' => $request->statut];

            // Si on passe à "publié" et qu'il n'y a pas de date de publication
            if ($request->statut === 'publie' && !$interview->date_publication) {
                $data['date_publication'] = now();
            }

            $interview->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Statut modifié avec succès'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la modification : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Supprimer l'image/vidéo d'une interview
     */
    public function deleteMedia($id)
    {
        $interview = Interview::findOrFail($id);

        if (!$interview->image) {
            return response()->json([
                'success' => false,
                'message' => 'Aucun média à supprimer'
            ], 404);
        }

        try {
            Storage::disk('public')->delete($interview->image);
            $interview->update(['image' => null]);

            return response()->json([
                'success' => true,
                'message' => 'Média supprimé avec succès'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Filtrer les interviews
     */
    public function filter(Request $request)
    {
        $query = Interview::query();

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('categorie')) {
            $query->where('categorie', $request->categorie);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('titre', 'like', "%{$search}%")
                  ->orWhere('nom_interviewe', 'like', "%{$search}%")
                  ->orWhere('contenu', 'like', "%{$search}%");
            });
        }

        $interviews = $query->orderBy('created_at', 'desc')->paginate(15);

        $stats = [
            'total' => Interview::count(),
            'publies' => Interview::where('statut', 'publie')->count(),
            'brouillons' => Interview::where('statut', 'brouillon')->count(),
            'archives' => Interview::where('statut', 'archive')->count(),
        ];

        return view('admin.interviews.index', compact('interviews', 'stats'));
    }
}
