<?php

namespace practice\task;

use practice\kits\Kit;
use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat;
use pocketmine\world\Position;
use pocketmine\world\World;
use pocketmine\world\WorldException;
use practice\Loader;
use practice\MatchManager;
use practice\PracticePlayer;

class MatchTask extends Task
{
    /** @var int */
    private int $time = 903;
    /** @var PracticePlayer */
    private PracticePlayer $player1;
    /** @var PracticePlayer */
    private PracticePlayer $player2;
    /** @var Kit */
    private Kit $kit;
    /** @var World */
    private World $level;
    /** @var string */
    private string $winner = "None";
    /** @var string */
    private string $loser = "None";

    public function __construct(Loader $plugin, string $name, PracticePlayer $player1, PracticePlayer $player2, Kit $kit)
    {
        $world = $plugin->getServer()->getWorldManager()->getWorldByName($name);
        if ($world === null) {
            throw new WorldException("World does not exist");
        }

        $this->setHandler($plugin->getScheduler()->scheduleRepeatingTask($this, 20));
        $this->kit = $kit;
        $this->level = $world;
        $this->player1 = $player1;
        $this->player2 = $player2;
    }

    public function onRun(): void
    {
        foreach ($this->getPlayers() as $player) {
            if ($player->isOnline()) {
                $player->setScoreTag(floor($player->getHealth()) . TextFormat::RED . " ❤");
                if (!$player->isPlaying()) {
                    $this->loser = $player->getName();
                    $this->winner = $player->getName() !== $this->player1->getName() ? $this->player1->getName() : $this->player2->getName();
                    $this->onEnd(null);
                }
            } else {
                $this->onEnd($player);
            }
        }

        switch ($this->time) {
            case 902:
                $this->player1->teleport(new Position(15, 4, 40, $this->level));
                $this->player2->teleport(new Position(15, 4, 10, $this->level));
                break;
            case 901:
                foreach ($this->getPlayers() as $player) {
                    if ($player instanceof PracticePlayer) {
                        $player->getArmorInventory()->setContents($this->kit->getArmorItems());
                        $player->getInventory()->setContents($this->kit->getInventoryItems());
                    }
                }
                break;
            case 0:
                $this->onEnd(null);
                break;
        }

        $this->time--;
    }

    public function onEnd(?PracticePlayer $playerLeft): void
    {
        foreach ($this->getPlayers() as $online) {
            if (is_null($playerLeft) || $online->getName() !== $playerLeft->getName()) {
                $online->sendMessage(TextFormat::GRAY . "---------------");
                $online->sendMessage(TextFormat::GOLD . "Winner: " . TextFormat::WHITE . $this->winner);
                $online->sendMessage(TextFormat::YELLOW . "Loser: " . TextFormat::WHITE . $this->loser);
                $online->sendMessage(TextFormat::GRAY . "---------------");
                $online->giveLobbyItems();
                $online->teleport($online->getServer()->getWorldManager()->getDefaultWorld()->getSafeSpawn(), 0, 0);
            }
        }
        $this->cancel();
    }

    public function cancel(): void
    {
        $this->getHandler()->cancel();
        MatchManager::getInstance()->stopMatch($this->level->getFolderName());
    }

    /**
     * @return PracticePlayer[]
     */
    public function getPlayers(): array
    {
        return [$this->player1, $this->player2];
    }
}