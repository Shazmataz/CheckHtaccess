<?php
/**
 * tasks/onCron.php - checkHTCron example 
 * Use this file to easily predefine the checkHTCron parameters when setting up CheckHtaccess as a Cron job 
 * 
 * Cron job example (every hour): 
 * 0 * * * * /usr/bin/php /home/user123/domains/example.com/public_html/tasks/onCron.php
 * This example is based on Hostinger shared hosting. Your configuration may differ. 
 * 
 * Use the getPathForCron() helper method to get the full/absolute path to be used
 **/

use CheckHtaccess\CheckHtaccess;
require_once '../lib/CheckHtaccess.class.php';
$CheckHtaccess = new CheckHtaccess();

$CheckHtaccess->checkHTCron('domains/example.com/public_html/.htaccess','domains/example.com/public_html/tasks/bak.htaccess',true,true,'domains/example.com/public_html/tasks/checkhtaccess.log',true);
?> 