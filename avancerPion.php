<?php
    include("bdd.php");

    if(isset($_GET["idGrille"])){
        $bd = connectBD("Siam");
        $idGrille = $_GET["idGrille"];

        $idPion = pionCourant($bd, $idGrille);

        $sql = "SELECT * From Pion Where idPion = ".$idPion;
        $result = selectTable($bd, $sql);
        $pion = $result->fetch(PDO::FETCH_ASSOC);

        $direction = $pion["direction"];
        $position = $pion["position"];

        if($direction == 0){
            $x = explode(":", $position)[0];
            $tabPions = recupPionsX($bd, $idGrille, $x);
            $tabReduc = reducTab($tabPions, $idPion, $direction);
            $estDeplacer = deplacerPion($bd, $tabReduc, $direction);
        }
        else if($direction == 1){
            $y = explode(":", $position)[1];
            $tabPions = recupPionsY($bd, $idGrille, $y);
            $tabReduc = reducTab($tabPions, $idPion, $direction);
            $estDeplacer = deplacerPion($bd, $tabReduc, $direction);
        }
        else if($direction == 2){
            $x = explode(":", $position)[0];
            $tabPions = recupPionsX($bd, $idGrille, $x);
            $tabReduc = reducTab($tabPions, $idPion, $direction);
            $estDeplacer = deplacerPion($bd, $tabReduc, $direction);
        }
        else if($direction == 3){
            $y = explode(":", $position)[1];
            $tabPions = recupPionsY($bd, $idGrille, $y);
            $tabReduc = reducTab($tabPions, $idPion, $direction);
            $estDeplacer = deplacerPion($bd, $tabReduc, $direction);
        }
        /*if( $estDeplacer == "Fin" )
            //finPartie();
            echo "Fin";*/
        echo $estDeplacer;
    }
?>