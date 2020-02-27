<?php

$cle = "md5";

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
?>