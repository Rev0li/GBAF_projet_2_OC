<?php
session_start();
require 'component/php/config.php  ';
require_once 'component/php/header.php';
if (!empty($_SESSION['id'])) {
        header('Location: component/php/home.php ');
        exit;
    }

$erreur_empty = [];
$erreur_recup = [];
$erreur_mdp = [];
$e = [];
$r = [];
$p = [];

if (isset($_POST['submit_connexion'])) {
        
        $username = htmlspecialchars($_POST['username']);
        $password = htmlspecialchars($_POST['password']);
        $recupUser = $bdd->prepare("SELECT * FROM utilisateurs WHERE username = ?");
        $recupUser->execute(array($username));

        if (!empty($username)) {

                if ($recupUser->rowCount() > 0) {
                        $data = $recupUser->fetch();
                        $hashedPassword = $data['password'];

                        if (!empty($password)) {

                                if (password_verify($password, $hashedPassword)) {
                                        $_SESSION['id']             = $data['id'];
                                        $_SESSION['nom']            = $data['nom'];
                                        $_SESSION['prenom']         = $data['prenom'];
                                        $_SESSION['username']       = $data['username'];
                                        $_SESSION['email']          = $data['email'];
                                        $_SESSION['secret_quest']   = $data['secret_quest'];
                                        $_SESSION['secret_answer']  = $data['secret_answer'];
                                        $_SESSION['profil_pict']    = $data['profil_pict'];

                                        header("Location: ./component/php/home.php");
                                } else {
                                        $erreur_empty[] = "Mot de passe incorrect";
                                }
                        } else {
                                $erreur_empty[] = "Veuillez remplir password";
                        }
                } else {
                        $erreur_empty[] = "Le Pseudo n'est pas reconnue";
                }
        } else {
                $erreur_empty[] = "Veuillez remplir Pseudo";
        }
}

elseif (isset($_POST["valide_new_mdp"])){
        $_SESSION['form_open'] = true;
        $username_mdp = $_POST["username_mdp"];
        $recupUser = $bdd->prepare("SELECT * FROM utilisateurs WHERE username = ?");
        $recupUser->execute(array($username_mdp));

        if (!empty($username_mdp)) {
                if ($recupUser->rowCount() > 0) {
                        $data = $recupUser->fetch();
                        $recup_quest = $data['secret_quest'];
                        $recup_answer = $data['secret_answer'];

                        if(!empty($_POST['rpsd_secret_quest']) and !empty($_POST['rpsd_secret_answer']) && $_POST['rpsd_secret_quest'] == $data['secret_quest']){
                                
                                if (password_verify($_POST['rpsd_secret_answer'], $recup_answer)) {

                                        if(!empty($_POST['new_password_retype']) and !empty($_POST['new_password'])){
                                                if ($_POST['new_password_retype'] == $_POST['new_password']){
                                                        $new_password = $_POST['new_password'];
                                                        $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
                                                        $update = $bdd->prepare('UPDATE utilisateurs SET password = :new_password_hash WHERE username  = :username_mdp');
                                                        $update->execute(array('new_password_hash' => $new_password_hash, 'username_mdp' => $username_mdp));
                                                        $erreur_recup[] = "Nouveau mot de passe mis a jour ";
                                                }else{$erreur_mdp[] = "Mot de passe pas identique";}
                                        }else{$erreur_mdp[] = "Remplir les champs mdp en meme temps que les Q/R";}
                                }else {$erreur_recup[] = "Incorrect(question/Reponse)";}
                        }else {$erreur_recup[] = "Incorrect(Question/reponse vide)";}
                } else {$erreur_recup[] = "Recup mdp - Le Pseudo n'est pas reconnue";}
        } else {$erreur_recup[] = "Recup mdp -Veuillez remplir Pseudo";}
} 

        ?>

<!DOCTYPE html>
<html lang="fr">

<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="author" content="NoS1gnal" />
        <link href="component/css/style.css" rel="stylesheet" media="all"> 

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <title>Connexion</title>
</head>

