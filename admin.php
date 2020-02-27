<?php 
  include("bdd.php");

  if(!isset($_COOKIE["id"])) header("Location: Connexion.php");
?>

<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <?php include("header.php"); ?>
  <body>
    <h1>Admin</h1>
  </body>
</html>
