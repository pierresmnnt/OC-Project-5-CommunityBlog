<?php

namespace App\Controllers\Backend;

use App\Controllers\Controller;

use App\Models\DBFactory;
use App\Models\User;
use App\Models\UserManager;

/**
 *
 */
class Auth extends Controller
{
  //------------------------------------------------------------------------------
  // Login Page Methods
  //------------------------------------------------------------------------------
  /**
  * Call for login page
  * Check if user infos are valid and create session
  */
  public function login()
  {
      if (isset($_POST['login'])) {
          $data = [
      'email' => htmlentities($_POST['email']),
      'password' => htmlentities($_POST['password'])
    ];
          // Save inputs in session
          $_SESSION['inputs'] = $_POST;

          $db = DBFactory::getPDO();
          $user = new User($data);
          $userManager = new UserManager($db);

          // Use Ip (see connect method for brute force attack defense)
          $ip = $_SERVER['REMOTE_ADDR'];
          $connect = $userManager->connect($ip);

          // User will be ban is there are more than 3 failed connections.
          if ($connect <= 3) {
              if ($connect == 3) {
                  $warning = "Attention, il vous reste un seul essai. <a href='?page=resetpass'>Mot de passe oublié ?</a>";
              }

              if ($user->isValid()) {
                  // get user from database with email from form
                  $loggingUser = $userManager->getUserByMail($user->email());

                  if ($loggingUser) {
                      $passwordCheck = password_verify($user->password(), $loggingUser->password());
                      if ($passwordCheck) {
                          // Restore failed connection to 0
                          if ($connect) {
                              $userManager->restoreConnect($ip, $loggingUser->id());
                          }

                          // If user is confirmed (has changed his password once)
                          if ($loggingUser->confirm() == 1) {
                              session_start();
                              $token = bin2hex(random_bytes(32));
                              $_SESSION['t_user'] = $token;
                              $_SESSION['role'] = 'admin';
                              $_SESSION['id'] = $loggingUser->id();
                              $_SESSION['inputs'] = [];
                              header('Location: ?page=home');
                          }
                          // If user is new and has not changed his password yet
                          elseif ($loggingUser->confirm() == 0) {
                              session_start();
                              $token = bin2hex(random_bytes(32));
                              $_SESSION['t_user'] = $token;
                              $_SESSION['role'] = 'guest';
                              $_SESSION['id'] = $loggingUser->id();
                              $_SESSION['inputs'] = [];
                              header('Location: ?page=newpass');
                          }
                      } else {
                          $prohib = "Informations de connection éronées (mdp)";
                          // Save the failed connection into dabatase
                          $failconnect = $userManager->failConnect($ip, $loggingUser->id());
                      }
                  } else {
                      $prohib = "Informations de connection éronées";
                  }
              } else {
                  $errors = $user->errors();
              }
          } else {
              throw new \Exception("Votre accès est bloqué. Contactez l'administrateur.");
          }
      }

      ob_start();
      require_once $this->getViewPath().'login.php';
      $content = ob_get_clean();
      require_once $this->getTemplatePath();
  }

}