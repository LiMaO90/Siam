<?php

include("bdd.php");

$error = NULL;

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
    header("Location: recherchePartie.php");
    return NULL;
  }
  return "identifiant";
}

if(isset($_COOKIE["id"])){
  header("Location: recherchePartie.php");
}

if(isset($_POST["MDP"]) && isset($_POST["identifiant"])){
  $error = testConnexion($_POST["identifiant"], $_POST["MDP"]);
}

?>
<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <?php include("header.php"); ?>
  <body>
    <h1>Connexion</h1>
    <p style="color:red"><?php if($error == "identifiant") echo "identifiant ou mot de passe incorrecte"; ?></p>
    <p style="color:green"><?php if($error == NULL) echo "Test"; ?></p>
    <form action="Connexion.php" method="post">
      <label>idententifiant: </label> <input type="text" name="identifiant" value=""><br>
      <label>Mot de passe: </label> <input type="password" name="MDP" value=""><br>
      <input type="submit" value="Se connecter">
    </form>
    <p> Vous n'avez pas de compte. Cliquez <a href="inscription.php">Ici</a>.</p>
  </body>
</html>
