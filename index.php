<?php
session_start();
require 'C:/xampp/htdocs/Extranet/component/php/config.php  ';
require_once '/xampp/htdocs/Extranet/component/php/header.php';
@$username = ($_POST['username']);
@$password = ($_POST['password']);
@$submit = ($_POST['submit']);
$erreur_empty="";

if(isset($submit)){
        if(empty($username)) $erreur_empty="<li>Veuillez remplir Pseudo</li>";
        if(empty($password)) $erreur_empty.="<li>Veuillez remplir password</li>";
}
?>

<!DOCTYPE html>
<html lang="fr">
        <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="author" content="NoS1gnal"/>

        <link href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        
        <title>Connexion</title>
        </head>

        <body>
                <div class="login-form">
                <!-- component/php/connexion.php -->
                <form action="" method="post">
                        <h2 class="text-center">Connexion</h2>
                        <div class="empty_err"><?php echo $erreur_empty  ?></div>
                        <div class="form-group">
                                <input 
                                type="text" 
                                name="username" 
                                class="form-controle" 
                                placeholder="Pseudo"  
                                autocomplete="off"  
                                value="<?php echo $username; ?>"
                                >
                        </div>

                        <div class="form-group">
                                <input 
                                type="password" 
                                name="password" 
                                class="form-controle" 
                                placeholder="Mot de passe"  
                                autocomplete="off"
                                >
                        </div>

                        <div class="form-group">
                        <button 
                                type="submit" 
                                name="submit" 
                                class="btn btn-primary btn-block"
                                >Connexion
                        </button>
                        </div>
                </form>
                <p class="text-center"><a href="component/php/inscription.php">Inscription</a></p>
        </div>

        </body>
<style>
.login-form {
        width: 340px;
        margin: 50px auto;
}
.login-form form {
        margin-bottom: 15px;
        background: #f7f7f7;
        box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
        padding: 30px;
}
.login-form h2 {
        margin: 0 0 15px;
}
.form-control, .btn {
        min-height: 38px;
        border-radius: 2px;
}
.btn {        
        font-size: 15px;
        font-weight: bold;
}
</style>

<?php require_once '/xampp/htdocs/Extranet/component/php/footer.php'; ?>

        </html>
