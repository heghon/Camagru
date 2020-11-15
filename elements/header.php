<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/header.css" />
    </head>
    <header>
        <div class="header-side">
            <a href="#" class="header-box">Galerie</a>
            <a href="#" class="header-box">Montage</a>
        </div>
        <h1><a href="index.php" id="website-title">Am'Stram'Gram</a></h1>
        <div class="header-side">
            <?php if (isset($_SESSION['auth'])): ?>
                <a href="account.php" class="header-box">Compte</a>
                <a href="logout.php" class="header-box">Déconnexion</a>
            <?php else: ?>    
                <a href="register.php" class="header-box">S'inscrire</a>
                <a href="login.php" class="header-box">Se connecter</a>
            <?php endif ?>
        </div>
    </header>

    <?php if(isset($_SESSION["flash"])): ?>
        <?php foreach($_SESSION["flash"] as $type => $message): ?>
            <div class="session-alert">
                <?= $message; ?>
            </div>
        <?php endforeach; ?>
        <?php unset($_SESSION["flash"]); ?>
    <?php endif; ?>
        
</html>
