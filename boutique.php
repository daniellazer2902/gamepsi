<?php
// intégralité de la page par daniel
// démarage de session + sécurité anti non connecté
    session_start();
    $login = $_SESSION['pseudo'];
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
    
    
// récupération des objets possédé par l'user
    $reqOwned = "SELECT pseudo,nom_objet FROM utilisateurs
                JOIN possession ON utilisateurs.id_user=possession.id_user
                JOIN objets ON objets.id_objet=possession.id_objet
                WHERE utilisateurs.pseudo= ?";
    $sth=$pdo->prepare($reqOwned);
    $sth->execute( [$login] );
    $listOwned = $sth->fetchAll();
    
// récupération de tout les objets existants
    $senObjects = "SELECT * FROM objets";
    $reqObjects = $pdo->prepare($senObjects);
    $reqObjects->execute();
    $resObjects = $reqObjects->fetchAll();
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>GAMEPSI.FR | Boutique</title>
        <link rel="stylesheet" href="style.css">
        <meta name = "viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    </head>
    
    <body style="background: #0065a0">
        
<!-- Barre de navigation faite par William  -->
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
        
        
        <div id="listAchat" class="col-md-10" style="margin: 50px auto; display:flex; justify-content: center; flex-wrap: wrap">
            
            
            <?php foreach ($resObjects as $item) {
// listing de tout les objets 
// comparaison si l'objet est possédé ou non
// si possédé ll'objet ne s'affiche pas dans la boutique
                $verif = false;
                foreach ($listOwned as $owned) {
                    if ($owned['nom_objet'] == $item['nom_objet']) {
                        $verif = true;
                        break;
                    }
                }
                if ( $verif == false ) { ?>
            
            <a href="#" onclick="achat('<?php echo $item['nom_objet'] ?>')">
                        <div class="boutique_div">
                        <img id="<?=$item['nom_objet'] ?>" style="border: white 3px solid" class="item" src="./img/avatar/<?=$item['lien'] ?>" alt="">       
                        <span class="boutique_nom"><?php echo $item['nom_objet'] ?></span>
                        <span class="boutique_prix"><?php echo $item['prix'] ?>$</span>
                        </div>
                    </a>
            <?php } } ?>
            
        </div>
        
        <script>
            
// requete ajax, avec le nom de l'objet et l'utilisateur
// renvoie un boolean si l'user peut acheter ou non l'objet
// actualise la page si oui
            function achat(item) {
                $.post( "boutiqueVerif.php", { ajax: '1', item: item, login: "<?php echo $login ?>"})
                    .done(function(result) {
                        console.log(result);
                        if (result === 'valid') {
                            alert("Bravo!\nVous venez d'acheter :\n"+item);
                            document.location.reload(true);
                        }
                        if (result === 'error') {
                            alert("Vous ne pouvez acheter :\n"+item+"\nCar vous n'avez pas assez de pièces!");
                        }
                    });
            }
        </script>
        
    </body>
</html>