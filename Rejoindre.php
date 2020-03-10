<?php
    include("bdd.php");

    if(isset($_SESSION["id"]) && isset($_GET["grille"])){
        $bd = connectBD("Siam");
        $sql = "INSERT INTO Participe(idJoueur, idGrille, numJoueur) Values (".$_SESSION["id"].", ".$_GET["grille"].", 2)";
        modifieTable($bd, $sql);

        $sql = "UPDATE Grille Set estPartie = \"1\" Where idGrille = ".$_GET["grille"];
        modifieTable($bd, $sql);

        $nbPion = nbPion($bd);

        for ($i=0; $i < 5 ; $i++) { 
            $sql = "INSERT Into Pion(idPion, role, direction, position) Values (".(($nbPion+1) + $i).", 1, 0, \"-1:-1\")";
            modifieTable($bd, $sql);

            $sql = "INSERT Into Joue(idGrille, idPion) Values (".$_GET["grille"].", ".(($nbPion+1) + $i).")";
            modifieTable($bd, $sql);
        }

        $nbPion = nbPion($bd);
        for ($i=0; $i < 5 ; $i++) { 
            $sql = "INSERT Into Pion(idPion, role, direction, position) Values (".(($nbPion+1) + $i).", 2, 0, \"-1:-1\")";
            modifieTable($bd, $sql);

            $sql = "INSERT Into Joue(idGrille, idPion) Values (".$_GET["grille"].", ".(($nbPion+1) + $i).")";
            modifieTable($bd, $sql);
        }

        $nbPion = nbPion($bd);
        for ($i=0; $i < 3 ; $i++) { 
            $sql = "INSERT Into Pion(idPion, role, direction, position) Values (".(($nbPion+1)+$i).", 0, 0, \"".($i+1).":2\")";
            modifieTable($bd, $sql);

            $sql = "INSERT Into Joue(idGrille, idPion) Values (".$_GET["grille"].", ".(($nbPion+1)+$i).")";
            modifieTable($bd, $sql);
        }

        header("Location: Compte.php");
    }
?>