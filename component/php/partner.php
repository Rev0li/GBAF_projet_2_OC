<?php
session_start();
require_once 'config.php  ';
require_once 'header.php';

if (empty($_SESSION['id'])) {
    header('Location:../../index.php ');
    exit;
}

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
        header('Location:home.php');
    }
} else {
    header('Location:home.php');
}

?>


<body>
<div class="container_flex_wrap">
    <div class="info">
        <img class="partner_logo rounded mt-4 mb-3" src="../image/miniature/<?= $get_id ?>.png" alt="logo partner">
        <h1><?= $titre ?></h1>
        <p><?= $contenu ?></p>
    </div>
    
    <div id="msform">  
    <div class="cont_likes_dislike">
            <h2 class="count_title"><?= $count_commentaire ?>  Commentaire</h2>
            
            <div class="cont_right">
                <button class="btn new-com">Nouveau commentaire</button>
            

                <div class="lk">
                    <p>(<?= $likes ?>)</p>

                    <a href="dis_like.php?t=1&id=<?= $id ?>">
                        <?php if ($check_like->rowCount() == 0) { ?>
                            <img class='img_pouce' src="../image/btn/pouce_up.png" alt="like">
                        <?php } else { ?>
                            <img class='img_pouce' src="../image/btn/pouce_up_full.png" alt="like full">
                        <?php } ?>
                    </a>
                </div>

                <div class="dslk">
                    <p>(<?= $dislikes ?>)</p>

                    <a href="dis_like.php?t=2&id=<?= $id ?>">

                        <?php if ($check_dislike->rowCount() == 0) { ?>
                            <img class='img_pouce' src="../image/btn/pouce_down.png" alt="dislike">
                        <?php } else { ?>
                            <img class='img_pouce' src="../image/btn/pouce_down_full.png" alt="dislike full">
                        <?php } ?>
                    </a>
                </div>
            </div> 
    </div>
        
        <div class="count_com ">
        <?php while ($c = $commentaires->fetch()) { ?>
            <div class="ind_com">
                <?= $c['prenom'] ?><br>
                le  <?= $c['post_date'] ?> <br>
                <?= $c['commentaire'] ?> 
            </div>
        <?php } ?> 
        </div>

        
            <?php if (isset($c_msg)) { ?>     <li> <?= $c_msg; ?></li>       <?php } ?>
            <form id="form-nouveau-commentaire" method="post">
                <div class="com_name">
                    <input readonly="readonly" type="text" name="prenom" value="<?= $_SESSION['prenom'] ?>"> 
                </div>
                <div class="whrit_com">
                    <textarea class="area-com" name="commentaire" placeholder="Votre commentaire..."></textarea>
                </div>
                <div >
                    <button class="btn" type="submit"  name="submit_commentaire">Poster mon commentaire</button>
                </div>
            </form>
        

        
    </div>
</div>


<script>
  const btn = document.getElementById("btn-nouveau-commentaire");
  const form = document.getElementById("form-nouveau-commentaire");
  
  btn.addEventListener("click", function() {
      form.scrollIntoView();
    });
</script>

<?php require_once 'footer.php';?>
