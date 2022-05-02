<?php
/**
 * tasks/on404.php - checkHTon404 example 
 * Use this file to easily predefine the checkHTon404 parameters when called from 404.php
 * 
 * Please note: The checkHTon404() method may not work on web hosting packages that do not allow you to set the "ErrorDocument" 
 * directive inside of your virtualhost settings. If you can only set your "ErrorDocument 404" directive inside of your .htaccess 
 * file, which then gets erased, this method may not be suitable for your website. In such cases the `checkHTCron()` method is 
 * recommended.
**/
use CheckHtaccess\CheckHtaccess;
require_once $_SERVER['DOCUMENT_ROOT'].'/lib/CheckHtaccess.class.php';
$CheckHtaccess = new CheckHtaccess();

$CheckHtaccess->checkHTon404('.htaccess','tasks/bak.htaccess',true,true,'tasks/checkhtaccess.log',false); 
?> 