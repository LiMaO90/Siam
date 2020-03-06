<?php
include("bdd.php");

if(!isset($_SESSION["id"])) header("Location: Connexion.php");

$isAdmin = False;

$bd = connectBD("Siam");
if(isAdmin($bd, $_SESSION["id"])){
    $isAdmin = True;
}

$value = NULL;

if(isset($_POST["oldMDP"]) && isset($_POST["MDP"]) && isset($_POST["verifMDP"])){
    $oldMDP = hachage($_POST["oldMDP"]);
    if(estChangeMDPValide($bd, $_SESSION["id"], $oldMDP))
    {
        if($_POST["MDP"] == $_POST["verifMDP"]){
            $mdp = hachage($_POST["MDP"]);
            $requete = "UPDATE Joueur SET motDePasse = \'".$mdp."\' Where idJoueur = ".$_SESSION["id"];
            debugConsole($requete);
            if(modifieTable($bd, $requete) > 0) $value = "success";
            else $value = "error";
        }
        else $value = "pass";
    }
    else $value = "oldPass";

}

?>

<!DOCTYPE html>
<html lang="fr">
    <?php include("header.php"); ?>
<body>
    <?php if($isAdmin) include("MenuHtmlAdmin.php"); else include("MenuHtmlUtilisateur.php"); ?>
    <h1>Mon compte</h1>
    <center>
        <?php  
            if($value == "success") echo "<p style=\"color:green;\">Le changement de mot de passe a été fait !!</p>"; 
            else if($value == "error") echo "<p style=\"color:red;\">Le changement n'a pas été fait !!</p>"; 
        ?>
        <form action="Compte.php" method="post">
            <fieldset>
                <legend>Changer le mot de passe :</legend>
                <label>ancien mot de passe: </label><input type="password" name="oldMDP"> <label style="color:red"><?php if($value=="oldPass") echo "Non valide !"; ?></label><br>
                <label>mot de passe: </label><input type="password" name="MDP"><br>
                <label>confirmer: </label><input type="password" name="verifMDP"> <label style="color:red"><?php if($value=="pass") echo "Non valide !"; ?></label><br>
            </fieldset>
            <p>
                <input type="submit" value="Valider">
                <br>
                <a href="javascript:history.go(-1)">Retour</a>
            </p>
        </form>

        <h2>Partie en cours</h2>
        <div>
            <?php
                $listPartie = listPartieEnCours($bd, $_SESSION["id"]);
                $cpt = 0;
                while($row = $listPartie->fetch(PDO::FETCH_ASSOC))
                {
                    $estPartie = $row["estPartie"];
                    if($estPartie)
                    {
                        $tour = (int)$row["tour"];
                        if($tour%2 == 0)
                        {
                            if($row["numJoueur"] == "2") echo "<p>Partie en cours: <a href=\"Partie.php?Grille=".$row["idGrille"]."\"  style=\"color:green;\">Votre tours</a> </p>";
                            else echo "<p>Partie en cours: <a href=\"#\"  style=\"color:orange;\">En attente</a> </p>";
                        }
                        else
                        {
                            if($row["numJoueur"] == "1") echo "<p>Partie en cours: <a href=\"Partie.php?Grille=".$row["idGrille"]."\"  style=\"color:green;\">Votre tours</a> </p>";
                            else echo "<p>Partie en cours: <a href=\"#\"  style=\"color:orange;\">En attente</a> </p>";
                        }
                    }
                    else echo "<p>Partie en attente d'un joueur: <a href=\"#\"  style=\"color:orange;\">En attente</a> </p>";
                    echo "<br>";
                    $cpt++;
                }
                if($cpt == 0) echo "<p>Vous n'avez pas de partie en cours.</p>";
            ?>
        </div>

    </center>
</body>
</html>