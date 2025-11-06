<?php

namespace App\Http\Controllers;

use App\Models\Actualite;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ActualiteController extends Controller
{
    /**
     * Afficher la page principale avec formulaire et liste
     */
    public function index()
    {
        $actualites = Actualite::with('admin')
            ->orderBy('date_publication', 'desc')
            ->get();

        $admins = Admin::all();

        // Statistiques
        $totalArticles = $actualites->count();
        $publishedArticles = $actualites->where('publie', true)->count();
        $draftArticles = $actualites->where('publie', false)->count();
        $totalCategories = $actualites->pluck('categorie')->unique()->count();

        return view('actualites', compact(
            'actualites',
            'admins',
            'totalArticles',
            'publishedArticles',
            'draftArticles',
            'totalCategories'
        ));
    }

    /**
     * Créer une nouvelle actualité
     */
    public function store(Request $request)
    {
        // DEBUG: Log toutes les données reçues
        Log::info('=== DEBUT UPLOAD ===');
        Log::info('Request data:', $request->all());
        Log::info('Has file:', ['has' => $request->hasFile('media')]);

        if ($request->hasFile('media')) {
            $file = $request->file('media');
            Log::info('File info:', [
                'nom' => $file->getClientOriginalName(),
                'taille' => $file->getSize(),
                'mime' => $file->getMimeType(),
                'extension' => $file->getClientOriginalExtension(),
                'isValid' => $file->isValid(),
                'error' => $file->getError(),
                'errorMessage' => $file->getErrorMessage()
            ]);
        }

        // Validation avec messages d'erreur personnalisés
        try {
            $validated = $request->validate([
                'admin_id' => 'required|exists:admins,id',
                'titre' => 'required|string|max:255',
                'contenu' => 'required|string',
                'media' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,avi,mov,webm|max:51200', // 50MB
                'categorie' => 'required|string',
                'date_publication' => 'required|date',
                'publie' => 'nullable',
            ], [
                'media.max' => 'Le fichier ne doit pas dépasser 50 Mo',
                'media.mimes' => 'Format non supporté. Utilisez : jpg, jpeg, png, gif, mp4, avi, mov, webm'
            ]);

            Log::info('Validation OK');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Erreur validation:', $e->errors());
            throw $e;
        }

        try {
            // Gérer l'upload du média
            if ($request->hasFile('media')) {
                $file = $request->file('media');

                // Vérifier que le fichier est valide
                if (!$file->isValid()) {
                    Log::error('Fichier invalide:', [
                        'error' => $file->getError(),
                        'message' => $file->getErrorMessage()
                    ]);
                    throw new \Exception('Le fichier est invalide: ' . $file->getErrorMessage());
                }

                $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
                Log::info('Tentative enregistrement:', ['filename' => $filename]);

                $mediaPath = $file->storeAs('actualites', $filename, 'public');

                if (!$mediaPath) {
                    throw new \Exception('Erreur lors de l\'enregistrement du fichier');
                }

                Log::info('Fichier enregistré:', ['path' => $mediaPath]);
                $validated['image'] = $mediaPath;
            }

            // Convertir la checkbox en boolean
            $validated['publie'] = $request->has('publie') ? 1 : 0;

            Log::info('Création actualité:', $validated);
            $actualite = Actualite::create($validated);
            Log::info('Actualité créée:', ['id' => $actualite->id]);

            // Support AJAX
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Article créé avec succès!',
                    'data' => $actualite
                ], 201);
            }

            return redirect()->route('actualites.index')
                ->with('success', 'Article créé avec succès !');

        } catch (\Exception $e) {
            Log::error('=== ERREUR UPLOAD ===');
            Log::error('Message:', ['error' => $e->getMessage()]);
            Log::error('Trace:', ['trace' => $e->getTraceAsString()]);

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur : ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la création : ' . $e->getMessage());
        }
    }

    /**
     * Afficher une actualité spécifique
     */
    public function show(Actualite $actualite)
    {
        // Support AJAX
        if (request()->wantsJson() || request()->ajax()) {
            $actualite->load('admin');
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $actualite->id,
                    'admin_id' => $actualite->admin_id,
                    'titre' => $actualite->titre,
                    'contenu' => $actualite->contenu,
                    'image' => $actualite->image ? asset('storage/' . $actualite->image) : null,
                    'categorie' => $actualite->categorie,
                    'date_publication' => $actualite->date_publication->format('Y-m-d\TH:i'),
                    'publie' => $actualite->publie,
                    'admin_nom' => $actualite->admin->nom ?? 'Inconnu'
                ]
            ]);
        }

        $actualites = Actualite::with('admin')
            ->orderBy('date_publication', 'desc')
            ->get();

        $admins = Admin::all();

        // Statistiques
        $totalArticles = $actualites->count();
        $publishedArticles = $actualites->where('publie', true)->count();
        $draftArticles = $actualites->where('publie', false)->count();
        $totalCategories = $actualites->pluck('categorie')->unique()->count();

        $actualite->load('admin');

        return view('actualites', compact(
            'actualites',
            'actualite',
            'admins',
            'totalArticles',
            'publishedArticles',
            'draftArticles',
            'totalCategories'
        ));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Actualite $actualite)
    {
        // Support AJAX
        if (request()->wantsJson() || request()->ajax()) {
            $actualite->load('admin');
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $actualite->id,
                    'admin_id' => $actualite->admin_id,
                    'titre' => $actualite->titre,
                    'contenu' => $actualite->contenu,
                    'image' => $actualite->image ? asset('storage/' . $actualite->image) : null,
                    'categorie' => $actualite->categorie,
                    'date_publication' => $actualite->date_publication->format('Y-m-d\TH:i'),
                    'publie' => $actualite->publie,
                    'is_video' => $actualite->isVideo()
                ]
            ]);
        }

        $actualites = Actualite::with('admin')
            ->orderBy('date_publication', 'desc')
            ->get();

        $admins = Admin::all();

        // Statistiques
        $totalArticles = $actualites->count();
        $publishedArticles = $actualites->where('publie', true)->count();
        $draftArticles = $actualites->where('publie', false)->count();
        $totalCategories = $actualites->pluck('categorie')->unique()->count();

        return view('actualites', compact(
            'actualites',
            'actualite',
            'admins',
            'totalArticles',
            'publishedArticles',
            'draftArticles',
            'totalCategories'
        ));
    }

    /**
     * Mettre à jour une actualité
     */
    public function update(Request $request, Actualite $actualite)
    {
        $validated = $request->validate([
            'admin_id' => 'required|exists:admins,id',
            'titre' => 'required|string|max:255',
            'contenu' => 'required|string',
            'media' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,avi,mov,webm|max:51200',
            'categorie' => 'required|string',
            'date_publication' => 'required|date',
            'publie' => 'nullable',
        ], [
            'media.max' => 'Le fichier ne doit pas dépasser 50 Mo',
            'media.mimes' => 'Format non supporté'
        ]);

        try {
            // Gérer le nouveau média
            if ($request->hasFile('media')) {
                $file = $request->file('media');

                if (!$file->isValid()) {
                    throw new \Exception('Le fichier est invalide ou corrompu');
                }

                // Supprimer l'ancien média
                if ($actualite->image && Storage::disk('public')->exists($actualite->image)) {
                    Storage::disk('public')->delete($actualite->image);
                }

                $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
                $mediaPath = $file->storeAs('actualites', $filename, 'public');

                if (!$mediaPath) {
                    throw new \Exception('Erreur lors de l\'enregistrement du fichier');
                }

                $validated['image'] = $mediaPath;
            }

            $validated['publie'] = $request->has('publie') ? 1 : 0;

            $actualite->update($validated);

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Article modifié avec succès!',
                    'data' => $actualite
                ]);
            }

            return redirect()->route('actualites.index')
                ->with('success', 'Article modifié avec succès !');

        } catch (\Exception $e) {
            Log::error('Erreur modification média', [
                'actualite_id' => $actualite->id,
                'message' => $e->getMessage()
            ]);

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur : ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la modification : ' . $e->getMessage());
        }
    }

    /**
     * Supprimer une actualité
     */
    public function destroy(Actualite $actualite)
    {
        try {
            if ($actualite->image && Storage::disk('public')->exists($actualite->image)) {
                Storage::disk('public')->delete($actualite->image);
            }

            $actualite->delete();

            // Support AJAX
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Article supprimé avec succès!'
                ]);
            }

            return redirect()->route('actualites.index')
                ->with('success', 'Article supprimé avec succès !');
        } catch (\Exception $e) {
            // Support AJAX
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la suppression : ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('actualites.index')
                ->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }
}
