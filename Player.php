<?php
namespace Quantik2024;

class Player
{
    public string $name;
    public int $id;

    public function getName(): string
    {
        return $this->name;
    }
    public function setName(string $name): void
    {
        $this->name = $name;
    }
    public function getId(): int
    {
        return $this->id;
    }
    public function setId(int $id): void
    {
        $this->id = $id;
    }
    public function __toString(): string
    {
        return '('.$this->id.')'.$this->name;
    }
    public function getJson():string {
        return '{"name":"'.$this->name.'","id":'.$this->id.'}';
    }
    public static function initPlayer(string $json): Player
    {
        $decodedJson = json_decode($json);
        $player = new Player();
        $player->setName($decodedJson->name);
        $player->setId($decodedJson->id);
        return $player;
    }

}