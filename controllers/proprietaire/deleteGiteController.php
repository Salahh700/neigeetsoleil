<?php  

session_start();

require_once '../../config/Database.php';
require_once '../../models/Gite.php';

if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'proprietaire'){
    $_SESSION['error']="Accès refusé ❌";
    header('location: login.php');
    exit();
}

$database= new bdd();
$db=$database->getConn();
$unGite= new Gite($db);

$idGite=$_GET['id'];

if($unGite->deleteGite($idGite)){
    $_SESSION['success']="✅ Logement bien supprimé";
    header('location: ../../views/mesGites.php');
    exit();

}else{
    $_SESSION['error']="❌ Echec de la suppression";
    header('location: ../../views/mesGites.php');
    exit();
    
}

?>