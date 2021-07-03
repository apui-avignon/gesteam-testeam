<?php
class Login extends Controller
{

    public function index()
    {
        $this->render('index');
    }

    public function log()
    {
        $configs = include('config.php');
        include_once($configs['root'].'helper/CAS-1.3.5/CAS.php');
        phpCAS::client(CAS_VERSION_2_0, $configs['cas'], 443, '/cas');
        phpCAS::setLang(PHPCAS_LANG_FRENCH);
        phpCAS::setNoCasServerValidation();
        phpCAS::forceAuthentication();
        $auth = phpCAS::checkAuthentication();

        if ($auth) {
            $_SESSION['connected'] = true;
            $_SESSION['user'] = phpCAS::getUser();
            header('Location: https://' . $configs['server_name'] . '/' . APP_NAME . '/index.php?p=home');
        }
    }
}
