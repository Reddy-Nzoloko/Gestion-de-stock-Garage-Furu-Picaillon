<?php
declare(strict_types=1);

final class Backup extends Model
{
    public function sql(): string
    {
        $lines = [
            '-- Sauvegarde Garage FURU / HAOJUE',
            '-- Générée le ' . date('Y-m-d H:i:s'),
            'CREATE DATABASE IF NOT EXISTS `' . str_replace('`', '``', DB_NAME) . '` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;',
            'USE `' . str_replace('`', '``', DB_NAME) . '`;',
            'SET FOREIGN_KEY_CHECKS=0;',
            '',
        ];
        $tables = $this->db->query('SHOW FULL TABLES WHERE Table_type = \'BASE TABLE\'')->fetchAll(PDO::FETCH_NUM);
        foreach ($tables as [$table]) {
            $quotedTable = '`' . str_replace('`', '``', $table) . '`';
            $create = $this->db->query('SHOW CREATE TABLE ' . $quotedTable)->fetch(PDO::FETCH_NUM);
            $lines[] = 'DROP TABLE IF EXISTS ' . $quotedTable . ';';
            $lines[] = $create[1] . ';';
            $rows = $this->db->query('SELECT * FROM ' . $quotedTable)->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as $row) {
                $values = array_map(fn (mixed $value): string => $value === null ? 'NULL' : $this->db->quote((string) $value), $row);
                $lines[] = 'INSERT INTO ' . $quotedTable . ' (`' . implode('`, `', array_keys($row)) . '`) VALUES (' . implode(', ', $values) . ');';
            }
            $lines[] = '';
        }
        $lines[] = 'SET FOREIGN_KEY_CHECKS=1;';
        return implode("\n", $lines) . "\n";
    }
}
