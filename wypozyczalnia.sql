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
  `haslo` varchar(16) NOT NULL
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
  `okladka` longblob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `filmy`
--

INSERT INTO `filmy` (`id`, `tytul`, `gatunek`, `data_dodania`, `ilosc_wypozyczen`, `rezyser`, `rok_premiery`, `okladka`) VALUES
(1, 'Skazani na Shawshank', 'Dramat', '2025-01-17', 0, 'Frank Darabont', 1994, 0x443a2f656c6f2f78616d702f6d7973716c2f646174612f7779706f7a79637a616c6e69612f736861777368616e6b2e6a7067),
(2, 'Zielona mila', 'Dramat', '2025-01-17', 0, 'Frank Darabont', 1999, 0x443a2f656c6f2f78616d702f6d7973716c2f646174612f7779706f7a79637a616c6e69612f7a69656c6f6e615f6d696c612e6a7067),
(3, 'Forrest Gump', 'Komedia', '2025-01-17', 0, 'Robert Zemeckis', 1994, 0x443a2f656c6f2f78616d702f6d7973716c2f646174612f7779706f7a79637a616c6e69612f666f72726573745f67756d702e6a7067),
(4, 'Ojciec chrzestny', 'Kryminalny', '2025-01-17', 0, 'Francis Ford Coppola', 1972, 0x443a2f656c6f2f78616d702f6d7973716c2f646174612f7779706f7a79637a616c6e69612f6f6a636965635f6368727a6573746e792e6a7067),
(5, 'Mroczny rycerz', 'Akcja', '2025-01-17', 0, 'Christopher Nolan', 2008, 0x443a2f656c6f2f78616d702f6d7973716c2f646174612f7779706f7a79637a616c6e69612f6d726f637a6e795f72796365727a2e6a7067),
(6, 'Władca Pierścieni: Powrót Króla', 'Fantasy', '2025-01-17', 0, 'Peter Jackson', 2003, 0x443a2f656c6f2f78616d702f6d7973716c2f646174612f7779706f7a79637a616c6e69612f706f77726f745f6b726f6c612e6a7067),
(7, 'Incepcja', 'Science Fiction', '2025-01-17', 0, 'Christopher Nolan', 2010, 0x443a2f656c6f2f78616d702f6d7973716c2f646174612f7779706f7a79637a616c6e69612f696e636570636a612e6a7067),
(8, 'Matrix', 'Science Fiction', '2025-01-17', 0, 'Lana Wachowski, Lilly Wachowski', 1999, 0x443a2f656c6f2f78616d702f6d7973716c2f646174612f7779706f7a79637a616c6e69612f6d61747269782e6a7067),
(9, 'Gladiator', 'Historyczny', '2025-01-17', 0, 'Ridley Scott', 2000, 0x443a2f656c6f2f78616d702f6d7973716c2f646174612f7779706f7a79637a616c6e69612f676c61646961746f722e6a7067),
(10, 'Titanic', 'Dramat', '2025-01-17', 0, 'James Cameron', 1997, 0x443a2f656c6f2f78616d702f6d7973716c2f646174612f7779706f7a79637a616c6e69612f746974616e69632e6a7067);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `uzytkownicy`
--

CREATE TABLE `uzytkownicy` (
  `id` int(11) NOT NULL,
  `nazwa` varchar(30) NOT NULL,
  `email` varchar(100) NOT NULL,
  `haslo` varchar(16) NOT NULL
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
