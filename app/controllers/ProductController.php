<?php

namespace app\controllers;

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
      throw new \Exception('Товар по запросу {$this->route["slug"]} не найден', 404);
    }

    $gallery = $this->model->get_gallery($product['id']);

    # передаем данные
    $this->setMeta($product['title'], $product['keywords'], $product['description']);
    $this->set(compact('product', 'gallery'));


  }
}