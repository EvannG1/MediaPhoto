-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : mar. 10 nov. 2020 à 18:22
-- Version du serveur :  10.3.23-MariaDB-0+deb10u1
-- Version de PHP : 7.3.19-1~deb10u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `mediaphoto`
--

-- --------------------------------------------------------

--
-- Structure de la table `galerie`
--

CREATE TABLE `galerie` (
  `id` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `type` int(11) NOT NULL,
  `auteur` int(11) NOT NULL,
  `taille` float NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `galerie`
--

INSERT INTO `galerie` (`id`, `titre`, `description`, `date`, `type`, `auteur`, `taille`) VALUES
(1, 'Vacances', 'Mes superbes vacances à la mer', '2020-11-02 23:00:00', 1, 1, 2472),
(2, 'Montagne', 'Mes superbes vacances à la montagne', '2020-11-02 23:00:00', 1, 1, 0),
(52, 'Papillons', 'Papillons galerie', '2020-11-09 23:00:00', 3, 1, 0),
(53, 'test', 'test', '2020-11-09 23:00:00', 3, 1, 0);

-- --------------------------------------------------------

--
-- Structure de la table `galerie_partage`
--

CREATE TABLE `galerie_partage` (
  `id` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `id_galerie` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `galerie_partage`
--

INSERT INTO `galerie_partage` (`id`, `id_utilisateur`, `id_galerie`) VALUES
(13, 2, 52),
(14, 11, 52),
(15, 2, 53),
(16, 11, 53),
(17, 1, 53);

-- --------------------------------------------------------

--
-- Structure de la table `photo`
--

CREATE TABLE `photo` (
  `id` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `chemin` varchar(255) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `id_galerie` int(11) NOT NULL,
  `qualite` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `taille` float NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `photo`
--

INSERT INTO `photo` (`id`, `titre`, `date`, `chemin`, `id_utilisateur`, `id_galerie`, `qualite`, `type`, `taille`) VALUES
(1, 'Plage magnifique', '2020-11-02 23:00:00', '/html/images/1.jpg', 1, 1, 'HD', 'jpg', 10),
(2, 'Plage moche', '2020-11-02 23:00:00', '/html/images/4.jpg', 1, 1, 'FHD', 'jpg', 57),
(3, 'Montagne anonyme', '2020-11-04 23:00:00', '/html/images/3.jpg', 2, 2, 'Ultra HD', 'jpg', 47),
(4, 'Mont Fuji', '2020-11-01 23:00:00', '/html/images/4.jpg', 2, 2, 'HD', 'jpg', 17),
(5, 'Montagne pas si anonyme', '2020-11-04 23:00:00', '/html/images/5.jpg', 2, 2, 'Ultra HD', 'jpg', 47),
(6, 'Plage nuageuse', '2020-11-02 23:00:00', '/html/images/6.jpg', 1, 1, 'HD', 'jpg', 10),
(7, 'Montagne petite', '2020-11-04 23:00:00', '/html/images/7.jpeg', 2, 2, 'Ultra SD', 'jpeg', 47),
(8, 'Montagnes avec cailloux', '2020-11-04 23:00:00', '/html/images/12.jpg', 2, 2, 'Ultra SD', 'jpeg', 47);

-- --------------------------------------------------------

--
-- Structure de la table `tag`
--

CREATE TABLE `tag` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `tag`
--

INSERT INTO `tag` (`id`, `nom`) VALUES
(39, 'papillons'),
(40, 'insectes'),
(41, 'nature'),
(49, 'multifus'),
(48, 'logo'),
(45, 'test'),
(46, 'bonjour'),
(47, 'eliott');

-- --------------------------------------------------------

--
-- Structure de la table `tag_galerie`
--

CREATE TABLE `tag_galerie` (
  `id` int(11) NOT NULL,
  `id_tag` int(11) NOT NULL,
  `id_galerie` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `tag_galerie`
--

INSERT INTO `tag_galerie` (`id`, `id_tag`, `id_galerie`) VALUES
(52, 46, 53),
(51, 45, 53),
(50, 39, 53),
(49, 45, 52),
(48, 39, 52);

-- --------------------------------------------------------

--
-- Structure de la table `tag_photo`
--

CREATE TABLE `tag_photo` (
  `id` int(11) NOT NULL,
  `id_tag` int(11) NOT NULL,
  `id_photo` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `nom_complet` varchar(255) NOT NULL,
  `mdp` varchar(255) NOT NULL,
  `level` int(11) NOT NULL DEFAULT 100
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id`, `nom`, `nom_complet`, `mdp`, `level`) VALUES
(1, 'admin', 'John Doe', '$2y$10$y0F5Z.E7qGjhTQhkLAlwFOVaOu8J4VLGjSwh9V834GzGax.2K/yC6', 100),
(2, 'evann', 'Evann Gehin', 'azerty', 100),
(3, 'vivien', 'Vivien Klop', 'azerty', 100),
(11, 'guy', 'Guy ZAEGEL', '$2y$10$IvApCw2lrsnikHlbowFYbeNFLdDnYG1Dk1DXqqOF5SVC9REfyRaeK', 100);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `galerie`
--
ALTER TABLE `galerie`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `galerie_partage`
--
ALTER TABLE `galerie_partage`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `photo`
--
ALTER TABLE `photo`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tag`
--
ALTER TABLE `tag`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tag_galerie`
--
ALTER TABLE `tag_galerie`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tag_photo`
--
ALTER TABLE `tag_photo`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `galerie`
--
ALTER TABLE `galerie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT pour la table `galerie_partage`
--
ALTER TABLE `galerie_partage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT pour la table `photo`
--
ALTER TABLE `photo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `tag`
--
ALTER TABLE `tag`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT pour la table `tag_galerie`
--
ALTER TABLE `tag_galerie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT pour la table `tag_photo`
--
ALTER TABLE `tag_photo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
