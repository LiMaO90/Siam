<?php
    include("bdd.php");

    if(!isset($_SESSION["id"]) && !isset($_GET["grille"])) header("Location: Connexion.php");

    $bd = connectBD("Siam");
    $isAdmin = isAdmin($bd, $_SESSION["id"]);

    $tab = recupTable($bd, $_GET["grille"]);

    $sql = "SELECT * from Grille where idGrille = ".$_GET["grille"];
    $result = selectTable($bd, $sql);
    $grille = $result->fetch(PDO::FETCH_ASSOC);

    $sql = "SELECT numJoueur From Participe Where idJoueur = ".$_SESSION["id"]." And idGrille = ".$_GET["grille"];
    $result = selectTable($bd, $sql);
    $tour = $result->fetch(PDO::FETCH_ASSOC);

    if($grille["tour"] != $tour["numJoueur"]) header("Location: Connexion.php");
?>

<!DOCTYPE html>
<html lang="fr">
<?php include("header.php"); ?>
<body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    <?php if($isAdmin) include("MenuHtmlAdmin.php"); else include("MenuHtmlUtilisateur.php"); ?>
    <h1>Jouer sur la grille N°<?php echo $_GET["grille"] ?></h1>
    <center>
        <?php if($grille["estSelectPion"] == "0"){ ?>
            <h5>Selection un pion</h5>
        <?php } else { ?>
            <h5>Selection une action</h5>
        <?php } ?>
        <table>
            <?php for ($i=0; $i < 5 ; $i++) { ?>
                <tr>
                <?php for ($j=0; $j < 5 ; $j++) {
                    $isPlacer = false;
                    foreach($tab as $value){
                        if($j.":".$i == $value["position"]){
                            $isPlacer = true;
                            if($value["role"] == "0"){
                                if($value["estSelectPion"] == "0")
                                    echo "<td><img onClick=\"selectPionOnGrille(".$j.", ".$i.", ".$_GET["grille"].")\" src=\"ressources/rocher.gif\" height=\"100%\" width=\"100%\" ></button></td>";
                                else{
                                    echo "<td><img onClick=\"PlacerPionOnGrille(".$j.", ".$i.", ".$_GET["grille"].")\" src=\"ressources/rocher.gif\" height=\"100%\" width=\"100%\" ></button></td>";
                                }
                            }
                            else{
                                if($value["idPion"] == $value["idPionJouer"]){
                                    if($value["estSelectPion"] == "0")
                                        echo "<td style=\"border: 5px solid red;\"><img onClick=\"selectPionOnGrille(".$j.", ".$i.", ".$_GET["grille"].")\" src=\"ressources/".$value["role"]."".$value["direction"].".gif\" height=\"100%\" width=\"100%\" ></button></td>";
                                    else{
                                        echo "<td style=\"border: 5px solid red;\"><img onClick=\"PlacerPionOnGrille(".$j.", ".$i.", ".$_GET["grille"].")\" src=\"ressources/".$value["role"]."".$value["direction"].".gif\" height=\"100%\" width=\"100%\" ></button></td>";
                                    }
                                }
                                else{
                                    if($value["estSelectPion"] == "0")
                                        echo "<td><img onClick=\"selectPionOnGrille(".$j.", ".$i.", ".$_GET["grille"].")\" src=\"ressources/".$value["role"]."".$value["direction"].".gif\" height=\"100%\" width=\"100%\" ></button></td>";
                                    else{
                                        echo "<td><img onClick=\"PlacerPionOnGrille(".$j.", ".$i.", ".$_GET["grille"].")\" src=\"ressources/".$value["role"]."".$value["direction"].".gif\" height=\"100%\" width=\"100%\" ></button></td>";
                                    }
                                }
                            }
                        }
                    }
                    if(!$isPlacer) {
                        if($value["estSelectPion"] == "0")
                            echo "<td><img onClick=\"selectPionOnGrille(".$j.", ".$i.", ".$_GET["grille"].")\" src=\"ressources/croix.png\" height=\"100%\" width=\"100%\" ></button></td>";
                        else
                            echo "<td><img onClick=\"PlacerPionOnGrille(".$j.", ".$i.", ".$_GET["grille"].")\" src=\"ressources/croix.png\" height=\"100%\" width=\"100%\" ></button></td>";
                    }
                } ?>
                </tr>
            <?php } ?>
        </table>
        <?php if($grille["estSelectPion"] == "0"){ ?>
            <button id="placerPion" onClick="placerPion()">Ajouter un pion</button>
            <button id="haut" onClick="ajouterPion(<?php echo $_GET["grille"]; ?>, 0)" hidden>haut</button>
            <button id="bas" onClick="ajouterPion(<?php echo $_GET["grille"]; ?>, 2)" hidden>bas</button>
            <button id="gauche" onClick="ajouterPion(<?php echo $_GET["grille"]; ?>, 3)" hidden>gauche</button>
            <button id="droite" onClick="ajouterPion(<?php echo $_GET["grille"]; ?>, 1)" hidden>droite</button>
        <?php } else { ?>
            <button id="avancer" onClick="avancerPion(<?php echo $_GET["grille"]; ?>)">Avancer le pion selectionné</button>
            <button id="tourner" onClick="tournerPion()">Tourner le pion selectionné</button>
            <button id="retirer" onClick="retirerPion(<?php echo $_GET["grille"]; ?>)" >Retirer le pion selectionné</button>
            <button id="tournerGauche" onClick="tournerGauchePion(<?php echo $_GET["grille"]; ?>)" hidden>Tourner à gauche</button>
            <button id="tournerDroite" onClick="tournerDroitePion(<?php echo $_GET["grille"]; ?>)" hidden>Tourner à gauche</button>
            <button id="valider" onClick="validerTourner(<?php echo $_GET["grille"]; ?>)" hidden>Valider</button>
        <?php } ?>
    <center>
