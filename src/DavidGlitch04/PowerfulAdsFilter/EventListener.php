<?php

namespace DavidGlitch04\PowerfulAdsFilter;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;

class EventListener implements Listener{
    /** @var PowerfulAdsFilter $plugin */
    public PowerfulAdsFilter $plugin;

    /**
     * EventListener constructor.
     * @param PowerfulAdsFilter $plugin
     */
    public function __construct(PowerfulAdsFilter $plugin)
    {
        $this->plugin = $plugin;
    }

    /**
     * @param PlayerChatEvent $event
     */
    public function onPlayerChat(PlayerChatEvent $event): void{
        $player = $event->getPlayer();
        $msg = $event->getMessage();
        if(!$player->hasPermission("powerfuladsfiler.bypass")){
            if($this->plugin->isIP($msg)){
                $this->plugin->sendAlert($player);
                $this->plugin->showAds($player, $msg);
                $filteredMsg = $this->plugin->handleMessage($msg);
                $event->setMessage($filteredMsg);
            }
        }
    }
}