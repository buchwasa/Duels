<?php
declare(strict_types=1);

namespace practice\kits;

use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\VanillaItems;

class Fist extends Kit
{

    /**
     * @return Item[]
     */
    public function getArmorItems(): array
    {
        return [
            ItemFactory::air()
        ];
    }

    /**
     * @return Item[]
     */
    public function getInventoryItems(): array
    {
        return [
            VanillaItems::STEAK()->setCount(64)
        ];
    }
}