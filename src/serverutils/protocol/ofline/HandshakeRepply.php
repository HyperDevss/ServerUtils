<?php

namespace serverutils\protocol\ofline;

use serverutils\protocol\ProtocolInfo;
use raklib\protocol\Packet;
use raklib\protocol\PacketSerializer;

class HandshakeRepply extends Packet {
    
    public static $ID = ProtocolInfo::HANDSHAKE_REPPLY;
    public $serverName;
    public $serverId;
    
    public static function create(string $serverName, int $serverId): HandshakeRepply {
        $packet = new Self();
        $packet->serverName = $serverName;
        $packet->serverId = $serverId;
        return $packet;
    }
    
    public function encodePayload(PacketSerializer $ini): void {
        $ini->putString($this->serverName);
        $ini->putInt($this->serverId);
    }
    
    public function decodePayload(PacketSerializer $out):  void {
        $this->serverName = $out->getString();
        $this->serverId = $out->getInt();
    }
}