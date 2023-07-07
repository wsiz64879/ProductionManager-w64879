-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Lip 07, 2023 at 09:52 PM
-- Wersja serwera: 10.4.28-MariaDB
-- Wersja PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `w64879_projekt`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `stan_magazynowy`
--

CREATE TABLE `stan_magazynowy` (
  `id_stanu` int(11) NOT NULL,
  `nazwa` varchar(255) NOT NULL,
  `ilosc` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stan_magazynowy`
--

INSERT INTO `stan_magazynowy` (`id_stanu`, `nazwa`, `ilosc`) VALUES
(14, 'Łącznik GPZ', 20),
(15, 'Przewód niebieski', 200),
(16, 'Przewód brązowy', 300),
(17, 'Oprawka GU10', 300);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `stan_zlecenia`
--

CREATE TABLE `stan_zlecenia` (
  `Id` int(11) NOT NULL,
  `Id_zlecenia` int(11) NOT NULL,
  `ilosc` int(11) NOT NULL,
  `ostatnia_zmiana` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stan_zlecenia`
--

INSERT INTO `stan_zlecenia` (`Id`, `Id_zlecenia`, `ilosc`, `ostatnia_zmiana`) VALUES
(1, 10, 300, ''),
(2, 11, 200, ''),
(3, 12, 2600, 'Adrian'),
(4, 13, 1500, ''),
(5, 14, 19999, ''),
(6, 18, 1500, 'Adrian'),
(7, 19, 170, 'admin1'),
(8, 20, 700, 'Adrian'),
(9, 21, 400, 'Adrian'),
(10, 22, 100, 'Adrian'),
(11, 15, 500, 'Adrian'),
(12, 16, 100, 'Adrian'),
(13, 23, 100, 'admin1');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `uprawnienia`
--

CREATE TABLE `uprawnienia` (
  `Id_up` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `Typ_uprawnienia` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `uprawnienia`
--

INSERT INTO `uprawnienia` (`Id_up`, `user_id`, `Typ_uprawnienia`) VALUES
(1, 4, 'Administrator'),
(2, 5, 'Pracownik');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `block_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `block_time`) VALUES
(4, 'admin1', 'admin123', NULL),
(5, 'Adrian ', 'Adrian123', NULL);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `zlecenia`
--

CREATE TABLE `zlecenia` (
  `Id_zlecenia` int(11) NOT NULL,
  `nazwa_zlecenia` varchar(255) DEFAULT NULL,
  `ilosc` int(11) DEFAULT NULL,
  `zleceniodawca` varchar(255) DEFAULT NULL,
  `data_dodania_zlecenia` date DEFAULT NULL,
  `data_zrealizowania_zlecenia` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `zlecenia`
--

INSERT INTO `zlecenia` (`Id_zlecenia`, `nazwa_zlecenia`, `ilosc`, `zleceniodawca`, `data_dodania_zlecenia`, `data_zrealizowania_zlecenia`) VALUES
(12, 'Izostat', 2500, 'Ciarko', '2023-07-04', '2023-07-04'),
(17, 'GPZ', 1500, 'Ciarko', '2023-07-05', '2023-07-12'),
(19, 'Mora', 67, 'Ciarko', '2023-07-05', '2023-07-05'),
(20, 'Izostat', 500, 'Asmot', '2023-07-05', '2023-07-05'),
(21, 'GPZ', 300, 'Ciarko', '2023-07-05', '2023-07-05'),
(23, 'SL-S 3B 709', 150, 'Stomil', '2023-07-07', '2023-07-05'),
(24, '123', 123, '123', '2023-07-07', '2023-07-07');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `stan_magazynowy`
--
ALTER TABLE `stan_magazynowy`
  ADD PRIMARY KEY (`id_stanu`);

--
-- Indeksy dla tabeli `stan_zlecenia`
--
ALTER TABLE `stan_zlecenia`
  ADD PRIMARY KEY (`Id`);

--
-- Indeksy dla tabeli `uprawnienia`
--
ALTER TABLE `uprawnienia`
  ADD PRIMARY KEY (`Id_up`);

--
-- Indeksy dla tabeli `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indeksy dla tabeli `zlecenia`
--
ALTER TABLE `zlecenia`
  ADD PRIMARY KEY (`Id_zlecenia`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `stan_magazynowy`
--
ALTER TABLE `stan_magazynowy`
  MODIFY `id_stanu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `stan_zlecenia`
--
ALTER TABLE `stan_zlecenia`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `uprawnienia`
--
ALTER TABLE `uprawnienia`
  MODIFY `Id_up` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `zlecenia`
--
ALTER TABLE `zlecenia`
  MODIFY `Id_zlecenia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
