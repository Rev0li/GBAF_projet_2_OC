<?php
session_start();
require_once 'header.php';
require_once 'config.php';

@$nom = htmlspecialchars($_POST['nom']);
@$prenom = htmlspecialchars($_POST['prenom']);
@$username = htmlspecialchars($_POST['username']);
@$email = htmlspecialchars($_POST['email']);
@$password = htmlspecialchars($_POST['password']);
@$password_retype = htmlspecialchars($_POST['password_retype']);
@$secret_quest = htmlspecialchars($_POST['secret_quest']);

@$secret_answer = htmlspecialchars($_POST['secret_answer']);
$erreur_empty = [];
$e = [];

if (!empty($_POST)) {
    if (empty($nom)) $erreur_empty[] = "Veuillez remplir Nom";
    if (empty($prenom)) $erreur_empty[] = "Veuillez remplir Prenom";
    if (empty($username)) $erreur_empty[] = "Veuillez remplir Pseudo";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $erreur_empty[] = "Veuillez remplir un email valide";
    if (empty($password)) {
        $erreur_empty[] = "Veuillez remplir le champ password";
    } else {
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
            $erreur_empty[] = "Le mot de passe doit contenir au moins : 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial";
        }
    }
    if (empty($secret_quest)) $erreur_empty[] = "Veuillez sélectionner une question secrète";
    if (empty($secret_answer)) $erreur_empty[] = "Veuillez selsectionner une Reponse secrete";
    if ($password != $password_retype) $erreur_empty[] = "Les mot de passe ne sont pas identique";


    if (empty($erreur_empty)) {

        $check = $bdd->prepare('SELECT username, email FROM utilisateurs WHERE username = ? OR email = ?');
        $check->execute(array($username, $email));
        $result = $check->fetchAll();
        if (count($result) > 0) {
            if ($result[0]['username'] == $username) $erreur_empty[] = "Pseudo deja utiliser";
            if ($result[0]['email'] == $email) $erreur_empty[] = "Email deja utiliser";
        }

        if ($check->rowCount() == 0) {
            $password = password_hash($password, PASSWORD_DEFAULT);
            $secret_answer = password_hash($secret_answer, PASSWORD_DEFAULT);
            $insert = $bdd->prepare('INSERT INTO utilisateurs(nom, prenom, username, email, password, secret_quest, secret_answer) VALUES (:nom, :prenom, :username, :email, :password, :secret_quest, :secret_answer)');
            if ($insert->execute(array('nom' => $nom, 'prenom' => $prenom, 'username' => $username, 'email' => $email, 'password' => $password, 'secret_quest' => $secret_quest, 'secret_answer' => $secret_answer))) {
                // L'insertion a été effectuée
                $rec_id = $bdd->prepare('SELECT id FROM utilisateurs WHERE username = ? OR email = ?');
                $rec_id->execute(array($username, $email));
                $user_id = $rec_id->fetch();
                $pict_default = '../image/profil_pict/Default_pfp.png';
                $dst_pd = '../image/profil_pict/' . $user_id['id'] . '/' . 'Default_pfp.png';

                $dossier = '../image/profil_pict/' . $user_id['id'] . '/';

                if (!is_dir($dossier)) {
                    mkdir($dossier);
                    if(copy($pict_default, $dst_pd)) {
                        
                    }
                }

                    $erreur_empty[] = '<p>Inscription réussie, vous allez être redirigé vers la page de connexion dans <span id="countdown">5</span> secondes.</p>'
?>
                    <script>
                        // Afficher le message de confirmation et le compte à rebours avant la redirection
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
                } else {
                    die(var_dump($insert->errorInfo()));
                }
            }
        }
    }

?>



<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="NoS1gnal" />

    <link href="../css/style.css" rel="stylesheet" media="all"> 

    <title>Inscription</title>
</head>

<body>
    <div id="msform">
        <form action="" method="post">
            <h2 class="text-center">Inscription</h2>

            <div class="empty_err">
                <ul>
                    <?php foreach ($erreur_empty as $e) : ?>
                        <li><?= $e; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="form-group">
                <input type="text" name="nom" class="form-control" placeholder="Nom" autocomplete="off" value="<?= $nom; ?>">
            </div>


            <div class="form-group">
                <input type="text" name="prenom" class="form-control" placeholder="Prénom" autocomplete="off" value="<?= $prenom; ?>">
            </div>


            <div class="form-group">
                <input type="text" name="username" class="form-control" placeholder="Pseudo" autocomplete="off" value="<?= $username; ?>">
            </div>


            <div class="form-group">
                <input type="email" name="email" class="form-control" placeholder="Email" autocomplete="off" value="<?= $email; ?>">
            </div>

            <div class="form-group">
                <input type="password" name="password" class="form-control" placeholder="Mot de passe" autocomplete="off">
            </div>

            <div class="form-group">
                <input type="password" name="password_retype" class="form-control" placeholder="Confirmer le mot de passe" autocomplete="off">
            </div>


            <div class="form-group">
                <label for="secret_quest">Question secrète</label>
                <select name="secret_quest" class="form-control" id="secret_quest">
                    <option value="" disabled selected>Sélectionnez votre question secrète</option>
                    <?php
                    $options = $bdd->query("SELECT * FROM quest_option");
                    while ($row = $options->fetch()) {
                        // valeur a transmettre Bdd utilisateur "secret_quest"    Valeur a afficher dans le formulaire
                        echo "<option value='" . $row['secret_quest_id'] . "'>" . $row['secret_quest_value'] . "</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <input type="text" name="secret_answer" class="form-control" placeholder="Réponse secrète" autocomplete="off">
            </div>

            <div class="form-group">
                <button type="submit" name="inscr" class="btn" >Inscription</button>
            </div>


        </form>
    </div>



</body>
<?php require_once 'footer.php'; ?>

</html>