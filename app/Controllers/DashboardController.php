<?php

namespace App\Controllers;

use App\Models\Article;
use App\Models\MouvementStock;
use App\Models\Demande;
use App\Models\User;
use Core\Auth;

class DashboardController extends BaseController
{
    public function index()
    {
        $this->requireAuth();
        
        $articleModel = new Article();
        $mouvementModel = new MouvementStock();
        $demandeModel = new Demande();
        $userModel = new User();
        
        $stats = [
            'articlesTotal' => $articleModel->count(['actif' => 1]),
            'articlesEnAlerte' => count($articleModel->getArticlesEnAlerte()),
            'mouvementsJour' => count($mouvementModel->getMouvementsJour()),
            'demandesEnAttente' => count($demandeModel->getDemandesEnAttente()),
            'utilisateurs' => $userModel->getActiveCount()
        ];
        
        $mouvements = $mouvementModel->getMouvementsJour();
        $demandes = $demandeModel->getDemandesEnAttente();
        $alertes = $articleModel->getArticlesEnAlerte();
        
        $this->view('dashboard', [
            'title' => 'Tableau de Bord',
            'stats' => $stats,
            'mouvements' => $mouvements,
            'demandes' => $demandes,
            'alertes' => $alertes
        ]);
    }
}
