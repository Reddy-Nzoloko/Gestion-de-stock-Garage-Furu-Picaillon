<?php
declare(strict_types=1);

final class CashController extends Controller
{
    public function index(): void
    {
        $cash = new Cash();
        $this->render('cash/index', ['summary' => $cash->summary(), 'operations' => $cash->all(), 'pageTitle' => 'Caisse']);
    }

    public function save(): void
    {
        try {
            (new Cash())->register($_POST);
            $this->flash('success', 'L’opération de caisse a été enregistrée.');
        } catch (Throwable $exception) {
            $this->flash('error', $exception->getMessage());
        }
        $this->redirect('caisse');
    }
}
