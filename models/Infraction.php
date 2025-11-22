<?php

require_once __DIR__ . '/../core/Model.php';
require_once __DIR__ . '/../helpers/functions.php';

class Infraction extends Model
{
    public static function getRandom()
    {
        $db = self::getDB();

        $stmt = $db->query("
            SELECT id, libelle, elements_materiels, elements_moraux
            FROM infractions
            ORDER BY RAND()
            LIMIT 1
        ");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        $row['elements_materiels'] = json_decode($row['elements_materiels'], true) ?: [];
        $row['elements_moraux']    = json_decode($row['elements_moraux'], true) ?: [];

        return $row;
    }

    public static function getRandomImportant()
    {
        $db = self::getDB();

        $stmt = $db->query("
            SELECT id, libelle, elements_materiels, elements_moraux
            FROM infractions
            WHERE is_important = 1
            ORDER BY RAND()
            LIMIT 1
        ");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        $row['elements_materiels'] = json_decode($row['elements_materiels'], true) ?: [];
        $row['elements_moraux']    = json_decode($row['elements_moraux'], true) ?: [];

        return $row;
    }

    public static function getStats(): array
    {
        $db = self::getDB();

        $stmt = $db->query("
            SELECT 
                COUNT(*) AS total,
                SUM(CASE WHEN is_important = 1 THEN 1 ELSE 0 END) AS prioritaires
            FROM infractions
        ");

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return [
                'total'        => 0,
                'prioritaires' => 0,
            ];
        }

        return [
            'total'        => (int) $row['total'],
            'prioritaires' => (int) ($row['prioritaires'] ?? 0),
        ];
    }

    /**
     * Liste toutes les infractions pour la vue "catalogue".
     */
    public static function getAll(): array
    {
        $db = self::getDB();

        $stmt = $db->query("
            SELECT id, libelle, elements_materiels, elements_moraux, is_important
            FROM infractions
            ORDER BY libelle ASC
        ");

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rows as &$row) {
            $row['elements_materiels'] = json_decode($row['elements_materiels'], true) ?: [];
            $row['elements_moraux']    = json_decode($row['elements_moraux'], true) ?: [];
            $row['is_important']       = (int)($row['is_important'] ?? 0);
        }

        return $rows;
    }
}
