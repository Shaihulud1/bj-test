<?php
namespace core;


abstract class AppController
{
    public $layout = "default";

    protected function renderView(string $requestView, array $vars = []): void
    {
        $requestView .= ".php";
        $viewFolder = __DIR__ . "/../view/";

        if (!file_exists($viewFolder.$requestView)) {
            return;
        }
        ob_start();
        require $viewFolder.$requestView;
        $content = ob_get_contents();
        ob_end_clean();
        $vars['content'] = $content;
        extract($vars);
        require "$viewFolder/layout/$this->layout.php";
    }

    protected function renderJson(array $ar2Json): void
    {
        header('Content-Type: application/json');
        print_R(json_encode($ar2Json));
        die;
    }
}