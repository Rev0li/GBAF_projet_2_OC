<!-- <?php require_once ('/xampp/htdocs/Extranet/component/php/header.php');?> -->
<!DOCTYPE html>
<html lang="en">
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
        <?php 
                if(isset($_GET['login_err']));
                {
                        $err = htmlspecialchars($_GET['login_err']);

                        switch($err) 
                        {
                                case 'pseudo':
                                ?>
                                <div class="alert alert-danger">
                                        <strong>Tu as oublié</strong> ton Pseudo boloss
                                </div>
                                <?php
                                break;

                                case 'password':
                                ?>
                                <div class="alert alert-danger">
                                        <strong>Erreur mot de passe</strong> fallait pas copier sur ton voisin
                                </div>
                                <?php
                                break;

                                case 'already':
                                ?>
                                <div class="alert alert-danger">
                                        <strong>Ta oublié ?</strong> Tu es déjà inscrit
                                </div>
                                <?php
                                break;
                        }
                }
        ?>
                <form action="connexion.php" method="post">
                        <h2 class="text-center">Connexion</h2>
                        <div class="form-group">
                                <input type="username" name="username" class="form-controle" placeholder="Pseudo" required="required" autocomplete="off">
                        </div>
                        <div class="form-group">
                                <input type="password" name="password" class="form-controle" placeholder="Mot de passe" required="required" autocomplete="off">
                        </div>
                        <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-block">Connexion</button>
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
        </html>
<!-- <?php require_once('/xampp/htdocs/Extranet/component/php/footer.php'); ?> -->
