<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">



    <div class="row text-center d-flex justify-content-center Hpad">
        <div class="col-md-4">
            <?php if (isset($_SESSION['id'])) { ?><a href="home.php"><?php } ?>
                <img class="logo" src="/Extranet/component/image/GBAF.png " alt="logo de notre societer">
                </a>
        </div>

        <div class="col-md-4 hdmid">
            <h1>GBAF</h1>
        </div>


        <div class="col-md-4 hdrght">
            <?php if (isset($_SESSION['id'])) { ?>
                <div class="preview_pict">
                    <img id="preview_h" src="<?= '../../component/image/profil_pict' . '/' . $_SESSION['id'] . '/' . $_SESSION['profil_pict'] ?>" alt="">
                </div>

                <div class="card_pseudo">
                    <a href="profil.php">
                        <p> <?= $_SESSION['nom'] ?> </p>
                        <p><?= $_SESSION['prenom'] ?> </p>
                    </a>
                </div>
            <?php } ?>
        </div>

    </div>


</head>
<style>
    .hdmid {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .hdrght {
        display: flex;
        justify-content: space-evenly;
        align-items: center;
    }

    .logo {
        max-width: 90px;
    }

    .Hpad {
        background-color: #dcdcdc;
        padding: 20px 0px 20px 0px;
    }

    #preview_h {
        position: relative;
        max-height: 70px;
        border-radius: 50%;
    }
</style>