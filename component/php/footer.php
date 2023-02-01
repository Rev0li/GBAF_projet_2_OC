    <?php     
    require_once 'config.php  ';
    ?>



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
        
            
                <div >
                    <p>
                    Extranet du groupe GBAF, le but est de permettre des échanges sécurisés à distance.
                    </p>
                </div>
            
        
    

    <div class="">
        © 2022 Copyright:
        <a  href="#!">GBAF</a>
    </div>
    </div>
    </footer>
    </body>

</html>