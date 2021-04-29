<?php
declare(strict_types=1);

namespace practice\kits;

use pocketmine\block\VanillaBlocks;
use pocketmine\item\Item;
use pocketmine\item\VanillaItems;

class BuildUHC extends Kit
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
            VanillaItems::GOLDEN_APPLE()->setCount(6),
            VanillaItems::STEAK()->setCount(64),
            VanillaItems::WATER_BUCKET(),
            VanillaItems::LAVA_BUCKET(),
            VanillaBlocks::COBBLESTONE()->asItem()->setCount(64),
            VanillaItems::DIAMOND_PICKAXE(),
            VanillaItems::ARROW()->setCount(32)
        ];
    }
}