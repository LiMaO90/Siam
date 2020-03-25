<?php
    include("../bdd.php");

    if(isset($_GET["idGrille"])){
        $bd = connectBD("../Siam");
        $idGrille = $_GET["idGrille"];

        $sql = "UPDATE Pion Set position = \"-1:-1\" Where idPion = (Select idPionJouer From Grille Where idGrille = ".$idGrille.")";
        modifieTable($bd, $sql);

        echo "concombre";
    }
?>