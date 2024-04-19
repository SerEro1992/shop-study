<?php

namespace app\controllers;

use app\models\Breadcrumbs;
use app\models\Product;
use wfm\App;

/** @property Product $model */
class ProductController extends AppController
{
  public function viewAction()
  {
    $lang = App::$app->getProperty('language');

    $product = $this->model->get_product($this->route['slug'], $lang);


    if (!$product) {
      # если продукт не найден
      $this->error_404();
      return;
    }

    # получаем хлебные крошки
    $breadcrumbs = Breadcrumbs::getBreadcrumbs($product['category_id'], $product['title']);

    # получаем галлерею продукта
    $gallery = $this->model->get_gallery($product['id']);

    # передаем данные в View
    # необходимо поменять 2 и 3 параметр в setMeta на product['title'], $product['keywords'], $product['description'] как внесем данные в БД
    $this->setMeta($product['title'], $product['title'], $product['title'] );

    $this->set(compact('product', 'gallery', 'breadcrumbs'));


  }
}