<?php require APP_ROOT . '/views/include/header.php'; ?>
  <h1><?php echo $data['title']; ?></h1>
    <ul>
        <?php foreach ($data['posts'] as $post): ?>
            <li><?php echo $post->title; ?></li>
        <?php endforeach; ?>
    </ul>
<?php  require APP_ROOT .  '/views/include/footer.php'; ?>