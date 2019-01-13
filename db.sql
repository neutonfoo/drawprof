-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 12, 2019 at 08:13 PM
-- Server version: 5.7.23
-- PHP Version: 7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `fun`
--

-- --------------------------------------------------------

--
-- Table structure for table `drawprof_drawings`
--

CREATE TABLE `drawprof_drawings` (
  `drawingId` int(11) NOT NULL,
  `profId` int(11) NOT NULL,
  `publishedDate` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `drawprof_drawings`
--

INSERT INTO `drawprof_drawings` (`drawingId`, `profId`, `publishedDate`) VALUES
(1, 1, 'Today');

-- --------------------------------------------------------

--
-- Table structure for table `drawprof_profs`
--

CREATE TABLE `drawprof_profs` (
  `profId` int(11) NOT NULL,
  `uniId` int(11) NOT NULL,
  `profName` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `drawprof_profs`
--

INSERT INTO `drawprof_profs` (`profId`, `uniId`, `profName`) VALUES
(1, 1, 'Andrew Goodney');

-- --------------------------------------------------------

--
-- Table structure for table `drawprof_unis`
--

CREATE TABLE `drawprof_unis` (
  `uniId` int(11) NOT NULL,
  `uniName` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `drawprof_unis`
--

INSERT INTO `drawprof_unis` (`uniId`, `uniName`) VALUES
(1, 'University of Southern California');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `drawprof_drawings`
--
ALTER TABLE `drawprof_drawings`
  ADD PRIMARY KEY (`drawingId`);

--
-- Indexes for table `drawprof_profs`
--
ALTER TABLE `drawprof_profs`
  ADD PRIMARY KEY (`profId`);

--
-- Indexes for table `drawprof_unis`
--
ALTER TABLE `drawprof_unis`
  ADD PRIMARY KEY (`uniId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `drawprof_drawings`
--
ALTER TABLE `drawprof_drawings`
  MODIFY `drawingId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `drawprof_profs`
--
ALTER TABLE `drawprof_profs`
  MODIFY `profId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `drawprof_unis`
--
ALTER TABLE `drawprof_unis`
  MODIFY `uniId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
