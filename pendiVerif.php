<?php
    require '_conf.php';
    
    $pdo=new PDO("mysql:host=$bdd_server;dbname=$bdd_name","$bdd_user","$bdd_password");
    
    if (is_null(filter_input(INPUT_POST, 'ajax'))) {
        header('Location: index.php');
    }
    
    
    else if (filter_input(INPUT_POST, 'ajax') == 'demande'){
        
        $lettre = filter_input(INPUT_POST,'lettre');
        $login = filter_input(INPUT_POST, 'login');
        
        
        
    // recherce d'un mot dans la BDD via l'id généré de manière aléatoire
        $reqWord = $pdo->prepare('SELECT pendi_word FROM utilisateurs WHERE pseudo= :login');
        $reqWord->bindParam(':login',$login, PDO::PARAM_STR);
        $reqWord->execute();

        $resWord = $reqWord->fetchAll();

    // on recupere la longueur du mot obtenu    
        foreach ($resWord as $word) {
            $wordRandom = $word['pendi_word'];
            $wordTaille = strlen($wordRandom);
        }
        
        $array=array();
        
        for ($i = 0; $i < $wordTaille ; $i++) {
            if ( $lettre === $wordRandom[$i]) {
                array_push($array, $i+1);
                
            } 
        }
        echo json_encode($array);

    }
    
    else if (filter_input(INPUT_POST, 'ajax') == 'win'){
        
        $login = filter_input(INPUT_POST, 'login');
        
        
        $reqMoney = "UPDATE utilisateurs
                     INNER JOIN dictionnaire ON dictionnaire.mots=utilisateurs.pendi_word
                     SET utilisateurs.solde = utilisateurs.solde + dictionnaire.reward
                     WHERE pseudo = :login ";
        $resMoney= $pdo->prepare($reqMoney);
        $resMoney->bindParam(':login', $login, PDO::PARAM_STR);
        $resMoney->execute();

        echo 'Félicitation! Tu as trouvé le mot!';
        
    }
    else if (filter_input(INPUT_POST, 'ajax') == 'lose'){
        $login = filter_input(INPUT_POST, 'login');
        
// recherce d'un mot dans la BDD via l'id généré de manière aléatoire
        $reqWord = $pdo->prepare('SELECT pendi_word FROM utilisateurs WHERE pseudo= :login');
        $reqWord->bindParam(':login',$login, PDO::PARAM_STR);
        $reqWord->execute();

        $resWord = $reqWord->fetchAll();

// on recupere la longueur du mot obtenu    
        foreach ($resWord as $word) {
            $wordRandom = $word['pendi_word'];
            $wordTaille = strlen($wordRandom);
        }
    
        echo 'Raté! le mot était : '.$wordRandom.' viens retenter ta chance!'; 
    }