<?php
declare(strict_types=1);

namespace practice\kits;

use practice\kits\Kit;
use pocketmine\item\ItemFactory;
use pocketmine\item\VanillaItems;

class Fist extends Kit
{

    public function getArmorItems(): array
    {
        return [
            ItemFactory::air()
        ];
    }

    public function getInventoryItems(): array
    {
        return [
            VanillaItems::STEAK()->setCount(64)
        ];
    }
}