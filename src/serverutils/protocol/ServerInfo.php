<?php

namespace serverutils\protocol;

use serverutils\protocol\Packet;
use serverutils\protocol\ProtocolInfo;
use raklib\protocol\PacketSerializer;

class ServerInfo extends Packet {

    public static $ID = ProtocolInfo::SERVERINFO;
    
    public $mothd = "";
    public $currentPlayers;
    public $maxPlayers;
    public $worlds;

    public static function construct(string $mothd, int $currentPlayers, int $maxPlayers): ServerInfo {
        $packet = new Self();
        $packet->mothd = $mothd;
        $packet->currentPlayers = $currentPlayers;
        $packet->maxPlayers = $maxPlayers;
        $packet->worlds = $worlds;
        return $packet;
    }

    public function encodePayload(PacketSerializer $ini): void {
        $ini->putString($this->mothd);
        $ini->putShort($currentPlayers);
        $ini->putShort($maxPlayers);
        $this->putByte($worlds);
    }
    
    public function decodePayload(PacketSerializer $out): void {
        $this->mothd = $out->getString();
        $this->currentPlayers = $out->getShort();
        $this->maxPlayers = $out->getShort();
        $this->worlds = $out->getByte();
    }
}