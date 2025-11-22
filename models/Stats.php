<?php

require_once __DIR__ . '/../core/Model.php';

class Stats extends Model
{
    public static function incrementVisit(string $page): void
    {
        $db = self::getDB();

        $stmt = $db->prepare("
            INSERT INTO visits (page, counter)
            VALUES (:page, 1)
            ON DUPLICATE KEY UPDATE counter = counter + 1
        ");

        $stmt->execute(['page' => $page]);
    }

    public static function getVisitCount(string $page): int
    {
        $db = self::getDB();

        $stmt = $db->prepare("
            SELECT counter
            FROM visits
            WHERE page = :page
        ");
        $stmt->execute(['page' => $page]);

        $value = $stmt->fetchColumn();

        return $value !== false ? (int) $value : 0;
    }
}
