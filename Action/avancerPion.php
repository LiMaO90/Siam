<?php
    include("../bdd.php");

    if(isset($_GET["idGrille"])){
        $bd = connectBD("../Siam");
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
        if( $estDeplacer == "Fin" ){
            if($direction == 0 || $direction == 3){
                $fin = $idGrille.":";

                $cpt = 0;
                $estFin = false;
                while ($cpt < 5 && !$estFin) {
                    if(isset($tabReduc[$cpt]["idPion"])){
                        $value = $tabReduc[$cpt];

                        $directionCourant = $value["direction"];

                        if($directionCourant == $direction){
                            $estFin = true;
                            $fin = $fin.$value["role"];
                        }
                    }
                    $cpt = $cpt + 1;
                }

                echo $fin;
            }
            else{
                $fin = $idGrille.":";

                $cpt = 5;
                $estFin = false;
                while ($cpt >= 0 && !$estFin) {
                    if(isset($tabReducY[$cpt])){
                        $value = $tabReducY[$cpt];

                        $directionCourant = $value["direction"];

                        if($directionCourant == $direction){
                            $estFin = true;
                            $fin = $fin.$value["role"];
                        }
                    }
                    $cpt = $cpt - 1;
                }

                echo $fin;
            }
        }
        else
            echo $estDeplacer;
    }
?>