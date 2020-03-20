<?php
    include("bdd.php");

    $bd = connectBD("Siam");

    $tabX = recupPionsX($bd, 6, 3);
    //var_dump($tabX);

    $tabReducX = reducTab($tabX, 7, 2);
    var_dump($tabReducX);

    deplacerPion($tabX, 2);


    $tabY = recupPionsY($bd, 6, 2);
    //var_dump($tabY);

    $tabReduc = reducTab($tabY, 9, 3);
    var_dump($tabReduc);

    deplacerPion($tabReduc, 3);

    //$tabReduc = reducTab($tabY, 12, 1);
    //var_dump($tabReduc);

    //$verif = verifPossibiliteDeplacement($tabReducX, 0);
    //var_dump($verif);

    //$test = explode(":", "2:3")[0];
    //var_dump($test);


    /*
    $tabPion = recupPionsX($bd, $idGrille, $x) / recupPionsY($bd, $idGrille, $y)
    $tabReduc = reducTab($tabPion, $idPion, $direction)
    deplacerPion($bd, $tabReduc, $direction)

    */
?>