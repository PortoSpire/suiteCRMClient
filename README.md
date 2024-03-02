![SuiteCRM API Client](https://assets.portospire.com/github.io/suitecrmclient.png)

![Version](https://img.shields.io/github/v/release/PortoSpire/suiteCRMClient)
![Size](https://img.shields.io/github/languages/code-size/PortoSpire/suiteCRMClient)
![License](https://img.shields.io/github/license/PortoSpire/suiteCRMClient)
# Client library for use with SuiteCRM
A free (LGPL3) client library for use with SuiteCRM to abstract various API usage to enable easier integrations.

[Provided by PortoSpire  
<img alt="PortoSpire - be seen" src="https://assets.portospire.com/psf/img/portospire%20header%20glow.svg" width="182" />](https://www.portospire.com)

***

 ## Table of Contents ##
  **[1. Introduction](#introduction)**  
  **[2. Setup](#setup)**  
  **[3. Usage](#usage)**  
   *[3.1. Mezzio](#mezzio)*  
   *[3.2. Laminas MVC](#laminasmvc)*  
   *[3.3. Standalone](#standalone)*  

*** 

## <a name="introduction" href="#introduction">Introduction</a>
This package provides a SuiteCRM client to abstract API calls and form submissions to provide and receive 
data from SuiteCRM instances. It makes use of Person Form campaigns and both the V8 and rest APIs as needed.

## <a name="setup" href="#setup">Setup</a>
Add to your project's composer.json

```bash
composer require portospire/suitecrmclient
```

## <a name="usage" href="#usage">Usage</a>
This package is built to support Laminas Mezzio and Laminas MVC as well 
as be available as a stand alone library. 

An example to get a list of Web campaigns from a SuiteCRM instance:

```php
$SuiteCRMClient->setServerDomain($server_domain);
$SuiteCRMClient->setClientId($client_id);
$SuiteCRMClient->setClientSecret($client_secret);
$filter = new \PortoSpire\SuiteCRMClient\Model\Filter(['campaign_type' => 'Web']);
$json = $SuiteCRMClient->getCampaigns([], ['size' => 20, 'number' => 1], 'name', $filter); // this will contain json of the results
$campaignsFull = $SuiteCRMClient->convertJsonToGenerics($json); // this converts the json to PHP objects
```

An example to submit a lead into a web-to-lead form in a SuiteCRM instance:
(You can extend the provided models (Model/WebToLead, etc) if you have custom fields)

```php
$values = (array) $WebToLead; // expects an array of key=>value pairs where the keys match the different fields from the web-to-lead form
$SuiteCRMClient->setServerDomain($server_domain);
$SuiteCRMClient->setClientId($client_id);
$SuiteCRMClient->setClientSecret($client_secret);
$SuiteCRMClient->submitWebToLead($values, $campaign_id); // this must match the campaign id that the web-to-lead form is associated to in SuiteCRM
```

### <a name="mezzio" href="#mezzio">Mezzio</a>
Add the ConfigProvider class to the config aggregator (typically found in config/config.php)

```php
$aggregator = new ConfigAggregator([
...
\PortoSpire\SuiteCRMClient\ConfigProvider::class,
...
```

Then use the client in your handlers/middleware as needed for your use cases.


### <a name="laminasmvc" href="#laminasmvc">Laminas MVC</a>
There should be no additional steps beyond adding to your project's composer.json required to begin using the library with Laminas MVC.

### <a name="standalone" href="#standalone">Standalone</a>
There should be no additional steps beyond adding to your project's composer.json required to begin using the library.
