<html>
    <head>
        <meta name = "viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8"></head>
        <style>
            #quest{
                background:#0065a0;
                text-align: center;


            }
            #ajout {
                text-align: left ;
            }
            div.inside{
                width: 175px;
                text-align: center;
                margin-left: auto;
                margin-right: auto;

            }
            .boxp{
                border-radius: 50%;
            }
        </style>
    </head>

    <body>
         <div class="col-md-12" >
            <br>
            <a href="./gallerie.php"><h1 style="text-align: center; font-family:Mv boli; color: #0065a0;"><u><b>GamePsi</b></u></h1></a>
            <br>
            <br>
         </div> 
            <div id="quest" class="container-fluid">
                <br>
                  <div class="row">
                    <div id ="block 1" class="col-md-4">
                        <h3 style="color: white;" >INSCRIS-TOI ET :</h3> 
                        <br>
                        <br>
                        <p style="color: white;">+ Obtient des miniatures</p> 

                        <p style="color: white;">+ Interagis avec les autres joueurs</p>

                        <p style="color: white;">+ Remporte un max de récompenses !</p>
                        <br>
                    </div>



                    <div id ="block 2" class="col-md-4">
                        <form autocomplete="off">
                        <h2 style="color: white;">CREATION DE VOTRE COMPTE:</h2>
                        <br>
                        <div class="boxp" style="border-radius: 50%;"></div>
                        <p><input id="pseudo" type="text" name="pseudo" placeholder="Pseudo:"></p>
                        <p class="boxp"><input id="pw" type="password" name="password" placeholder="Mot De Passe:"></p>
                        <p class="boxp"><input id="email" type="email" name="email" placeholder="Email:"></p>
                            <div class="inside">
                                <p style="color: white;">En vous inscrivant, vous acceptez les Conditions d'utilisation et la Politique de confidentialité, y compris l'utilisation des cookies.</p>
                            </div>
                        <input type="button" onclick="sign_on()" value="S'INSCRIRE">

                        </form>
                    </div>
                    <div id="block 3" class="col-md-4"></div>
                </div>    
            </div>
        
        
        <script>
            
            function sign_on() {
                
                $("input[type='password']").css('background', 'white');
                
                
                var pseudo = document.getElementById("pseudo").value;
                var pw = document.getElementById("pw").value;
                var email = document.getElementById("email").value;
                
                $.post( "registerVerif.php", { ajax: '1', pseudo: pseudo, password: pw, email: email })
                    .done(function(res) {
                        console.log(res);
                        if ( res === 'valid') {
                            window.location.href = "./gallerie.php";
                        }
                        else if ( res === 'errorCourt'){
                            $("#pw").css('background', '#ff8080');
                            alert('La taille du mot de passe est trop petite');
                        }
                        else if ( res === 'errorLong') {
                            $("#pw").css('background', '#ff8080');
                            alert('La taille du mot de passe est trop grande');
                        }
                        else if ( res === 'errorPsecourt') {
                            $("#pseudo").css('background', '#ff8080');
                            alert('Le pseudo trop court');
                        }
                        else if ( res === 'errorPseLong') {
                            $("#pseudo").css('background', '#ff8080');
                            alert('Le pseudo est trop grand');
                        }
                        else if ( res === 'errorEmail') {
                            $("#email").css('background', '#ff8080');
                            alert('Email invalide');
                        }
                        else if ( res === 'errorLogin') {
                            $("#pseudo").css('background', '#ff8080');
                            alert('Le pseudo existe déjà');
                        }
                        else if (res === 'errorEmailExist') {
                            $("#email").css('background', '#ff8080');
                            alert('L\'email existe déjà');
                        }
                    });
            }
        </script>

    </body>
</html>