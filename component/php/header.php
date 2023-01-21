<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/style.css" rel="stylesheet" media="all"> 
    <link href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<header>
 <div class="container-fluid">
    <div class="row text-center d-flex justify-content-center Hpad">
        <div class="col-md-4 mt-3 ">
            
            <?php if (isset($_SESSION['id'])) { ?>
                <a href="home.php">
                    <?php }else { ?>
                <a href="../../index.php">
                    <?php } ?>
                <img class="logo" src="/Extranet/component/image/GBAF.png " alt="logo de notre societer">
                </a>
                </a>

        </div>

        <div class="col-md-4 mt-3 hdmid">
            <h1>GBAF</h1>
        </div>


        <div class="col-md-4 mt-3 hdrght">
            <?php if (isset($_SESSION['id'])) { ?>
                <div class="preview_pict">
                    <img id="preview_h" src="<?= '../../component/image/profil_pict' . '/' . $_SESSION['id'] . '/' . $_SESSION['profil_pict'] ?>" alt="">
                </div>

                <div class="card_pseudo mt-2">
                    <a href="profil.php">
                        <p> <?= $_SESSION['nom'] ?>  - <?= $_SESSION['prenom'] ?> </p>
                    </a>
                </div>
            <?php } ?>
        </div>

    </div>
    </div>


</header>
<body>
    
</body>
</html>