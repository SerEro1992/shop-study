<?php

namespace app\controllers;

use app\models\Breadcrumbs;
use app\models\Category;
use RedBeanPHP\R;
use wfm\App;
use wfm\Pagination;

/** @property Category $model */
class CategoryController extends AppController
{

  public function viewAction()
  {
    # получаем язык
    $lang = App::$app->getProperty('language');

    #получаем категории
    $category = $this->model->getCategory($this->route['slug'], $lang);

    if (!$category) {
      $this->error_404();
      return;
    }

    # получаем хлебные крошки
    $breadcrumbs = Breadcrumbs::getBreadcrumbs($category['id']);

    # получаем id
    $ids = $this->model->getIds($category['id']);
    $ids = !$ids ? $category['id'] : $ids . $category['id'];


    #получаем пагинацию $perpage  берем из config->params.php
    $page = get('page');
    $perpage = App::$app->getProperty('pagination');
    $total = $this->model->get_count_product($ids);
    $pagination = new Pagination($page, $perpage, $total);
    $start = $pagination->getStart();


    # получаем продукты
    $products = $this->model->getProducts($ids, $lang, $start, $perpage);


    # передаем мета данные
    # надо исправить после добавления данных в бд $category['description'] , $category['keywords']
    $this->setMeta($category['title'], 'Описание категории', 'Ключевые слова');

    # передаем данные в view
    $this->set(compact('products', 'category', 'breadcrumbs', 'pagination', 'total'));
  }

}

