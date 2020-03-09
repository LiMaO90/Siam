<?php
    include("bdd.php");

    if(!isset($_SESSION["id"]) && !isAdmin($_SESSION["id"])) header("Location: ListePartie.php");

    if(isset($_GET["grille"])){
        $bd = connectBD("Siam");
        $sql = "DELETE From Participe Where idGrille = ".$_GET["grille"];
        modifieTable($bd, $sql);

        $sql = "UPDATE Pion Set direction = -100 Where idPion In (Select idPion From Joue where idGrille = ".$_GET["grille"].")";
        modifieTable($bd, $sql);

        $sql = "DELETE From Joue Where idGrille = ".$_GET["grille"];
        modifieTable($bd, $sql);

        $sql = "DELETE From Pion Where direction = -100";
        modifieTable($bd, $sql);

        $sql = "DELETE From Grille Where idGrille = ".$_GET["grille"];
        modifieTable($bd, $sql);
        header("Location: ListePartie.php");
    }
    else header("Location: ListePartie.php");
?>