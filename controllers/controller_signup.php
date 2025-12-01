<?php

session_start();

require_once '../config/Database.php';
require_once '../models/User.php';

// Vérifier que c'est bien un POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../views/view_signup.php');
    exit();
}

$database = new bdd();
$db = $database->getConn();
$unUser = new User($db);

$username = $_POST['mail'];

$data = [
    'nom' => $_POST['nom'],
    'prenom' => $_POST['prenom'],
    'username' => $_POST['username'],
    'password' => $_POST['password'],
    'mail' => $username,
    'role' => $_POST['role']
];

$res = $unUser->selectUser($username);

if($res) {
    // Email ou username existe déjà donc directetion le signup pour qu'il réessaie un autre email
    $_SESSION['error'] = "Cet email ou ce nom d'utilisateur existe déjà";
    header('Location: ../views/view_signup.php'); 
    exit();

} elseif ($unUser->insertUser($data)) {
    // Inscription réussie donc direction login pour qu'il se connecte
    $_SESSION['success'] = "Inscription réussie ! Vous pouvez vous connecter.";
    header('Location: ../views/view_login.php'); 
    exit();

} else {
    // Erreur lors de l'insertion
    $_SESSION['error'] = "Erreur lors de l'inscription. Veuillez réessayer.";
    header('Location: ../views/view_signup.php'); // 
    exit();
}

?>