<?php
namespace Portospire\SuiteCRM;

class Module
{

    const VERSION = "0.0.3";
 public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
}
