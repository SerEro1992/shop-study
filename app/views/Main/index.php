<h1>Hello from index</h1>


<?php if (!empty($names)): ?>
  <?php foreach ($names as $name): ?>
        <p> <?= $name->name ?> </p>
  <?php endforeach; ?>
<?php endif; ?>

