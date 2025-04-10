<?php


namespace Quantik2024;
use ArrayAccess;
use Countable;
use Quantik2024\PieceQuantik;


require_once 'PieceQuantik.php';
class ArrayPieceQuantik implements ArrayAccess ,Countable
{
    protected array  $PiecesQuantiks;// Tableau pour stocker les pièces

    public function __construct()
    {
        $this->PiecesQuantiks = array();// Initialisation du tableau

    }

    public function __toString(): string
    {
        $affichage = "";
        for ($i = 0; $i < count($this->PiecesQuantiks); $i++) {
            $affichage .= "piece " . $i . " : " . $this->PiecesQuantiks[$i] . '\n';
        }
        return $affichage;
    }

    // Méthode pour récupérer une pièce à une position donnée
    public function getPieceQuantik(int $pos): PieceQuantik
    {
        return $this->offsetGet($pos);
    }
    // Méthode pour définir une pièce à une position donnée
    public function setPieceQuantik(int $pos, PieceQuantik $piece): void
    {
        $this->offsetSet($pos, $piece);
    }
// Méthode pour ajouter une pièce au tableau
    public function addPieceQuantik(PieceQuantik $piece): void
    {
        $this->PiecesQuantiks[] = $piece;

    }
// Méthode pour supprimer une pièce à une position donnée
    public function removePieceQuantik(int $pos): void
    {
        if($this->offsetExists($pos)){
            $this->offsetUnset($pos);
            // Réindexer les clés après la suppression
            $this->PiecesQuantiks = array_values($this->PiecesQuantiks);
        }
    }

// Méthode statique pour initialiser un ensemble de pièces noires
    public static function initPiecesNoires(): ArrayPieceQuantik
    {
        $arrayPieceQuantik = new ArrayPieceQuantik(); // Crée une instance de ArrayPieceQuantik
        for ($i = 0; $i < 2; $i++) {
            // Chaque joueur a 8 cartes, deux pour chaque forme
            $arrayPieceQuantik->addPieceQuantik(PieceQuantik::initBlackCube());
            $arrayPieceQuantik->addPieceQuantik(PieceQuantik::initBlackCone());
            $arrayPieceQuantik->addPieceQuantik(PieceQuantik::initBlackCylindre());
            $arrayPieceQuantik->addPieceQuantik(PieceQuantik::initBlackSphere());
        }
        return $arrayPieceQuantik;
    }
    // Méthode statique pour initialiser un ensemble de pièces blanches
    public static function initPiecesBlanches(): ArrayPieceQuantik
    {
        $arrayPieceQuantik = new ArrayPieceQuantik(); // Crée une instance de ArrayPieceQuantik
        for ($i = 0; $i < 2; $i++) {
            // Chaque joueur a 8 cartes, deux pour chaque forme
            $arrayPieceQuantik->addPieceQuantik(PieceQuantik::initWhiteCube());
            $arrayPieceQuantik->addPieceQuantik(PieceQuantik::initWhiteCone());
            $arrayPieceQuantik->addPieceQuantik(PieceQuantik::initWhiteCylindre());
            $arrayPieceQuantik->addPieceQuantik(PieceQuantik::initWhiteSphere());
        }
        return $arrayPieceQuantik;
    }
    // Implémentation des méthodes de l'interface ArrayAccess
    public function offsetExists($offset): bool {
        return isset($this->PiecesQuantiks[$offset]);

    }




    public function offsetSet($offset, $value): void {
        if (is_null($offset)) {
            $this->PiecesQuantiks[] = $value;
        } else {
            $this->PiecesQuantiks[$offset] = $value;
        }
    }

    public function offsetUnset($offset): void {
        unset($this->PiecesQuantiks[$offset]);
    }
    // Implémentation de la méthode de l'interface Countable

    public function count():int

    {

        return sizeof($this->PiecesQuantiks);

    }


    public function offsetGet($offset): mixed {
        return isset($this->PiecesQuantiks[$offset]) ? $this->PiecesQuantiks[$offset] : PieceQuantik::initVoid();
    }

    public function getJson(): string
    {
        $json = "[";
        $jTab = [];
        foreach ($this->PiecesQuantiks as $p)
            $jTab[] = $p->getJson();
        $json .= implode(',', $jTab);
        return $json . ']';
    }

    public static function initArrayPieceQuantik(string|array $json): ArrayPieceQuantik
    {
        $apq = new ArrayPieceQuantik();
        if (is_string($json)) {
            $json = json_decode($json);
        }
        foreach ($json as $j)
            $apq[] = PieceQuantik::initPieceQuantik($j);
        return $apq;
    }
    public static function initFromJson(string $json): ArrayPieceQuantik
    {
        $decodedJson = json_decode($json);
        return self::initArrayPieceQuantik($decodedJson);
    }

}