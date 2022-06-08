<?php

namespace serverutils\task;

use serverutils\Main;
use serverutils\ServerUtils;
use serverutils\protocol\ServerInfo;
use pocketmine\Server;
use pocketmine\scheduler\Task;

class ServerNotify extends Task {

    private $server;

    public function __construct(ServerUtils $server) {
        $this->server = $server;
        Main::getInstance()->getScheduler()->scheduleRepeatingTask($this, 15);
    }

    public function stop() {
        $this->getHandler()->cancel();
    }

    public function onRun(): void {
        $worlds = [];
        $players = [];
        
        foreach (Server::getInstance()->getWorldManager()->getWorlds() as $world) {
            $worlds[] = $world->getFolderName();
        }
        
        if (count(($allPlayers = Server::getInstance()->getOnlinePlayers())) === 0) {
            $players = ["0"];
        } else {
            foreach ($allPlayers as $player) {
                $players[] = $player->gerName();
            }
        }
        
        $this->server->broadcastPacket(ServerInfo::create(
            $players,
            Server::getInstance()->getMaxPlayers(),
            $worlds
        ));
    }
}