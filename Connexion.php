<?php

include("bdd.php");

$error = NULL;

$bd = connectBD("Siam");

function passage($bd, $id){
  if(isAdmin($bd, $id)) header("Location: GestionMenu/MenuUtilisateur.php");
  else header("Location: GestionMenu/MenuAdmin.php");
}

function testConnexion($bd, $identifiant, $mdp){
  $hashMdp = hachage($mdp);
  $id = recupeId($bd, $identifiant, $hashMdp);
  if($id != "error"){
    $_SESSION["id"] = $id;
    passage($bd, $_SESSION["id"]);
    return NULL;
  }
  return "identifiant";
}

if(isset($_SESSION["id"])){
  passage($bd, $_SESSION["id"]);
}

if(isset($_POST["MDP"]) && isset($_POST["identifiant"])){
  $error = testConnexion($bd, $_POST["identifiant"], $_POST["MDP"]);
}

?>
<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <?php include("header.php"); ?>
  <body>
    <img src="ressources/logo_sia.gif" alt="Logo de Siam"><br>
    <p style="color:red"><?php if($error == "identifiant") echo "identifiant ou mot de passe incorrecte"; ?></p>
    <center>
    <form action="Connexion.php" method="post">
        <fieldset>
        <legend>Connexion :</legend>
      <label>idententifiant: </label> <input type="text" name="identifiant" value=""><br>
      <label>Mot de passe: </label> <input type="password" name="MDP" value=""><br>
          </fieldset>
          <p>
      <input type="submit" value="Se connecter">
</p>
    </form>
    </center>
  </body>
</html>
