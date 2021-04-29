<?php
declare(strict_types=1);

namespace practice\kits;

use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\item\Potion;
use pocketmine\item\VanillaItems;

class NoDebuff extends Kit
{

    /**
     * @return Item[]
     */
    public function getArmorItems(): array
    {
        return [
            VanillaItems::DIAMOND_HELMET(),
            VanillaItems::DIAMOND_CHESTPLATE(),
            VanillaItems::DIAMOND_LEGGINGS(),
            VanillaItems::DIAMOND_BOOTS()
        ];
    }

    /**
     * @return Item[]
     */
    public function getInventoryItems(): array
    {
        $contents = [];
        $contents[] = VanillaItems::DIAMOND_SWORD();
        $contents[] = VanillaItems::ENDER_PEARL()->setCount(16);
        $contents[] = VanillaItems::STEAK()->setCount(64);
        $contents[] = ItemFactory::getInstance()->get(ItemIds::POTION, Potion::SWIFTNESS, 2);
        for ($i = 0; $i < 32; $i++) {
            $contents[] = ItemFactory::getInstance()->get(ItemIds::SPLASH_POTION, Potion::STRONG_HEALING, 1);
        }

        return $contents;
    }
}