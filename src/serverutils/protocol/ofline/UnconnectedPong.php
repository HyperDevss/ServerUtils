<?php

namespace serverutils\protocol\ofline;

use serverutils\protocol\ProtocolInfo;
use serverutils\protocol\Packet;
use raklib\protocol\PacketSerializer;

class UnconnectedPong extends Packet {
    
    public static $ID = ProtocolInfo::UNCONNECTED_PONG;
    
    public $protocolVersion;
    
    public static function create(int $protocolVersion): UnconnectedPong {
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