<?php

include("bdd.php");

$error = NULL;

function passage($id){
  $bd = connectBD("Siam");
  if(isAdmin($bd, $id)) header("Location: MenuUtilisateur.php");
  else header("Location: MenuAdmin.php");
}

function estValide($identifiant, $mdp){
  $bd = connectBD("Siam");
  $sql = 'Select idJoueur From Joueur Where identifiant="'.$identifiant.'" and motDePasse="'.$mdp.'"';
  $result = selectTable($bd, $sql);
  if($result != NULL){
    $row = $result->fetch(PDO::FETCH_ASSOC);
    return $row["idJoueur"];
  }
  return "error";
}

function testConnexion($identifiant, $mdp){
  $hashMdp = hash("md5", $mdp);
  $id = estValide($identifiant, $hashMdp);
  if($id != "error"){
    setcookie("id", $id, (time()+30*24*30));
    passage($_COOKIE["id"]);
    return NULL;
  }
  return "identifiant";
}

if(isset($_COOKIE["id"])){
  passage($_COOKIE["id"]);
}

if(isset($_POST["MDP"]) && isset($_POST["identifiant"])){
  $error = testConnexion($_POST["identifiant"], $_POST["MDP"]);
}

?>
<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <?php include("header.php"); ?>
  <body>
    <img src="ressources/logo_sia.gif" alt="Logo de Siam"><br>
    <p style="color:red"><?php if($error == "identifiant") echo "identifiant ou mot de passe incorrecte"; ?></p>
    <form action="Connexion.php" method="post">
      <label>idententifiant: </label> <input type="text" name="identifiant" value=""><br>
      <label>Mot de passe: </label> <input type="password" name="MDP" value=""><br>
      <input type="submit" value="Se connecter">
    </form>
  </body>
</html>
