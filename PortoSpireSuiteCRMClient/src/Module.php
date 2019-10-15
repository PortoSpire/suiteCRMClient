<?php
namespace Portospire\SuiteCRM;

class Module
{

    const VERSION = "0.0.2";
 public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
}
