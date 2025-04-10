<?php


use Quantik2024\ArrayPieceQuantik;

require_once 'PieceQuantik.php';
require_once 'ArrayPieceQuantik.php';


$apq = ArrayPieceQuantik::initPiecesNoires();
for ($i = 0; $i < count($apq); $i++)
    echo $apq[$i];
echo "\n";
$apq = ArrayPieceQuantik::initPiecesBlanches();
for ($i = 0; $i < count($apq); $i++)
    echo $apq[$i];
echo "\n";


/* *********************** TRACE d'éxécution de ce programme
( CUBE : BLACK )( CONE : BLACK )( CYLINDRE : BLACK )( SPHERE : BLACK )( CUBE : BLACK )( CONE : BLACK )( CYLINDRE : BLACK )( SPHERE : BLACK )
( CUBE : WHITE )( CONE : WHITE )( CYLINDRE : WHITE )( SPHERE : WHITE )( CUBE : WHITE )( CONE : WHITE )( CYLINDRE : WHITE )( SPHERE : WHITE )
*********************** */
?>