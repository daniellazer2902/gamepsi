<!-- page faite par Marine et Daniel -->
<html>
    <head>
       <meta charset="utf-8">
       
        <!--Lien pour integrer le fichier avec le CSS de la page-->
        <link href="style_login.css" rel="stylesheet"> 
        <meta name = "viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    </head>
  
    <body style="background-color: #0065A0;">
        
        <div id="GamePsi">
            <h1><u><b>GamePsi</b></u><h1>
        </div>
            
            
        <div id="Connexion">
            <h1> Se connecter </h1>
        </div>
   
                
        <div id="alignement">
        <form autocomplete="off">
                      
            <div class="form-group col-sm-4" style="margin: 0 auto 10px; text-align: center;">
            <label id="label1" style="font-size: 20px; color: white;">Ton pseudo</label>
            <input type="text" name="login" id="login" class="form-control" autocomplete="off">
            </div>
            
            <div class="form-group col-sm-4" style="margin: 0 auto 10px; text-align: center;">
            <label id="label2" style="font-size: 20px; color: white">Mot de passe</label>
            <input type="password" name="mdp" id="mdp">
            </div>
            
            <div style="margin:20px" class="d-flex justify-content-center">
            <input type="button" onclick="change_pw()" class="btn" style="font-size: 20px" value="Login">
            </div>
    
            <div id="inscription" class="form-group col-sm-4" style="margin: 0 auto 10px; text-align: center;">
                <a href="./register.php" style="color: white">Tu n'as pas encore de compte? Inscris-toi !</a>
            </div>   
            
        </div>
        
        </form>
        </div>
        
        <script>
            //script fait par Marine avec Daniel en soutient
            function change_pw() {
                // remet les cases blanches
                $("input[type='password']").css('background', 'white');
                
                // récupération des login et mdp
                var login = document.getElementById("login").value;
                var mdp = document.getElementById("mdp").value;
                
                    // requete ajax avec le login et mdp
                    $.post( "indexVerif.php", { ajax: '1', login: login, mdp: mdp})
                        .done(function(res) {
                            if ( res === 'valid') {
                                window.location.href = "./gallerie.php";
                            }
                            if ( res === 'error'){
                                
                                $("#mdp").css('background', '#ff8080');
                            }
                        });
                }
            
            
        </script>    
        
 </html>