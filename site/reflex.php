<?php    
    session_start();
    $login = $_SESSION['pseudo'];
    if (is_null($login)) {
        header('Location: ./index.php');
    }
    require '_conf.php';
    $pdo=new PDO("mysql:host=$bdd_server;dbname=$bdd_name","$bdd_user","$bdd_password");
    
    $senSolde = "SELECT solde FROM utilisateurs WHERE pseudo = :pseudo";
    $reqSolde = $pdo->prepare($senSolde);
    $reqSolde->bindParam(':pseudo', $login, PDO::PARAM_STR);
    $reqSolde->execute();
    $resSolde = $reqSolde->fetch(PDO::FETCH_ASSOC);

    $solde = $resSolde['solde'];
    
?>
<html>
    <head>
        <meta charset="utf-8">

        <!--Lien pour integrer le fichier avec le CSS de la page-->
        <link href="style.css" rel="stylesheet">
        <meta name="viewport" content="width=device-width" />
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

    </head>

    <body style="background: #0065a0">

        <div  id="block" class="col-md-8 col-sm-12" style="text-align: justify-all; background: #121287;margin:0 auto 50px; border-radius:10px;" >
            <div class="row d-flex justify-content-around" style="text-align: center">
                <div class="col-md-2"><h3 class="ecriture"><a href="./boutique.php"><u style="color: white;">Boutique</u></a></h3></div>
                <div class="col-md-2"><h3 class="ecriture"><a href="./leaderboard.php"><u style="color: white;">Classement</u></a></h3></div>
                <div class="col-md-2"><h3 class="ecriture"><a href="./gallerie.php"><u style="color: white;">Gallerie</u></a></h3></div>
                <div class="col-md-2"><h3 class="ecriture"><a href="./profil.php"><u style="color: white;"><?php echo $login ?></u></a></h3></div>
                <div class="col-md-2"><h4 id="dollar" style="color: goldenrod; line-height: 1.5;"><?php echo $solde ?>$</h4></div>
                <div class="col-md-1"><a href="logout.php"><img style="height: 30px;" src="./img/eteindre1.png"></a></div>
            </div>
        </div>
        
        
        <div id="rf_text" class="col-md-8" style="font-size: 1.5em; text-align: center; margin: auto">

            <p>Bienvenue dans tap reflex !</p>
            <p>Le principe est simple!</p>
            <p>en appuyant sur strat, tu devras attendre que le carré passe au <br/>
               rouge pour cliquer avec ta sourie le plus rapidement possible ! A toi de jouer !</p>
        </div>
        
        <div id="rf_case" class="col-md-6">
            <button id="rf_button" class="col-md-6" onclick="debut_decompte()"> START </button> 
        </div>                        

        <script>
            var enCours = 0;
            var dateDebut = 0;
            var dateFin = 0;
            var depart = null;
            var blanc =0;

// lorsque on clique trouve unnombre aleatoire puis lance le decompte avant de lancer le changement de couleur
            function debut_decompte() {
                document.getElementById("rf_button").style.display= "none";
                sec = getRandom(2,7);
                sec = sec * 1000;
                depart = setTimeout(changement_couleur, sec);
                $("#rf_case").click(function(){ stop(); });
            }

// une fois le decompte fini on lance le chrono, on change la couleur
            function changement_couleur(){
                document.getElementById("rf_case").style.background= "blue";
                enCours = 1;
                dateDebut = new Date();
                }

// lorsqu'on clique sur la case on lance la fonction stop
            function stop() {
                
                // lorsque l'event listener est ajouté il lance minimum une fois donc 
                // c'est pour eviter le coup à blanc
                if ( blanc === 0 ) {
                    ++blanc;
                    return;
                }
                // le coup a blanc est evité le prochain clique sera celui de l'user
                // et donc sera lui transmit a la fonction stop
                else if (blanc === 1) {
                    $("#rf_case").click(function(){ $("#rf_case").unbind(); });
                    ++blanc;
            
                // on detecte si le changement de couleur a eut lieu
                // SI OUI - alors on prend la fin du chrono puis esthetique
                // SI NON - alors on a un faux départ puis esthetique
                    if ( enCours === 1 ) {
                        dateFin = new Date();
                        dateFin -= dateDebut;  
                        
                        // on interroge la BDD pour savoir si c'est un record ou non
                         $.post( "reflexVerif.php", { ajax: '1', score: dateFin, login: "<?php echo $login ?>" })
                        .done(function(status) {
                            switch(status) {
                                // si c'est un record alors on fait ça:
                                case 'record':
                                    
                                    alert('Félicitation tu as battus ton record!\n Tu as fais '+dateFin+' ms');
                                    document.location.reload(true);
                                    break;
                                // si c'est un score normal alors on fait ça
                                case 'normal':
                                    
                                    alert('Hélas tu as fais '+dateFin+' ms.\n Tu n\'as pas battus ton record');
                                    document.location.reload(true);
                                    break;
                            }
                        }); 
                    }
                    else if ( enCours === 0) {
                        clearTimeout(depart);
                        alert('Faux depart essayez de nouveau');
                        document.location.reload(true);
                    }
                }
            }
            
// fonction permettant de générer un nombre aléatoire            
            function getRandom(min, max) {
                min = Math.ceil(min);
                max = Math.floor(max);
                return Math.floor(Math.random() * (max - min +1)) + min;
            }

        </script>    
    </body>
</html>

