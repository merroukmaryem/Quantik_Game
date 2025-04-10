<?php

require_once 'PieceQuantik.php';
require_once 'ArrayPieceQuantik.php';
require_once 'PlateauQuantik.php';
// Création d'une instance de PlateauQuantik
$plateau = new PlateauQuantik();
echo "affichge de plateau \n".$plateau;
echo "\n";
// Accès à une pièce à une position spécifique
$piece = $plateau->getPiece(2, 3);
echo "affichge de la piece (2,3) \n".$piece;
echo "\n";
// Définition d'une pièce à une position spécifique
$plateau->setPiece(1, 0, PieceQuantik::initBlackCone());
echo "Affichage de plateau aprés la modification de la  de la piece (1,0) \n".$plateau;
echo "\n";
// Obtention d'une ligne entière
$line = $plateau->getRow(1);
echo "affichage de la ligne 1:\n".$line;
echo "\n";
// Obtention d'une colonne entière
$column = $plateau->getCol(0);
echo "affichage de la colone 0:\n".$column;
echo "\n";
// Obtention d'un coin spécifique
$corner = $plateau->getCorner(PlateauQuantik::NW);
echo "affichage de coin NW:\n".$corner;
/****************************resultat d'affichage******************
 *
 * affichge de plateau
 * ( void : WHITE ) ( void : WHITE ) ( void : WHITE ) ( void : WHITE )
 * ( void : WHITE ) ( void : WHITE ) ( void : WHITE ) ( void : WHITE )
 * ( void : WHITE ) ( void : WHITE ) ( void : WHITE ) ( void : WHITE )
 * ( void : WHITE ) ( void : WHITE ) ( void : WHITE ) ( void : WHITE )
 *
 * affichge de la piece (2,3)
 * ( void : WHITE )
 * Affichage de plateau aprés la modification de la  de la piece (1,0)
 * ( void : WHITE ) ( void : WHITE ) ( void : WHITE ) ( void : WHITE )
 * ( CONE : BLACK ) ( void : WHITE ) ( void : WHITE ) ( void : WHITE )
 * ( void : WHITE ) ( void : WHITE ) ( void : WHITE ) ( void : WHITE )
 * ( void : WHITE ) ( void : WHITE ) ( void : WHITE ) ( void : WHITE )
 *
 * affichage de la ligne 1:
 * piece 0 : ( CONE : BLACK )\npiece 1 : ( void : WHITE )\npiece 2 : ( void : WHITE )\npiece 3 : ( void : WHITE )\n
 * affichage de la colone 0:
 * piece 0 : ( void : WHITE )\npiece 1 : ( CONE : BLACK )\npiece 2 : ( void : WHITE )\npiece 3 : ( void : WHITE )\n
 * affichage de coin NW:
 * piece 0 : ( void : WHITE )\npiece 1 : ( void : WHITE )\npiece 2 : ( CONE : BLACK )\npiece 3 : ( void : WHITE )\n
 * */

?>