<?php
    require '_conf.php';
    
    if (is_null(filter_input(INPUT_POST, 'ajax'))) {
        header('Location:index.php');
    }
    else {
        $pdo=new PDO("mysql:host=$bdd_server;dbname=$bdd_name","$bdd_user","$bdd_password");

        $score = filter_input(INPUT_POST,'score');
        $login = filter_input(INPUT_POST,'login');

        $reqBest = "SELECT best_reflex FROM utilisateurs WHERE pseudo= ?";
        $stmt=$pdo->prepare($reqBest);
        $stmt->execute( [$login] );
        $resBest=$stmt->fetch(PDO::FETCH_ASSOC);

        $bestScore = $resBest['best_reflex'];

        if ($score < $bestScore || is_null($bestScore)) {

            $reqUpdateScore = "UPDATE utilisateurs SET best_reflex = :score WHERE pseudo= :login";
            $sth=$pdo->prepare($reqUpdateScore);
            $sth->bindParam(':score', $score, PDO::PARAM_INT);
            $sth->bindParam(':login', $login, PDO::PARAM_STR);
            $sth->execute();

            echo 'record';
        }
        else {
            echo 'normal';
        }
    }