<?php

/**
 * Description of SuiteCrm
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
 * @category  Service
 * @package   SuiteCRMClient
 * @author    Andrew Wallace <andrew.wallace@portospire.com>
 * @copyright 2026 PORTOSPIRE
 * @license   LGPL 3
 * @version   Release: 0.1.4.1
 * @link      https://portospire.github.io/
 */

namespace PortoSpire\SuiteCRMClient\Service;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7;
use GuzzleHttp\Psr7\Request;
use PortoSpire\SuiteCRMClient\Model\Filter;
use PortoSpire\SuiteCRMClient\Model\Generic;
use PortoSpire\SuiteCRMClient\Model\WebToContact;
use PortoSpire\SuiteCRMClient\Model\WebToLead;
use PortoSpire\SuiteCRMClient\Model\WebToPerson;
use Psr\Log\LoggerInterface;
use Swoole\MySQL\Exception as Exception2;

/**
 * Description of SuiteCrm
 *
 * @category  Service
 * @package   SuiteCRMClient
 * @author    Andrew Wallace <andrew.wallace@portospire.com>
 * @copyright 2026 PORTOSPIRE
 * @license   LGPL 3
 * @version   Release: 0.1.5.0
 * @link      https://portospire.github.io/
 * @since     Class available since Release 0.0.1
 */
class SuiteCrm {

    const _access_url = 'access_token',
            _module_url = 'V8/module',
            _rest_url = '/service/v4_1/rest.php',
            _web_to_person_uri = '/index.php?entryPoint=WebToPersonCapture',
            _v8_modes = ['GET' => 'get', 'POST' => 'post', 'PUT' => 'put', 'PATCH' => 'patch', 'DELETE' => 'delete'];

    private $logger, $server_domain, $client_id, $client_secret, $access_token, $token_expires,
            $guzzle, $user, $password, $sid;

    public function __construct(LoggerInterface $logger, array $config = []) {
        $this->logger = $logger;
        if (isset($config['client_id'])) {
            $this->client_id = $config['client_id'];
        }
        if (isset($config['client_secret'])) {
            $this->client_secret = $config['client_secret'];
        }
        if (isset($config['server_domain'])) {
            $this->server_domain = $config['server_domain'];
        }
        if (isset($config['user'])) {
            $this->user = $config['user'];
        }
        if (isset($config['password'])) {
            $this->password = $config['password'];
        }
        $this->guzzle = new Client(['headers' => ['Content-type' => 'application/vnd.api+json',
                'Accept' => '*/*']]);
    }

    /*
     * applies to v8 bearer tokens
     */

    public function getCurrentAccessToken() {
        return ['token' => $this->access_token, 'expires' => $this->token_expires];
    }

    /*
     * only used for v4_rest calls, initiates active session
     */

    public function login(): string {
        $login_params = array(
            'user_name' => $this->rest_user,
            'password' => $this->rest_pass,
        );
        $result = $this->rest_request('login', [
            'user_auth' => ['user_name' => $this->user,
                'password' => $this->password],
            'application_name' => '',
            'name_value_list' => [['name' => 'notifyonsave', 'value' => 'true']]
        ]);
        if (isset($result['id'])) {
            $this->sid = $result['id'];
            return $result['id'];
        }
        return false;
    }

    /*
     * only used for v4_rest calls, clears active session
     */

    public function logout() {
        $this->callRestApi('logout', ['session' => $this->sid]);
        $this->sid = null;
    }

    public function submitWebToPerson(WebToPerson $person,
            string $assignedUserId,
            string $campaignID) {
        $vars = $person->extractNonEmpty();
        $vars['moduleDir'] = $person::MODULE_DIR;
        $vars['campaign_id'] = $campaignID;
        $vars['assigned_user_id'] = $assignedUserId;
        $person->checkRequiredFields();
        $person->checkFieldOptions();
        try {
            $this->logger->debug($vars);
            $request = $this->guzzle->post('https://' . $this->server_domain . $this::_web_to_person_uri,
                    ['form_params' => $vars,]);
            if ($request->getStatusCode() == 200) {
                return true;
            } else {
                $this->logger->error('SuiteCRM: unable to submit WebToForm');
                return false;
            }
        } catch (RequestException $e) {
            $this->logger->notice(Psr7\str($e->getRequest()));
            if ($e->hasResponse()) {
                $this->logger->error(Psr7\str($e->getResponse()));
            }
        }
        return false;
    }

