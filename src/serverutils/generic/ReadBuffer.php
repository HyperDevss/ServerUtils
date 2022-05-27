<?php

namespace serverutils\generic;

use serverutils\Main;
use pocketmine\scheduler\Task;

class ReadBuffer extends Task {
    
    private $runClass;
    
    public function __construct($class) {
        $this->runClass = $class;
        Main::getInstance()->getScheduler()->scheduleRepeatingTask($this, 2);
    }
    
    public function onRun(): void {
        if (!$this->runClass->readBuffer()) $this->getHandler()->cancel();
    }
}

?>