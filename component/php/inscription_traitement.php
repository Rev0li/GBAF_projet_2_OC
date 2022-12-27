<?php
    require_once 'config.php';

    if(isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['pseudo']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['password_retype']) && isset($_POST['secret_quest']) && isset($_POST['secret_answer']))
    {
        $nom = htmlspecialchars($_POST['nom']);
        $prenom = htmlspecialchars($_POST['prenom']);
        $pseudo = htmlspecialchars($_POST['psuedo']);
        $email = htmlspecialchars($_POST['email']);
        $password = htmlspecialchars($_POST['password']);
        $password_retype = htmlspecialchars($_POST['password_retype']);
        $secret_quest = htmlspecialchars($_POST['secret_quest']);
        $secret_answer = htmlspecialchars($_POST['secret_answer']);

        $check = $bdd->prepare('SELECT pseudo, email, password FROM utilisateurs WHERE username = ?');
        $check->execute(array($username));
        $data = $check->fetch();
        $row = $check->rowCount();

        if($row == 0)
        {
            if(filter_var($email, FILTER_VALIDATE_EMAIL))
            {
                if($password == $password_retype)
                {
                    $password = hash('256', $password);
                    $insert = $bdd->prepare('INSERT INTO utilisateurs(nom, prenom, username, email, password, question_secrete, reponse_secrete) VALUES (:nom, :prenom, :pseudo, :email, :password, :secret_quest, :secret_answer)');
                    $insert->execute(array(
                        'nom' => $nom,
                        'prenom' => $prenom,
                        'username' => $pseudo,
                        'email' => $email,
                        'password' => $password,
                        'question_secrete' => $secret_quest,
                        'reponse_secrete' => $secret_answer,

                    ));
                    header('Location: inscription.php?reg_err=sucess');
                }else header('Location: inscription.php?reg_err=password');
            }else header('Location: inscription.php?reg_err=email');
        }else header('Location: inscription.php?reg_err=already');
    }