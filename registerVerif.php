<?php
    require '_conf.php';
        
    $pdo=new PDO("mysql:host=$bdd_server;dbname=$bdd_name","$bdd_user","$bdd_password");

    $pseudo = filter_input(INPUT_POST,'pseudo',FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST,'email',FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST,'password');

    $taillepassword = strlen ($password );
    $tailleemail= strlen($email);
    $taillepseudo= strlen($pseudo);
    
    $senSolde = "SELECT * FROM utilisateurs WHERE pseudo = :pseudo";
    $reqSolde = $pdo->prepare($senSolde);
    $reqSolde->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
    $reqSolde->execute();
    $resSolde = $reqSolde->fetch(PDO::FETCH_ASSOC);

    $senEmail = "SELECT * FROM utilisateurs WHERE email = :email";
    $reqEmail = $pdo->prepare($senEmail);
    $reqEmail->bindParam(':email', $email, PDO::PARAM_STR);
    $reqEmail->execute();
    $resEmail = $reqEmail->fetch(PDO::FETCH_ASSOC);
    
    
    if( $pseudo === $resSolde['pseudo']) {
        echo 'errorLogin';
    }
    else if( $email === $resEmail['email']) {
        echo 'errorEmailExist';
    }
    else if($taillepassword<6 ){
        echo 'errorCourt';
    }
    else if ($taillepassword>20){
        echo 'errorLong';
    }
    else if($taillepseudo<3 ){
        echo 'errorPseCourt';
    }
    else if ($taillepseudo>20){
        echo 'errorPseLong';
    }
    else if($tailleemail<7){
        echo 'errorEmail';
    }
    else if ( $pseudo !== $resSolde['pseudo'] && $email !== $resEmail['email']){
        $password_hash = password_hash($password, PASSWORD_BCRYPT);

        $senInsertUser = 'INSERT INTO utilisateurs (pseudo, password, email,solde,best_dino) VALUES (:login, :password, :email, 0, 0)';
        $reqInsertUser = $pdo->prepare($senInsertUser);
        $reqInsertUser->bindParam(':login', $pseudo, PDO::PARAM_STR);
        $reqInsertUser->bindParam(':password', $password_hash, PDO::PARAM_STR);
        $reqInsertUser->bindParam(':email', $email, PDO::PARAM_STR);
        $reqInsertUser->execute();
        
        $senGetUser = "SELECT * FROM utilisateurs WHERE pseudo = :pseudo";
        $reqGetUser = $pdo->prepare($senGetUser);
        $reqGetUser->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
        $reqGetUser->execute();
        $resGetUser = $reqGetUser->fetch(PDO::FETCH_ASSOC);
        
        $id_user = $resGetUser['id_user'];
      
        
        $senUpdateObject = "INSERT INTO possession(id_user,id_objet) VALUES
                            (:user,1),(:user2,5),(:user3,7),(:user4,9),(:user5,17),(:user6,18)";
        $reqUpdateObject = $pdo->prepare($senUpdateObject);
        $reqUpdateObject->bindParam(':user', $id_user, PDO::PARAM_INT);
        $reqUpdateObject->bindParam(':user2', $id_user, PDO::PARAM_INT);
        $reqUpdateObject->bindParam(':user3', $id_user, PDO::PARAM_INT);
        $reqUpdateObject->bindParam(':user4', $id_user, PDO::PARAM_INT);
        $reqUpdateObject->bindParam(':user5', $id_user, PDO::PARAM_INT);
        $reqUpdateObject->bindParam(':user6', $id_user, PDO::PARAM_INT);
        $reqUpdateObject->execute();
        
        $senUpdateAvatar = "INSERT INTO avatar(id_user,type,lien) VALUES
                            (:user,'shoul','1shoul_black.png'),(:user2,'color','2color_y.png'),
                            (:user3,'smile','3smile_poker.png'),(:user4,'eye','4eye_dot.png'),
                            (:user5,'acces','5acces_blank.png'),(:user6,'hat','6hat_blank.png')";
        $reqUpdateAvatar = $pdo->prepare($senUpdateAvatar);
        $reqUpdateAvatar->bindParam(':user', $id_user, PDO::PARAM_INT);
        $reqUpdateAvatar->bindParam(':user2', $id_user, PDO::PARAM_INT);
        $reqUpdateAvatar->bindParam(':user3', $id_user, PDO::PARAM_INT);
        $reqUpdateAvatar->bindParam(':user4', $id_user, PDO::PARAM_INT);
        $reqUpdateAvatar->bindParam(':user5', $id_user, PDO::PARAM_INT);
        $reqUpdateAvatar->bindParam(':user6', $id_user, PDO::PARAM_INT);
        $reqUpdateAvatar->execute();
        
        session_abort();
        session_start();
        $_SESSION['pseudo'] = $pseudo; /*affiche connexion réussie*/
        
        echo 'valid';
    }

