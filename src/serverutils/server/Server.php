<?php

namespace serverutils\server;

use serverutils\ServerUtils;
use serverutils\server\ServerManager;
use serverutils\session\Session;
use serverutils\protocol\Packet;
use raklib\utils\InternetAddress;

class Server {
    
    private $serverUtils;
    
    private $session;
    private $players = [];
    private $maxPlayers = 45;
    private $worlds = [];
    
    public function __construct(Session $session) {
        $this->serverUtils = ServerUtils::getInstance();
        $this->session = $session; 
    }
    
    public function getName(): string {
        return $this->session->getName();
    }
    
    public function sendDataPacket(Packet $packet) {
        $this->session->sendPacket($packet);
    }
    
    public function getPlayers(): array {
        return $this->players;
    }
    
    public function getMaxPlayers(): int {
        return $this->maxPlayers;
    }
    
    public function getWorlds(): array {
        return $this->worlds;
    }
    
    public function getAddress(): InternetAddress {
        return $this->session->getAddress();
    }
    
    public function broadcastDataPacket(Packet $packet) {
        $this->serverUtils->broadcastPacket($packet);
    }
    
    public function broadcastMessage($message) {
        $this->serverUtils->broadcastMessage($message);
    }
    
    public function setWorlds(array $worlds) {
        $this->worlds = $worlds;
    }
    
    public function setMaxPlayers(int $maxPlayers) {
        $this->maxPlayers = $maxPlayers;
    }
    
    public function setPlayers(array $players) {
        $this->players = $players;
    }
    
    public function close() {
        ServerManager::getInstance()->removeServer($this->session->getName());
    }
}