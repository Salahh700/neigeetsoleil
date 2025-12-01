<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel ="stylesheet" href="../style/login.css">
    <title>Se connecter</title>
</head>
<body>
    <div class="login-container">

        <?php if (isset($_SESSION['success'])): ?>
            <p class="success">✅ <?= $_SESSION['success'] ?></p>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <p class="error">❌ <?= $_SESSION['error'] ?></p>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <h3>Connectez-vous !</h3>
        <form action="../controller/controller_login.php" method="POST">
            <label for="">Entrez votre nom d'utilisateur ou votre email</label>
            <input type="text" name="username" required>
            <label for="">Entrez votre mot de passe</label>
            <input type="password" name="password" required >

            <input type="input" name="valider" value="Se connecter">

        </form>

    </div>
</body>
</html>