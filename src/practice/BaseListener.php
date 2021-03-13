<?php
declare(strict_types=1);

namespace practice;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCreationEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerExhaustEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\item\ItemIds;
use pocketmine\item\VanillaItems;
use pocketmine\player\GameMode;
use pocketmine\utils\TextFormat;
use practice\kits\KitRegistry;
use xenialdan\customui\elements\Button;
use xenialdan\customui\windows\SimpleForm;

class BaseListener implements Listener
{

    public function onCreation(PlayerCreationEvent $event)
    {
        $event->setPlayerClass(PracticePlayer::class);
    }

    public function onJoin(PlayerJoinEvent $event)
    {
        /** @var PracticePlayer $player */
        $player = $event->getPlayer();

        $event->setJoinMessage("");
        $player->giveLobbyItems();
    }

    public function onQuit(PlayerQuitEvent $event)
    {
        $event->setQuitMessage("");
    }

    public function onInteract(PlayerInteractEvent $ev)
    {
        $player = $ev->getPlayer();
        $item = $ev->getItem();
        if ($player instanceof PracticePlayer) {
            if ($player->getWorld()->getDisplayName() === $player->getServer()->getWorldManager()->getDefaultWorld()->getDisplayName() && $item->getId() === ItemIds::COMPASS) {
                {
                    $form = new SimpleForm("Kit Selector");
                    foreach (KitRegistry::getKits() as $kit) {
                        $form->addButton(new Button($kit->getName()));
                    }
                    $form->setCallable(function (PracticePlayer $player, $data) {
                        if (!is_string($data)) return;
                        $player->setCurrentKit(KitRegistry::fromString($data));
                        $player->setInQueue(true);
                        $player->getInventory()->clearAll();
                        $player->getInventory()->setItem(0, VanillaItems::PAPER()->setCustomName(TextFormat::RESET . TextFormat::RED . "Leave Queue"));
                        $player->checkQueue();
                    });

                    if (!$player->isInQueue()) {
                        $player->sendForm($form);
                    }
                }
            }
        }
    }

    public function onDamage(EntityDamageEvent $event)
    {
        $entity = $event->getEntity();
        if ($entity instanceof PracticePlayer) {
            if ($entity->getWorld()->getDisplayName() === $entity->getServer()->getWorldManager()->getDefaultWorld()->getDisplayName()) {
                $event->cancel();
            } else {
                /** @var PracticePlayer $entity */
                $entity = $event->getEntity();
                if ($entity->isPlaying() && $event->getFinalDamage() >= $entity->getHealth()) {
                    foreach ($entity->getWorld()->getPlayers() as $player) {
                        $player->sendMessage(TextFormat::RED . "{$player->getDisplayName()} died");
                    }
                    $entity->setPlaying(false);
                    $entity->resetPlayer();
                    $entity->setGamemode(GameMode::SPECTATOR());
                }
            }
        } else {
            $event->cancel();
        }
    }

    public function onDrop(PlayerDropItemEvent $event)
    {
        $player = $event->getPlayer();
        if ($player->getWorld()->getDisplayName() === $player->getServer()->getWorldManager()->getDefaultWorld()->getDisplayName()) {
            $event->cancel();
        }
    }

    public function onBreak(BlockBreakEvent $event): void
    {
        $player = $event->getPlayer();
        if ($player instanceof PracticePlayer && $player->isPlaying() && MatchManager::getInstance()->canUseBlock($event->getBlock())) {
            return;
        } else {
            $event->cancel();
        }
    }

    public function onPlace(BlockPlaceEvent $event): void
    {
        $player = $event->getPlayer();
        if ($player instanceof PracticePlayer && $player->isPlaying() && MatchManager::getInstance()->canUseBlock($event->getBlock())) {
            return;
        } else {
            $event->cancel();
        }
    }

    public function onExhaust(PlayerExhaustEvent $event)
    {
        $player = $event->getPlayer();
        if ($player instanceof PracticePlayer) {
            if ($player->getWorld()->getDisplayName() === $player->getServer()->getWorldManager()->getDefaultWorld()->getDisplayName()) {
                $event->cancel();
            }
        }
    }
}