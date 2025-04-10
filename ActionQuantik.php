<?php
namespace Quantik2024;

use Quantik2024\ArrayPieceQuantik;


require_once 'PieceQuantik.php';
require_once 'PlateauQuantik.php';
require_once 'ArrayPieceQuantik.php';
class ActionQuantik{
    protected PlateauQuantik  $plateau;
    // Constructeur de la classe
    public function __construct (PlateauQuantik $plateau)
    {
        $this->plateau=$plateau;

    }

    /**
     * @return PlateauQuantik
     */
    public function getPlateau(): PlateauQuantik
    {
        return $this->plateau;
    }
    // Méthode statique privée pour vérifier si un ensemble de pièces forme une combinaison gagnante
    private static function isComboWin(ArrayPieceQuantik $pieces): bool {
        for ($i = 0; $i < count($pieces); $i++) {
            if( $pieces->getPieceQuantik($i)->getForme()==0) return false;
            for ($j = $i + 1; $j < count($pieces); $j++) {
                // Vérifie si deux pièces ont la même forme dans l'ensemble
                if ($pieces->getPieceQuantik($i)->getForme() == $pieces->getPieceQuantik($j)->getForme() ) {
                    return false; // Si une paire est trouvée, retourne false immédiatement
                }
            }
        }
        return true; // Si aucune paire n'est trouvée, retourne true
    }

    // Méthode statique privée pour vérifier si une pièce est valide dans un ensemble de pièces
    private static function isPieceValide(ArrayPieceQuantik $pieces, PieceQuantik $p): bool
    {
        //n'est pas valide si une des forme de l'array est égal et la couleur est différente
        for ($i = 0; $i < count($pieces); $i++) {
            $piece = $pieces->getPieceQuantik($i);
            if ($piece->getForme() == $p->getForme() && $piece->getCouleur() != $p->getCouleur()) {
                return false;
            }
        }
        return true;
    }
    // Méthode pour vérifier si une ligne est gagnante
    public function isRowWin(int $numRow):bool
    {
        return self::isComboWin( $this->plateau->getRow($numRow));
    }
    // Méthode pour vérifier si une colonne est gagnante
    public function isColWin(int $numCol):bool
    {
        return self::isComboWin( $this->plateau->getCol($numCol));
    }
    // Méthode pour vérifier si un coin est gagnant
    public function isCornerWin(int $dir):bool
    {
        return self::isComboWin( $this->plateau->getCorner($dir));
    }
    // Méthode pour vérifier si la position d'une pièce est valide à un emplacement donné
    public function isValidePose(int $rowNum, int $colNum, PieceQuantik $piece): bool
    {
        return $this->isPieceValide($this->plateau->getRow($rowNum), $piece) // Vérification que la ligne est bonnne
            &&$this->isPieceValide($this->plateau->getCol($colNum), $piece) // Vérification que la colonne est bonnne
            && $this->isPieceValide($this->plateau->getCorner($this->plateau::getCornerFromCoord($rowNum, $colNum)), $piece) // Vérification que le coin est bon
            && $this->plateau->getPiece($rowNum, $colNum)->getForme() == PieceQuantik::VOID; // Il n'y a pas de pièce à l'endroit ou l'on pose la pièce
    }


// Méthode pour poser une pièce à un emplacement donné
    public function posePiece(int $rowNum, int $colNum, PieceQuantik $piece): void
    {if($this->isValidePose( $rowNum,$colNum,$piece))
        $this->plateau->setPiece($rowNum, $colNum, $piece);

    }

    public function __toString() : String{
        return $this->plateau->__toString();    }


}