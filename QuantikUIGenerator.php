<?php
namespace Quantik2024;

use Quantik2024\ArrayPieceQuantik;

require_once 'AbstractUIGenerator.php';

class QuantikUIGenerator extends AbstractUIGenerator
{
    protected static function getButtonClass(PieceQuantik $pq): string
    {
        if ($pq->getForme() == PieceQuantik::VOID)
            return "empty";
        $piece = $pq->__toString();
        return substr($piece, 2, 2) . substr($piece, 7, 1);
    }

    protected static function getDivPlateauQuantik(PlateauQuantik $plateau): string
    {
        $resultat = "<div  ><table  class='plateau'>";
        for ($j = 0; $j < $plateau::NBROWS; $j++) {
            $ligne = $plateau->getRow($j);
            $resultat .= "<tr>";
            for ($i = 0; $i < count($ligne); $i++) {
                // $item = $ligne->getPieceQuantik($i);
                $buttonClass = self::getButtonClass($ligne->getPieceQuantik($i));
                $item = "<button type='submit' name='active' class='$buttonClass' disabled >";
                $resultat .= "<td><div>";
                $resultat .= $item;
                $resultat .= "</div></td>";
            }
            $resultat = $resultat . "</tr>";
        }
        return $resultat . "</table></div>";
    }

    public static function getDivPiecesDisponibles(ArrayPieceQuantik $apq, int $pos = -1): string
    {
        $res = "<div class='pieces'>";

        for ($i = 0; $i < count($apq); $i++) {
            $buttonClass = self::getButtonClass($apq->getPieceQuantik($i));
            $res .= "<button type='submit' name='active' class='$buttonClass' disabled >";
            // $res .= $apq->getPieceQuantik($i);
            $res .= "</button>";
        }

        return $res . "</div>";
    }

    protected static function getFormSelectionPiece(ArrayPieceQuantik $apq): string
    {
        $resultat = "<div class='pieces'>";
        $resultat .= "<form action='traitFormQuantik.php' method='post'>";
        $resultat .= "<input type='hidden' name='action' value='choisirPiece' />";
        for ($i = 0; $i < count($apq); $i++) {
            $buttonClass = self::getButtonClass($apq->getPieceQuantik($i));
            $resultat .= "<button  type='submit' name='pos' class='$buttonClass' value='";
            $resultat .= $i;
            $resultat .= "'>";
            // $resultat .= $apq->getPieceQuantik($i);
            $resultat .= "</button>";
        }

        return $resultat . "</form> </div>";
    }

    protected static function getFormPlateauQuantik(PlateauQuantik $plateau, PieceQuantik $piece): string
    {
        $resultat = "<form action='traitFormQuantik.php' method='post' ><table  class='plateau'>";

        $actionQuantik = new ActionQuantik($plateau);
        $resultat .= "<input type='hidden' name='action' value='poserPiece' />";

        for ($j = 0; $j < $plateau::NBROWS; $j++) {
            $ligne = $plateau->getRow($j);
            $resultat .= "<tr>";
            for ($i = 0; $i < count($ligne); $i++) {
                $buttonClass = self::getButtonClass($ligne->getPieceQuantik($i));
                $resultat .= "<td>";
                if ($actionQuantik->isValidePose($j, $i, $piece)) {
                    $resultat .= "<button type='submit'  class=$buttonClass ' name='coord' value='";
                    $resultat .= 'l' . $j . 'c' . $i;
                    $resultat .= "'>";
                    //$resultat .=  $ligne->getPieceQuantik($i);
                    $resultat .= "</button>";

                } else {

                    $resultat .= "<button type='submit' class=$buttonClass name='coord' value='";
                    $resultat .= 'ligne' . $j . 'colonne' . $i;
                    $resultat .= "' disabled>";
                    $resultat .= "</button>";

                }
                $resultat .= "</td>";
            }
            $resultat .= "</tr>";
        }

        $resultat .= "</table></form>";
        return $resultat;

    }

    protected static function getFormBoutonAnnuler() : string {
        $bouton="<form action='traitFormQuantik.php' method='post' >";
        $bouton.='<button class="bouton-annuler" type="submit" value="annulerChoix" name="action" >Annuler</button>';
        $bouton.='</form>';
        return $bouton;
    }

    protected static function getDivMessageVictoire(int $couleur): string
    {
        $couleurVictoire = " ";
        switch ($couleur) {
            case 1:
                $couleurVictoire .= "Black";
                break;
            case 0:
                $couleurVictoire .= "White ";
                break;
        }
        $resultat = "";
        $resultat .= "<div class='victoire'>";
        $resultat .= '   <h3>VICTOIRE ' . $couleurVictoire . ' <h3> ';
        $resultat .= '</div> ';
        return $resultat;
    }

