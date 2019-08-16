<? $title = "Backoffice | Articles"; ?>

<h2>Liste des articles</h2>
<?php if($message) echo "<p class='alert success'>" . $message . "</p>"?>

<div class="posts_container">
  <?php foreach($postList as $post) : ?>
    <hr>
    <div class="post">
      <div class="post_card">
        <h3><?= $post->id() ?> | <?= $post->title() ?></h3>

        <p>Par <?=$post->author()?> <em>Publié le <?= $post->publishDate() ?><?= ($post->updateDate()) ? " - Modifié le " . $post->updateDate() : ""; ?></em></p>

        <h4><?= $post->kicker() ?></h4>

        <?php if($post->countComment()) :?>
        <p><em><?= $post->countComment() ?> <?= $post->countComment() == 1 ? "commentaire" : "commentaires" ?></em></p>
        <?php endif ?>
      </div>
      <div class="post_actions">
          <p>Modifier</p>
          <p><a href="?delete=<?= $post->id() ?>&token=<?= $token ?>">Supprimer</a></p>
      </div>
    </div>
  <?php endforeach ?>
</div>