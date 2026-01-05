<?php

/**
 * Description of Module
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
 * @category  Module
 * @package   SuiteCRMClient
 * @author    Andrew Wallace <andrew.wallace@portospire.com>
 * @copyright 2022 PORTOSPIRE
 * @license   LGPL 3
 * @version   Release: 0.1.1
 * @link      https://portospire.github.io/ 
 */

namespace Portospire\SuiteCRMClient;

use PortoSpire\SuiteCRMClient\ConfigProvider;
use Laminas\Stdlib\ArrayUtils;

/**
 * Description of Module
 * 
 * The Module entry point for Laminas Mezzio & Laminas MVC
 *
 * @category  Module
 * @package   SuiteCRMClient
 * @author    Andrew Wallace <andrew.wallace@portospire.com>
 * @copyright 2022 PORTOSPIRE
 * @license   LGPL 3
 * @version   Release: 0.1.1
 * @link      https://portospire.github.com/
 * @since     Class available since Release 0.0.1
 */
class Module {

    const VERSION = "0.1.4.0";

    public function getConfig() {
        $configProvider = new ConfigProvider();

        $temp = [
            'service_manager' => $configProvider->getDependencies(),
        ];
        $config = ArrayUtils::merge(include __DIR__ . '/../config/module.config.php', $temp);
        if (class_exists(\Laminas\ApiTools\Module::class)) {
            unset($config['router']);
        }
        return $config;
    }
}
