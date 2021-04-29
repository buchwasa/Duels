<?php
declare(strict_types=1);

namespace practice\kits;

use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\item\Potion;
use pocketmine\item\VanillaItems;

class Combo extends Kit
{
    /**
     * @return Item[]
     */
    public function getArmorItems(): array
    {
        return [
            VanillaItems::DIAMOND_HELMET()->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 4))
                ->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 3)),
            VanillaItems::DIAMOND_CHESTPLATE()->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 4))
                ->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 3)),
            VanillaItems::DIAMOND_LEGGINGS()->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 4))
                ->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 3)),
            VanillaItems::DIAMOND_BOOTS()->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 4))
                ->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 3))
        ];
    }

    /**
     * @return Item[]
     */
    public function getInventoryItems(): array
    {
        return [
            VanillaItems::DIAMOND_SWORD()->addEnchantment(new EnchantmentInstance(VanillaEnchantments::SHARPNESS(), 5))
                ->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 3)),
            VanillaItems::STEAK()->setCount(64),
            VanillaItems::ENCHANTED_GOLDEN_APPLE()->setCount(64),
            ItemFactory::getInstance()->get(ItemIds::POTION, Potion::SWIFTNESS, 5),
            ItemFactory::getInstance()->get(ItemIds::POTION, Potion::STRENGTH, 5),
        ];
    }
}