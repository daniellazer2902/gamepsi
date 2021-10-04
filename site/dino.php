<?php
// intégralité de la page fait par daniel
    session_start();
    $login = $_SESSION['pseudo'];

// démarage de session + sécurité anti non connecté
    if (is_null($login)) {
        header('Location: ./index.php');
    }
    
    require '_conf.php';
    $pdo = new PDO("mysql:host=$bdd_server;dbname=$bdd_name", "$bdd_user", "$bdd_password");

// récupération du solde de l'user connecté
    $senSolde = "SELECT solde FROM utilisateurs WHERE pseudo = :pseudo";
    $reqSolde = $pdo->prepare($senSolde);
    $reqSolde->bindParam(':pseudo', $login, PDO::PARAM_STR);
    $reqSolde->execute();
    $resSolde = $reqSolde->fetch(PDO::FETCH_ASSOC);

    $solde = $resSolde['solde'];
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>GAMEPSI.FR | Dino</title>
        <link rel="stylesheet" href="style.css">
        <meta name = "viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
       
        <style>
            @media screen and (min-width: 600px) {
                #screen {
                    width: 600px;
                }
                #spawner {
                    left: 600px;
                }
              }
            @media screen and (min-width: 300px) and (max-width: 600px) {
                #screen {
                    width: 100%;
                }
                #spawner {
                    left: 100%;
                }
                #score_screen {
                    border-radius: 0;
                }
              }
        </style>
        
    </head>
    <body style="background: #0065a0">
        
