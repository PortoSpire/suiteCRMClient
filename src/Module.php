<?php
namespace Portospire\SuiteCRMClient;

class Module
{

    const VERSION = "0.0.4";
 public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
}
