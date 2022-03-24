<?php 

namespace DavidGlitch04\PowerfulAdsFilter;

use pocketmine\lang\Language;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;
use pocketmine\utils\TextFormat;
use function is_dir;
use function mkdir;
use function is_file;
use function strval;
use function str_replace;
use function strtolower;
/**
 * Class PowerfulAdsFilter
 * @package DavidGlitch04\PowerfulAdsFilter
 */
class PowerfulAdsFilter extends PluginBase{
    use SingletonTrait;
    /** @var Config $config */
    private Config $config;
    /** @var Language $language */
    private static Language $language;
    /** @var array|string[] $languages */
    private array $languages = [
        "eng",
        "cn",
        "vie"
    ];

    /**
     * @return Language
     */
    public static function getLanguage(): Language{
        return self::$language;
    }

    /**
     * @return void
     */
    protected function onLoad(): void
    {
        self::setInstance($this);
    }

    /**
     * @return void
     */
    protected function onEnable(): void{
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
        $this->saveDefaultConfig();
        $this->config = $this->getConfig();
        $this->initLanguage(strval($this->config->get("language", "eng")), $this->languages);
        //var_dump($this->handleMessage("Test play.estellavn.xyz"));
    }

    /**
     * @param string $lang
     * @param string[] $languageFiles
     */
    public function initLanguage(string $lang, array $languageFiles): void {
        $path = $this->getDataFolder() . "languages/";
        if (!is_dir($path)) {
            @mkdir($path);
        }
        foreach ($languageFiles as $file) {
            if (!is_file($path . $file . ".ini")) {
                $this->saveResource("languages/" . $file . ".ini");
            }
        }
        self::$language = new Language($lang, $path);
    }

    /**
     * @return string
     */
    public function getPrefix(): string{
        return strval($this->config->get("prefix", "&c[&aPowerfulAdsFilter&c] "));
    }

    /**
     * @return string
     */
    public function getCharacterReplaced(): string {
        return strval($this->config->get("characterReplaced", "*"));
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
        ) or preg_match('/\d+\.\d+\.\d+\.\d+/', $ip)
        ){
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
        $alert = PowerfulAdsFilter::getLanguage()->translateString("notice.message");
        $colorize = TextFormat::colorize($prefix . $alert);
        $player->sendMessage($colorize);
    }
    /**
     * @param string $msg
     * @return string
     */
    public function handleMessage(string $msg): string {
        $adsstring = ["/\d+\.\d+\.\d+\.\d+/", "/(([\w.-]*)\.([\w]*))/"];
        $callback = function (string $adsstring): string {
            $character = $this->getCharacterReplaced();
            $search = $adsstring;
            $replace = str_repeat(strval($character), mb_strlen($adsstring, "utf8"));
            $subject = $adsstring;
            $adsstring = str_replace($search, $replace, $subject);
            return $adsstring;
        };
        $array = $adsstring;
        $search = $adsstring;
        $replace = array_map($callback, $array);
        $subject = strtolower($msg);
        $filteredMsg = preg_replace($search, $replace, $subject);
        /** @var string $filteredMsg */
        return $filteredMsg;
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
        $this->getLogger()->info(str_replace($search, $replace, strval($subject)));
    }
}