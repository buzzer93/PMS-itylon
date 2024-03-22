<?php
$pass = '$2y$12$x4TKuSOcuprYk.wT.IQRbeLX/j5CXC5EvwGEte2pTNIWWi3PwWtuW';
$error = '';
if (!empty($_POST['password'])) {
    if (password_verify($_POST['password'], $pass)) {
        session_start();
        $_SESSION['connected'] = 1;
        header('Location: ./index.php');
    } else {
        $error = 'Mauvais mot de passe';
    }
}
require_once '../functions/auth.php';

if (isConnected()) {
    header('Location: ./index.php');
    exit();
}
require_once './assets/elements/header.php';

?>

<section class="section login">
    
    <form class="login-form container" action="" method="post">
        <h1 class="section-title"> Connection </h1>
        <input type="password" name="password" class="form-input" placeholder="Mot de passe" />
        <?php if ($error) : ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif ?>
        <button class="login-btn btn" type="submit">Se connecter</button>
    </form>
</section>

<?php
require_once './assets/elements/footer.php';

?>