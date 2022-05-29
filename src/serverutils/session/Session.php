<?php

namespace serverutils\session;

use serverutils\session\SessionManager;
use raklib\utils\InternetAddress;

class Session {
    
    private $sessionManager;
    private $address;
    private $name;
    private $id;
    
    public function __construct(InternetAddress $address, string $serverName, int $serverId, SessionManager $sessionManager) {
        $this->address = $this->address;
        $this->name = $serverName;
        $this->id = $serverId;
        $this->sessionManager = $sessionManager;
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
    
    public function handle($buffer) {
        
    }
    
    public function close() {
        
    }
    
    public function update() {
        
    }
}
