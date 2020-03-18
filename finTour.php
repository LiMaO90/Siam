<?php
    include("bdd.php");

    if(isset($_GET["idGrille"])){
        $bd = connectBD("Siam");
        $idGrille = $_GET["idGrille"];

        $sql = "Select tour From Grille Where idGrille = ".$idGrille;
        $result = selectTable($bd, $sql);
        $row = $result->fetch(PDO::FETCH_ASSOC);

        if($row["tour"] == "1") $tour = 2;
        else $tour = 1;

        echo "tour: ".$tour." idGrille: ".$idGrille;

        $sql = "UPDATE Grille Set tour = ".$tour." Where idGrille = ".$idGrille;
        modifieTable($bd, $sql);

        $sql = "UPDATE Grille Set estSelectPion = 0 Where idGrille = ".$idGrille;
        modifieTable($bd, $sql);

        echo "Ca marche";
    }
?>