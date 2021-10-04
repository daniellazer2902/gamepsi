<?php
// intégralement fait par daniel
    session_start();
    $login = $_SESSION['pseudo'];
    
// sécurité anti user malveillant
    if (is_null($login)) {
        header('Location: ./index.php');
    }
    
    require '_conf.php';
    
    $pdo=new PDO("mysql:host=$bdd_server;dbname=$bdd_name","$bdd_user","$bdd_password");
    
// récupération du solde de l'user
    $senSolde = "SELECT solde FROM utilisateurs WHERE pseudo = :pseudo";
    $reqSolde = $pdo->prepare($senSolde);
    $reqSolde->bindParam(':pseudo', $login, PDO::PARAM_STR);
    $reqSolde->execute();
    $resSolde = $reqSolde->fetch(PDO::FETCH_ASSOC);

    $solde = $resSolde['solde'];
    
// récupération de l'avatar de l'user
    $reqDisplayImg = "SELECT pseudo,solde,avatar.lien,type,nom_objet FROM utilisateurs
                        INNER JOIN avatar ON avatar.id_user=utilisateurs.id_user
                        INNER JOIN objets ON avatar.lien=objets.lien";
    $reqDisplayImg .= " WHERE utilisateurs.pseudo= :pseudo ";
    $stmt=$pdo->prepare($reqDisplayImg);
    $stmt->bindParam(':pseudo', $login, PDO::PARAM_STR);
    $stmt->execute();
    
    $resDisplayImg = $stmt->fetchAll();
      
// récupération de tout les objets possédé par l'user
    $reqOwned = "SELECT pseudo,nom_objet FROM utilisateurs
                JOIN possession ON utilisateurs.id_user=possession.id_user
                JOIN objets ON objets.id_objet=possession.id_objet
                WHERE utilisateurs.pseudo= ?";
    $sth=$pdo->prepare($reqOwned);
    $sth->execute( [$login] );

    $listOwned = $sth->fetchAll();
    $totalOwned = count($listOwned);
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>GAMEPSI.FR | Profil</title>
        <meta name = "viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <link rel="stylesheet" href="style.css">
        
    </head>
    <body style="line-height: 1.15;background-color: #0065a0">
