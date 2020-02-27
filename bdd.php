<?php
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

function isAdmin($bd, $id){
    $requete = 'Select isAdmin From Joueur Where idJoueur="'.$id.'"';
    $result = $bd->query($requete);
    $resultat = $result->fetch(PDO::FETCH_ASSOC);
    return $resultat["isAdmin"] == "True";
}
?>