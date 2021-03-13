<?php
declare(strict_types=1);

namespace practice\kits;

use pocketmine\item\Item;

abstract class Kit
{
    /** @var string */
    private string $kitName;

    public function __construct(string $kitName)
    {
        $this->kitName = $kitName;
    }

    public function getName(): string
    {
        return $this->kitName;
    }

    /**
     * @return Item[]
     */
    public abstract function getArmorItems(): array;

    /**
     * @return Item[]
     */
    public abstract function getInventoryItems(): array;
}