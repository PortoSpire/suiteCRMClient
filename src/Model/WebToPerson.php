<?php

/**
 * Description of WebToPerson
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
 * @category  Model
 * @package   SuiteCRMClient
 * @author    Andrew Wallace <andrew.wallace@portospire.com>
 * @copyright 2022 PORTOSPIRE
 * @license   LGPL 3
 * @version   Release: 0.0.6
 * @link      https://portospire.github.io/ 
 */

namespace PortoSpire\SuiteCRMClient\Model;

/**
 * Description of WebToPerson
 *
 * @category  Model
 * @package   SuiteCRMClient
 * @author    Andrew Wallace <andrew.wallace@portospire.com>
 * @copyright 2022 PORTOSPIRE
 * @license   LGPL 3
 * @version   Release: 0.0.6
 * @link      https://portospire.github.io/
 * @since     Class available since Release 0.0.0
 */
class WebToPerson
{
    const MODULE_DIR = 'Prospects';
    public $description, $salutation, $first_name, $last_name, $title, $department,
        $do_not_call, $phone_home, $phone_mobile, $phone_work, $phone_other, $phone_fax,
        $email1, $email2, $lawful_basis, $date_reviewed, $lawful_basis_source,
        $primary_address_street, $primary_address_city, $primary_address_state,
        $primary_address_postalcode, $primary_address_country, $alt_address_street,
        $alt_address_city, $alt_address_state, $alt_address_postalcode, $alt_address_country,
        $assistant, $assistant_phone, $account_name, $birthdate;
    public $salutation_values = ['Mr.','Ms.','Mrs.','Mrs.','Miss','Dr.','Prof.', ''],
        $lawful_basis_values = ['consent', 'contract', 'legal_obligation',
            'protection_of_interest','public_interest','legitimate_interest','withdrawn',''],
        $lawful_basis_source_values = ['website','phone','given_to_user','email','third_party',''];
    public $has_options = ['salutation','lawful_basis','lawful_basis_source'];
    public $required_fields = ['last_name'];
    
    public function __construct()
    {
        //no constructing done at this time.
    }
    
    public function extractNonEmpty(): array
    {
        $return = [];
        $vars = get_object_vars($this);
        foreach($vars as $key=>$value){
            if(!empty($value) && !is_array($value)){
                $return[$key]=$value;
            }
        }
        return $return;
    }
    
    public function toArray(): array
    {
        return get_object_vars($this);
    }
    
    public function exchangeArray(array $data): WebToPerson
    {
        foreach($data as $key=>$value)
        {
            if(property_exists($this, $key)){
                $this->$key = $value;
            }
        }
        return $this;
    }
    
    public function checkRequiredFields()
    {
        foreach($this->required_fields as $field)
        {
            if(empty($this->$field)){
                throw \Exception('SuiteCRM: "'.$field.'" is a required field for WebToPerson submissions');
            }
        }
    }
    
    public function checkFieldOptions()
    {
        foreach($this->has_options as $opt)
        {
            $key = $opt.'_values';
            if(!in_array($this->$opt, $this->$key)){
                throw new \Exception('SuiteCRM: "'.$this->$opt.'" is not a valid value for attribute "'.$opt.'"');
            }
        }
    }
}
