<?php

namespace app\controllers;

use app\controllers\AppController;
use app\models\Page;
use wfm\App;

/** @property  Page $model  */
class PageController extends AppController
{
  public function viewAction()
  {
    $lang = App::$app->getProperty('language');
    $page = $this->model->get_page($this->route['slug'], $lang);

    if(!$page){
      $this->error_404();
      return;
    }

    ## Подключаем мета теги
    #$this->setMeta($page['title'], $page['description'], $page['keywords']);
    $this->setMeta($page['title'], $page['title'], $page['title']);
    $this->set(compact('page'));

  }

}