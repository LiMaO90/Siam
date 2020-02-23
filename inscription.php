<?php

include("bdd.php");

if(isset($_POST["identifiant"]) && isset($_POST["MDP"]) && isset($_POST["verifMDP"])){
  if($_POST["MDP"] == $_POST["verifMDP"]){
    $requete = "Insert Into Joueur(identifiant, motDePasse, role) Values(".$_POST["identifiant"].", ".$_POST["MDP"].", 0);";
  }
}
?>

<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <?php include("header.php"); ?>
  <body>
    <h1>Inscription</h1>
    <form action="inscription.php" method="post">
      <label>identfiant: </label><input type="text" name="identifiant"><br>
      <label>mot de passe: </label><input type="password" name="MDP"><br>
      <label>confirmer: </label><input type="password" name="verifMDP"><br>
      <input type="submit" value="Valider">
    </form>
    <p> Vous avez un compte. Connectez-vous <a href="Connexion.php">Ici</a>.</p>
  </body>
</html>