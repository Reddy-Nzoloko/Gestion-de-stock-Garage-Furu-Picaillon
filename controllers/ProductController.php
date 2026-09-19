<?php
declare(strict_types=1);

final class ProductController extends Controller
{
    public function index(): void
    {
        $this->render('products/index', [
            'products' => (new Product())->all(trim((string) ($_GET['search'] ?? '')), (string) ($_GET['category'] ?? '')),
            'categories' => (new Product())->categories(),
            'pageTitle' => 'Inventaire',
        ]);
    }

    public function form(): void
    {
        $product = !empty($_GET['id']) ? (new Product())->find((int) $_GET['id']) : null;
        $this->render('products/form', ['product' => $product, 'categories' => (new Product())->categories(), 'pageTitle' => $product ? 'Modifier une référence' : 'Ajouter une référence']);
    }

    public function save(): void
    {
        try {
            (new Product())->save($_POST);
            $this->flash('success', 'La référence a été enregistrée.');
        } catch (Throwable $exception) {
            $this->flash('error', $exception->getMessage());
        }
        $this->redirect('produits');
    }

    public function delete(): void
    {
        try {
            (new Product())->delete((int) ($_POST['id'] ?? 0));
            $this->flash('success', 'La référence a été supprimée.');
        } catch (Throwable $exception) {
            $this->flash('error', 'Suppression impossible.');
        }
        $this->redirect('produits');
    }
}
