<?php
include("bdd.php");

if(!isset($_SESSION["id"])) header("Location: Connexion.php");

$bd = connectBD("Siam");
$isAdmin = isAdmin($bd, $_SESSION["id"]);

$value = NULL;

$sql = "INSERT INTO Grille(tour, estPartie, estSelectPion) Values (1, 0, 0)"; // 0 false et 1 true
if(modifieTable($bd, $sql))
{
    $value = "success";
    $idGrille = idDernierePartie($bd);
    $sql = "INSERT INTO Participe(idJoueur, idGrille, numJoueur) Values (".$_SESSION["id"].", ".$idGrille.", 1)";
    if(modifieTable($bd, $sql)) $value = "success";
    else $value = "error2";
}
else $value = "error";

?>

<!DOCTYPE html>
<html lang="fr">
    <?php include("header.php"); ?>
<body>
    <?php if($isAdmin) include("MenuHtmlAdmin.php"); else include("MenuHtmlUtilisateur.php"); ?>
    <h1>Création de partie</h1>
    <?php  
        if($value == "success") echo "<p style=\"color:green;\">La partie a été créé !!</p>"; 
        else if($value == "error") echo "<p style=\"color:red;\">Erreur sur la création de la partie</p>";
        else if($value == "error2") echo "<p style=\"color:red;\">Erreur sur l'ajout du joueur dans la partie</p>"; 
    ?>
</body>
</html>