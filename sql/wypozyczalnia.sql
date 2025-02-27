-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sty 17, 2025 at 06:45 PM
-- Wersja serwera: 10.4.32-MariaDB
-- Wersja PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wypozyczalnia`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `administratorzy`
--

CREATE TABLE `administratorzy` (
  `id` int(11) NOT NULL,
  `login` varchar(30) NOT NULL,
  `haslo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `filmy`
--

CREATE TABLE `filmy` (
  `id` int(11) NOT NULL,
  `tytul` varchar(100) NOT NULL,
  `gatunek` varchar(50) NOT NULL,
  `data_dodania` date NOT NULL DEFAULT curdate(),
  `ilosc_wypozyczen` int(11) NOT NULL DEFAULT 0,
  `rezyser` varchar(100) DEFAULT NULL,
  `rok_premiery` int(11) NOT NULL,
  `okladka` VARCHAR(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `filmy`
--

INSERT INTO `filmy` (`id`, `tytul`, `gatunek`, `data_dodania`, `ilosc_wypozyczen`, `rezyser`, `rok_premiery`, `okladka`) VALUES
(1, 'Skazani na Shawshank', 'Dramat', '2025-01-17', 0, 'Frank Darabont', 1994, 'https://srv82359.seohost.com.pl/img/okladka1.jpg'),
(2, 'Zielona mila', 'Dramat', '2025-01-17', 0, 'Frank Darabont', 1999, 'https://srv82359.seohost.com.pl/img/okladka2.jpg'),
(3, 'Forrest Gump', 'Komedia', '2025-01-17', 0, 'Robert Zemeckis', 1994, 'https://srv82359.seohost.com.pl/img/okladka3.jpg'),
(4, 'Ojciec chrzestny', 'Kryminalny', '2025-01-17', 0, 'Francis Ford Coppola', 1972, 'https://srv82359.seohost.com.pl/img/okladka4.jpg'),
(5, 'Mroczny rycerz', 'Akcja', '2025-01-17', 0, 'Christopher Nolan', 2008, 'https://srv82359.seohost.com.pl/img/okladka5.jpg'),
(6, 'Władca Pierścieni: Powrót Króla', 'Fantasy', '2025-01-17', 0, 'Peter Jackson', 2003, 'https://srv82359.seohost.com.pl/img/okladka6.jpg'),
(7, 'Incepcja', 'Science Fiction', '2025-01-17', 0, 'Christopher Nolan', 2010, 'https://srv82359.seohost.com.pl/img/okladka7.jpg'),
(8, 'Matrix', 'Science Fiction', '2025-01-17', 0, 'Lana Wachowski, Lilly Wachowski', 1999, 'https://srv82359.seohost.com.pl/img/okladka8.jpg'),
(9, 'Gladiator', 'Historyczny', '2025-01-17', 0, 'Ridley Scott', 2000, 'https://srv82359.seohost.com.pl/img/okladka9.jpg'),
(10, 'Titanic', 'Dramat', '2025-01-17', 0, 'James Cameron', 1997, 'https://srv82359.seohost.com.pl/img/okladka10.jpg');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `uzytkownicy`
--

CREATE TABLE `uzytkownicy` (
  `id` int(11) NOT NULL,
  `nazwa` varchar(30) NOT NULL,
  `email` varchar(100) NOT NULL,
  `haslo` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `confirmed` int(1) NOT NULL,
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `wypozyczenia`
--

CREATE TABLE `wypozyczenia` (
  `id` int(11) NOT NULL,
  `uzytkownik_id` int(11) NOT NULL,
  `film_id` int(11) NOT NULL,
  `data_wypozyczenia` date NOT NULL DEFAULT curdate(),
  `data_zwrotu` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `administratorzy`
--
ALTER TABLE `administratorzy`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login` (`login`);

--
-- Indeksy dla tabeli `filmy`
--
ALTER TABLE `filmy`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `uzytkownicy`
--
ALTER TABLE `uzytkownicy`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `Nazwa` (`nazwa`),
  ADD UNIQUE KEY `Email` (`email`);

--
-- Indeksy dla tabeli `wypozyczenia`
--
ALTER TABLE `wypozyczenia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uzytkownik_id` (`uzytkownik_id`),
  ADD KEY `film_id` (`film_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `administratorzy`
--
ALTER TABLE `administratorzy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `filmy`
--
ALTER TABLE `filmy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `uzytkownicy`
--
ALTER TABLE `uzytkownicy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wypozyczenia`
--
ALTER TABLE `wypozyczenia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `wypozyczenia`
--
ALTER TABLE `wypozyczenia`
  ADD CONSTRAINT `wypozyczenia_ibfk_1` FOREIGN KEY (`uzytkownik_id`) REFERENCES `uzytkownicy` (`id`),
  ADD CONSTRAINT `wypozyczenia_ibfk_2` FOREIGN KEY (`film_id`) REFERENCES `filmy` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
