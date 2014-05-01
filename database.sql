-- phpMyAdmin SQL Dump
-- version 4.1.8
-- http://www.phpmyadmin.net
--
-- Client :  localhost:8889
-- Généré le :  Jeu 01 Mai 2014 à 16:48
-- Version du serveur :  5.5.34
-- Version de PHP :  5.5.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de données :  `keepict`
--
CREATE DATABASE IF NOT EXISTS `keepict` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `keepict`;

-- --------------------------------------------------------

--
-- Structure de la table `album`
--

DROP TABLE IF EXISTS `album`;
CREATE TABLE `album` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `pathCoverThumbnail` varchar(500) DEFAULT NULL,
  `pathCover` varchar(500) DEFAULT NULL,
  `description` longtext,
  `slug` varchar(255) DEFAULT NULL,
  `visibility` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `visibility` (`visibility`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

--
-- Vider la table avant d'insérer `album`
--

TRUNCATE TABLE `album`;
--
-- Contenu de la table `album`
--

INSERT INTO `album` (`id`, `name`, `pathCoverThumbnail`, `pathCover`, `description`, `slug`, `visibility`) VALUES
(1, 'Flux', NULL, NULL, NULL, 'flux', 1),
(19, 'Album#11', 'images/albums/album-11/cover/thumb_cover.jpeg', 'images/albums/album-11/cover/cover.jpeg', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iste deleniti blanditiis fugit delectus accusantium quaerat sapiente impedit laudantium incidunt nisi! Commodi, veniam, quos placeat omnis vero itaque nobis qui ullam!', 'album-11', 1),
(20, 'Toronto', 'images/albums/toronto/cover/thumb_cover.JPG', 'images/albums/toronto/cover/cover.JPG', 'Aucune description', 'toronto', 2);

-- --------------------------------------------------------

--
-- Structure de la table `albumpicture`
--

DROP TABLE IF EXISTS `albumpicture`;
CREATE TABLE `albumpicture` (
  `album` tinyint(4) NOT NULL,
  `picture` tinyint(4) NOT NULL,
  KEY `album` (`album`,`picture`),
  KEY `photo` (`picture`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Vider la table avant d'insérer `albumpicture`
--

TRUNCATE TABLE `albumpicture`;
--
-- Contenu de la table `albumpicture`
--

INSERT INTO `albumpicture` (`album`, `picture`) VALUES
(1, 15),
(1, 16),
(1, 17),
(1, 18),
(1, 19),
(1, 20),
(1, 21),
(1, 22),
(1, 23),
(1, 24),
(19, 25),
(19, 26),
(19, 27),
(19, 28),
(19, 29),
(19, 30),
(19, 31),
(19, 32),
(19, 33),
(19, 34),
(20, 35),
(20, 36),
(20, 37),
(20, 38);

-- --------------------------------------------------------

--
-- Structure de la table `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE `category` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `wording` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=26 ;

--
-- Vider la table avant d'insérer `category`
--

TRUNCATE TABLE `category`;
--
-- Contenu de la table `category`
--

INSERT INTO `category` (`id`, `wording`) VALUES
(1, 'Sans catégorie'),
(2, 'Abstrait'),
(3, 'Animaux'),
(4, 'Art'),
(5, 'Célébrités'),
(6, 'Cinéma'),
(7, 'Concert'),
(8, 'Famille'),
(9, 'Journalisme'),
(10, 'Macro'),
(11, 'Mariage'),
(12, 'Mode'),
(13, 'Nature'),
(14, 'Noir & Blanc'),
(15, 'Nourriture'),
(16, 'Paysages'),
(17, 'Personnage'),
(18, 'Publicitaire'),
(19, 'Représentation artistique'),
(20, 'Sous l''eau'),
(21, 'Sports'),
(22, 'Street'),
(23, 'Transport'),
(24, 'Vie courante'),
(25, 'Villes & Architecture');

-- --------------------------------------------------------

--
-- Structure de la table `commentalbum`
--

DROP TABLE IF EXISTS `commentalbum`;
CREATE TABLE `commentalbum` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `album` tinyint(4) NOT NULL,
  `member` tinyint(4) NOT NULL,
  `body` longtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `membre` (`member`),
  KEY `album` (`album`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Vider la table avant d'insérer `commentalbum`
--

TRUNCATE TABLE `commentalbum`;
--
-- Contenu de la table `commentalbum`
--

INSERT INTO `commentalbum` (`id`, `album`, `member`, `body`) VALUES
(9, 2, 7, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Repellat, eum, cumque quisquam quae atque nostrum quis rerum eveniet optio ratione doloremque error dolore laborum beatae nesciunt nisi dolorum aut cum?'),
(10, 2, 8, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Repellat, eum, cumque quisquam quae atque nostrum quis rerum eveniet optio ratione doloremque error dolore laborum beatae nesciunt nisi dolorum aut cum?'),
(11, 2, 9, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Repellat, eum, cumque quisquam quae atque nostrum quis rerum eveniet optio ratione doloremque error dolore laborum beatae nesciunt nisi dolorum aut cum?');

-- --------------------------------------------------------

--
-- Structure de la table `commentpicture`
--

DROP TABLE IF EXISTS `commentpicture`;
CREATE TABLE `commentpicture` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `picture` tinyint(4) NOT NULL,
  `member` tinyint(4) NOT NULL,
  `body` longtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `picture` (`picture`,`member`),
  KEY `member` (`member`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Vider la table avant d'insérer `commentpicture`
--

TRUNCATE TABLE `commentpicture`;
-- --------------------------------------------------------

--
-- Structure de la table `picture`
--

DROP TABLE IF EXISTS `picture`;
CREATE TABLE `picture` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` longtext,
  `pathThumbnail` varchar(500) NOT NULL,
  `pathPicture` varchar(500) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `category` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `category` (`category`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=39 ;

--
-- Vider la table avant d'insérer `picture`
--

TRUNCATE TABLE `picture`;
--
-- Contenu de la table `picture`
--

INSERT INTO `picture` (`id`, `title`, `description`, `pathThumbnail`, `pathPicture`, `slug`, `category`) VALUES
(15, 'Picture#1', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iste deleniti blanditiis fugit delectus accusantium quaerat sapiente impedit laudantium incidunt nisi! Commodi, veniam, quos placeat omnis vero itaque nobis qui ullam!', 'images/albums/flux/thumb_picture_53610a51071a9.jpg', 'images/albums/flux/picture_53610a51071a9.jpg', 'picture-1', 6),
(16, 'Untitled', NULL, 'images/albums/flux/thumb_picture_53610a5107470.jpg', 'images/albums/flux/picture_53610a5107470.jpg', NULL, 1),
(17, 'Untitled', NULL, 'images/albums/flux/thumb_picture_53610a510764d.jpg', 'images/albums/flux/picture_53610a510764d.jpg', NULL, 1),
(18, 'Untitled', NULL, 'images/albums/flux/thumb_picture_53610a510783c.jpg', 'images/albums/flux/picture_53610a510783c.jpg', NULL, 1),
(19, 'Untitled', NULL, 'images/albums/flux/thumb_picture_53610a5107aa6.jpg', 'images/albums/flux/picture_53610a5107aa6.jpg', NULL, 1),
(20, 'Untitled', NULL, 'images/albums/flux/thumb_picture_53610a5107d3b.jpg', 'images/albums/flux/picture_53610a5107d3b.jpg', NULL, 1),
(21, 'Untitled', NULL, 'images/albums/flux/thumb_picture_53610a5107fec.jpg', 'images/albums/flux/picture_53610a5107fec.jpg', NULL, 1),
(22, 'Untitled', NULL, 'images/albums/flux/thumb_picture_53610a510839b.jpg', 'images/albums/flux/picture_53610a510839b.jpg', NULL, 1),
(23, 'Untitled', NULL, 'images/albums/flux/thumb_picture_53610a51085dd.jpg', 'images/albums/flux/picture_53610a51085dd.jpg', NULL, 1),
(24, 'Untitled', NULL, 'images/albums/flux/thumb_picture_53610a5108829.jpg', 'images/albums/flux/picture_53610a5108829.jpg', NULL, 1),
(25, 'Untitled', NULL, 'images/albums/album-11/pictures/thumb_picture_53610b0619058.jpg', 'images/albums/album-11/pictures/picture_53610b0619058.jpg', NULL, 1),
(26, 'Untitled', NULL, 'images/albums/album-11/pictures/thumb_picture_53610b06192ef.jpg', 'images/albums/album-11/pictures/picture_53610b06192ef.jpg', NULL, 1),
(27, 'Untitled', NULL, 'images/albums/album-11/pictures/thumb_picture_53610b06194fb.jpg', 'images/albums/album-11/pictures/picture_53610b06194fb.jpg', NULL, 1),
(28, 'Untitled', NULL, 'images/albums/album-11/pictures/thumb_picture_53610b061972e.jpg', 'images/albums/album-11/pictures/picture_53610b061972e.jpg', NULL, 1),
(29, 'Untitled', NULL, 'images/albums/album-11/pictures/thumb_picture_53610b0619993.jpg', 'images/albums/album-11/pictures/picture_53610b0619993.jpg', NULL, 1),
(30, 'Untitled', NULL, 'images/albums/album-11/pictures/thumb_picture_53610b0619c2b.jpg', 'images/albums/album-11/pictures/picture_53610b0619c2b.jpg', NULL, 1),
(31, 'Untitled', NULL, 'images/albums/album-11/pictures/thumb_picture_53610b0619e81.jpg', 'images/albums/album-11/pictures/picture_53610b0619e81.jpg', NULL, 1),
(32, 'Untitled', NULL, 'images/albums/album-11/pictures/thumb_picture_53610b061a34b.jpg', 'images/albums/album-11/pictures/picture_53610b061a34b.jpg', NULL, 1),
(33, 'Untitled', NULL, 'images/albums/album-11/pictures/thumb_picture_53610b061a5e1.jpg', 'images/albums/album-11/pictures/picture_53610b061a5e1.jpg', NULL, 1),
(34, 'Untitled', NULL, 'images/albums/album-11/pictures/thumb_picture_53610b061a837.jpg', 'images/albums/album-11/pictures/picture_53610b061a837.jpg', NULL, 1),
(35, 'CN Tower', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iste deleniti blanditiis fugit delectus accusantium quaerat sapiente impedit laudantium incidunt nisi! Commodi, veniam, quos placeat omnis vero itaque nobis qui ullam!', 'images/albums/toronto/pictures/thumb_picture_53610dd6f25c0.jpg', 'images/albums/toronto/pictures/picture_53610dd6f25c0.jpg', 'cn-tower', 25),
(36, 'Untitled', NULL, 'images/albums/toronto/pictures/thumb_picture_53610dd7014bc.jpg', 'images/albums/toronto/pictures/picture_53610dd7014bc.jpg', NULL, 1),
(37, 'Untitled', NULL, 'images/albums/toronto/pictures/thumb_picture_53610dd702c29.jpg', 'images/albums/toronto/pictures/picture_53610dd702c29.jpg', NULL, 1),
(38, 'Untitled', NULL, 'images/albums/toronto/pictures/thumb_picture_5361157ea8305.jpg', 'images/albums/toronto/pictures/picture_5361157ea8305.jpg', NULL, 1);

-- --------------------------------------------------------

--
-- Structure de la table `state`
--

DROP TABLE IF EXISTS `state`;
CREATE TABLE `state` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `wording` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Vider la table avant d'insérer `state`
--

TRUNCATE TABLE `state`;
--
-- Contenu de la table `state`
--

INSERT INTO `state` (`id`, `wording`) VALUES
(1, 'enabled'),
(2, 'pending'),
(3, 'disabled');

-- --------------------------------------------------------

--
-- Structure de la table `tag`
--

DROP TABLE IF EXISTS `tag`;
CREATE TABLE `tag` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `wording` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Vider la table avant d'insérer `tag`
--

TRUNCATE TABLE `tag`;
--
-- Contenu de la table `tag`
--

INSERT INTO `tag` (`id`, `wording`) VALUES
(1, 'Tag#1'),
(2, 'Tag#2'),
(3, 'Tag#3'),
(4, 'Tag#4'),
(5, 'Tag#5'),
(6, 'Tag#6'),
(7, 'Tag#7'),
(8, 'Tag#8'),
(9, 'Tag#9'),
(10, 'Tag#10'),
(11, 'Tour'),
(12, 'Ciel');

-- --------------------------------------------------------

--
-- Structure de la table `tagpicture`
--

DROP TABLE IF EXISTS `tagpicture`;
CREATE TABLE `tagpicture` (
  `tag` tinyint(4) NOT NULL,
  `picture` tinyint(4) NOT NULL,
  KEY `tag` (`tag`,`picture`),
  KEY `picture` (`picture`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Vider la table avant d'insérer `tagpicture`
--

TRUNCATE TABLE `tagpicture`;
--
-- Contenu de la table `tagpicture`
--

INSERT INTO `tagpicture` (`tag`, `picture`) VALUES
(1, 15),
(11, 35),
(12, 35);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `avatar` varchar(500) DEFAULT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `courriel` varchar(255) NOT NULL,
  `birth` date NOT NULL,
  `password` varchar(500) NOT NULL,
  `isAdmin` tinyint(1) NOT NULL,
  `state` tinyint(4) NOT NULL,
  `dateRequest` date DEFAULT NULL,
  `dateAdded` date DEFAULT NULL,
  `dateBlocked` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `courriel` (`courriel`),
  KEY `state` (`state`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- Vider la table avant d'insérer `user`
--

TRUNCATE TABLE `user`;
--
-- Contenu de la table `user`
--

INSERT INTO `user` (`id`, `avatar`, `firstname`, `lastname`, `courriel`, `birth`, `password`, `isAdmin`, `state`, `dateRequest`, `dateAdded`, `dateBlocked`) VALUES
(1, 'beardfont-fedora2', 'Florentin', 'TH', 'florentinth@florentinth.fr', '1992-06-23', '37fa265330ad83eaa879efb1e2db6380896cf639', 1, 1, NULL, '2014-03-15', NULL),
(4, 'beardfont-buckled3', 'John', 'Doe', 'john.doe@uqac.ca', '1987-08-23', '37fa265330ad83eaa879efb1e2db6380896cf639', 0, 2, '2014-03-22', NULL, NULL),
(5, 'beardfont-circle32', 'Jane', 'Doe', 'jane.doe@uqac.ca', '1994-03-09', '37fa265330ad83eaa879efb1e2db6380896cf639', 0, 2, '2014-03-22', NULL, NULL),
(6, 'images/avatars/user_6/avatar.jpeg', 'Sheldon', 'Cooper', 'knock.knock.knock@caltech.edu', '1981-07-24', '37fa265330ad83eaa879efb1e2db6380896cf639', 0, 1, '2014-03-21', '2014-03-21', NULL),
(7, 'images/avatars/user_7/avatar.jpeg', 'Leonard', 'Hofstadter', 'leonard.hofstadter@caltech.edu', '1987-04-16', '37fa265330ad83eaa879efb1e2db6380896cf639', 0, 1, '2014-03-21', '2014-03-21', NULL),
(8, 'images/avatars/user_8/avatar.jpeg', 'Penny', 'Lavoisine', 'penny@gmail.com', '1992-09-12', '37fa265330ad83eaa879efb1e2db6380896cf639', 0, 1, '2014-03-21', '2014-03-21', NULL),
(9, 'images/avatars/user_9/avatar.jpeg', 'Howard', 'Wolowitz', 'howard.wolowitz@nasa.gov', '1987-01-05', '37fa265330ad83eaa879efb1e2db6380896cf639', 0, 1, '2014-03-21', '2014-03-21', NULL),
(10, 'beardfont-round38', 'Stuart', 'Bloom', 'stuart.bloom@marvel.com', '1974-10-11', '37fa265330ad83eaa879efb1e2db6380896cf639', 0, 3, '2014-03-21', NULL, '2014-03-21'),
(11, 'beardfont-cowboy4', 'Barry', 'Kripke', 'barry.kripke@caltech.edu', '1983-12-20', '37fa265330ad83eaa879efb1e2db6380896cf639', 0, 3, '2014-03-21', NULL, '2014-03-21'),
(12, 'images/avatars/user_12/avatar.jpeg', 'Rajesh', 'Koothrappali', 'rajesh.koothrappali@caltech.edu', '1988-10-06', '37fa265330ad83eaa879efb1e2db6380896cf639', 0, 1, '2014-03-24', '2014-03-24', NULL),
(14, 'beardfont-tall5', 'juda', 'bricot', 'florentin.thullier1@uqac.ca', '2014-04-02', '37fa265330ad83eaa879efb1e2db6380896cf639', 0, 1, '2014-04-30', '2014-04-30', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `visibility`
--

DROP TABLE IF EXISTS `visibility`;
CREATE TABLE `visibility` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `wording` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Vider la table avant d'insérer `visibility`
--

TRUNCATE TABLE `visibility`;
--
-- Contenu de la table `visibility`
--

INSERT INTO `visibility` (`id`, `wording`) VALUES
(1, 'published'),
(2, 'unpublished');

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `album`
--
ALTER TABLE `album`
  ADD CONSTRAINT `album_ibfk_1` FOREIGN KEY (`visibility`) REFERENCES `visibility` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `albumpicture`
--
ALTER TABLE `albumpicture`
  ADD CONSTRAINT `albumpicture_ibfk_2` FOREIGN KEY (`picture`) REFERENCES `picture` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `albumpicture_ibfk_3` FOREIGN KEY (`album`) REFERENCES `album` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `commentpicture`
--
ALTER TABLE `commentpicture`
  ADD CONSTRAINT `commentpicture_ibfk_1` FOREIGN KEY (`picture`) REFERENCES `picture` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `commentpicture_ibfk_2` FOREIGN KEY (`member`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `picture`
--
ALTER TABLE `picture`
  ADD CONSTRAINT `picture_ibfk_1` FOREIGN KEY (`category`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `tagpicture`
--
ALTER TABLE `tagpicture`
  ADD CONSTRAINT `tagpicture_ibfk_1` FOREIGN KEY (`tag`) REFERENCES `tag` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tagpicture_ibfk_2` FOREIGN KEY (`picture`) REFERENCES `picture` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`state`) REFERENCES `state` (`id`);
