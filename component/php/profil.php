<?php
session_start();
require 'C:/xampp/htdocs/Extranet/component/php/config.php';
require_once 'crop_image.php';

if (!isset($_SESSION['id'])) {
    header('Location:../../index.php');
    exit;
}

@$secret_quest_options = array("Quel est le nom de votre première école?", "Quel est le nom de votre animal de compagnie?", "Quel est le nom de votre mère?");


// Declaration tableau vide des erreurs
$err_nom = [];
$e = [];


if (isset($_POST['modif'])) {
    extract($_POST);
    $nom = htmlspecialchars($nom);
    $prenom = htmlspecialchars($prenom);
    $username = htmlspecialchars($username);
    $email = htmlspecialchars($email);
    $secret_answer = htmlspecialchars($secret_answer);


    if (empty($nom))          $nom = $_SESSION['nom'];
    if (empty($prenom))       $prenom = $_SESSION['prenom'];
    if (empty($username))     $username = $_SESSION['username'];
    if (empty($email))        $email = $_SESSION['email'];
    if (empty($secret_quest)) $secret_quest = $_SESSION['secret_quest'];
    if (empty($secret_answer)) $secret_answer = $_SESSION['secret_answer'];

    if ($nom != $_SESSION['nom'] || $prenom != $_SESSION['prenom'] || $username != $_SESSION['username'] || $email != $_SESSION['email'] || $secret_quest != $_SESSION['secret_quest'] || $secret_answer != $_SESSION['secret_answer']) {
        $req = $bdd->prepare('UPDATE utilisateurs SET 
            nom=?,
            prenom=?,
            username=?,
            email=?,
            secret_quest=?,
            secret_answer=?
            WHERE id = ?');

        $req->execute([$nom, $prenom, $username, $email, $secret_quest, $secret_answer, $_SESSION['id']]);

        $_SESSION['nom'] = $nom;
        $_SESSION['prenom'] = $prenom;
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;
        $_SESSION['secret_quest'] = $secret_quest;
        $_SESSION['secret_answer'] = $secret_answer;

        $err_nom[] = "Votre profil à été modifier";
    } else {
        // les champs n'ont pas été modifié 
        $err_nom[] = "Aucune modification effectuée";
    }
} elseif (isset($_POST['modif_pict'])) {

    //Image de profil
    if (isset($_FILES['profil_pict']) and (!empty($_FILES['profil_pict']['name']))) {
        $taillemax = 5242880; //5mo
        $extensionsValides = array('jpg', 'jpeg');

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
                        //j'aimerai comprendre
                        // crop_image($pic_name,300);
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
    <?php require_once '/xampp/htdocs/Extranet/component/php/header.php'; ?>
</head>

<body>
    <div class="login-form">
        <form action="" method="post" enctype="multipart/form-data">
            <h2 class="text-center">Mes Informations</h2>
            <div>
                <label for="image">Image de profil :</label><br>
                <input type="file" name="profil_pict"><br>
                <img id="preview" src="<?= '../../component/image/profil_pict' . '/' . $_SESSION['id'] . '/' . $_SESSION['profil_pict'] ?>" alt="">
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
                <input type="password" name="old_password" class="form-control" placeholder="Mot de passe actuel" autocomplete="off">
            </div>

            <div class="form-group">
                <input type="password" name="new_password" class="form-control" placeholder="Nouveau mot de passe" autocomplete="off">
            </div>


            <div class="form-group">
                <input type="password" name="password_retype" class="form-control" placeholder="Confirmer le mot de passe" autocomplete="off">
            </div>


            <div class="form-group">
                <label for="secret_quest">Question secrète</label>
                <select name="secret_quest" class="form-control" id="secret_quest">
                    <option value="" disabled selected>Sélectionnez votre question secrète</option>
                    <option value="Nom de votre animal de compagnie ?">Nom de votre animal de compagnie ?</option>
                    <option value="Nom de votre mère ?">Nom de votre mère ?</option>
                    <option value="Ville de naissance ?">Ville de naissance ?</option>
                </select>
            </div>


            <div class="form-group">
                <input type="text" name="secret_answer" class="form-control" placeholder="Réponse secrète" autocomplete="off" value="<?= $_SESSION["secret_answer"]; ?>">
            </div>


            <div class="form-group">
                <button type="submit" name="modif" class="btn btn-primary btn-block">Enregistrer modification</button>
            </div>


        </form>
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

    .form-control,
    .btn {
        min-height: 38px;
        border-radius: 2px;
    }

    .btn {
        font-size: 15px;
        font-weight: bold;
    }

    #preview {
        position: relative;
        max-width: 200px;
        max-height: 200px;
    }
</style>
<?php
require_once '/xampp/htdocs/Extranet/component/php/footer.php';
?>

</html>