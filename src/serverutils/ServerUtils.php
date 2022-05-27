<?php

namespace serverutils;

use serverutils\Main;
use serverutils\generic\ReadBuffer;
use serverutils\session\SessionManager;
use pocketmine\utils\SingletonTrait;
use raklib\utils\InternetAddress;
use raklib\generic\Socket;

class ServerUtils {
    
    use SingletonTrait;
    
    private $owner;
    private $socket;
    private $address;
    private $sessionManager;
    
    public function __construct(Main $main) {
        $this->owner = $main;
        $this->address = new InternetAddress("0.0.0.0", 19135, 4);
        $this->socket = new Socket($this->address);
        $this->sessionManager = new SessionManager($this);
        new ReadBuffer($this);
    }
    
    public function getAddress(): InternetAddress {
        return $this->address;
    }
    
    public function getLogger() {
        return $this->owner->getLogger();
    }
    
    public function getSessionManager(): SessionManager {
        return $this->sessionManager;
    }
    
    public function sendPacket($packet, InternetAddress $address) {
        $this->socket->writePacket($packet, $address->getIp(), $address->getPort());
    }
    
    public function readBuffer(): bool {
        $buffer = $this->socket->readPacket($ip, $port);
        return true;
    }
}