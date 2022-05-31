<?php

namespace serverutils\protocol;

use serverutils\protocol\ProtocolInfo;
use serverutils\protocol\Packet;
use raklib\protocol\PacketSerializer;

class ConnectedPong extends Packet {
    
    public static $ID = ProtocolInfo::CONNECTED_PONG;
    
    public $pongTime;
    
    public static function create($pongTime): ConnectedPong {
        $packet = new Self();
        $packet->pongTime = $pongTime;
        return $packet;
    }
    
    public function encodePayload(PacketSerializer $ini): void {
        $ini->putFloat($this->pongTime);
    }
    
    public function decodePayload(PacketSerializer $out): void {
        $this->pongTime = $out->getRoundedFloat(1); 
    }
}