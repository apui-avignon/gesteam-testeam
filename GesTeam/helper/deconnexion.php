<?php
$configs = include('../config.php');
include_once($configs['root'].'../'.$configs['teacher_app_name'].'/helper/CAS-1.3.5/CAS.php');

phpCAS::client(CAS_VERSION_2_0, $configs['cas'], 443, '/cas');

phpCAS::setLang(PHPCAS_LANG_FRENCH);
phpCAS::logoutWithUrl('https://'.$configs['server_name'].'/'.$configs['app_name'].'/index.php');
?>
