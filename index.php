<?php
namespace Quantik2024;

require_once 'PDOQuantik.php';
require_once 'env/db.php';
session_start();

use Quantik2024\PDOQuantik;
\Quantik2024\PDOQuantik::initPDO($_ENV['sgbd'], $_ENV['host'], $_ENV['database'], $_ENV['user'], $_ENV['password']);


?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quantik</title>
    <link rel='stylesheet' href='Style.css' type='text/css' />
</head>
<body class="back">

<main class="parties">
    <h1>Quantik</h1>
    <p>Salon de jeux de  <?php echo  $_SESSION['player']->getName();?></p>
    <form action="traitFormQuantik.php" method="post">
        <input type="submit"  name='action' value='NouvelPartie' class="nouvel">
    </form>

    <section id="parties-en-cours">
        <h2>Parties en cours</h2>
        <?php
        require_once 'PDOQuantik.php';
        require_once 'env/db.php';

        // Récupération des parties en attente d'adversaire depuis la base de données
        $partiesEnAttente = \Quantik2024\PDOQuantik::getAllGameQuantikByPlayerName($_SESSION['player']->name);
        $gamesWithPlayer2 = array_filter($partiesEnAttente, function ($game) {
            return $game->getPlayerTwo() !== null && $game->getGameStatus() == 'initialized' ||$game->getGameStatus() == 'waitingForPlayer';
        });


        if (!empty($gamesWithPlayer2)) {
            echo '<ul>';
            foreach ($gamesWithPlayer2 as $game) {
                $item = '<li>';
                $item .= '<form action="traitFormQuantik.php" method="post">';
                //$item .= '<input type="hidden" name="action" value="Visiter">';
                $item .= '<input type="hidden" name="idGame" value="' . $game->getGameId() . '">';
                $item .= '<input type="submit" name="action" value="Visiter" class="visit"/>';
                $names = explode(':', $game->getJson());
                $player1 =explode(',',$names[1]);
                $player2 =explode(',',$names[3]);

                $item .= 'Partie N°'.$game->getGameId().' : ' . $player1[0].' vs '.$player2[0] . '</form></li>';
                echo $item;
            }
            echo '</ul>';
        }
        else {
            echo '<p>Aucune partie en cour.</p>';
        }
        ?>

    </section>
    <section id="parties-en-attente">
        <h2>Parties en attente d'adversaire</h2>
        <?php

        require_once 'PDOQuantik.php';
        require_once 'env/db.php';
        $partiesEnAttente = \Quantik2024\PDOQuantik::getAllQuantikGame();
        $gamesWithoutPlayer2 = array_filter($partiesEnAttente, function ($game) {
            return $game->getPlayerTwo() === null;
        });
        // Affichage des parties en attente d'adversaire
        if (!empty($gamesWithoutPlayer2)) {
            foreach ($gamesWithoutPlayer2 as $game) {
                $item= '<form action="traitFormQuantik.php" method="post">';
                $item .= '<input type="hidden" name="idGame" value="' . $game->getGameId();
                $item .= 'Partie ID: ' . $game->getJson() . '</li>';



                $item.= '<input type="submit"  name="action" value="RendreEnCour" ';
                if (strstr($game->getJson(), $_SESSION['player']->getName())) {
                    $item .= ' disabled class="horloge" />';
                } else {
                    $item .= 'class="rejoindre"/>';
                }
                $data = json_decode($game->getJson(), true);
                $item .= 'Partie lancée par : ' .$data['name'] ;
                $item .= '</form>';

                $item .= '</ul>';
                $item .= '</form>';
                echo $item;
            }
        } else {
            echo '<p>Aucune partie en attente d\'adversaire pour le moment.</p>';
        }

        ?>

    </section>
    <section id="parties-terminees">
        <h2>Parties terminées</h2>
        <?php
        require_once 'PDOQuantik.php';
        require_once 'env/db.php';

        // Récupération des parties en attente d'adversaire depuis la base de données
        $partiesEnAttente = \Quantik2024\PDOQuantik::getAllGameQuantikByPlayerName($_SESSION['player']->name);
        $gamesWithPlayer2 = array_filter($partiesEnAttente, function ($game) {
            return $game->getGameStatus()== 'finished';
        });

        if (!empty($gamesWithPlayer2)) {
            echo '<ul>';
            foreach ($gamesWithPlayer2 as $game) {
                $item = '<li>';
                $item .= '<form action="traitFormQuantik.php" method="post">';
                //$item .= '<input type="hidden" name="action" value="Visiter">';
                $item .= '<input type="hidden" name="idGame" value="' . $game->getGameId() . '">';
                $item .= '<input type="submit" name="action" value="Visiter" class="termine" />';
                $item .= 'Partie N°  ' .$game->getGameId()  ;
                echo $item;
            }
            echo '</ul>';
        }
        else {
            echo '<p>Aucune partie términée.</p>';
        }
        ?>
    </section>
    <form action="traitFormQuantik.php" method="post">
        <input type="submit"  name='action' value='quitter' class="quitter">
    </form>

</main>


</body>
</html>