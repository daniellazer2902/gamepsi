-- Adminer 4.7.7 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

CREATE DATABASE `gamepsi` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `gamepsi`;

SET NAMES utf8mb4;

CREATE TABLE `avatar` (
  `id_user` int(11) NOT NULL,
  `type` varchar(10) NOT NULL,
  `lien` text NOT NULL,
  KEY `id_user` (`id_user`),
  CONSTRAINT `avatar_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `utilisateurs` (`id_user`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `avatar` (`id_user`, `type`, `lien`) VALUES
(1,	'shoul',	'1shoul_black.png'),
(1,	'color',	'2color_b.png'),
(1,	'smile',	'3smile_happy.png'),
(1,	'eye',	'4eye_dot.png'),
(1,	'acces',	'5acces_angry.png'),
(1,	'hat',	'6hat_marins.png'),
(2,	'shoul',	'1shoul_black.png'),
(2,	'color',	'2color_b.png'),
(2,	'smile',	'3smile_happy.png'),
(2,	'eye',	'4eye_dot.png'),
(2,	'acces',	'5acces_blank.png'),
(2,	'hat',	'6hat_marins.png'),
(3,	'shoul',	'1shoul_black.png'),
(3,	'color',	'2color_b.png'),
(3,	'smile',	'3smile_happy.png'),
(3,	'eye',	'4eye_dot.png'),
(3,	'acces',	'5acces_angry.png'),
(3,	'hat',	'6hat_yearcap.png'),
(4,	'shoul',	'1shoul_camo.png'),
(4,	'color',	'2color_w.png'),
(4,	'smile',	'3smile_happy.png'),
(4,	'eye',	'4eye_dot.png'),
(4,	'acces',	'5acces_blank.png'),
(4,	'hat',	'6hat_marins.png')
ON DUPLICATE KEY UPDATE `id_user` = VALUES(`id_user`), `type` = VALUES(`type`), `lien` = VALUES(`lien`);

CREATE TABLE `dictionnaire` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mots` varchar(15) DEFAULT NULL,
  `reward` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `dictionnaire` (`id`, `mots`, `reward`) VALUES
(1,	'ORDINATEUR',	5),
(2,	'ADSL',	2),
(3,	'ALGORITHME',	5),
(4,	'INTEL',	2),
(5,	'ANTIVIRUS',	4),
(6,	'WINDOWS',	3),
(7,	'PROCESSEUR',	5),
(8,	'AJAX',	2),
(9,	'INDEX',	2),
(10,	'AMD',	1),
(11,	'FIBRE',	2),
(12,	'PNG',	1),
(13,	'RESEAU',	3),
(14,	'JPEG',	2),
(15,	'LINUX',	2),
(16,	'CLAVIER',	3),
(17,	'LOGICIEL',	3),
(18,	'JAVASCRIPT',	4),
(19,	'GITHUB',	3),
(20,	'DELL',	2),
(21,	'TELEPHONE',	4),
(22,	'FABRICE',	10),
(23,	'csharp',	3),
(24,	'ram',	1)
ON DUPLICATE KEY UPDATE `id` = VALUES(`id`), `mots` = VALUES(`mots`), `reward` = VALUES(`reward`);

CREATE TABLE `objets` (
  `id_objet` int(11) NOT NULL AUTO_INCREMENT,
  `nom_objet` varchar(50) NOT NULL,
  `prix` int(11) DEFAULT NULL,
  `lien` text NOT NULL,
  `corps` text NOT NULL,
  PRIMARY KEY (`id_objet`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `objets` (`id_objet`, `nom_objet`, `prix`, `lien`, `corps`) VALUES
(1,	'shoul_black',	NULL,	'1shoul_black.png',	'shoul'),
(2,	'shoul_camo',	NULL,	'1shoul_camo.png',	'shoul'),
(3,	'color_b',	NULL,	'2color_b.png',	'color'),
(4,	'color_w',	NULL,	'2color_w.png',	'color'),
(5,	'color_y',	NULL,	'2color_y.png',	'color'),
(6,	'smile_happy',	NULL,	'3smile_happy.png',	'smile'),
(7,	'smile_poker',	NULL,	'3smile_poker.png',	'smile'),
(8,	'smile_sad',	50,	'3smile_sad.png',	'smile'),
(9,	'eye_dot',	NULL,	'4eye_dot.png',	'eye'),
(10,	'acces_angry',	NULL,	'5acces_angry.png',	'acces'),
(11,	'hat_marins',	NULL,	'6hat_marins.png',	'hat'),
(12,	'hat_yearcap',	NULL,	'6hat_yearcap.png',	'hat'),
(17,	'acces_blank',	NULL,	'5acces_blank.png',	'acces'),
(18,	'hat_blank',	NULL,	'6hat_blank.png',	'hat')
ON DUPLICATE KEY UPDATE `id_objet` = VALUES(`id_objet`), `nom_objet` = VALUES(`nom_objet`), `prix` = VALUES(`prix`), `lien` = VALUES(`lien`), `corps` = VALUES(`corps`);

CREATE TABLE `possession` (
  `id_user` int(11) NOT NULL,
  `id_objet` int(11) NOT NULL,
  KEY `id_user` (`id_user`),
  KEY `id_objet` (`id_objet`),
  CONSTRAINT `possession_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `utilisateurs` (`id_user`) ON DELETE CASCADE,
  CONSTRAINT `possession_ibfk_2` FOREIGN KEY (`id_objet`) REFERENCES `objets` (`id_objet`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `possession` (`id_user`, `id_objet`) VALUES
(1,	2),
(1,	4),
(1,	7),
(1,	9),
(1,	12),
(1,	1),
(1,	8),
(1,	3),
(1,	6),
(1,	5),
(1,	17),
(1,	18),
(1,	10),
(1,	11),
(2,	1),
(2,	3),
(2,	6),
(2,	9),
(2,	17),
(2,	18),
(2,	12),
(3,	1),
(3,	3),
(3,	6),
(3,	9),
(3,	12),
(3,	17),
(3,	18),
(3,	10),
(3,	11),
(4,	1),
(4,	5),
(4,	7),
(4,	9),
(4,	17),
(4,	18),
(4,	4),
(4,	11),
(4,	6),
(4,	2),
(4,	8),
(4,	10),
(2,	11)
ON DUPLICATE KEY UPDATE `id_user` = VALUES(`id_user`), `id_objet` = VALUES(`id_objet`);

CREATE TABLE `utilisateurs` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(20) NOT NULL,
  `password` varchar(200) NOT NULL,
  `email` varchar(50) NOT NULL,
  `solde` int(11) DEFAULT NULL,
  `best_dino` int(11) DEFAULT NULL,
  `best_reflex` int(11) DEFAULT NULL,
  `best_memory` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `utilisateurs` (`id_user`, `pseudo`, `password`, `email`, `solde`, `best_dino`, `best_reflex`, `best_memory`) VALUES
(1,	'daniel',	'$2y$10$yT0/HcrMxjFfVUtln/kLpexwGIO9YM5OiMAlnS.uDR2CNolFbYzFq',	'couscous@gmail.com',	0,	5,	NULL,	12),
(2,	'marine',	'$2y$10$yT0/HcrMxjFfVUtln/kLpexwGIO9YM5OiMAlnS.uDR2CNolFbYzFq',	'mari@gmail.cf',	10,	0,	NULL,	18),
(3,	'william',	'$2y$10$RGm06oUMEFVPqLp05Buc7OrfwHJb.9u2LISMfW/wUqaDdAU09SCj6',	'williammi@gmail.cf',	0,	0,	NULL,	17),
(4,	'couscous',	'$2y$10$yT0/HcrMxjFfVUtln/kLpexwGIO9YM5OiMAlnS.uDR2CNolFbYzFq',	'daniellazer2902MC2015@gmail.com',	250,	0,	NULL,	NULL)
ON DUPLICATE KEY UPDATE `id_user` = VALUES(`id_user`), `pseudo` = VALUES(`pseudo`), `password` = VALUES(`password`), `email` = VALUES(`email`), `solde` = VALUES(`solde`), `best_dino` = VALUES(`best_dino`), `best_reflex` = VALUES(`best_reflex`), `best_memory` = VALUES(`best_memory`);

-- 2021-10-04 20:38:35
