<?php

namespace practice\task;

use muqsit\chunkloader\ChunkRegion;
use practice\kits\Kit;
use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat as TF;
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
    /** @var PracticePlayer|null */
    private ?PracticePlayer $winner = null;
    /** @var PracticePlayer|null */
    private ?PracticePlayer $loser = null;

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
                $player->setScoreTag(floor($player->getHealth() / 2) . TF::RED . " ❤");
                if (!$player->isPlaying()) {
                    $this->loser = $player;
                    $this->winner = $player->getName() !== $this->player1->getName() ? $this->player1 : $this->player2;
                    $this->onEnd();
                }
            } else {
                $this->loser = $player;
                $this->winner = $player->getName() !== $this->player1->getName() ? $this->player1 : $this->player2;
                $this->onEnd($player);
            }
        }

        switch ($this->time) {
            case 902:
                $this->level->orderChunkPopulation(15 >> 4, 40 >> 4, null)->onCompletion(function (): void {
                    $this->player1->teleport(new Position(15, 4, 40, $this->level));
                }, function (): void {
                });

                $this->level->orderChunkPopulation(15 >> 4, 10 >> 4, null)->onCompletion(function (): void {
                    $this->player2->teleport(new Position(15, 4, 10, $this->level));
                }, function (): void {
                });
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
                $this->onEnd();
                break;
        }

        $this->time--;
    }

    public function onEnd(?PracticePlayer $playerLeft = null): void
    {
        foreach ($this->getPlayers() as $online) {
            if (is_null($playerLeft) || $online->getName() !== $playerLeft->getName()) {
                $online->sendMessage(TF::GRAY . "---------------");

                $winnerMessage = TF::GOLD . "Winner: " . TF::WHITE;
                if ($this->winner === null) {
                    $winnerMessage .= "None";
                } else {
                    $winnerMessage .= $this->winner->getDisplayName() . " " . floor($this->winner->getHealth() / 2) . TF::RED . " ❤";
                }
                $online->sendMessage($winnerMessage);

                $loserMessage = TF::YELLOW . "Loser: " . TF::WHITE;
                $loserMessage .= $this->loser !== null ? $this->loser->getDisplayName() : "None";
                $online->sendMessage($loserMessage);

                $online->sendMessage(TF::GRAY . "---------------");
                $online->resetPlayer();
                $online->giveLobbyItems();
                $online->teleport($online->getServer()->getWorldManager()->getDefaultWorld()->getSafeSpawn(), 0, 0);
            }
        }

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