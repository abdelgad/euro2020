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
    <link href="assets/css/style.css" rel="stylesheet">


    <!-- Bootstrap core JavaScript -->
    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>


    <!-- JS file -->
    <script type="text/javascript" src="assets/js/jsfile.js"></script>

    <!-- Connection to DB php -->
    <?php require_once 'connection.php'; ?>

    <!-- PHP classes -->
    <?php require_once 'pojos/Team.php'; ?>

    <!-- PHP forms -->
    <?php require_once 'forms/groups-teams_forms.php'; ?>
</head>

<body>
<!-- NAVIGATION BAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
        <a class="navbar-brand" href="index.php">EURO2020</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive"
                aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Accueil</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="groups-teams.php">Groupes & Equipes<span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="standings.php">Classements</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<!-- END NAVIGATION BAR -->

<!-- Header -->
<header class="py-5 bg-image-full" style="background-image: url(assets/img/firstBackground.jpg);">
    <img class="logo-img img-fluid d-block mx-auto" src="assets/img/logo.png" alt="">
</header>

<!--MESSSAGE FOR USER-->
<div class="d-flex justify-content-center">
    <div class="col-sm-10">
        <?php if(isset($messageForUser)) echo $messageForUser;?>
    </div>
</div>

<div class="container pt-3 pb-3 border my-3">
    <h2>Ajouter un groupe:</h2>

    <form method="POST">
        <div class="form-group">
            <label class="my-2 mr-22" for="inlineFormCustomSelectPref">Groupe:</label>
            <select class="custom-select my-1 mr-sm-2" id="inlineFormCustomSelectPref" name="selectLetter">
                <option disabled selected value>Choisissez...</option>
                <?php
                // TODO: Move this code to Seperate file
                $allLetters = array('A', 'B', 'C', 'D', 'E', 'F');
                $query = "SELECT * FROM Groupes;";
                $prepQuery = $connection->prepare($query);
                $prepQuery->execute();
                $groups = $prepQuery->fetchAll(PDO::FETCH_COLUMN, 0);

                $lettersAvailable = array_diff($allLetters, $groups);
                foreach ($lettersAvailable as $letter)
                {
                    echo "<option value='" . $letter . "'>" . $letter . "</option>";
                }
                ?>
            </select>
        </div>
        <div class="field_wrapper">
            <label for="inputTowns">Ville(s)</label>
            <div class="input-group mb-3" id="inputTowns">
                <input type="text" class="form-control" placeholder="Nom de la ville" aria-label="Nom de la ville"
                       aria-describedby="basic-addon2" name="inputFieldTown[]"/>
                <div class="input-group-append">
                    <button title="Add field" class="btn btn-success add_input_button" onclick="void(0);" type="button">
                        +
                    </button>
                </div>
            </div>
        </div>
        <button type="submit" name="submitGroup" class="btn btn-primary">Ajouter</button>
    </form>
</div>


<div class="container pt-3 pb-3 border my-3">
    <h2>Liste des groupes:</h2>

    <?php
    foreach ($groups as $group)
    {
        ?>
        <div class="group">
            <?php echo "<h4>Groupe " . $group . "</h4>"; ?>
            <div class="row">
                <div class="col-12 col-sm-8 col-lg-7">
                    <h6 class="text-muted">Equipes</h6>
                    <ul class="list-group teams-list">
                        <?php
                        $query = "SELECT * FROM Equipes WHERE RefGroupe = :letter;";
                        $prepQuery = $connection->prepare($query);
                        $prepQuery->bindValue('letter', $group, PDO::PARAM_STR);
                        $prepQuery->setFetchMode(PDO::FETCH_CLASS, 'Team');
                        $prepQuery->execute();

                        $nbTeams = $prepQuery->rowCount();

                        if ($nbTeams == 0)
                        {
                            ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent">
                                Aucune équipe dans ce groupe...
                            </li>
                            <?php
                        } else
                        {
                            $teams = $prepQuery->fetchAll();

                            foreach ($teams as $team)
                            {
                                //Get all the matchs that the team already played and finished
                                $query = "SELECT * FROM Matchs WHERE (RefEquipe1 = ? OR RefEquipe2 = ?) AND (ScoreEquipe1 IS NOT NULL AND ScoreEquipe2 IS NOT NULL);";
                                $prepQuery = $connection->prepare($query);
                                $prepQuery->bindValue(1, $team->getNomEquipe(), PDO::PARAM_STR);
                                $prepQuery->bindValue(2, $team->getNomEquipe(), PDO::PARAM_STR);
                                $prepQuery->execute();
                                $nbMatchs = $prepQuery->rowCount();
                                ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent">
                                    <div class="image-parent d-flex align-content-between">
                                        <img src="<?php echo "assets/img_upload/" . $team->getNomFichierDrapeau(); ?>"
                                             class="img-fluid mr-2" alt="quixote">
                                        <?php echo $team->getNomEquipe(); ?>
                                    </div>

                                    <form method="POST">
                                        <input type="hidden" name="teamName"
                                               value="<?php echo $team->getNomEquipe(); ?>">
                                        <button type="submit" class="btn btn-outline-danger btn-sm"
                                                name="deleteTeamSubmit" <?php if ($nbMatchs > 0)
                                        {
                                            echo "hidden";
                                        } ?>>Supprimer
                                        </button>
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
                        //Select all the towns that are related to the group
                        $query = "SELECT RefVille FROM GroupesVilles WHERE Refgroupe = :letter;";
                        $prepQuery = $connection->prepare($query);
                        $prepQuery->bindValue('letter', $group, PDO::PARAM_STR);
                        $prepQuery->execute();
                        $towns = $prepQuery->fetchAll(PDO::FETCH_COLUMN, 0);

                        foreach ($towns as $town)
                        {
                            echo "<li class=\"list-group-item d-flex justify-content-center align-items-center bg-transparent\">" . $town . "</li>";
                        }
                        ?>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-8 col-lg-7">
                    <form method="POST" enctype="multipart/form-data" <?php if ($nbTeams == 4)
                    {
                        echo "hidden";
                    } ?>>
                        <input type="hidden" name="groupLetter" value="<?php echo $group; ?>">
                        <div class="form-group">
                            <label class="text-muted" for="inputTeamNameID">Ajouter équipe:</label>
                            <input type="text" class="form-control" id="inputTeamNameID" aria-describedby="teamHelp"
                                   placeholder="Nom de l'équipe" name="inputTeamName">
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
    <?php
    }
    if (count($groups) == 0)
    {
        echo '<p class="text-muted">Aucun groupe à été ajouté</p>';
    }
    ?>
</div>

<!-- Footer -->
<footer class="py-5 bg-dark">
    <div class="container">
        <p class="m-0 text-center text-white">Copyright &copy; AbdelGad</p>
    </div>
</footer>

</body>

</html>
