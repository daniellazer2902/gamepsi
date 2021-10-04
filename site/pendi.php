<?php
// intégralement fait par daniel
    session_start();
    $login = $_SESSION['pseudo'];
    
// sécurité anti user malveillant
    if (is_null($login)) {
        header('Location: ./index.php');
    }
    

// connexion à la BDD
    require '_conf.php';
    $pdo = new PDO("mysql:host=$bdd_server;dbname=$bdd_name", "$bdd_user", "$bdd_password");

// recuperation du solde du joueur    
    $senSolde = "SELECT solde FROM utilisateurs WHERE pseudo = :pseudo";
    $reqSolde = $pdo->prepare($senSolde);
    $reqSolde->bindParam(':pseudo', $login, PDO::PARAM_STR);
    $reqSolde->execute();
    $resSolde = $reqSolde->fetch(PDO::FETCH_ASSOC);

    $solde = $resSolde['solde'];


// recherche de l'id MAX
    $reqMaxId = $pdo->prepare('SELECT * FROM dictionnaire');
    $reqMaxId->execute();
    $resMaxId = $reqMaxId->fetchAll();
    $totalWords = count($resMaxId);
    
// selection aleatoire
    $random_id = rand(0, $totalWords-1);
    
// recherce d'un mot dans la BDD via l'id généré de manière aléatoire
    $reqWord = $pdo->prepare('SELECT * FROM dictionnaire WHERE id = :random');
    $reqWord->bindParam(':random', $random_id, PDO::PARAM_STR);
    $reqWord->execute();
    
    $resWord = $reqWord->fetchAll();
    
// on recupere la longueur du mot obtenu    
    foreach ($resWord as $word) {
        $wordRandom = $word['mots'];
        $wordTaille = strlen($wordRandom);
    }
    
    $reqInsWord = $pdo->prepare('UPDATE utilisateurs SET pendi_word = :word WHERE pseudo = :login');
    $reqInsWord->bindParam(':word', $wordRandom, PDO::PARAM_STR);
    $reqInsWord->bindParam(':login', $login, PDO::PARAM_STR);
    $reqInsWord->execute();
    
    
