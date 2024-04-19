<?php

namespace app\controllers;

use app\models\Main;
use RedBeanPHP\R;
use wfm\App;
use wfm\Cache;
use wfm\Language;


/** @property Main $model */
class MainController extends AppController
{
  public function indexAction()
  {
    $lang = App::$app->getProperty('language'); #получаем язык
    $slides = R::findAll('slider'); #получаем слайды
    $products = $this->model->getHits($lang, 6); #получаем рекомендуемые товары


    $this->set(compact('slides', 'products')); #передаем слайды и рекомендуемые товары в view->main->index

    $this->setMeta(___('main_index_meta_title'), ___('main_index_meta_keywords'), ___('main_index_meta_description'));
  }
}