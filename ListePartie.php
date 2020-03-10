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
            if($isAdmin){
                $listPartie = listPartie($bd);
                $cpt = 0;
                while($row = $listPartie->fetch(PDO::FETCH_ASSOC))
                {
                    if($row["estPartie"] == "0")
                        echo "<p>N°".$row["idGrille"].": Partie en attente d'un joueur <a href=\"Rejoindre.php?grille=".$row["idGrille"]."\"  style=\"color:green;\">Rejoindre</a></p>";
                    if($row["estPartie"] == "2")
                        echo "<p>N°".$row["idGrille"].": Partie termine <a href=\"SupprimerPartie.php?grille=".$row["idGrille"]."\" ><img src=\"ressources/croix.png\" height=\"1%\" width=\"1%\" alt=\"croix\"> </a></p>";
                    $cpt++;
                }
                if($cpt == 0) echo "<p>Il n'y a pas de partie en attente.</p>";
            }
            else{
                $listPartie = listPartieId($bd, $_SESSION["id"]);
                $cpt = 0;
                while($row = $listPartie->fetch(PDO::FETCH_ASSOC))
                {
                    echo "<p>N°".$row["idGrille"].": Partie en attente d'un joueur <a href=\"Rejoindre.php?grille=".$row["idGrille"]."\"  style=\"color:green;\">Rejoindre</a> </p>";
                    $cpt++;
                }
                if($cpt == 0) echo "<p>Il n'y a pas de partie en attente.</p>";
            }
        ?>
    </div>
</body>
</html>