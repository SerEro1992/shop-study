<?php

namespace app\controllers;

use app\models\AppModel;
use app\models\Wishlist;
use app\widgets\language\Language;
use wfm\{App, Controller};
use RedBeanPHP\R;

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


    # получаем категории товаров
    $categories = R::getAssoc("SELECT c.*, cd.* FROM category c 
                        JOIN category_description cd
                        ON c.id = cd.category_id
                        WHERE cd.language_id = ?", [$lang['id']]);

    # добавляем категории в свойство контейнер
    App::$app->setProperty("categories_{$lang['code']}", $categories);


    # получаем id товаров в избранном
    App::$app->setProperty('wishlist', Wishlist::get_wishlist_ids());


  }

}