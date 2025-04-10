<?php
namespace Quantik2024;

require_once 'QuantikGame.php';
require_once 'QuantikUIGenerator.php';
require_once 'PlateauQuantik.php';
require_once 'ActionQuantik.php';
require_once 'PDOQuantik.php';


// Démarrage de la session
session_start();

// Initialisation des données de session si elles sont vides
if (empty($_SESSION)) {
    $_SESSION['lesBlancs'] = ArrayPieceQuantik::initPiecesBlanches();
    $_SESSION['lesNoirs'] = ArrayPieceQuantik::initPiecesNoires();
    $_SESSION['plateau'] = new PlateauQuantik();
    $_SESSION['etat'] = 'login';
    $_SESSION['message'] = "Erreur";
    $_SESSION['couleurActive'] = PieceQuantik::WHITE;
    $_SESSION['coleurPlayer'] = [PieceQuantik::WHITE, PieceQuantik::BLACK];

    $_SESSION['quantik'] = new QuantikGame($_SESSION['coleurPlayer']);
    $_SESSION['quantik']->plateau=$_SESSION['plateau'];
    $_SESSION['quantik']->piecesBlanches= $_SESSION['lesBlancs'];
    $_SESSION['quantik']->piecesNoires= $_SESSION['lesNoirs'];

    $_SESSION['actionQuantik'] = new ActionQuantik($_SESSION['plateau']);
}

// Affichage de la page en fonction de l'état actuel
switch ($_SESSION['etat']) {
    case 'choixPiece':
        echo QuantikUIGenerator::getPageSelectionPiece($_SESSION['quantik'], $_SESSION['couleurActive']);
        break;
    case 'posePiece':
        echo QuantikUIGenerator::getPagePosePiece($_SESSION['quantik'], $_SESSION['couleurActive'], $_SESSION['posSelection']);
        break;
    case 'victoire':
        echo QuantikUIGenerator::getPageVictoire($_SESSION['quantik'], $_SESSION['couleurActive']);
        require_once 'env/db.php';
        \Quantik2024\PDOQuantik::initPDO($_ENV['sgbd'], $_ENV['host'], $_ENV['database'], $_ENV['user'], $_ENV['password']);

        PDOQuantik::saveGameQuantik('finished', $_SESSION['game']->getJson(), $_SESSION['game']->getGameId());

        break;
    case 'login':
        header('Location: login.php');
        $_SESSION['etat'] = 'home';
        break;
    case 'home':
        header('Location: index.php');

        break;

    case 'consultePartieEnCours':
        echo QuantikUIGenerator::getPageConsultation($_SESSION['quantik']);
        break;
    default:
        echo QuantikUIGenerator::getPageErreur($_SESSION['message'], 'Acceuil.php');
        break;
}