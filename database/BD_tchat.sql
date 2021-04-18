-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : Dim 18 avr. 2021 à 20:52
-- Version du serveur :  10.4.17-MariaDB
-- Version de PHP : 7.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `t_chat`
--

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `content` text NOT NULL,
  `receiver` int(11) NOT NULL,
  `sender` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `sent` tinyint(1) NOT NULL,
  `seen` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `messages`
--

INSERT INTO `messages` (`id`, `content`, `receiver`, `sender`, `date`, `sent`, `seen`) VALUES
(1, 'test1', 2, 1, '2021-04-18 02:41:49', 1, 1),
(2, 'yesd', 1, 2, '2021-04-18 02:42:03', 1, 1),
(3, 'qdq', 1, 2, '2021-04-18 02:44:22', 1, 1),
(4, 'qsd', 2, 1, '2021-04-18 03:29:29', 0, 1),
(5, 'test', 2, 1, '2021-04-18 03:29:36', 0, 1),
(6, 'ez', 2, 1, '2021-04-18 03:33:29', 0, 1),
(7, 'sdf', 2, 3, '2021-04-18 04:12:19', 1, 1),
(8, 'azeaze', 1, 3, '2021-04-18 05:17:12', 1, 1),
(9, 'dqsd', 1, 3, '2021-04-18 10:08:33', 1, 1),
(10, 'salut', 3, 1, '2021-04-18 10:08:41', 1, 1),
(11, 'oooo', 3, 4, '2021-04-18 13:08:55', 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(30) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `connected` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`,`connected`) VALUES
(1, 'elorfi', 'ayouborfi@gmail.com', '123456789.', '2021-04-17 23:50:23', 1),
(2, 'ayoub2', 'ayouborfi2@gmail.com', '123456789', '2021-04-18 02:12:50', 1);
(3, 'orfi', 'ayouborfi@gmail.com', '123456789', '2021-04-18 03:20:23', 1);
(4, 'marocayoub', 'ayouborfi@gmail.com', '123456789', '2021-04-18 12:25:03', 1);
--
-- Index pour les tables déchargées
--

--
-- Index pour la table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_Receiver` (`receiver`),
  ADD KEY `FK_Sender` (`sender`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
