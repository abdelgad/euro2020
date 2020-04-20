<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Title -->
    <title>Groupes & Matchs</title>

    <!-- Favicon -->
    <link rel="icon" href="assets/img/favicon.ico" type="image/x-icon"/>

    <!-- Bootstrap core CSS -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- CSS style -->
    <link href="assets/css/style.css?v={random number/string}" rel="stylesheet">


    <!-- Bootstrap core JavaScript -->
    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Connection to DB php -->
    <?php // TODO: Seperate js file ?>
    <script type="text/javascript">
        $(document).ready(function()
        {
            var add_input_button = $('.add_input_button');
            var field_wrapper = $('.field_wrapper');
            var new_field_html = '<div class="input-group mb-3"><input type="text" class="form-control" name="inputFieldTown[]" value="" placeholder="Nom de la ville"/><div class="input-group-append"><button title="Remove field"  class="btn btn-danger remove_input_button" onclick="javascript:void(0);" type="button">−</button></div></div>';

            // Add button dynamically
            $(add_input_button).click(function()
            {
                $(field_wrapper).append(new_field_html);
            });

            // Remove dynamically added button
            $(field_wrapper).on('click', '.remove_input_button', function(e)
            {
                e.preventDefault();
                $(this).parent('div').parent().remove();
            });
        });
    </script>


    <?php require_once 'connection.php'; ?>
</head>

<body>
<!-- PHP STATEMENTS -->
<?php
if(isset($_POST['submitGroup']) && isset($_POST['selectLetter']) && isset($_POST['inputFieldTown']))
{
    $selectedLetter = $_POST['selectLetter'];
    $towns = $_POST['inputFieldTown'];

    if(!empty($selectedLetter) && !empty($towns[0]))
    {
        //Add the selected group to the DB
        $query = "insert into GROUPES values (:letter);";
        $prepQuery = $connection->prepare($query);
        $prepQuery->bindValue('letter', $selectedLetter, PDO::PARAM_STR);
        $prepQuery->execute();

        foreach($towns as $town)
        {
            //Check if town exists in the DB
            $query = "select * from Villes where NomVille = :town;";
            $prepQuery = $connection->prepare($query);
            $prepQuery->bindValue('town', $town, PDO::PARAM_STR);
            $prepQuery->execute();

            //If the town doesn't exist, insert it
            if($prepQuery->rowCount() == 0)
            {
                $query = "insert into Villes values (:town);";
                $prepQuery = $connection->prepare($query);
                $prepQuery->bindValue('town', $town, PDO::PARAM_STR);
                $prepQuery->execute();
            }

            //Add the link between the group and the town
            $query = "insert into GroupesVilles values (:group, :town);";
            $prepQuery = $connection->prepare($query);
            $prepQuery->bindValue('group', $selectedLetter, PDO::PARAM_STR);
            $prepQuery->bindValue('town', $town, PDO::PARAM_STR);
            $prepQuery->execute();
        }
    }
    else
    {
        // TODO: MESSAGE : REMPLISSEZ TOUTS LES CHAMPS
    }
}
?>









<?php
if(isset($_POST['teamSubmit'])  && isset($_POST['inputTeamName']) && isset($_FILES['inputFlagFile']))
{
    $myFile = $_FILES['inputFlagFile'];

    if(!empty($myFile['name']) && !empty($_POST['inputTeamName']))
    {
        $teamName = $_POST['inputTeamName'];
        $fileType = $myFile['type'];

        $query = "select * from Equipes where NomEquipe = :teamName;";
        $prepQuery = $connection->prepare($query);
        $prepQuery->bindValue('teamName', $teamName, PDO::PARAM_STR);
        $prepQuery->execute();

        if($prepQuery->rowCount() == 0)
        {
            if(strtolower($fileType) == 'image/png')
            {
                //Insert team into DB
                $query = "insert into Equipes (NomEquipe, NomFichierDrapeau, RefGroupe) values (:teamName, :flagFileName, :groupLetter);";
                $prepQuery = $connection->prepare($query);
                $prepQuery->bindValue('teamName', $teamName, PDO::PARAM_STR);
                $prepQuery->bindValue('flagFileName', $myFile['name'], PDO::PARAM_STR);
                $prepQuery->bindValue('groupLetter', $_POST['groupLetter'], PDO::PARAM_STR);
                $prepQuery->execute();

                // TODO: Verify if the destination folder exists, if not create it
                // TODO: Verify if the name of the file isnt already used by another file in the destination folder, if true then add 1 at the end of the file name
                $newDir = './assets/img_upload/';
                $tempName = $myFile['tmp_name'];
                move_uploaded_file($tempName, $newDir.$myFile['name']);
            }
            else
            {
                echo "FICHIER PAS BON";
                //TODO: Message : Le type de fichier n'est pas bon maggle
            }
        }
        else
        {
            echo "EQUIPE EXISTE DEJA";
            // TODO: Message: L'équipe existe déja BG
        }
    }
    else
    {
        echo "remplis tout";
        //TODO: MESSAGE : REMPLIS TOUS LES CHAMPS
    }
}
?>







