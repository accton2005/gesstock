<?php

namespace App\Controllers;

use App\Models\MouvementStock;
use App\Models\Article;
use App\Services\AuditService;
use Core\Auth;

class StockController extends BaseController
{
    public function index()
    {
        $this->authorize('stock.view');
        
        $articleModel = new Article();
        $articles = $articleModel->getWithStock();
        $alertes = $articleModel->getArticlesEnAlerte();
        
        $this->view('stock.index', [
            'title' => 'Gestion du Stock',
            'articles' => $articles,
            'articlesEnAlerte' => $alertes
        ]);
    }

    public function mouvements()
    {
        $this->authorize('stock.view');
        
        $mouvementModel = new MouvementStock();
        $type = $_GET['type'] ?? null;
        $dateDebut = $_GET['date_debut'] ?? null;
        $dateFin = $_GET['date_fin'] ?? null;
        
        $mouvements = $mouvementModel->getMouvementsFiltrés($type, $dateDebut, $dateFin);
        
        $this->view('stock.mouvements', [
            'title' => 'Mouvements de Stock',
            'mouvements' => $mouvements,
            'type' => $type,
            'dateDebut' => $dateDebut,
            'dateFin' => $dateFin
        ]);
    }

    public function createMouvement()
    {
        $this->authorize('stock.create');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            die('Méthode non autorisée');
        }
        
        $data = [
            'article_id' => (int)($_POST['article_id'] ?? 0),
            'type' => $_POST['type'] ?? 'sortie',
            'quantite' => (int)($_POST['quantite'] ?? 0),
            'justification' => $_POST['justification'] ?? '',
            'reference_document' => $_POST['reference_document'] ?? '',
            'statut' => Auth::can('stock.approve') ? 'approuve' : 'en_attente',
            'approuve_par' => Auth::can('stock.approve') ? Auth::id() : null,
            'date_approbation' => Auth::can('stock.approve') ? date('Y-m-d H:i:s') : null
        ];
        
        $errors = $this->validate($data, [
            'article_id' => 'required|numeric',
            'quantite' => 'required|numeric',
            'type' => 'required'
        ]);
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $this->redirect('/stock');
        }
        
        $mouvementModel = new MouvementStock();
        $id = $mouvementModel->createMouvement($data);
        
        AuditService::log('mouvements_stock', $id, 'CREATE', [], $data);
        
        $this->redirect('/stock', 'Mouvement enregistré avec succès');
    }

    public function export()
    {
        $this->authorize('stock.export');
        
        $mouvementModel = new MouvementStock();
        $mouvements = $mouvementModel->getMouvementsFiltrés();
        
        $headers = ['Date', 'Type', 'Article', 'Code', 'Quantité', 'Justification', 'Statut'];
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="mouvements_stock_' . date('Ymd_His') . '.csv"');
        
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        fputcsv($output, $headers, ';');
        
        foreach ($mouvements as $mouvement) {
            fputcsv($output, [
                $mouvement['date_mouvement'],
                $mouvement['type'],
                $mouvement['designation'],
                $mouvement['code_interne'],
                $mouvement['quantite'],
                $mouvement['justification'],
                $mouvement['statut']
            ], ';');
        }
        
        fclose($output);
        exit;
    }
}
