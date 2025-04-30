-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 30, 2025 at 07:37 PM
-- Wersja serwera: 10.11.11-MariaDB-cll-lve
-- Wersja PHP: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `srv82359_wyporzyczalnia`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `filmy`
--

CREATE TABLE `filmy` (
  `id` int(11) NOT NULL,
  `tytul` varchar(100) NOT NULL,
  `gatunek` varchar(50) NOT NULL,
  `data_dodania` date NOT NULL DEFAULT curdate(),
  `rezyser` varchar(100) DEFAULT NULL,
  `rok_premiery` int(11) NOT NULL,
  `okladka` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Zrzut danych tabeli `filmy`
--

INSERT INTO `filmy` (`id`, `tytul`, `gatunek`, `data_dodania`, `rezyser`, `rok_premiery`, `okladka`) VALUES
(2, 'Zielona mila', 'Dramat', '2025-01-17', 'Frank Darabont', 1999, 'https://srv82359.seohost.com.pl/img/okladka2.jpg'),
(3, 'Forrest Gump', 'Komedia', '2025-01-17', 'Robert Zemeckis', 1994, 'https://srv82359.seohost.com.pl/img/okladka3.jpg'),
(4, 'Ojciec chrzestny', 'Kryminalny', '2025-01-17', 'Francis Ford Coppola', 1972, 'https://srv82359.seohost.com.pl/img/okladka4.jpg'),
(5, 'Mroczny rycerz', 'Akcja', '2025-01-17', 'Christopher Nolan', 2008, 'https://srv82359.seohost.com.pl/img/okladka5.jpg'),
(6, 'Władca Pierścieni: Powrót Króla', 'Fantasy', '2025-01-17', 'Peter Jackson', 2003, 'https://srv82359.seohost.com.pl/img/okladka6.jpg'),
(7, 'Incepcja', 'Science Fiction', '2025-01-17', 'Christopher Nolan', 2010, 'https://srv82359.seohost.com.pl/img/okladka7.jpg'),
(8, 'Matrix', 'Science Fiction', '2025-01-17', 'Lana Wachowski, Lilly Wachowski', 1999, 'https://srv82359.seohost.com.pl/img/okladka8.jpg'),
(9, 'Gladiator', 'Historyczny', '2025-01-17', 'Ridley Scott', 2000, 'https://srv82359.seohost.com.pl/img/okladka9.jpg'),
(10, 'Titanic', 'Dramat', '2025-01-17', 'James Cameron', 1997, 'https://srv82359.seohost.com.pl/img/okladka10.jpg'),
(14, 'Siedem', 'Kryminał', '2025-03-17', 'David Fincher', 1995, 'http://dvdrental.online/img/okladka12.jpg'),
(15, 'Władca Pierścieni: Drużyna Pierścienia', 'Fantasy', '2025-03-17', 'Peter Jackson', 2001, 'http://dvdrental.online/img/okladka13.jpg'),
(16, 'Truman Show', 'Dramat', '2025-03-17', 'Peter Weir', 1998, 'http://dvdrental.online/img/okladka14.jpg'),
(17, 'Król Lew', 'Animacja', '2025-03-17', 'Roger Allers, Rob Minkoff', 1994, 'http://dvdrental.online/img/okladka15.jpg'),
(18, 'Interstellar', 'Science Fiction', '2025-03-17', 'Christopher Nolan', 2014, 'http://dvdrental.online/img/okladka16.jpg'),
(19, 'Wilk z Wall Street', 'Dramat', '2025-03-17', 'Martin Scorsese', 2013, 'http://dvdrental.online/img/okladka17.jpg'),
(20, 'Władca Pierścieni: Dwie wieże', 'Fantasy', '2025-03-17', 'Peter Jackson', 2002, 'http://dvdrental.online/img/okladka18.jpg'),
(21, 'Podziemny krąg', 'Dramat', '2025-03-17', 'David Fincher', 1999, 'http://dvdrental.online/img/okladka19.jpg');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `uzytkownicy`
--

CREATE TABLE `uzytkownicy` (
  `id` int(11) NOT NULL,
  `nazwa` varchar(30) NOT NULL,
  `email` varchar(100) NOT NULL,
  `new_email` varchar(255) DEFAULT NULL,
  `haslo` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `confirmed` int(1) NOT NULL,
  `ban` int(1) NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Zrzut danych tabeli `uzytkownicy`
--

INSERT INTO `uzytkownicy` (`id`, `nazwa`, `email`, `new_email`, `haslo`, `token`, `confirmed`, `ban`, `admin`) VALUES
(10, 'EmciaToChinka', 'pawel.kornek@icloud.com', NULL, '$2y$10$LNnlNkmXJcEJl4fB2jait.4bpSJvoyxAz5MJafrbrCrmta0QjQYFK', '', 1, 0, 0),
(11, 'WKrzysztof', 'kwolinski@elektryk.opole.pl', NULL, '$2y$10$B8zpqf/crvDj2asxmJANuOOFk5L5i/6U6kGNPd9YgOYLKTwyxh/52', 'bf0171d1bf5b642b130f90be90921c8facbdec163383e679758ab7e8e54dacb9', 1, 0, 1),
(12, 'xdlol', 'fabianlenart00@gmail.com', NULL, '$2y$10$vL4MF8bfleHaJdeEndYa8.G/7kVz0LAowF5S.ZKhVbsDPxg3FLOS.', 'e233414dbdc07d15805730c116b725dd3718cb3bad932a5d78b9bc3937c232e7', 1, 0, 1),
(14, 'niuuuuu', 'szymon.orc@gmail.com', NULL, '$2y$10$rBvl.gmtvtPzLVO0KNPXXuThnAoR8E8HrXB2pXe7pm5QRmppo7vD6', '2aaaf665b50829e9cc50705500263cf4c4fe60f8859a006c20bcd627d6e45d01f9738b0f809a226e427cec658da2a1a27f6f', 1, 0, 1),
(16, 'tester', 'dominik.drzymota@gmail.com', NULL, '$2y$10$HOuvYZhwABmfL8RmsUdNAeG.ChWzw3XmJcwVYXHBeklpveUW8ahG.', '0f222bbc59aa1e91793a568f80e3b53a06237f85883417059b56ae7ddd21dd06f04e478c9f4c6f1042dbb0f89828c0b191ce', 1, 0, 0),
(19, 'AdminPawel', 'beatzuuu@gmail.com', NULL, '$2y$10$3V7XqN1OZ9u.B9yhJx/4TO5Yawr7yyvRS.K7PMxUjeFOsvVrLzixm', '', 1, 0, 1),
(20, 'xdlol2', 'fabian72lenart@gmail.com', NULL, '$2y$10$bLurliBJ/biwBKTNNNOBjucuSEAlgl9hSPQmROgWdsXiRjWJbQH8C', '', 1, 0, 0),
(21, 'uyuyftuufyftu', 'xd@xd.pl', NULL, '$2y$10$Yk0bhgTwnrzdbKprUH1MZ.Q1vopNgZZJ.biuUBSg1FfW7XEI9clV6', '6e7a894d7a77e0b83241ce6e32706e2e75c5f3a317cda45390c61630d0b0b3f1', 0, 0, 0);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `wypozyczenia`
--

CREATE TABLE `wypozyczenia` (
  `id` int(11) NOT NULL,
  `uzytkownik_id` int(11) DEFAULT NULL,
  `film_id` int(11) NOT NULL,
  `data_wypozyczenia` date NOT NULL DEFAULT curdate(),
  `data_zwrotu` date DEFAULT NULL,
  `zwrot` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Zrzut danych tabeli `wypozyczenia`
--

INSERT INTO `wypozyczenia` (`id`, `uzytkownik_id`, `film_id`, `data_wypozyczenia`, `data_zwrotu`, `zwrot`) VALUES
(12, 11, 3, '2025-03-31', '2025-04-13', 1),
(13, 11, 5, '2025-03-31', '2025-04-13', 0),
(14, 11, 4, '2025-03-31', '2025-04-13', 0),
(16, 12, 4, '2025-03-31', '2025-04-13', 0),
(17, 12, 3, '2025-03-31', '2025-04-13', 0),
(19, 14, 4, '2025-04-03', '2025-04-06', 0),
(20, 14, 3, '2025-04-03', '2025-04-13', 1),
(25, NULL, 2, '2025-04-03', '2025-04-13', 1),
(26, NULL, 5, '2025-04-03', '2025-04-13', 1),
(27, NULL, 4, '2025-04-03', '2025-04-13', 1),
(28, 12, 5, '2025-03-03', '2025-03-11', 0),
(29, 12, 8, '2025-03-05', '2025-03-15', 0),
(30, 12, 17, '2025-04-05', '2025-04-15', 1),
(31, 12, 15, '2025-04-05', '2025-04-15', 0),
(32, 16, 17, '2025-04-09', '2025-04-19', 0),
(33, 12, 18, '2025-04-12', '2025-04-22', 0),
(34, 12, 16, '2025-04-27', '2025-05-07', 0);

--
-- Indeksy dla zrzutów tabel
--

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
-- AUTO_INCREMENT dla zrzuconych tabel
--

--
-- AUTO_INCREMENT dla tabeli `filmy`
--
ALTER TABLE `filmy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT dla tabeli `uzytkownicy`
--
ALTER TABLE `uzytkownicy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT dla tabeli `wypozyczenia`
--
ALTER TABLE `wypozyczenia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `wypozyczenia`
--
ALTER TABLE `wypozyczenia`
  ADD CONSTRAINT `wypozyczenia_ibfk_1` FOREIGN KEY (`uzytkownik_id`) REFERENCES `uzytkownicy` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
