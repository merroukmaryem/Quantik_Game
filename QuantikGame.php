<?php
namespace Quantik2024;
use Quantik2024\ArrayPieceQuantik;
use Quantik2024\Player;


require_once 'AbstractGame.php';
require_once 'PlateauQuantik.php';


class QuantikGame extends AbstractGame {
    public PlateauQuantik $plateau;
    public ArrayPieceQuantik $piecesBlanches;
    public ArrayPieceQuantik $piecesNoires;
    public array $couleurPlayer;

    public function __construct(array $players) {
        $this->gameID = 0; // Initialiser l'ID du jeu à null car il sera généré plus tard
        $this->currentPlayer = 0; // Commencer avec le premier joueur
        $this->gameStatus = 'constructed'; // Définir l'état du jeu par défaut


        // Associer chaque joueur à une couleur
        $this->couleurPlayer = [];
        foreach ($players as $player) {
            $this->couleurPlayer[] = $player;
        }
    }

    public function __toString(): string
    {
        return 'Partie n°' . $this->gameID . ' lancée par joueur ' . $this->getPlayers()[0];
    }
    public function getJson(): string
    {
        $json = '{';
        $json .= '"plateau":' . $this->plateau->getJson();
        $json .= ',"piecesBlanches":' . $this->piecesBlanches->getJson();
        $json .= ',"piecesNoires":' . $this->piecesNoires->getJson();
        $json .= ',"currentPlayer":' . $this->currentPlayer;
        $json .= ',"gameID":' . $this->gameID;
        $json .= ',"gameStatus":' . json_encode($this->gameStatus);
        if (is_null($this->couleurPlayer[1]))
            $json .= ',"couleursPlayers":[' . $this->couleurPlayer[0]->getJson() . ']';
        else
            $json .= ',"couleursPlayers":[' . $this->couleurPlayer[0]->getJson() . ',' . $this->couleurPlayer[1]->getJson() . ']';
        return $json . '}';
    }
    public static function initQuantikGame(string $json): QuantikGame{
        $result = new QuantikGame( []);
        $tab = json_decode($json);
        $result->plateau = PlateauQuantik::initFromJson(json_encode($tab->plateau));
        $result->piecesBlanches = ArrayPieceQuantik::initFromJson(json_encode($tab->piecesBlanches));
        $result->piecesNoires = ArrayPieceQuantik::initFromJson(json_encode($tab->piecesNoires));
        $result->currentPlayer = $tab->currentPlayer;
        $result->gameID = $tab->gameID;
        $result->gameStatus = $tab->gameStatus;
        $result->couleurPlayer = [];
        foreach ($tab->couleursPlayers as $player)
            $result->couleurPlayer[] = Player::initPlayer(json_encode($player));
        return $result;
    }

    private function getPlayers():array
    {return  $this->couleurPlayer;
    }
}