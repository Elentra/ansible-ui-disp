<?php
/* Environment */
require_once("config.php");

/* Check authentication */
require_once("core/auth.container.php");



/* Additional display variables */
$viewIndexHover = "active";

/* Page template processing */
require_once("template/index.tpl");
?>
