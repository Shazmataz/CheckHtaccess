<?php
/* Example 404.php page enacting the CheckHtaccess->checkHTon404 method */ 
  use CheckHtaccess\CheckHtaccess; /* Depending on your configuration you may not need this if it is in /tasks/on404.php */
  header("HTTP/1.0 404 Not Found");
  require_once $_SERVER['DOCUMENT_ROOT'].'/tasks/on404.php'; /* Enacts the checkHTon404 method with your parameters */
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>404</title>
</head>
<body>
  <h1>Page not found!</h1>
</body>
</html>