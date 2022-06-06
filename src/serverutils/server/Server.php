<?php

namespace serverutils\server;

use serverutils\server\ServerManager;
use serverutils\session\Session;
use serverutils\protocol\Packet;
use serverutils\task\ServerNotify;

class Server {
    
    private $serverNotify;
    
    private $session;
    private $players = [];
    private $maxPlayers = 1;
    private $worlds = [];
    
    public function __construct(Session $session) {
        $this->session = $session; 
        $this->serverNotify = new ServerNotify($this);
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
    
    public function setWorlds(array $worlds) {
        $this->worlds = $worlds;
    }
    
    public function setMaxPlayers(int $maxPlayers) {
        $this->maxPlayers = $players;
    }
    
    public function setPlayers(array $players) {
        $this->players = $players;
    }
    
    public function close() {
        ServerManager::getInstance()->removeServer($this->session->getName());
    }
}