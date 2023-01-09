<?php 
session_start();
require 'C:/xampp/htdocs/Extranet/component/php/config.php';


//ici j'aimerai reussir a recuperer l'id pour avoir un $_SESSION['id'] mais avec son email vue que on ne peut pas avoir 2fois le meme email dans la Bdd; 
$req = $bdd->prepare('SELECT * FROM utilisateurs WHERE username = ?');
$req->execute([$_SESSION['username']]);
$req_user = $req->fetch();
    // var_dump($req_user['id']);
    $_SESSION['id'] = $req_user['id'] ;


if(!isset($_SESSION['id'])){
    header('Location:../../index.php');
    exit;
}

// Declaration tableau vide des erreur
$err_nom = [];
$e = [];

if(!empty($_POST)){
    extract($_POST);

    $valid = true;

    if(isset($_POST['modif'])){
        $nom = htmlspecialchars(trim($nom));
        $prenom = htmlspecialchars(trim($prenom));
        $username = htmlspecialchars(trim($username));
        $email = htmlspecialchars(trim($email));
        $secret_quest = htmlspecialchars(trim($secret_quest));
        $secret_answer = htmlspecialchars(trim($secret_answer));

        if(empty($nom))          $nom = $req_user['nom'];
        if(empty($prenom))       $prenom = $req_user['prenom'];
        if(empty($username))     $username = $req_user['username'];
        if(empty($email))        $email = $req_user['email'];
        if(empty($secret_quest)) $secret_quest = $req_user['secret_quest'];
        if(empty($secret_answer))$secret_answer = $req_user['secret_answer'];
        
        
        // {
        //     $valid = false;
        //     $err_nom[] = "Ce champ ne peut pas etre vide";

        // }else{
        //     $req = $bdd->prepare('SELECT * FROM utilisateurs WHERE nom = ?');
        //     $req->execute(['nom']);
        //     $req = $req->fetch();

            
        // }
        if($valid){
            $req = $bdd->prepare('UPDATE utilisateurs SET nom=? WHERE username = ?');
            $req->execute([$nom, $_SESSION['username']]);

            $_SESSION['nom'] = $nom;
            $err_nom[] = "Votre profil à été modifier";
            header('Location: profil.php');
            exit;
        }
    }
}







