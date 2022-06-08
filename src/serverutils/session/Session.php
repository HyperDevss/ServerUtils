<?php

namespace serverutils\session;

use serverutils\ServerUtils;
use serverutils\protocol\Packet;
use serverutils\protocol\ProtocolInfo;
use serverutils\protocol\ConnectedPing;
use serverutils\protocol\ConnectedPong;
use serverutils\protocol\ServerInfo;
use serverutils\task\PingPong;
use serverutils\session\SessionManager;
use serverutils\server\ServerManager;
use raklib\utils\InternetAddress;
use raklib\protocol\PacketSerializer;

class Session {  

    public const CONNECTED = 1;
    public const DISCONNECTED = 2;

    private $sessionManager;
    private $address;
    private $name;
    private $id;
    private $state = Session::DISCONNECTED;
    private $server;

    private $ping = 0;
    private $pingTask;
    private $pingTime = 0;

    private $packets;

    public function __construct(InternetAddress $address, string $serverName, int $serverId, SessionManager $sessionManager) {
        $this->address = $address;
        $this->name = $serverName;
        $this->id = $serverId;
        $this->sessionManager = $sessionManager;
        $this->registerPackets();
    }

    public function getName(): string {
        return $this->name;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getAddress(): InternetAddress {
        return $this->address;
    }

    public function getState(): int {
        return $this->state;
    }

    public function sendPacket(Packet $packet) {
        $this->getServer()->sendPacket($packet, $this->address);
    }

    public function getPing(): int {
        return $this->ping;
    }

    private function getServer(): ServerUtils {
        return $this->sessionManager->getServer();
    }

    public function getLogger() {
        return $this->getServer()->getLogger();
    }

    public function handle($buffer) {
        if (($packet = $this->getPacket($buffer)) === null) return; // ignorar

        if ($packet instanceof ConnectedPing || $packet instanceof ConnectedPong) {
            $this->handlePingPong($packet);
        } elseif ($packet instanceof ServerInfo) {
            $this->server->setPlayers($packet->currentPlayers);
            $this->server->setMaxPlayers($packet->maxPlayers);
            $this->server->setWorlds($packet->worlds);
        }
    }

    public function close() {
        $this->server->close();
    }

    public function update() {
        
    }

    public function handlePingPong(Packet $packet) {
        if ($packet instanceof ConnectedPing) {
            $this->ping = $packet->pingTime;
            $pingTime = ($this->pingTime !== null) ? microtime(true) - $this->pingTime : 0;
            $this->sendPacket(ConnectedPong::create($pingTime));
            $this->pingTime = microtime(true);

            if ($this->pingTask === null) $this->pingTask = new PingPong($this);
            $this->pingTask->restart();
        } else {
            $this->ping = $packet->pongTime;
            $pingTime = ($this->pingTime !== null) ? microtime(true) - $this->pingTime : 0;
            $this->sendPacket(ConnectedPing::create($pingTime));
            $this->pingTime = microtime(true);

            if ($this->pingTask === null) $this->pingTask = new PingPong($this);
            $this->pingTask->restart();
        }
    }

    public function startPingPong(): void {
        $this->sendPacket(ConnectedPing::create($this->pingTime));
        $this->pingTime = microtime(true);
    }

    public function getPacket($buffer) {
        if (!isset($this->packets[ord($buffer)])) return null;

        $packet = clone $this->packets[ord($buffer)];
        $out = new PacketSerializer($buffer);
        $packet->decode($out);
        return $packet;
    }

    public function setState(int $state) {
        $this->state = $state;
        if ($state = Session::CONNECTED) {
            $this->server = ServerManager::getInstance()->addServer($this);
        }
    }

    public function registerPackets(): void {
        $this->packets = [
            ProtocolInfo::CONNECTED_PING => new ConnectedPing(),
            ProtocolInfo::CONNECTED_PONG => new ConnectedPong(),
            ProtocolInfo::SERVERINFO => new ServerInfo()
        ];
    }
    
    public function __destruct() {
        $this->close();
    }
}