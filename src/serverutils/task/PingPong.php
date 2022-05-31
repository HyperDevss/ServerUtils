<?php

namespace serverutils\task;

use serverutils\Main;
use serverutils\session\Session;
use pocketmine\scheduler\Task;

class PingPong extends Task {
    
    private $cooldown = 3;
    private $session;
    
    public function __construct(Session $session) {
        $this->session = $session;
        Main::getInstance()->getScheduler()->scheduleRepeatingTask($this, 20);
    }
    
    public function onRun(): void {
        if ($this->cooldown > 0) {
            $this->cooldown--;
        } else {
            $this->session->close();
        }
    }
    
    public function restart() {
        $this->cooldown = 3;
    }
}