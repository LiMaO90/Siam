<?php
include("bdd.php");

if(!isset($_SESSION["id"])) header("Location: Connexion.php");

$isAdmin = False;

$bd = connectBD("Siam");
if(isAdmin($bd, $_SESSION["id"])){
    $isAdmin = True;
}

?>

<!DOCTYPE html>
<html lang="fr">
    <?php include("header.php"); ?>
<body>
    <?php if($isAdmin) include("MenuHtmlAdmin.php"); else include("MenuHtmlUtilisateur.php"); ?>
    <h1>Liste partie</h1>
    <div>
        <?php
            $listPartie = listPartie($bd, $_SESSION["id"]);
            $cpt = 0;
            while($row = $listPartie->fetch(PDO::FETCH_ASSOC))
            {
                echo "<p>Partie en attente d'un joueur: <a href=\"Rejoindre.php?Grille=".$row["idGrille"]."\"  style=\"color:green;\">Rejoindre</a> </p>";
                $cpt++;
            }
            if($cpt == 0) echo "<p>Il n'y a pas de partie en attente.</p>";
        ?>
    </div>
</body>
</html>