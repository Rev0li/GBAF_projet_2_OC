<?php
session_start();
require 'C:/xampp/htdocs/Extranet/component/php/config.php';
require_once 'crop_image.php';

if (!isset($_SESSION['id'])) {
    header('Location:../../index.php');
    exit;
}


// Declaration tableau vide des erreurs
$err_nom = [];
$e = [];


if (isset($_POST['modif'])) {
    extract($_POST);
    $nom = htmlspecialchars($nom);
    $prenom = htmlspecialchars($prenom);
    $username = htmlspecialchars($username);
    $email = htmlspecialchars($email);

    if (empty($nom))          $nom = $_SESSION['nom'];
    if (empty($prenom))       $prenom = $_SESSION['prenom'];
    if (empty($username))     $username = $_SESSION['username'];
    if (empty($email))        $email = $_SESSION['email'];
        

    //for all but not password
    if ($nom != $_SESSION['nom'] || $prenom != $_SESSION['prenom'] || $username != $_SESSION['username'] || $email != $_SESSION['email'] ) {
        $req = $bdd->prepare('UPDATE utilisateurs SET 
            nom=?,
            prenom=?,
            username=?,
            email=?,
            -- secret_quest=?,
            -- secret_answer=?
            WHERE id = ?');
            
        $secret_answer = password_hash($secret_answer, PASSWORD_DEFAULT);
        $req->execute([$nom, $prenom, $username, $email, $secret_quest, $secret_answer, $_SESSION['z']]);

        $_SESSION['nom'] = $nom;
        $_SESSION['prenom'] = $prenom;
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;
        // $_SESSION['secret_quest'] = $secret_quest;
        // $_SESSION['secret_answer'] = $secret_answer;

        $err_nom[] = "Votre profil à été modifier";
    } else {
        // les champs n'ont pas été modifié 
        $err_nom[] = "Aucune modification effectuée";
    }
}

//profil_picture
if (isset($_POST['modif_pict'])) {
    if (isset($_FILES['profil_pict']) and (!empty($_FILES['profil_pict']['name']))) {
        $taillemax = 5242880; //5mo
        $extensionsValides = array('jpg', 'jpeg', 'png', 'gif');

        if ($_FILES['profil_pict']['size'] <= $taillemax) {
            $extensionUpload = strtolower(substr(strrchr($_FILES['profil_pict']['name'], '.'), 1));

            if (in_array($extensionUpload, $extensionsValides)) {
                $dossier = '../image/profil_pict/' . $_SESSION['id'] . '.' . '/';

                //si le dossier Id de l'utilisateur n'existe pas on le creer)
                if (!is_dir($dossier)) {
                    mkdir($dossier);
                }

                $pic_name =  "profilpict-" . time() . '.' . $extensionUpload;
                $chemin = $dossier . $pic_name;

                $resultat = move_uploaded_file($_FILES['profil_pict']['tmp_name'], $chemin);
                //ligne 84 a 90 on supprime l'ancienne image de profil

                $current_picture = $bdd->prepare('SELECT profil_pict FROM utilisateurs WHERE id = ?');
                $current_picture->execute(array($_SESSION['id']));
                $current_picture = $current_picture->fetch();
                //verifier quand c'est la 1er photo de profile
                if (file_exists($dossier . $current_picture['profil_pict'])) {
                    unlink($dossier . $current_picture['profil_pict']);
                }
                if ($resultat) {
                    if (is_readable($chemin)) {
                        crop_image($chemin, 300);
                        $req = $bdd->prepare("UPDATE utilisateurs SET profil_pict = ? WHERE id = ?");

                        $req->execute([($pic_name), $_SESSION['id']]);
                        $err_nom[] = 'upload reussi';
                        $_SESSION['profil_pict'] = $pic_name;
                        // header('Location: home.php ');
                        

                    } else {
                        $err_nom[] = "Impossible d'optimiser votre fichier";
                    }
                } else {
                    $err_nom[] = "Impossible d'importer votre fichier";
                }
            } else {
                $err_nom[] = "Votre photo de profil doit etre au format, jpg, jpeg.";
            }
        } else {
            $err_nom[] = "Le fichier ne doit pas dépassé 2 Mo";
        }
    }
}

//password
if (isset($_POST['modif_psw'])) {
    if (isset($_POST['old_password'], $_POST['new_password'], $_POST['password_retype']) and !empty($_POST['old_password']) and !empty($_POST['new_password']) and !empty($_POST['password_retype'])) {
        $recup_pass = $bdd->prepare("SELECT password FROM utilisateurs WHERE username = ?");
        $recup_pass->execute(array($username));
        $password_hash = $recup_pass->fetchColumn();
        if(password_verify($_POST['old_password'],$password_hash)){
            if ($_POST['new_password'] == $_POST['password_retype']) {
                $new_password = $_POST['new_password'];
                $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
                $update = $bdd->prepare('UPDATE utilisateurs SET password = :new_password_hash WHERE username = :username');
                $update->execute(array('new_password_hash' => $new_password_hash, 'username' => $username));
                $err_nom[] = "Nouveau mot de passe mis a jour ";
            }else{
                $err_nom[] = "Nouveau mot de passe mal recopier ";
            }
        }else{
            $err_nom[] = "Ancien mot de passe incorrect";
        }
    }else{
        $err_nom[] = "Veuillez remplir tout les champs mot de passe";
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
    <link href="../css/style.css" rel="stylesheet" media="all"> 
    <title>Mon profil</title>
</head>
<?php require_once '/xampp/htdocs/Extranet/component/php/header.php'; ?>

<body>
    <div class="login-form">
        <form action="" method="post" enctype="multipart/form-data">
            <h2 class="text-center">Mes Informations</h2>
            <div>
                <label for="image">Image de profil :</label><br>
                <img id="preview" src="<?= '../../component/image/profil_pict' . '/' . $_SESSION['id'] . '/' . $_SESSION['profil_pict'] ?>" alt="">
                <br/>
                <input type="file" name="profil_pict" value="Choisir un fichier">
            </div><br>
            <div class="form-group">
                <button type="submit" name="modif_pict" class="btn btn-primary btn-block">Maj photo profil</button>
            </div>

            <div class="empty_err">
                <ul>
                    <?php foreach ($err_nom as $e) : ?>
                        <li><?= $e; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="form-group">
                <input type="text" name="nom" class="form-control" placeholder="Nom" autocomplete="off" value="<?= $_SESSION['nom']; ?>">
            </div>
            <div class="form-group">
                <input type="text" name="prenom" class="form-control" placeholder="Prénom" autocomplete="off" value="<?= $_SESSION['prenom']; ?>">
            </div>
            <div class="form-group">
                <input type="text" name="username" class="form-control" placeholder="Pseudo" autocomplete="off" value="<?= $_SESSION['username']; ?>">
            </div>
            <div class="form-group">
                <input type="email" name="email" class="form-control" placeholder="Email" autocomplete="off" value="<?= $_SESSION['email']; ?>">
            </div>
            <div class="form-group">
                <button type="submit" name="modif" class="btn btn-primary btn-block">Modifier vos Informations</button>
            </div>


            <div class="form-group">
                <input type="password" name="old_password" class="form-control" placeholder="Mot de passe actuel" autocomplete="off">
            </div>
            <div class="form-group">
                <input type="password" name="new_password" class="form-control" placeholder="Nouveau mot de passe" autocomplete="off">
            </div>
            <div class="form-group">
                <input type="password" name="password_retype" class="form-control" placeholder="Confirmer le mot de passe" autocomplete="off">
            </div>
            <div class="form-group">
                <button type="submit" name="modif_psw" class="btn btn-primary btn-block">Mettre a jour le mot de passe</button>
            </div>

        </form>
    </div>
</body>
<?php
require_once '/xampp/htdocs/Extranet/component/php/footer.php';
?>

</html>