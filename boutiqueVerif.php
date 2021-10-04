<?php
// intégralité de la page faite par daniel
    require '_conf.php';
    
    $pdo=new PDO("mysql:host=$bdd_server;dbname=$bdd_name","$bdd_user","$bdd_password");
    
// sécurtié qui redirect un utilisateur malveillant
    if (is_null(filter_input(INPUT_POST, 'ajax'))) {
        header('Location:index.php');
    }
    else if (filter_input(INPUT_POST,'ajax') == '1'){
    
// récupération du nom de l'objet et de l'user
        $item = filter_input(INPUT_POST,'item');
        $login = filter_input(INPUT_POST,'login');
        
// recherche les infos de l'objet dans la BDD    
        $senObjects = "SELECT * FROM objets WHERE nom_objet = ?";
        $reqObjects = $pdo->prepare($senObjects);
        $reqObjects->execute( [$item] );
        $resObjects = $reqObjects->fetch(PDO::FETCH_ASSOC);
        
// stock le prix et l'id de l'objet
        $prix = $resObjects['prix'];
        $id_objet = $resObjects['id_objet'];
        
// recherche les infos de l'user 
        $senUser = "SELECT * FROM utilisateurs WHERE pseudo = :pseudo";
        $reqUser = $pdo->prepare($senUser);
        $reqUser->bindParam(':pseudo', $login, PDO::PARAM_STR);
        $reqUser->execute();
        $resUser = $reqUser->fetch(PDO::FETCH_ASSOC);
      
// stock le solde et l'id de l'user
        $id_user = $resUser['id_user'];
        $solde = $resUser['solde'];
        
// si le joueur est en mesure d'acheter rentre dans la boucle
        if ( $solde >= $prix) {
            
            $newSolde = $solde - $prix;
            
// actualise le nouveau solde de l'user dans la BDD
            $senUpdateUser = "UPDATE utilisateurs
                          SET solde = :solde
                          WHERE pseudo = :login";
            $reqUpdateUser = $pdo->prepare($senUpdateUser);
            $reqUpdateUser->bindParam(':solde', $newSolde, PDO::PARAM_INT);
            $reqUpdateUser->bindParam(':login', $login, PDO::PARAM_STR);
            $reqUpdateUser->execute();
            
// ajoute l'objet dans l'inventaire de l'user dans la BDD
            $senUpdateObject = "INSERT INTO possession(id_user,id_objet) VALUES
                                (:user,:objet)";
            $reqUpdateObject = $pdo->prepare($senUpdateObject);
            $reqUpdateObject->bindParam(':user', $id_user, PDO::PARAM_INT);
            $reqUpdateObject->bindParam(':objet', $id_objet, PDO::PARAM_INT);
            $reqUpdateObject->execute();
            
// renvoie la confirmation valid ou error en fonction de si
// l'user a pu acheter l'objet ou non.
            echo "valid";
        }
        else {
            echo "error";
        }
    }
