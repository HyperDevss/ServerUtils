<?php

namespace serverutils\server;

use serverutils\ServerUtils;
use serverutils\session\Session;
use pocketmine\utils\SingletonTrait;

class ServerManager {
    
    use SingletonTrait;
    
    private $serverUtils;
    private $servers = [];
    
    public function __construct(ServerUtils $server) {
        self::setInstance($this);
        $this->serverUtils = $server;
    }
    
    public function getServers(): array {
        return $this->servers;
    }
    
    public function getServer($name) {
        return $this->servers[$name];
    }
    
    public function addServer(Session $session) {
        return $this->servers[$session->getName()] = new Server($session);
    }
    
    public function isServer($name) {
        return isset($this->servers[$name]);
    }
    
    public function removeServer($name) {
        unset($this->servers[$name]);
    }
}