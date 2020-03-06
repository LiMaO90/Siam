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

    function listPartie($bd, $id)
    {
        $sql = "SELECT Grille.idGrille From Grille Inner Join Participe On Participe.idGrille = Grille.idGrille Where NOT Participe.idJoueur = ".$id." AND Grille.estPartie = \"0\"";
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
?>