    public function submitWebToContact(array $values, string $campaignID,
            string $lead_source = 'Other',
            string $assigned_user_id = '1'): bool {
        $webToContact = new WebToContact();
        $webToContact->exchangeArray($values);
        return $this->submitWebToPerson($webToContact, $assigned_user_id, $campaignID);
    }

    public function submitWebToLead(array $values, string $campaignID,
            string $lead_source = 'Other',
            string $lead_source_description = 'PortoSpire: WebToLead',
            string $assigned_user_id = '1'): bool {
        $webToLead = new WebToLead();
        $webToLead->exchangeArray($values);
        $webToLead->lead_source = $lead_source;
        $webToLead->lead_source_description = $lead_source_description;
        return $this->submitWebToPerson($webToLead, $assigned_user_id, $campaignID);
    }

    /**
     * determines if a passed string matches the criteria for a SuitCRM GUID.
     *
     * @param string $guid
     *
     * @return bool False on failure
     */
    private function is_guid(string $guid): bool {
        if (strlen($guid) != 36) {
            return false;
        }
        if (preg_match("/\w{8}-\w{4}-\w{4}-\w{4}-\w{12}/i", $guid)) {
            return true;
        }
        return true;
    }

    /**
     * A method of generating GUIDs of the correct format for SuiteCRM.
     *
     * @return string containing a GUID in the format: aaaaaaaa-bbbb-cccc-dddd-eeeeeeeeeeee
     *
     */
    public function create_guid(): string {
        $microTime = microtime();
        list($a_dec, $a_sec) = explode(' ', $microTime);
        $dec_hex = dechex($a_dec * 1000000);
        $sec_hex = dechex($a_sec);
        $this->ensure_length($dec_hex, 5);
        $this->ensure_length($sec_hex, 6);
        $guid = '';
        $guid .= $dec_hex;
        $guid .= $this->create_guid_section(3);
        $guid .= '-';
        $guid .= $this->create_guid_section(4);
        $guid .= '-';
        $guid .= $this->create_guid_section(4);
        $guid .= '-';
        $guid .= $this->create_guid_section(4);
        $guid .= '-';
        $guid .= $sec_hex;
        $guid .= $this->create_guid_section(6);
        return $guid;
    }

    private function create_guid_section(string $characters): string {
        $return = '';
        for ($i = 0; $i < $characters; ++$i) {
            $return .= dechex(mt_rand(0, 15));
        }
        return $return;
    }

    private function ensure_length(string &$string, int $length): void {
        $strlen = strlen($string);
        if ($strlen < $length) {
            $string = str_pad($string, $length, '0');
        } elseif ($strlen > $length) {
            $string = substr($string, 0, $length);
        }
    }

    public function createRelationship(string $module, string $id, string $relationship_type, string $relationID) {
        $uri = $this->buildUri($module, [], [], null, [], $id, $relationship_type);
        $data = [
            'type' => $relationship_type,
            'id' => $relationID
        ];
        return $this->callV8Api($uri, 'POST', json_encode(['data' => $data]));
    }

    public function getRelationship(string $module, string $id, string $relationship_type, array $fields = [], array $page = [], string $sort = null, array $filter = []) {
        $uri = $this->buildUri($module, $fields, $page, $sort, $filter, $id, $relationship_type);
        return $this->callV8Api($uri, 'GET');
    }

    public function deleteRelationship(string $module, string $id, string $relationship_type, string $relationID) {
        $uri = $this->buildUri($module, [], [], null, [], $id, $relationship_type);
        return $this->callV8Api($uri . '/' . $relationID, 'DEL');
    }

    public function update(string $type, string $id, array $attributes) {
        $uri = $this::_module_url;
        $data = [
            'type' => $type,
            'id' => $id,
            'attributes' => $attributes
        ];
        return $this->callV8Api($uri, 'PATCH', json_encode(['data' => $data]));
    }

    public function create(string $type, array $attributes, string $id = null) {
        $uri = $this::_module_url;
        $data = [
            'type' => $type,
            'attributes' => $attributes
        ];
        if (!is_null($id)) {
            $data['id'] = $id;
        }
        return $this->callV8Api($uri, 'POST', json_encode(['data' => $data]));
    }

