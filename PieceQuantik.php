<?php
namespace Quantik2024;

class PieceQuantik {
    // Constantes définissant les formes et couleurs
    public const WHITE =0;
    public const BLACK =1;

    public const VOID =0;
    public const  CUBE=1;
    public const  CONE=2;
    public const CYLINDRE =3;
    public const SPHERE =4;
    protected int $forme;// Propriété représentant la forme de la pièce
    protected int $couleur;// Propriété représentant la couleur de la pièce

    // Constructeur privé pour restreindre l'instanciation de la classe
    private function __construct(int $forme,int $couleur)
    {
        $this->forme=$forme;
        $this->couleur=$couleur;

    }
    // Méthodes d'accès aux propriétés
    public function getForme() : int{
        return $this->forme;
    }
    public function getCouleur() : int{
        return $this->couleur;
    }
    // Méthode pour obtenir une représentation textuelle de l'objet
    public function __toString() : String{
        $laforme ="";
        $lacouleur="";
        // Conversion des valeurs de forme et couleur en chaînes lisibles
        switch($this->forme){
            case self::VOID : $laforme = "VO";
                break;
            case self::CUBE : $laforme = "CU";
                break;
            case self::CONE : $laforme = "CO";
                break;
            case self::CYLINDRE : $laforme = "CY";
                break;
            case self::SPHERE : $laforme = "SP";
                break;
        }
        switch($this->couleur){
            case self::WHITE : $lacouleur = "W";
                break;
            case self::BLACK : $lacouleur= "B";
                break;
        }

        return "( ".$laforme ." : ".$lacouleur." )";
    }

    // Méthodes statiques pour créer des instances spécifiques de la classe
    public static function initVoid() : PieceQuantik {
        $piece = new PieceQuantik(self::VOID,self::WHITE);
        RETURN $piece;
    }
    public static function initWhiteCube() : PieceQuantik {
        $piece = new PieceQuantik(self::CUBE,self::WHITE);
        RETURN $piece;
    }
    public static function initBlackCube() : PieceQuantik {
        $piece = new PieceQuantik(self::CUBE,self::BLACK);
        RETURN $piece;
    }
    public static function initWhiteCone() : PieceQuantik {
        $piece = new PieceQuantik(self::CONE,self::WHITE);
        RETURN $piece;
    }
    public static function initBlackCone() : PieceQuantik {
        $piece = new PieceQuantik(self::CONE,self::BLACK);
        RETURN $piece;
    }
    public static function initWhiteCylindre() : PieceQuantik {
        $piece = new PieceQuantik(self::CYLINDRE,self::WHITE);
        RETURN $piece;
    }

    public static function initBlackCylindre() : PieceQuantik {
        $piece = new PieceQuantik(self::CYLINDRE,self::BLACK);
        RETURN $piece;
    }
    public static function initWhiteSphere() : PieceQuantik {
        $piece = new PieceQuantik(self::SPHERE,self::WHITE);
        RETURN $piece;
    }
    public static function initBlackSphere() : PieceQuantik {
        $piece = new PieceQuantik(self::SPHERE,self::BLACK);
        RETURN $piece;
    }
    public function getJson(): string {
        return '{"forme":'. $this->forme . ',"couleur":'.$this->couleur. '}';
    }

    public static function initPieceQuantik(string|object $json): PieceQuantik {
        if (is_string($json)) {
            $props = json_decode($json, true);
            return new PieceQuantik($props['forme'], $props['couleur']);
        }
        else
            return new PieceQuantik($json->forme, $json->couleur);
    }


}