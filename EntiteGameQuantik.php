<?php
namespace Quantik2024;

class EntiteGameQuantik
{
    public int $gameid;
    public int $playerone;
    public ?int $playertwo = null;
    public string $gamestatus; // was = 'init';

    public ?string $json = '';

    public function getGameId(): ?int
    {
        return $this->gameid;
    }

    public function setGameId(int $gameId): void
    {
        $this->gameid = $gameId;
    }

    public function getPlayerOne(): int
    {
        return $this->playerone;
    }

    public function setPlayerOne(int $playerOne): void
    {
        $this->playerone = $playerOne;
    }

    public function getPlayerTwo(): ?int
    {
        return $this->playertwo;
    }

    public function setPlayerTwo(?int $playerTwo): void
    {
        $this->playertwo = $playerTwo;
    }

    public function getGameStatus(): string
    {
        return $this->gamestatus;
    }

    public function setGameStatus(string $gameStatus): void
    {
        $this->gamestatus = $gameStatus;
    }

    public function getJson(): ?string
    {
        return $this->json;
    }

    public function setJson(?string $json): void
    {
        $this->json = $json;
    }
}