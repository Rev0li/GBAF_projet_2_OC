    <?php     
    require_once 'config.php  ';
    ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/style.css" rel="stylesheet" media="all">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>Footer</title>
</head>

    <footer >
    
        <div class="case" >
            <div class="">
            <h6>
                <a href="" >Mention Legal</a>
            </h6>
            </div>

            <div class="">
            <h6 >
                <a href="">Contact</a>
            </h6>
            </div>


            <!-- creer une condition pour l'afficher si on est connecter -->
            <div class="">
                <?php if(isset($_SESSION['id'])){ ?>
            <h6>
            <button onclick="window.location.href='deconnexion.php'" type="button" class="btn_deco" > Deconnexion</button>
            </h6>
            <?php }?>
            </div>

            </div>


            <div class="container_flex_wrap" >
        <section >
            <div class="">
                <div >
                    <p>
                    Extranet du groupe GBAF, le but est de permettre des échanges sécurisés à distance.
                    </p>
                </div>
            </div>
        </section>
    

    <div class="">
        © 2022 Copyright:
        <a  href="#!">GBAF</a>
    </div>
    </div>
    </footer>
</html>