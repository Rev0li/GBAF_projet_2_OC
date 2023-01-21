<?php
session_start();
require_once 'C:/xampp/htdocs/Extranet/component/php/config.php  ';
require_once '/xampp/htdocs/Extranet/component/php/header.php';

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

    //je ne peut pas poster 1 commentaire par personne mais 1 commentair en tout
    //ajoute lheure du commentaire
    $verif_user = $bdd->prepare('SELECT COUNT(*) FROM commentaires WHERE user_id = ?');        
    $verif_user->execute(array($sessionid));
    $count = $verif_user->fetchColumn();
    if (isset($_POST['submit_commentaire'])) {
        if ($count < 1) {
            if (isset($_POST['commentaire']) and !empty($_POST['prenom']) and !empty($_POST['commentaire'])) {

                $commentaire = htmlspecialchars($_POST['commentaire']);

                $ins = $bdd->prepare('INSERT INTO commentaires (prenom, commentaire, id_partner, post_date, user_id) VALUES (?, ?, ?, NOW(), ?)');
                $ins->execute(array($prenom, $commentaire, $get_id, $sessionid));
                $c_msg = "Votre commentaire a bien été posté";
            } else {
                $c_msg = " Erreur: Remplir l'espace commentaire";
            }
        } else {
            $c_msg = "Vous n'avez le droit que a 1 commentaire";
        }
    }

    $commentaires = $bdd->prepare('SELECT * FROM commentaires WHERE id_partner = ? ORDER BY id DESC');
    $commentaires->execute(array($getid));
    $count_commentaire = $commentaires->rowcount();

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
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="http://localhost/extranet/component/css/style.css" rel="stylesheet" media="all"> 
    <link href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">

    <title><?= $titre ?></title>
</head>

<body>
<div class="container text-center">
    <div class="info">
        <img class="partner_logo rounded mt-4 mb-3" src="../image/miniature/<?= $get_id ?>.png">
        <h1><?= $titre ?></h1>
        <p><?= $contenu ?></p>
    </div>
    
    <div class="cont-commentaire card shadow-sm mt-5">  
    <div class="cont_likes_dislike">
            <h2 class="count_title"><?= $count_commentaire ?>  Commentaire</h2>
            
            <div class="cont_right">
                <button id="btn-nouveau-commentaire" class="btn-primary rounded new_com">Nouveau commentaire</button>
            

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
    </div>
        
        <div class="count_com ">
        <?php while ($c = $commentaires->fetch()) { ?>
            <div class="ind_com card mt-3 mb-3 me-3 ms-3 shadow-sm">
                <?= $c['prenom'] ?><br>
                le  <?= $c['post_date'] ?> <br>
                <?= $c['commentaire'] ?> 
            </div>
        <?php } ?> 
        </div>

        <div class="form cont-post-com card mt-3 mb-3 me-3 ms-3 shadow-sm">
            <?php if (isset($c_msg)) { ?>     <li> <?= $c_msg; ?></li>       <?php } ?>
            <form id="form-nouveau-commentaire" method="post">
                <div class="com_name mt-3 mb-3">
                    <input readonly="readonly" type="text" name="prenom" value="<?= $_SESSION['prenom'] ?>"> 
                </div>
                <div class="whrit_com mt-3 mb-3">
                    <textarea class="area-com" name="commentaire" placeholder="Votre commentaire..."></textarea>
                </div>
                <div class="btn btn_com mt-3 mb-3">
                    <input class="btn-primary rounded" type="submit" value="Poster mon commentaire" name="submit_commentaire">
                </div>
            </form>
        </div>

        
    </div>
</div>
</body>
<?php require_once '/xampp/htdocs/Extranet/component/php/footer.php';
?>
<style>
    /* Partner */
    .partner_logo{
    max-width: 90%;
}

.cont_likes_dislike {
    display: flex;
    justify-content: space-between;
    margin: 20px 25px 0px 25px;
    flex-flow: row wrap;
}

.cont_right{
    display: flex;
}
.new_com{
margin:0 20px 0 20px;
}
.lk, .dslk{
    display: flex;
}

.img_pouce {
    max-width: 50px;
}
.area-com{
    width: 90%;
    min-height: 15vh;
}

.cont-post-com, .ind_com {
    background: #dcdcdc!important;
}

/* ____________________ */
</style>
<script>
  const btn = document.getElementById("btn-nouveau-commentaire");
  const form = document.getElementById("form-nouveau-commentaire");

  btn.addEventListener("click", function() {
    form.scrollIntoView();
  });
</script>

</html>