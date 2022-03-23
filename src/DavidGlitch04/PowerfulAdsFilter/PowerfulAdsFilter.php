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
    /** @var Config $adsString */
    private Config $adsString;
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
    public static function getLanguage(): ?Language{
        return self::$language;
    }

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
        $this->saveResource("AdsString.yml");
        $this->adsString = new Config($this->getDataFolder()."AdsString.yml", Config::YAML);
        $this->initLanguage(strval($this->config->get("language", "eng")), $this->languages);
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

    public function getFilterString(): mixed{
        return $this->adsString->get("filterstring", ["thevertie", "play", "xyz", 1, 2,3 ,4, 5 ,6 ,7, 8, 9, 0]);
    }

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
        ) or preg_match('([a-zA-Z0-9]+ *+[(\.|,)]+ *+[^\s]{2,}|\.[a-zA-Z0-9]+\.[^\s]{2,})', $ip)
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
        $alert = self::getLanguage()->translateString("notice.message");
        $colorize = TextFormat::colorize($prefix . $alert);
        $player->sendMessage($colorize);
    }

    public function handleMessage(string $msg): string {
        $adsstring = $this->getFilterString();
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
        /**
         * @phpstan-ignore-next-line
         * Parameter #3 $subject of function str_replace expects array|string, mixed given.
         */
        $replace = array_map($callback, (array)$array);
        $subject = strtolower($msg);
        $filteredMsg = str_replace((array)$search, $replace, $subject);
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