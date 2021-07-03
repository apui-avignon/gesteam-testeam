<?php
class Login extends Controller
{
    public function log($course_id)
    {
        $configs = include('config.php');
        include_once($configs['root'].'../'.$configs['teacher_app_name'].'/helper/CAS-1.3.5/CAS.php');
        phpCAS::client(CAS_VERSION_2_0, $configs['cas'], 443, '/cas');
        phpCAS::setLang(PHPCAS_LANG_FRENCH);
        phpCAS::setNoCasServerValidation();
        phpCAS::forceAuthentication();
        $auth = phpCAS::checkAuthentication();

        if ($auth) {
            $_SESSION['course_id'] = $course_id;
            $_SESSION['connected'] = true;
            $_SESSION['user'] = phpCAS::getUser();
            header('Location: https://'.$configs['server_name'].'/' . $configs['app_name'] . '/index.php?p=home');
        }
    }
}
