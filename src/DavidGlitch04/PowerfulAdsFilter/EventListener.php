<?php

namespace DavidGlitch04\PowerfulAdsFilter;

use pocketmine\event\Listener;

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
}