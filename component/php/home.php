<?php
session_start();
require_once 'config.php  ';
require_once 'header.php';

$partner = $bdd->query('SELECT * FROM partner ORDER BY ID DESC');
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/style.css" rel="stylesheet" media="all"> 
   
    <title>GBAF</title>
</head>

<body>
<div class="container_flex_wrap ">
    <div class="hero" >
        <h1>Groupement Banque-Assurance Français</h1>
        <p>Repertoire d’informations sur nos partenaires et acteurs du groupe ainsi que sur leurs produits et services bancaires et financiers.</p>
        <div class="contn_hero "><img class="imghero" src="../image/hero.jpg " alt="low angle view of a bank building"></div>
    </div>
</div>

<div class="container_flex_wrap">
    
    <h2 > Voici les partenaires</h2>
    
    <div class="container_partner" >
        <?php while ($p = $partner->fetch()) { ?>

            
            
                        <!-- <h3><?= $p['partner_name'] ?></h3> -->
                        <div class="card-partner" >
                    <img class="img-thumbnail minia" src="../image/miniature/<?= $p['id'] ?>.png" height="80">
                    <p class="truncate"><?= $p['contenu'] ?></p>
                <a class="btn" href="partner.php?id=<?= $p['id'] ?>"> Lire la suite</a>
                </div>
            
        
        <?php } ?>
    </div>
    

</div>
</body>

<?php require_once 'footer.php';?>
</html>