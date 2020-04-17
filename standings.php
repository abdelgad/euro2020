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
</head>

<body>
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
                <li class="nav-item">
                    <a class="nav-link" href="groups-teams.php">Groupes & Equipes</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="#standings.php">Classements<span class="sr-only">(current)</span></a>
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










<?php // TODO: Fix the team name overflow ?>
<?php // TODO: Fix the group title font size ?>
<div class="container pt-3 pb-3 border my-3">
    <h2>Classements & Matchs</h2>

    <?php
    $query = "SELECT * FROM Groupes;";
    $prepQuery = $connection->prepare($query);
    $prepQuery->execute();


    while ($ligneGroupe = $prepQuery->fetch(PDO::FETCH_ASSOC))
    {
        ?>
        <div class="group">
            <?php echo "<h4>Groupe ".$ligneGroupe['Lettre']."</h4>"; ?>
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
                        $prepQuery2 = $connection->prepare($query);
                        $prepQuery2->bindValue('letter', $ligneGroupe['Lettre'], PDO::PARAM_STR);
                        $prepQuery2->execute();
                        $nbTeams = $prepQuery2->rowCount();
                        while ($ligneEquipe = $prepQuery2->fetch(PDO::FETCH_ASSOC))
                        {
                            ?>
                            <tr>
                                <?php // TODO: Replace the 1's with the real placement number ?>
                                <td data-label="No" class="number"><?php echo "1"; ?></td>
                                <td data-label="Équipe"><div><img class="flag-standings-table" src="<?php echo "assets/img_upload/".$ligneEquipe['NomFichierDrapeau']; ?>"></img><span><?php echo $ligneEquipe['NomEquipe']; ?></span></div></td>
                                <td data-label="J"><?php echo $ligneEquipe['nbMatchJoue']; ?></td>
                                <td data-label="G"><?php echo $ligneEquipe['nbMatchGagne']; ?></td>
                                <td data-label="N"><?php echo $ligneEquipe['nbMatchNul']; ?></td>
                                <td data-label="P"><?php echo $ligneEquipe['nbMatchPerdu']; ?></td>
                                <td data-label="Bp"><?php echo $ligneEquipe['nbButMarque']; ?></td>
                                <td data-label="Bc"><?php echo $ligneEquipe['nbButEnc']; ?></td>
                                <td data-label="+/-"><?php echo $ligneEquipe['nbButMarque'] - $ligneEquipe['nbButEnc']; ?></td>
                                <td data-label="PTS"><?php echo $ligneEquipe['nbPoints']; ?></td>
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
                        $query = "SELECT * FROM Matchs WHERE Matchs.RefEquipe1 IN (SELECT NomEquipe FROM `Equipes` WHERE Equipes.RefGroupe = ?) OR Matchs.RefEquipe2 IN (SELECT NomEquipe FROM `Equipes` WHERE Equipes.RefGroupe = ?);";
                        $prepQuery2 = $connection->prepare($query);
                        $prepQuery2->bindValue(1, $ligneGroupe['Lettre'], PDO::PARAM_STR);
                        $prepQuery2->bindValue(2, $ligneGroupe['Lettre'], PDO::PARAM_STR);
                        $prepQuery2->execute();
                        $nbMatchs = $prepQuery2->rowCount();
                        if ($nbMatchs > 0)
                        {
                            echo '<label class="text-muted" for="listOfMatchs">Liste des matchs: </label>';
                            echo '<ul class="list-group" id="listOfMatchs">';
                            while ($ligneMatch = $prepQuery2->fetch(PDO::FETCH_ASSOC))
                            {
                                $query = "SELECT NomFichierDrapeau FROM `Equipes` WHERE Equipes.NomEquipe = :team;";
                                $prepQuery3 = $connection->prepare($query);
                                $prepQuery3->bindValue('team', $ligneMatch['RefEquipe1'], PDO::PARAM_STR);
                                $prepQuery3->execute();
                                $flagFileNameTeam1 = $prepQuery3->fetch(PDO::FETCH_ASSOC)['NomFichierDrapeau'];

                                $prepQuery3 = $connection->prepare($query);
                                $prepQuery3->bindValue('team', $ligneMatch['RefEquipe2'], PDO::PARAM_STR);
                                $prepQuery3->execute();
                                $flagFileNameTeam2 = $prepQuery3->fetch(PDO::FETCH_ASSOC)['NomFichierDrapeau'];
                                ?>
                                <li class="list-group-item d-sm-flex justify-content-around align-items-center bg-transparent">
                                    <span><img src="<?php echo "assets/img_upload/".$flagFileNameTeam1; ?>" class="image-parent" alt=""></img><a class="ml-1"><?php echo $ligneMatch['RefEquipe1']; ?></a></span>

                                    <div>
                                        <form class="form-inline" method="post">
                                            <input type="number" style="width: 3em" class="form-control mr-2" placeholder="X1">
                                            <button type="submit" class="btn btn-primary btn-sm" name="enterScoreButton">Enter</button>
                                            <input type="number" style="width: 3em" class="form-control ml-2" placeholder="X2">
                                        </form>
                                    </div>

                                    <span><img src="<?php echo "assets/img_upload/".$flagFileNameTeam2; ?>" class="image-parent" alt=""></img><a class="ml-1"><?php echo $ligneMatch['RefEquipe2']; ?></a></span>
                                </li>
                                <?php
                            }
                            ?>
                            </ul>
                            <?php
                        }
                        else
                        {
                            echo '<div id="generateMatchsButtonWrapper"><button type="button" class="btn btn-primary btn-lg">Génerer la liste des matchs</button></div>';
                        }
                    }
                    ?>
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
    <!-- /.container -->
</footer>

</body>

</html>
