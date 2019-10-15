<?php

/**
 * Description of SuiteCrm
 * 
 * PHP version 7
 * 
 * * * License * * * 
 * PORTOSPIRE ("COMPANY") CONFIDENTIAL
 * Unpublished Copyright (c) 2016-2020 PORTOSPIRE, All Rights Reserved.
 * 
 * NOTICE: All information contained herein is, and remains the property of 
 * COMPANY. The intellectual and technical concepts contained herein are
 * proprietary to COMPANY and may be covered by U.S. and Foreign Patents, 
 * patents in process, and are protected by trade secret or copyright law.
 * Dissemination of this information or reproduction of this material is 
 * strictly forbidden unless prior written permission is obtained from COMPANY.
 * Access to the source code contained herein is hereby forbidden to anyone 
 * except current COMPANY employees, managers or contractors who have executed 
 * Confidentiality and Non-disclosure agreements explicitly covering such access.
 * 
 * The copyright notice above does not evidence any actual or intended publication
 * or disclosure of this source code, which includes information that is 
 * confidential and/or proprietary, and is a trade secret, of COMPANY. 
 * ANY REPRODUCTION, MODIFICATION, DISTRIBUTION, PUBLIC  PERFORMANCE, OR
 * PUBLIC DISPLAY OF OR THROUGH USE OF THIS SOURCE CODE WITHOUT THE EXPRESS WRITTEN
 * CONSENT OF COMPANY IS STRICTLY PROHIBITED, AND IN VIOLATION OF APPLICABLE 
 * LAWS AND INTERNATIONAL TREATIES. THE RECEIPT OR POSSESSION OF THIS SOURCE CODE
 * AND/OR RELATED INFORMATION DOES NOT CONVEY OR IMPLY ANY RIGHTS TO REPRODUCE,
 * DISCLOSE OR DISTRIBUTE ITS CONTENTS, OR TO MANUFACTURE, USE, OR SELL ANYTHING 
 * THAT IT MAY DESCRIBE, IN WHOLE OR IN PART.
 * * * End License * * * 
 * 
 * @category  CategoryName
 * @package   PackageName
 * @author    Andrew Wallace <andrew.wallace@portospire.com>
 * @copyright 2019 PORTOSPIRE
 * @license   https://portospire.com/policies Proprietary, Confidential
 * @version   GIT: $ID$
 * @link      https://portospire.com 
 */

namespace PortoSpireSuiteCRMClient\Service;

use \GuzzleHttp\Client;
use \GuzzleHttp\Exception\RequestException;
use \GuzzleHttp\Psr7;
use \Psr\Log\LoggerInterface;

/**
 * Description of SuiteCrm
 *
 * @category  CategoryName
 * @package   PackageName
 * @author    Andrew Wallace <andrew.wallace@portospire.com>
 * @copyright 2019 PORTOSPIRE
 * @license   https://portospire.com/policies Proprietary
 * @version   Release: @package_version@
 * @link      https://coderepo.portospire.com/#git_repo_name
 * @since     Class available since Release 0.0.0
 */
class SuiteCrm
{

    const _access_url = 'access_token',
        _module_url = 'V8/module';

    private $logger, $server_domain, $client_id, $client_secret, $access_token, $token_expires,
        $guzzle;

    public function __construct(LoggerInterface $logger, array $config = [])
    {
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
    }

    private function initConnection()
    {
        $this->guzzle = new Client(['base_uri' => 'https://' . $this->server_domain . '/Api/',
            'headers' => ['Content-type: application/vnd.api+json',
                'Accept: application/vnd.api+json']]);
    }

    public function submitWebToLead(array $values, string $campaignID,
        string $uri = '/index.php?entryPoint=WebToPersonCapture',
        string $lead_source = 'Other',
        string $lead_source_description = 'PortoSpire: WebToLead',
        string $module_dir = 'Leads',
        string $assigned_user_id = '1',
        array $opt_fields = []): bool
    {
        $optional_fields = !empty($opt_fields) ? $opt_fields : ['first_name', 'work_phone', 'email1'];
        $required_fields = ['last_name'];
        foreach ($optional_fields as $field) {
            $values[$field] = isset($values[$field]) ? $values[$field] : '';
        }
        foreach ($required_fields as $field) {
            if (!isset($values[$field])) {
                throw \Exception('SuiteCRM: "' . $field . '" is a required field for WebToLead submissions');
            }
        }
        $vars = ['campaign_id' => $campaignID,
            'first_name' => $values['first_name'],
            'last_name' => $values['last_name'],
            'work_phone' => $values['work_phone'],
            'email1' => $values['email1'],
            'lead_source_description' => $lead_source_description,
            'moduleDir' => $module_dir,
            'assigned_user_id' => $assigned_user_id,
            'submit' => 'Submit',
            'lead_source' => $lead_source,
        ];
        if (!isset($this->access_token)) {
            $this->initConnection();
        }
        try {
            $request = $this->guzzle->post($uri, ['form_params' => $vars]);
            $promise = $client->requestAsync('POST', $uri);
            $promise->then(
                function (ResponseInterface $res) {
                echo $res->getStatusCode() . "\n";
            },
                function (RequestException $e) {
                echo $e->getMessage() . "\n";
                echo $e->getRequest()->getMethod();
            }
            );
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

    /**
     * determines if a passed string matches the criteria for a Sugar GUID.
     *
     * @param string $guid
     *
     * @return bool False on failure
     */
    private function is_guid(string $guid): bool
    {
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
    public function create_guid(): string
    {
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

    private function create_guid_section(string $characters): string
    {
        $return = '';
        for ($i = 0; $i < $characters; ++$i) {
            $return .= dechex(mt_rand(0, 15));
        }
        return $return;
    }

    private function ensure_length(string &$string, int $length): void
    {
        $strlen = strlen($string);
        if ($strlen < $length) {
            $string = str_pad($string, $length, '0');
        } elseif ($strlen > $length) {
            $string = substr($string, 0, $length);
        }
    }
    
    public function callV8Api()
    {
        
    }
    
    public function callRestApi()
    {
        
    }

    private function getAccessToken(): string
    {
        if (isset($this->access_token) && isset($this->token_expires) && time() < $this->token_expires) {
            return $this->access_token;
        }
        if (!$this->guzzle instanceof Client) {
            $this->initConnection();
        }
        try {
            $response = $this->guzzle->post($this::_access_url, ['json' => [
                    'grant_type' => 'client_credentials',
                    'client_id' => $this->client_id,
                    'client_secret' => $this->client_secret
            ]]);
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
            $this->logger->error(Psr7\str($e->getRequest()));
            if ($e->hasResponse()) {
                $this->logger->error(Psr7\str($e->getResponse()));
            }
        } catch (\Exception $e) {
            $this->logger->error('SuiteCRM: unable to get access token. ' . $e->getMessage());
        }
        throw new \Exception('SuiteCRM: unable to fetch access token. Check the logs for details.');
    }

    public function setClientId(string $client_id)
    {
        $this->client_id = $client_id;
    }

    public function setClientSecret(string $client_secret)
    {
        $this->client_secret = $client_secret;
    }

}
