-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 04, 2019 at 09:57 AM
-- Server version: 10.4.6-MariaDB
-- PHP Version: 7.3.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `izi`
--

-- --------------------------------------------------------

--
-- Table structure for table `attachments`
--

CREATE TABLE `attachments` (
  `Id` int(11) NOT NULL,
  `TicketId` int(11) DEFAULT NULL,
  `TicketRuleId` int(11) NOT NULL,
  `DisplayName` varchar(255) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `CustomerId` int(11) NOT NULL,
  `BusinessId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `attachments`
--

INSERT INTO `attachments` (`Id`, `TicketId`, `TicketRuleId`, `DisplayName`, `Name`, `CustomerId`, `BusinessId`) VALUES
(1, 8, 1, 'izi_EERD.pdf', 'izi_EERD.pdf', 1, 1),
(2, 8, 1, 'sample.pdf', 'sample.pdf', 1, 1),
(3, 9, 2, 'sample.pdf', 'sample.pdf', 1, 1),
(4, 11, 4, 'sample.pdf', 'sample.pdf', 1, 1),
(5, 11, 4, 'izi_EERD.pdf', 'izi_EERD.pdf', 1, 1),
(6, 12, 5, 'This is a test for Ticket', 'sample.pdf', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `business`
--

CREATE TABLE `business` (
  `Id` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `StreetName` varchar(255) NOT NULL,
  `StreetNumber` int(11) NOT NULL,
  `StreetAddition` varchar(255) NOT NULL,
  `ZipCode` varchar(255) NOT NULL,
  `City` varchar(255) NOT NULL,
  `Country` varchar(255) NOT NULL,
  `IBAN` varchar(255) NOT NULL,
  `GREK` varchar(255) NOT NULL,
  `KVK` varchar(255) NOT NULL,
  `BIC` varchar(255) NOT NULL,
  `BTW` varchar(255) NOT NULL,
  `PhoneNumber` varchar(255) NOT NULL,
  `PhoneMobile` varchar(255) NOT NULL,
  `Fax` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Website` varchar(255) NOT NULL,
  `DirectoryPrefix` varchar(255) NOT NULL,
  `InvoiceAddition` varchar(255) NOT NULL,
  `InvoiceText` longblob NOT NULL,
  `InvoiceCopyText` longblob NOT NULL,
  `InvoiceEmail` varchar(255) NOT NULL,
  `InvoiceCopy` enum('0','1') NOT NULL DEFAULT '0',
  `InvoiceCopyEmail` varchar(255) NOT NULL,
  `ReminderText` longblob DEFAULT NULL,
  `DunningText` longblob DEFAULT NULL,
  `ImportType` longblob NOT NULL,
  `InvoiceNumber` int(11) NOT NULL DEFAULT 0,
  `SalesOrderNumber` int(11) NOT NULL,
  `PurchaseOrderNumber` int(11) NOT NULL DEFAULT 0,
  `PurchaseNumber` int(11) NOT NULL DEFAULT 0,
  `QuotationNumber` int(11) NOT NULL DEFAULT 0,
  `ProjectNumber` int(11) NOT NULL DEFAULT 0,
  `WorkEmailTextCU` longblob DEFAULT NULL,
  `WorkEmailTextBC` longblob DEFAULT NULL,
  `WorkEmailTextCC` longblob DEFAULT NULL,
  `NewUserMailText` longblob DEFAULT NULL,
  `WorkEmail` varchar(255) DEFAULT NULL,
  `QuotationEmailText` longblob NOT NULL,
  `ModuleTickets` enum('0','1') DEFAULT '0',
  `ModuleWebsite` enum('0','1') DEFAULT '0',
  `ModuleSystemManagement` enum('0','1') DEFAULT '0',
  `ModuleTransporters` enum('0','1') DEFAULT '0',
  `ModuleSellers` enum('0','1') DEFAULT '0',
  `ModulePriceAgreement` enum('0','1') DEFAULT '0',
  `ModuleRepeatingInvoice` enum('0','1') DEFAULT '0',
  `ModuleQuotation` enum('0','1') DEFAULT '0',
  `CoditionsGeneralText` text DEFAULT NULL,
  `ConditionsSalesText` text DEFAULT NULL,
  `ConditionsGeneralPdf` varchar(255) DEFAULT NULL,
  `ConditionsSalesPdf` varchar(255) DEFAULT NULL,
  `SignConfirmationForCustomer` longblob DEFAULT NULL,
  `SignConfirmationForCollaborator` longblob DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `business`
--

INSERT INTO `business` (`Id`, `Name`, `StreetName`, `StreetNumber`, `StreetAddition`, `ZipCode`, `City`, `Country`, `IBAN`, `GREK`, `KVK`, `BIC`, `BTW`, `PhoneNumber`, `PhoneMobile`, `Fax`, `Email`, `Website`, `DirectoryPrefix`, `InvoiceAddition`, `InvoiceText`, `InvoiceCopyText`, `InvoiceEmail`, `InvoiceCopy`, `InvoiceCopyEmail`, `ReminderText`, `DunningText`, `ImportType`, `InvoiceNumber`, `SalesOrderNumber`, `PurchaseOrderNumber`, `PurchaseNumber`, `QuotationNumber`, `ProjectNumber`, `WorkEmailTextCU`, `WorkEmailTextBC`, `WorkEmailTextCC`, `NewUserMailText`, `WorkEmail`, `QuotationEmailText`, `ModuleTickets`, `ModuleWebsite`, `ModuleSystemManagement`, `ModuleTransporters`, `ModuleSellers`, `ModulePriceAgreement`, `ModuleRepeatingInvoice`, `ModuleQuotation`, `CoditionsGeneralText`, `ConditionsSalesText`, `ConditionsGeneralPdf`, `ConditionsSalesPdf`, `SignConfirmationForCustomer`, `SignConfirmationForCollaborator`) VALUES
(1, 'CommPro Automatisering', 'Nijverheidsweg', 6, 'a', '3381 LM', 'Giessenburg', 'Nederland', 'NL63INGB0004545179', '', '11065908', 'INGNL2A', 'NL189491036B01', '+31 (0)184 65 41 27', '', '', 'alaa.darkashli@commpro.nl', 'www.commpro.nl', 'commpro', '', 0x3c703e47656163687465206b6c616e742c3c2f703e0d0a3c703e496e2064652062696a6c6167652076696e6474207520757720666163747575722e3c6272202f3e3c6272202f3e496e6469656e207520646520666163747572656e206f702065656e20616e64657220652d6d61696c2061647265732077696c74206f6e7476616e67656e2c207265706c792064616e206f702064657a65206d61696c206d6574206465206e61616d20656e2068657420652d6d61696c2061647265732076616e206465206f6e7476616e6765722e3c6272202f3e3c6272202f3e4d657420767269656e64656c696a6b652067726f65742c3c6272202f3e3c6272202f3e41646d696e697374726174696520266e646173683b20436f6d6d50726f204175746f6d617469736572696e673c6272202f3e4e696a76657268656964737765672036613c6272202f3e33333831204c4d266e6273703b204769657373656e627572673c2f703e0d0a3c703e4e2e422e204469742062657269636874206973206175746f6d61746973636820676567656e65726565726420656e2064657268616c7665206e69657420706572736f6f6e6c696a6b206f6e64657274656b656e642e3c2f703e, 0x3c703e4265737465206b6c616e742c3c2f703e0d0a3c703e4869657262696a207472656674207520646520646f6f722075206f70676576726161676465206b6f70696520666163747575722e3c2f703e0d0a3c703e496e6469656e2075206e6f672076726167656e20686562742064616e207665726e656d656e207765206469652067726161672076616e20752e3c2f703e0d0a3c703e4d657420767269656e64656c696a6b652067726f65742c3c2f703e0d0a3c703e41646d696e697374726174696520266e646173683b20436f6d6d50726f204175746f6d617469736572696e673c6272202f3e4e696a76657268656964737765672036613c6272202f3e33333831204c4d266e6273703b204769657373656e627572673c6272202f3e74656c2e2030313834202d2036352034312032373c2f703e0d0a3c703e4e2e422e204469742062657269636874206973206175746f6d61746973636820676567656e65726565726420656e2064657268616c7665206e69657420706572736f6f6e6c696a6b206f6e64657274656b656e642e3c2f703e, 'facturen@commpro.nl', '1', '', 0x3c703e42657374652072656c617469652c3c2f703e0d0a3c703e556974206f6e7a652061646d696e6973747261746965206973206765626c656b656e206572206e6f672031206f66206d6565726465726520666163747572656e206e6965742c206f66206e69657420766f6c6c656469672c207a696a6e20766f6c6461616e2e20496e206d656567657a6f6e64656e206f7665727a6963687420747265667420752065656e207370656369666963617469652076616e2064652066616374757572206f6620666163747572656e2e3c2f703e0d0a3c703e57696a207665727a6f656b656e207520767269656e64656c696a6b20646974206265647261672061616e206f6e73206f766572207465206d616b656e206f702072656b2e6e722e204e4c20363320494e47423020303034353435313739206f6e646572207665726d656c64696e672076616e2068657420666163747575726e756d6d65722e3c2f703e0d0a3c703e4d6f6368742064657a65206d61696c20656e20757720626574616c696e6720656c6b616172206b72756973656e2c206f6620646520626574616c696e67206973207265656473206f6e6465727765672c2064616e206b756e7420752064657a65206d61696c20616c73206e696574207665727a6f6e64656e2062657363686f7577656e2e3c2f703e0d0a3c703e266e6273703b4d657420767269656e64656c696a6b652067726f65742c3c2f703e0d0a3c703e4465626974657572656e62656865657220266e646173683b20436f6d6d50726f204175746f6d617469736572696e673c2f703e0d0a3c703e4e696a76657268656964737765672036613c6272202f3e33333831204c4d266e6273703b204769657373656e627572673c2f703e0d0a3c703e4e2e422e204469742062657269636874206973206175746f6d61746973636820676567656e65726565726420656e2064657268616c7665206e69657420706572736f6f6e6c696a6b206f6e64657274656b656e642e3c2f703e, 0x3c703e42657374652072656c617469652c3c2f703e0d0a3c703e4f6e64616e6b73206565726465726520686572696e6e6572696e67656e2c20626c696a6b7420756974206f6e7a652061646d696e69737472617469652064617420266561637574653b266561637574653b6e206f66206d65657264657220666163747572656e206e6965742c206f66206e69657420766f6c6c656469672c207a696a6e20766f6c6461616e2e20496e206d656567657a6f6e64656e206f7665727a6963687420747265667420752065656e207370656369666963617469652076616e2064652066616374757572206f6620666163747572656e2e3c2f703e0d0a3c703e57696a207665727a6f656b656e207520767269656e64656c696a6b20646974206265647261672061616e206f6e73206f766572207465206d616b656e206f702072656b2e6e722e204e4c20363320494e47423020303034353435313739206f6e646572207665726d656c64696e672076616e2068657420666163747575726e756d6d65722e3c2f703e0d0a3c703e4d6f6368742064657a65206d61696c20656e20757720626574616c696e6720656c6b616172206b72756973656e2c206f6620646520626574616c696e67206973207265656473206f6e6465727765672c2064616e206b756e7420752064657a65206d61696c20616c73206e696574207665727a6f6e64656e2062657363686f7577656e2e3c2f703e0d0a3c703e266e6273703b4d657420767269656e64656c696a6b652067726f65742c3c2f703e0d0a3c703e4465626974657572656e62656865657220266e646173683b20436f6d6d50726f204175746f6d617469736572696e673c2f703e0d0a3c703e4e696a76657268656964737765672036613c6272202f3e33333831204c4d266e6273703b204769657373656e627572673c2f703e0d0a3c703e4e2e422e204469742062657269636874206973206175746f6d61746973636820676567656e65726565726420656e2064657268616c7665206e69657420706572736f6f6e6c696a6b206f6e64657274656b656e642e3c2f703e, 0x4e3b, 5787, 3297, 0, 3590, 199, 4, '', 0x3c703e426573746520636f6c6c6567612c3c2f703e0d0a3c703e4572206973207a6f6a7569737420646f6f72207b434f4e544143544b4c414e547d2076616e207b4b4c414e544e41414d7d2065656e20696e636964656e742067656d656c642062696a207b434f4c4c4547417d2e3c2f703e0d0a3c703e3c753e4d656c64696e673a266e6273703b3c2f753e3c2f703e0d0a3c703e3c656d3e7b4d454c44494e474b4c414e547d3c2f656d3e3c2f703e0d0a3c703e3c753e41637469653a3c2f753e3c2f703e0d0a3c703e3c656d3e7b41435449454d4544455745524b45527d3c2f656d3e3c2f703e0d0a3c703e3c7370616e207374796c653d22746578742d6465636f726174696f6e3a20756e6465726c696e653b223e496e7465726e65206e6f74697469653a3c2f7370616e3e3c2f703e0d0a3c703e7b494e5445524e454e4f54495449457d3c2f703e0d0a3c703e3c7374726f6e673e416374696520746f65676577657a656e2061616e3a207b544f45474557455a454e41414e7d3c2f7374726f6e673e3c6272202f3e3c7374726f6e673e5374617475733a207b5354415455537d3c2f7374726f6e673e3c2f703e0d0a3c703e446174756d3a207b444154554d7d20266e6273703b20266e6273703b20266e6273703b20266e6273703b54696a643a207b54494a447d3c2f703e0d0a3c703e3c6120687265663d227b5449434b455455524c7d223e42656b696a6b20646974207469636b657420696e20697a694163636f756e743c2f613e3c2f703e0d0a3c703e266e6273703b4d657420767269656e64656c696a6b652067726f65742c3c2f703e0d0a3c703e436f6d6d50726f204175746f6d617469736572696e673c6272202f3e4e696a76657268656964737765672036613c6272202f3e33333831204c4d266e6273703b204769657373656e627572673c6272202f3e74656c2e203031383420266e646173683b2036352034312032373c6272202f3e652d6d61696c3a203c6120687265663d226d61696c746f3a737570706f727440636f6d6d70726f2e6e6c223e737570706f727440636f6d6d70726f2e6e6c3c2f613e3c6272202f3e776562736974653a203c6120687265663d22687474703a2f2f7777772e636f6d6d70726f2e6e6c2f223e7777772e636f6d6d70726f2e6e6c3c2f613e3c2f703e0d0a3c703e266e6273703b3c2f703e0d0a3c703e4e2e422e204469742062657269636874206973206175746f6d61746973636820676567656e65726565726420656e2064657268616c7665206e69657420706572736f6f6e6c696a6b206f6e64657274656b656e642e3c2f703e, '', 0x3c703e4265737465207b46495253544e414d457d2c3c2f703e0d0a3c703e45722069732065656e206e6965757765206163636f756e742061616e67656d61616b742062696a20697a694163636f756e742e20446520766f6c67656e6465206765676576656e73207a696a6e206e6f646967206f6d20696e207465206b756e6e656e206c6f6767656e3a3c2f703e0d0a3c756c207374796c653d22666f6e742d73697a653a20313670783b223e0d0a3c6c693e4765627275696b6572736e61616d3a207b555345524e414d457d3c2f6c693e0d0a3c6c693e5761636874776f6f72643a207b50415353574f52447d3c2f6c693e0d0a3c2f756c3e0d0a3c703e496e6c6f6767656e206b616e20766961207777772e6d696a6e2e697a696163636f756e742e6e6c3c2f703e0d0a3c703e566f6f722076726167656e206b756e74207520616c74696a6420636f6e74616374206d6574206f6e73206f706e656d656e2e3c2f703e0d0a3c703e4d657420767269656e64656c696a6b652067726f65742c3c2f703e0d0a3c703e436f6d6d50726f204175746f6d617469736572696e673c6272202f3e4e696a76657268656964737765672036613c6272202f3e33333831204c4d266e6273703b204769657373656e627572673c6272202f3e74656c2e203031383420266e646173683b2036352034312032373c6272202f3e652d6d61696c3a266e6273703b3c6120687265663d226d61696c746f3a737570706f727440636f6d6d70726f2e6e6c223e737570706f727440636f6d6d70726f2e6e6c3c2f613e3c6272202f3e776562736974653a266e6273703b3c6120687265663d22687474703a2f2f7777772e636f6d6d70726f2e6e6c2f223e7777772e636f6d6d70726f2e6e6c3c2f613e3c2f703e0d0a3c703e4e2e422e204469742062657269636874206973206175746f6d61746973636820676567656e65726565726420656e2064657268616c7665206e69657420706572736f6f6e6c696a6b206f6e64657274656b656e642e3c2f703e, 'alaa.darkashli@commpro.nl', 0x3c703e4265737465207b46495253544e414d457d2c3c2f703e0d0a3c703e4869657262696a20747265667420752c207a6f616c732062656c6f6f66642c206465206f666665727465207374616174206b6c61617220766f6f7220752e3c2f703e0d0a3c703e6b6c696b206d616172206f702064657a65207b4c494e4b7d206f6d207577206f66666572746520746520636f6e74726f6c6c6572656e2e3c2f703e0d0a3c703e566f6f72206d65657220696e666f726d61746965206b756e74207520616c74696a64206d6574206f6e7320636f6e74616374206f706e656d656e2e3c2f703e0d0a3c703e4d657420767269656e64656c696a6b652067726f65742c3c2f703e0d0a3c703e41646d696e697374726174696520266e646173683b20436f6d6d50726f204175746f6d617469736572696e673c6272202f3e4e696a76657268656964737765672036613c6272202f3e33333831204c4d266e6273703b204769657373656e627572673c6272202f3e74656c2e2030313834202d2036352034312032373c2f703e0d0a3c703e4e2e422e204469742062657269636874206973206175746f6d61746973636820676567656e65726565726420656e2064657268616c7665206e69657420706572736f6f6e6c696a6b206f6e64657274656b656e642e3c2f703e, '1', '1', '1', '0', '0', '1', '1', '1', '', '', NULL, NULL, 0x3c703e4265737465207b46495253544e414d457d2c3c2f703e0d0a3c703e4869657262696a206465206b6f7069652076616e206465206f66666572746520646965207520686565667420676574656b656e642e3c2f703e0d0a3c703e566f6f72206d65657220696e666f726d61746965206b756e74207520616c74696a64206d6574206f6e7320636f6e74616374206f706e656d656e2e3c2f703e0d0a3c703e266e6273703b3c2f703e0d0a3c703e4d657420767269656e64656c696a6b652067726f65742c3c2f703e0d0a3c703e41646d696e697374726174696520266e646173683b20436f6d6d50726f204175746f6d617469736572696e673c6272202f3e4e696a76657268656964737765672036613c6272202f3e33333831204c4d266e6273703b204769657373656e627572673c6272202f3e74656c2e2030313834202d2036352034312032373c2f703e0d0a3c703e4e2e422e204469742062657269636874206973206175746f6d61746973636820676567656e65726565726420656e2064657268616c7665206e69657420706572736f6f6e6c696a6b206f6e64657274656b656e642e3c2f703e0d0a3c703e266e6273703b3c2f703e, 0x3c703e4265737465207b46495253544e414d457d2c3c2f703e0d0a3c703e4869657262696a206465206b6f7069652076616e206465206f66666572746520646965206465206b6c616e7420686565667420676574656b656e642e3c2f703e0d0a3c703e266e6273703b3c2f703e0d0a3c703e4d657420767269656e64656c696a6b652067726f65742c3c2f703e0d0a3c703e41646d696e697374726174696520266e646173683b20436f6d6d50726f204175746f6d617469736572696e673c6272202f3e4e696a76657268656964737765672036613c6272202f3e33333831204c4d266e6273703b204769657373656e627572673c6272202f3e74656c2e2030313834202d2036352034312032373c2f703e0d0a3c703e4e2e422e204469742062657269636874206973206175746f6d61746973636820676567656e65726565726420656e2064657268616c7665206e69657420706572736f6f6e6c696a6b206f6e64657274656b656e642e3c2f703e);

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

CREATE TABLE `ci_sessions` (
  `id` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `data` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `contactmoment`
--

CREATE TABLE `contactmoment` (
  `Id` int(11) NOT NULL,
  `Description` varchar(255) NOT NULL,
  `BusinessId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `contactmoment`
--

INSERT INTO `contactmoment` (`Id`, `Description`, `BusinessId`) VALUES
(2, 'first contactmoment', 1);

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `Id` int(11) NOT NULL,
  `FirstName` varchar(255) NOT NULL,
  `Insertion` varchar(255) NOT NULL,
  `LastName` varchar(255) NOT NULL,
  `Sex` varchar(255) DEFAULT NULL,
  `Salutation` varchar(255) NOT NULL DEFAULT 'formal',
  `Email` varchar(255) NOT NULL,
  `naam` varchar(255) NOT NULL,
  `PhoneNumber` varchar(255) NOT NULL,
  `PhoneMobile` varchar(255) NOT NULL,
  `Function` varchar(255) NOT NULL,
  `Employed` int(1) NOT NULL DEFAULT 1,
  `CustomerId` int(11) NOT NULL,
  `BusinessId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`Id`, `FirstName`, `Insertion`, `LastName`, `Sex`, `Salutation`, `Email`, `naam`, `PhoneNumber`, `PhoneMobile`, `Function`, `Employed`, `CustomerId`, `BusinessId`) VALUES
(2, 'Alaa', '', 'Darkashli', 'male', 'informal', 'alaa.darkashli@commpro.nl', '', '', '0685862225', 'none', 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `contactss`
--

CREATE TABLE `contactss` (
  `Id` int(11) NOT NULL,
  `FirstName` varchar(255) NOT NULL,
  `Insertion` varchar(255) NOT NULL,
  `LastName` varchar(255) NOT NULL,
  `PhoneNumber` varchar(255) NOT NULL,
  `PhoneMobile` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Function` varchar(255) NOT NULL,
  `SupplierId` int(11) NOT NULL,
  `BusinessId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `Id` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `StreetName` varchar(255) NOT NULL,
  `StreetNumber` int(11) NOT NULL,
  `StreetAddition` varchar(255) NOT NULL,
  `ZipCode` varchar(255) NOT NULL,
  `City` varchar(255) NOT NULL,
  `Country` varchar(255) NOT NULL,
  `IBAN` varchar(255) NOT NULL,
  `KVK` varchar(255) NOT NULL,
  `BTW` varchar(255) NOT NULL,
  `PhoneNumber` varchar(255) NOT NULL,
  `PhoneMobile` varchar(255) NOT NULL,
  `Fax` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Website` varchar(255) NOT NULL,
  `TwitterProfile` varchar(255) NOT NULL,
  `FacebookPage` varchar(255) NOT NULL,
  `VisitStreetName` varchar(255) NOT NULL,
  `VisitStreetNumber` varchar(255) NOT NULL,
  `VisitStreetAddition` varchar(255) NOT NULL,
  `VisitZipCode` varchar(255) NOT NULL,
  `VisitCity` varchar(255) NOT NULL,
  `VisitCountry` varchar(255) NOT NULL,
  `PaymentCondition` varchar(255) NOT NULL,
  `TermOfPayment` int(255) NOT NULL DEFAULT 30,
  `ToAttention` varchar(255) NOT NULL,
  `PhonenumberFinancial` varchar(255) NOT NULL,
  `EmailFinancial` varchar(255) NOT NULL,
  `HeadCustomerId` int(11) NOT NULL,
  `Note` longblob NOT NULL,
  `BusinessId` int(11) NOT NULL,
  `contactpersoon` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`Id`, `Name`, `StreetName`, `StreetNumber`, `StreetAddition`, `ZipCode`, `City`, `Country`, `IBAN`, `KVK`, `BTW`, `PhoneNumber`, `PhoneMobile`, `Fax`, `Email`, `Website`, `TwitterProfile`, `FacebookPage`, `VisitStreetName`, `VisitStreetNumber`, `VisitStreetAddition`, `VisitZipCode`, `VisitCity`, `VisitCountry`, `PaymentCondition`, `TermOfPayment`, `ToAttention`, `PhonenumberFinancial`, `EmailFinancial`, `HeadCustomerId`, `Note`, `BusinessId`, `contactpersoon`) VALUES
(1, 'Jacky valkparkietje ', 'Drossaartstraat ', 6, 'C', '4204AS', 'Gorinchem ', 'Nederland', '', '', '', '0685862225', '', '', 'alaa.darkashli@commpro.nl', '', 'jacky_valkparkiet_twitter', 'jacky_valkparkiet_facebook', '', '', '', '', '', '', 'nog te betalen ', 14, 'Alaa', '0685862225', 'alaa.darkashli@commpro.nl', 0, '', 1, '');

-- --------------------------------------------------------

--
-- Table structure for table `defaulttexts`
--

CREATE TABLE `defaulttexts` (
  `Id` int(11) NOT NULL,
  `Titel` varchar(255) NOT NULL,
  `Text` longblob NOT NULL,
  `BusinessId` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `domain`
--

CREATE TABLE `domain` (
  `Id` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `RegisterDate` date NOT NULL,
  `Customer` int(11) NOT NULL,
  `Reseller` int(11) DEFAULT NULL,
  `HasHosting` tinyint(1) NOT NULL,
  `BusinessId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `importtype`
--

CREATE TABLE `importtype` (
  `Id` int(11) NOT NULL,
  `Name` varchar(255) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `invoice`
--

CREATE TABLE `invoice` (
  `Id` int(11) NOT NULL,
  `InvoiceNumber` varchar(255) NOT NULL,
  `TotalEx` decimal(11,2) NOT NULL,
  `TotalIn` decimal(11,2) NOT NULL,
  `TotalTax21` decimal(11,2) NOT NULL,
  `TotalExTax21` decimal(11,2) NOT NULL,
  `TotalTax6` decimal(11,2) NOT NULL,
  `TotalExTax6` decimal(11,2) NOT NULL,
  `TotalExTax0` decimal(11,2) NOT NULL,
  `TotalIn21` decimal(11,2) NOT NULL,
  `TotalIn6` decimal(11,2) NOT NULL,
  `btw_verleg` varchar(255) NOT NULL,
  `verleg_21` varchar(255) NOT NULL,
  `verleg_6` varchar(255) NOT NULL,
  `btw_in0` varchar(255) NOT NULL,
  `totaal_in0` varchar(255) NOT NULL DEFAULT '0,00',
  `inkoopprijs` varchar(255) NOT NULL,
  `WorkOrder` varchar(255) NOT NULL DEFAULT '0',
  `InvoiceDate` int(11) NOT NULL,
  `ExpirationDate` int(11) NOT NULL,
  `TimePeriod` varchar(255) DEFAULT NULL,
  `ShortDescription` varchar(255) DEFAULT NULL,
  `Description` text DEFAULT NULL,
  `PaymentDate` int(11) NOT NULL,
  `PaymentCondition` varchar(255) NOT NULL,
  `TermOfPayment` int(11) NOT NULL,
  `ImportPaymentRemark` varchar(255) NOT NULL,
  `contact` varchar(255) DEFAULT NULL,
  `HeadCustomerId` int(11) NOT NULL,
  `CustomerId` int(11) DEFAULT NULL,
  `CompanyName` varchar(255) DEFAULT NULL,
  `FrontName` varchar(255) DEFAULT NULL,
  `Insertion` varchar(255) DEFAULT NULL,
  `LastName` varchar(255) DEFAULT NULL,
  `Address` varchar(255) DEFAULT NULL,
  `AddressNumber` varchar(255) DEFAULT NULL,
  `AddressAddition` varchar(255) DEFAULT NULL,
  `ZipCode` varchar(255) DEFAULT NULL,
  `City` varchar(255) DEFAULT NULL,
  `Country` varchar(255) DEFAULT NULL,
  `MailAddress` varchar(225) DEFAULT NULL,
  `SentPerMail` tinyint(1) NOT NULL DEFAULT 0,
  `BusinessId` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `invoice`
--

INSERT INTO `invoice` (`Id`, `InvoiceNumber`, `TotalEx`, `TotalIn`, `TotalTax21`, `TotalExTax21`, `TotalTax6`, `TotalExTax6`, `TotalExTax0`, `TotalIn21`, `TotalIn6`, `btw_verleg`, `verleg_21`, `verleg_6`, `btw_in0`, `totaal_in0`, `inkoopprijs`, `WorkOrder`, `InvoiceDate`, `ExpirationDate`, `TimePeriod`, `ShortDescription`, `Description`, `PaymentDate`, `PaymentCondition`, `TermOfPayment`, `ImportPaymentRemark`, `contact`, `HeadCustomerId`, `CustomerId`, `CompanyName`, `FrontName`, `Insertion`, `LastName`, `Address`, `AddressNumber`, `AddressAddition`, `ZipCode`, `City`, `Country`, `MailAddress`, `SentPerMail`, `BusinessId`) VALUES
(1, '105787', '1320.00', '1597.20', '277.20', '1320.00', '0.00', '0.00', '0.00', '1597.20', '0.00', '', '', '', '', '0,00', '', '0', 1570658400, 1571868000, NULL, '', '', 0, 'nog te betalen ', 14, '', NULL, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `invoicecustomfield`
--

CREATE TABLE `invoicecustomfield` (
  `Id` int(10) UNSIGNED NOT NULL,
  `InvoiceId` int(11) NOT NULL,
  `Key` varchar(255) NOT NULL,
  `Value` varchar(255) NOT NULL,
  `BusinessId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `invoicepayments`
--

CREATE TABLE `invoicepayments` (
  `Id` int(11) NOT NULL,
  `InvoiceId` int(11) NOT NULL,
  `Amount` decimal(10,2) NOT NULL,
  `Date` int(11) NOT NULL,
  `BusinessId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `invoicereminders`
--

CREATE TABLE `invoicereminders` (
  `Id` int(11) NOT NULL,
  `InvoiceId` int(11) NOT NULL,
  `ReminderDate` varchar(11) NOT NULL,
  `ReminderType` int(11) NOT NULL,
  `BusinessId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `invoicerules`
--

CREATE TABLE `invoicerules` (
  `Id` int(11) NOT NULL,
  `InvoiceId` int(11) NOT NULL,
  `InvoiceNumber` varchar(255) NOT NULL,
  `ArticleC` varchar(255) NOT NULL,
  `Amount` decimal(10,2) NOT NULL,
  `Description` varchar(255) NOT NULL,
  `inkoopprijs` decimal(11,2) NOT NULL,
  `Price` decimal(11,2) NOT NULL,
  `Discount` decimal(11,2) NOT NULL,
  `Tax` int(11) NOT NULL,
  `verlegd` decimal(11,2) NOT NULL,
  `Total` decimal(11,2) NOT NULL,
  `MetaData` mediumblob DEFAULT NULL,
  `CustomerId` int(11) DEFAULT NULL,
  `BusinessId` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `invoicerules`
--

INSERT INTO `invoicerules` (`Id`, `InvoiceId`, `InvoiceNumber`, `ArticleC`, `Amount`, `Description`, `inkoopprijs`, `Price`, `Discount`, `Tax`, `verlegd`, `Total`, `MetaData`, `CustomerId`, `BusinessId`) VALUES
(1, 1, '105787', '5', '3.00', 'notebook MSi', '0.00', '550.00', '20.00', 21, '0.00', '1320.00', NULL, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `invoicerulessupplier`
--

CREATE TABLE `invoicerulessupplier` (
  `Id` int(11) NOT NULL,
  `InvoiceId` int(11) NOT NULL DEFAULT 0,
  `InvoiceNumber` varchar(255) NOT NULL,
  `ArticleC` varchar(255) NOT NULL,
  `Amount` int(11) NOT NULL,
  `Description` varchar(255) NOT NULL,
  `Price` decimal(11,2) NOT NULL,
  `Discount` decimal(11,2) NOT NULL,
  `Tax` int(11) NOT NULL,
  `Total` decimal(11,2) NOT NULL,
  `SupplierId` int(11) DEFAULT NULL,
  `BusinessId` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `invoicesupplier`
--

CREATE TABLE `invoicesupplier` (
  `Id` int(11) NOT NULL,
  `InvoiceNumber` varchar(255) NOT NULL,
  `PurchaseNumber` varchar(255) NOT NULL,
  `TotalEx` varchar(255) NOT NULL,
  `TotalTax21` decimal(11,2) NOT NULL,
  `TotalTax6` decimal(11,2) NOT NULL,
  `TotalIn` decimal(11,2) NOT NULL,
  `TotalIn21` decimal(11,2) NOT NULL,
  `TotalIn6` decimal(11,2) NOT NULL,
  `TotalExTax21` decimal(11,2) NOT NULL,
  `TotalExTax6` decimal(11,2) NOT NULL,
  `TotalExTax0` decimal(11,2) NOT NULL,
  `InvoiceDate` int(11) NOT NULL,
  `ExpirationDate` int(11) NOT NULL,
  `Description` text DEFAULT NULL,
  `PeriodDateFrom` int(11) DEFAULT NULL,
  `PeriodDateTo` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL,
  `PaymentDate` int(11) NOT NULL,
  `ImportPaymentRemark` varchar(255) NOT NULL,
  `PaymentCondition` varchar(255) DEFAULT NULL,
  `TermOfPayment` int(11) DEFAULT NULL,
  `ContactId` int(11) NOT NULL,
  `SupplierId` int(11) DEFAULT NULL,
  `CompanyName` varchar(255) DEFAULT NULL,
  `FrontName` varchar(255) DEFAULT NULL,
  `Insertion` varchar(255) DEFAULT NULL,
  `LastName` varchar(255) DEFAULT NULL,
  `Address` varchar(255) DEFAULT NULL,
  `AddressNumber` varchar(255) DEFAULT NULL,
  `AddressAddition` varchar(255) DEFAULT NULL,
  `ZipCode` varchar(255) DEFAULT NULL,
  `City` varchar(255) DEFAULT NULL,
  `Country` varchar(255) DEFAULT NULL,
  `MailAddress` varchar(225) DEFAULT NULL,
  `BusinessId` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `invoicesupplierpayments`
--

CREATE TABLE `invoicesupplierpayments` (
  `Id` int(10) UNSIGNED NOT NULL,
  `InvoiceId` int(11) NOT NULL,
  `Amount` decimal(10,2) NOT NULL,
  `Date` int(11) NOT NULL,
  `BusinessId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `version` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`version`) VALUES
(20191101094000),
(20191101094000),
(20191101094000),
(20191101094000),
(20191101094000),
(20191101094000),
(20191101094000),
(20191101094000),
(20191101094000),
(20191101094000),
(20191101094000),
(20191101094000),
(20191101094000),
(20191101094000),
(20191101094000),
(20191101094000),
(20191101094000),
(20191101094000),
(20191101094000),
(20191101094000),
(20191101094000),
(20191101094000),
(20191101094000),
(20191101094000),
(20191101094000),
(20191101094000),
(20191101094000),
(20191101094000),
(20191101094000),
(20191101094000),
(20191101094000),
(20191101094000),
(20191101094000),
(20191101094000),
(20191101094000),
(20191101094000),
(20191101094000),
(20191101094000),
(20191101094000),
(20191101094000),
(20191101094000),
(20191101094000),
(20191101094000),
(20191101094000),
(20191101094000),
(20191101094000),
(20191101094000),
(20191101094000),
(20191101094000),
(20191101094000),
(20191101094000),
(20191101094000),
(20191101094000),
(20191101094000),
(20191101094000),
(20191101094000),
(20191101094000),
(20191101094000),
(20191101094000),
(20191101094000),
(20191101094000),
(20191101094000),
(20191101094000);

-- --------------------------------------------------------

--
-- Table structure for table `natureofwork`
--

CREATE TABLE `natureofwork` (
  `Id` int(11) NOT NULL,
  `Description` varchar(255) NOT NULL,
  `BusinessId` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `natureofwork`
--

INSERT INTO `natureofwork` (`Id`, `Description`, `BusinessId`) VALUES
(1, 'Test', 1);

-- --------------------------------------------------------

--
-- Table structure for table `notificationmessage`
--

CREATE TABLE `notificationmessage` (
  `Id` int(11) UNSIGNED NOT NULL,
  `UserId` int(11) NOT NULL,
  `BusinessId` int(11) NOT NULL,
  `Title` varchar(255) NOT NULL,
  `Body` varchar(255) NOT NULL,
  `Read` tinyint(1) NOT NULL,
  `Timestamp` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `paymentconditions`
--

CREATE TABLE `paymentconditions` (
  `Id` int(11) NOT NULL,
  `Name` varchar(225) NOT NULL,
  `BusinessId` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `paymentconditions`
--

INSERT INTO `paymentconditions` (`Id`, `Name`, `BusinessId`) VALUES
(1, 'nog te betalen ', 1);

-- --------------------------------------------------------

--
-- Table structure for table `priceagreement`
--

CREATE TABLE `priceagreement` (
  `Id` int(11) NOT NULL,
  `ArticleNumber` varchar(255) NOT NULL DEFAULT '0',
  `Description` varchar(255) NOT NULL DEFAULT '0',
  `Price` decimal(11,2) NOT NULL DEFAULT 0.00,
  `Discount` decimal(11,2) NOT NULL DEFAULT 0.00,
  `CustomerId` int(11) NOT NULL DEFAULT 0,
  `BusinessId` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `priceagreement`
--

INSERT INTO `priceagreement` (`Id`, `ArticleNumber`, `Description`, `Price`, `Discount`, `CustomerId`, `BusinessId`) VALUES
(1, '3', 'Logitech mice ', '40.00', '15.00', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `Id` int(11) NOT NULL,
  `ArticleNumber` varchar(255) NOT NULL,
  `Description` varchar(255) NOT NULL,
  `SupplierId` int(11) DEFAULT NULL,
  `EanCode` varchar(255) NOT NULL,
  `PurchasePrice` decimal(10,2) NOT NULL,
  `SalesPrice` decimal(10,2) NOT NULL,
  `BTW` enum('21','9','0') DEFAULT NULL,
  `Vvp` decimal(10,2) DEFAULT NULL,
  `ProductGroup` int(11) NOT NULL,
  `Active` int(11) NOT NULL DEFAULT 0,
  `ProductKind` int(11) NOT NULL,
  `Type` int(11) NOT NULL DEFAULT 0,
  `Stock` int(11) NOT NULL DEFAULT 0,
  `StockReadonly` int(11) NOT NULL DEFAULT 0,
  `NatureOfWork` int(11) NOT NULL,
  `WarehouseLocation` varchar(255) NOT NULL,
  `Warehouse` int(11) NOT NULL,
  `BulkLocation` varchar(255) DEFAULT NULL,
  `UserId` int(11) NOT NULL,
  `BusinessId` int(11) NOT NULL,
  `isShop` tinyint(1) NOT NULL DEFAULT 0,
  `shopId` int(11) DEFAULT 0,
  `Woocommerce_Description` varchar(225) DEFAULT NULL,
  `SoldIndividually` int(11) DEFAULT 0,
  `WoocommerceInSale` tinyint(1) NOT NULL DEFAULT 0,
  `SalePrice` decimal(10,2) DEFAULT NULL,
  `SalePriceDatesTo` date DEFAULT NULL,
  `SalePriceDatesFrom` date DEFAULT NULL,
  `Weight` int(11) DEFAULT NULL,
  `Height` int(11) DEFAULT NULL,
  `Length` int(11) DEFAULT NULL,
  `Width` int(11) DEFAULT NULL,
  `LongDescription` longtext DEFAULT NULL,
  `Upsells` mediumblob DEFAULT NULL,
  `Crossells` mediumblob DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `productgroup`
--

CREATE TABLE `productgroup` (
  `Id` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Description` text DEFAULT NULL,
  `Image` varchar(255) DEFAULT NULL,
  `ParentId` int(11) DEFAULT NULL,
  `IsShop` tinyint(1) DEFAULT 0,
  `ShopId` int(11) DEFAULT NULL,
  `BusinessId` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `productimage`
--

CREATE TABLE `productimage` (
  `Id` int(11) NOT NULL,
  `ProductId` int(11) NOT NULL,
  `FileName` varchar(2083) NOT NULL,
  `Position` int(11) NOT NULL,
  `BusinessId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `project`
--

CREATE TABLE `project` (
  `Id` int(11) NOT NULL,
  `ProjectNumber` varchar(255) NOT NULL,
  `Description` varchar(255) NOT NULL,
  `LongDescription` text NOT NULL,
  `NatureOfWorkId` int(11) NOT NULL,
  `CustomerId` int(11) NOT NULL,
  `BusinessId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `projectphase`
--

CREATE TABLE `projectphase` (
  `Id` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `ProjectId` int(11) NOT NULL,
  `BusinessId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `purchaseorderrules`
--

CREATE TABLE `purchaseorderrules` (
  `Id` int(11) NOT NULL,
  `PurchaseOrderId` int(11) DEFAULT NULL,
  `OrderNumber` varchar(255) NOT NULL,
  `ArticleC` varchar(255) NOT NULL,
  `EanCode` varchar(255) DEFAULT NULL,
  `Amount` decimal(10,2) NOT NULL,
  `Description` varchar(255) NOT NULL,
  `inkoopprijs` decimal(11,2) NOT NULL,
  `Price` decimal(11,2) NOT NULL,
  `Discount` decimal(11,2) NOT NULL,
  `Tax` int(11) NOT NULL,
  `verlegd` decimal(11,2) NOT NULL,
  `Total` decimal(11,2) NOT NULL,
  `SupplierId` int(11) DEFAULT NULL,
  `BusinessId` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `purchaseorders`
--

CREATE TABLE `purchaseorders` (
  `Id` int(11) NOT NULL,
  `OrderNumber` varchar(255) NOT NULL,
  `TotalEx` decimal(11,2) NOT NULL,
  `TotalIn` decimal(11,2) NOT NULL,
  `TotalTax21` decimal(11,2) NOT NULL,
  `TotalExTax21` decimal(11,2) NOT NULL,
  `TotalTax6` decimal(11,2) NOT NULL,
  `TotalExTax6` decimal(11,2) NOT NULL,
  `TotalExTax0` decimal(11,2) NOT NULL,
  `TotalIn21` decimal(11,2) NOT NULL,
  `TotalIn6` decimal(11,2) NOT NULL,
  `btw_verleg` varchar(255) NOT NULL,
  `verleg_21` varchar(255) NOT NULL,
  `verleg_6` varchar(255) NOT NULL,
  `btw_in0` varchar(255) NOT NULL,
  `totaal_in0` varchar(255) NOT NULL DEFAULT '0,00',
  `inkoopprijs` varchar(255) NOT NULL,
  `WorkOrder` varchar(255) NOT NULL DEFAULT '0',
  `OrderDate` date DEFAULT NULL,
  `contact` varchar(255) NOT NULL,
  `HeadCustomerId` int(11) NOT NULL,
  `SupplierId` int(11) DEFAULT NULL,
  `CompanyName` varchar(255) DEFAULT NULL,
  `FrontName` varchar(255) DEFAULT NULL,
  `Insertion` varchar(255) DEFAULT NULL,
  `LastName` varchar(255) DEFAULT NULL,
  `Address` varchar(255) DEFAULT NULL,
  `AddressNumber` varchar(255) DEFAULT NULL,
  `AddressAddition` varchar(255) DEFAULT NULL,
  `ZipCode` varchar(255) DEFAULT NULL,
  `City` varchar(255) DEFAULT NULL,
  `Country` varchar(255) DEFAULT NULL,
  `MailAddress` varchar(225) DEFAULT NULL,
  `Reference` text DEFAULT NULL,
  `Invoiced` int(11) NOT NULL DEFAULT 0,
  `BusinessId` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `quotation`
--

CREATE TABLE `quotation` (
  `Id` int(11) NOT NULL,
  `UserId` int(11) DEFAULT NULL,
  `CreatorName` varchar(255) DEFAULT NULL,
  `CustomerId` int(11) DEFAULT NULL,
  `CustomerName` varchar(255) NOT NULL,
  `CustomerStreet` varchar(255) NOT NULL,
  `CustomerHousenumber` varchar(255) NOT NULL,
  `CustomerHousenumberAddition` varchar(255) DEFAULT NULL,
  `CustomerZipCode` varchar(255) NOT NULL,
  `CustomerCity` varchar(255) NOT NULL,
  `CustomerCountry` varchar(255) NOT NULL,
  `CustomerMailaddress` varchar(255) DEFAULT NULL,
  `CreatedDate` date NOT NULL,
  `ContactId` int(11) DEFAULT NULL,
  `ContactFirstName` varchar(255) NOT NULL,
  `ContactInsertion` varchar(255) DEFAULT NULL,
  `ContactLastName` varchar(255) NOT NULL,
  `ContactSex` varchar(6) DEFAULT NULL,
  `ContactSalutation` varchar(255) NOT NULL,
  `QuotationNumber` varchar(255) NOT NULL,
  `Subject` varchar(255) NOT NULL,
  `Reason` text NOT NULL,
  `ContactDate` date NOT NULL,
  `WorkDescription` longblob NOT NULL,
  `WorkAmount` decimal(11,2) NOT NULL,
  `WorkArticleC` varchar(255) DEFAULT NULL,
  `ProductDescription` longblob DEFAULT NULL,
  `RecurringDescription` longblob DEFAULT NULL,
  `RecurringTimePeriod` varchar(255) DEFAULT NULL,
  `ProjectDescription` longblob NOT NULL,
  `ValidDays` int(11) NOT NULL,
  `DeliveryTime` varchar(255) DEFAULT NULL,
  `PaymentConditionId` int(11) NOT NULL,
  `PaymentTerm` int(11) DEFAULT NULL,
  `IsComparison` tinyint(1) NOT NULL DEFAULT 0,
  `Status` varchar(255) NOT NULL,
  `Rejected` tinyint(1) NOT NULL DEFAULT 0,
  `Template` varchar(255) DEFAULT NULL,
  `BusinessId` int(11) DEFAULT NULL,
  `CurrentSituationAndAdvice` text DEFAULT NULL,
  `StatusPdf` varchar(255) DEFAULT NULL,
  `Signature` longblob DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `quotation`
--

INSERT INTO `quotation` (`Id`, `UserId`, `CreatorName`, `CustomerId`, `CustomerName`, `CustomerStreet`, `CustomerHousenumber`, `CustomerHousenumberAddition`, `CustomerZipCode`, `CustomerCity`, `CustomerCountry`, `CustomerMailaddress`, `CreatedDate`, `ContactId`, `ContactFirstName`, `ContactInsertion`, `ContactLastName`, `ContactSex`, `ContactSalutation`, `QuotationNumber`, `Subject`, `Reason`, `ContactDate`, `WorkDescription`, `WorkAmount`, `WorkArticleC`, `ProductDescription`, `RecurringDescription`, `RecurringTimePeriod`, `ProjectDescription`, `ValidDays`, `DeliveryTime`, `PaymentConditionId`, `PaymentTerm`, `IsComparison`, `Status`, `Rejected`, `Template`, `BusinessId`, `CurrentSituationAndAdvice`, `StatusPdf`, `Signature`) VALUES
(143, 1, 'Alaa Darkashli', 1, 'Jacky valkparkietje ', 'Drossaartstraat ', '6', 'C', '4204AS', 'Gorinchem ', 'Nederland', 'alaa.darkashli@commpro.nl', '2019-11-01', 2, 'Alaa', '', 'Darkashli', 'male', 'informal', 'O2019-0197', 'First offerte ', 'Dit is onze aanbieding voor deze maand', '2019-11-01', '', '0.00', '', '', '', '7 days', '', 14, '15', 1, 12, 0, '', 0, 'commpro', 1, '', NULL, NULL),
(145, 1, 'Alaa Darkashli', 1, 'Jacky valkparkietje ', 'Drossaartstraat ', '6', 'C', '4204AS', 'Gorinchem ', 'Nederland', 'alaa.darkashli@commpro.nl', '2019-11-04', 2, 'Alaa', '', 'Darkashli', 'male', 'informal', 'O2019-0199', 'Second offerte ', 'Dit is onze aanbieding voor deze maand', '2019-11-04', '', '0.00', '', '', '', '7 days', '', 14, '15', 1, 30, 0, '', 0, 'commpro', 1, '', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `quotationcustomfield`
--

CREATE TABLE `quotationcustomfield` (
  `Id` int(10) UNSIGNED NOT NULL,
  `QuotationId` int(11) NOT NULL,
  `Key` varchar(255) NOT NULL,
  `Value` varchar(255) NOT NULL,
  `BusinessId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `quotationfile`
--

CREATE TABLE `quotationfile` (
  `Id` int(11) UNSIGNED NOT NULL,
  `Name` varchar(255) NOT NULL,
  `DisplayFileName` varchar(255) NOT NULL,
  `QuotationId` int(11) NOT NULL,
  `BusinessId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `quotationfile`
--

INSERT INTO `quotationfile` (`Id`, `Name`, `DisplayFileName`, `QuotationId`, `BusinessId`) VALUES
(9, 'sample.pdf', 'This is a test', 143, 1),
(10, '', '', 144, 1),
(11, 'sample.pdf', 'sample pdf ', 145, 1);

-- --------------------------------------------------------

--
-- Table structure for table `quotationrules`
--

CREATE TABLE `quotationrules` (
  `Id` int(11) NOT NULL,
  `BusinessId` int(11) NOT NULL,
  `QuotationId` int(11) NOT NULL,
  `ArticleC` varchar(255) NOT NULL,
  `EanCode` varchar(255) NOT NULL,
  `ArticleDescription` varchar(255) NOT NULL,
  `Amount` int(11) NOT NULL,
  `SalesPrice` decimal(11,2) NOT NULL,
  `Discount` decimal(11,2) DEFAULT NULL,
  `Tax` int(11) NOT NULL,
  `Type` int(11) NOT NULL,
  `MetaData` mediumblob DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `quotationrules`
--

INSERT INTO `quotationrules` (`Id`, `BusinessId`, `QuotationId`, `ArticleC`, `EanCode`, `ArticleDescription`, `Amount`, `SalesPrice`, `Discount`, `Tax`, `Type`, `MetaData`) VALUES
(1, 1, 1, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(2, 1, 1, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(3, 1, 2, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(4, 1, 2, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(5, 1, 3, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(6, 1, 3, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(7, 1, 4, '3', '', 'artikelnummer 3', 4, '40.00', '5.00', 21, 1, NULL),
(8, 1, 4, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(9, 1, 5, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(10, 1, 5, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(11, 1, 6, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(12, 1, 6, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(13, 1, 7, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(14, 1, 7, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(15, 1, 8, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(16, 1, 8, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(17, 1, 9, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(18, 1, 9, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(19, 1, 10, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(20, 1, 10, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(21, 1, 11, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(22, 1, 11, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(23, 1, 12, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(24, 1, 12, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(25, 1, 13, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(26, 1, 13, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(27, 1, 14, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(28, 1, 14, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(29, 1, 15, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(30, 1, 15, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(31, 1, 16, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(32, 1, 16, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(33, 1, 17, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(34, 1, 17, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(35, 1, 18, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(36, 1, 18, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(37, 1, 19, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(38, 1, 19, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(39, 1, 20, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(40, 1, 20, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(41, 1, 21, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(42, 1, 21, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(43, 1, 22, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(44, 1, 22, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(45, 1, 23, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(46, 1, 23, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(47, 1, 24, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(48, 1, 24, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(49, 1, 25, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(50, 1, 25, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(51, 1, 26, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(52, 1, 26, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(53, 1, 27, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(54, 1, 27, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(55, 1, 28, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(56, 1, 28, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(57, 1, 29, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(58, 1, 29, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(59, 1, 30, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(60, 1, 30, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(61, 1, 31, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(62, 1, 31, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(63, 1, 32, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(64, 1, 32, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(65, 1, 33, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(66, 1, 33, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(67, 1, 34, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(68, 1, 34, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(69, 1, 35, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(70, 1, 35, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(71, 1, 41, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(72, 1, 41, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(73, 1, 42, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(74, 1, 42, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(75, 1, 43, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(76, 1, 43, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(77, 1, 44, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(78, 1, 44, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(79, 1, 45, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(80, 1, 45, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(81, 1, 46, '1', '', 'artikel nummer 1 ', 2, '20.00', '4.00', 21, 1, NULL),
(82, 1, 46, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(83, 1, 47, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(84, 1, 47, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(85, 1, 48, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(86, 1, 48, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(87, 1, 49, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(88, 1, 49, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(89, 1, 50, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(90, 1, 50, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(91, 1, 51, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(92, 1, 51, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(93, 1, 52, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(94, 1, 52, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(95, 1, 53, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(96, 1, 53, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(97, 1, 54, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(98, 1, 54, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(99, 1, 55, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(100, 1, 55, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(101, 1, 56, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(102, 1, 56, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(103, 1, 57, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(104, 1, 57, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(105, 1, 58, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(106, 1, 58, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(107, 1, 59, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(108, 1, 59, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(109, 1, 60, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(110, 1, 60, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(111, 1, 61, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(112, 1, 61, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(113, 1, 62, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(114, 1, 62, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(115, 1, 63, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(116, 1, 63, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(117, 1, 64, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(118, 1, 64, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(119, 1, 65, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(120, 1, 65, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(121, 1, 66, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(122, 1, 66, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(123, 1, 67, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(124, 1, 67, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(125, 1, 68, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(126, 1, 68, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(127, 1, 69, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(128, 1, 69, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(129, 1, 70, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(130, 1, 70, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(131, 1, 71, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(132, 1, 71, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(133, 1, 72, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(134, 1, 72, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(135, 1, 73, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(136, 1, 73, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(137, 1, 74, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(138, 1, 74, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(139, 1, 75, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(140, 1, 75, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(141, 1, 76, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(142, 1, 76, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(143, 1, 77, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(144, 1, 77, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(145, 1, 78, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(146, 1, 78, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(147, 1, 79, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(148, 1, 79, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(149, 1, 80, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(150, 1, 80, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(151, 1, 81, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(152, 1, 81, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(153, 1, 82, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(154, 1, 82, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(155, 1, 83, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(156, 1, 83, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(157, 1, 84, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(158, 1, 84, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(159, 1, 85, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(160, 1, 85, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(161, 1, 86, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(162, 1, 86, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(163, 1, 87, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(164, 1, 87, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(165, 1, 88, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(166, 1, 88, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(167, 1, 89, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(168, 1, 89, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(169, 1, 90, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(170, 1, 90, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(171, 1, 91, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(172, 1, 91, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(173, 1, 92, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(174, 1, 92, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(175, 1, 93, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(176, 1, 93, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(177, 1, 94, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(178, 1, 94, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(179, 1, 95, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(180, 1, 95, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(181, 1, 96, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(182, 1, 96, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(183, 1, 97, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(184, 1, 97, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(185, 1, 98, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(186, 1, 98, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(187, 1, 99, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(188, 1, 99, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(189, 1, 100, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(190, 1, 100, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(191, 1, 101, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(192, 1, 101, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(193, 1, 102, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(194, 1, 102, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(195, 1, 103, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(196, 1, 103, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(197, 1, 104, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(198, 1, 104, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(199, 1, 105, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(200, 1, 105, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(201, 1, 106, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(202, 1, 106, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(203, 1, 107, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(204, 1, 107, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(205, 1, 108, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(206, 1, 108, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(207, 1, 109, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(208, 1, 109, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(209, 1, 110, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(210, 1, 110, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(211, 1, 111, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(212, 1, 111, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(213, 1, 1, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(214, 1, 1, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(215, 1, 2, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(216, 1, 2, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(217, 1, 126, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(218, 1, 126, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(219, 1, 127, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(220, 1, 127, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(221, 1, 128, 'article 55', '', 'this is an article 55', 3, '13.00', '0.00', 21, 1, NULL),
(222, 1, 128, 'Artikelnummer 3', '', 'This is an a article ', 6, '16.00', '0.00', 21, 2, NULL),
(223, 1, 129, 'artikelnummer 3', '', 'DIt is Artikelomschrijving', 2, '33.00', '5.00', 21, 1, NULL),
(224, 1, 129, 'Artikelnummer 4', '', 'Artikelomschrijving 4', 2, '16.00', '3.00', 21, 2, NULL),
(225, 1, 130, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(226, 1, 130, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(227, 1, 131, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(228, 1, 131, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(229, 1, 132, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(230, 1, 132, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(231, 1, 134, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(232, 1, 134, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(233, 1, 135, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(234, 1, 135, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(235, 1, 136, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(236, 1, 136, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(237, 1, 137, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(238, 1, 137, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(239, 1, 138, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(240, 1, 138, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(241, 1, 139, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(242, 1, 139, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(243, 1, 140, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(244, 1, 140, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(245, 1, 141, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(246, 1, 141, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(247, 1, 142, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(248, 1, 142, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(249, 1, 143, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(250, 1, 143, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(251, 1, 144, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(252, 1, 144, '', '', '', 0, '0.00', '0.00', 21, 2, NULL),
(253, 1, 145, '', '', '', 0, '0.00', '0.00', 21, 1, NULL),
(254, 1, 145, '', '', '', 0, '0.00', '0.00', 21, 2, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `quotationstatus`
--

CREATE TABLE `quotationstatus` (
  `Id` int(11) NOT NULL,
  `Description` varchar(255) NOT NULL,
  `SortingOrder` int(11) NOT NULL,
  `BusinessId` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `reasons`
--

CREATE TABLE `reasons` (
  `Id` int(11) NOT NULL,
  `Description` varchar(255) NOT NULL,
  `BusinessId` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `reasons`
--

INSERT INTO `reasons` (`Id`, `Description`, `BusinessId`) VALUES
(1, 'Dit is onze aanbieding voor deze maand ', 1);

-- --------------------------------------------------------

--
-- Table structure for table `repeatinginvoice`
--

CREATE TABLE `repeatinginvoice` (
  `Id` int(11) NOT NULL,
  `InvoiceDate` int(11) NOT NULL DEFAULT 0,
  `TimePeriod` varchar(255) NOT NULL DEFAULT '0',
  `PaymentCondition` varchar(255) NOT NULL DEFAULT '0',
  `TermOfPayment` int(11) NOT NULL DEFAULT 0,
  `ContactId` int(11) NOT NULL DEFAULT 0,
  `InvoiceDescription` varchar(255) NOT NULL DEFAULT '0',
  `InvoiceRules` longblob NOT NULL,
  `CustomerId` int(11) NOT NULL,
  `BusinessId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `repeatinginvoice`
--

INSERT INTO `repeatinginvoice` (`Id`, `InvoiceDate`, `TimePeriod`, `PaymentCondition`, `TermOfPayment`, `ContactId`, `InvoiceDescription`, `InvoiceRules`, `CustomerId`, `BusinessId`) VALUES
(1, 1570658400, '7 days', 'nog te betalen ', 14, 0, 'Deze factuur is voor artikel 9', 0x613a313a7b693a313b4f3a383a22737464436c617373223a383a7b733a31333a2241727469636c654e756d626572223b733a313a2239223b733a31383a2241727469636c654465736372697074696f6e223b733a383a226d696e6920706320223b733a363a22446f6d61696e223b733a32333a222d2053656c6563746565722065656e206f70746965202d223b733a363a22416d6f756e74223b733a313a2232223b733a31303a2253616c65735072696365223b733a323a223330223b733a383a22446973636f756e74223b733a313a2238223b733a333a22546178223b733a323a223231223b733a353a22546f74616c223b733a353a2235352e3230223b7d7d, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `salesordercustomfield`
--

CREATE TABLE `salesordercustomfield` (
  `Id` int(10) UNSIGNED NOT NULL,
  `SalesOrderId` int(11) NOT NULL,
  `Key` varchar(255) NOT NULL,
  `Value` varchar(255) NOT NULL,
  `BusinessId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `salesorderrules`
--

CREATE TABLE `salesorderrules` (
  `Id` int(11) NOT NULL,
  `SalesOrderId` int(11) NOT NULL,
  `OrderNumber` varchar(255) NOT NULL,
  `ArticleC` varchar(255) NOT NULL,
  `EanCode` varchar(255) DEFAULT NULL,
  `Amount` decimal(10,2) NOT NULL,
  `Description` varchar(255) NOT NULL,
  `inkoopprijs` decimal(11,2) NOT NULL,
  `Price` decimal(11,2) NOT NULL,
  `Discount` decimal(11,2) NOT NULL,
  `Tax` int(11) NOT NULL,
  `verlegd` decimal(11,2) NOT NULL,
  `Total` decimal(11,2) NOT NULL,
  `MetaData` mediumblob DEFAULT NULL,
  `CustomerId` int(11) DEFAULT NULL,
  `BusinessId` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `salesorderrules`
--

INSERT INTO `salesorderrules` (`Id`, `SalesOrderId`, `OrderNumber`, `ArticleC`, `EanCode`, `Amount`, `Description`, `inkoopprijs`, `Price`, `Discount`, `Tax`, `verlegd`, `Total`, `MetaData`, `CustomerId`, `BusinessId`) VALUES
(1, 1, 'VKO103297', '8', '', '10.00', 'mini pc ', '0.00', '450.00', '200.00', 21, '0.00', '-4500.00', NULL, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `salesorders`
--

CREATE TABLE `salesorders` (
  `Id` int(11) NOT NULL,
  `OrderNumber` varchar(255) NOT NULL,
  `TotalEx` decimal(11,2) NOT NULL,
  `TotalIn` decimal(11,2) NOT NULL,
  `TotalTax21` decimal(11,2) NOT NULL,
  `TotalExTax21` decimal(11,2) NOT NULL,
  `TotalTax6` decimal(11,2) NOT NULL,
  `TotalExTax6` decimal(11,2) NOT NULL,
  `TotalExTax0` decimal(11,2) NOT NULL,
  `TotalIn21` decimal(11,2) NOT NULL,
  `TotalIn6` decimal(11,2) NOT NULL,
  `btw_verleg` varchar(255) NOT NULL,
  `verleg_21` varchar(255) NOT NULL,
  `verleg_6` varchar(255) NOT NULL,
  `btw_in0` varchar(255) NOT NULL,
  `totaal_in0` varchar(255) NOT NULL DEFAULT '0,00',
  `inkoopprijs` varchar(255) NOT NULL,
  `WorkOrder` varchar(255) NOT NULL DEFAULT '0',
  `OrderDate` date DEFAULT NULL,
  `contact` varchar(255) NOT NULL,
  `Note` text DEFAULT NULL,
  `HeadCustomerId` int(11) NOT NULL,
  `CustomerId` int(11) DEFAULT NULL,
  `CompanyName` varchar(255) DEFAULT NULL,
  `FrontName` varchar(255) DEFAULT NULL,
  `Insertion` varchar(255) DEFAULT NULL,
  `LastName` varchar(255) DEFAULT NULL,
  `Address` varchar(255) DEFAULT NULL,
  `AddressNumber` varchar(255) DEFAULT NULL,
  `AddressAddition` varchar(255) DEFAULT NULL,
  `ZipCode` varchar(255) DEFAULT NULL,
  `City` varchar(255) DEFAULT NULL,
  `Country` varchar(255) DEFAULT NULL,
  `MailAddress` varchar(225) DEFAULT NULL,
  `PaymentCondition` varchar(255) DEFAULT NULL,
  `TermOfPayment` int(11) DEFAULT NULL,
  `Seller_id` int(11) NOT NULL,
  `Transport_id` int(11) NOT NULL,
  `Reference` text DEFAULT NULL,
  `Colli` varchar(225) DEFAULT NULL,
  `Invoiced` int(11) NOT NULL DEFAULT 0,
  `Printed` tinyint(1) NOT NULL DEFAULT 0,
  `BusinessId` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `salesorders`
--

INSERT INTO `salesorders` (`Id`, `OrderNumber`, `TotalEx`, `TotalIn`, `TotalTax21`, `TotalExTax21`, `TotalTax6`, `TotalExTax6`, `TotalExTax0`, `TotalIn21`, `TotalIn6`, `btw_verleg`, `verleg_21`, `verleg_6`, `btw_in0`, `totaal_in0`, `inkoopprijs`, `WorkOrder`, `OrderDate`, `contact`, `Note`, `HeadCustomerId`, `CustomerId`, `CompanyName`, `FrontName`, `Insertion`, `LastName`, `Address`, `AddressNumber`, `AddressAddition`, `ZipCode`, `City`, `Country`, `MailAddress`, `PaymentCondition`, `TermOfPayment`, `Seller_id`, `Transport_id`, `Reference`, `Colli`, `Invoiced`, `Printed`, `BusinessId`) VALUES
(1, 'VKO103297', '-4500.00', '-5445.00', '-945.00', '-4500.00', '0.00', '0.00', '0.00', '-5445.00', '0.00', '', '', '', '', '0,00', '', '0', '2019-10-10', '', '', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, '', '', 0, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `seller`
--

CREATE TABLE `seller` (
  `Seller_id` int(11) NOT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `Client_id` varchar(255) DEFAULT NULL,
  `Street` varchar(255) DEFAULT NULL,
  `House_number` varchar(255) DEFAULT NULL,
  `Number_addition` varchar(255) DEFAULT NULL,
  `Zip_code` varchar(255) DEFAULT NULL,
  `City` varchar(255) DEFAULT NULL,
  `Country` varchar(255) DEFAULT NULL,
  `Default_transport` int(11) DEFAULT NULL,
  `Only_option` int(11) NOT NULL,
  `Phone` varchar(255) DEFAULT NULL,
  `Fax` varchar(255) DEFAULT NULL,
  `Mail` varchar(255) DEFAULT NULL,
  `Website` varchar(255) DEFAULT NULL,
  `Facebook` varchar(255) DEFAULT NULL,
  `Twitter` varchar(255) DEFAULT NULL,
  `Import` varchar(255) DEFAULT NULL,
  `BusinessId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE `status` (
  `Id` int(11) NOT NULL,
  `Color` varchar(255) NOT NULL DEFAULT '#000000',
  `Description` varchar(255) NOT NULL,
  `Order` int(11) NOT NULL DEFAULT 0,
  `ProgressPercentage` decimal(11,2) NOT NULL,
  `BusinessId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`Id`, `Color`, `Description`, `Order`, `ProgressPercentage`, `BusinessId`) VALUES
(1, '000000', 'Test', 1, '0.00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `Id` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `StreetName` varchar(255) NOT NULL,
  `StreetNumber` int(11) NOT NULL,
  `StreetAddition` varchar(255) NOT NULL,
  `ZipCode` varchar(255) NOT NULL,
  `City` varchar(255) NOT NULL,
  `Country` varchar(255) NOT NULL,
  `PhoneNumber` varchar(255) NOT NULL,
  `Fax` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Website` varchar(255) NOT NULL,
  `BTW` varchar(255) NOT NULL,
  `KVK` varchar(255) NOT NULL,
  `IBAN` varchar(255) NOT NULL,
  `PaymentCondition` varchar(255) NOT NULL,
  `TermOfPayment` varchar(255) NOT NULL,
  `Note` longblob NOT NULL,
  `BusinessId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `systemmanagementgroup`
--

CREATE TABLE `systemmanagementgroup` (
  `Id` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Password` varchar(255) DEFAULT NULL,
  `Type` varchar(255) NOT NULL,
  `Comments` longblob NOT NULL,
  `CustomerId` int(11) NOT NULL DEFAULT 0,
  `BusinessId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `systemmanagementhardware`
--

CREATE TABLE `systemmanagementhardware` (
  `Id` int(11) NOT NULL,
  `Kind` enum('1','2','3','4','5','6','7','8','9') NOT NULL,
  `Brand` varchar(255) NOT NULL,
  `Type` varchar(255) NOT NULL,
  `Processor` varchar(255) NOT NULL,
  `Memory` varchar(255) NOT NULL,
  `OperatingSystem` varchar(255) NOT NULL,
  `HardDisks` varchar(255) NOT NULL,
  `MacAddress1` varchar(255) NOT NULL,
  `MacAddress2` varchar(255) NOT NULL,
  `SerialNumber` varchar(255) NOT NULL,
  `Hostname` varchar(255) NOT NULL,
  `IpAddress` varchar(255) NOT NULL,
  `Comments` longblob NOT NULL,
  `CustomerId` int(11) NOT NULL DEFAULT 0,
  `BusinessId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `systemmanagementinternetdata`
--

CREATE TABLE `systemmanagementinternetdata` (
  `Id` int(11) NOT NULL,
  `Provider` varchar(255) NOT NULL,
  `Type` varchar(255) NOT NULL,
  `IpRange` varchar(255) NOT NULL,
  `SubnetMasker` varchar(255) NOT NULL,
  `DefaultGateway` varchar(255) NOT NULL,
  `PrimaryDns` varchar(255) NOT NULL,
  `SecondaryDns` varchar(255) NOT NULL,
  `IpAddress` varchar(255) NOT NULL,
  `Username` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `VPI` varchar(255) NOT NULL,
  `Speed` varchar(255) NOT NULL,
  `Note` longblob NOT NULL,
  `CustomerId` int(11) NOT NULL DEFAULT 0,
  `BusinessId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `systemmanagementlogonscript`
--

CREATE TABLE `systemmanagementlogonscript` (
  `Id` int(11) NOT NULL,
  `FileName` varchar(255) NOT NULL,
  `NetworkName` varchar(255) NOT NULL,
  `LocationServer` varchar(255) NOT NULL,
  `Script` longblob NOT NULL,
  `Comments` longblob NOT NULL,
  `CustomerId` int(11) NOT NULL DEFAULT 0,
  `BusinessId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `systemmanagementnetworkinformation`
--

CREATE TABLE `systemmanagementnetworkinformation` (
  `Id` int(11) NOT NULL,
  `IpRange` varchar(255) DEFAULT NULL,
  `SubnetMasker` varchar(255) DEFAULT NULL,
  `DefaultGateway` varchar(255) DEFAULT NULL,
  `PrimaryDns` varchar(255) DEFAULT NULL,
  `SecondaryDns` varchar(255) DEFAULT NULL,
  `DnsForward1` varchar(255) DEFAULT NULL,
  `DnsForward2` varchar(255) DEFAULT NULL,
  `SmtpServer1` varchar(255) DEFAULT NULL,
  `SmtpServer2` varchar(255) DEFAULT NULL,
  `DhcpRange10` varchar(255) DEFAULT NULL,
  `DhcpRange11` varchar(255) DEFAULT NULL,
  `DhcpRange20` varchar(255) DEFAULT NULL,
  `DhcpRange21` varchar(255) DEFAULT NULL,
  `Note` longblob DEFAULT NULL,
  `CustomerId` int(11) DEFAULT NULL,
  `BusinessId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `systemmanagementshare`
--

CREATE TABLE `systemmanagementshare` (
  `Id` int(11) NOT NULL,
  `DriveLetter` varchar(255) NOT NULL,
  `Description` varchar(255) NOT NULL,
  `NetworkName` varchar(255) NOT NULL,
  `LocationServer` varchar(255) NOT NULL,
  `Permission` varchar(255) NOT NULL,
  `Comments` longblob NOT NULL,
  `CustomerId` int(11) NOT NULL DEFAULT 0,
  `BusinessId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `systemmanagementsoftware`
--

CREATE TABLE `systemmanagementsoftware` (
  `Id` int(11) NOT NULL,
  `Developer` varchar(255) NOT NULL,
  `ProductName` varchar(255) NOT NULL,
  `KindSoftware` varchar(255) NOT NULL,
  `LicenseNumber` varchar(255) NOT NULL,
  `SupplierName` varchar(255) NOT NULL,
  `SupplierPhonenumber` varchar(255) NOT NULL,
  `SupplierWebsite` varchar(255) NOT NULL,
  `Comments` longblob NOT NULL,
  `CustomerId` int(11) DEFAULT NULL,
  `BusinessId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `systemmanagementuser`
--

CREATE TABLE `systemmanagementuser` (
  `Id` int(11) NOT NULL,
  `FirstName` varchar(255) NOT NULL,
  `Insertion` varchar(255) NOT NULL,
  `LastName` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Username` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `ComputerName` varchar(255) NOT NULL,
  `Groups` varchar(255) NOT NULL,
  `Users` varchar(255) NOT NULL,
  `Comments` longblob NOT NULL,
  `ExchangeUsername` varchar(255) NOT NULL,
  `ExchangePassword` varchar(255) NOT NULL,
  `CustomerId` int(11) NOT NULL DEFAULT 0,
  `BusinessId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ticket`
--

CREATE TABLE `ticket` (
  `Id` int(11) NOT NULL,
  `Number` varchar(255) NOT NULL,
  `Description` varchar(255) NOT NULL,
  `Priority` int(11) NOT NULL DEFAULT 2 COMMENT '1: Laag, 2: Gemiddeld, 3: hoog.',
  `Status` int(11) NOT NULL,
  `CustomerNotification` longblob NOT NULL,
  `LatestTicketRule` int(11) NOT NULL,
  `Prognosis` decimal(11,2) NOT NULL,
  `PhaseId` int(11) DEFAULT NULL,
  `CustomerId` int(11) NOT NULL,
  `BusinessId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ticket`
--

INSERT INTO `ticket` (`Id`, `Number`, `Description`, `Priority`, `Status`, `CustomerNotification`, `LatestTicketRule`, `Prognosis`, `PhaseId`, `CustomerId`, `BusinessId`) VALUES
(8, 'I1910-1615-5118', 'werwer', 2, 0, '', 1, '0.00', NULL, 1, 1),
(9, 'I1910-1709-0548', 'sfd', 2, 0, '', 2, '0.00', NULL, 1, 1),
(10, 'I1910-1711-2522', 'first ticket ', 2, 0, '', 3, '0.00', NULL, 1, 1),
(11, 'I1910-2411-1355', 'test', 2, 0, '', 4, '0.00', NULL, 1, 1),
(12, 'I1910-3016-4730', 'Ticket omschrijving', 2, 0, '', 5, '0.00', NULL, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `ticketproduct`
--

CREATE TABLE `ticketproduct` (
  `Id` int(11) NOT NULL,
  `TicketId` int(11) NOT NULL DEFAULT 0,
  `ArticleC` varchar(255) DEFAULT '0',
  `EanCode` varchar(225) DEFAULT NULL,
  `Amount` decimal(11,2) DEFAULT 0.00,
  `Description` varchar(255) DEFAULT '0',
  `Price` decimal(11,2) DEFAULT 0.00,
  `Discount` decimal(11,2) DEFAULT 0.00,
  `Tax` int(11) DEFAULT 0,
  `Total` decimal(11,2) DEFAULT 0.00,
  `CustomerId` int(11) DEFAULT 0,
  `BusinessId` int(11) DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ticketrules`
--

CREATE TABLE `ticketrules` (
  `Id` int(11) NOT NULL,
  `TicketId` int(11) NOT NULL,
  `Number` varchar(255) NOT NULL,
  `UserId` varchar(255) NOT NULL,
  `UserIdLink` varchar(255) NOT NULL,
  `ContactId` int(11) NOT NULL,
  `StartWork` int(11) NOT NULL,
  `EndWork` int(11) NOT NULL,
  `TotalWork` decimal(10,2) NOT NULL,
  `Date` int(11) NOT NULL,
  `Status` varchar(255) NOT NULL,
  `NatureOfWorkId` int(11) NOT NULL,
  `CustomerNotification` longblob NOT NULL,
  `ActionUser` longblob NOT NULL,
  `InternalNote` longblob NOT NULL,
  `administratief` varchar(255) NOT NULL DEFAULT 'factureren',
  `ContactMomentId` int(11) NOT NULL,
  `CustomerId` int(11) NOT NULL,
  `BusinessId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ticketrules`
--

INSERT INTO `ticketrules` (`Id`, `TicketId`, `Number`, `UserId`, `UserIdLink`, `ContactId`, `StartWork`, `EndWork`, `TotalWork`, `Date`, `Status`, `NatureOfWorkId`, `CustomerNotification`, `ActionUser`, `InternalNote`, `administratief`, `ContactMomentId`, `CustomerId`, `BusinessId`) VALUES
(1, 8, 'I1910-1615-5118', '1', '1', 2, 1571233860, 1571233860, '0.00', 1571176800, '1', 1, '', '', '', 'factureren', 2, 1, 1),
(2, 9, 'I1910-1709-0548', '1', '1', 2, 1571295900, 1571295900, '0.00', 1571263200, '1', 1, '', '', '', 'factureren', 2, 1, 1),
(3, 10, 'I1910-1711-2522', '1', '1', 2, 1571304300, 1571304300, '0.00', 1571263200, '1', 1, '', '', '', 'factureren', 2, 1, 1),
(4, 11, 'I1910-2411-1355', '1', '1', 2, 1571908380, 1571908380, '0.00', 1571868000, '1', 1, '', '', '', 'factureren', 2, 1, 1),
(5, 12, 'I1910-3016-4730', '1', '1', 2, 1572450420, 1572450420, '0.00', 1572390000, '1', 1, '', '', '', 'factureren', 2, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `transporter`
--

CREATE TABLE `transporter` (
  `Transporter_id` int(11) NOT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `Client_id` varchar(255) DEFAULT NULL,
  `Street` varchar(255) DEFAULT NULL,
  `House_number` varchar(255) DEFAULT NULL,
  `Number_addition` varchar(255) DEFAULT NULL,
  `Zip_code` varchar(255) DEFAULT NULL,
  `City` varchar(255) DEFAULT NULL,
  `Country` varchar(255) DEFAULT NULL,
  `Phone` varchar(255) DEFAULT NULL,
  `Fax` varchar(255) DEFAULT NULL,
  `Mail` varchar(255) DEFAULT NULL,
  `Website` varchar(255) DEFAULT NULL,
  `Facebook` varchar(255) DEFAULT NULL,
  `Twitter` varchar(255) DEFAULT NULL,
  `BusinessId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `transporter_import`
--

CREATE TABLE `transporter_import` (
  `Id` int(11) NOT NULL,
  `TransporterId` int(11) NOT NULL,
  `Import` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `Id` int(11) NOT NULL,
  `Username` varchar(255) NOT NULL,
  `FirstName` varchar(255) NOT NULL,
  `Insertion` varchar(255) NOT NULL,
  `LastName` varchar(255) NOT NULL,
  `Mobile` varchar(255) NOT NULL,
  `BSN` int(10) NOT NULL DEFAULT 0,
  `Salt` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Level` int(2) NOT NULL DEFAULT 1,
  `Activated` enum('1','0') NOT NULL,
  `CustomerManagement` enum('1','0') NOT NULL DEFAULT '1',
  `Tab_CContacts` enum('1','0') NOT NULL DEFAULT '0',
  `Tab_CSalesOrders` enum('1','0') NOT NULL DEFAULT '0',
  `Tab_CPurchaseOrders` enum('1','0') NOT NULL DEFAULT '0',
  `Tab_CInvoice` enum('1','0') NOT NULL DEFAULT '0',
  `Tab_CQuotations` enum('1','0') NOT NULL DEFAULT '0',
  `Tab_CWork` enum('1','0') NOT NULL DEFAULT '0',
  `Tab_CPriceAgr` enum('1','0') NOT NULL DEFAULT '0',
  `Tab_CRepeatingInv` enum('1','0') NOT NULL DEFAULT '0',
  `BusinessId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`Id`, `Username`, `FirstName`, `Insertion`, `LastName`, `Mobile`, `BSN`, `Salt`, `Password`, `Email`, `Level`, `Activated`, `CustomerManagement`, `Tab_CContacts`, `Tab_CSalesOrders`, `Tab_CPurchaseOrders`, `Tab_CInvoice`, `Tab_CQuotations`, `Tab_CWork`, `Tab_CPriceAgr`, `Tab_CRepeatingInv`, `BusinessId`) VALUES
(1, 'alaa', 'Alaa', '', 'Darkashli', '', 0, 'e1ad19b5bbeb19084ae676438fb573c6', 'c7248403024108ddc8118987793e40e6f2b0919952abe6b90d0abf7f9f3e63a2', 'alaa.darkashli@commpro.nl', 15, '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', 1);

-- --------------------------------------------------------

--
-- Table structure for table `warehouse`
--

CREATE TABLE `warehouse` (
  `Id` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Description` longblob NOT NULL,
  `BusinessId` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `webshop`
--

CREATE TABLE `webshop` (
  `Id` int(11) NOT NULL,
  `Description` varchar(225) DEFAULT NULL,
  `Url` varchar(225) NOT NULL,
  `ApiKey` varchar(225) NOT NULL,
  `Secret` varchar(225) NOT NULL,
  `OrderFormat` varchar(10) DEFAULT NULL,
  `Active` tinyint(1) NOT NULL DEFAULT 0,
  `BusinessId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `website`
--

CREATE TABLE `website` (
  `Id` int(11) NOT NULL,
  `DomainName` varchar(255) NOT NULL,
  `IpAddress` varchar(255) NOT NULL,
  `Provider` varchar(255) NOT NULL,
  `Hosting` varchar(255) NOT NULL,
  `HostingUsername` varchar(255) NOT NULL,
  `HostingPassword` varchar(255) NOT NULL,
  `NameServer1` varchar(255) NOT NULL,
  `NameServer2` varchar(255) NOT NULL,
  `FTPHost` varchar(255) NOT NULL,
  `FTPPort` varchar(255) NOT NULL,
  `FTPUsername` varchar(255) NOT NULL,
  `FTPPassword` varchar(255) NOT NULL,
  `CMS` varchar(255) NOT NULL,
  `CMSVersion` varchar(255) NOT NULL,
  `UpdatesInstallatron` int(11) NOT NULL,
  `LatestUpdate` int(11) NOT NULL,
  `GoogleAnalytics` int(11) NOT NULL,
  `GoogleSearch` int(11) NOT NULL,
  `Extentions` longblob NOT NULL,
  `CustomerId` int(11) NOT NULL DEFAULT 0,
  `BusinessId` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `woocommerceorderimports`
--

CREATE TABLE `woocommerceorderimports` (
  `Id` int(10) UNSIGNED NOT NULL,
  `WebshopId` int(11) NOT NULL,
  `ImportDate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `year`
--

CREATE TABLE `year` (
  `Id` int(11) NOT NULL,
  `Year` year(4) NOT NULL,
  `BusinessId` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attachments`
--
ALTER TABLE `attachments`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `business`
--
ALTER TABLE `business`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `ci_sessions`
--
ALTER TABLE `ci_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ci_sessions_timestamp` (`timestamp`);

--
-- Indexes for table `contactmoment`
--
ALTER TABLE `contactmoment`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `contactss`
--
ALTER TABLE `contactss`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `defaulttexts`
--
ALTER TABLE `defaulttexts`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `domain`
--
ALTER TABLE `domain`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `importtype`
--
ALTER TABLE `importtype`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `invoicecustomfield`
--
ALTER TABLE `invoicecustomfield`
  ADD KEY `Id` (`Id`);

--
-- Indexes for table `invoicepayments`
--
ALTER TABLE `invoicepayments`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `invoicereminders`
--
ALTER TABLE `invoicereminders`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `invoicerules`
--
ALTER TABLE `invoicerules`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `invoicerulessupplier`
--
ALTER TABLE `invoicerulessupplier`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `invoicesupplier`
--
ALTER TABLE `invoicesupplier`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `invoicesupplierpayments`
--
ALTER TABLE `invoicesupplierpayments`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `natureofwork`
--
ALTER TABLE `natureofwork`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `notificationmessage`
--
ALTER TABLE `notificationmessage`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `paymentconditions`
--
ALTER TABLE `paymentconditions`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `priceagreement`
--
ALTER TABLE `priceagreement`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `productgroup`
--
ALTER TABLE `productgroup`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `productimage`
--
ALTER TABLE `productimage`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `project`
--
ALTER TABLE `project`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `projectphase`
--
ALTER TABLE `projectphase`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `purchaseorderrules`
--
ALTER TABLE `purchaseorderrules`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `purchaseorders`
--
ALTER TABLE `purchaseorders`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `quotation`
--
ALTER TABLE `quotation`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `quotationcustomfield`
--
ALTER TABLE `quotationcustomfield`
  ADD KEY `Id` (`Id`);

--
-- Indexes for table `quotationfile`
--
ALTER TABLE `quotationfile`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `quotationrules`
--
ALTER TABLE `quotationrules`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `quotationstatus`
--
ALTER TABLE `quotationstatus`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `reasons`
--
ALTER TABLE `reasons`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `repeatinginvoice`
--
ALTER TABLE `repeatinginvoice`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `salesordercustomfield`
--
ALTER TABLE `salesordercustomfield`
  ADD KEY `Id` (`Id`);

--
-- Indexes for table `salesorderrules`
--
ALTER TABLE `salesorderrules`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `salesorders`
--
ALTER TABLE `salesorders`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `seller`
--
ALTER TABLE `seller`
  ADD PRIMARY KEY (`Seller_id`);

--
-- Indexes for table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `systemmanagementgroup`
--
ALTER TABLE `systemmanagementgroup`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `systemmanagementhardware`
--
ALTER TABLE `systemmanagementhardware`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `systemmanagementinternetdata`
--
ALTER TABLE `systemmanagementinternetdata`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `systemmanagementlogonscript`
--
ALTER TABLE `systemmanagementlogonscript`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `systemmanagementnetworkinformation`
--
ALTER TABLE `systemmanagementnetworkinformation`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `systemmanagementshare`
--
ALTER TABLE `systemmanagementshare`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `systemmanagementsoftware`
--
ALTER TABLE `systemmanagementsoftware`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `systemmanagementuser`
--
ALTER TABLE `systemmanagementuser`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `ticket`
--
ALTER TABLE `ticket`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `ticketproduct`
--
ALTER TABLE `ticketproduct`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `ticketrules`
--
ALTER TABLE `ticketrules`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `transporter`
--
ALTER TABLE `transporter`
  ADD PRIMARY KEY (`Transporter_id`);

--
-- Indexes for table `transporter_import`
--
ALTER TABLE `transporter_import`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `BusinessId` (`BusinessId`);

--
-- Indexes for table `warehouse`
--
ALTER TABLE `warehouse`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `webshop`
--
ALTER TABLE `webshop`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `website`
--
ALTER TABLE `website`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `woocommerceorderimports`
--
ALTER TABLE `woocommerceorderimports`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `year`
--
ALTER TABLE `year`
  ADD PRIMARY KEY (`Id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attachments`
--
ALTER TABLE `attachments`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `business`
--
ALTER TABLE `business`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `contactmoment`
--
ALTER TABLE `contactmoment`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `contactss`
--
ALTER TABLE `contactss`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `defaulttexts`
--
ALTER TABLE `defaulttexts`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `domain`
--
ALTER TABLE `domain`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `importtype`
--
ALTER TABLE `importtype`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoice`
--
ALTER TABLE `invoice`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `invoicecustomfield`
--
ALTER TABLE `invoicecustomfield`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoicepayments`
--
ALTER TABLE `invoicepayments`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoicereminders`
--
ALTER TABLE `invoicereminders`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoicerules`
--
ALTER TABLE `invoicerules`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `invoicerulessupplier`
--
ALTER TABLE `invoicerulessupplier`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoicesupplier`
--
ALTER TABLE `invoicesupplier`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoicesupplierpayments`
--
ALTER TABLE `invoicesupplierpayments`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `natureofwork`
--
ALTER TABLE `natureofwork`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `notificationmessage`
--
ALTER TABLE `notificationmessage`
  MODIFY `Id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `paymentconditions`
--
ALTER TABLE `paymentconditions`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `priceagreement`
--
ALTER TABLE `priceagreement`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `productgroup`
--
ALTER TABLE `productgroup`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `productimage`
--
ALTER TABLE `productimage`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `project`
--
ALTER TABLE `project`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `projectphase`
--
ALTER TABLE `projectphase`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchaseorderrules`
--
ALTER TABLE `purchaseorderrules`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchaseorders`
--
ALTER TABLE `purchaseorders`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quotation`
--
ALTER TABLE `quotation`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=146;

--
-- AUTO_INCREMENT for table `quotationcustomfield`
--
ALTER TABLE `quotationcustomfield`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quotationfile`
--
ALTER TABLE `quotationfile`
  MODIFY `Id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `quotationrules`
--
ALTER TABLE `quotationrules`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=255;

--
-- AUTO_INCREMENT for table `quotationstatus`
--
ALTER TABLE `quotationstatus`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reasons`
--
ALTER TABLE `reasons`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `repeatinginvoice`
--
ALTER TABLE `repeatinginvoice`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `salesordercustomfield`
--
ALTER TABLE `salesordercustomfield`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `salesorderrules`
--
ALTER TABLE `salesorderrules`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `salesorders`
--
ALTER TABLE `salesorders`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `seller`
--
ALTER TABLE `seller`
  MODIFY `Seller_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `status`
--
ALTER TABLE `status`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `systemmanagementgroup`
--
ALTER TABLE `systemmanagementgroup`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `systemmanagementhardware`
--
ALTER TABLE `systemmanagementhardware`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `systemmanagementinternetdata`
--
ALTER TABLE `systemmanagementinternetdata`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `systemmanagementlogonscript`
--
ALTER TABLE `systemmanagementlogonscript`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `systemmanagementnetworkinformation`
--
ALTER TABLE `systemmanagementnetworkinformation`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `systemmanagementshare`
--
ALTER TABLE `systemmanagementshare`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `systemmanagementsoftware`
--
ALTER TABLE `systemmanagementsoftware`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `systemmanagementuser`
--
ALTER TABLE `systemmanagementuser`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ticket`
--
ALTER TABLE `ticket`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `ticketproduct`
--
ALTER TABLE `ticketproduct`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ticketrules`
--
ALTER TABLE `ticketrules`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `transporter`
--
ALTER TABLE `transporter`
  MODIFY `Transporter_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transporter_import`
--
ALTER TABLE `transporter_import`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `warehouse`
--
ALTER TABLE `warehouse`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `webshop`
--
ALTER TABLE `webshop`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `website`
--
ALTER TABLE `website`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `woocommerceorderimports`
--
ALTER TABLE `woocommerceorderimports`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `year`
--
ALTER TABLE `year`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
