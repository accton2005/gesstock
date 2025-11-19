<?php

namespace App\Controllers;

use App\Models\Inventaire;
use App\Models\Article;
use App\Services\AuditService;
use Core\Auth;

class InventaireController extends BaseController
{
    public function index()
    {
        $this->authorize('stock.view');
        
        $inventaireModel = new Inventaire();
        $db = \Core\Database::getInstance()->getConnection();
        
        $stmt = $db->prepare("SELECT * FROM inventaires ORDER BY date_debut DESC LIMIT 20");
        $stmt->execute();
        $inventaires = $stmt->fetchAll();
        
        $this->view('inventaires.index', [
            'title' => 'Gestion des Inventaires',
            'inventaires' => $inventaires
        ]);
    }

    public function create()
    {
        $this->authorize('stock.view');
        
        $this->view('inventaires.create', [
            'title' => 'Nouvel Inventaire'
        ]);
    }

    public function store()
    {
        $this->authorize('stock.view');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            die('Méthode non autorisée');
        }
        
        $data = [
            'type' => $_POST['type'] ?? 'periodique',
            'notes' => $_POST['notes'] ?? ''
        ];
        
        $inventaireModel = new Inventaire();
        $id = $inventaireModel->createInventaire($data);
        
        $articleModel = new Article();
        $articles = $articleModel->getWithStock();
        
        foreach ($articles as $article) {
            $inventaireModel->addArticleToInventaire($id, $article['id'], $article['stock_actuel']);
        }
        
        AuditService::log('inventaires', $id, 'CREATE', [], $data);
        
        $this->redirect('/inventaires', 'Inventaire créé');
    }

    public function show($id)
    {
        $this->authorize('stock.view');
        
        $inventaireModel = new Inventaire();
        $inventaire = $inventaireModel->find($id);
        
        if (!$inventaire) {
            http_response_code(404);
            die('Inventaire non trouvé');
        }
        
        $articles = $inventaireModel->getDetailsArticles($id);
        $ecarts = $inventaireModel->getEcartsInventaire($id);
        
        $this->view('inventaires.show', [
            'title' => 'Inventaire #' . $inventaire['numero_inventaire'],
            'inventaire' => $inventaire,
            'articles' => $articles,
            'ecarts' => $ecarts
        ]);
    }
}
