[![Build Status](https://travis-ci.org/LeadPages/php_pages_component.svg?branch=master)](https://travis-ci.org/LeadPages/php_pages_component)

## Synopsis

Leadpages Pages is meant to make it simple to get your integration into Leadpages off the ground quickly.
* Abstracts away the required methods to call Leadpages to retrieve a list of all your pages and get specific pages.
* Built in minimal storage abstraction to allow Leadpages extensions to follow known sets of standards.
* Uses Guzzle5 to allow a consistent Http abstraction layer across all platforms. Guzzle5 chosen for PHP 5.4 support

## Code Example 

```
<?php

    use GuzzleHttp\Client;
    use WordPressLeadpagesLogin;  //class made from the Leadpages-Auth example
    use Leadpages\Pages\LeadpagesPages;
    
    
    class LeadpagesController
    {
    
        public function __construct(LeadpagesAuthentication $leadpagesAuthentication, Client $client)
        {
            $this->leadpagesAuthentication = $leadpagesAuthentication;
            $this->client = $client;
            $this->leadpagesPages = new LeadpagesPages($this->client, $this->leadpagesAuthentication);
        }
        
        /**
        * Get an array of all the pages you have in your account
        */
        public function getAllPages()
        {
            $pages = $this->leadpagesPages->getAllUserPages();
            return $pages;
        }
        
        /**
        * Get an array of all the pages you have in your account
        */
        public function getPageHtml()
        {
            //page id returned from $pages array above
            $page = $this->leadpagesPages->getSinglePageDownloadUrl('5638830484881408'); 
            $page = json_decode($page['response'], true);
            $pageUrl = $page['url'];
            $html = file_get_contents($pageUrl);
            echo $html;
        }
    
    }

```



## Installation

Package can be installed via [Composer](https://getcomposer.org/)

```
#install composer
curl -sS https://getcomposer.org/installer | php
```

Run composer to require the package

```
php composer.phar require leadpages/leadpages-auth
```

Next insure that you are included the composer autoloader into your project. Package uses PSR-4 Autoloading
```
require 'vendor/autoload.php';
```

## API Reference

Docs to come

## Tests

Tests are run via PHPUnit

## Contributors

## License