    public function delete(string $module, string $id) {
        $uri = $this->buildUri($module, [], [], null, [], $id);
        return $this->callV8Api($uri, 'DEL');
    }

    public function get(string $module, array $fields = []) {
        $uri = $this->buildUri($module, $fields);
        return $this->callV8Api($uri, 'GET');
    }

    private function checkMode($mode) {

        if ($key = array_search($mode, $this::_v8_modes)) {
            return $key;
        }
        if (array_key_exists($mode, $this::_v8_modes)) {
            return $mode;
        }
        return 'GET'; // default to GET
    }

    public function convertJsonToGenerics(array $decoded_json) {
        $res = [];
        foreach ($decoded_json['data'] as $obj) {
            $newObj = new Generic();
            $res[] = $newObj->exchangeArray($obj);
        }
        return $res;
    }

    public function callV8Api(string $uri, string $http_mode, string $body = null) {
        $mode = $this->checkMode($http_mode);
        $access_token = $this->getAccessToken();
        try {
            $this->logger->debug('Requesting v8 api uri: ' . $uri);
            $body = !is_null($body) ? $body : '';
            $request = new Request($mode, "https://{$this->server_domain}/Api/{$uri}",
                    [
                "Authorization" => "Bearer {$access_token}",
                "Content-Type" => "application/vnd.api+json",
                "Cache-Control" => "no-cache",
                    ], $body
            );

            $response = $this->guzzle->send($request);
            if ($response->getStatusCode() > 199 && $response->getStatusCode() < 300) {
                return json_decode($response->getBody(), true);
            } else {
                $this->logger->error('SuiteCRM: unable to make call to destination service');
                return false;
            }
        } catch (RequestException $e) {
            $this->logger->notice(Psr7\Message::toString($e->getRequest()));
            if ($e->hasResponse()) {
                $this->logger->error(Psr7\Message::toString($e->getResponse()));
            }
        }
    }

    public function getCampaigns(array $fields = [], array $page = ['size' => 20, 'number' => 1], string $sort = 'name', $filter = null) {
        if ($filter instanceof Filter) {
            return $this->getList('Campaigns', $fields, $page, $sort, $filter->toString());
        }
        return $this->getList('Campaigns', $fields, $page, $sort);
    }

    public function getContacts(array $fields = [], array $page = ['size' => 20, 'number' => 1], string $sort = 'name', $filter = null) {
        if ($filter instanceof Filter) {
            return $this->getList('Contacts', $fields, $page, $sort, $filter->toString());
        }
        return $this->getList('Contacts', $fields, $page, $sort);
    }

    public function getAccounts(array $fields = [], string $account_type = 'Customer', array $page = ['size' => 20, 'number' => 1],
            string $sort = 'name', $filter = null) {
        $filterAccountType = new Filter(['account_type' => $account_type]);
        if (is_array($filter)) {
            $filter[] = $filterAccountType->toString();
        } elseif ($filter instanceof Filter) {
            $filter = [$filter->toString(), $filterAccountType->toString()];
        } elseif (!is_null($filter)) {
            $filter .= '&' . $filterAccountType->toString();
        }
        return $this->getList('Accounts', $fields, $page, $sort, $filter->toString());
    }

    public function getList(string $module, array $fields = [], array $page = ['size' => 20, 'number' => 1], string $sort = null,
            $filter = []) {
        $uri = $this->buildUri($module, $fields, $page, $sort, $filter);
        return $this->callV8Api($uri, 'GET');
    }

    private function buildFilterUri($filter, $separator) {
        $string = '';
        if ($filter instanceof Filter) {
            $string .= $separator . $filter->toString();
        } elseif (is_array($filter)) {
            foreach ($filter as $filt) {
                if ($filt instanceof Filter) {
                    $string .= $separator . $filt->toString();
                } else {
                    $string .= $separator . $filt->toString();
                }
                $separator = '&';
            }
        } else {
            $string .= $separator . $filter;
        }
        return $string;
    }

