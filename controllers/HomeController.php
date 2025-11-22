<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../helpers/functions.php';
require_once __DIR__ . '/../models/Infraction.php';

class HomeController extends Controller
{
    public function index()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Compteur de visites simple en session
        if (!isset($_SESSION['visit_count'])) {
            $_SESSION['visit_count'] = 0;
        }
        $_SESSION['visit_count']++;
        $visitCount = $_SESSION['visit_count'];

        $infraction = $_SESSION['current_infraction'] ?? null;

        $feedback = null;
        $userInputs = [
            'elements_materiels' => [],
            'elements_moraux'    => [],
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // 1) Génération d'une nouvelle infraction (toutes / prioritaires)
            if (isset($_POST['generate']) || isset($_POST['generate_important'])) {

                if (isset($_POST['generate_important'])) {
                    $infraction = Infraction::getRandomImportant();
                } else {
                    $infraction = Infraction::getRandom();
                }

                $_SESSION['current_infraction'] = $infraction;

                if ($infraction) {
                    $userInputs['elements_materiels'] = array_fill(
                        0,
                        count($infraction['elements_materiels'] ?? []),
                        ''
                    );
                    $userInputs['elements_moraux'] = array_fill(
                        0,
                        count($infraction['elements_moraux'] ?? []),
                        ''
                    );
                }
            }

            // 2) Vérification des réponses
            if (isset($_POST['check']) && !empty($_SESSION['current_infraction'])) {
                $infraction = $_SESSION['current_infraction'];

                $userEm = $_POST['elements_materiels'] ?? [];
                $userMo = $_POST['elements_moraux']    ?? [];

                // On garde les saisies pour les réafficher dans les champs
                $userInputs['elements_materiels'] = $userEm;
                $userInputs['elements_moraux']    = $userMo;

                $feedback = [
                    'elements_materiels' => [
                        'items'      => [],
                        'allCorrect' => true,
                    ],
                    'elements_moraux' => [
                        'items'      => [],
                        'allCorrect' => true,
                    ],
                ];

                // Comparaison éléments matériels
                foreach ($infraction['elements_materiels'] as $index => $expected) {
                    $userText = $userEm[$index] ?? '';
                    $correct  = $this->compareText($userText, $expected);

                    if (!$correct) {
                        $feedback['elements_materiels']['allCorrect'] = false;
                    }

                    $feedback['elements_materiels']['items'][] = [
                        'correct'  => $correct,
                        'expected' => $expected,
                        'user'     => $userText,
                    ];
                }

                // Comparaison éléments moraux
                foreach ($infraction['elements_moraux'] as $index => $expected) {
                    $userText = $userMo[$index] ?? '';
                    $correct  = $this->compareText($userText, $expected);

                    if (!$correct) {
                        $feedback['elements_moraux']['allCorrect'] = false;
                    }

                    $feedback['elements_moraux']['items'][] = [
                        'correct'  => $correct,
                        'expected' => $expected,
                        'user'     => $userText,
                    ];
                }
            }
        } else {
            // GET simple : si une infraction est déjà en session, on prépare juste les tableaux vides
            if ($infraction) {
                $userInputs['elements_materiels'] = array_fill(
                    0,
                    count($infraction['elements_materiels'] ?? []),
                    ''
                );
                $userInputs['elements_moraux'] = array_fill(
                    0,
                    count($infraction['elements_moraux'] ?? []),
                    ''
                );
            }
        }

        // Récupération des stats globales
        $stats = Infraction::getStats();

        self::render('home/index', [
            'infraction'  => $infraction,
            'feedback'    => $feedback,
            'userInputs'  => $userInputs,
            'visitCount'  => $visitCount,
            'stats'       => $stats,
        ]);
    }

    private function normalizeText(string $text): string
    {
        // 1) Tout en minuscules
        $text = mb_strtolower($text, 'UTF-8');

        // 2) Suppression des accents (tolérance)
        $trans = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);
        if ($trans !== false) {
            $text = $trans;
        }

        // 3) Suppression de la ponctuation courante
        $text = preg_replace('/[[:punct:]]+/u', ' ', $text);

        // 4) Réduction des espaces multiples en un seul
        $text = preg_replace('/\s+/', ' ', $text);

        // 5) Trim final
        return trim($text);
    }

    private function compareText(string $user, string $expected): bool
    {
        return $this->normalizeText($user) === $this->normalizeText($expected);
    }
}
