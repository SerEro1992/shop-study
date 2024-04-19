<?php

namespace wfm;

abstract class Controller
{
  public array $data = [];
  public array $meta = ['title' => '', 'keywords' => '', 'description' => ''];
  public false|string $layout = '';
  public string $view = '';
  public object $model;


  public function __construct(public $route = [])
  {

  }

  public function getModel()
  {
    $model = 'app\models\\' . $this->route['admin_prefix'] . $this->route['controller'];
    if (class_exists($model)) {
      $this->model = new $model($this->route);
    }
  }

  public function getView()
  {
    $this->view = $this->view ?: $this->route['action'];
    (new View($this->route, $this->layout, $this->view, $this->meta))->render($this->data);

  }

  public function set($data)
  {
    $this->data = $data;
  }

  public function setMeta($title = '', $keywords = '', $description = '')
  {
    $this->meta = [
      'title' => $title,
      'keywords' => $keywords,
      'description' => $description
    ];
  }

  public function isAjax(): bool
  {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
  }

  public function loadView($view, $params = [])
  {
    extract($params);
    $prefix = str_replace('\\', '/', $this->route['admin_prefix']);
    require APP . "/views/{$prefix}{$this->route['controller']}/{$view}.php";
    exit();
  }


}