<?php
if (isset($_POST['deleteTeamSubmit']))
{
    $teamToBeDeleted = $_POST['teamName'];

    //Supprimer les matchs dont l'équipe est participante;
    $query = "DELETE FROM Matchs WHERE (RefEquipe1 = ? OR RefEquipe2 = ?);";
    $prepQuery = $connection->prepare($query);
    $prepQuery->bindValue(1, $teamToBeDeleted, PDO::PARAM_STR);
    $prepQuery->bindValue(2, $teamToBeDeleted, PDO::PARAM_STR);
    $prepQuery->execute();

    //Supprimer l'équipe même
    $query = "DELETE FROM Equipes where NomEquipe = :teamName;";
    $prepQuery = $connection->prepare($query);
    $prepQuery->bindValue('teamName', $teamToBeDeleted, PDO::PARAM_STR);
    $prepQuery->execute();
}
?>


















<!-- NAVIGATION BAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
        <a class="navbar-brand" href="#">Start Bootstrap</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="groups-teams.php">Groupes & Equipes<span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="standings.php">Classements</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Contact</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<!-- END NAVIGATION BAR -->

<!-- Header - set the background image for the header in the line below -->
<header class="py-5 bg-image-full" style="background-image: url(assets/img/firstBackground.jpg);">
    <img class="logo-img img-fluid d-block mx-auto" src="assets/img/logo.png" alt="">
</header>





<div class="container pt-3 pb-3 border my-3">
    <h2>Ajouter un groupe:</h2>

    <form method="POST">
        <div class="form-group">
            <label class="my-2 mr-22" for="inlineFormCustomSelectPref">Groupe:</label>
            <select class="custom-select my-1 mr-sm-2" id="inlineFormCustomSelectPref" name="selectLetter">
                <option disabled selected value>Choose...</option>
                <?php
                // TODO: Move this code to Seperate file
                $lettersAvailable = array('A', 'B', 'C', 'D', 'E', 'F');
                $query = "select * from GROUPES;";
                $prepQuery = $connection->prepare($query);
                $prepQuery->execute();

                while ($ligne = $prepQuery->fetch(PDO::FETCH_ASSOC))
                {
                    if(($key = array_search($ligne['Lettre'], $lettersAvailable)) !== false)
                    {
                        unset($lettersAvailable[$key]);
                    }
                }
                foreach ($lettersAvailable as $letter)
                {
                    echo "<option value='".$letter."'>".$letter."</option>";
                }
                ?>
            </select>
        </div>

        <div class="field_wrapper">
            <label for="inputTowns">Ville(s)</label>
            <div class="input-group mb-3" id="inputTowns">
                <input  type="text" class="form-control" placeholder="Nom de la ville" aria-label="Nom de la ville" aria-describedby="basic-addon2" name="inputFieldTown[]"/>
                <div class="input-group-append">
                    <button title="Add field"  class="btn btn-success add_input_button" onclick="javascript:void(0);" type="button">+</button>
                </div>
            </div>
        </div>

        <button type="submit" name="submitGroup" class="btn btn-primary">Ajouter</button>

    </form>
</div>






