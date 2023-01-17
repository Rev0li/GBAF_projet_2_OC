<?php
session_start();
require 'C:/xampp/htdocs/Extranet/component/php/config.php  ';
require_once '/xampp/htdocs/Extranet/component/php/header.php';

$partner = $bdd->query('SELECT * FROM partner ORDER BY ID DESC');
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <title>GBAF</title>
</head>

<body>
    <div class="hero">
        <h1>Groupement Banque-Assurance Français</h1>
        <p>Repertoire d’informations sur nos partenaires et acteurs du groupe ainsi que sur leurs produits et services bancaires et financiers.</p>
        <div class="ims_hero"><img src="../image/hero.jpg " alt="low angle view of a bank building"></div>
    </div>


    <h2> Voici les partenaires</h2>
    <p>
    <ul class="acteur">
        <?php while ($p = $partner->fetch()) { ?>

            <li>
                <img src="../image/miniature/<?= $p['id'] ?>.png" width="100"><br>
                <a href="partner.php?id=<?= $p['id'] ?>"> <?= $p['partner_name'] ?></a>
            </li><br>
        <?php } ?>
    </ul>
    </p>

    <div class="container">

        <div class="partnaire">

            <div class="partnaire_logo">
                <img src="" alt="">
            </div>

            <div class="partnaire_pres">
                <h3></h3>
                <p></p>
            </div>

            <div class="comment">
                <p class="xcomment"></p>
                <div class="new_comment"></div>
                <div class="like_dislike"></div>
                <div class="post_comment"></div>

            </div>
        </div>

    </div>
</body>

<?php require_once '/xampp/htdocs/Extranet/component/php/footer.php';
?>

</html>