    protected static function getLienRecommencer(): string
    {
        $resultat = "<form action='traitFormQuantik.php' method='post'>";
        $resultat .= "<input type='hidden' name='action' value='recommencerPartie' />";
        $resultat .= "<a href='traitFormQuantik.php' onclick='this.parentNode.submit(); return false;'>Cliquer pour Recommencer</a>";
        $resultat .= "</form>";
        return $resultat;
    }


    public static function getPageSelectionPiece(QuantikGame $quantik, int $couleurActive): string
    {
        $pageSelection = QuantikUIGenerator::getDebutHTML("jeu Quantik");
        $pageSelection .= "<div class='lespieces' >";
        $plateau = $quantik->plateau;
        if ($couleurActive == PieceQuantik::WHITE) {
            $pageSelection .= self::getFormSelectionPiece($quantik->piecesBlanches);
            $pageSelection .= self::getDivPlateauQuantik($plateau);
            $pageSelection .= self::getDivPiecesDisponibles($quantik->piecesNoires);
        } else {
            $pageSelection .= self::getDivPiecesDisponibles($quantik->piecesBlanches);
            $pageSelection .= self::getDivPlateauQuantik($plateau);
            $pageSelection .= self::getFormSelectionPiece($quantik->piecesNoires);
        }

        $pageSelection .= "</div>";
        $pageSelection .= "<form action='traitFormQuantik.php' method='post'>
<button type='submit' name='action' value='retourAcceuil' class='acceuil'>Acceuil</button>
</form>";
        return $pageSelection . self::getFinHTML();
    }

    public static function getPagePosePiece(QuantikGame $quantik, int $couleurActive, int $posSelection): string
    {
        $pagePose = QuantikUIGenerator::getDebutHTML("jeu Quantik");
        $pagePose .= "<div class='lespieces'>";
        $plateau = $quantik->plateau;
        if ($couleurActive == PieceQuantik::WHITE) {
            $pagePose .= self::getDivPiecesDisponibles($quantik->piecesBlanches, $posSelection);
            $pagePose .= self::getFormPlateauQuantik($plateau, $quantik->piecesBlanches[$posSelection]);
            $pagePose .= self::getDivPiecesDisponibles($quantik->piecesNoires);
        } else {
            $pagePose .= self::getDivPiecesDisponibles($quantik->piecesBlanches);
            $pagePose .= self::getFormPlateauQuantik($plateau, $quantik->piecesNoires[$posSelection]);
            $pagePose .= self::getDivPiecesDisponibles($quantik->piecesNoires, $posSelection);
        }

        $pagePose .= "</div>";
        $pagePose .= self::getFormBoutonAnnuler();
        $pagePose .= "<form action='traitFormQuantik.php' method='post'>
<button type='submit' name='action' value='retourAcceuil' class='acceuil'>Acceuil</button>
</form>";

        return $pagePose . self::getFinHTML();
    }

    public static function getPageVictoire(QuantikGame $quantik, int $couleurActive): string
    {
        $pageVictoire = QuantikUIGenerator::getDebutHTML("jeu Quantik");
        $pageVictoire .= "<div class='lespieces' >";
        $plateau = $quantik->plateau;
        if ($couleurActive == PieceQuantik::WHITE) {
            $pageVictoire .= self::getDivPiecesDisponibles($quantik->piecesBlanches);
            $pageVictoire .= self::getDivPlateauQuantik($plateau);
            $pageVictoire .= self::getDivPiecesDisponibles($quantik->piecesNoires);
        } else {
            $pageVictoire .= self::getDivPiecesDisponibles($quantik->piecesNoires);
            $pageVictoire .= self::getDivPlateauQuantik($plateau);
            $pageVictoire .= self::getDivPiecesDisponibles($quantik->piecesBlanches);
        }

        $pageVictoire .= self::getDivMessageVictoire($couleurActive);
        $pageVictoire .= self::getLienRecommencer();
        $pageVictoire .= "</div>";
        $pageVictoire .= "<form action='traitFormQuantik.php' method='post' >
<button type='submit' name='action' value='retourAcceuil' class='acceuil'>Acceuil</button>
</form>";
        return $pageVictoire . self::getFinHTML();

    }

    public static function getPageConsultation(QuantikGame $quantik): string
    {
        $page = QuantikUIGenerator::getDebutHTML("jeu Quantik");
        $page .= "<div class='lespieces' >";
        $plateau = $quantik->plateau;

        $page .= self::getDivPiecesDisponibles($quantik->piecesBlanches);
        $page .= self::getDivPlateauQuantik($plateau);
        $page .= self::getDivPiecesDisponibles($quantik->piecesNoires);


        $page .= "</div>";
        $page .= "<form action='traitFormQuantik.php' method='post'>
<button type='submit' name='action' value='retourAcceuil' class='acceuil'>Acceuil</button>
</form>";
        return $page . self::getFinHTML();
    }
}