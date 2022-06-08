<?php

namespace serverutils\command;


class CommandManager {
    
    public function __construct($commandMap) {
        $commandMap->register("/serverinfo", new ServerInfoCommand());
    } 
}