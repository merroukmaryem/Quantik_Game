<?php
namespace Quantik2024;
require_once 'QuantikGame.php';
require_once 'QuantikUIGenerator.php';
require_once 'PlateauQuantik.php';
require_once 'ActionQuantik.php';
require_once  'PDOQuantik.php';
require_once 'Player.php';
use Quantik2024\ArrayPieceQuantik;
session_start();


// Gestion des actions POST
if (isset($_POST['action'])) {
    $action = $_POST['action'];
    switch ($action) {
        case 'choisirPiece':
            if ($_SESSION['currentPlayer'] === $_SESSION['game']->getPlayerOne()) {
                $_SESSION['couleurActive'] = PieceQuantik::WHITE;
            } else {
                $_SESSION['couleurActive'] = PieceQuantik::BLACK;
            }
            $player = ($_SESSION['couleurActive'] == PieceQuantik::WHITE) ? $_SESSION['lesBlancs'] : $_SESSION['lesNoirs'];
            $piecePos = $_POST['pos'];
            $piece = $player->getPieceQuantik($piecePos);
            $_SESSION['etat'] = "posePiece";
            $_SESSION['posSelection'] = $piecePos;
            break;
        case 'poserPiece':
            $coordonnee = $_POST['coord'];
            $ligne = $coordonnee[1];
            $colonne = $coordonnee[3];
            $player = ($_SESSION['couleurActive'] == PieceQuantik::WHITE) ? $_SESSION['lesBlancs'] : $_SESSION['lesNoirs'];
            if ($_SESSION['actionQuantik']->isValidePose($ligne, $colonne, $player->getPieceQuantik($_SESSION['posSelection']))) {
                $_SESSION['actionQuantik']->posePiece($ligne, $colonne, $player->getPieceQuantik($_SESSION['posSelection']));
                $player->removePieceQuantik($_SESSION['posSelection']);
                // Vérifier s'il y a une victoire
                if ($_SESSION['actionQuantik']->isCornerWin(PlateauQuantik::getCornerFromCoord($ligne, $colonne)) || $_SESSION['actionQuantik']->isRowWin($ligne) || $_SESSION['actionQuantik']->isColWin($colonne)) {
                    $_SESSION['etat'] = "victoire";
                } else {
                    require_once 'env/db.php';
                    \Quantik2024\PDOQuantik::initPDO($_ENV['sgbd'],$_ENV['host'],$_ENV['database'],$_ENV['user'],$_ENV['password']);

                    PDOQuantik::saveGameQuantik('waitingForPlayer',$_SESSION['game']->getJson(),$_SESSION['game']->getGameId());

                    $_SESSION['etat'] = "consultePartieEnCours";
                    $_SESSION['currentPlayer'] = ($_SESSION['currentPlayer'] === $_SESSION['game']->getPlayerOne()) ?$_SESSION['game']->getPlayerTwo() :$_SESSION['game']->getPlayerOne();
                }
            }
            break;
        case 'annulerChoix':
            $_SESSION['etat'] = "choixPiece";
            unset($_SESSION['posSelection']);
            break;
        case 'recommencerPartie':
            session_unset();
            session_destroy();
            break;
        case 'NouvelPartie':
            require_once 'env/db.php';
            \Quantik2024\PDOQuantik::initPDO($_ENV['sgbd'],$_ENV['host'],$_ENV['database'],$_ENV['user'],$_ENV['password']);
            \Quantik2024\PDOQuantik::createGameQuantik($_SESSION['player']->getName(),$_SESSION['player']->getJson());
            break;
        case 'RendreEnCour':
            require_once 'env/db.php';
            echo "Action RendreEnCour détectée";
            if (isset($_POST['idGame'])) {
                // Ajoutez un message de débogage pour afficher l'ID du jeu
                echo "ID du jeu: " . $_POST['idGame'];
                \Quantik2024\PDOQuantik::initPDO($_ENV['sgbd'],$_ENV['host'],$_ENV['database'],$_ENV['user'],$_ENV['password']);
                $game=PDOQuantik::getGameQuantikById(intval($_POST['idGame']));
                PDOQuantik::saveGameQuantik('initialized',$game->getJson(),$game->getGameId());

                \Quantik2024\PDOQuantik::addPlayerToGameQuantik($_SESSION['player']->getName(), $game->getJson().$_SESSION['player']->getJson(), intval($_POST['idGame']));
                header('Location: index.php');
                exit(); // Assurez-vous de terminer le script après la redirection
            } else {
                echo "Erreur: ID du jeu non trouvé dans la requête POST";
            }
            break;
        case 'Visiter':
            require_once 'env/db.php';
            \Quantik2024\PDOQuantik::initPDO($_ENV['sgbd'],$_ENV['host'],$_ENV['database'],$_ENV['user'],$_ENV['password']);

            $game = PDOQuantik::getGameQuantikById(intval($_POST['idGame']));
            $_SESSION['game'] = $game;

            $connectedPlayer = $_SESSION['player']->getId();

            if ($game->getPlayerOne() === $connectedPlayer) {
                $_SESSION['currentPlayer'] = $game->getPlayerOne();
                $_SESSION['couleurActive'] = PieceQuantik::WHITE;

            } else {
                $_SESSION['currentPlayer'] = $game->getPlayerTwo();
                $_SESSION['couleurActive'] = PieceQuantik::BLACK;


            }
            if($connectedPlayer==$_SESSION['currentPlayer'])
            { $_SESSION['etat'] = 'choixPiece';
            }
            else{
                $_SESSION['etat'] = 'consultePartieEnCours';

            }
            header('Location: Acceuil.php');
            break;

        case 'quitter':
            $_SESSION['etat'] = "login";
            header('Location: Acceuil.php');
            break;
        case 'retourAcceuil':
            $_SESSION['etat'] = 'home';
            header('Location: Acceuil.php');
            break;
        default:
            $_SESSION['etat'] = "erreur";
            break;
    }

    header('Location: Acceuil.php');
}