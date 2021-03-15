<?php
declare(strict_types=1);

namespace practice\kits;

use pocketmine\utils\RegistryTrait;

/**
 * @method static Kit ARCHER()
 * @method static Kit BUILDUHC()
 * @method static Kit CLASSIC()
 * @method static Kit COMBO()
 * @method static Kit FIST()
 * @method static Kit NO_DEBUFF()
 * @method static Kit SG()
 */
class KitRegistry
{
    use RegistryTrait;

    protected static function setup(): void
    {
        self::register(new Archer("Archer"));
        self::register(new BuildUHC("BuildUHC"));
        self::register(new Classic("Classic"));
        self::register(new Combo("Combo"));
        self::register(new Fist("Fist"));
        self::register(new NoDebuff("NoDebuff"));
        self::register(new SG("SG"));
    }

    public static function fromString(string $name): Kit
    {
        /** @var Kit $kit */
        $kit = self::_registryFromString(strtolower($name));
        return $kit;
    }

    /**
     * @return Kit[]
     */
    public static function getKits(): array
    {
        /** @var Kit[] $kits */
        $kits = self::_registryGetAll();
        return $kits;
    }

    public static function register(Kit $kit): void
    {
        self::_registryRegister($kit->getName(), $kit);
    }
}