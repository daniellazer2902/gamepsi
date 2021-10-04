<?php
// intégralité faite par Daniel
    session_start();
    
// sécurité anti user malveillant
        $login = $_SESSION['pseudo'];
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
    
    // récupération des donnés de tout les USERS dans l'odre DESc de leur argent
    $reqUsersInfo = "SELECT pseudo,best_dino,best_reflex,solde FROM utilisateurs ORDER BY solde DESC";
    $stmt=$pdo->prepare($reqUsersInfo);
    $stmt->execute();
    $tabUsers = $stmt->fetchAll();
    
    $rang = 1;
?>
<html>
    <head>
        <meta name = "viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <link rel="stylesheet" href="style.css">
        
        <style>
            @media screen and (min-width: 768px) {
                #container {
                    width: 900px;
                }
                
            }  
        </style>
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

        <div id="container" class=" col-sm-10" style="background:white; margin: 100px auto 50px;display: flex; justify-content: center ">
            <table id="ld_table" class="table-responsive-sm table-responsive-md">
                <tr class="tr_border">
                    <th>Avatar</th>
                    <th>Position</th>
                    <th>Pseudo</th>
                    <th>Solde</th>
                    <th>Score Dino</th>
                    <th>Score Reflex</th>
                </tr>
                <?php foreach( $tabUsers as $user) { ?>

                <tr class="tr_border">
                    <td class="moyen_avatar " style="">
                        
                        <?php
                            // affichage de l'avatar par users 
                            $reqDisplayImg = "SELECT pseudo,avatar.lien FROM utilisateurs
                                                INNER JOIN avatar ON avatar.id_user=utilisateurs.id_user
                                                INNER JOIN objets ON avatar.lien=objets.lien";
                            $reqDisplayImg .= " WHERE utilisateurs.pseudo= :pseudo";
                            $stmt=$pdo->prepare($reqDisplayImg);
                            $stmt->bindParam(':pseudo', $user['pseudo'], PDO::PARAM_STR);
                            $stmt->execute();
                            $resDisplayImg = $stmt->fetchAll();

                        foreach ($resDisplayImg as $morceau) { ?>
                        <img class="moyen" style="position: absolute; border-radius: 50%" src="./img/avatar/<?=$morceau['lien'] ?>" alt="">
                        <?php } ?>
                    </td>
                    <td class="ld_rang"><?php echo $rang++; ?></td>
                    <td class="ld_pseudo"><?php echo $user['pseudo'] ?></td>
                    <td class="ld_pseudo"><?php echo $user['solde'] ?></td>
                    <td class="ld_dino"><?php echo $user['best_dino'] ?></td>
                    <td class="ld_reflex d-xs-none"><?php echo $user['best_reflex'] ?> ms</td>
                </tr>
                <?php } ?>
            </table>
        </div>
        
        <script>
        // script permettant la disparition des avatars
        // pour les version tablettes lors du scroll
        var table = document.getElementById('ld_table');
        $('#ld_table').on('scroll', function() {
            if(table.scrollLeft>10){
                $('.moyen').css('display','none');
            }
            else {
                $('.moyen').css('display','inline-block');
            }
        });

        </script>
    </body>
</html>