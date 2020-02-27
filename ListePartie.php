<?php
include("bdd.php");

if(!isset($_COOKIE["id"])) header("Location: Connexion.php");

$isAdmin = False;

$bd = connectBD("Siam");
if(isAdmin($bd, $_COOKIE["id"])){
    $isAdmin = True;
}

?>

<!DOCTYPE html>
<html lang="fr">
    <?php include("header.php"); ?>
<body>
    <?php if($isAdmin) include("MenuHtmlAdmin.php"); else include("MenuHtmlUtilisateur.php"); ?>
    <h1>Liste partie</h1>
    
</body>
</html>