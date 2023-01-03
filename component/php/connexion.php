<?php
session_start();
require './config.php';

    $username = $_POST['username'];
    $password = $_POST['password'];

    
        $sql = "SELECT * FROM utilisateurs WHERE username = :username";
        $result = $bdd->prepare($sql);
        $result->bindParam(':username', $username);
        $result->execute();

        if($result->rowCount() > 0)
        {
            $data = $result->fetch();
            $hashedPassword = $data['password'];
            if (password_verify($password, $hashedPassword))
            {
                header('Location: home.php?');

            }else{
                echo "Mauvais mot de passe ";
            }

        }else{
            
            echo "tu n'es pas inscris, ou tu as mal taper ton pseudos";
        }
    
?>