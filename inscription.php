<?php

include("bdd.php");

$value = NULL;

if(isset($_POST["identifiant"]) && isset($_POST["MDP"]) && isset($_POST["verifMDP"])){
  if($_POST["MDP"] == $_POST["verifMDP"]){
    $bd = connectBD("Siam");
    if($bd != NULL){
      $mdp = hash($cle, $_POST["MDP"]);
      $requete = 'Insert Into Joueur(identifiant, motDePasse, isAdmin) Values("'.$_POST["identifiant"].'", "'.$mdp.'", "False")';
      modifieTable($bd, $requete);
      $value = "success";
    }
    else $value = "bd";
  }
  else $value = "pass";
}
?>

<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <?php include("header.php"); ?>
  <body>
    <p style="color:green;"><?php if($value == "success") echo "Votre compte a été créé !!"; ?></p>
    <h1>Inscription</h1>
    <form action="inscription.php" method="post">
      <label>identfiant: </label><input type="text" name="identifiant"><br>
      <label>mot de passe: </label><input type="password" name="MDP"><br>
      <label>confirmer: </label><input type="password" name="verifMDP"> <label style="color:red"><?php if($value=="pass") echo "Non valide !"; ?></label><br>
      <input type="submit" value="Valider">
    </form>
    <p> Vous avez un compte. Connectez-vous <a href="Connexion.php">Ici</a>.</p>
  </body>
</html>