<?php

namespace serverutils\protocol\ofline;

use serverutils\protocol\ProtocolInfo;
use raklib\protocol\Packet;
use raklib\protocol\PacketSerializer;

class HandshakeRequest extends Packet {
    
    public static $ID = ProtocolInfo::HANDSHAKE_REQUEST;
    public $protocolVersion;
    
    public static function create(int $protocolVersion): Hands {
        $packet = new Self();
        $packet->protocolVersion = $protocolVersion;
        return $packet;
    }
    
    public function encodePayload(PacketSerializer $ini): void {
        $ini->putByte($this->protocolVersion);
    }
    
    public function decodePayload(PacketSerializer $out): void {
        $this->protocolVersion = $out->getByte(); 
    }
}