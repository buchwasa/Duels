<?php

namespace practice;

use pocketmine\plugin\PluginBase;
use UnexpectedValueException;

class Loader extends PluginBase
{

    public function onEnable(): void
    {
        new MatchManager($this);
        $this->getServer()->getPluginManager()->registerEvents(new BaseListener(), $this);
    }

    public function onDisable(): void
    {
        foreach (MatchManager::getInstance()->getMatches() as $activeMatch => $matchTask) {
            MatchManager::getInstance()->stopMatch($activeMatch);
        }
    }

    public function deleteDir($dirPath): void
    {
        if (!is_dir($dirPath)) {
            throw new UnexpectedValueException("dirPath must be a directory");
        }

        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }

        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                $this->deleteDir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dirPath);
    }
}