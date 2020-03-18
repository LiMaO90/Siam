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
?>