<?php $title = "Backoffice | Requests"; ?>

<h2>Demande de contribution : </h2>

<div class="userList">
  <?php if ($userList) : ?>
    <h3>Demande en attente :</h3>
    <?php foreach ($userList as $user) : ?>
      <div class="user">
        <p>Date de la demande : <?= htmlentities($user->requestDate()) ?></p>
        <p>Nom : <?= htmlentities($user->name()) ?></p>
        <p>Email : <?= htmlentities($user->email()) ?></p>
        <p>Message :</p>
        <p><?= nl2br(htmlentities($user->message())) ?></p>
      </div>
      <form class="" action="" method="post">
        <input type="hidden" name="userid" value="<?= htmlentities($user->id()) ?>">
        <input type="hidden" name="t_user" value="<?= htmlentities($token) ?>">
        <input type="submit" name="accept" value="Accepter">
      </form>
    <?php endforeach ?>
  <?php endif ?>
</div>

<div class="userList">
  <?php if ($acceptedUserList) : ?>
    <h3>Demande acceptée :</h3>
    <?php foreach ($acceptedUserList as $acceptedUser) : ?>
      <div class="user">
        <p>Nom : <?= htmlentities($acceptedUser->name()) ?></p>
        <p>Email : <?= htmlentities($acceptedUser->email()) ?></p>
        <p><strong><?= (int)$acceptedUser->confirm() === 0 ? "L'utilisateur n'a pas encore confirmé" : "Ce contributeur a confirmé" ?></strong></p>
        <hr>
      </div>
    <?php endforeach ?>
  <?php endif ?>
</div>
