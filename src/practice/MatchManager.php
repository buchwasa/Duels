<?php

namespace practice;

use pocketmine\world\generator\Flat;
use pocketmine\world\WorldCreationOptions;
use practice\kits\Kit;
use pocketmine\block\Block;
use pocketmine\block\BlockLegacyIds;
use pocketmine\utils\SingletonTrait;
use practice\task\MatchTask;
use Ramsey\Uuid\Uuid;

class MatchManager
{
    use SingletonTrait;

    /** @var MatchTask[] */
    private array $matches = [];
    /** @var Loader */
    private Loader $plugin;

    public function __construct(Loader $plugin)
    {
        $this->plugin = $plugin;
        self::$instance = $this;
    }

    public function createMatch(PracticePlayer $player1, PracticePlayer $player2, Kit $kit): void
    {
        $worldName = "arena-" . Uuid::uuid4();
        $player1->getInventory()->clearAll();
        $player2->getInventory()->clearAll();
        $creationOptions = new WorldCreationOptions();
        $creationOptions->setGeneratorClass(Flat::class);
        $this->plugin->getServer()->getWorldManager()->generateWorld($worldName,$creationOptions);
        $this->addMatch($worldName, new MatchTask($this->plugin, $worldName, $player1, $player2, $kit));
        $player1->setPlaying(true);
        $player2->setPlaying(true);
    }

    public function canUseBlock(Block $block): bool
    {
        return $block->getId() === BlockLegacyIds::COBBLESTONE;
    }

    public function stopMatch(string $name)
    {
        $this->removeMatch($name);
        $level = $this->plugin->getServer()->getWorldManager()->getWorldByName($name);
        $this->plugin->getServer()->getWorldManager()->unloadWorld($level, true);
        $this->plugin->deleteDir($this->plugin->getServer()->getDataPath() . "worlds/$name");
    }

    /**
     * @return MatchTask[]
     */
    public function getMatches(): array
    {
        return $this->matches;
    }

    public function setMatches(array $matches): void
    {
        $this->matches = $matches;
    }

    public function addMatch(string $name, MatchTask $task): void
    {
        $this->getMatches()[$name] = $task;
    }

    public function isMatch($name): bool
    {
        return isset($this->getMatches()[$name]);
    }

    public function removeMatch($name): void
    {
        if ($this->isMatch($name)) {
            unset($this->matches[$name]);
        }
    }

    public function getMatch($name): ?MatchTask
    {
        return $this->isMatch($name) ? $this->getMatches()[$name] : null;
    }
}