<?php

namespace App\Controllers\Frontend;

use App\Models\Message;
use App\Models\MessageManager;
use App\Models\Post;
use App\Models\PostManager;
use App\Models\Comment;
use App\Models\CommentManager;

/**
 * Main controller, action to do when called by the router
 */
class FrontendController extends Controller
{
    //------------------------------------------------------------------------------
    // Home Page Methods
    //------------------------------------------------------------------------------
    /**
     * Actions when in home page :
     *
     * New object Message is created with data from contact form.
     * Send function in MessageManager will send the object.
     *
     * Require content of the page.
     */
    public function home()
    {
        if (isset($_POST['send'])) {
            $name = htmlspecialchars((string)$_POST['name']);
            $email = htmlspecialchars((string)$_POST['email']);
            $messageContent = htmlspecialchars((string)$_POST['message']);

            $data = [
        'name' => $name,
        'email' => $email,
        'message' => $messageContent,
      ];

            $newMessage = new Message($data);

            if ($newMessage->isValid()) {
                $messageManager = new MessageManager;
                ;
                if ($messageManager->send($newMessage)) {
                    $message = 'Votre message a bien été envoyé.';
                } else {
                    $message = 'Une erreur est survenue.';
                }
            } else {
                $errors = $newMessage->errors();
            }
        }

        ob_start();
        require_once $this->getViewPath().'home.php';
        $content = ob_get_clean();
        require_once $this->getTemplatePath();
    }


    //------------------------------------------------------------------------------
    // Blog Page Methods
    //------------------------------------------------------------------------------
    /**
     * Actions when in blog page :
     *
     * Call PDO and create an object PostManager
     * Call list of posts with getList function
     *
     * Require content of the page.
     */
    public function blog()
    {
        $postManager = new PostManager($this->getDb());

        $postList = $postManager->getList();

        ob_start();
        require_once $this->getViewPath() .'blog.php';
        $content = ob_get_clean();
        require_once $this->getTemplatePath();
    }


    //------------------------------------------------------------------------------
    // Unique Post Page Methods
    //------------------------------------------------------------------------------
    /**
     * Action when in post page :
     *
     * Get the post with id in param.
     * Create object Comment with data from comment form.
     * Check if data are valid to send the comment with CommentManager object.
     *
     * Require content of the page.
     *
     * @param  int $id article id from get
     */
    public function post($id)
    {
        $manager = new PostManager($this->getDb());
        $post = $manager->getUnique($id);
        if (!$post) {
            throw new \Exception("Cette page n'existe pas");
        }
        $commentManager = new CommentManager($this->getDb());

        if (isset($_POST['add'])) {
            $author = htmlspecialchars((string)$_POST['author']);
            $content = htmlspecialchars((string)$_POST['comment']);
            $data = [
        'idArticle' => $id,
        'author' => $author,
        'content' => $content,
      ];


            $newComment = new Comment($data);

            if ($newComment->isValid()) {
                $commentManager->save($newComment);
                $message = "Votre commentaire à bien été envoyé. Il sera vérifié avant d'être mis en ligne";
            } else {
                $errors = $newComment->errors();
            }
        }
        /*
         * Call for getList function in CommentManager
         * Params for function : id article, type of comment (null for all, checked for 'checked' comments only, 'unchecked' for unchecked comments only)
         */
        $listofcomments = $commentManager->getList($post->id(), 'checked');

        ob_start();
        require_once $this->getViewPath() .'post.php';
        $content = ob_get_clean();
        require_once $this->getTemplatePath();
    }
}
