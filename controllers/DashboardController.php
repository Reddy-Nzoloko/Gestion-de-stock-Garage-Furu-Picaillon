<?php
declare(strict_types=1);

final class DashboardController extends Controller
{
    public function index(): void
    {
        $dashboard = new Dashboard();
        $this->render('dashboard/index', [
            'indicators' => $dashboard->indicators(),
            'daily' => $dashboard->dailyTotals(),
            'movements' => (new Movement())->recent(),
            'pageTitle' => 'Tableau de bord',
        ]);
    }
}
