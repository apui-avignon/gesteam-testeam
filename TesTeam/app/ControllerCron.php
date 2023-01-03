<?php
require_once(ROOT . 'app/Model.php');

abstract class ControllerCron extends Model
{
    public function loadModel(string $model)
    {
        require_once(ROOT . 'models/' . $model . '.php');
        $this->$model = new $model();
    }

    public function render(string $fichier, array $data = [])
    {
        extract($data);
        ob_start();
        require_once(ROOT . 'views/' . strtolower(get_class($this)) . '/' . $fichier . '.php');
        $content = ob_get_clean();
        require_once(ROOT . 'views/layouts/default.php');
    }

    public function loadHelper(string $helper)
    {
        require_once(ROOT . 'helper/' . $helper . '.php');
        $this->$helper = new $helper();
    }
}
