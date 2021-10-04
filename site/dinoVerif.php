<?php
// intégralité fait par daniel
    require '_conf.php';
    
// sécurité contre les utilisations malveillantes
    if (is_null(filter_input(INPUT_POST, 'ajax'))) {
        header('Location: index.php');
    }
    
    $pdo=new PDO("mysql:host=$bdd_server;dbname=$bdd_name","$bdd_user","$bdd_password");
    
// récupération du score et de l'user
    $score = filter_input(INPUT_POST,'score');
    $login = filter_input(INPUT_POST,'login');
    
// récupération des infos de l'user
    $reqBest = "SELECT best_dino FROM utilisateurs WHERE pseudo= ?";
    $stmt=$pdo->prepare($reqBest);
    $stmt->execute( [$login] );
    $resBest=$stmt->fetch(PDO::FETCH_ASSOC);
    
// stock son meilleure score actuel dans la BDD
    $bestScore = $resBest['best_dino'];
    
// si le score obtenu est meilleur que le précédant on rentre dans la condition
    if ($score > $bestScore) {
        
// on met à jour la BBD avec le nouveau score
        $reqUpdateScore = "UPDATE utilisateurs SET best_dino = :score WHERE pseudo= :login";
        $sth=$pdo->prepare($reqUpdateScore);
        $sth->bindParam(':score', $score, PDO::PARAM_INT);
        $sth->bindParam(':login', $login, PDO::PARAM_STR);
        $sth->execute();
      
// renvoie un booléan record ou normal
        echo 'record';
    }
    else {
        echo 'normal';
    }