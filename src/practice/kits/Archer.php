<?php
declare(strict_types=1);

namespace practice\kits;

use pocketmine\item\Item;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\VanillaItems;

class Archer extends Kit
{

    /**
     * @return Item[]
     */
    public function getArmorItems(): array
    {
        return [
            VanillaItems::IRON_HELMET(),
            VanillaItems::IRON_CHESTPLATE(),
            VanillaItems::IRON_LEGGINGS(),
            VanillaItems::IRON_BOOTS()
        ];
    }

    /**
     * @return Item[]
     */
    public function getInventoryItems(): array
    {
        return [
            VanillaItems::BOW()->addEnchantment(new EnchantmentInstance(VanillaEnchantments::INFINITY()))
                ->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 3)),
            VanillaItems::STEAK()->setCount(64),
            VanillaItems::ARROW()
        ];
    }
}