<?php

namespace serverutils;

use serverutils\Main;
use serverutils\task\ServerNotify;
use serverutils\server\ServerManager;
use serverutils\protocol\ofline\UnconnectedPing;
use serverutils\OflineMessageHandler;
use serverutils\generic\ReadBuffer;
use serverutils\session\SessionManager;
use serverutils\session\Session;
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
    private $serverManager;
    private $oflineHandler;

    private $serverName = "SkyWars-1";
    private $serverId = 1;

    public function __construct(Main $main) {
        self::setInstance($this);
        $this->owner = $main;
        $this->address = new InternetAddress("0.0.0.0", 19135, 4);
        $this->socket = new Socket($this->address);
        $this->sessionManager = new SessionManager($this);
        $this->oflineHandler = new OflineMessageHandler($this);
        $this->serverManager = new ServerManager($this);
        new ServerNotify($this);
        
        new ReadBuffer($this);

        // test enviar packet
        $this->sendPacket(UnconnectedPing::create(1), $this->address);
    }

    public function getAddress(): InternetAddress {
        return $this->address;
    }

    public function getLogger() {
        return $this->owner->getLogger();
    }
    
    public function getServerManager(): ServerManager {
        return $this->serverManager;
    }

    public function getSessionManager(): SessionManager {
        return $this->sessionManager;
    }
    
    public function broadcastPacket(Packet $packet) {
        if (count($this->sessionManager->getSessions()) === 0) return;
        foreach ($this->serverManager->getServers() as $server) {
            $this->sendPacket($packet, $server->getAddress());
        }
        
    }
    
    public function broadcastMessage($message) {
        
    }

    public function sendPacket(Packet $packet, InternetAddress $address) {
        $out = new PacketSerializer();
        $packet->encode($out);
        $this->socket->writePacket($out->getBuffer(), $address->getIp(), $address->getPort());
    }

    public function readBuffer(): bool {
        $buffer = $this->socket->readPacket($ip, $port);
        if ($buffer === null) return true;
        $address = new InternetAddress($ip, $port, 4);

        //$this->getLogger()->info("§epacket§6: " . bin2hex($buffer[0]) . " §eby client§6: " . $ip . ":" . $port);
        
        if (ord($buffer[0]) <= 4) {
            $this->oflineHandler->handle($buffer, $address);
            return true;
        }
        
        if ($this->sessionManager->isSession($address)) {
            if (($session = $this->sessionManager->getSession($address))->getState() === Session::CONNECTED) $session->handle($buffer);
            return true;
        }


        return true;
    }

    public function getName(): string {
        return $this->serverName;
    }

    public function getId(): int {
        return $this->serverId;
    }
}