<div class="container pt-3 pb-3 border my-3">
    <h2>Liste des groupes:</h2>

    <?php
    $query = "select * from GROUPES;";
    $prepQuery = $connection->prepare($query);
    $prepQuery->execute();


    while ($ligneGroupe = $prepQuery->fetch(PDO::FETCH_ASSOC))
    {
        ?>

        <div class="group">
            <?php echo "<h4>Groupe ".$ligneGroupe['Lettre']."</h4>"; ?>
            <div class="row">
                <div class="col-12 col-sm-8 col-lg-7">
                    <h6 class="text-muted">Equipes</h6>
                    <ul class="list-group teams-list">


                        <?php
                        $query = "select NomEquipe, NomFichierDrapeau from Equipes where RefGroupe = :letter;";
                        $prepQuery2 = $connection->prepare($query);
                        $prepQuery2->bindValue('letter', $ligneGroupe['Lettre'], PDO::PARAM_STR);
                        $prepQuery2->execute();

                        $nbTeams = $prepQuery2->rowCount();
                        if ($nbTeams == 0)
                        {
                            ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent">
                                Aucune équipe dans ce groupe...
                            </li>
                            <?php
                        }
                        else
                        {
                            while ($ligneEquipe = $prepQuery2->fetch(PDO::FETCH_ASSOC))
                            {
                                $query = "SELECT * FROM Matchs WHERE (RefEquipe1 = ? OR RefEquipe2 = ?) AND (ScoreEquipe1 IS NOT NULL AND ScoreEquipe2 IS NOT NULL);";
                                $prepQuery3 = $connection->prepare($query);
                                $prepQuery3->bindValue(1, $ligneEquipe['NomEquipe'], PDO::PARAM_STR);
                                $prepQuery3->bindValue(2, $ligneEquipe['NomEquipe'], PDO::PARAM_STR);
                                $prepQuery3->execute();
                                $nbMatchs = $prepQuery3->rowCount();
                                ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent">
                                    <div class="image-parent">
                                        <img src="<?php echo "assets/img_upload/".$ligneEquipe['NomFichierDrapeau']; ?>" class="img-fluid" alt="quixote">
                                    </div>
                                    <?php echo $ligneEquipe['NomEquipe']; ?>

                                    <form method="POST">
                                        <input type="hidden" name="teamName" value="<?php echo $ligneEquipe['NomEquipe']; ?>">
                                        <button type="submit" class="btn btn-outline-danger btn-sm" name="deleteTeamSubmit" <?php if ($nbMatchs > 0){echo "hidden";} ?>>Supprimer</button>
                                    </form>
                                </li>
                                <?php
                            }
                        }
                        ?>


                    </ul>
                </div>
                <div class="col-12 col-sm-8 col-lg-4">
                    <h6 class="text-muted">Villes</h6>
                    <ul class="list-group teams-list">
                        <?php
                        $query = "select RefVille from GroupesVilles where Refgroupe = :letter;";
                        $prepQuery2 = $connection->prepare($query);
                        $prepQuery2->bindValue('letter', $ligneGroupe['Lettre'], PDO::PARAM_STR);
                        $prepQuery2->execute();

                        while ($ligneVille = $prepQuery2->fetch(PDO::FETCH_ASSOC))
                        {
                            echo "<li class=\"list-group-item d-flex justify-content-center align-items-center bg-transparent\">".$ligneVille['RefVille']."</li>";
                        }
                        ?>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-8 col-lg-7">

                    <form method="POST" enctype="multipart/form-data" <?php if($nbTeams == 4){echo "hidden";} ?>>
                        <input type="hidden" name="groupLetter" value="<?php echo $ligneGroupe['Lettre']; ?>">
                        <div class="form-group">
                            <label class="text-muted" for="inputTeamNameID">Ajouter équipe:</label>
                            <input type="text" class="form-control" id="inputTeamNameID" aria-describedby="teamHelp" placeholder="Nom de l'équipe" name="inputTeamName">
                        </div>
                        <div class="form-group">
                            <label class="text-muted" for="inputFlagFileID">Ajouter le drapeau de l'équipe:</label>
                            <input type="file" class="form-control-file" id="inputFlagFileID" name="inputFlagFile">
                        </div>
                        <button type="submit" class="btn btn-primary" name="teamSubmit">Ajouter</button>
                    </form>

                </div>
            </div>
        </div>
    <?php } ?>
</div>


<!-- Footer -->
<footer class="py-5 bg-dark">
    <div class="container">
        <p class="m-0 text-center text-white">Copyright &copy; Abdelrahman et fils</p>
    </div>
</footer>


</body>

</html>
