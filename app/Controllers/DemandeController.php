<?php

namespace App\Controllers;

use App\Models\Demande;
use App\Models\Article;
use App\Services\AuditService;
use Core\Auth;

class DemandeController extends BaseController
{
    public function index()
    {
        $this->authorize('demandes.view');
        
        $demandeModel = new Demande();
        $role = Auth::role();
        
        if ($role === 'admin' || $role === 'magasinier') {
            $demandes = $demandeModel->getDemandesEnAttente();
        } else {
            $demandes = $demandeModel->getDemandesUtilisateur(Auth::id());
        }
        
        $this->view('demandes.index', [
            'title' => 'Demandes de Matériel',
            'demandes' => $demandes
        ]);
    }

    public function show($id)
    {
        $this->authorize('demandes.view');
        
        $demandeModel = new Demande();
        $demande = $demandeModel->find($id);
        
        if (!$demande) {
            http_response_code(404);
            die('Demande non trouvée');
        }
        
        $articles = $demandeModel->getDetailsArticles($id);
        
        $this->view('demandes.show', [
            'title' => 'Demande #' . $demande['numero_demande'],
            'demande' => $demande,
            'articles' => $articles
        ]);
    }

    public function create()
    {
        $this->authorize('demandes.create');
        
        $articleModel = new Article();
        $articles = $articleModel->getWithStock();
        
        $this->view('demandes.create', [
            'title' => 'Nouvelle Demande',
            'articles' => $articles
        ]);
    }

    public function store()
    {
        $this->authorize('demandes.create');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            die('Méthode non autorisée');
        }
        
        $data = [
            'service_demandeur' => Auth::id(),
            'justification' => $_POST['justification'] ?? '',
            'priorite' => $_POST['priorite'] ?? 'normale',
            'statut' => 'soumise'
        ];
        
        $demandeModel = new Demande();
        $id = $demandeModel->createDemande($data);
        
        $articles = $_POST['articles'] ?? [];
        if ($id && !empty($articles)) {
            $db = \Core\Database::getInstance()->getConnection();
            foreach ($articles as $articleId => $quantite) {
                if ($quantite > 0) {
                    $stmt = $db->prepare("
                        INSERT INTO details_demande (demande_id, article_id, quantite_demandee)
                        VALUES (?, ?, ?)
                    ");
                    $stmt->execute([$id, $articleId, $quantite]);
                }
            }
        }
        
        AuditService::log('demandes', $id, 'CREATE', [], $data);
        
        $this->redirect('/demandes', 'Demande créée avec succès');
    }

    public function validate($id)
    {
        $this->authorize('demandes.validate');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            die('Méthode non autorisée');
        }
        
        $action = $_POST['action'] ?? 'validate';
        $motif = $_POST['motif'] ?? '';
        
        $demandeModel = new Demande();
        
        if ($action === 'validate') {
            $demandeModel->validateDemande($id, Auth::id());
            AuditService::log('demandes', $id, 'VALIDATE', [], []);
            $this->redirect('/demandes', 'Demande validée');
        } else {
            $demandeModel->rejectDemande($id, $motif, Auth::id());
            AuditService::log('demandes', $id, 'REJECT', [], ['motif' => $motif]);
            $this->redirect('/demandes', 'Demande rejetée');
        }
    }
}
