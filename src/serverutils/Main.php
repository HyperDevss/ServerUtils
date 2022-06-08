<?php

namespace serverutils;

use serverutils\ServerUtils;
use serverutils\command\CommandManager;
use pocketmine\utils\SingletonTrait;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase {

    use SingletonTrait;

    public function onEnable(): void {
        self::setInstance($this);
        
        new ServerUtils($this);
        new CommandManager($this->getServer()->getCommandMap());
    }
}