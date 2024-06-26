<?php

namespace app\controllers;

use app\models\Search;
use wfm\App;
use wfm\Pagination;

/** @property Search $model */
class SearchController extends AppController
{
  public function indexAction()
  {
    $s = get('s', 's');
    $lang = App::$app->getProperty('language');

    $page = get('page');
    $perpage = App::$app->getProperty('pagination');
    $total = $this->model->get_count_find_products($s, $lang);
    $pagination = new Pagination($page, $perpage, $total);
    $start = $pagination->getStart();

    $products = $this->model->get_find_products($s, $lang, $start, $perpage);


    $this->setMeta(___('tpl_search_title'), ___('main_index_meta_description'), ___('main_index_meta_keywords'));
    $this->set(compact('s', 'products', 'pagination', 'total'));


  }

}