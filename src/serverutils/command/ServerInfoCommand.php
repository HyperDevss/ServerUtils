<?php

namespace serverutils\command;

use serverUtils\server\ServerManager;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class ServerInfoCommand extends Command {
     
    public function __construct() {
        parent::__construct("serverinfo", "muestra información de tus servidores externos", "/serverinfo (server)", ["svinfo", "SVINFO", "SERVERINFO"]);
    }
    
    public function execute(CommandSender $sender, string $label, array $args) { 
        if (!isset($args[0])) {
            $sender->sendMessage("§cusa §4/" . $label . " (server)");
            return;
        }
        
        if (!ServerManager::getInstance()->isServer($args[0])) {
            $sender->sendMessage("§cno se encontro el servidor §4" . $args[0]);
            return;
        }
        
        $server = ServerManager::getInstance()->getServer($args[0]);
        $sender->sendMessage("§7--------------------");
        $sender->sendMessage("§ename§6: §a" . $server->getName());
        $sender->sendMessage("§eworlds§6: §a" . count($server->getWorlds()));
        $sender->sendMessage("§eplayers§6: §a" . count($server->getPlayers()));
        $sender->sendMessage("§emax players§6: §a" . $server->getMaxPlayers());
        $sender->sendMessage("§7--------------------");
    }
}