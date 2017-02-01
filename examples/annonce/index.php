<?php
set_time_limit(0);
ignore_user_abort(1);

$start = microtime(false);
include_once("bootstrap.php");
$cfDb = new cf\Db();

$router = new RouterController();
$router->process($_SERVER['REQUEST_URI']);

// Process view
$router->processView();
echo microtime(false) - $start;
ob_end_flush();
exit(0);