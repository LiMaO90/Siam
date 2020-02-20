<?php
  function testConnexion($identifiant, $mdp){
    if(True){
      // Tester si identifiant existe dans Base si oui test si identifiant.mdp == mdp

      setcookie("id", $id, (time()+30*24*30));
      header("Location: recherchePartie.php");
    }
  }

  if(isset($_POST["MDP"]) && isset($_POST["identifiant"])){
    testConnexion($_POST["identifiant"], $_POST["MDP"]);
  }
?>
<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Siam</title>
  </head>
  <body>
    <form action="index.php" method="post">
      <label>idententifiant: </label>
      <input type="text" name="identifiant" value=""><br>
      <label>Mot de passe: </label>
      <input type="passworld" name="MDP" value=""><br>
      <input type="button" value="Se connecter">
    </form>
    <p> Vous n'avez pas de compte. Cliquez <a href="inscription.php">Ici</a>.</p>
  </body>
</html>
