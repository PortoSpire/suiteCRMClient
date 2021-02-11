<img src="https://img.shields.io/github/v/release/PortoSpire/suiteCRMClient" /> <img src="https://img.shields.io/github/languages/code-size/PortoSpire/suiteCRMClient" /> <img src="https://img.shields.io/github/license/PortoSpire/suiteCRMClient" />
# Client library for use with SuiteCRM
A free (LGPL3) client library for use with SuiteCRM to abstract various API usage to enable easier integrations.
<a href="https://suitecrm.com/"><img src="https://assets.portospire.com/psf/img/suite_icon.png" alt="SuiteCRM" width="182" /></a>

<a href="https://www.portospire.com/">Provided by PortoSpire 
    <img src="https://assets.portospire.com/psf/img/portospire%20header.svg" alt="PortoSpire - be seen" width="182" /></a>

[Introduction](#introduction)
[Setup](#setup)
[Usage](#usage)
* [Mezzio](#mezzio)
* [Laminas MVC](#laminasmvc)
* [Standalone](#standalone)
  

## <a name="introduction" href="#introduction">Introduction</a>
This package provides a SuiteCRM client to abstract API calls and form submissions to provide and receive 
data from SuiteCRM instances. It makes use of Person Form campaigns and both the V8 and rest APIs as needed.

## <a name="setup" href="#setup">Setup</a>
Install libraries
> composer install

## <a name="usage" href="#usage">Usage</a>
This package is built to support zend-expressive and zend-framework 2/3 as well 
as be available as a stand alone library. 

### <a name="mezzio" href="#mezzio">Mezzio</a>
Add the ConfigProvider class to the config aggregator (typically found in config.php)
> \PortoSpire\SuiteCRM\ConfigProvider::class,

### <a name="laminasmvc" href="#laminasmvc">Laminas MVC</a>
Add the module to the module config (typically found in APP_DIR/config/modules.config.php)
> SuiteCRM

### <a name="standalone" href="#standalone">Standalone</a>
