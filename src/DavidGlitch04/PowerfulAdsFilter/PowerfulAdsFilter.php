<?php 

namespace DavidGlitch04\PowerfulAdsFilter;

use pocketmine\lang\Language;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;

class PowerfulAdsFilter extends PluginBase{
    /** @var Config $config */
    private Config $config;

    /**
     * @return void
     */
    protected function onEnable(): void{
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
        $this->saveDefaultConfig();
        $this->config = $this->getConfig();
    }

    /**
     * @return string
     */
    public function getPrefix(): string{
        return strval($this->config->get("prefix", "&c[&aPowerfulAdsFilter&c] "));
    }

    /**
     * @param string $ip
     * @return bool
     */
    public function isIP(string $ip): bool{
        /**
         * @phpstan-ignore-next-line
        */
        if(filter_var(
            $ip,
            FILTER_VALIDATE_IP
        )){
            return true;
        } else{
            return false;
        }
    }

    /**
     * @param Player $player
     * @return void
     */
    public function sendAlert(Player $player): void{
        $prefix = $this->getPrefix();
        $alert = "&cPlease don't send ads on this server!";
        $colorize = TextFormat::colorize($prefix . $alert);
        $player->sendMessage($colorize);
    }

    /**
     * @param Player $player
     * @return void
     */
    public function showAds(Player $player, string $msg): void{
        $search = [
            "%player",
            "%msg"
        ];
        $replace = [
            $player->getName(),
            $msg
        ];
        $subject = "%player > %msg";
        $this->getServer()->getLogger()->info(str_replace($search, $replace, strval($subject)));
    }
}