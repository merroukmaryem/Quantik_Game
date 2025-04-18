<?php
namespace Quantik2024;
use Quantik2024\PDOQuantik;

require_once 'PDOQuantik.php';
session_start();
function getPageLogin(): string {
    $form = '<!DOCTYPE html>
<html class="no-js" lang="fr" dir="ltr">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="Author" content="Dominique Fournier" />
    <link rel="stylesheet" href="style.css" />
    <title>Accès à la salle de jeux</title></head>
<body class="back"><div class="quantik"><h1>Accès au salon Quantik</h1><h2>Identification du joueur</h2>
<form action="'.$_SERVER['PHP_SELF'].'" method="post">
<fieldset><legend>Nom</legend>
          <input type="text" name="playerName" />
          <input type="submit" name="action" value="connecter" class="bouton"></fieldset></form>
 </div></body></html>';
    return $form;
}

if (isset($_REQUEST['playerName'])) {
    // connexion à la base de données
    require_once 'env/db.php';
    PDOQuantik::initPDO($_ENV['sgbd'],$_ENV['host'],$_ENV['database'],$_ENV['user'],$_ENV['password']);
    $player = PDOQuantik::selectPlayerByName($_REQUEST['playerName']);
    if (is_null($player))
        $player = PDOQuantik::createPlayer($_REQUEST['playerName']);
    $_SESSION['player'] = $player;
    header('HTTP/1.1 303 See Other');
    header('Location: index.php');
}
else {
    echo getPageLogin();
}