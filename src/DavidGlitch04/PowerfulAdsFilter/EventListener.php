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

    public function onPlayerChat(PlayerChatEvent $event){
        $player = $event->getPlayer();
        //TODO
    }
}