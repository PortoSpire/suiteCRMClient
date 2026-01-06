<?php

/**
 * Description of WebToContact
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
 * @category  Model
 * @package   SuiteCRMClient
 * @author    Andrew Wallace <andrew.wallace@portospire.com>
 * @copyright 2026 PORTOSPIRE
 * @license   LGPL 3
 * @version   Release: 0.1.5.1
 * @link      https://portospire.github.io/ 
 */

namespace PortoSpire\SuiteCRMClient\Model;

/**
 * Description of WebToContact
 *
 * @category  Model
 * @package   SuiteCRMClient
 * @author    Andrew Wallace <andrew.wallace@portospire.com>
 * @copyright 2026 PORTOSPIRE
 * @license   LGPL 3
 * @version   Release: 0.1.5.1
 * @link      https://portospire.github.io/
 * @since     Class available since Release 0.0.1
 */
class WebToContact extends WebToPerson
{
    const MODULE_DIR = 'Contacts';
    public $lead_source,$joomla_account_id,$portal_account_disabled,$portal_user_type,$facebook_user_c;
    public $lead_source_values = ['Cold Call', 'Existing Customer', 'Self Generated', 'Employee',
            'Partner', 'Public Relations', 'Direct Mail', 'Conference', 'Trade Show',
            'Web Site', 'Word of mouth', 'Email', 'Campaign', 'Other'],
        $portal_user_type_values = ['Single','Account'];
    public function __construct()
    {
        parent::__construct();
        $this->has_options[]='lead_source';
        $this->has_options[]='portal_user_type';
    }
}
