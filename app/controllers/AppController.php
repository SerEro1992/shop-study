<?php

namespace app\controllers;
use app\models\AppModel;
use app\widgets\language\Language;
use wfm\{App, Controller};

class AppController extends Controller
{

  /**
   * @throws \Exception
   */
  public function __construct($route)
  {
    parent::__construct($route);
    new AppModel();

    #получаем языки в массиве
    App::$app->setProperty('languages', Language::getLanguages());

    #получаем текущий язык
    App::$app->setProperty('language', Language::getLanguage(App::$app->getProperty('languages')));


    # получаем переводные фразы
    $lang = App::$app->getProperty('language');
    \wfm\Language::load($lang['code'], $this->route);








  }

}