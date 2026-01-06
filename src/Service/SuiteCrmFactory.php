<?php

/**
 * Description of SuiteCrmFactory
 * 
 * PHP version 8
 * 
 * * * License * * * 
 * Copyright (C) 2025 PortoSpire, LLC.
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
 * @category  Factory
 * @package   SuiteCRMClient
 * @author    Andrew Wallace <andrew.wallace@portospire.com>
 * @copyright 2026 PORTOSPIRE
 * @license   LGPL 3
 * @version   Release: 0.1.5.0
 * @link      https://portospire.github.io/
 */

namespace PortoSpire\SuiteCRMClient\Service;

use PortoSpire\SuiteCRMClient\Service\SuiteCrm;
use Psr\Container\ContainerInterface;
use Psr\Log\NullLogger;
use Psr\Log\LoggerInterface;

/**
 * Description of SuiteCrmFactory
 *
 * @category  Factory
 * @package   SuiteCRMClient
 * @author    Andrew Wallace <andrew.wallace@portospire.com>
 * @copyright 2026 PORTOSPIRE
 * @license   LGPL 3
 * @version   Release: 0.1.5.0
 * @link      https://portospire.github.io/
 * @since     Class available since Release 0.0.1
 */
class SuiteCrmFactory
{
    public function __invoke(ContainerInterface $container) : SuiteCrm
    {
        $logger = null;
        if($container->has(LoggerInterface::class)){
            $logger = $container->get(LoggerInterface::class);
        } else {
            $logger = new NullLogger();
        }
        return new SuiteCrm($logger);
    }
}
