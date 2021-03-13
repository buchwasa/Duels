<?php
declare(strict_types=1);

namespace practice\kits;

use practice\kits\Kit;
use pocketmine\item\VanillaItems;

class SG extends Kit
{

    public function getArmorItems(): array
    {
        return [
            VanillaItems::GOLDEN_HELMET(),
            VanillaItems::IRON_CHESTPLATE(),
            VanillaItems::CHAINMAIL_LEGGINGS(),
            VanillaItems::IRON_BOOTS()
        ];
    }

    public function getInventoryItems(): array
    {
        return [
            VanillaItems::STONE_SWORD(),
            VanillaItems::BOW(),
            VanillaItems::GOLDEN_APPLE(),
            VanillaItems::GOLDEN_CARROT(),
            VanillaItems::PUMPKIN_PIE()->setCount(2),
            VanillaItems::BREAD(),
            VanillaItems::FLINT_AND_STEEL()->setDamage(99),
            VanillaItems::ARROW()->setCount(8)
        ];
    }
}