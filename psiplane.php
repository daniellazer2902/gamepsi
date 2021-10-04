<?php
// intégralité faite par William
    session_start();
    $login = $_SESSION['pseudo'];
    
// sécurité anti user malveillant
    if (is_null($login)) {
        header('Location: ./index.php');
    }
    require '_conf.php';
    $pdo=new PDO("mysql:host=$bdd_server;dbname=$bdd_name","$bdd_user","$bdd_password");
    
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
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <style>
        #body{
            background-color:#0065a0;
        }
        #ecriture{
            text-align: center;
            font-family:Mv boli;
            color: white;
        }
        .ecriture{
            font-family:Mv boli;
            text-decoration: underline white;
        }
        
        #text_non_compa{
            display: none;
        }
        /* affiche le text d'incompatibilité' */
        @media only screen and (max-width:800px)  {
            #text_non_compa{
                display: block;
            }
        }
        </style>
</head>
<body id="body" style="text-align: center">

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
  
    <iframe class="d-none d-md-block w-50 h-75" style="border: none;margin: 50px auto;" src="https://storage.googleapis.com/files.gamefroot.com/users/3363577/games/180920/gamefroot-2019-11-14T14-39-37.302Z/index.html?embed=true"></iframe>
    <span id="text_non_compa" style="color:white; font-size: 1.5em;">Le jeu n'est pas disponible sur tablette et portable !<br> Veuillez réessayez sur ordinateur :)</span>


</body>
</html>
    
    

























