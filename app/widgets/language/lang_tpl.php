


<div class="dropdown d-inline-block">
    <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown">
        <img src="<?= PATH ?>/assets/img/lang/<?= \wfm\App::$app->getProperty('language')['code'] ?>.png" alt="">
    </a>
    <ul class="dropdown-menu" id="languages">
      <?php foreach ($this->languages as $key => $value): ?>
        <?php if (\wfm\App::$app->getProperty('language')['code'] == $key) continue; ?>

          <li>
              <button class="dropdown-item" data-langcode="<?= $key ?>">
                  <img src="<?= PATH ?>/assets/img/lang/<?= $key ?>.png" alt="">
                  <?= $value['title'] ?>
               </button>
          </li>
      <?php endforeach;?>

    </ul>
</div>