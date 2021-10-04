<?php
// intégralité de la page fait par Marine sauf PDO
    require '_conf.php';
    
    $pdo=new PDO("mysql:host=$bdd_server;dbname=$bdd_name","$bdd_user","$bdd_password");
    
// sécurtié anti user malveillant
    if (is_null(filter_input(INPUT_POST, 'ajax'))) {
        header('Location: index.php');
    }
    
    else {
        
        $login = filter_input(INPUT_POST, 'login'); /*crée une variable $login récuperée via le formulaire du username*/
        $password = filter_input(INPUT_POST, 'mdp'); /*crée une variable $login récuperée via le formulaire du password*/
        $password_hash = password_hash ($password , PASSWORD_BCRYPT); /*crypte le password*/

        // PDO fait par Daniel
        $senLogin = "SELECT * FROM utilisateurs WHERE pseudo = :pseudo";
        $reqLogin = $pdo->prepare($senLogin);
        $reqLogin->bindParam(':pseudo', $login, PDO::PARAM_STR);
        $reqLogin->execute();
        $resLogin = $reqLogin->fetch(PDO::FETCH_ASSOC);

        $login_server = $resLogin['pseudo']; /*stock le pseudo de l'utilisateur*/
        $password_server = $resLogin['password'];/*stock le mot de passe de l'utilisateur*/

        if (is_null($login_server)){ /*si le login rentré existe*/
           echo 'error';
        }
        else {
            if ($login == $login_server){ /*si le login rentré et le login server sont identiques*/


                if (password_verify($password, $password_server)){ /*si le mot de passe rentré et identiques a celui de la BDD*/

                    session_abort();
                    session_start();
                    $_SESSION['pseudo'] = $login; /*affiche connexion réussie*/
                    echo 'valid';
                }

                else {
                    echo 'error'; /*sinon affiche mdp incorrect*/
                }
            }
        }
    }
