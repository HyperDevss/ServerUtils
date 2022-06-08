<?php

namespace serverutils\protocol;

use serverutils\protocol\Packet;
use serverutils\protocol\ProtocolInfo;
use raklib\protocol\PacketSerializer;

class ServerInfo extends Packet {

    public static $ID = ProtocolInfo::SERVERINFO;
    
    public $currentPlayers;
    public $maxPlayers;
    public $worlds;

    public static function create(array $currentPlayers, int $maxPlayers, array $worlds): ServerInfo {
        $packet = new Self();
        $packet->currentPlayers = $currentPlayers;
        $packet->maxPlayers = $maxPlayers;
        $packet->worlds = $worlds;
        return $packet;
    }

    public function encodePayload(PacketSerializer $ini): void {
        $ini->putString(implode(":", $this->currentPlayers));
        $ini->putShort($this->maxPlayers);
        $ini->putString(implode(":", $this->worlds));
    }
    
    public function decodePayload(PacketSerializer $out): void {
        $this->currentPlayers = (strlen(($players = $out->getString())) === 1) ? [] : explode(":", $players);
        $this->maxPlayers = $out->getShort();
        $this->worlds = explode(":", $out->getString());
    }
}