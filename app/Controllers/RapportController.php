<?php

namespace App\Controllers;

use App\Models\Article;
use App\Models\MouvementStock;
use App\Services\ExportService;
use Core\Auth;

class RapportController extends BaseController
{
    public function index()
    {
        Auth::authorize('reports.view');
        
        $this->view('rapports.index', [
            'title' => 'Rapports & Exports'
        ]);
    }

    public function exportArticles()
    {
        Auth::authorize('reports.view');
        
        $articleModel = new Article();
        $articles = $articleModel->getWithStock();
        
        $headers = ['Code Interne', 'Désignation', 'Catégorie', 'Stock Actuel', 'Stock Min', 'Stock Max', 'Prix Unitaire'];
        
        ExportService::exportToExcel($articles, $headers, 'articles_' . date('Ymd_His') . '.csv');
    }

    public function exportMouvements()
    {
        Auth::authorize('stock.export');
        
        $mouvementModel = new MouvementStock();
        $mouvements = $mouvementModel->getMouvementsFiltrés();
        
        $headers = ['Date', 'Type', 'Article', 'Code', 'Quantité', 'Justification', 'Utilisateur'];
        
        ExportService::exportToExcel($mouvements, $headers, 'mouvements_' . date('Ymd_His') . '.csv');
    }

    public function rapportStock()
    {
        Auth::authorize('reports.view');
        
        $articleModel = new Article();
        $articles = $articleModel->getWithStock();
        
        $html = ExportService::generatePDFContent(
            'Rapport de Stock - ' . date('d/m/Y'),
            $articles,
            [
                'code_interne' => 'Code',
                'designation' => 'Désignation',
                'stock_actuel' => 'Stock',
                'stock_min' => 'Min'
            ]
        );
        
        ExportService::exportToPDF($html, 'rapport_stock_' . date('Ymd_His') . '.pdf');
    }
}
