<?php
session_start();
require_once '/xampp/htdocs/Extranet/component/php/header.php';
require_once 'config.php';


@$nom = htmlspecialchars($_POST['nom']) ;
@$prenom = htmlspecialchars($_POST['prenom']) ;
@$username = htmlspecialchars($_POST['username']) ;
@$email = htmlspecialchars($_POST['email']);
@$password = htmlspecialchars($_POST['password']);
@$password_retype = htmlspecialchars($_POST['password_retype']);
@$secret_quest = htmlspecialchars($_POST['secret_quest']);
@$secret_answer = htmlspecialchars($_POST['secret_answer']);

$erreur_empty= [];
$e = [];


if(!empty($_POST)){
    if(empty($nom)) $erreur_empty[]="Veuillez remplir Nom";
    if(empty($prenom)) $erreur_empty[]="Veuillez remplir Prenom";
    if(empty($username)) $erreur_empty[]="Veuillez remplir Pseudo";
    if(!preg_match("#^[a-zA-Z0-9À-ú\.:\!\?\&',\s-]{10,50}#",$email)) $erreur_empty[]="Veuillez remplir un email valide";      
        //   if(filter_var($email, FILTER_VALIDATE_EMAIL)) {}
    if(empty($password)) $erreur_empty[]="Veuillez remplir password";
    if(empty($secret_quest)) $erreur_empty[]="Veuillez remplir La question secrete";
    if(empty($secret_answer)) $erreur_empty[]="Veuillez remplir Reponse secrete";
    if($password!=$password_retype) $erreur_empty[]="Les mot de passe ne sont pas identique";
    
    
    
    if(count($erreur_empty) > 0 ){
        
    $nom = trim($nom);
    $prenom = trim($prenom);
    $username = trim($username);
    $email = trim($email);
    $password = trim($password);
    $password_retype = trim($password_retype);
    $secret_quest = trim($secret_quest);
    // Prevoir une liste de question 
    $secret_answer = trim($secret_answer);
    // prevoir hash answer 
    }else{
        $check = $bdd->prepare('SELECT username, email FROM utilisateurs WHERE username = ? OR email = ?');
        $check->execute(array($username, $email));
        
        if($check->rowCount()==0){
        $password = password_hash($password, PASSWORD_DEFAULT);
        $insert = $bdd->prepare('INSERT INTO utilisateurs(nom, prenom, username, email, password, secret_quest, secret_answer) VALUES (:nom, :prenom, :username, :email, :password, :secret_quest, :secret_answer)');
        if($insert->execute(array('nom' => $nom,'prenom' => $prenom,'username' => $username,'email' => $email,'password' => $password,'secret_quest' => $secret_quest,'secret_answer' => $secret_answer,
            ))){
                
                    // Insertion réussie
                    if ($insert->rowCount() > 0) {
                        // L'insertion a été effectuée
                        // Afficher le message de confirmation et le compte à rebours avant la redirection
                        
                        $erreur_empty[] = '<p>Inscription réussie, vous allez être redirigé vers la page de connexion dans <span id="countdown">5</span> secondes.</p>'
                        ?><script>
                            // Définir le nombre de secondes avant la redirection
                            var seconds = 5;
                            // Mettre à jour le compte à rebours toutes les secondes
                            setInterval(function() {
                                // Decrementer le nombre de secondes
                                seconds--;
                                // Afficher le compte à rebours
                                document.getElementById("countdown").innerHTML = seconds;
                                // Rediriger l'utilisateur si le compte à rebours atteint 0
                                if (seconds == 0) {
                                    window.location = "../../index.php";
                                }
                            }, 1000);
                        </script>
                        <?php
            }else{
                die(var_dump($insert->errorInfo()));
            }
        // header('Location: inscription.php?=success');
        }else{
            $erreur_empty[]="e-mail deja utilise";
        }
    }
}}
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
            <form action="" method="post">
                <h2 class="text-center">Inscription</h2>     
                
                <div class="empty_err">
                    <ul>
                        <!-- inverser e et $erreur_empty -->
                        <?php foreach($erreur_empty as $e): ?>
                            <li><?= $e ; ?></li>
                            <?php endforeach;?>
                    </ul>
                        </div>
                <div class="form-group">
                    <input 
                    type="text" 
                    name="nom" 
                    class="form-control" 
                    placeholder="Nom"  
                    autocomplete="off" 
                    value="<?= $nom; ?>"
                    >
                </div>


                <div class="form-group">
                    <input 
                    type="text" 
                    name="prenom" 
                    class="form-control" 
                    placeholder="Prénom"  
                    autocomplete="off" 
                    value="<?= $prenom; ?>"
                    >
                </div>


                <div class="form-group">
                    <input 
                    type="text" 
                    name="username" 
                    class="form-control" 
                    placeholder="Pseudo"  
                    autocomplete="off"
                    value="<?= $username; ?>"
                    >
                </div>
                

                <div class="form-group">
                    <input 
                    type="email" 
                    name="email" 
                    class="form-control" 
                    placeholder="Email"  
                    autocomplete="off"
                    value="<?= $email; ?>"
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
                    value="<?= $secret_quest; ?>"
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
                    value="<?= $secret_answer; ?>"
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