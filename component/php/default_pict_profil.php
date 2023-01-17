<?php

$pic_name = "profilpict-" . time() . '.jpg';
$dossier = '../image/profil_pict/' . $_SESSION['id'] . '.' . '/';
$chemin = $dossier . $pic_name;
if (!is_dir($dossier)) {
    mkdir($dossier);

    if (is_readable($chemin)) {

        $req = $bdd->prepare("UPDATE utilisateurs SET profil_pict = ? WHERE id = ?");

        $req->execute([($pic_name), $_SESSION['id']]);
        $err_nom[] = 'upload reussi';
        $_SESSION['profil_pict'] = $pic_name;
    }
}
            //ajouter cela a l'inscription aller chercher la photo default copier coller dans le dossier
