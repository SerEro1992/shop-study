<?php
if (PHP_MAJOR_VERSION < 8){
    exit('Не поддерживается PHP версии < 8');
}

require_once dirname(__DIR__) . '/config/init.php';
require_once HELPERS . '/functions.php';
require_once  CONFIG . '/routes.php';

new \wfm\App();


