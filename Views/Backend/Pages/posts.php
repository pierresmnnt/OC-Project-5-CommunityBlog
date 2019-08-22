<? $title = "Backoffice | Articles"; ?>

<h2>Liste des articles</h2>
<?php if($message) : ?>
  <p class='alert success'><?= $message ?></p>
<?php endif ?>

<div class="posts_container">
  <?php foreach($postList as $post) : ?>
    <hr>
    <div class="post">
      <div class="post_content">
        <?php if($post->image()) : ?>
          <div class="post_img">
            <img src="../Public/Content/Post-<?= $post->id() ?>/<?= $post->image() ?>" alt="">
          </div>
        <?php endif ?>
        <div class="post_card">
          <h3><?= $post->id() ?> | <?= $post->title() ?></h3>

          <p>Par <?=$post->name()?> <em>Publié le <?= $post->publishDate()->format('d/m/Y à H\hi') ?><?= ($post->updateDate()) ? " - Modifié le " . $post->updateDate()->format('d/m/Y à H\hi') : ""; ?></em></p>

          <h4><?= $post->kicker() ?></h4>
        </div>
      </div>
      <div class="post_actions">
          <p><a href="?page=edit&postid=<?= $post->id() ?>">Modifier</a></p>
          <p><a href="?delete=<?= $post->id() ?>&token=<?= $token ?>">Supprimer</a></p>
          <p><a href="../Public/index.php?page=article&id=<?= $post->id() ?>">Voir l'article</a></p>
      </div>
    </div>
  <?php endforeach ?>
</div>
