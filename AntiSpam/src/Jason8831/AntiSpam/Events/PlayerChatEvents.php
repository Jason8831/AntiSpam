<?php

namespace Jason8831\AntiSpam\Events;

use Jason8831\AntiSpam\Main;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\utils\Config;

class PlayerChatEvents implements Listener
{
    private $antispam = [];
    private $cooldown = [];

    /**
     * @param PlayerChatEvent $event
     * @return void
     */
    public function tchat(PlayerChatEvent $event): void
    {

        $config = new Config(Main::getInstance()->getDataFolder() . "config.yml", Config::YAML);

        $player = $event->getPlayer();
        $msg = $event->getMessage();
        $name = $player->getName();

        //antispam
        if(isset($this->antispam[strtolower($player->getName())])){
            $msgsave = $this->antispam[strtolower($player->getName())];
            if($msgsave === $msg){
                $event->cancel();
            }
        }
        $this->antispam[strtolower($player->getName())] = $msg;

        //cooldown
        if(isset($this->cooldown[strtolower($player->getName())])){
            $time = $this->cooldown[strtolower($player->getName())];
            if($time > time()){
                $event->cancel();
                $player->sendMessage($config->get("AntiSpamMessage"));
            }
        }
        $this->cooldown[strtolower($player->getName())] = time() +2;

    }
}