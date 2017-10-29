<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 19.10.2017
 * Time: 17:46
 */

namespace App\Controllers;

use App\Exceptions\Error404;
use App\Models\Article;
use App\View;

class Main extends \App\Controller
{
    protected $mainPage;

    public function __construct()
    {
        parent::__construct();
        $this->mainPage = new View('/App/templates/main_page.phtml');
    }

    protected function actionIndex()
    {
        $this->mainPage->news = Article::findAll();
        View::display($this->header, $this->mainPage, $this->sideBar, $this->footer);
    }

    /**
     * @param int $id
     * @throws Error404
     */
    protected function actionOneArticle(string $link, int $id)
    {
        $this->mainPage = new View('/App/templates/one_article.phtml');
        $this->mainPage->article = Article::findOneArticle($link, $id);
        if (empty($this->mainPage->article)) {
            throw new Error404();
        }
        View::display($this->header, $this->mainPage, $this->sideBar, $this->footer);
    }

    protected function actionOneCategory(string $link)
    {
        $this->mainPage->news = Article::findByCategory($link);
        View::display($this->header, $this->mainPage, $this->sideBar, $this->footer);
    }
}