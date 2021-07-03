<?php
abstract class ComponentController extends Controller
{
    public function __construct()
    {
        session_start();
    }

    public function render(string $fichier, array $data = [])
    {
        extract($data);
        ob_start();
        require_once(ROOT . 'views/' . strtolower(get_class($this)) . '/' . $fichier . '.php');
        $content = ob_get_clean();
        require_once(ROOT . 'views/layouts/component.php');
    }
}
