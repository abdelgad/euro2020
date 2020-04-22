<?php
try
{
    $connection = new PDO('mysql:host=localhost;dbname=BD19GAD', 'BD19GAD', 'Labodon54');
    $connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    //echo 'Connexion réussie<br/>';
} catch (PDOException $e)
{
    //echo 'Erreur connexion : '.$e->getMessage();
    die();
}
?>
