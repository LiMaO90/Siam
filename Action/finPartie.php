<?php
    include("../bdd.php");

    if(isset($_GET["vainqueur"])){
        $bd = connectBD("../Siam");
        $vainqueur = explode(":", $_GET["vainqueur"]);
        $idGrille = $vainqueur[0];
        $roleId = $vainqueur[1];

        //var_dump($vainqueur);

        $sql = "UPDATE Grille Set joueurGagnant = \"".$roleId."\" WHERE idGrille = ".$idGrille;
        modifieTable($bd, $sql);

        $sql = "UPDATE Grille Set estPartie = \"2\" Where idGrille = ".$idGrille;
        modifieTable($bd, $sql);
    }
?>