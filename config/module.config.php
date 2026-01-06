<?php
/**
 * Description of module.config
 * 
 * PHP version 8
 * 
 * * * License * * * 
 * Copyright (C) 2026 PortoSpire, LLC.
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
 * @category  Configuration
 * @package   SuiteCRMClient
 * @author    Andrew Wallace <andrew.wallace@portospire.com>
 * @copyright 2026 PORTOSPIRE
 * @license   LGPL 3
 * @version   Release: 0.1.5.0
 * @link      https://portospire.github.io/ 
 */

/*
 * Returns module configuration for Laminas Mezzio and Laminas MVC
 */
return [
    'service_manager' => [
        'factories' => [
            PortoSpire\SuiteCRMClient\Service\SuiteCrm::class => PortoSpireSuiteCRMClient\Service\SuiteCrmFactory::class,
        ]
    ]
];
