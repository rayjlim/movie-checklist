<?php

define('BASEDIR', pathinfo(__FILE__, PATHINFO_DIRNAME));
require_once BASEDIR . '/lib/core/fundead.php';
Fundead::init();

Fundead::run();
?>