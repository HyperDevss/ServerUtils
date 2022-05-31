<?php

namespace serverutils\protocol;

use serverutils\protocol\ProtocolInfo;
use serverutils\protocol\Packet;
use raklib\protocol\PacketSerializer;

class ConnectedPing extends Packet {
    
    public static $ID = ProtocolInfo::CONNECTED_PING;
    
    public $pingTime;
    
    public static function create($pingTime): ConnectedPing {
        $packet = new Self();
        $packet->pingTime = $pingTime;
        return $packet;
    }
    
    public function encodePayload(PacketSerializer $ini): void {
        $ini->putFloat($this->pingTime);
    }
    
    public function decodePayload(PacketSerializer $out): void {
        $this->pingTime = $out->getRoundedFloat(1); 
    }
}