<?php

function connexpdo($base) {
    $dsn="sqlite:$base.db" ;
    try {
        $bd = new PDO($dsn);
        return $bd;
    }catch(PDOException $except){
        echo "Échec de la connexion",$except->getMessage();
        return FALSE;
        exit();
    }
}

function modifieTable($bd, $requete){
    return $db->exec($requete);
}

function selectTable($bd, $requete){
    return $bd->query($requete);
}
?>