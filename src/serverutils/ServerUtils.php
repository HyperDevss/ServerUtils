<?php

namespace serverutils;

use serverutils\Main;
use serverutils\protocol\ofline\UnconnectedPing;
use serverutils\OflineMessageHandler;
use serverutils\generic\ReadBuffer;
use serverutils\session\SessionManager;
use pocketmine\utils\SingletonTrait;
use raklib\utils\InternetAddress;
use raklib\generic\Socket;
use raklib\protocol\Packet;
use raklib\protocol\PacketSerializer;


class ServerUtils {
    
    use SingletonTrait;
    
    private $owner;
    private $socket;
    private $address;
    private $sessionManager;
    private $oflineHandler;
    
    public function __construct(Main $main) {
        $this->owner = $main;
        $this->address = new InternetAddress("0.0.0.0", 19135, 4);
        $this->socket = new Socket($this->address);
        $this->sessionManager = new SessionManager($this);
        $this->oflineHandler = new OflineMessageHandler($this);
        new ReadBuffer($this);
        
        // test enviar packet
        $this->sendPacket(UnconnectedPing::create("SkyWars-1", 1838378), $this->address);
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
    
    public function sendPacket(Packet $packet, InternetAddress $address) {
        $out = new PacketSerializer();
        $packet->encode($out);
        $this->socket->writePacket($out->getBuffer(), $address->getIp(), $address->getPort());
    }
    
    public function readBuffer(): bool {
        $buffer = $this->socket->readPacket($ip, $port);
        if ($buffer === null) return true;
        
        //test 
        $this->getLogger()->info("§epacket§6: " . bin2hex(ord($buffer[0])) . " §eby client§6: " . $ip . ":" . $port);
        
        return true;
    }
}