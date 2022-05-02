<?php
/** tasks/checkHTFull.php - checkHTFull example 
 * Performs the .htaccess check and restoration with all available options.
 **/

use CheckHtaccess\CheckHtaccess;
require_once '../lib/CheckHtaccess.class.php';

$CheckHtaccess = new CheckHtaccess();

if(!$CheckHtaccess->checkHTFull('../.htaccess','bak.htaccess',true,true,true,'checkhtaccess.log',false)) {
  echo $CheckHtaccess->getErrors();
};
?> 