<!-- barre de navigation faites par William -->
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
        
        
<!-- affichage du jeu et de son déroulement fait par daniel -->
        <div id="screen">

            <div id="player"></div>
            <div id="spawner"></div>
        </div>

        <div id="score_screen" class="col-sm-4 col-md-3" style="display: none;font-size: 22px;margin: 0 auto 20px;border-style: none;text-align: center;background-color: red;border-radius: 12px;"></div> 
        <div id="start-btn" onclick="start()">START</div>
        
        <script>
            
            /*
             *===========================================
             *              SYSTEME DE JUMP
             *===========================================    
             */
            
            //var player = document.getElementById("player");
            var posY = 135;
            var counter = 0;
            
            
            // initialisation du saut
            function jump() {
                document.body.onkeypress = null;
                document.body.onclick = null;
                counter = 0;
                IntervalId = setInterval(jumping, 1);
            };
            
            
            // attribution de la barre espace et du click
            document.body.onkeypress = function(e){
                if(e.code === 'Space') {
                    jump();
                    console.log('cc');
                }
            };
            document.body.onclick = function(e){
                jump();
            };
            
                
            // animation de saut   
            function jumping(){
                var elem = document.getElementById("player");
                
                // condition de monté
                if (counter < 130 ) {
                    --posY;
                    elem.style.top = posY + 'px';
                }
                
                // condition de descente
                 else if ( counter < 260 && counter >= 130 ) {
                    ++posY;
                    elem.style.top = posY + 'px';
                }
                
                // renitialisation du saut
                else if (counter >= 260) {
                    clearInterval(IntervalId);
                    
                    document.body.onkeypress = function(e){
                        if(e.code === 'Space') {
                            jump();
                        }
                    };
                    document.body.onclick = function(e){
                        jump();
                    };
                }
                ++counter;
            }
            
            /*
             *===============================
             *       CREATION DE CIBLE
             *===============================
             */
            
            var ennemytab = [];
            var score = 0;
            var interSpawn = null;
            
            function start() {
                // début du jeu suite au click sur la balise
                // dans un interval X secondes appel la fonction d'apparation d'obstacle
                interSpawn = setInterval(spawn, 1000);
                document.getElementById("start-btn").onclick = null;
                document.getElementById("start-btn").style.display = 'none';
            }
            
            // en fonction du score augmente la difficulté du jeu
            function spawn() {
                var sec = 2000;
                if ( score <= 10) {
                    sec = randomNum(2000, 2500);
                }
                if ( score > 10 && score <= 20) {
                    sec = randomNum(1500, 1800);
                }
                    if ( score > 20 && score <= 30) {
                    sec = randomNum(1200, 1400);
                }
                    if ( score > 30) {
                    sec = randomNum(1000, 1200);
                }
                // console.log(sec);
                // une fois le timer entre chaque apparition écoulé
                // lance la fonction de création d'un obstacle
                summon();
                clearInterval(interSpawn);
                interSpawn = setInterval(spawn, sec);
                
            }
            
            function summon() {
                // on ajoute l'obstacle à la liste des obstacles
                var maxCell = ennemytab.length;
                ennemytab.push('ennemy'+maxCell);
                
                // création de la balise obstacles et de ses propriété
                var case_vide = document.createElement("div");
                var vide = document.createTextNode('');
                case_vide.appendChild(vide);
                case_vide.setAttribute("id",'ennemy'+maxCell);
                case_vide.className += "ennemy";
                document.getElementById("spawner").appendChild(case_vide);
                
                // une fois la création faites on lance la fonction
                // de déplacement de l'obstacles
                moveEnnemy('ennemy'+maxCell);
            }
            
            // fonction permettant de générer un nombre aléatoire
            function randomNum(min, max) {
                min = Math.ceil(min);
                max = Math.floor(max);
                return Math.floor(Math.random() * (max - min +1)) + min;
            }
            

            /*
             *======================================
             *        DEPLACEMENT DES CIBLES
             *======================================
             */

            
            // instanciation du déplacement de l'obstacle
            function moveEnnemy(ennemy) {
                
                var y = randomNum(120,170);
                var compteur = 580;
                var posX = -10;
                
               // var elem = document.getElementById(ennemy);
                document.getElementById(ennemy).style.top =y+'px';
                
                // init de la boucle permettant le déplacement
                // fait par un compteur qui une fois atteint 0
                // tue l'obstacle pour éviter les lags après X temps
                interMove = setInterval(function(){
                    if (document.getElementById(ennemy) === null) {
                        return;
                    }
                    else {
                        
                        // a chaque rotation on appelle la fonction qui permet
                        // de detecter si on a touché le joueur ou non
                        // toujours avec l'instanciatin de l'obstacle
                        detectHitBox(ennemy);
                        --compteur; 

                        if (compteur > 0) {
                            --posX;
                            document.getElementById(ennemy).style.left=  posX +'px';
                        }
                        if (compteur <= 0) {
                            document.getElementById("spawner").removeChild(document.getElementById(ennemy));
                            ++score;
                            return;
                        }  
                    }
                },5);
            }
        
        
            /*
             *======================================
             *        DETECTION DES HITBOXS
             *======================================
             */
            
            function detectHitBox(ennemy) {
                // sécurité pour éviter les messages d'erreur
                // lors de la supression de l'obstacle
                if (document.getElementById(ennemy) === null) {
                    return;
                }
                else {
                    // si l'obstacle existe tjr, on utilise une propriété
                    // afin de récupérer les coordonné de l'élément joueur et obstacle
                    var ennemyH = document.getElementById(ennemy).getBoundingClientRect();
                    var playerH = document.getElementById("player").getBoundingClientRect();
                    
                    // appel de la fonction collision afin de savoir s'il y a eu collision ou non
                    // si oui, appel de la fonction fin de jeu
                    // + requete ajax pour savoir s'il y a un nouveau record ou non.
                    if (!Collision(playerH, ennemyH)=== true) {
                        
                        end();
                        
                        $.post( "dinoVerif.php", { ajax: '1',score: score, login: "<?php echo $login ?>" })
                        .done(function(status) {
                            console.log(status);
                            switch(status) {
                                case 'record':
                                    
                                    document.getElementById("score_screen").innerHTML= "Félicitation, !\nScore: "+score+"\n Appuyez ici ou actualisez la page";                 
                                    break;
                                
                                case 'normal':
                                    
                                    
                                    document.getElementById("score_screen").innerHTML= "Perdu! \nScore: "+score+"\n Appuyez ici ou actualisez la page";
                                    break;
                            }
                        });
                    } 
                }
            }
              
            function Collision(a, b) {
                return (
                    ((a.y + a.height) < (b.y)) ||
                    (a.y > (b.y + b.height))   ||
                    ((a.x + a.width) < b.x)    ||
                    (a.x > (b.x + b.width))
                );
            }
            
            // lorsque le fin de jeu est enclenché
            // on stop toute les boucles
            // puis on fait apparaitre l'écran des scores
            function end() {
                clearInterval(interSpawn);
                clearInterval(interMove);
                document.getElementById("spawner").innerHTML= '';
                document.getElementById("score_screen").style.display = 'block';
                
                $(document).ready(function(){
                    $('#score_screen').click(function() { 
                        document.location.reload(true);
                    });
                });
            }
            
            
        </script>
    </body>
</html>