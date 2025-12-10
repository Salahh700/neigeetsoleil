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

        <p>Nom :<?php echo $each['nomGite'] ?></p><br>
        <p>Adresse: <?php echo $each['adresseGite'] ?></p><br>
        <p>Ville : <?php echo $each['villeGite'] ?></p><br>
        <p>Code Postal : <?php echo $each['codePostalGite'] ?></p><br>
        <p>Description : <?php echo $each['descriptionGite'] ?></p><br>
        <p>Capacité : <?php echo $each['capaciteGite'] ?></p><br>
        <p> Prix de la nuitée : <?php echo$each['prixNuitGite'] ?> €</p><br>
        <?php if( $each['disponibiliteGite']==1 ){
            echo "<p> Disponible à la réservation ✅ </p>";
        } else{
            echo "<p> Indisponible à la réservation ❌ </p>";
        }?><br>

        <form method="POST" action="../../controllers/proprietaire/deleteGiteController.php">
            <input type="hidden" name="id" value="<?php echo $each['idGite']?>">
            <button type="submit" onclick="return confirm('Confirmer la suppression ?')">
                🗑️ Supprimer
            </button>
        </form> 
        <form method="POST" action="updateGite.php">
            <input type="hidden" name="id" value="<?php echo $each['idGite']?>">
            <button type="submit" >
                📝 Modifier
            </button>
        </form> 
    </div>
    <?php
    }
    ?>
    <p> Ajouter un nouveau logement <a href="addGite.php">➕</a></p>

</body>
</html>