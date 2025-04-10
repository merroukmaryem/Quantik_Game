<?php


require_once 'PieceQuantik.php';
require_once 'PlateauQuantik.php';
require_once 'ActionQuantik.php';
// Créer un plateau de jeu
$plateau = new PlateauQuantik();
// Créer une action
$action = new ActionQuantik($plateau);

$action->posePiece(0,0,PieceQuantik::initBlackCube());
$action->posePiece(0,1,PieceQuantik::initWhiteSphere());
$action->posePiece(0,2,PieceQuantik::initWhiteCylindre());
$action->posePiece(0,3,PieceQuantik::initWhiteCone());

echo $action;

if($action->isValidePose(1, 0,PieceQuantik::initwhiteSphere())) echo "(0,1) valide \n";
else echo "invalide \n";

echo "Ligne 0 gagnante ? " ;
if( $action->isRowWin(0) ) echo "Oui \n";
else echo "Non\n";

// Vérifier si une colonne est gagnante
echo "Colonne 0 gagnante ? " ;
if($action->isColWin(0) ) echo "Oui  \n";
else echo "Non \n";
echo "Colonne 3 gagnante ? " ;
if($action->isColWin(3)) echo "Oui  \n";
else echo "Non \n";

// Vérifier si un coin est gagnant
echo "Coin 0 gagnant ? " ;
if($action->isCornerWin(0) ) echo "Oui  \n";
else echo "Non\n";
echo "Coin 3 gagnant ? " ;
if($action->isCornerWin(3) ) echo "Oui  \n";
else echo "Non \n";


?>