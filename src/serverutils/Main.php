<?php

namespace serverutils;

use serverutils\ServerUtils;
use pocketmine\utils\SingletonTrait;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase {

    use SingletonTrait;

    public function onEnable(): void {
        self::setInstance($this);
        new ServerUtils($this);
    }
}