    private function buildUri(string $entrypoint, array $fields = [], array $page = [], string $sort = null, $filter = [], $id = null, $relationpoint = null) {
        $string = $this::_module_url . '/' . $entrypoint;
        if (!is_null($relationpoint) && !is_null($id)) {
            $string = $string . '/' . $id . '/relationships/' . $relationpoint;
        }
        $string = $string . '?';
        $separator = '';

        // process fields
        foreach ($fields as $key => $val) {
            $string .= $separator . 'fields[' . $key . ']=';
            if (is_array($val)) { //handles cases like ['Accounts'=>['name','account_type']]
                $string .= implode(',', $val);
            } else { //handles cases like ['Accounts'=>'name,account_type']
                $string .= $val;
            }
            $separator = '&';
        }

        //process page
        if (isset($page['number'])) {
            $string .= $separator . 'page[number]=' . $page['number'];
            $separator = '&';
        }
        if (isset($page['size'])) {
            $string .= $separator . '&page[size]=' . $page['size'];
        }

        //add sort
        if (!is_null($sort)) {
            $string .= $separator . 'sort=' . $sort;
            $separator = '&';
        }

        // process filters
        if (is_array($filter)) {
            foreach ($filter as $filt) {
                $string .= $this->buildFilterUri($filt, $separator);
                $separator = '&';
            }
        } else {
            $string .= $this->buildFilterUri($filter, $separator);
            $separator = '&';
        }
        return $string;
    }

    public function callRestApi(string $callname, array $arguments) {
        try {
            $response = $this->guzzle->post('https://' . $this->server_domain . '/' . $this::_rest_url, ['method' => $callname,
                'input_type' => 'JSON', 'response_type' => 'JSON',
                'rest_data' => json_encode($arguments)]);
            if ($response->getStatusCode() === 200) {
                return json_decode($response->getBody(), true);
            }
        } catch (RequestException $e) {
            $this->logger->error(Psr7\str($e->getRequest()));
            if ($e->hasResponse()) {
                $this->logger->error(Psr7\str($e->getResponse()));
            }
        } catch (Exception2 $e) {
            $this->logger->error('SuiteCRM: failed calling rest api. ' . $e->getMessage());
        }
        return false;
    }

    private function getAccessToken(): string {
        if (isset($this->access_token) && isset($this->token_expires) && time() < $this->token_expires) {
            return $this->access_token;
        }
        try {
            $response = $this->guzzle->request('POST', 'https://' . $this->server_domain . '/Api/' . $this::_access_url, [
                'multipart' =>
                [
                    ['name' => 'grant_type', 'contents' => 'client_credentials'],
                    ['name' => 'client_id', 'contents' => $this->client_id],
                    ['name' => 'client_secret', 'contents' => $this->client_secret,
                        'headers' => [
                            'Content-Type' => 'application/vnd.api+json',
                            'Accept' => 'application/vnd.api+json'
                        ],],
                ],
            ]);
            if ($response->getStatusCode() === 200) {
                $out = json_decode($response->getBody(), true);
                if (isset($out['access_token'])) {
                    $this->access_token = $out['access_token'];
                    $this->token_expires = (time() + $out['expires_in']);
                    return $this->access_token;
                }
                $this->logger->error('SuiteCRM: unable to get access token. ' . $response->getBody());
            }
        } catch (RequestException $e) {
            $this->logger->error(Psr7\Message::toString($e->getRequest()));
            if ($e->hasResponse()) {
                $this->logger->error(Psr7\Message::toString($e->getResponse()));
            } else {
                $this->logger->error($e->getMessage());
            }
        } catch (BadResponseException $e) {
            $this->logger->error(Psr7\Message::toString($e->getRequest()));
            if ($e->hasResponse()) {
                $this->logger->error(Psr7\Message::toString($e->getResponse()));
            }
        } catch (Exception $e) {
            $this->logger->error('SuiteCRM: unable to get access token. ' . $e->getMessage());
        }

        throw new Exception('SuiteCRM: unable to fetch access token. Check the logs for details.');
    }

    public function setClientId(string $client_id) {
        $this->client_id = $client_id;
    }

    public function setClientSecret(string $client_secret) {
        $this->client_secret = $client_secret;
    }

    public function setUser(string $user) {
        $this->user = $user;
    }

    public function setPassword(string $password) {
        $this->password = $password;
    }

    public function setServerDomain(string $domain) {
        $this->server_domain = $domain;
    }
}
