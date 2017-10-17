<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 16.10.2017
 * Time: 16:52
 */

namespace App;


class View
{
    use Magic;

    /**
     * В цикле создаються переменные вида $array['key']=$value => $key=$value
     * @param string $template
     * @return string Возвращает строку с шаблоном
     */
    private function render(string $template)
    {
        ob_start();
        foreach ($this->_data as $name => $value) {
            $$name = $value;
        }
        include $_SERVER['DOCUMENT_ROOT'] . $template;
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }

    /**
     * @param string $template Путь к шаблону
     */
    public function display(string $template)
    {
        print $this->render($template);
    }
}