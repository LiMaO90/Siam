<?php
    include("bdd.php");

    if(isset($_GET["direction"]) && isset($_GET["idGrille"])){
        $bd = connectBD("Siam");
        $direction = $_GET["direction"];
        $idGrille = $_GET["idGrille"];

        $idPion = prochainPion($bd, $idGrille);
        if($idPion != null){
            $sql = "UPDATE Pion Set direction = \"".$direction."\" Where idPion = ".$idPion;
            modifieTable($bd, $sql);

            $sql = "UPDATE Grille Set idPionJouer = \"".$idPion."\" Where idGrille = ".$idGrille;
            modifieTable($bd, $sql);

            $sql = "UPDATE Grille Set estSelectPion = \"1\" Where idGrille = ".$idGrille;
            modifieTable($bd, $sql);

            echo "concombre";
        }
        else{
            echo "Error";
        }
    }
?>