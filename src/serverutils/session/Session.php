<?php

namespace core\session;

use serverutils\session\SessionManager;
use raklib\utils\InternetAddress;

class Session {
    
    private $sessionManager;
    private $address;
    
    public function __construct() {
        $this->sessionManager = $sessionManager;
        $this->address = $this->address;
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
