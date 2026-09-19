<?php
declare(strict_types=1);

final class CategoryController extends Controller
{
    public function index(): void
    {
        $this->render('categories/index', ['categories' => (new Category())->all(), 'pageTitle' => 'Catégories']);
    }

    public function save(): void
    {
        try {
            (new Category())->create((string) $this->post('nom'));
            $this->flash('success', 'La catégorie a été enregistrée dans la base de données.');
        } catch (Throwable $exception) {
            $this->flash('error', $exception instanceof PDOException ? 'Cette catégorie existe déjà.' : $exception->getMessage());
        }
        $this->redirect('categories');
    }
}
