<?php
    include("bdd.php");

    if(!isset($_SESSION["id"]) && !isset($_GET["grille"])) header("Location: Connexion.php");

    $bd = connectBD("Siam");
    $isAdmin = isAdmin($bd, $_SESSION["id"]);

    $tab = recupTable($bd, $_GET["grille"]);

?>

<!DOCTYPE html>
<html lang="fr">
<?php include("header.php"); ?>
<body>
    <?php if($isAdmin) include("MenuHtmlAdmin.php"); else include("MenuHtmlUtilisateur.php"); ?>
    <h1>Jouer sur la grille N°<?php echo $_GET["grille"] ?></h1>
    <center>
        <table style="width:15%;">
            <?php for ($i=0; $i < 5 ; $i++) { ?>
                <tr>
                <?php for ($j=0; $j < 5 ; $j++) {
                    $isPlacer = false;
                    foreach($tab as $value){
                        if($j.":".$i == $value["position"]){
                            $isPlacer = true;
                            if($value["role"] == "0")
                                echo "<td><img onClick=\"clickOnButton(".$j.", ".$i.")\" src=\"ressources/rocher.gif\" ></button></td>";
                            else
                                echo "<td><img onClick=\"clickOnButton(".$j.", ".$i.")\" src=\"ressources/".$value["role"]."".$value["direction"].".gif\" ></button></td>";
                        }
                    }
                    if(!$isPlacer) echo "<td><img onClick=\"clickOnButton(".$j.", ".$i.")\" src=\"ressources/croix.png\" height=\"78%\" width=\"100%\" ></button></td>";
                } ?>
                </tr>
            <?php } ?>
        </table>
    <center>
</body>
</html>

<script>
    function clickOnButton(x, y){
        console.log('Debug Objects: ' + x + ", " + y );
    }
</script>