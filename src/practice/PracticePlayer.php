<?php

namespace practice;

use practice\kits\Kit;
use pocketmine\item\VanillaItems;
use pocketmine\player\GameMode;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class PracticePlayer extends Player
{
    /** @var bool */
    private bool $isPlaying = false;
    /** @var bool */
    private bool $inQueue = false;
    /** @var Kit */
    private Kit $currentKit;

    public function resetPlayer(): void
    {
        $this->setScoreTag("");
        $this->setGamemode(GameMode::SURVIVAL());
        $this->setHealth($this->getMaxHealth());
        $this->getHungerManager()->setFood($this->getHungerManager()->getMaxFood());
        $this->getInventory()->clearAll();
        $this->getArmorInventory()->clearAll();
        $this->getEffects()->clear();
        $this->extinguish();
    }

    public function giveLobbyItems(): void
    {
        $this->getInventory()->setItem(0, VanillaItems::COMPASS()->setCustomName(TextFormat::RESET . TextFormat::WHITE . "Kit Selector"));
        $this->setInQueue(false);
        $this->setPlaying(false);
    }

    public function checkQueue(): void
    {
        $this->sendMessage(TextFormat::GOLD . "Entering queue...");
        foreach ($this->getServer()->getOnlinePlayers() as $player) {
            if ($player instanceof PracticePlayer and $player->getName() != $this->getName()) {
                if ($player->isInQueue() && $player->getCurrentKit() === $this->getCurrentKit()) {
                    MatchManager::getInstance()->createMatch($this, $player, $this->getCurrentKit());
                    $this->sendMessage(TextFormat::YELLOW . "Found a match against " . TextFormat::GOLD . $player->getName());
                    $player->sendMessage(TextFormat::YELLOW . "Found a match against " . TextFormat::GOLD . $this->getName());
                    $player->setInQueue(false);
                    $this->setInQueue(false);
                    return;
                }
            }
        }
    }

    public function isPlaying(): bool
    {
        return $this->isPlaying;
    }

    public function setPlaying(bool $playing): void
    {
        $this->isPlaying = $playing;
    }

    public function isInQueue(): bool
    {
        return $this->inQueue;
    }

    public function setInQueue(bool $inQueue): void
    {
        $this->inQueue = $inQueue;
    }

    public function getCurrentKit(): Kit
    {
        return $this->currentKit;
    }

    public function setCurrentKit(Kit $kit): void
    {
        $this->currentKit = $kit;
    }
}