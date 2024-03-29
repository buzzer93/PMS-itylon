<?php
require '../vendor/autoload.php';

use App\App;

session_start();

$auth = App::getAuth();
$error = false;
if($auth->get_user() !== null){
    header('Location: ./index.php?');
    exit();
}
if (!empty($_POST)) {
$user = $auth->login($_POST['username'],$_POST['password']);
if($user){
    header('Location: ./index.php?');
    exit();
}
$error = true;
}
require_once './assets/elements/header.php';
?>

<section class="section login">
    
    <form class="login-form container" action="" method="post">
        <h1 class="section-title"> Connection </h1>
        <input type="text" name="username" class="form-input" placeholder="Nom d'utilisateur" />
        <input type="password" name="password" class="form-input" placeholder="Mot de passe" />
        <?php if ($error) : ?>
            <div class="alert-danger">Nom d'utilisateur ou mot de passe incorrect</div>
        <?php endif ?>
        <button class="login-btn btn" type="submit">Se connecter</button>
    </form>
</section>

<?php
require_once './assets/elements/footer.php';

?>