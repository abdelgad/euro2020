<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Title -->
    <title>Classements</title>

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
    <?php require_once 'connection.php'; ?>

    <!-- PHP classes -->
    <?php require_once 'pojos/Team.php'; ?>
    <?php require_once 'pojos/Match.php'; ?>

    <!-- PHP forms -->
    <?php require_once 'forms/standings_forms.php'; ?>
</head>

<body>
<!-- NAVIGATION BAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
        <a class="navbar-brand" href="#">Start Bootstrap</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive"
                aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="groups-teams.php">Groupes & Equipes</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="#">Classements<span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Contact</a>
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


<?php // TODO: Fix the team name overflow ?>
<?php // TODO: Fix the group title font size ?>
<div class="container pt-3 pb-3 border my-3">
    <h2>Classements & Matchs</h2>

    <?php
    $query = "SELECT * FROM Groupes;";
    $prepQuery = $connection->prepare($query);
    $prepQuery->execute();
    $groups = $prepQuery->fetchAll(PDO::FETCH_COLUMN, 0);

    foreach ($groups as $group)
    {
        ?>
        <div class="group">
            <?php echo "<h4>Groupe " . $group . "</h4>"; ?>
            <div class="row mb-4">
                <div class="col">
                    <label class="text-muted" for="standingsOfGroup">Classements du groupe:</label>
                    <table id="standingsOfGroup">
                        <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Équipe</th>
                            <th scope="col">J</th>
                            <th scope="col">G</th>
                            <th scope="col">N</th>
                            <th scope="col">P</th>
                            <th scope="col">Bp</th>
                            <th scope="col">Bc</th>
                            <th scope="col">+/-</th>
                            <th scope="col">PTS</th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php
                        $query = "SELECT * FROM Equipes WHERE RefGroupe = :letter;";
                        $prepQuery = $connection->prepare($query);
                        $prepQuery->bindValue('letter', $group, PDO::PARAM_STR);
                        $prepQuery->setFetchMode(PDO::FETCH_CLASS, 'Team');
                        $prepQuery->execute();
                        $nbTeams = $prepQuery->rowCount();
                        $teams = insertion_Sort($prepQuery->fetchAll());

                        foreach ($teams as $team)
                        {
                            ?>
                            <tr>
                                <?php // TODO: Replace the 1's with the real placement number
                                ?>
                                <td data-label="No" class="number"><?php echo "1"; ?></td>
                                <td data-label="Équipe">
                                    <div><img class="flag-standings-table"
                                              src="<?php echo "assets/img_upload/" . $team->getNomFichierDrapeau(); ?>"
                                              alt="flag"><span><?php echo $team->getNomEquipe(); ?></span></div>
                                </td>
                                <td data-label="J"><?php echo $team->getnbMatchJoue(); ?></td>
                                <td data-label="G"><?php echo $team->getnbMatchGagne(); ?></td>
                                <td data-label="N"><?php echo $team->getnbMatchNul(); ?></td>
                                <td data-label="P"><?php echo $team->getnbMatchPerdu(); ?></td>
                                <td data-label="Bp"><?php echo $team->getnbButMarque(); ?></td>
                                <td data-label="Bc"><?php echo $team->getnbButEnc(); ?></td>
                                <td data-label="+/-"><?php echo $team->getnbButMarque() - $team->getnbButEnc(); ?></td>
                                <td data-label="PTS"><?php echo $team->getnbPoints(); ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <?php
                    if ($nbTeams == 4)
                    {
                        //Get all the matchs related to this group
                        $query = "SELECT * FROM Matchs WHERE Matchs.RefEquipe1 IN (SELECT NomEquipe FROM `Equipes` WHERE Equipes.RefGroupe = ?) OR Matchs.RefEquipe2 IN (SELECT NomEquipe FROM `Equipes` WHERE Equipes.RefGroupe = ?);";
                        $prepQuery = $connection->prepare($query);
                        $prepQuery->bindValue(1, $group, PDO::PARAM_STR);
                        $prepQuery->bindValue(2, $group, PDO::PARAM_STR);
                        $prepQuery->setFetchMode(PDO::FETCH_CLASS, 'Match');
                        $prepQuery->execute();
                        $nbMatchs = $prepQuery->rowCount();

                        if ($nbMatchs > 0) //If the matchs are not generated
                        {
                            if ($nbMatchs < 6) //If some matchs exist but not all of them, generate the rest
                            {
                                //Find the teams with no matchs and add them to an array
                                $query = "SELECT NomEquipe FROM Equipes WHERE RefGroupe = :letter AND (SELECT COUNT(*) FROM Matchs WHERE Matchs.RefEquipe1 = NomEquipe OR Matchs.RefEquipe2 = NomEquipe) = 0;";
                                $prepQuery2 = $connection->prepare($query);
                                $prepQuery2->bindValue('letter', $group, PDO::PARAM_STR);
                                $prepQuery2->execute();
                                $teamsWithoutMatchs = $prepQuery2->fetchAll(PDO::FETCH_COLUMN, 0);


                                //Find the teams to be played against and add them to an array
                                $query = "SELECT NomEquipe FROM Equipes WHERE RefGroupe = :letter;";
                                $prepQuery2 = $connection->prepare($query);
                                $prepQuery2->bindValue('letter', $group, PDO::PARAM_STR);
                                $prepQuery2->execute();
                                $teamsToPlayAgainst = $prepQuery2->fetchAll(PDO::FETCH_COLUMN, 0);


                                //Create matchs for the new teams with no matchs
                                foreach ($teamsWithoutMatchs as $team1)
                                {
                                    foreach ($teamsToPlayAgainst as $key1 => $team2)
                                    {
                                        if ($team1 != $team2)
                                        {
                                            $query = "INSERT INTO Matchs (RefEquipe1, RefEquipe2) values (:team1, :team2);";
                                            $prepQuery2 = $connection->prepare($query);
                                            $prepQuery2->bindValue('team1', $team1, PDO::PARAM_STR);
                                            $prepQuery2->bindValue('team2', $team2, PDO::PARAM_STR);
                                            $prepQuery2->execute();
                                        } else
                                        {
                                            unset($teamsToPlayAgainst[$key1]);
                                        }
                                    }
                                }
                                //Reload the matchs;
                                $prepQuery->execute();
                            }
                            //Display Matchs
                            echo '<label class="text-muted" for="listOfMatchs">Liste des matchs: </label>';
                            echo '<ul class="list-group" id="listOfMatchs">';

                            $matchs = $prepQuery->fetchAll();
                            foreach ($matchs as $match)
                            {
                                $query = "SELECT NomFichierDrapeau FROM `Equipes` WHERE Equipes.NomEquipe = :team;";
                                $prepQuery = $connection->prepare($query);
                                $prepQuery->bindValue('team', $match->getRefEquipe1(), PDO::PARAM_STR);
                                $prepQuery->execute();
                                $flagFileNameTeam1 = $prepQuery->fetch(PDO::FETCH_ASSOC)['NomFichierDrapeau'];

                                $prepQuery = $connection->prepare($query);
                                $prepQuery->bindValue('team', $match->getRefEquipe2(), PDO::PARAM_STR);
                                $prepQuery->execute();
                                $flagFileNameTeam2 = $prepQuery->fetch(PDO::FETCH_ASSOC)['NomFichierDrapeau'];
                                ?>
                                <li class="list-group-item d-sm-flex justify-content-around align-items-center bg-transparent">
                                    <span><img src="<?php echo "assets/img_upload/" . $flagFileNameTeam1; ?>"
                                               class="image-parent" alt=""><a
                                                class="ml-1"><?php echo $match->getRefEquipe1(); ?></a></span>
                                    <div>

                                        <?php
                                        //If the match doesn't have scores
                                        if (is_null($match->getScoreEquipe1()) && is_null($match->getScoreEquipe2()))
                                        {
                                            ?>
                                            <form class="form-inline" method="post">
                                                <input type="hidden" name="numMatch"
                                                       value="<?php echo $match->getNumMatch() ?>">
                                                <input type="number" name="scoreTeam1" style="width: 3em"
                                                       class="form-control mr-2" placeholder="X1">
                                                <button type="submit" class="btn btn-primary btn-sm"
                                                        name="enterScoreButton">Enter
                                                </button>
                                                <input type="number" name="scoreTeam2" style="width: 3em"
                                                       class="form-control ml-2" placeholder="X2">
                                            </form>
                                            <?php
                                        } //Else, the match has scores, display it
                                        else
                                        {
                                            echo $match->getScoreEquipe1() . ' - ' . $match->getScoreEquipe2();
                                        }
                                        ?>
                                    </div>
                                    <span><img src="<?php echo "assets/img_upload/" . $flagFileNameTeam2; ?>"
                                               class="image-parent" alt=""><a
                                                class="ml-1"><?php echo $match->getRefEquipe2(); ?></a></span>
                                </li>
                                <?php
                            }
                            ?>
                            </ul>
                            <?php
                        } else
                        { //If group has 4 teams but 0 matchs, display the option to generate matchs
                            ?>
                            <form method="post">
                                <input type="hidden" name="groupLetter" value="<?php echo $group; ?>">
                                <div id="generateMatchsButtonWrapper">
                                    <button type="submit" class="btn btn-primary btn-lg"
                                            name="generateMatchsButton">
                                        Générer la liste des matchs
                                    </button>
                                </div>
                            </form>
                            <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
        <?php
    }
    ?>
</div>


<!-- Footer -->
<footer class="py-5 bg-dark">
    <div class="container">
        <p class="m-0 text-center text-white">Copyright &copy; Abdelrahman et fils</p>
    </div>
</footer>

</body>

</html>
