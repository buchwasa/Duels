<?php
declare(strict_types=1);

namespace practice\kits;

use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\item\Potion;
use pocketmine\item\VanillaItems;

class NoDebuff extends Kit
{
    public function getArmorItems(): array
    {
        return [
            VanillaItems::DIAMOND_HELMET(),
            VanillaItems::DIAMOND_CHESTPLATE(),
            VanillaItems::DIAMOND_LEGGINGS(),
            VanillaItems::DIAMOND_BOOTS()
        ];
    }

    public function getInventoryItems(): array
    {
        return [
            VanillaItems::DIAMOND_SWORD(),
            VanillaItems::ENDER_PEARL()->setCount(16),
            VanillaItems::STEAK()->setCount(64),
            ItemFactory::getInstance()->get(ItemIds::POTION, Potion::SWIFTNESS, 2),
            ItemFactory::getInstance()->get(ItemIds::SPLASH_POTION, Potion::STRONG_HEALING, 34)
        ];
    }
}