<?php

namespace serverutils;

use serverutils\protocol\ProtocolInfo;
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
            if (!$this->verifyProtocol($packet->protocolVersion)) {
                //$this->sendPacket(new IncompatibleProtocol(), $address);
                return;
            }

            $this->sendPacket(UnConnectedPong::create(ProtocolInfo::VERSION), $address);
        } elseif ($packet instanceof UnconnectedPong) {
            if (!$this->verifyProtocol($packet->protocolVersion)) {
                //$this->sendPacket(new IncompatibleProtocol(), $address);
                return;
            }

            $this->sendPacket(HandshakeRequest::create($this->server->getName(), $this->server->getId()), $address);
        } elseif ($packet instanceof HandshakeRequest) {
            $this->sendPacket(HandshakeRepply::create($this->server->getName(), $this->server->getId()), $address);
            if (!$this->server->getSessionManager()->isSession($address)) {
                $this->server->getSessionManager()->createSession($address, $packet->serverName, $packet->serverId);
            }
        } elseif ($packet instanceof HandshakeRepply) {

            if (!$this->server->getSessionManager()->isSession($address)) {
                $this->server->getSessionManager()->createSession($address, $packet->serverName, $packet->serverId);
            }
            // Yupi, conexión establecida:D
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
            ProtocolInfo::HANDSHAKE_REQUEST => new HandshakeRequest(),
            ProtocolInfo::HANDSHAKE_REPPLY => new HandshakeRepply()
        ];
    }

    public function verifyProtocol(int $version): bool {
        return $version === ProtocolInfo::VERSION;
    }
}