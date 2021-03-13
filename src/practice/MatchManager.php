<?php

namespace practice;

use pocketmine\uuid\UUID;
use pocketmine\world\generator\Flat;
use practice\kits\Kit;
use pocketmine\block\Block;
use pocketmine\block\BlockLegacyIds;
use pocketmine\utils\SingletonTrait;
use practice\task\MatchTask;

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

    public function createMatch(PracticePlayer $player1, PracticePlayer $player2, Kit $kit)
    {
        $worldName = "arena-" . UUID::fromRandom()->toString();
        $player1->getInventory()->clearAll();
        $player2->getInventory()->clearAll();
        $this->plugin->getServer()->getWorldManager()->generateWorld($worldName, 0, Flat::class);
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

    public function getMatches(): array
    {
        return $this->matches;
    }

    public function setMatches(array $matches)
    {
        $this->matches = $matches;
    }

    public function addMatch(string $name, MatchTask $task)
    {
        $this->getMatches()[$name] = $task;
    }

    public function isMatch($name): bool
    {
        return isset($this->getMatches()[$name]);
    }

    public function removeMatch($name)
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