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
    public const DISCONNECTED = 0x05;
    public const CONNECTED_PING = 0x06;
    public const CONNECTED_PONG = 0x07;
    public const SERVERINFO = 0x08;
}