if(isset($_POST['modif_pict'])){
//Image de profil
    if(isset($_FILES['profil_pict']) AND (!empty($_FILES['profil_pict']['name']))){
    $taillemax = 5242880; //5mo
    $extensionsValides = array('jpg', 'png', 'jpeg', 'gif');

        if($_FILES['profil_pict']['size'] <= $taillemax){
            $extensionUpload = strtolower(substr(strrchr($_FILES['profil_pict']['name'], '.'), 1));
            // var_dump($extensionUpload, $extensionsValides);

            if(in_array($extensionUpload, $extensionsValides)){
                $dossier = '../image/profil_pict/' . $_SESSION['id'] . '.' . '/';
            // var_dump($dossier);

                    //si le dossier Id de l'utilisateur n'existe pas on le creer)
                    if(!is_dir($dossier)){
                        mkdir($dossier);
                    }

                    $profil_pict_name= md5(uniqid(rand(), true));

                    $chemin = $dossier . $profil_pict_name . '.' . $extensionUpload;

                    $resultat = move_uploaded_file($_FILES['profil_pict']['tmp_name'], $chemin);

                    if($resultat){
                        if(is_readable($chemin)){

                            $req = $bdd->prepare("UPDATE utilisateurs SET profil_pict = ? WHERE id = ?");

                            $req->execute([($profil_pict_name . '.' . $extensionUpload), $_SESSION['id']]);
                            $err_nom[] = 'upload reussi';
                            // header('Location: home.php ');

                        }else{
                            $err_nom[] = "Impossible d'optimiser votre fichier";

                        }
                    }else{
                        $err_nom[] = "Impossible d'importer votre fichier";
                    }

            }else{
                $err_nom[] = "Votre photo de profil doit etre au format, jpg, png, jpeg, ou gif.";
            }
        }else{
            $err_nom[] = "Le fichier ne doit pas dépassé 2 Mo";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <title>Mon profil</title>
    <?php require_once '/xampp/htdocs/Extranet/component/php/header.php';?>
</head>

<body>
<div class="login-form">
            <form action="" method="post" enctype="multipart/form-data">
                <h2 class="text-center">Mes Informations</h2>     
                <div>
                    <label for="image">Image de profil :</label><br>
                    <input type="file" name="profil_pict"><br>
                    <img id="preview" src="<?= $profil_pict_name['profil_pict']; ?>" alt="Prévisualisation de l'image" >
                </div><br>
                <div class="form-group">
                    <button type="submit" name="modif_pict"  class="btn btn-primary btn-block">Maj photo profil</button>
                </div>   

                <div class="empty_err">
                    <ul>
                        <?php foreach($err_nom as $e): ?>
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
                    value="<?= $req_user['nom']; ?>"
                    >
                </div>


                <div class="form-group">
                    <input 
                    type="text" 
                    name="prenom" 
                    class="form-control" 
                    placeholder="Prénom"  
                    autocomplete="off" 
                    value="<?= $req_user['prenom'] ?>"
                    >
                </div>


                <div class="form-group">
                    <input 
                    type="text" 
                    name="username" 
                    class="form-control" 
                    placeholder="Pseudo"  
                    autocomplete="off"
                    value="<?= $req_user['username'] ; ?>"
                    >
                </div>
                

                <div class="form-group">
                    <input 
                    type="email" 
                    name="email" 
                    class="form-control" 
                    placeholder="Email"  
                    autocomplete="off"
                    value="<?= $req_user['email']; ?>"
                    >
                </div>



                <div class="form-group">
                    <input 
                    type="password" 
                    name="old_password" 
                    class="form-control" 
                    placeholder="Mot de passe actuel"  
                    autocomplete="off">
                </div>

                <div class="form-group">
                    <input 
                    type="password" 
                    name="new_password" 
                    class="form-control" 
                    placeholder="Nouveau mot de passe"  
                    autocomplete="off">
                </div>


                <div class="form-group">
                    <input 
                    type="password" 
                    name="password_retype" 
                    class="form-control" 
                    placeholder="Confirmer le mot de passe"  
                    autocomplete="off">
                </div>


                <div class="form-group">
                    <input 
                    type="text" 
                    name="secret_quest" 
                    class="form-control" 
                    placeholder="Question secrète"  
                    autocomplete="off"
                    value="<?= $req_user['secret_quest']; ?>"
                    >
                </div>


                <div class="form-group">
                    <input 
                    type="text" 
                    name="secret_answer" class="form-control" 
                    placeholder="Réponse secrète"  
                    autocomplete="off"
                    value="<?= $req_user['secret_answer']; ?>"
                    >
                </div>


                <div class="form-group">
                    <button type="submit" name="modif"  class="btn btn-primary btn-block">Enregistrer modification</button>
                </div>   


            </form>
        </div>


</body>

<script>

  // Récupération de l'image téléchargée par l'utilisateur
  var input = document.querySelector('input[type=file]');
  if (input.files && input.files[0]) {
    var image = input.files[0];
 // Lecture des données de l'image avec l'objet FileReader
var reader = new FileReader();
reader.onload = function(e) {
  // Mise à jour de la propriété src de la balise img avec les données lues par FileReader
  var preview = document.getElementById('preview');
  preview.src = e.target.result;
  preview.style.display = 'block';
}
reader.readAsDataURL(image);
  }
</script>

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

#preview{
    position: relative;
    width: 100%;
    height: 100%;
}
</style>
<?php
require_once '/xampp/htdocs/Extranet/component/php/footer.php';
?>

</html>