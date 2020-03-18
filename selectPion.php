<?php
    include("bdd.php");

    if(isset($_GET["x"]) && isset($_GET["y"]) && isset($_GET["idGrille"])){
        $bd = connectBD("Siam");
        $x = $_GET["x"];
        $y = $_GET["y"];
        $idGrille = $_GET["idGrille"];

        $result = recupPion($bd, $idGrille, $x, $y);
        if($result != null){
            $sql = "UPDATE Grille Set idPionJouer = \"".$result."\" Where idGrille = ".$idGrille." And tour In ( Select role From Pion where idPion = ".$result." )";
            if( modifieTable($bd, $sql) < 1)
                echo "Error Joueur";
            else{
                $sql = "UPDATE Grille Set estSelectPion = 1 Where idGrille = ".$idGrille;
                modifieTable($bd, $sql);
                echo "Ca marche";
            }            
        }
        else{
            echo "Error";
        }
    }
?>