<?php
session_start();
require 'C:/xampp/htdocs/Extranet/component/php/config.php';

if (isset($_POST['article_titre'], $_POST['article_contenu'])) {
    if (!empty($_POST['article_titre']) and !empty($_POST['article_contenu'])) {
        $article_titre = htmlspecialchars($_POST['article_titre']);
        $article_contenu = htmlspecialchars($_POST['article_contenu']);

        $ins = $bdd->prepare('INSERT INTO partner (partner_name,contenu) VALUES (?, ?)');
        $ins->execute(array($article_titre, $article_contenu));
        $lastid = $bdd->lastInsertId();


        if (isset($_FILES['miniature']) && !empty($_FILES['miniature']['name'])) {
            if (exif_imagetype($_FILES['miniature']['tmp_name']) == 3) {
                $chemin = "../image/miniature/" . $lastid . ".png";
                move_uploaded_file($_FILES['miniature']['tmp_name'], $chemin);
                $message = "image ok";
            } else {
                $message = "Png obligatoire";
            }
        }


        $message = 'Votre article a bien etait posté';
    } else {
        $message = 'Veuillez remplir tous les champs';
    }
}

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redaction</title>
</head>

<body>
    <form method="post" enctype="multipart/form-data">
        <input type="text" name="article_titre" placeholder="Titre"><br>
        <textarea name="article_contenu" placeholder="Contenu de l'article"></textarea><br>
        <input type="file" value="miniature" name="miniature"><br />
        <input type="submit" value="Envoyer l'article">

    </form>
    <?php if (isset($message)) {
        echo $message;
    } ?>
</body>

</html>