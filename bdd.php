<?php
    session_start();

    function connectBD($base){
        try {
            $bd = new PDO("sqlite:$base.db");
            return $bd;
        }catch(PDOException $except){
            echo "Échec de la connexion",$except->getMessage();
            exit();
            return NULL;
        }
    }

    function modifieTable($bd, $requete){
        return $bd->exec($requete);
    }

    function selectTable($bd, $requete){
        return $bd->query($requete);
    }

    function recupeId($bd, $identifiant, $mdp){
        $sql = 'SELECT idJoueur From Joueur Where identifiant="'.$identifiant.'" and motDePasse="'.$mdp.'"';
        $result = selectTable($bd, $sql);
        if($result != NULL){
          $row = $result->fetch(PDO::FETCH_ASSOC);
          return $row["idJoueur"];
        }
        return "error";
    }

    function estChangeMDPValide($bd, $id, $mdp){
        $sql = 'SELECT identifiant From Joueur Where idJoueur='.$id.' and motDePasse="'.$mdp.'"';
        $result = selectTable($bd, $sql);
        if($result != NULL)
        {
            $row = $result->fetch(PDO::FETCH_ASSOC);
            return $row["identifiant"] != "";
        }
        return false;
    }

    function listPartie($bd)
    {
        $sql = "SELECT idGrille, estPartie From Grille";
        return selectTable($bd, $sql);
    }

    function listPartieId($bd, $id)
    {
        $sql2 = "SELECT Grille.idGrille From Grille Inner Join Participe On Participe.idGrille = Grille.idGrille Where Participe.idJoueur = ".$id;
        $sql = "SELECT Grille.idGrille From Grille Inner Join Participe On Participe.idGrille = Grille.idGrille Where Grille.idGrille Not In (".$sql2.") AND Grille.estPartie = \"0\"";
        debugConsole($sql);
        return selectTable($bd, $sql);
    }

    function listPartieEnCours($bd, $id)
    {
        $sql = "SELECT Grille.idGrille, Grille.tour, Grille.estPartie, Participe.numJoueur From Grille Inner Join Participe On Participe.idGrille = Grille.idGrille Where Participe.idJoueur = ".$id;
        return selectTable($bd, $sql);
    }

    function isAdmin($bd, $id){
        $requete = 'SELECT isAdmin From Joueur Where idJoueur="'.$id.'"';
        $result = $bd->query($requete);
        $resultat = $result->fetch(PDO::FETCH_ASSOC);
        return $resultat["isAdmin"] == "True";
    }

    function idDernierePartie($bd)
    {
        $sql = "SELECT idGrille from Grille Order By idGrille DESC";
        $result = $bd->query($sql);
        $row = $result->fetch(PDO::FETCH_ASSOC);
        return $row["idGrille"];
    }

    function nbPion($bd)
    {
        $sql = "SELECT Count(idPion) As nb from Pion";
        $result = $bd->query($sql);
        $row = $result->fetch(PDO::FETCH_ASSOC);
        return $row["nb"];
    }

    function prochainPion($bd, $idGrille){
        $sql = "SELECT Pion.idPion from Pion Inner Join Joue On Joue.idPion = Pion.idPion Where idGrille = ".$idGrille." And  position = \"-1:-1\" And role In ( Select tour From Grille Where idGrille = ".$idGrille." )";
        $result = $bd->query($sql);
        $row = $result->fetch(PDO::FETCH_ASSOC);
        return $row["idPion"];
    }

    function recupTable($bd, $idGrille){
        $tab = array();
        $sql = "SELECT * From Pion Inner join Joue On Joue.idPion = Pion.idPion Inner join Grille On Grille.idGrille = Joue.idGrille Where Grille.idGrille = ".$idGrille;
        $result = $bd->query($sql);
        while($row = $result->fetch(PDO::FETCH_ASSOC)){
            $tab[$row["idPion"]] = $row;
        }
        return $tab;
    }

    function recupPion($bd, $idGrille, $x, $y){
        $sql = "SELECT Pion.idPion From Pion Inner join Joue On Joue.idPion = Pion.idPion Where position = '".$x.":".$y."' And Not role = 0 And idGrille = ".$idGrille;
        $result = $bd->query($sql);
        $row = $result->fetch(PDO::FETCH_ASSOC);
        return $row["idPion"];
    }

    function estEmplacementLibre($bd, $idGrille, $x, $y){
        $sql = "SELECT Pion.idPion From Pion Inner join Joue On Joue.idPion = Pion.idPion Where position = '".$x.":".$y."' And idGrille = ".$idGrille;
        $result = $bd->query($sql);
        $row = $result->fetch(PDO::FETCH_ASSOC);
        return $row["idPion"] == null;
    }

    function pionCourant($bd, $idGrille){
        $sql = "SELECT idPionJouer From Grille Where idGrille = ".$idGrille;
        $result = $bd->query($sql);
        $row = $result->fetch(PDO::FETCH_ASSOC);
        return $row["idPionJouer"];
    }

    function estPionCourantSurGrille($bd, $idGrille){
        if( pionCourant($bd, $idGrille) == null) return false;
        $sql = "SELECT position From Pion Where idPion = ".pionCourant($bd, $idGrille);
        $result = $bd->query($sql);
        $row = $result->fetch(PDO::FETCH_ASSOC);
        return $row["position"] != "-1:-1";
    }

    function deplacerPionInBDD($bd, $idPion, $position){
        $sql = "UPDATE Pion Set position = \"".$position."\" Where idPion = ".$idPion;
        $bd->exec($sql);
    }

    function joueurGagnant($bd, $idGrille){
        $sql = "SELECT Joueur.* From Joueur Inner Join Participe On Joueur.idJoueur = Participe.idJoueur Inner Join Grille On Participe.idGrille = Grille.idGrille Where Grille.idGrille = ".$idGrille." AND numJoueur = joueurGagnant";
        $result = selectTable($bd, $sql);
        $joueur = $result->fetch(PDO::FETCH_ASSOC);
        return $joueur;
    }

    function hachage($mdp)
    {
        return hash("md5", $mdp);
    }

    function debugConsole($data)
    {
        $output = $data;
        if (is_array($output)) $output = implode(',', $output);
        echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
    }

    function debugConsoleJS($data)
    {
        $output = $data;
        if (is_array($output)) $output = implode(',', $output);
        echo "console.log('Debug Objects: " . $output . "' );";
    }





    function recupPionsX($bd, $idGrille, $x){
        $tab = array();
        for ($y=0; $y < 5; $y++) { 
            $sql = "SELECT * From Pion Inner join Joue On Joue.idPion = Pion.idPion Where Joue.idGrille = ".$idGrille." And position = \"".$x.":".$y."\"";
            $result = $bd->query($sql);
            $row = $result->fetch(PDO::FETCH_ASSOC);
            if($row["idPion"] != null){
                $tab[] = $row;
            }
            else{
                $tab[] = array();
            }
        }
        return $tab;
    }

    function recupPionsY($bd, $idGrille, $y){
        $tab = array();
        for ($x=0; $x < 5; $x++) { 
            $sql = "SELECT * From Pion Inner join Joue On Joue.idPion = Pion.idPion Where Joue.idGrille = ".$idGrille." And position = \"".$x.":".$y."\"";
            $result = $bd->query($sql);
            $row = $result->fetch(PDO::FETCH_ASSOC);
            if($row["idPion"] != null){
                $tab[] = $row;
            }
            else{
                $tab[] = array();
            }
        }
        return $tab;
    }

    function reducTab($tab, $idPion, $direction){
        $newTab = array();
        $estTrouve = False;
        if($direction == 0 || $direction == 3){
            foreach ($tab as $key => $value) {
                if(!$estTrouve){
                    if( isset($value["idPion"]) && $value["idPion"] == $idPion) $estTrouve = True;
                    $newTab[$key] = $value;
                }
            }
        }
        else{
            foreach ($tab as $key => $value) {
                if( isset($value["idPion"]) && $value["idPion"] == $idPion) $estTrouve = True;
                if($estTrouve){
                    $newTab[$key] = $value;
                }
            }
        }
        return $newTab;
    }

    function verifPossibiliteDeplacement($tabReduc, $direction){
        $directionOppose = ( $direction + 2 ) % 4;
        $cptOpp = 0;
        $cptMeme = 0;

        foreach ($tabReduc as $key => $value) {
            if(isset($value["idPion"])){
                if($value["role"] != 0){
                    if($value["direction"] == $direction) $cptMeme = $cptMeme + 1;
                    else if($value["direction"] == $directionOppose) $cptOpp = $cptOpp + 1;
                }
            }
        }

        return $cptMeme > $cptOpp;
    }

    function deplacerPion($bd, $tabReduc, $direction){
        if(verifPossibiliteDeplacement($tabReduc, $direction)){
            $estFin = False;
            $estTrouveVide = False;
            if($direction == 0){
                $cpt = 0;
                foreach ($tabReduc as $key => $value) {
                    $cpt = $key;
                }

                
                while($cpt >= 0 && !$estTrouveVide){
                    $value = $tabReduc[$cpt];
                    if(isset($value["idPion"]) && !$estTrouveVide){
                        if($cpt == 0){
                            if($value["role"] == 0) $estFin = True;
                            else deplacerPionInBDD($bd, $value["idPion"], "-1:-1");
                        }
                        else{
                            $y = $cpt-1;
                            $x = explode(":", $value["position"])[0];

                            $position = $x.":".$y;
                            deplacerPionInBDD($bd, $value["idPion"], $position);
                        }
                    }
                    else $estTrouveVide = True;
                    $cpt = $cpt - 1;
                }
            }
            else if($direction == 3){
                $cpt = 0;
                foreach ($tabReduc as $key => $value) {
                    $cpt = $key;
                }

                while($cpt >= 0 && !$estTrouveVide) {
                    $value = $tabReduc[$cpt];
                    if(isset($value["idPion"]) && !$estTrouveVide){
                        if($cpt == 0){
                            if($value["role"] == 0) $estFin = True;
                            else deplacerPionInBDD($bd, $value["idPion"], "-1:-1");
                        }
                        else{
                            $x = $cpt-1;
                            $y = explode(":", $value["position"])[1];

                            $position = $x.":".$y;
                            deplacerPionInBDD($bd, $value["idPion"], $position);
                        }
                    }
                    else $estTrouveVide = True;
                    $cpt = $cpt - 1;
                }
            }
            else if($direction == 2){
                foreach ($tabReduc as $key => $value) {
                    if(isset($value["idPion"]) && !$estTrouveVide){
                        if($key == 4){
                            if($value["role"] == 0) $estFin = True;
                            else deplacerPionInBDD($bd, $value["idPion"], "-1:-1");
                        }
                        else{
                            $y = $key+1;
                            $x = explode(":", $value["position"])[0];

                            $position = $x.":".$y;
                            deplacerPionInBDD($bd, $value["idPion"], $position);
                        }
                    }
                    else $estTrouveVide = True;
                }
            }
            else if($direction == 1){
                foreach ($tabReduc as $key => $value) {
                    if(isset($value["idPion"]) && !$estTrouveVide){
                        if($key == 4){
                            if($value["role"] == 0) $estFin = True;
                            else deplacerPionInBDD($bd, $value["idPion"], "-1:-1");
                        }
                        else{
                            $x = $key+1;
                            $y = explode(":", $value["position"])[1];

                            $position = $x.":".$y;
                            deplacerPionInBDD($bd, $value["idPion"], $position);
                        }
                    }
                    else $estTrouveVide = True;
                }
            }

            if($estFin) return "Fin";
            return "Deplacer";
        }
        return "Non";
    }
?>