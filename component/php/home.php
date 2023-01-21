<?php
session_start();
require_once 'C:/xampp/htdocs/Extranet/component/php/config.php  ';
require_once '/xampp/htdocs/Extranet/component/php/header.php';

$partner = $bdd->query('SELECT * FROM partner ORDER BY ID DESC');
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/style.css" rel="stylesheet" media="all"> 
    <link href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <title>GBAF</title>
</head>

<body>
<div class="container-fluid text-center ">
    <div class="hero" >
        <h1>Groupement Banque-Assurance Français</h1>
        <p>Repertoire d’informations sur nos partenaires et acteurs du groupe ainsi que sur leurs produits et services bancaires et financiers.</p>
        <div class="contn_hero "><img class="imghero" src="../image/hero.jpg " alt="low angle view of a bank building"></div>
    </div>
</div>
<div class="container text-center mt-3">
    
    <h2 class="mt-3"> Voici les partenaires</h2>
    
    <div class="acteur row  me-3 ms-3">
        <?php while ($p = $partner->fetch()) { ?>

            <div class="col-12  mt-3 mb-3 shadow-sm">
                <div class="row card">
                        <!-- <h3><?= $p['partner_name'] ?></h3> -->
                        <div class="col-12 mt-3 mb-3 ">
                    <img class="img-thumbnail minia" src="../image/miniature/<?= $p['id'] ?>.png" height="80">
                    <p class="truncate"><?= $p['contenu'] ?></p>
                <a class="btn btn-primary" href="partner.php?id=<?= $p['id'] ?>"> Lire la suite</a>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
    

    </div>
</body>

<?php require_once '/xampp/htdocs/Extranet/component/php/footer.php';?>
</html>