CREATE TABLE IF NOT EXISTS `client` (
  `idClient`  int(11)     NOT NULL AUTO_INCREMENT,
  `nom`       varchar(255) NOT NULL,
  `prenom`    varchar(255) NOT NULL,
  `pseudo`    varchar(32)  NOT NULL,
  `mdp`       varchar(255) NOT NULL,
  `mail`      varchar(255) DEFAULT NULL,
  `telephone` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`idClient`),
  UNIQUE KEY `uq_client_pseudo` (`pseudo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `users` (
  `codeUser` int(11)     NOT NULL AUTO_INCREMENT,
  `login`    varchar(15) NOT NULL,
  `mdp`      varchar(255) NOT NULL,
  `nom`      varchar(32) NOT NULL,
  `prenom`   varchar(32) NOT NULL,
  `role`     varchar(3)  NOT NULL DEFAULT 'usr',
  PRIMARY KEY (`codeUser`),
  UNIQUE KEY `uq_users_login` (`login`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
