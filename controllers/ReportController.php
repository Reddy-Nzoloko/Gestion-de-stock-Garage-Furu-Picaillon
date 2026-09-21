<?php
declare(strict_types=1);

final class ReportController extends Controller
{
    public function invoice(): void
    {
        $movement = (new Movement())->findById((int) ($_GET['id'] ?? 0));
        if (!$movement || !in_array($movement['type_mouvement'], ['ACHAT', 'VENTE'], true)) {
            http_response_code(404);
            $this->redirect('mouvements');
        }
        $format = $_GET['format'] ?? 'a4';
        if (!in_array($format, ['a4', 'pos80', 'pos50'], true)) {
            $format = 'a4';
        }
        require __DIR__ . '/../views/reports/invoice.php';
    }

    public function daily(): void
    {
        $dashboard = new Dashboard();
        $this->render('reports/daily', ['daily' => $dashboard->dailyTotals(), 'movements' => (new Movement())->today(), 'cash' => (new Cash())->today(), 'pageTitle' => 'Rapport journalier']);
    }
}
