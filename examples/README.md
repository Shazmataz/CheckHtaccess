# CheckHtaccess: Installation Examples & Methods!
## Contents
<!-- TOC depthfrom:2 orderedlist:true updateonsave:false -->

- [Prerequisites & Requirements](#prerequisites--requirements)
  - [Prerequisites](#prerequisites)
  - [Minimum Requirements](#minimum-requirements)
- [Example Directory Structure](#example-directory-structure)
- [Usage Example Files Quick start / TLDR](#usage-example-files-quick-start--tldr)
- [Installing CheckHtaccess](#installing-checkhtaccess)
  - [Instantiating CheckHtaccess](#instantiating-checkhtaccess)
- [CheckHtaccess Methods](#checkhtaccess-methods)
  - [Helper Method](#helper-method)
- [Parameters for CheckHtaccess Methods](#parameters-for-checkhtaccess-methods)
- [Usage Examples](#usage-examples)
  - [checkHTFull: Standard, On-the-fly and Testing CheckHtaccess](#checkhtfull-standard-on-the-fly-and-testing-checkhtaccess)
  - [checkHTCron: Setting CheckHtaccess as a Cron Job](#checkhtcron-setting-checkhtaccess-as-a-cron-job)
    - [Adding CheckHtaccess to Cron Example](#adding-checkhtaccess-to-cron-example)
  - [checkHTon404: Using CheckHtaccess on 404 Error Pages](#checkhton404-using-checkhtaccess-on-404-error-pages)
- [Copyright & License](#copyright--license)
- [Contributions](#contributions)
- [Sponsorship](#sponsorship)

<!-- /TOC -->
## Prerequisites & Requirements
___
### Prerequisites
CheckHtaccess should work with PHP Version 5.6. and up. It should also work with most Apache based servers although some hosting packages may not allow PHP `fwrite` privileges.
### Minimum Requirements
* [PHP](https://www.php.net) 5.6 or above
* [PHP fwrite](https://www.php.net/manual/en/function.fwrite.php) & write privileges 
## Example Directory Structure 
___
```
.                                       # public_html
┣ lib/                                  # Lib directory 
┃ ┗ CheckHtaccess.class.php             # CheckHtaccess main class file  
┣ tasks/                                # tasks directory (forbidden to public)
┃ ┣ bak.htaccess                        # Backup .htaccess file (manually copied)
┃ ┣ checkHTFull.php                     # checkHTFull method enacting page 
┃ ┣ on404.php                           # checkHTon404 method enacting page (to require within your 404 page)
┃ ┗ onCron.php                          # checkHTCron method enacting page (to use as a Cron job)
┣ .htaccess                             # Active .htaccess file 
┣ 404.php                               # 404 error page     
┣ index.php                             # Website homepage    
┗ quickcheck.php                        # Non-indexed page for on-the-fly checking 
```
## Usage Example Files (Quick start / TLDR)
___
Here are examples of implementing and using CheckHtaccess if you are wanting a quick guide. These are based on the above Example Directory Structure. 
| Methods                         | Files                                                                            |
|---------------------------------|----------------------------------------------------------------------------------|
| `checkHTFull()`                 | [tasks/checkHTFull.php](tasks/checkHTFull.php) & [quickcheck.php](quickcheck.php)|
| `checkHTCron()`                 | [tasks/onCron.php](tasks/onCron.php)                                             |
| `checkHTon404()`                | [tasks/on404.php](tasks/on404.php)                                               |

## Installing CheckHtaccess
___
Installing CheckHtaccess is as simple as downloading and saving the [CheckHtaccess.class.php](../src/CheckHtaccess.class.php) file into your website. This can be in a directory of it's own such as `libs/CheckHtaccess/CheckHtaccess.class.php`.

The next step is to create a PHP file (or use an existing one) and. For **an additional security measure** I create a separate directory `tasks` or similar to place my CheckHtaccess related PHP scripts and backup .htaccess files and prevent access to them via my .htaccess file. For example: 

.htaccess file: 
```
RewriteEngine On

RewriteRule ^tasks - [F,L]
```

The next next step is to import CheckHtaccess: 
```php
<?php

use CheckHtaccess\CheckHtaccess;

require_once 'libs/CheckHtaccess/CheckHtaccess.class.php';
```
The `use` statement should always be near the top of your PHP code, before referring to any other files, etc. 
### Instantiating CheckHtaccess
Before using any of the CheckHtaccess methods, you must instantiate the CheckHtaccess class, similarly as shown below: 
```php
$checkHtaccess = new CheckHtaccess();
```
Please note that `$checkHtaccess` can be named anything.

## CheckHtaccess Methods
___
CheckHtaccess has three usable methods: 
* `checkHTFull()` Performs CheckHtaccess and restoration with all available options. Uses the following parameters:
  * ```$currentHtFile``` *(string)* 
  * ```$bacukupHtFile``` *(string)*
  * ```$createCurrentHFileIfNotFound``` *(boolean)*
  * ```$echoProcess``` *(boolean)*
  * ```$localLog``` *(boolean)*
  * ```$localLogFile``` *(string)*
  * ```$logErrorsToServerLogs``` *(boolean)*
* `checkHTCron()` The method to use if you are using CheckHtaccess as a cron job to check and restore .htaccess files. Uses the following parameters:
  * ```$currentHtFile``` *(string)* 
  * ```$bacukupHtFile``` *(string)*
  * ```$createCurrentHFileIfNotFound``` *(boolean)*
  * ```$localLog``` *(boolean)*
  * ```$localLogFile``` *(string)*
  * ```$logErrorsToServerLogs``` *(boolean)*
* `checkHTon404()` Performs CheckHtaccess and restoration upon a 404 error if called within your 404 page but **does not** output any echos. Uses the following parameters:
  * ```$currentHtFile``` *(string)* 
  * ```$bacukupHtFile``` *(string)*
  * ```$createCurrentHFileIfNotFound``` *(boolean)*
  * ```$localLog``` *(boolean)*
  * ```$localLogFile``` *(string)*
  * ```$logErrorsToServerLogs``` *(boolean)*
  
  **Please note** The `checkHTon404()` method may not work on web hosting packages that do not allow you to set the `ErrorDocument` directive inside of your virtualhost settings. If you can only set your `ErrorDocument 404` directive inside of your .htaccess file, which then gets erased, this method may not be suitable for your website. In such cases the `checkHTCron()` method is recommended.

### Helper Method
* `getPathForCron()` Useful if you are using a shared hosting package. This can be used to get the full/absolute path to be used with `checkHTWithCron()` and to see whether it is writable.
  * ```$htFile``` *(string)* 
* `getErrors()` Returns any errors, if any of the main methods returns `false`

## Parameters for CheckHtaccess Methods
___
CheckHtaccess predominately uses the following parameters to function: 

* ```$currentHtFile``` *(string)* 
  * The path and name of the .htaccess file. Can be relative except when using `checkHTCron()`
* ```$bacukupHtFile``` *(string)*
  * The path and name of the backup .htaccess file. Can be relative except when using `checkHTCron()`
* ```$createCurrentHFileIfNotFound``` *(boolean)*
  * Whether to create a new .htaccess file if it is not found 
* ```$echoProcess``` *(boolean)*
  * Whether to echo out the process. Only recommended for testing/debuging. Not used with `checkHTCron()` or `checkHTon404()`. Alternatively the `getErrors()` helper method can be used if any of the main methods returns `false`
* ```$localLog``` *(boolean)*
  * Whether to use a local file for logging errors and successful restorations 
* ```$localLogFile``` *(string)*
  * The path and name of the local logging file. Can be relative except when using `checkHTCron()`
* ```$logErrorsToServerLogs``` *(boolean)*
  * Whether to log errors to the system logs 
## Usage Examples
___
### `checkHTFull()`: Standard, On-the-fly and Testing CheckHtaccess 
It is recommended to test CheckHtaccess and configure it to your setup. To do this, you should use the `checkHTFull()` within a PHP file such as `tasks/checkHTFull.php`.
```php
<?php
use CheckHtaccess\CheckHtaccess;
require_once '../lib/CheckHtaccess.class.php';

$CheckHtaccess = new CheckHtaccess();

if(!$CheckHtaccess->checkHTFull('../.htaccess','bak.htaccess',true,true,true,'checkhtaccess.log',false)) {
  echo $CheckHtaccess->getErrors();
};
```
In the above example, `$CheckHtaccess->checkHTFull` enacts the `checkHTFull()` method and passes the following parameters: 
| Parameters                      | Values             |
|---------------------------------|--------------------|
| `$currentHtFile`                | ../.htaccess       |
| `$bacukupHtFile`                | bak.htaccess       |
| `$createCurrentHFileIfNotFound` | true               |
| `$echoProcess`                  | true               |
| `$localLog`                     | true               |
| `$localLogFile`                 | checkhtaccess.log  |
| `$logErrorsToServerLogs`        | false              |

Additionally, using an `if` statement, you can check for any errors if the return value is `false`. You can then manually check, or in the above example, `echo` the errors using the `$CheckHtaccess->getErrors()` helper method. 

If you are after a quick and on-the-fly checking ability of your .htaccess files, you could create a non-indexed PHP page and refer to the `tasks/checkHTFull.php` file. For example: 

`quickcheck.php`
```php 
<?php
require_once 'tasks/checkHTFull.php';
header('X-Robots-Tag: noindex,nofollow');
?>
``` 
Once done, you can simply navigate to `example.com/quickcheck.php` to perform a check. 
### `checkHTCron()`: Setting CheckHtaccess as a Cron Job 
If your web hosting service allows you to set Cron jobs then this is the **most effective** way to use CheckHtaccess. 

If you use CheckHtaccess as a Cron Job then it is usually best to use full/absolute paths when passing parameters to the `checkHTCron()`. This can be difficult to determine on shared hosting packages so the `getPathForCron()` method can assist with this. 

To set up CheckHtaccess as a Cron Job, you should first create a PHP file such as `tasks/onCron.php`, which you can refer to within your `crontab`.
```php
<?php
use CheckHtaccess\CheckHtaccess;
require_once '../lib/CheckHtaccess.class.php';

$CheckHtaccess = new CheckHtaccess();

$CheckHtaccess->checkHTCron('domains/example.com/public_html/.htaccess','domains/example.com/public_html/tasks/bak.htaccess',true,true,'domains/example.com/public_html/tasks/checkhtaccess.log',true);
```

In the above example, `$CheckHtaccess->checkHTCron` enacts the `checkHTCron()` method and passes the following parameters: 
| Parameters                      | Values                                                   |
|---------------------------------|----------------------------------------------------------|
| `$currentHtFile`                | domains/example.com/public_html/.htaccess                |
| `$bacukupHtFile`                | domains/example.com/public_html/tasks/bak.htaccess       |
| `$createCurrentHFileIfNotFound` | true                                                     |
| `$localLog`                     | true                                                     |
| `$localLogFile`                 | domains/example.com/public_html/tasks/checkhtaccess.log  |
| `$logErrorsToServerLogs`        | true                                                     |

As with the `checkHTFull()` example, you have the option of an `if` statement to check for any errors. However as this is intended to used in the background, therefore it is better to check the log to check for any errors or issues. 
#### Adding CheckHtaccess to Cron Example
This example is based on Hostinger shared hosting when setting a Cron job for CheckHtaccess every hour. Your configuration may differ. 
```sh 
0 * * * * /usr/bin/php /home/user123/domains/example.com/public_html/tasks/onCron.php
```
### `checkHTon404()`: Using CheckHtaccess on 404 Error Pages 
**Important to  note:** If you can only set your `ErrorDocument 404` directive inside of your .htaccess file then the `checkHTon404()` method may be ineffective for you. Likewise on web hosting packages that do not allow you to set the `ErrorDocument` directive inside of your virtualhost settings. In such cases the `checkHTCron()` method is recommended. 

To set up CheckHtaccess to run on 404 error pages, preferably in your `VirtualHost` or `vhost` file but optionally within your .htaccess file. This should point to your 404 page. For example: 
```
ErrorDocument 404 /404.php
```
The next step is to create a PHP file such as `tasks/on404.php`, in which you can prepare the `checkHTon404()` with the relevant parameters. For example: 
```php
<?php
use CheckHtaccess\CheckHtaccess;
require_once $_SERVER['DOCUMENT_ROOT'].'/lib/CheckHtaccess.class.php';
$CheckHtaccess = new CheckHtaccess();

$CheckHtaccess->checkHTon404('.htaccess','tasks/bak.htaccess',true,true,'tasks/checkhtaccess.log',false); 
```

In the above example, I have decided to use PHP's built-in array/variable `$_SERVER['DOCUMENT_ROOT']` as part of the CheckHtaccess class path. I chose to this do this as using relative paths within a 404 error page can sometimes be unpredictable. 

This page then enacts the `checkHTon404()` method and passes the following parameters: 
| Parameters                      | Values                   |
|---------------------------------|--------------------------|
| `$currentHtFile`                | .htaccess                |
| `$bacukupHtFile`                | tasks/bak.htaccess       |
| `$createCurrentHFileIfNotFound` | true                     |
| `$localLog`                     | true                     |
| `$localLogFile`                 | tasks/checkhtaccess.log  |
| `$logErrorsToServerLogs`        | false                    |

Once again, as with the `checkHTFull()` example, you have the option of an `if` statement to check for any errors. However the `checkHTon404()` method is intended to be unbeknown to the visitor, therefore it is better to check the log regularly check for any errors or issues. 

The last step is to refer to the `tasks/on404.php` file inside of your main `404.php` page, suchlike: 
```php 
<?php
  use CheckHtaccess\CheckHtaccess; 
  header("HTTP/1.0 404 Not Found");
  require_once $_SERVER['DOCUMENT_ROOT'].'/tasks/on404.php';
?>
``` 
Depending on your configuration you may not need to add the `use CheckHtaccess\CheckHtaccess;`  if you have instantiated it inside `tasks/on404.php`.
___
## Copyright & License 
___
Copyright 2022 Shaz Hossain ([Shazmataz](https://github.com/Shazmataz/)).

Licensed and released under [MIT](https://github.com/Shazmataz/CheckHtaccess/LICENSE).
## Contributions 
Please feel free to contribute as you wish providing you have read the [Code of Conduct](../.github/CODE_OF_CONDUCT.md)! 

Please also use [Conventional Commits](https://www.conventionalcommits.org) to help keep the project tidy! 
## Sponsorship 
If you have found this useful, please feel free to [Buy Me A Coffee](https://buymeacoffee.com/shazmataz) (I am a coffee Lover!), or alternatively donating via [PayPal](https://paypal.me/ShazHossain). However even a star is gratefully appreciated! 
