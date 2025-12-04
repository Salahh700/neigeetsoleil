<?php
session_start();

require_once '../../config/Database.php';
require_once '../../models/Gite.php';

$database= new bdd();
$db=$database->getConn();
$unGite= new Gite($db);

if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'proprietaire'){
    $_SESSION['error']="Accès refusé ❌";
    header('location: login.php');
    exit();
}

$res=$unGite->selectGitesByUser($_SESSION['idUser']);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Neige & Soleil - Mes Logements</title>
</head>
<body>
    <div class='navbar'>
        <img class="logo" src="../images/logo.png" alt="">
        <ul>
            <li><a href="homeProprietaire.php">Accueil</a></li>
            <li><a href="mesGites.php">Mes logements</a></li>
            <li><a href="profilProprietaire.php">Profil</a></li>
            <li><a href="../../controllers/auth/LogoutController.php">Déconnexion</a></li>
        </ul>
    </div>

    <div class="temporaly-message">
        <?php if (isset($_SESSION['success'])): ?>
            <p class="success">✅ <?= $_SESSION['success'] ?></p>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <p class="error">❌ <?= $_SESSION['error'] ?></p>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
    </div>

    <h3>Mes logements <?php echo $_SESSION['username'] ?> </h3>

    <?php foreach($res as $each){
     ?>
    <div class="each-gite">

        <p><?php $each['nomGite'] ?></p>
        <p><?php $each['adresseGite'] ?></p>
        <p><?php $each['villeGite'] ?></p>
        <p><?php $each['codePostalGite'] ?></p>
        <p><?php $each['descriptionGite'] ?></p>
        <p><?php $each['capaciteGite'] ?></p>
        <p><?php $each['prixNuitGite'] ?> €</p>
        <p><?php $each['disponibiliteGite'] ?></p>

        <p>🗑️ Supprimer <a href="deleteGiteController.php?id=<?php echo $res['idGite']?>"></a> </p>

    </div>
    <?php
    }
    ?>
    <p> Ajouter un nouveau logement <a href="addGite.php">➕</a></p>

</body>
</html>