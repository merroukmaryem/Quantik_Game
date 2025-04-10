<?php


namespace Quantik2024;
require_once 'Player.php';
require_once 'QuantikGame.php'; // Assurez-vous que QuantikGame.php est correctement inclus
require_once 'EntiteGameQuantik.php';
use PDO;
use PDOException;
use PDOStatement;
use Quantik2024\QuantikGame;


class PDOQuantik
{
    private static PDO $pdo;

    public static function initPDO(string $sgbd, string $host, string $db, string $user, string $password, string $nomTable = ''): void
    {
        switch ($sgbd) {
            case 'pgsql':
                self::$pdo = new PDO('pgsql:host=' . $host . ' dbname=' . $db . ' user=' . $user . ' password=' . $password);
                break;
            default:
                exit ("Type de sgbd non correct : $sgbd fourni, 'mysql' ou 'pgsql' attendu");
        }

        // pour récupérer aussi les exceptions provenant de PDOStatement
        self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /* requêtes Préparées pour l'entitePlayer */
    private static PDOStatement $createPlayer;
    private static PDOStatement $selectPlayerByName;

    /******** Gestion des requêtes relatives à Player *************/
    public static function createPlayer(string $name): Player
    {
        if (!isset(self::$createPlayer))
            self::$createPlayer = self::$pdo->prepare('INSERT INTO Player(name) VALUES (:name)');
        self::$createPlayer->bindValue(':name', $name, PDO::PARAM_STR);
        self::$createPlayer->execute();
        return self::selectPlayerByName($name);
    }

    public static function selectPlayerByName(string $name): ?Player
    {
        if (!isset(self::$selectPlayerByName))
            self::$selectPlayerByName = self::$pdo->prepare('SELECT * FROM Player WHERE name=:name');
        self::$selectPlayerByName->bindValue(':name', $name, PDO::PARAM_STR);
        self::$selectPlayerByName->execute();
        $player = self::$selectPlayerByName->fetchObject('Quantik2024\Player');
        return ($player) ? $player : null;
    }

    /* requêtes préparées pour l'entiteGameQuantik */
    private static PDOStatement $createGameQuantik;
    private static PDOStatement $saveGameQuantik;
    private static PDOStatement $addPlayerToGameQuantik;
    private static PDOStatement $selectGameQuantikById;
    private static PDOStatement $selectAllGameQuantik;
    private static PDOStatement $selectAllGameQuantikByPlayerName;
    private static PDOStatement $getLastGameIdForPlayer;

    /******** Gestion des requêtes relatives à QuantikGame *************/

    /**
     * initialisation et execution de $createGameQuantik la requête préparée pour enregistrer une nouvelle partie
     */

    public static function createGameQuantik(string $playerName, string $json): void
    {
        if (!isset(self::$createGameQuantik)) {
            self::$createGameQuantik = self::$pdo->prepare('INSERT INTO quantikgame(playerOne, gameStatus, json) VALUES (:playerId, :gameStatus, :json)');
        }

        $player = self::selectPlayerByName($playerName);
        $playerId = $player->getId();
        $gameStatus = 'constructed';

        self::$createGameQuantik->bindValue(':playerId', $playerId, PDO::PARAM_INT);
        self::$createGameQuantik->bindValue(':gameStatus', $gameStatus, PDO::PARAM_STR);
        self::$createGameQuantik->bindValue(':json', $json, PDO::PARAM_STR);

        self::$createGameQuantik->execute();
    }


    /**
     * initialisation et execution de $saveGameQuantik la requête préparée pour changer
     * l'état de la partie et sa représentation json
     */
    public static function saveGameQuantik(string $gameStatus, string $json, int $gameId): void
    {
        if (!isset(self::$saveGameQuantik)) {
            self::$saveGameQuantik = self::$pdo->prepare('UPDATE QuantikGame SET gameStatus = :gameStatus, json = :json WHERE gameId = :gameId');
        }

        self::$saveGameQuantik->bindValue(':gameStatus', $gameStatus, PDO::PARAM_STR);
        self::$saveGameQuantik->bindValue(':json', $json, PDO::PARAM_STR);
        self::$saveGameQuantik->bindValue(':gameId', $gameId, PDO::PARAM_INT);

        self::$saveGameQuantik->execute();
    }

    /**
     * initialisation et execution de $addPlayerToGameQuantik la requête préparée pour intégrer le second joueur
     */

    public static function addPlayerToGameQuantik(string $playerName, string $json, int $gameId): void
    {
        if (!isset(self::$addPlayerToGameQuantik)) {
            self::$addPlayerToGameQuantik = self::$pdo->prepare('UPDATE quantikgame SET playerTwo = :playerId,json= :json WHERE gameId = :gameId');
        }

        $player = self::selectPlayerByName($playerName);
        $playerId = $player->getId();

        self::$addPlayerToGameQuantik->bindValue(':playerId', $playerId, PDO::PARAM_INT);
        self::$addPlayerToGameQuantik->bindValue(':json', $json, PDO::PARAM_STR);
        self::$addPlayerToGameQuantik->bindValue(':gameId', $gameId, PDO::PARAM_INT);

        self::$addPlayerToGameQuantik->execute();
    }

    /**
     * initialisation et execution de $selectAllGameQuantikById la requête préparée pour récupérer
     * une instance de quantikGame en fonction de son identifiant
     */


    public static function getGameQuantikById(int $gameId): ?EntiteGameQuantik
    {
        if (!isset(self::$selectGameQuantikById)) {
            self::$selectGameQuantikById = self::$pdo->prepare('SELECT * FROM QuantikGame WHERE gameId = :gameId');
        }

        self::$selectGameQuantikById->bindValue(':gameId', $gameId, PDO::PARAM_INT);
        self::$selectGameQuantikById->execute();

        $gameData = self::$selectGameQuantikById->fetch(PDO::FETCH_ASSOC);

        if ($gameData) {
            $game = new EntiteGameQuantik();
            $game->setGameId($gameData['gameid']);
            $game->setPlayerOne($gameData['playerone']);
            $game->setPlayerTwo($gameData['playertwo']);
            $game->setGameStatus($gameData['gamestatus']);
            $game->setJson($gameData['json']);

            return $game;
        } else {
            return null;
        }
    }


    /**
     * initialisation et execution de $selectAllGameQuantik la requête préparée pour récupérer toutes
     * les instances de quantikGame
     */
    public static function getAllQuantikGame(): array
    {
        $games = [];

        if (!isset(self::$selectAllGameQuantik)) {
            self::$selectAllGameQuantik = self::$pdo->prepare('SELECT * FROM QuantikGame');
        }

        try {
            self::$selectAllGameQuantik->execute();

            $rows = self::$selectAllGameQuantik->fetchAll(PDO::FETCH_ASSOC);

            foreach ($rows as $row) {
                $game = new EntiteGameQuantik();
                $game->setGameId($row['gameid']);
                $game->setPlayerOne($row['playerone']);
                $game->setPlayerTwo($row['playertwo']);
                $game->setGameStatus($row['gamestatus']);
                $game->setJson($row['json']);

                $games[] = $game;
            }
        } catch (PDOException $e) {
            echo "Erreur lors de la récupération des parties : " . $e->getMessage();
        }

        return $games;
    }


    /**
     * initialisation et execution de $selectAllGameQuantikByPlayerName la requête préparée pour récupérer les instances
     * de quantikGame accessibles au joueur $playerName
     * ne pas oublier les parties "à un seul joueur"
     */
    public static function getAllGameQuantikByPlayerName(string $playerName): array
    {
        /* TODO */
        $games = [];

        if (!isset(self::$selectAllGameQuantikByPlayerName)) {
            self::$selectAllGameQuantikByPlayerName = self::$pdo->prepare('SELECT * FROM QuantikGame WHERE playerOne = (SELECT id FROM Player WHERE name = :playerName) OR playerTwo = (SELECT id FROM Player WHERE name = :playerName)');
        }

        try {
            self::$selectAllGameQuantikByPlayerName->bindValue(':playerName', $playerName, PDO::PARAM_STR);
            self::$selectAllGameQuantikByPlayerName->execute();

            $lignes = self::$selectAllGameQuantikByPlayerName->fetchAll(PDO::FETCH_ASSOC);

            foreach ($lignes as $ligne) {
                $game = new EntiteGameQuantik();
                $game->setGameId($ligne['gameid']);
                $game->setPlayerOne($ligne['playerone']);
                $game->setPlayerTwo($ligne['playertwo']);
                $game->setGameStatus($ligne['gamestatus']);
                $game->setJson($ligne['json']);

                $games[] = $game;
            }
        } catch (PDOException $e) {
            echo "Erreur lors de la récupération des parties du joueur : " . $e->getMessage();
        }

        return $games;
    }

    /**
     * initialisation et execution de la requête préparée pour récupérer
     * l'identifiant de la dernière partie ouverte par $playername
     */
    public static function getLastGameIdForPlayer(string $playerName): int
    {
        if (!isset(self::$getLastGameIdForPlayer)) {
            self::$getLastGameIdForPlayer = self::$pdo->prepare('SELECT MAX(gameId) AS lastGameId FROM QuantikGame WHERE playerOne IN (SELECT id FROM Player WHERE name = :playerName) OR playerTwo IN (SELECT id FROM Player WHERE name = :playerName)');
        }

        self::$getLastGameIdForPlayer->bindValue(':playerName', $playerName, PDO::PARAM_STR);
        self::$getLastGameIdForPlayer->execute();

        $lastGameId = self::$getLastGameIdForPlayer->fetch(PDO::FETCH_ASSOC)['lastGameId'];

        return $lastGameId !== null ? (int) $lastGameId : 0;
    }

}