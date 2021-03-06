<?php
declare(strict_types=1);

namespace practice\kits;

use pocketmine\item\Item;
use pocketmine\item\VanillaItems;

class Classic extends Kit
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
        return [
            VanillaItems::DIAMOND_SWORD(),
            VanillaItems::BOW(),
            VanillaItems::GOLDEN_APPLE()->setCount(9),
            VanillaItems::ARROW()->setCount(16),
            VanillaItems::STEAK()->setCount(64)
        ];
    }
}