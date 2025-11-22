<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Infraction.php';

class InfractionController extends Controller
{
    public function index()
    {
        // Option : si tu veux une session sur cette page aussi
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Récupération de toutes les infractions
        $infractions = Infraction::getAll();

        self::render('infractions/index', [
            'infractions' => $infractions,
        ]);
    }
}
