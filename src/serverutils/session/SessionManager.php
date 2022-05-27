<?php

namespace serverutils\session;

use serverutils\ServerUtils;
use serverutils\session\Session;
use pocketmine\utils\SingletonTrait;
use raklib\utils\InternetAddress;

class SessionManager {
    
    use SingletonTrait;
    
    private $server;
    private $sessions = [];
    
    public function __construct(ServerUtils $server) {
        $this->server = $server;
    }
    
    public function getSessions(): array {
        return $this->sessions;
    }
    
    public function isSession(InternetAddress $address): bool {
        return isset($this->sessions[$address->toString()]);
    }
    
    public function getSession(InternetAddress $address): Session {
        return $this->sessions[$address->toString()];
    }
    
    public function createSession(InternetAddress $address) {
        return $this->sessions[$address->toString()] = new Session($address, $this);
    }
    
    public function closeSession(InternetAddress $address) {
        $this->sessions[$address->toString()]->close();
        unset($this->sessions[$address->toString()]);
    }
    
    public function update(): void {
        if (count($this->sessions) === 0) return;
        
        foreach ($this->sessions as $session) {
            $session->update();
        }
    }
    
    public function close(): void {
        if (count($this->sessions) === 0) return;
        
        foreach ($this->sessions as $session) {
            $session->close();
        }
    }
}