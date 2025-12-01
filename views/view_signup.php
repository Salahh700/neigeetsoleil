<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel ="stylesheet" href="../style/signup.css">
    <title>S'inscrire</title>
</head>
<body>
    <div class="signup-container"> 

        <?php if (isset($_SESSION['error'])): ?>
            <p class="error">❌ <?= $_SESSION['error'] ?></p>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <p class="success">✅ <?= $_SESSION['success'] ?></p>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

            <h3>Inscrivez-vous !</h3>

        <form action="../controller/controller_sign_up.php" method="post">
            <input type="text" name="prenom" placeholder="Prénom" required/><br><br>    
            <input type="text" name="nom" placeholder="Nom" required/><br><br>
            <input type="text" name="username" placeholder="Nom d'utilisateur" required/><br><br>
            <input type="password" name="password" placeholder="Mot de passe" required/><br><br>
            <input type="email" name="mail" placeholder="Email de l'utilisateur" required/><br><br>
            <label for="">Rôle : </label><br>
            <select name="role" id="">
                <option value="voyageur">Voyageur</option>
                <option value="proprietaire">Propriétaire</option>
            </select><br><br>
            <input type="reset" value="Annuler"/><br><br>
            <input type="submit" name="valider" value="Enregistrer"><br><br>    

            <p>Déjà un compte ? <a href="view_login.php">Se connecter</a></p>
        </form>
    </div>
</body>
</html>