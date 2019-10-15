
# Client library for use with SuiteCRM
A free client library for use with SuiteCRM to abstract the various API usage to enable easier integrations.
<a href="https://suitecrm.com/"><img src="https://assets.portospire.com/psf/img/suite_icon.png" alt="SuiteCRM" width="182" /></a>

<a href="https://www.portospire.com/">Provided by PortoSpire 
    <img src="https://assets.portospire.com/psf/img/portospire%20header.svg" alt="PortoSpire - be seen" width="182" /></a>

## Introduction
This package provides a SuiteCRM client to abstract API calls and form submissions to provide and receive 
data from SuiteCRM instances. It makes use of Person Form campaigns and both the V8 and rest APIs.

## Setup
Install libraries
> composer install

## Usage
This package is built to support zend-expressive and zend-framework 2/3 as well 
as be available as a stand alone library. 

### Zend Expressive
Add the ConfigProvider class to the config aggregator (typically found in config.php)
> \PortoSpire\SuiteCRM\ConfigProvider::class,

### Zend Framework 2/3
Add the module to the module config (typically found in APP_DIR/config/modules.config.php)
> SuiteCRM

### Standalone
