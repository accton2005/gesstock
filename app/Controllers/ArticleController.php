<?php

namespace App\Controllers;

use App\Models\Article;
use App\Models\MouvementStock;
use App\Services\AuditService;
use Core\Auth;

class ArticleController extends BaseController
{
    public function index()
    {
        $this->authorize('articles.view');
        
        $articleModel = new Article();
        $page = $_GET['page'] ?? 1;
        $search = $_GET['search'] ?? '';
        
        if ($search) {
            $articles = $articleModel->search($search);
        } else {
            $data = $articleModel->paginate($page);
            $articles = $data['data'];
        }
        
        $this->view('articles.index', [
            'title' => 'Gestion des Articles',
            'articles' => $articles,
            'search' => $search
        ]);
    }

    public function show($id)
    {
        $this->authorize('articles.view');
        
        $articleModel = new Article();
        $article = $articleModel->find($id);
        
        if (!$article) {
            http_response_code(404);
            die('Article non trouvé');
        }
        
        $mouvementModel = new MouvementStock();
        $mouvements = $mouvementModel->getMouvementsArticle($id);
        $stockActuel = $articleModel->getStockActuel($id);
        
        $this->view('articles.show', [
            'title' => 'Détail - ' . $article['designation'],
            'article' => $article,
            'mouvements' => $mouvements,
            'stockActuel' => $stockActuel
        ]);
    }

    public function create()
    {
        $this->authorize('articles.create');
        
        $this->view('articles.create', [
            'title' => 'Créer un Article'
        ]);
    }

    public function store()
    {
        $this->authorize('articles.create');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            die('Méthode non autorisée');
        }
        
        $data = [
            'code_interne' => $_POST['code_interne'] ?? '',
            'designation' => $_POST['designation'] ?? '',
            'description' => $_POST['description'] ?? '',
            'categorie' => $_POST['categorie'] ?? '',
            'unite_base' => $_POST['unite_base'] ?? 'piece',
            'stock_min' => (int)($_POST['stock_min'] ?? 0),
            'stock_max' => (int)($_POST['stock_max'] ?? 0),
            'stock_critique' => (int)($_POST['stock_critique'] ?? 0),
            'prixunitaire' => (float)($_POST['prixunitaire'] ?? 0),
            'created_by' => Auth::id()
        ];
        
        $errors = $this->validate($data, [
            'code_interne' => 'required',
            'designation' => 'required'
        ]);
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $this->redirect('/articles/create');
        }
        
        $articleModel = new Article();
        $id = $articleModel->create($data);
        
        AuditService::log('articles', $id, 'CREATE', [], $data);
        
        $this->redirect('/articles', 'Article créé avec succès');
    }

    public function edit($id)
    {
        $this->authorize('articles.edit');
        
        $articleModel = new Article();
        $article = $articleModel->find($id);
        
        if (!$article) {
            http_response_code(404);
            die('Article non trouvé');
        }
        
        $this->view('articles.edit', [
            'title' => 'Modifier - ' . $article['designation'],
            'article' => $article
        ]);
    }

    public function update($id)
    {
        $this->authorize('articles.edit');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            die('Méthode non autorisée');
        }
        
        $articleModel = new Article();
        $old = $articleModel->find($id);
        
        $data = [
            'designation' => $_POST['designation'] ?? '',
            'description' => $_POST['description'] ?? '',
            'categorie' => $_POST['categorie'] ?? '',
            'stock_min' => (int)($_POST['stock_min'] ?? 0),
            'stock_max' => (int)($_POST['stock_max'] ?? 0),
            'stock_critique' => (int)($_POST['stock_critique'] ?? 0),
            'prixunitaire' => (float)($_POST['prixunitaire'] ?? 0)
        ];
        
        $articleModel->update($id, $data);
        
        AuditService::log('articles', $id, 'UPDATE', $old, $data);
        
        $this->redirect('/articles', 'Article mis à jour');
    }

    public function archive($id)
    {
        $this->authorize('articles.edit');
        
        $motif = $_POST['motif'] ?? 'Archivage de l\'article';
        
        $articleModel = new Article();
        $articleModel->archive($id, $motif);
        
        AuditService::log('articles', $id, 'ARCHIVE', [], ['motif' => $motif]);
        
        $this->redirect('/articles', 'Article archivé');
    }
}
