<?php

namespace serverutils\protocol;

use raklib\protocol\Packet as RaklibPacket;
use raklib\protocol\PacketSerializer;

class Packet extends RaklibPacket {
    
    public function encodePayload(PacketSerializer $ini): void {}
    public function decodePayload(PacketSerializer $out): void {}
}