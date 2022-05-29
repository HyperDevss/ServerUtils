<?php

namespace serverutils;

use serverutils\protocol\ofline\UnconnectedPing;
use serverutils\protocol\ofline\UnconnectedPong;
use serverutils\protocol\ofline\HandshakeRequest;
use serverutils\protocol\ofline\HandshakeRepply;
use raklib\protocol\Packet;
use raklib\protocol\PacketSerializer;
use raklib\utils\InternetAddress;

class OflineMessageHandler {

    private $server;
    private $pol = [];

    public function __construct(ServerUtils $server) {
        $this->server = $server;
        $this->registerPackets();
    }

    public function handle($buffer, InternetAddress $address) {
        if (($packet = $this->getPacket($buffer)) === null) return;
        
        if ($packet instanceof UnconnectedPing) {
            
        } elseif ($packet instanceof UnconnectedPong) {
            
        } elseif ($packet instanceof HandshakeRequest) {
            
        } elseif ($packet instanceof HandshakeRepply) {
            
        }
    }
    
    public function sendPacket(Packet $packet, InternetAddress $address) {
        $this->server->sendPacket($packet, $address);
    }

    public function getPacket($buffer) {
        if (!isset($this->packets[ord($buffer[0])])) return null;
        $packet = $this->packets[ord($buffer[0])];
        $ini = new PacketSerializer($buffer);
        $packet->decode($ini);
        
        return $packet;
    }

    public function registerPackets() {
        $this->packets = [
            ProtocolInfo::UNCONNECTED_PING => new UnconnectedPing(),
            ProtocolInfo::UNCONNECTED_PONG => new UnconnectedPong(),
            ProtocolInfo::HANDSHAKR_REQUEST => new HandshakeRequest(),
            ProtocolInfo::HANDSHAKR_REPPLY => new HandshakeRepply()
        ];
    }
}