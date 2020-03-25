<?php
    include("../bdd.php");

    if(isset($_GET["idGrille"])){
        $bd = connectBD("../Siam");
        $idGrille = $_GET["idGrille"];

        $sql = "UPDATE Grille Set estSelectPion = \"0\" Where idGrille = ".$idGrille;
        $resultat = modifieTable($bd, $sql);

        echo $resultat." test";
    }
?>