<?php
    session_start();
    require_once 'config.php';
    
    if (isset($_POST['username']) && isset($_POST['password'])) 
    {
        $username = htmlspecialchars($_POST['username']);
        $password = htmlspecialchars($_POST['password']);

        $check = $bdd->prepare('SELECT username, email, password FROM utilisateurs WHERE username = ?');
        $check->execute(array($username));
        $data = $check->fetch();
        $row = $check->rowCount();

        if ($row == 1)
        {
            if(filter_var($email, FILTER_VALIDATE_EMAIL))
            {
                $password = hash('sha256', $password);

                if($data['password'] === $password)
                {
                    $_SESSION['user'] = $data['username'];
                    header('Location:landing.php');
                }else header('Location:index.php?login_err=password');
            }else header('Location:index.php?login_err=pseudo');
        }else header('Location:index.php?login_err=already');
    }else header('Location:index.php');