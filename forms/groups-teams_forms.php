<?php


//ADD GROUP FORM
if (isset($_POST['submitGroup']) && isset($_POST['selectLetter']) && isset($_POST['inputFieldTown']))
{
    $selectedLetter = $_POST['selectLetter'];
    $towns = $_POST['inputFieldTown'];

    if (!empty($selectedLetter) && !empty($towns[0]))
    {
        //Add the selected group to the DB
        $query = "insert into GROUPES values (:letter);";
        $prepQuery = $connection->prepare($query);
        $prepQuery->bindValue('letter', $selectedLetter, PDO::PARAM_STR);
        $prepQuery->execute();

        foreach ($towns as $town)
        {
            //Check if town exists in the DB
            $query = "select * from Villes where NomVille = :town;";
            $prepQuery = $connection->prepare($query);
            $prepQuery->bindValue('town', $town, PDO::PARAM_STR);
            $prepQuery->execute();

            //If the town doesn't exist, insert it
            if ($prepQuery->rowCount() == 0)
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
    } else
    {
        // TODO: MESSAGE : REMPLISSEZ TOUTS LES CHAMPS
    }
}


//ADD TEAM FORM
if (isset($_POST['teamSubmit']) && isset($_POST['inputTeamName']) && isset($_FILES['inputFlagFile']))
{
    $myFile = $_FILES['inputFlagFile'];

    if (!empty($myFile['name']) && !empty($_POST['inputTeamName']))
    {
        $fileType = $myFile['type'];
        if (strtolower($fileType) == 'image/png')
        {


            $query = "select * from Equipes where NomEquipe = :teamName;";
            $prepQuery = $connection->prepare($query);
            $prepQuery->bindValue('teamName', $_POST['inputTeamName'], PDO::PARAM_STR);
            $prepQuery->execute();

            if ($prepQuery->rowCount() == 0)
            {
                $teamToBeAdded = new Team($_POST['inputTeamName'], $myFile['name'], $_POST['groupLetter']);
                //Insert team into DB
                $query = "INSERT INTO Equipes VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
                $prepQuery = $connection->prepare($query);
                $prepQuery->bindValue(1, $teamToBeAdded->getNomEquipe(), PDO::PARAM_STR);
                $prepQuery->bindValue(2, $teamToBeAdded->getNomFichierDrapeau(), PDO::PARAM_STR);
                $prepQuery->bindValue(3, $teamToBeAdded->getRefGroupe(), PDO::PARAM_STR);
                $prepQuery->bindValue(4, $teamToBeAdded->getNbMatchJoue(), PDO::PARAM_STR);
                $prepQuery->bindValue(5, $teamToBeAdded->getNbMatchGagne(), PDO::PARAM_STR);
                $prepQuery->bindValue(6, $teamToBeAdded->getNbMatchNul(), PDO::PARAM_STR);
                $prepQuery->bindValue(7, $teamToBeAdded->getNbMatchPerdu(), PDO::PARAM_STR);
                $prepQuery->bindValue(8, $teamToBeAdded->getNbButMarque(), PDO::PARAM_STR);
                $prepQuery->bindValue(9, $teamToBeAdded->getNbButEnc(), PDO::PARAM_STR);
                $prepQuery->bindValue(10, $teamToBeAdded->getNbPoints(), PDO::PARAM_STR);

                $prepQuery->execute();

                // TODO: Verify if the destination folder exists, if not create it
                // TODO: Verify if the name of the file isnt already used by another file in the destination folder, if true then add 1 at the end of the file name
                $newDir = './assets/img_upload/';
                $tempName = $myFile['tmp_name'];
                move_uploaded_file($tempName, $newDir . $myFile['name']);
            } else
            {
                echo "EQUIPE EXISTE DEJA";
                // TODO: Message: L'équipe existe déja BG
            }
        } else
        {
            echo "Fichier pas bon";
            // TODO: Fichier pas bon message
        }
    } else
    {
        echo "remplis tout";
        //TODO: MESSAGE : REMPLIS TOUS LES CHAMPS
    }
}


//DELETE TEAM FORM
if (isset($_POST['deleteTeamSubmit']))
{
    $teamToBeDeleted = $_POST['teamName'];

    //Delete all the matchs that the team is part of
    $query = "DELETE FROM Matchs WHERE (RefEquipe1 = ? OR RefEquipe2 = ?);";
    $prepQuery = $connection->prepare($query);
    $prepQuery->bindValue(1, $teamToBeDeleted, PDO::PARAM_STR);
    $prepQuery->bindValue(2, $teamToBeDeleted, PDO::PARAM_STR);
    $prepQuery->execute();

    //Delete the team
    $query = "DELETE FROM Equipes where NomEquipe = :teamName;";
    $prepQuery = $connection->prepare($query);
    $prepQuery->bindValue('teamName', $teamToBeDeleted, PDO::PARAM_STR);
    $prepQuery->execute();
}





