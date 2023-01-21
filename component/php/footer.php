    <?php     
    require_once 'C:/xampp/htdocs/Extranet/component/php/config.php  ';
    ?>

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

    <footer class="text-center" style="background-color: #dcdcdc">
    <div class="container">
        <section class="mt-5">
        <div class="row text-center d-flex justify-content-center pt-5">
            <div class="col-md-3 mt-3">
            <h6 class="text-uppercase font-weight-bold">
                <a href="" >Mention Legal</a>
            </h6>
            </div>

            <div class="col-md-3 mt-3">
            <h6 class="text-uppercase font-weight-bold">
                <a href="" >Contact</a>
            </h6>
            </div>


            <!-- creer une condition pour l'afficher si on est connecter -->
            <?php if(isset($_SESSION['id'])){ ?>
            <div class="col-md-3 mt-3">
            <h6 class="text-uppercase font-weight-bold">
            <button onclick="window.location.href='deconnexion.php'" type="button" class="btn btn-outline-danger btn-rounded" data-mdb-ripple-color="dark"> Deconnexion</button>
            </h6>
            </div>
            <?php }?>

        </section>

        <hr class="my-3" />


        <section class="mb-3 mt-3">
            <div class="row d-flex justify-content-center">
                <div class="col-lg-8">
                    <p>
                    Extranet du groupe GBAF, le but est de permettre des échanges sécurisés à distance.
                    </p>
                </div>
            </div>
        </section>
    </div>

    <div class="text-center p-3"
        style="background-color: rgba(0, 0, 0, 0.2)">
        © 2022 Copyright:
        <a  href="#!">GBAF</a>
    </div>

    </footer>
</html>