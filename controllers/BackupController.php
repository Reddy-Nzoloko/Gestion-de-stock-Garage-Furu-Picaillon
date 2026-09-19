<?php
declare(strict_types=1);

final class BackupController extends Controller
{
    public function download(): never
    {
        $filename = 'sauvegarde-garage-' . date('Y-m-d-H-i-s') . '.sql';
        header('Content-Type: application/sql; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: no-store');
        echo (new Backup())->sql();
        exit;
    }
}
