<?php
declare(strict_types=1);

final class MovementController extends Controller
{
    public function index(): void
    {
        $this->render('movements/index', ['movements' => (new Movement())->all(), 'products' => (new Product())->all(), 'pageTitle' => 'Mouvements']);
    }

    public function save(): void
    {
        try {
            $data = $_POST;
            $data['prix_unitaire'] = $data['type_mouvement'] === 'VENTE' ? (new Product())->find((int) $data['produit_id'])['prix_vente'] : $data['prix_unitaire'];
            (new Movement())->register($data);
            $this->flash('success', 'Le mouvement a été enregistré et le stock a été recalculé.');
        } catch (Throwable $exception) {
            $this->flash('error', $exception->getMessage());
        }
        $this->redirect('mouvements');
    }
}