<!-- barre de navigation faites par William -->
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

        
        <div id="top_pannel" class="container-fluid" style="margin-top: 50px">

            <div id="hori_pannel" class="col-sm-5 col-lg-2" style="margin: auto">
                <div id="l_pan" class="pannel">
                    <?php
            
                    // affichage des fleches pour switch avec attribution des attribut de type
                    // afin de cibler la zone a changer
                    
                    foreach ($resDisplayImg as $left) {?>
                        <span class="" onclick="prev('<?=$left['type'] ?>')"><</span>
                    <?php } ?>
                </div>
                 
                
                <?php 
                
                // affichage des morceaux composant l'avatar
                
                foreach ($resDisplayImg as $morceau) {  ?>
                <img id="<?=$morceau['type'] ?>" style="position: absolute; top:5px" class="morceau" src="./img/avatar/<?=$morceau['lien'] ?>" alt="">
                <?php } ?>
                
                <div id="r_pan" class="pannel">
                    
                    <?php 
                    foreach ($resDisplayImg as $right) { ?>
                        <span class="" onclick="next('<?=$right['type'] ?>')">></span>
                    <?php } ?>
                </div>
            </div>         
        </div>
        
        <div class="col-sm-6" style="text-align: center;margin: 10px auto 10px; font-size: 35px;color: white;"><?php echo $login ?></div>
        
        <div class="col-sm-6" style="text-align: center;margin: 0 auto 10px; font-size: 35px; color: goldenrod">
            <span style="font-size: 35px; color: goldenrod"">$$$</span>
            <span style="font-size: 35px; color: white""><?php  echo $solde; ?></span>
            <span style="font-size: 35px; color: goldenrod"">$$$</span>
        </div>
        
        
        <form autocomplete="off">
            <div class="form-group col-sm-4" style="margin: 0 auto 10px; text-align: center;">
                <label id="label1" style="font-size: 20px; color: white;">Mot de passe:</label>
                <input type="password" name="actual_pw" id="actual_pw" class="form-control" autocomplete="off">
            </div>
            
            <div class="form-group col-sm-4" style="margin: 0 auto 10px; text-align: center;">
                <label id="label2" style="font-size: 20px; color: white">Nouveau mot de passe:</label>
                <input type="password" name="new_pw" id="new_pw" class="form-control" autocomplete="off">
            </div>
            
            <div class="form-group col-sm-4" style="margin: 0 auto 10px; text-align: center;">
                <label id="label3" style="font-size: 20px; color: white;">Véfication mot de passe:</label>
                <input type="password" name="verif_pw" id="verif_pw" class="form-control" autocomplete="off">
            </div>
            
            <div style="margin:20px" class="d-flex justify-content-center">
                <input type="button" onclick="change_pw()" class="btn" style="font-size: 20px" value="Envoyer">
            </div>
            
        </form>
        
        <script>
            
            function change_pw() {
                // fonction permettant le changement de mdp
                // via une requete ajax
                // renvoyant un boolean
                $("input[type='password']").css('background', 'white');
                
                
                var actual_pw = document.getElementById("actual_pw").value;
                var new_pw = document.getElementById("new_pw").value;
                var verif_pw = document.getElementById("verif_pw").value;
                
                if (new_pw === verif_pw) {
                    $.post( "profileUpdate.php", { ajax: '2', login: "<?php echo $login ?>", pw: actual_pw, new_pw: new_pw })
                        .done(function(res) {
                            if ( res === 'yes') {
                                $("#actual_pw").css('background', '#70db70');
                                $("#new_pw").css('background', '#70db70');
                                $("#verif_pw").css('background', '#70db70');
                            }
                            else if ( res === 'non'){
                                $("#actual_pw").css('background', '#ff8080');
                            }
                        });
                }
                else {
                    $('#verif_pw').val('');
                    $('#new_pw').val('');
                    $("#verif_pw").css('background', '#ff8080');
                    $("#new_pw").css('background', '#ff8080');
                }
            }
            
            
            
            /*
             *====================================  
             *     trie chaque objet posséde
             *     et le range dans le tableau
             *     de sa propre categorie
             *     
             *====================================  
             */
            
            var total = <?php echo $totalOwned ?>;
                tabOwned = [],
                tabShoul = [],
                tabColor = [],
                tabSmile = [],
                tabEye = [],
                tabAcces = [],
                tabHat = [];
            
            var iShoul = 0,
                iColor = 0,
                iSmile = 0,
                iEye = 0,
                iAcces = 0,
                iHat = 0;
            
            <?php foreach ($listOwned as $owned) { ?>
                
                word = '<?php echo $owned['nom_objet'] ?>';
                tabOwned.push(word);
                if (word.startsWith('shoul_')) {
                    tabShoul.push(word);
                }
                if (word.startsWith('color_')) {
                    tabColor.push(word);
                }
                if (word.startsWith('smile_')) {
                    tabSmile.push(word);
                }
                if (word.startsWith('eye_')) {
                    tabEye.push(word);
                }
                if (word.startsWith('acces_')) {
                    tabAcces.push(word);
                }
                if (word.startsWith('hat_')) {
                    tabHat.push(word);
                }
            <?php } ?>
  
            /*
             * =================================
             *      on recherche l'objet
             *      équipé pour initialiser
             *      notre id de type  
             * =================================
             */
  
            for (var i=0 ; i < tabOwned.length; ++i) {
                
                if ( './img/avatar/1'+tabOwned[i]+'.png' === document.getElementById('shoul').getAttribute('src') ) {
                    iShoul = tabShoul.indexOf(tabOwned[i]);
                }
                if ( './img/avatar/2'+tabOwned[i]+'.png' === document.getElementById('color').getAttribute('src') ) {
                    iColor = tabColor.indexOf(tabOwned[i]);
                }
                if ( './img/avatar/3'+tabOwned[i]+'.png' === document.getElementById('smile').getAttribute('src') ) {
                    iSmile = tabSmile.indexOf(tabOwned[i]);
                }
                if ( './img/avatar/4'+tabOwned[i]+'.png' === document.getElementById('eye').getAttribute('src') ) {
                    iEye = tabEye.indexOf(tabOwned[i]);
                }
                if ( './img/avatar/5'+tabOwned[i]+'.png' === document.getElementById('acces').getAttribute('src') ) {
                    iAcces = tabAcces.indexOf(tabOwned[i]);
                }
                if ( './img/avatar/6'+tabOwned[i]+'.png' === document.getElementById('hat').getAttribute('src') ) {
                    iHat = tabHat.indexOf(tabOwned[i]);
                }
                
            }
            
            /*
             * ======================================================
             *      fonction next qui permet de passer
             *      l'item suivant en possession
             *      - detecte la partie du corps cliqué
             *      - on id++ le type selectionne
             *      - on recupere le lien de l'image a afficher
             * ======================================================
             */
                  
            function next(morceau) {
                elem = document.getElementById(morceau);
                lien = elem.getAttribute('src');
                str = lien.slice(13, 14);

                
                switch (str) {
                    case '1':
                        
                        ++iShoul;
                        if (iShoul >= tabShoul.length) {
                            iShoul = 0;
                        }
                        $.post( "profileUpdate.php", { ajax: '1', objet: tabShoul[iShoul], login: "<?php echo $login ?>", corps: morceau })
                            .done(function(lien) {
                                elem.setAttribute("src",'./img/avatar/'+lien);
                            });
                        break;
                        
                        
                        
                    case '2':
                        
                        ++iColor;
                        if (iColor >= tabColor.length) {
                            iColor = 0;
                        }
                        $.post( "profileUpdate.php", { ajax: '1', objet: tabColor[iColor], login: "<?php echo $login ?>", corps: morceau })
                             .done(function(lien) {
                                elem.setAttribute("src",'./img/avatar/'+lien);
                            });
                        break;
                        
                        
                        
                    case '3':
                        
                        ++iSmile;
                        if (iSmile >= tabSmile.length) {
                            iSmile = 0;
                        }
                        $.post( "profileUpdate.php", { ajax: '1', objet: tabSmile[iSmile], login: "<?php echo $login ?>", corps: morceau })
                             .done(function(lien) {
                                elem.setAttribute("src",'./img/avatar/'+lien);
                            });
                        break;
                        
                        
                        
                    case '4':
                                                
                        ++iEye;
                        if (iEye >= tabEye.length) {
                            iEye = 0;
                        }
                        $.post( "profileUpdate.php", { ajax: '1', objet: tabEye[iEye], login: "<?php echo $login ?>", corps: morceau })
                             .done(function(lien) {
                                elem.setAttribute("src",'./img/avatar/'+lien);
                            });
                        break;
                        
                        
                    case '5':
                                                
                        ++iAcces;
                        if (iAcces >= tabAcces.length) {
                            iAcces = 0;
                        }
                        $.post( "profileUpdate.php", { ajax: '1', objet: tabAcces[iAcces], login: "<?php echo $login ?>", corps: morceau })
                            .done(function(lien) {
                                elem.setAttribute("src",'./img/avatar/'+lien);
                            });
                        break;
                        
                        
                    case '6':
                                             
                        ++iHat;
                        if (iHat >= tabHat.length) {
                            iHat = 0;
                        }
                        $.post( "profileUpdate.php", { ajax: '1', objet: tabHat[iHat], login: "<?php echo $login ?>", corps: morceau })
                            .done(function(lien) {
                                elem.setAttribute("src",'./img/avatar/'+lien);
                            });
                        break;
                }
 
            }
            
            /*===============================
             *      de meme que pour la 
             *      fonction next sauf
             *      qu'on id-- 
             *===============================
             */
            
            
            function prev(morceau) {
                elem = document.getElementById(morceau);
                lien = elem.getAttribute('src');
                str = lien.slice(13, 14);
                
                
                
                switch (str) {
                    case '1':
                        
                        --iShoul;
                        if (iShoul <= -1) {
                            iShoul = tabShoul.length - 1;
                        }
                        $.post( "profileUpdate.php", { ajax: '1', objet: tabShoul[iShoul], login: "<?php echo $login ?>", corps: morceau })
                            .done(function(lien) {
                                elem.setAttribute("src",'./img/avatar/'+lien);
                            });
                        break;
                        
                        
                        
                    case '2':
                        
                        --iColor;
                        if (iColor <= -1 ) {
                            iColor = tabColor.length -1;
                        }
                        
                        $.post( "profileUpdate.php", { ajax: '1', objet: tabColor[iColor], login: "<?php echo $login ?>", corps: morceau })
                            .done(function(lien) {
                                elem.setAttribute("src",'./img/avatar/'+lien);
                            });
                        break;
                        
                        
                        
                    case '3':
                        
                        --iSmile;
                        if (iSmile <= -1) {
                            iSmile = tabSmile.length -1;
                        }
                        $.post( "profileUpdate.php", { ajax: '1', objet: tabSmile[iSmile], login: "<?php echo $login ?>", corps: morceau })
                            .done(function(lien) {
                                elem.setAttribute("src",'./img/avatar/'+lien);
                            });
                        break;
                        
                        
                        
                    case '4':
                                                
                        --iEye;
                        if (iEye <= -1) {
                            iEye = tabEye.length -1;
                        }
                        $.post( "profileUpdate.php", { ajax: '1', objet: tabEye[iEye], login: "<?php echo $login ?>", corps: morceau })
                            .done(function(lien) {
                                elem.setAttribute("src",'./img/avatar/'+lien);
                            });
                        break;
                        
                        
                    case '5':
                                                
                        --iAcces;
                        if (iAcces <= -1) {
                            iAcces = tabAcces.length -1;
                        }
                        $.post( "profileUpdate.php", { ajax: '1', objet: tabAcces[iAcces], login: "<?php echo $login ?>", corps: morceau })
                            .done(function(lien) {
                                elem.setAttribute("src",'./img/avatar/'+lien);
                            });
                        break;
                        
                        
                    case '6':
                                             
                        --iHat;
                        if (iHat <= -1) {
                            iHat = tabHat.length -1;
                        }
                        $.post( "profileUpdate.php", { ajax: '1', objet: tabHat[iHat], login: "<?php echo $login ?>", corps: morceau })
                            .done(function(lien) {
                                elem.setAttribute("src",'./img/avatar/'+lien);
                            });
                        break;
                }
                   
            }
        </script>
        
        
    </body>
</html>
        