?>
<html>
    <head>
        <meta name = "viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="style.css">
        <title>GAMEPSI.FR | PENDU</title>
    </head>
    
    <body style="background: #0065a0">
    <!-- barre de navigation faites par William -->
        <div  id="block" class="col-md-8 col-sm-12" style="text-align: justify-all; background: #121287;margin:0 auto 50px; border-radius:10px;" >
            <div class="row d-flex justify-content-around" style="text-align: center">
                <a class="col-md-2" href="./boutique.php"><h3 class="ecriture" style="color:white">Boutique</h3></a>
                <a class="col-md-2" href="./leaderboard.php"><h3 class="ecriture" style="color:white">LeaderBoard</h3></a>
                <a class="col-md-2" href="./gallerie.php"><h3 class="ecriture" style="color:white">Gallerie</h3></a>
                <a class="col-md-2" href="./profil.php"><h3 class="ecriture" style="color:white"><?php echo $login ?></h3></a>
                <a class="col-md-2" href="./boutique.php"><h3 style="text-align: center; color: white; color: goldenrod"><?php echo $solde ?>$</h3></a>
                <a class="col-md-2" style="text-align: center" href="./logout.php"><img style="margin-top: 5px; height:30px; width:30px;" src="./img/eteindre1.png"></a>
            </div>
        </div>
        
        <div class="container">
            <div class="row" >
                
                <div id="tt" style="margin: 0 auto 20px">
                    
                    <div id="answer">
                        
                        <?php
                        // création de lettre avec l'id de son apparition
                        for ($i = 0; $i < $wordTaille; $i++) { ?>
                        
                        <a id="case_<?php echo $i+1 ?>" class="word_to_guess" href="#">_</a>
                        
                        <?php } ?>
                    </div>
                    
                    <div id="bad_list">Bad Letters: </div>

                    <!-- ========================================
                                    CLAVIER NUMERIQUE
                            execute la fonction letter_guess
                            contenant la lettre de la case
                            cliqué
                        ======================================== -->

                    <div id="clavier">
                        <a id="A" href="#" onclick="letter_guess('A')"><div class="btn-guess">A</div></a>
                        <a id="Z" href="#" onclick="letter_guess('Z')"><div class="btn-guess">Z</div></a>
                        <a id="E" href="#" onclick="letter_guess('E')"><div class="btn-guess">E</div></a>
                        <a id="R" href="#" onclick="letter_guess('R')"><div class="btn-guess">R</div></a>
                        <a id="T" href="#" onclick="letter_guess('T')"><div class="btn-guess">T</div></a>
                        <a id="Y" href="#" onclick="letter_guess('Y')"><div class="btn-guess">Y</div></a>
                        <a id="U" href="#" onclick="letter_guess('U')"><div class="btn-guess">U</div></a>
                        <a id="I" href="#" onclick="letter_guess('I')"><div class="btn-guess">I</div></a>
                        <a id="O" href="#" onclick="letter_guess('O')"><div class="btn-guess">O</div></a>
                        <a id="P" href="#" onclick="letter_guess('P')"><div class="btn-guess">P</div></a>
                        <a id="Q" href="#" onclick="letter_guess('Q')"><div class="btn-guess">Q</div></a>
                        <a id="S" href="#" onclick="letter_guess('S')"><div class="btn-guess">S</div></a>
                        <a id="D" href="#" onclick="letter_guess('D')"><div class="btn-guess">D</div></a>
                        <a id="F" href="#" onclick="letter_guess('F')"><div class="btn-guess">F</div></a>
                        <a id="G" href="#" onclick="letter_guess('G')"><div class="btn-guess">G</div></a>
                        <a id="H" href="#" onclick="letter_guess('H')"><div class="btn-guess">H</div></a>
                        <a id="J" href="#" onclick="letter_guess('J')"><div class="btn-guess">J</div></a>
                        <a id="K" href="#" onclick="letter_guess('K')"><div class="btn-guess">K</div></a>
                        <a id="L" href="#" onclick="letter_guess('L')"><div class="btn-guess">L</div></a>
                        <a id="M" href="#" onclick="letter_guess('M')"><div class="btn-guess">M</div></a>
                        <a id="W" href="#" onclick="letter_guess('W')"><div class="btn-guess">W</div></a>
                        <a id="X" href="#" onclick="letter_guess('X')"><div class="btn-guess">X</div></a>
                        <a id="C" href="#" onclick="letter_guess('C')"><div class="btn-guess">C</div></a>
                        <a id="V" href="#" onclick="letter_guess('V')"><div class="btn-guess">V</div></a>
                        <a id="B" href="#" onclick="letter_guess('B')"><div class="btn-guess">B</div></a>
                        <a id="N" href="#" onclick="letter_guess('N')"><div class="btn-guess">N</div></a> 
                    </div>
                </div>
            </div>
        </div>
        <img id="pendue" src="./img/ordi.1.png" style="width: 300px;display: none;margin: auto">
         
        <script>

// je compare la lettre cliqué avec mon mot
            var good_answer=0,
                bad_answer=0,
                response=[];
                
            function letter_guess(letter) {
                $.ajax({
                type: 'POST',
                url: 'pendiVerif.php',
                data: {ajax: 'demande', lettre: letter, login: "<?php echo $login ?>"},
                dataType: 'json',
                cache: false,
                success: function(status){
                    tab = status;
                    console.log(tab.length);
                    if (tab.length === 0) {
                        
// si le tableau est vide alors pas de correspondance dc retire la lettre              
                        document.getElementById("bad_list").innerHTML += " " + letter;
                        document.getElementById(letter).onclick = null;
                        bad_answer++;

// pour chaque erreur on ajoute une phase  à 'image du pendue
                        document.getElementById("pendue").style.display = 'block';
                        document.getElementById("pendue").setAttribute("src",'./img/ordi.'+bad_answer+'.png');
                        }
                        
                        
                    else {
                        
                        for (var i = 0; i < status.length; i++) {
                            if (i >= 9) {
                                status[i] = status[i] + status[i+1];
                                
                            }
                            
                                document.getElementById("case_"+status[i]).innerHTML = letter;
                                document.getElementById(letter).onclick = null;
                                good_answer++;
                            
                        }

                    }
                     
// detection si après mon click il y a victoire ou defaite                
                   if (good_answer === <?php echo $wordTaille ?>) {
                       
                        $.post( "pendiVerif.php", { ajax: 'win', login: "<?php echo $login ?>"})
                         .done(function(message) {
                            alert(message);
                            document.location.reload(true);
                            
                            
                        });
                    }
                    else if (bad_answer === 5) {
                       
                        $.post( "pendiVerif.php", { ajax: 'lose', login: "<?php echo $login ?>"})
                         .done(function(message) {
                            alert(message);
                            document.location.reload(true);
                        });
                    } 
                    
                }});
                  
            }
        </script>
    </body>
</html>