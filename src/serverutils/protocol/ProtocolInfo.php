<?php

namespace serverutils\protocol;

class ProtocolInfo {
    public const VERSION = 1;
    
    // Ofline Packets
    public const UNCONNECTED_PING = 0x01;
    public const UNCONNECTED_PONG = 0x02;
    public const HANDSHAKE_REQUEST = 0x03;
    public const HANDSHAKE_REPPLY = 0x04;

    // Onlibe Packets
}