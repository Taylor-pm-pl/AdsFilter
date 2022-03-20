<?php 

namespace DavidGlitch04\PowerfulAdsFilter;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class PowerfulAdsFilter extends PluginBase{

    protected function onEnable(): void{
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
    }
}