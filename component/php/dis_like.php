<?php
session_start();
require 'config.php';

if (isset($_GET['t'], $_GET['id']) and !empty($_GET['t']) and !empty($_GET['id'])) {
    $getid = (int) $_GET['id'];
    $gett = (int) $_GET['t'];

    $sessionid = $_SESSION['id'];

    $check = $bdd->prepare('SELECT id FROM partner WHERE id = ?');
    $check->execute(array($getid));

    if ($check->rowCount() == 1) {
        if ($gett == 1) {
            $check_like = $bdd->prepare('SELECT id FROM likes WHERE id_partner = ? AND id_utilisateurs = ?');
            $check_like->execute(array($getid, $sessionid));

            $del = $bdd->prepare('DELETE FROM dislikes WHERE id_partner = ? AND id_utilisateurs = ?');
            $del->execute(array($getid, $sessionid));

            if ($check_like->rowCount() == 1) {
                $del = $bdd->prepare('DELETE FROM likes WHERE id_partner = ? AND id_utilisateurs = ?');
                $del->execute(array($getid, $sessionid));
            } else {
                $ins = $bdd->prepare('INSERT INTO likes (id_partner, id_utilisateurs) VALUES (?, ?) ');
                $ins->execute(array($getid, $sessionid));
            }
        } elseif ($gett == 2) {
            $check_dislike = $bdd->prepare('SELECT id FROM dislikes WHERE id_partner = ? AND id_utilisateurs = ?');
            $check_dislike->execute(array($getid, $sessionid));

            $del = $bdd->prepare('DELETE FROM likes WHERE id_partner = ? AND id_utilisateurs = ?');
            $del->execute(array($getid, $sessionid));

            if ($check_dislike->rowCount() == 1) {
                $del = $bdd->prepare('DELETE FROM dislikes WHERE id_partner = ? AND id_utilisateurs = ?');
                $del->execute(array($getid, $sessionid));
            } else {
                $ins = $bdd->prepare('INSERT INTO dislikes (id_partner, id_utilisateurs) VALUES (?, ?) ');
                $ins->execute(array($getid, $sessionid));
            }
        }
        header('Location: partner.php?id=' . $getid);
    }
}
?>
