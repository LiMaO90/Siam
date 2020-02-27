<?php 
  if(isset($_GET["menu"])){
    echo "<script> console.log(\"Test\"); </script>";
    switch ($_GET["menu"]) {
      case 'liste':
        header("Location: ListePartie.php");
        break;

      case 'creer':
        header("Location: CreerPartie.php");
        break;

      case 'compte':
        header("Location: Compte.php");
        break;

      case 'deco':
        setcookie("id", "", (time()+30*24*30));
        header("Location: index.php");
        break;
    }
  }
  else header("Location: Compte.php");
?>
