<?php

namespace App\Controllers;

use App\Models\BonEntree;
use App\Models\Fournisseur;
use App\Models\Article;
use App\Services\AuditService;
use Core\Auth;

class ApprovisionnementController extends BaseController
{
    public function index()
    {
        $this->authorize('bons.view');
        
        $bonModel = new BonEntree();
        $bons = $bonModel->getBonsEnAttente();
        
        $this->view('approvisionnements.index', [
            'title' => 'Approvisionnements',
            'bons' => $bons
        ]);
    }

    public function create()
    {
        $this->authorize('bons.create');
        
        $fournisseurModel = new Fournisseur();
        $articleModel = new Article();
        
        $fournisseurs = $fournisseurModel->getActifs();
        $articles = $articleModel->getWithStock();
        
        $this->view('approvisionnements.create', [
            'title' => 'Nouveau Bon d\'Entrée',
            'fournisseurs' => $fournisseurs,
            'articles' => $articles
        ]);
    }

    public function store()
    {
        $this->authorize('bons.create');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            die('Méthode non autorisée');
        }
        
        $data = [
            'fournisseur_id' => (int)($_POST['fournisseur_id'] ?? 0),
            'date_commande' => $_POST['date_commande'] ?? date('Y-m-d'),
            'date_livraison_prevue' => $_POST['date_livraison_prevue'] ?? '',
            'montant_total' => (float)($_POST['montant_total'] ?? 0),
            'notes' => $_POST['notes'] ?? ''
        ];
        
        $bonModel = new BonEntree();
        $id = $bonModel->createBon($data);
        
        AuditService::log('bons_entree', $id, 'CREATE', [], $data);
        
        $this->redirect('/approvisionnements', 'Bon créé avec succès');
    }

    public function show($id)
    {
        $this->authorize('bons.view');
        
        $bonModel = new BonEntree();
        $bon = $bonModel->find($id);
        
        if (!$bon) {
            http_response_code(404);
            die('Bon non trouvé');
        }
        
        $details = $bonModel->getDetailsBon($id);
        
        $this->view('approvisionnements.show', [
            'title' => 'Bon #' . $bon['numero_bon'],
            'bon' => $bon,
            'details' => $details
        ]);
    }
}
