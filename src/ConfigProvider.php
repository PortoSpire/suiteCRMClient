<?php
/**
 * Description of ConfigProvider
 * 
 * PHP version 7
 * 
 * * * License * * * 
 * Copyright (C) 2022 PortoSpire, LLC.
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301  USA
 * * * End License * * * 
 * 
 * @category  ConfigProvider
 * @package   SuiteCRMClient
 * @author    Andrew Wallace <andrew.wallace@portospire.com>
 * @copyright 2022 PORTOSPIRE
 * @license   LGPL 3
 * @version   Release: 0.0.6
 * @link      https://portospire.github.io/ 
 */
namespace PortoSpire\SuiteCRMClient;

/**
 * Description of ConfigProvider
 * 
 * The configuration provider for SuiteCRMClient for Laminas Mezzio
 *
 * @category  ConfigProvider
 * @package   SuitCRMClient
 * @author    Andrew Wallace <andrew.wallace@portospire.com>
 * @copyright 2022 PORTOSPIRE
 * @license   LGPL 3
 * @version   Release: 0.0.6
 * @link      https://portospire.github.io/
 * @since     Class available since Release 0.0.1
 */
class ConfigProvider
{

    const VERSION = "0.1.3.7";
    
    /**
     * Returns the configuration array
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
                Service\SuiteCrm::class => Service\SuiteCrmFactory::class,
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
