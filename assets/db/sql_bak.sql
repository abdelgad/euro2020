CREATE TABLE `Groupes` (
                           `Lettre` varchar(1) NOT NULL,
                           PRIMARY KEY (`Lettre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4




CREATE TABLE `Equipes` (
 `NomEquipe` varchar(25) NOT NULL,
 `NomFichierDrapeau` varchar(25) NOT NULL,
 `RefGroupe` varchar(1) NOT NULL,
 `nbMatchJoue` int(5) NOT NULL,
 `nbMatchGagne` int(5) NOT NULL,
 `nbMatchNul` int(5) NOT NULL,
 `nbMatchPerdu` int(5) NOT NULL,
 `nbButMarque` int(5) NOT NULL,
 `nbButEnc` int(5) NOT NULL,
 `nbPoints` int(5) NOT NULL,
 PRIMARY KEY (`NomEquipe`),
 CONSTRAINT `FKEquipesGroupes` FOREIGN KEY (`RefGroupe`) REFERENCES `Groupes` (`Lettre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4




CREATE TABLE `Villes` (
                          `NomVille` varchar(25) NOT NULL,
                          PRIMARY KEY (`NomVille`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4




CREATE TABLE `GroupesVilles` (
                                 `RefGroupe` varchar(1) NOT NULL,
                                 `RefVille` varchar(25) NOT NULL,
                                 PRIMARY KEY (`RefGroupe`,`RefVille`),
                                 CONSTRAINT `FKGroupes` FOREIGN KEY (`RefGroupe`) REFERENCES `Groupes` (`Lettre`),
                                 CONSTRAINT `FKVilles` FOREIGN KEY (`RefVille`) REFERENCES `Villes` (`NomVille`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4




CREATE TABLE `Matchs` (
 `NumMatch` int(5) NOT NULL AUTO_INCREMENT,
 `RefEquipe1` varchar(25) NOT NULL,
 `RefEquipe2` varchar(25) NOT NULL,
 `ScoreEquipe1` int(2) DEFAULT NULL,
 `ScoreEquipe2` int(2) DEFAULT NULL,
 PRIMARY KEY (`NumMatch`),
 CONSTRAINT `FKMatchEquipe1` FOREIGN KEY (`RefEquipe1`) REFERENCES `Equipes` (`NomEquipe`),
 CONSTRAINT `FKMatchEquipe2` FOREIGN KEY (`RefEquipe2`) REFERENCES `Equipes` (`NomEquipe`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4











