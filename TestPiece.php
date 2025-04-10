<?php



require_once 'PieceQuantik.php';

echo "<pre>\n";
echo PieceQuantik::initBlackCone()."<br />\n";
echo PieceQuantik::initWhiteCone()."<br />\n";
echo PieceQuantik::initBlackCube()."<br />\n";
echo PieceQuantik::initWhiteCube()."<br />\n";
echo PieceQuantik::initBlackCylindre()."<br />\n";
echo PieceQuantik::initWhiteCylindre()."<br />\n";
echo PieceQuantik::initBlackSphere()."<br />\n";
echo PieceQuantik::initWhiteSphere()."<br />\n";
echo PieceQuantik::initVOID()."<br />\n";
echo "</pre>\n";

/*
 <pre>
( CONE : BLACK )<br />
( CONE : WHITE )<br />
( CUBE : BLACK )<br />
( CUBE : WHITE )<br />
( CYLINDRE : BLACK )<br />
( CYLINDRE : WHITE )<br />
( SPHERE : BLACK )<br />
( SPHERE : WHITE )<br />
( void : WHITE )<br />
</pre>
 */

?>