</body>
</html>

<script>
    function tournerPion(){
        $("#avancer").hide();
        $("#tourner").hide();
        $("#retirer").hide();
        $("#tournerGauche").show();
        $("#tournerDroite").show();
        $("#valider").show();
    }

    function placerPion(){
        $("#placerPion").hide();
        $("#haut").show();
        $("#bas").show();
        $("#gauche").show();
        $("#droite").show();
    }

    function selectPionOnGrille(x, y, idGrille){
        $.ajax({
            url: 'selectPion.php',
            type: 'GET',
            cache: true,
            data: 'x='+ x + '&y=' + y + '&idGrille=' + idGrille,
            success: function(reponse) {
                console.log(reponse);
                if(reponse == "Error")
                    alert("Il faut cliquer sur un pion !!");
                else if(reponse == "Error Joueur")
                    alert("Jouer avec vos Pions");
                else
                    document.location.reload(true);
            }
        });
    }

    function ajouterPion(idGrille, direction){
        console.log("ajouter pion");
        $.ajax({
            url: 'ajouterPion.php',
            type: 'GET',
            cache: true,
            data: 'idGrille=' + idGrille + "&direction=" + direction,
            success: function(reponse) {
                console.log(reponse);
                if(reponse == "Error")
                    alert("Vous n'avez plus de pion a placer");
                else
                    document.location.reload(true);
            }
        });
    }

    function retirerPion(idGrille){
        console.log("retirer");
        $.ajax({
            url: 'retirerPion.php',
            type: 'GET',
            cache: true,
            data: 'idGrille=' + idGrille,
            success: function(reponse) {
                console.log(reponse);
                finTour(idGrille);
            }
        });
    }

    function PlacerPionOnGrille(x, y, idGrille){
        console.log("placer on " + x + ", " + y);
        $.ajax({
            url: 'placerPion.php',
            type: 'GET',
            cache: true,
            data: 'x='+ x + '&y=' + y + '&idGrille=' + idGrille,
            success: function(reponse) {
                console.log(reponse);
                if(reponse == "Error")
                    alert("Pas de selection de Pion !!");
                else
                    finTour(idGrille);
            }
        });
    }

    function tournerGauchePion(idGrille){
        /*$.ajax({
            url: 'tournerPion.php',
            type: 'GET',
            cache: true,
            data: 'idGrille=' + idGrille + "&tourner=1",
            success: function(reponse) {
                console.log(reponse);
                if(reponse == "Error")
                    alert("Il faut cliquer sur un pion !!");
                else if(reponse == "Error Joueur")
                    alert("Jouer avec vos Pions");
                else
                    document.location.reload(true);
            }
        });*/
        console.log("tourner a gauche");
    }

    function tournerDroitePion(idGrille){
        /*$.ajax({
            url: 'tournerPion.php',
            type: 'GET',
            cache: true,
            data: 'idGrille=' + idGrille + "&tourner=2",
            success: function(reponse) {
                console.log(reponse);
                if(reponse == "Error")
                    alert("Il faut cliquer sur un pion !!");
                else if(reponse == "Error Joueur")
                    alert("Jouer avec vos Pions");
                else
                    document.location.reload(true);
            }
        });*/
        console.log("tourner a droite");
    }

    function validerTourner(idGrille){
        /*$.ajax({
            url: 'tournerPion.php',
            type: 'GET',
            cache: true,
            data: 'idGrille=' + idGrille + "&tourner=2",
            success: function(reponse) {
                console.log(reponse);
                if(reponse == "Error")
                    alert("Il faut cliquer sur un pion !!");
                else if(reponse == "Error Joueur")
                    alert("Jouer avec vos Pions");
                else
                    document.location.reload(true);
            }
        });*/
        console.log("valider Tour");
    }

    function avancerPion(idGrille){
        /*$.ajax({
            url: 'avancerPion.php',
            type: 'GET',
            cache: true,
            data: 'idGrille=' + idGrille,
            success: function(reponse) {
                console.log(reponse);
                if(reponse == "Error")
                    alert("Il faut cliquer sur un pion !!");
                else if(reponse == "Error Joueur")
                    alert("Jouer avec vos Pions");
                else
                    document.location.reload(true);
            }
        });*/
        console.log("avancer");
    }

    function finTour(idGrille){
        console.log("Fin tour");
        $.ajax({
            url: 'finTour.php',
            type: 'GET',
            cache: true,
            data: 'idGrille=' + idGrille,
            success: function(reponse) {
                console.log(reponse);
                if(reponse == "Error")
                    alert("Il faut cliquer sur un pion !!");
                else
                    document.location.reload(true);
            }
        });
    }
</script>