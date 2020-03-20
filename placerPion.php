<?php
    include("bdd.php");

    if(isset($_GET["x"]) && isset($_GET["y"]) && isset($_GET["idGrille"])){
        $bd = connectBD("Siam");
        $x = $_GET["x"];
        $y = $_GET["y"];
        $idGrille = $_GET["idGrille"];

        if( estEmplacementLibre($bd, $idGrille, $x, $y) ){
            $sql = "SELECT * From Pion Where idPion = ( Select idPionJouer From Grille Where idGrille = ".$idGrille." )";
            $result = selectTable($bd, $sql);
            $row = $result->fetch(PDO::FETCH_ASSOC);

            if($result != null){
                $sql = "UPDATE Pion Set position = \"".$x.":".$y."\" Where idPion = ".$row["idPion"];
                modifieTable($bd, $sql);            
                echo "Ca marche";
            }
            else
                echo "Error";
        }
        else
            echo "Error Placement";
    }
?>