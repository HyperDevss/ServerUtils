<?php

namespace serverutils\task;

use serverutils\Main;
use serverutils\server\Server;
use pocketmine\scheduler\Task;

class ServerNotify extends Task {
    
    private $server;
    
    public function __construct(Server $server) {
        $this->server = $server;
        Main::getInstance()->getScheduler()->scheduleRepeatingTask($this, 15);
    }
    
    public function stop() {
        $this->getHandler()->cancel();
    }
    
    public function onRun(): void {
        
    }
}