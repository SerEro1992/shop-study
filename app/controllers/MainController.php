<?php

namespace app\controllers;

use app\models\Main;
use RedBeanPHP\R;
use wfm\Controller;

/** @property Main $model */
class MainController extends Controller
{
  public function indexAction()
  {
    $names = $this->model->getNames();
    $one_name = R::getRow('SELECT * FROM name WHERE id = 2');


    $this->setMeta('Главная страница', 'Ключевые слова', 'Описание страницы',);
    $this->set(['test' => 'SerEro']);
    $this->set(compact('names'));
  }
}