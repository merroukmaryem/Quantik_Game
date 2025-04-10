<?php
namespace Quantik2024;

use Quantik2024\ArrayPieceQuantik;


require_once 'ArrayPieceQuantik.php';
require_once 'PieceQuantik.php';
class PlateauQuantik
{
    // Constantes pour définir les dimensions du plateau et les coins
    public const NBROWS = 4;
    public const NBCOLS = 4;
    public const NW = 0;
    public const NE = 1;
    public const SW = 2;
    public const SE = 3;

    // Tableau bidimensionnel pour stocker les pièces du plateau

    protected array $cases ;

    // Constructeur pour initialiser le plateau avec des instances d'ArrayPieceQuantik
    public function __construct()
    {
        for ($i = 0 ; $i < self::NBROWS ; $i++){
            $this->cases[] = new ArrayPieceQuantik();
        }
    }
    // Méthode pour obtenir une pièce à une position donnée sur le plateau
    public function getPiece(int $rowNum , int $colNum) : PieceQuantik{
        return $this->cases[$rowNum]->getPieceQuantik($colNum);
    }
    // Méthode pour définir une pièce à une position donnée sur le plateau
    public function setPiece(int $rowNum , int $colNum , PieceQuantik $pieceQuantik) : void{

        $this->cases[$rowNum]->setPieceQuantik($colNum,$pieceQuantik);

    }
    // Méthode pour obtenir une ligne du plateau
    public function getRow(int $numRow) : ArrayPieceQuantik{

        $resultat = new ArrayPieceQuantik();

        for ($i = 0 ; $i < self::NBCOLS ;$i++)
        {
            $resultat->setPieceQuantik($i,$this->cases[$numRow]->getPieceQuantik($i));
        }

        return $resultat;
    }
    // Méthode pour obtenir une colonne du plateau
    public function getCol(int $numCol) : ArrayPieceQuantik{

        $resultat = new ArrayPieceQuantik();

        for ($i = 0 ; $i < self::NBROWS ;$i++)
        {
            $resultat->setPieceQuantik($i,$this->cases[$i]->getPieceQuantik($numCol));
        }

        return $resultat;
    }

    // Méthode pour obtenir un coin du plateau
    public function getCorner(int $dir) : ArrayPieceQuantik{

        $resultat = new ArrayPieceQuantik();
        $position = 0;
        for ($i = 0 ; $i < self::NBROWS ; $i++){
            for ($j = 0 ; $j < self::NBCOLS ; $j++){
                if ($dir == self::getCornerFromCoord($i,$j)){
                    $resultat->setPieceQuantik($position,$this->cases[$i]->getPieceQuantik($j));
                    $position++;
                }
            }
        }

        return $resultat;
    }
    public function __toString(): string
    {
        $affichage = "";

        for ($row = 0; $row < self::NBROWS; $row++) {


            for ($col = 0; $col < self::NBCOLS; $col++) {
                $affichage .= $this->cases[$row]->getPieceQuantik($col) . " ";
            }

            $affichage .= "\n";
        }

        return $affichage;
    }

    // Méthode statique pour déterminer le coin à partir des coordonnées
    public static function getCornerFromCoord(int $rowNum, int $ColNum): int {

        if ($rowNum<=1){
            //on est au nord
            if ($ColNum<=1){
                //on est à l'ouest
                return self::NW;
            }else{
                //on est à l'est
                return self::NE;
            }
        } else {
            //on est au sud
            if ($ColNum<=1){
                //on est à l'ouest
                return self::SW;
            }else{
                //on est à l'est
                return self::SE;
            }
        }
    }
    public function getJson(): string {
        $json = "[";
        $jTab = [];
        foreach ($this->cases as $apq)
            $jTab[] = $apq->getJson();
        $json .= implode(',',$jTab);
        return $json.']';
    }

    public static function initPlateauQuantik(string|array $json) : PlateauQuantik
    {
        $pq = new PlateauQuantik();
        if (is_string($json))
            $json = json_decode($json);
        $cases = [];
        foreach($json as $elem)
            $cases[] = ArrayPieceQuantik::initArrayPieceQuantik($elem);
        $pq->cases = $cases;
        return $pq;
    }
    public static function initFromJson(string $json): PlateauQuantik
    {
        $decodedJson = json_decode($json);
        return self::initPlateauQuantik($decodedJson);
    }


}