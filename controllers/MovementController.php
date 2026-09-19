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
            if (($data['type_mouvement'] ?? '') === 'ACHAT' && trim((string) ($data['prix_unitaire'] ?? '')) === '') {
                throw new InvalidArgumentException('Le prix unitaire d’achat est obligatoire.');
            }
            if (($data['type_mouvement'] ?? '') === 'VENTE') {
                $product = (new Product())->find((int) $data['produit_id']);
                if (!$product) {
                    throw new InvalidArgumentException('Référence introuvable.');
                }
                $data['prix_unitaire'] = $product['prix_vente'];
            }
            $movementId = (new Movement())->register($data);
            $this->flash('success', 'Le mouvement a été enregistré, le stock et la caisse ont été recalculés.');
            if ($data['type_mouvement'] === 'VENTE') {
                header('Location: ' . BASE_URL . '/index.php?page=facture&id=' . $movementId);
                exit;
            }
        } catch (Throwable $exception) {
            $this->flash('error', $exception->getMessage());
        }
        $this->redirect('mouvements');
    }
}
