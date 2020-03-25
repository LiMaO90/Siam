<?php
    include("../bdd.php");

    if(isset($_GET["direction"]) && isset($_GET["idGrille"])){
        $bd = connectBD("../Siam");
        $direction = $_GET["direction"];
        $idGrille = $_GET["idGrille"];

        $idPion = pionCourant($bd, $idGrille);
        if($idPion != null){
            $sql = "UPDATE Pion Set direction = \"".$direction."\" Where idPion = ".$idPion;
            modifieTable($bd, $sql);

            echo "concombre";
        }
        else{
            echo "Error";
        }
    }
?>