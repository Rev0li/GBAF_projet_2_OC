<?php
session_start();
require_once '/xampp/htdocs/Extranet/component/php/header.php';
@$nom = htmlspecialchars($_POST['nom']);
@$prenom = htmlspecialchars($_POST['prenom']);
@$username = htmlspecialchars($_POST['username']);
@$email = htmlspecialchars($_POST['email']);
@$password = htmlspecialchars($_POST['password']);
$password_retype = htmlspecialchars($_POST['password_retype']);

@$secret_quest = htmlspecialchars($_POST['secret_quest']);
@$secret_answer = htmlspecialchars($_POST['secret_answer']);

@$_SESSION['nom'] = $nom;
@$_SESSION['prenom'] = $prenom;
@$_SESSION['username'] = $username;
@$_SESSION['email'] = $email;
@$_SESSION['secret_quest'] = $secret_quest;
@$_SESSION['secret_answer'] = $secret_answer;

@$inscr=$_POST['inscr'];
$erreur_empty="";

if(isset($inscr)){
    if(empty($nom)) $erreur_empty="<li>Veuillez remplir Nom</li>";
    if(empty($prenom)) $erreur_empty.="<li>Veuillez remplir Prenom</li>";
    if(empty($username)) $erreur_empty.="<li>Veuillez remplir Pseudo</li>";
    if(empty($email)) $erreur_empty.="<li>Veuillez remplir email</li>";
    if(empty($password)) $erreur_empty.="<li>Veuillez remplir password</li>";
    if(empty($secret_quest)) $erreur_empty.="<li>Veuillez remplir La question secrete</li>";
    if(empty($secret_answer)) $erreur_empty.="<li>Veuillez remplir Reponse secrete</li>";
    elseif($password!=$password_retype) $erreur_empty.="<li>Les mot de passe ne sont pas identique</li>";

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
            
            <title>Inscription</title>
        </head>
        <body>
        <div class="login-form">
        <!-- inscription_traitement.php -->
            <form action="" method="post">
                <h2 class="text-center">Inscription</h2>     
                
                <div class="empty_err"><?php echo $erreur_empty  ?></div>
                <div class="form-group">
                    <input 
                    type="text" 
                    name="nom" 
                    class="form-control" 
                    placeholder="Nom"  
                    autocomplete="off" 
                    value="<?php if (isset($_SESSION['nom'])) echo $_SESSION['nom']; ?>"
                    >
                </div>


                <div class="form-group">
                    <input 
                    type="text" 
                    name="prenom" 
                    class="form-control" 
                    placeholder="Prénom"  
                    autocomplete="off" 
                    value="<?php if (isset($_SESSION['prenom'])) echo $_SESSION['prenom']; ?>"
                    >
                </div>


                <div class="form-group">
                    <input 
                    type="text" 
                    name="username" 
                    class="form-control" 
                    placeholder="Pseudo"  
                    autocomplete="off"
                    value="<?php if (isset($_SESSION['username'])) echo $_SESSION['username']; ?>"
                    >
                </div>
                

                <div class="form-group">
                    <input 
                    type="email" 
                    name="email" 
                    class="form-control" 
                    placeholder="Email"  
                    autocomplete="off"
                    value="<?php if (isset($_SESSION['email'])) echo $_SESSION['email']; ?>"
                    >
                </div>
                <php  
                
                ?>


                <div class="form-group">
                    <input 
                    type="password" 
                    name="password" 
                    class="form-control" 
                    placeholder="Mot de passe"  
                    autocomplete="off">
                </div>
                <php  
                
                ?>

                <div class="form-group">
                    <input 
                    type="password" 
                    name="password_retype" 
                    class="form-control" 
                    placeholder="Confirmer le mot de passe"  
                    autocomplete="off">
                </div>
                <php  
                
                ?>

                <div class="form-group">
                    <input 
                    type="text" 
                    name="secret_quest" 
                    class="form-control" 
                    placeholder="Question secrète"  
                    autocomplete="off"
                    value="<?php if (isset($_SESSION['secret_quest'])) echo $_SESSION['secret_quest']; ?>"
                    >
                </div>
                <php  
                
                ?>

                <div class="form-group">
                    <input 
                    type="text" 
                    name="secret_answer" class="form-control" 
                    placeholder="Réponse secrète"  
                    autocomplete="off"
                    value="<?php if (isset($_SESSION['secret_answer'])) echo $_SESSION['secret_answer']; ?>"
                    >
                </div>
                <php  
                
                ?>

                <div class="form-group">
                    <button type="submit" name="inscr"  class="btn btn-primary btn-block">Inscription</button>
                </div>   


            </form>
        </div>


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
        </body>
        <?php require_once '/xampp/htdocs/Extranet/component/php/footer.php'; ?>

</html>
