<?php
session_start();
require_once 'config.php';

if(isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['password_retype']) && isset($_POST['secret_quest']) && isset($_POST['secret_answer']))
{
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);
    $password_retype = htmlspecialchars($_POST['password_retype']);
    $secret_quest = htmlspecialchars($_POST['secret_quest']);
    $secret_answer = htmlspecialchars($_POST['secret_answer']);

    $check = $bdd->prepare('SELECT username, email FROM utilisateurs WHERE username = ? OR email = ?');
    $check->execute(array($username, $email));
    $row = $check->rowCount();

    if($row > 0) {
        // header('Location: inscription.php?reg_err=already');
        echo "remplir le formulaire svp";
    } else {
        if($password == $password_retype) {
            if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $password = password_hash($password, PASSWORD_DEFAULT);
                $insert = $bdd->prepare('INSERT INTO utilisateurs(nom, prenom, username, email, password, secret_quest, secret_answer) VALUES (:nom, :prenom, :username, :email, :password, :secret_quest, :secret_answer)');
                $insert->execute(array(
                    'nom' => $nom,
                    'prenom' => $prenom,
                    'username' => $username,
                    'email' => $email,
                    'password' => $password,
                    'secret_quest' => $secret_quest,
                    'secret_answer' => $secret_answer,
                ));

                


                header('Location: inscription.php?reg_err=success');
            } else {
                header('Location: inscription.php?reg_err=email');
            }
        } else {
            header('Location: inscription.php?reg_err=password');
        }
    }
}
?>