<?php

/*

    Ce fichier n'est plus utilisé. Il permet d'oiuvrir le répertoire de documentation présent dans le projet

*/

// Le chemin absolu du dossier public/documentation
$docPath = realpath(__DIR__.'/../public/documentation/');

// La commande à exécuter pour ouvrir le dossier dans l'explorateur de fichiers (valable seulement sur window)
$command = "explorer.exe $docPath";

// Exécution de la commande
shell_exec($command);

// Redirige vers l'index
header('Location: ../index.php');
exit();
?>
