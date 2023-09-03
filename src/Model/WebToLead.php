<?php

/**
 * Description of WebToLead
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
 * @category  CategoryName
 * @package   PackageName
 * @author    Andrew Wallace <andrew.wallace@portospire.com>
 * @copyright 2019 PORTOSPIRE
 * @license   LGPL 3
 * @version   GIT: 0.1.3.2
 * @link      https://portospire.com 
 */

namespace PortoSpire\SuiteCRMClient\Model;

use \PortoSpire\SuiteCRMClient\Model\WebToPerson;

/**
 * Description of WebToLead
 *
 * @category  CategoryName
 * @package   PackageName
 * @author    Andrew Wallace <andrew.wallace@portospire.com>
 * @copyright 2019 PORTOSPIRE
 * @license   LGPL 3
 * @version   Release: @package_version@
 * @link      https://coderepo.portospire.com/#git_repo_name
 * @since     Class available since Release 0.0.0
 */
class WebToLead extends WebToPerson
{
    const MODULE_DIR = 'Leads';
    public $referred_by, $lead_source, $lead_source_description,
        $status, $status_description, $account_description, $opportunity_name,
        $opportunity_amount, $portal_name, $portal_app, $website;
    public $lead_source_values = ['Cold Call', 'Existing Customer', 'Self Generated', 'Employee',
            'Partner', 'Public Relations', 'Direct Mail', 'Conference', 'Trade Show',
            'Web Site', 'Word of mouth', 'Email', 'Campaign', 'Other'],
        $status_values = ['New', 'Assigned','In Progress','Converted','Recycled','Dead'];
    public function __construct()
    {
        parent::__construct();
        $this->has_options[]='lead_source';
        $this->has_options[]='status';
        $this->status='New';
    }
}