<body>
        <div id="msform">
                <form action="" method="post">
                        <h2 class="text-center">Connexion</h2>
                        <div class="empty_err">
                                <ul>
                                        <?php foreach ($erreur_empty as $e) : ?>
                                                <li><?= $e; ?></li>
                                        <?php endforeach; ?>
                                </ul>
                        </div>
                        <div class="form-group">
                                <input type="text" name="username" class="form-controle" placeholder="Pseudo" autocomplete="off" value="<?php if(isset($_POST['username'])) echo $_POST['username']; ?>">
                        </div>
                        <div class="form-group">
                                <input type="password" name="password" class="form-controle" placeholder="Mot de passe" autocomplete="off">
                        </div>
                        <div class="form-group">
                                <button type="submit" name="submit_connexion" class="btn">Connexion</button>
                        </div>
                </form>




                        <p id="panneau-depliant">Mot de passe oublié</p>
                        
                        <div class="form-group" id="controle_id_to_reset"  >
                                <form id='formulaire_mdp_oublie' action="" method="post" style="display: <?php echo isset($_SESSION['form_open']) && $_SESSION['form_open'] == true ? 'block' : 'none'; ?>;">
                                        <div class="empty_err">
                                                <ul>
                                                        <?php foreach ($erreur_recup as $r) : ?>
                                                                <li><?= $r; ?></li>
                                                        <?php endforeach; ?>
                                                </ul>
                                        </div>
                                <div class="form-group">
                                        <input type="text" name="username_mdp" class="form-controle" placeholder="Pseudo" autocomplete="off" 
                                        value="<?php 
                                        if(isset($_POST['username'])) {echo $_POST['username']; 
                                        }elseif(isset($_POST['username_mdp'])){ echo $_POST['username_mdp']; }
                                        ?>">
                                </div>
                                <label for="secret_quest">Question secrète</label>
                                <select name="rpsd_secret_quest" class="form-control" id="secret_quest">
                                        <option value="" disabled selected>Sélectionnez votre question secrète</option>
                                        <?php
                                        $options = $bdd->query("SELECT * FROM quest_option");
                                        while ($row = $options->fetch()) {
                                        // valeur a transmettre Bdd utilisateur "secret_quest"    Valeur a afficher dans le formulaire
                                        echo "<option value='" . $row['secret_quest_id'] . "'>" . $row['secret_quest_value'] . "</option>";
                                        }?>
                                </select>
                                <div class="form-group">
                                        <input type="text" name="rpsd_secret_answer" class="form-control" placeholder="Réponse secrète" autocomplete="off">
                                </div>

                                <div class="empty_err">
                                <ul>
                                        <?php foreach ($erreur_mdp as $p) : ?>
                                                <li><?= $p; ?></li><br>
                                        <?php endforeach; ?>
                                </ul>
                                </div>
                        <label for="password_reset">Enregistré votre nouveau Mot de passe</label>
                                <div class="form-group">
                                        <input type="password" name="new_password" class="form-control" placeholder="Nouveau mot de passe" autocomplete="off">
                                </div>
                                
                                <div class="form-group">
                                        <input type="password" name="new_password_retype" class="form-control" placeholder="Confirmer le mot de passe" autocomplete="off">
                                </div>

                                <div class="form-group">
                                        <button type="submit" name="valide_new_mdp" id="valide_new_mdp" class="btn btn-primary btn-block">Valider mon nouveau mdp</button>
                                </div>
                                </form>
                        </div>



                        
                
                <p class="text-center"><a href="component/php/inscription.php">Inscription</a></p>
        </div>
        <script>
    // sélectionner le bouton "Mot de passe oublié"
    const btnRecupMdp = document.querySelector('#panneau-depliant');

    // sélectionner le formulaire de récupération de mot de passe
    const formRecupMdp = document.querySelector('#formulaire_mdp_oublie');

// Vérifier si le formulaire a été ouvert lors de la précédente session
if(sessionStorage.getItem("form_open") === "true") {
        formRecupMdp.style.display = 'block';
    }

    // ajouter un écouteur d'événement "click" sur le bouton
    btnRecupMdp.addEventListener('click', function() {
    if (sessionStorage.getItem("form_open") === "open") {
        formRecupMdp.style.display = 'none';
        sessionStorage.removeItem("form_open");
    } else {
        formRecupMdp.style.display = 'block';
        sessionStorage.setItem("form_open", "open");
    }
    });
</script>
</body>
<?php require_once 'component/php/footer.php'; ?>
</html>