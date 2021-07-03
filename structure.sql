-- phpMyAdmin SQL Dump
-- version 4.6.6deb4+deb9u2
-- https://www.phpmyadmin.net/
--
-- Client :  localhost
-- Généré le :  Sam 03 Juillet 2021 à 23:06
-- Version du serveur :  10.1.48-MariaDB-0+deb9u2
-- Version de PHP :  7.0.33-0+deb9u10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `gesteam-testeam`
--

-- --------------------------------------------------------

--
-- Structure de la table `appreciation`
--

CREATE TABLE `appreciation` (
  `id` int(11) NOT NULL,
  `id_course` int(11) NOT NULL,
  `date` date NOT NULL,
  `evaluator_student` char(11) CHARACTER SET utf8mb4 NOT NULL,
  `evaluated_student` char(11) CHARACTER SET utf8mb4 NOT NULL,
  `id_group` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `card`
--

CREATE TABLE `card` (
  `id` int(11) NOT NULL,
  `id_course` int(11) NOT NULL,
  `date` date NOT NULL,
  `username` char(11) CHARACTER SET utf8mb4 NOT NULL,
  `color` varchar(6) CHARACTER SET utf8mb4 NOT NULL,
  `deactivation_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `course_group`
--

CREATE TABLE `course_group` (
  `id` int(11) NOT NULL,
  `id_course` int(11) NOT NULL,
  `username` char(11) CHARACTER SET utf8mb4 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `course_parameters`
--

CREATE TABLE `course_parameters` (
  `id_course` int(11) NOT NULL,
  `course` varchar(64) CHARACTER SET utf8mb4 NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `threshold_red_card` int(11) NOT NULL,
  `period` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `criteria`
--

CREATE TABLE `criteria` (
  `id_appreciation` int(11) NOT NULL,
  `id_criteria` int(11) NOT NULL,
  `value` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `group_name`
--

CREATE TABLE `group_name` (
  `id_group` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `teacher_s_course`
--

CREATE TABLE `teacher_s_course` (
  `id_course` int(11) NOT NULL,
  `username` char(11) CHARACTER SET utf8mb4 NOT NULL,
  `owner` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `username` char(11) CHARACTER SET utf8mb4 NOT NULL,
  `firstname` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `lastname` varchar(255) CHARACTER SET utf8mb4 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Index pour les tables exportées
--

--
-- Index pour la table `appreciation`
--
ALTER TABLE `appreciation`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ak_conditions_appreciation` (`id_course`,`date`,`evaluator_student`,`evaluated_student`,`id_group`),
  ADD KEY `appreciation_ibfk_4` (`id_group`),
  ADD KEY `appreciation_ibfk_2` (`evaluator_student`),
  ADD KEY `appreciation_ibfk_3` (`evaluated_student`);

--
-- Index pour la table `card`
--
ALTER TABLE `card`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_course` (`id_course`),
  ADD KEY `card_ibfk_2` (`username`);

--
-- Index pour la table `course_group`
--
ALTER TABLE `course_group`
  ADD PRIMARY KEY (`id_course`,`username`),
  ADD KEY `course_group_ibfk_2` (`username`);

--
-- Index pour la table `course_parameters`
--
ALTER TABLE `course_parameters`
  ADD PRIMARY KEY (`id_course`);

--
-- Index pour la table `criteria`
--
ALTER TABLE `criteria`
  ADD PRIMARY KEY (`id_appreciation`,`id_criteria`);

--
-- Index pour la table `group_name`
--
ALTER TABLE `group_name`
  ADD PRIMARY KEY (`id_group`);

--
-- Index pour la table `teacher_s_course`
--
ALTER TABLE `teacher_s_course`
  ADD PRIMARY KEY (`id_course`,`username`),
  ADD KEY `teacher_s_course_ibfk_1` (`username`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`username`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `appreciation`
--
ALTER TABLE `appreciation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT pour la table `card`
--
ALTER TABLE `card`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT pour la table `course_parameters`
--
ALTER TABLE `course_parameters`
  MODIFY `id_course` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `appreciation`
--
ALTER TABLE `appreciation`
  ADD CONSTRAINT `appreciation_ibfk_1` FOREIGN KEY (`id_course`) REFERENCES `course_parameters` (`id_course`),
  ADD CONSTRAINT `appreciation_ibfk_2` FOREIGN KEY (`evaluator_student`) REFERENCES `user` (`username`),
  ADD CONSTRAINT `appreciation_ibfk_3` FOREIGN KEY (`evaluated_student`) REFERENCES `user` (`username`),
  ADD CONSTRAINT `appreciation_ibfk_4` FOREIGN KEY (`id_group`) REFERENCES `group_name` (`id_group`);

--
-- Contraintes pour la table `card`
--
ALTER TABLE `card`
  ADD CONSTRAINT `card_ibfk_1` FOREIGN KEY (`id_course`) REFERENCES `course_parameters` (`id_course`),
  ADD CONSTRAINT `card_ibfk_2` FOREIGN KEY (`username`) REFERENCES `user` (`username`);

--
-- Contraintes pour la table `course_group`
--
ALTER TABLE `course_group`
  ADD CONSTRAINT `course_group_ibfk_1` FOREIGN KEY (`id_course`) REFERENCES `course_parameters` (`id_course`),
  ADD CONSTRAINT `course_group_ibfk_2` FOREIGN KEY (`username`) REFERENCES `user` (`username`);

--
-- Contraintes pour la table `criteria`
--
ALTER TABLE `criteria`
  ADD CONSTRAINT `criteria_ibfk_1` FOREIGN KEY (`id_appreciation`) REFERENCES `appreciation` (`id`);

--
-- Contraintes pour la table `teacher_s_course`
--
ALTER TABLE `teacher_s_course`
  ADD CONSTRAINT `teacher_s_course_ibfk_1` FOREIGN KEY (`username`) REFERENCES `user` (`username`),
  ADD CONSTRAINT `teacher_s_course_ibfk_2` FOREIGN KEY (`id_course`) REFERENCES `course_parameters` (`id_course`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
