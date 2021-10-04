<?php
// intégralement fait par Daniel
    require '_conf.php';
    
    $pdo=new PDO("mysql:host=$bdd_server;dbname=$bdd_name","$bdd_user","$bdd_password");
    
// securité anti user malveillant
    if (is_null(filter_input(INPUT_POST, 'ajax'))) {
        header('Location:index.php');
    }
    // requete ajax permettant le changement du morceau d'avatar
    else if (filter_input(INPUT_POST,'ajax') == '1'){
    
    // récupération des infos de la requete ajax
        $objet = filter_input(INPUT_POST,'objet');
        $login = filter_input(INPUT_POST,'login');
        $corps = filter_input(INPUT_POST,'corps');

    // récupération du morceau a mettre
        $reqUpdateImg = "SELECT * FROM objets WHERE nom_objet= :objet";
        $stmt=$pdo->prepare($reqUpdateImg);
        $stmt->bindParam(':objet', $objet, PDO::PARAM_STR);
        $stmt->execute();

        $lien = $stmt->fetch(PDO::FETCH_ASSOC);

    // renvoie du lien ( chemin d'acces ) de l'image
        echo $lien['lien'];

        
    // mise a jour dans la BDD du nouveau avatar de l'user
        $reqUpdateUser = "UPDATE avatar INNER JOIN utilisateurs ON utilisateurs.id_user=avatar.id_user";
        $reqUpdateUser .= " SET avatar.lien = :lien WHERE utilisateurs.pseudo= :login AND avatar.type = :type ";
        $sth = $pdo->prepare($reqUpdateUser);
        $sth->bindParam(':lien', $lien['lien'], PDO::PARAM_STR);
        $sth->bindParam(':login', $login, PDO::PARAM_STR);
        $sth->bindParam(':type', $corps, PDO::PARAM_STR);
        $sth->execute();
    }
    // requete ajax permettant le changement de password
    else if (filter_input(INPUT_POST,'ajax') == '2'){
        
    // recupération des infos de la requete ajax
        $login = filter_input(INPUT_POST,'login');
        $pw = filter_input(INPUT_POST,'pw');
        $new_pw = filter_input(INPUT_POST,'new_pw');
        
    // récupération des infos de l'user
        $reqInfo = "SELECT * FROM utilisateurs WHERE pseudo= :login";
        $stmt=$pdo->prepare($reqInfo);
        $stmt->bindParam(':login', $login, PDO::PARAM_STR);
        $stmt->execute();
        
    // vérification si le mdp saisit est le meme que dans la BDD
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $pw_hash = $user['password'];
        
        if (password_verify($pw, $pw_hash) ) {
            
    // si les mdp sont identique on change le mdp dans la BDD
            $pw = password_hash($new_pw, PASSWORD_BCRYPT);
            $reqUpPw = "UPDATE utilisateurs SET password= :pw WHERE pseudo= :login";
            $stmt=$pdo->prepare($reqUpPw);
            $stmt->bindParam(':login', $login, PDO::PARAM_STR);
            $stmt->bindParam(':pw', $pw, PDO::PARAM_STR);
            $stmt->execute();

    // renvoie du boolean
            echo 'yes';
        }
        else {
            echo 'non';
        }
    }