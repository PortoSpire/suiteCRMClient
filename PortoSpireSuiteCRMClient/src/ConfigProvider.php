<?php

namespace PortoSpireSuiteCRMClient;

/**
 * The configuration provider for the PortoSpireSuiteCRMClient module
 *
 */
class ConfigProvider
{

    const VERSION = "0.0.2";
    
    /**
     * Returns the configuration array
     *
     * To add a bit of a structure, each section is defined in a separate
     * method which returns an array with its configuration.
     *
     */
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
            'templates' => $this->getTemplates(),
        ];
    }

    /**
     * Returns the container dependencies
     */
    public function getDependencies(): array
    {
        return [
            'factories' => [
                \PortoSpireSuiteCRMClient\Servics\SuiteCrm::class => \PortoSpireSuiteCRMClient\Service\SuiteCrmFactory::class,
            ],
        ];
    }

    /**
     * Returns the templates configuration
     */
    public function getTemplates(): array
    {
        return [
            'paths' => [
            ],
        ];
    }

}
