<?php
// intégralité de la page fait par William

// sécurité qui redirect un user non connecté
    session_start();
    $login = $_SESSION['pseudo'];
    if (is_null($login)) {
        header('Location: ./index.php');
    }
    
    require '_conf.php';
    $pdo = new PDO("mysql:host=$bdd_server;dbname=$bdd_name", "$bdd_user", "$bdd_password");

// récupération du solde de l'user
    $senSolde = "SELECT solde FROM utilisateurs WHERE pseudo = :pseudo";
    $reqSolde = $pdo->prepare($senSolde);
    $reqSolde->bindParam(':pseudo', $login, PDO::PARAM_STR);
    $reqSolde->execute();
    $resSolde = $reqSolde->fetch(PDO::FETCH_ASSOC);

    $solde = $resSolde['solde'];
?>

<html>
    <head>
        <meta name = "viewport" content="width=device-width, initial-scale=1">
        <meta name="viewport" content="width=device-width" />
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <style>
        
        #body{
            background-color:#0065a0;
        }
        #barre{
            border-radius:10px;
        }
        
        #block1{
            background-color:white;
            border-top-left-radius: 5%;
        }
        #block2{
            background-color:white;
            border-top-right-radius: 5%;
        }
        #block3{
            background-color:white;
            border-bottom-left-radius: 5%;
        }
        #block4{
            background-color:white;
            border-bottom-right-radius: 5%;
        }
        #miniatures2{
            
            margin-top: 5px;
            height:30px;
            width:30px;
        }
        @media only screen and (max-width:800px)  {
            #block1{
            background-color:white;
            border-top-left-radius: 0%;
            }
            #block2{
                background-color:white;
                border-top-right-radius: 0%;
            }
            #block3{
                background-color:white;
                border-bottom-left-radius: 0%;
            }
            #block4{
                background-color:white;
                border-bottom-right-radius: 0%;
            }
            #miniatures2{
            
            margin-top: 0px;
            margin-bottom:5px;
            height:30px;
            width:30px;
            }
        }

        #miniatures{
            height:125px;
            width:200px;
        }
        
        .ecriture{
            text-align: center;
            font-family:Mv boli;
            color: white;

        }
        #dollar{
            text-align: center;
            color: white;
        }
        #btneteindre{
            text-align:center;
        }    
            
        
        
    </style>
</head>
<body id="body">
    <div class="col-md-12" >
        <br>
        <h1 class="ecriture"><a style="color: white;" href="./gallerie.php"><u><b>GamePsi</b></u></a></h1>
        <br>
        <br>
     </div> 
    
<!-- barre de navigation présente sur tout le site -->
    <div  id="block" class="col-md-8 col-sm-12" style="text-align: justify-all; background: #121287;margin:0 auto 50px; border-radius:10px;" >
        <div class="row d-flex justify-content-around" style="text-align: center">
            <div class="col-md-2"><h3 class="ecriture"><a style="color: white;" href="./boutique.php"><u>Boutique</u></a></h3></div>
            <div class="col-md-2"><h3 class="ecriture"><a style="color: white;" href="./leaderboard.php"><u>Classement</u></a></h3></div>
            <div class="col-md-2"><h3 class="ecriture"><a style="color: white;" href="./gallerie.php"><u>Gallerie</u></a></h3></div>
            <div class="col-md-2"><h3 class="ecriture"><a style="color: white;" href="./profil.php"><u><?php echo $login ?></u></a></h3></div>
            <div class="col-md-2"><h4 id="dollar" style="color: goldenrod; line-height: 1.5;"><?php echo $solde ?>$</h4></div>
            <div class="col-md-1"><a href="logout.php"><img id="miniatures2" src="./img/eteindre1.png"></a></div>
        </div>
    </div>
    
    
    <div id="quest" class="container">
        <br>
          <div class="row">
                <div style=";" class="col-md-1">
                    <br>
                </div>
                <div id ="block1" class="col-md-4"> 
                    <br>
                        <table id="tableau" border="3" align=center>
                        <thead border="2">
                            <tr >
                                <td style="text-align:center;"><a style="color: black;" href="./reflex.php">Clic the Point</a> </td>
                            </tr>
                            </thead>
                            <tbody border="2">
                                <tr >
                                    <td><a href="./reflex.php"><img id="miniatures" src="./img/imgreflex.png"></a></td>
                                </tr>
                            </tbody>
                        </table>
                    <br>
                </div>

              
              
                <div style="background-color:white;" class="col-md-2">
                    <br>
                </div>

              
              
                <div id ="block2" class="col-md-4">
                    <br>
                    <table id="tableau" border="3" align=center>
                            <thead border="2">
                            <tr>
                                <td style="text-align:center;"><a style="color: black;" href="./pendi.php">Pendu</a></td>
                            </tr>
                            </thead>
                            <tbody border="2">
                                <tr >
                                    <td><a href="./pendi.php"><img id="miniatures" src="./img/imgpendu.png"></a></td>
                                </tr>
                                
                            </tbody>
                        </table> 
                        <div style=";" class="col-md-1">
                            <br>
                         </div>
                    <br>
                </div>
        </div>    



        <div class="row">
                <div style=";" class="col-md-1">
                    <br>
                </div>
                <div id="block3" class="col-md-4">
                    <br>
                    <table id="tableau" border="3" align=center>
                            <thead border="2">
                            <tr>
                                <td style="text-align:center;"><a style="color: black;" href="./dino.php">jeu du Dino</a></td>
                            </tr>
                            </thead>
                            <tbody border="2">
                                <tr >
                                    <td><a href="./dino.php"><img id="miniatures" src="./img/imgdino.png"></a></td>
                                </tr>
                            </tbody>
                        </table>
                    <br>
                </div>

            
            
                <div style="background-color:white;" class="col-md-2">
                    <br>
                </div>

            
            
                <div id="block4" class="col-md-4">
                    <br>
                    <table id="tableau" border="3" align=center>
                            <thead border="2">
                            <tr>
                                <td style="text-align:center;"><a style="color: black;" href="./psiplane.php">Psiplane</a></td>
                            </tr>
                            </thead>
                            <tbody border="2">
                                <tr >
                                    <td><a href="./psiplane.php"><img id="miniatures" src="./img/psiplaneimage.png"></a></td>
                                </tr>
                            </tbody>
                        </table>
                    <br>
                    <div style=";" class="col-md-1">
                    <br>
                    </div>
                </div>

            </div>    
    </div>
</body>