<?php
session_start();
require 'C:/xampp/htdocs/Extranet/component/php/config.php  ';
require_once '/xampp/htdocs/Extranet/component/php/header.php';

//init variables
$getid = (int) $_GET['id'];
$sessionid = $_SESSION['id'];
$prenom = $_SESSION['prenom'];


$check_like = $bdd->prepare('SELECT id FROM likes WHERE id_partner = ? AND id_utilisateurs = ?');
$check_like->execute(array($getid, $sessionid));

$check_dislike = $bdd->prepare('SELECT id FROM dislikes WHERE id_partner = ? AND id_utilisateurs = ?');
$check_dislike->execute(array($getid, $sessionid));

if (isset($_GET['id']) and !empty(['id'])) {
    $get_id = htmlspecialchars($_GET['id']);
    $partner = $bdd->prepare('SELECT * FROM partner WHERE id = ?');
    $partner->execute(array($get_id));

    //commentary ATTENTION 1 seul commentaire par personne
    if (isset($_POST['submit_commentaire'])) {
        if (isset($_POST['commentaire']) and !empty($_POST['prenom']) and !empty($_POST['commentaire'])) {
            $commentaire = htmlspecialchars($_POST['commentaire']);

            $ins = $bdd->prepare('INSERT INTO commentaires (prenom, commentaire, id_partner, post_date) VALUES (?, ?, ?, NOW())');
            $ins->execute(array($prenom, $commentaire, $get_id));
            $c_msg = "Votre commentaire a bien été posté";
        } else {
            $c_msg = " Erreur: Completer votre commentaire";
        }
    }

    $commentaires = $bdd->prepare('SELECT * FROM commentaires WHERE id_partner = ? ORDER BY id DESC');
    $commentaires->execute(array($getid));

    //php for likes and dislike
    if ($partner->rowCount() == 1) {
        $partner = $partner->fetch();
        $id = $partner['id'];
        $titre = $partner['partner_name'];
        $contenu = $partner['contenu'];

        $likes = $bdd->prepare('SELECT id FROM likes WHERE id_partner = ?');
        $likes->execute(array($id));
        $likes = $likes->rowCount();

        $dislikes = $bdd->prepare('SELECT id FROM dislikes WHERE id_partner = ?');
        $dislikes->execute(array($id));
        $dislikes = $dislikes->rowCount();
    } else {
        // header('Location:home.php');
        die("cet article n'existe pas");
    }
} else {
    // header('Location:home.php');
    die("erreur");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

    <title><?= $titre ?></title>
</head>

<body>
    <img src="../image/miniature/<?= $get_id ?>.png">
    <h1><?= $titre ?></h1>
    <p><?= $contenu ?></p>

    <h2>Commentaire:</h2>
    <?php while ($c = $commentaires->fetch()) { ?>
        <?= $c['prenom'] ?> : <br>
        <?= $c['post_date'] ?> <br>
        <?= $c['commentaire'] ?>
    <?php } ?>

    <br><br>
    <?php if (isset($c_msg)) { ?>
        <li> <?= $c_msg; ?></li>
    <?php } ?>
    <form method="post">
        <input readonly="readonly" type="text" name="prenom" value="<?= $_SESSION['prenom'] ?>"> <br>
        <textarea name="commentaire" placeholder="Votre commentaire..." id="" cols="30" rows="10"></textarea><br>
        <input type="submit" value="Poster mon commentaire" name="submit_commentaire">
    </form>


    <div class="cont_likes_dislike">
        <div class="lk">
            <p>(<?= $likes ?>)</p>

            <a href="dis_like.php?t=1&id=<?= $id ?>">
                <?php if ($check_like->rowCount() == 0) { ?>
                    <img class='img_pouce' src="../image/btn/pouce_up.png">
                <?php } else { ?>
                    <img class='img_pouce' src="../image/btn/pouce_up_full.png">
                <?php } ?>
            </a>
        </div>

        <div class="dslk">
            <p>(<?= $dislikes ?>)</p>

            <a href="dis_like.php?t=2&id=<?= $id ?>">

                <?php if ($check_dislike->rowCount() == 0) { ?>
                    <img class='img_pouce' src="../image/btn/pouce_down.png">
                <?php } else { ?>
                    <img class='img_pouce' src="../image/btn/pouce_down_full.png">
                <?php } ?>

            </a>
        </div>
    </div>

</body>
<?php require_once '/xampp/htdocs/Extranet/component/php/footer.php';
?>
<style>
    .cont_likes_dislike {
        display: flex;
    }

    .img_pouce {

        max-width: 50px;
    }
</style>

</html>