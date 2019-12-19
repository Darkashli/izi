<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Initial extends CI_Migration {

	public function up()
	{
		$this->db->query("SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\"");
		$this->db->query("SET AUTOCOMMIT = 0");
		$this->db->query("START TRANSACTION");
		$this->db->query("SET time_zone = \"+00:00\"");
		$this->db->query("DROP TABLE IF EXISTS `Attachments`");
		$this->db->query("CREATE TABLE IF NOT EXISTS `Attachments` (
		  `Id` int(11) NOT NULL AUTO_INCREMENT,
		  `TicketId` int(11) DEFAULT NULL,
		  `TicketRuleId` int(11) NOT NULL,
		  `DisplayName` varchar(255) NOT NULL,
		  `Name` varchar(255) NOT NULL,
		  `CustomerId` int(11) NOT NULL,
		  `BusinessId` int(11) NOT NULL,
		  PRIMARY KEY (`Id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1");
		$this->db->query("DROP TABLE IF EXISTS `Business`");
		$this->db->query("CREATE TABLE IF NOT EXISTS `Business` (
		  `Id` int(11) NOT NULL AUTO_INCREMENT,
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
		  `ReminderText` longblob,
		  `DunningText` longblob,
		  `ImportType` longblob NOT NULL,
		  `InvoiceNumber` int(11) NOT NULL DEFAULT '0',
		  `SalesOrderNumber` int(11) NOT NULL,
		  `PurchaseOrderNumber` int(11) NOT NULL DEFAULT '0',
		  `PurchaseNumber` int(11) NOT NULL DEFAULT '0',
		  `QuotationNumber` int(11) NOT NULL DEFAULT '0',
		  `ProjectNumber` int(11) NOT NULL DEFAULT '0',
		  `WorkEmailTextCU` longblob,
		  `WorkEmailTextBC` longblob,
		  `WorkEmailTextCC` longblob,
		  `NewUserMailText` longblob,
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
		  PRIMARY KEY (`Id`)
		) ENGINE=MyISAM DEFAULT CHARSET=latin1");
		$this->db->query("DROP TABLE IF EXISTS `ci_sessions`");
		$this->db->query("CREATE TABLE IF NOT EXISTS `ci_sessions` (
		  `id` varchar(40) NOT NULL,
		  `ip_address` varchar(45) NOT NULL,
		  `timestamp` int(10) UNSIGNED NOT NULL DEFAULT '0',
		  `data` blob NOT NULL,
		  PRIMARY KEY (`id`),
		  KEY `ci_sessions_timestamp` (`timestamp`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1");
		$this->db->query("DROP TABLE IF EXISTS `ContactMoment`");
		$this->db->query("CREATE TABLE IF NOT EXISTS `ContactMoment` (
		  `Id` int(11) NOT NULL AUTO_INCREMENT,
		  `Description` varchar(255) NOT NULL,
		  `BusinessId` int(11) NOT NULL,
		  PRIMARY KEY (`Id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1");
		$this->db->query("DROP TABLE IF EXISTS `Contacts`");
		$this->db->query("CREATE TABLE IF NOT EXISTS `Contacts` (
		  `Id` int(11) NOT NULL AUTO_INCREMENT,
		  `FirstName` varchar(255) NOT NULL,
		  `Insertion` varchar(255) NOT NULL,
		  `LastName` varchar(255) NOT NULL,
		  `Sex` varchar(255) NOT NULL DEFAULT 'male',
		  `Salutation` varchar(255) NOT NULL DEFAULT 'formal',
		  `Email` varchar(255) NOT NULL,
		  `naam` varchar(255) NOT NULL,
		  `PhoneNumber` varchar(255) NOT NULL,
		  `PhoneMobile` varchar(255) NOT NULL,
		  `Function` varchar(255) NOT NULL,
		  `Employed` int(1) NOT NULL DEFAULT '1',
		  `CustomerId` int(11) NOT NULL,
		  `BusinessId` int(11) NOT NULL,
		  PRIMARY KEY (`Id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1");
		$this->db->query("DROP TABLE IF EXISTS `ContactsS`");
		$this->db->query("CREATE TABLE IF NOT EXISTS `ContactsS` (
		  `Id` int(11) NOT NULL AUTO_INCREMENT,
		  `FirstName` varchar(255) NOT NULL,
		  `Insertion` varchar(255) NOT NULL,
		  `LastName` varchar(255) NOT NULL,
		  `PhoneNumber` varchar(255) NOT NULL,
		  `PhoneMobile` varchar(255) NOT NULL,
		  `Email` varchar(255) NOT NULL,
		  `Function` varchar(255) NOT NULL,
		  `SupplierId` int(11) NOT NULL,
		  `BusinessId` int(11) NOT NULL,
		  PRIMARY KEY (`Id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1");
		$this->db->query("DROP TABLE IF EXISTS `Customers`");
		$this->db->query("CREATE TABLE IF NOT EXISTS `Customers` (
		  `Id` int(11) NOT NULL AUTO_INCREMENT,
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
		  `TermOfPayment` int(255) NOT NULL DEFAULT '30',
		  `ToAttention` varchar(255) NOT NULL,
		  `PhonenumberFinancial` varchar(255) NOT NULL,
		  `EmailFinancial` varchar(255) NOT NULL,
		  `HeadCustomerId` int(11) NOT NULL,
		  `Note` longblob NOT NULL,
		  `BusinessId` int(11) NOT NULL,
		  `contactpersoon` varchar(255) NOT NULL,
		  PRIMARY KEY (`Id`)
		) ENGINE=MyISAM DEFAULT CHARSET=latin1");
		$this->db->query("DROP TABLE IF EXISTS `Defaulttexts`");
		$this->db->query("CREATE TABLE IF NOT EXISTS `Defaulttexts` (
		  `Id` int(11) NOT NULL AUTO_INCREMENT,
		  `Titel` varchar(255) NOT NULL,
		  `Text` longblob NOT NULL,
		  `BusinessId` int(11) NOT NULL,
		  PRIMARY KEY (`Id`)
		) ENGINE=MyISAM DEFAULT CHARSET=latin1");
		$this->db->query("DROP TABLE IF EXISTS `Domain`");
		$this->db->query("CREATE TABLE IF NOT EXISTS `Domain` (
		  `Id` int(11) NOT NULL AUTO_INCREMENT,
		  `Name` varchar(255) NOT NULL,
		  `RegisterDate` date NOT NULL,
		  `Customer` int(11) NOT NULL,
		  `Reseller` int(11) DEFAULT NULL,
		  `HasHosting` tinyint(1) NOT NULL,
		  `BusinessId` int(11) NOT NULL,
		  PRIMARY KEY (`Id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1");
		$this->db->query("DROP TABLE IF EXISTS `ImportType`");
		$this->db->query("CREATE TABLE IF NOT EXISTS `ImportType` (
		  `Id` int(11) NOT NULL AUTO_INCREMENT,
		  `Name` varchar(255) DEFAULT '0',
		  PRIMARY KEY (`Id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1");
		$this->db->query("DROP TABLE IF EXISTS `Invoice`");
		$this->db->query("CREATE TABLE IF NOT EXISTS `Invoice` (
		  `Id` int(11) NOT NULL AUTO_INCREMENT,
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
		  `ShortDescription` varchar(255) DEFAULT NULL,
		  `Description` text,
		  `PaymentDate` int(11) NOT NULL,
		  `PaymentCondition` varchar(255) NOT NULL,
		  `TermOfPayment` int(11) NOT NULL,
		  `ImportPaymentRemark` varchar(255) NOT NULL,
		  `contact` varchar(255) DEFAULT NULL,
		  `HeadCustomerId` int(11) NOT NULL,
		  `CustomerId` int(11) DEFAULT NULL,
		  `CompanyName` varchar(255) DEFAULT NULL,
		  `FrontName` varchar(255) DEFAULT NULL,
		  `LastName` varchar(255) DEFAULT NULL,
		  `Address` varchar(255) DEFAULT NULL,
		  `AddressNumber` varchar(255) DEFAULT NULL,
		  `AddressAddition` varchar(255) DEFAULT NULL,
		  `ZipCode` varchar(255) DEFAULT NULL,
		  `City` varchar(255) DEFAULT NULL,
		  `Country` varchar(255) DEFAULT NULL,
		  `MailAddress` varchar(225) DEFAULT NULL,
		  `SentPerMail` tinyint(1) NOT NULL DEFAULT '0',
		  `BusinessId` int(11) NOT NULL,
		  PRIMARY KEY (`Id`)
		) ENGINE=MyISAM DEFAULT CHARSET=latin1");
		$this->db->query("DROP TABLE IF EXISTS `InvoicePayments`");
		$this->db->query("CREATE TABLE IF NOT EXISTS `InvoicePayments` (
		  `Id` int(11) NOT NULL AUTO_INCREMENT,
		  `InvoiceId` int(11) NOT NULL,
		  `Amount` decimal(10,2) NOT NULL,
		  `Date` int(11) NOT NULL,
		  `BusinessId` int(11) NOT NULL,
		  PRIMARY KEY (`Id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1");
		$this->db->query("DROP TABLE IF EXISTS `InvoiceRules`");
		$this->db->query("CREATE TABLE IF NOT EXISTS `InvoiceRules` (
		  `Id` int(11) NOT NULL AUTO_INCREMENT,
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
		  `CustomerId` int(11) DEFAULT NULL,
		  `BusinessId` int(11) NOT NULL,
		  PRIMARY KEY (`Id`)
		) ENGINE=MyISAM DEFAULT CHARSET=latin1");
		$this->db->query("DROP TABLE IF EXISTS `InvoiceRulesSupplier`");
		$this->db->query("CREATE TABLE IF NOT EXISTS `InvoiceRulesSupplier` (
		  `Id` int(11) NOT NULL AUTO_INCREMENT,
		  `InvoiceId` int(11) NOT NULL DEFAULT '0',
		  `InvoiceNumber` varchar(255) NOT NULL,
		  `ArticleC` varchar(255) NOT NULL,
		  `Amount` int(11) NOT NULL,
		  `Description` varchar(255) NOT NULL,
		  `Price` decimal(11,2) NOT NULL,
		  `Discount` decimal(11,2) NOT NULL,
		  `Tax` int(11) NOT NULL,
		  `Total` decimal(11,2) NOT NULL,
		  `SupplierId` int(11) DEFAULT NULL,
		  `BusinessId` int(11) NOT NULL,
		  PRIMARY KEY (`Id`)
		) ENGINE=MyISAM DEFAULT CHARSET=latin1");
		$this->db->query("DROP TABLE IF EXISTS `InvoiceSupplier`");
		$this->db->query("CREATE TABLE IF NOT EXISTS `InvoiceSupplier` (
		  `Id` int(11) NOT NULL AUTO_INCREMENT,
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
		  `Description` text,
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
		  `LastName` varchar(255) DEFAULT NULL,
		  `Address` varchar(255) DEFAULT NULL,
		  `AddressNumber` varchar(255) DEFAULT NULL,
		  `AddressAddition` varchar(255) DEFAULT NULL,
		  `ZipCode` varchar(255) DEFAULT NULL,
		  `City` varchar(255) DEFAULT NULL,
		  `Country` varchar(255) DEFAULT NULL,
		  `MailAddress` varchar(225) DEFAULT NULL,
		  `BusinessId` int(11) NOT NULL,
		  PRIMARY KEY (`Id`)
		) ENGINE=MyISAM DEFAULT CHARSET=latin1");
		$this->db->query("DROP TABLE IF EXISTS `NatureOfWork`");
		$this->db->query("CREATE TABLE IF NOT EXISTS `NatureOfWork` (
		  `Id` int(11) NOT NULL AUTO_INCREMENT,
		  `Description` varchar(255) NOT NULL,
		  `BusinessId` int(11) NOT NULL,
		  PRIMARY KEY (`Id`)
		) ENGINE=MyISAM DEFAULT CHARSET=latin1");
		$this->db->query("DROP TABLE IF EXISTS `PaymentConditions`");
		$this->db->query("CREATE TABLE IF NOT EXISTS `PaymentConditions` (
		  `Id` int(11) NOT NULL AUTO_INCREMENT,
		  `Name` varchar(225) NOT NULL,
		  `BusinessId` int(11) NOT NULL,
		  PRIMARY KEY (`Id`)
		) ENGINE=MyISAM DEFAULT CHARSET=latin1");
		$this->db->query("DROP TABLE IF EXISTS `PriceAgreement`");
		$this->db->query("CREATE TABLE IF NOT EXISTS `PriceAgreement` (
		  `Id` int(11) NOT NULL AUTO_INCREMENT,
		  `ArticleNumber` varchar(255) NOT NULL DEFAULT '0',
		  `Description` varchar(255) NOT NULL DEFAULT '0',
		  `Price` decimal(11,2) NOT NULL DEFAULT '0.00',
		  `Discount` decimal(11,2) NOT NULL DEFAULT '0.00',
		  `CustomerId` int(11) NOT NULL DEFAULT '0',
		  `BusinessId` int(11) NOT NULL DEFAULT '0',
		  PRIMARY KEY (`Id`)
		) ENGINE=MyISAM DEFAULT CHARSET=latin1");
		$this->db->query("DROP TABLE IF EXISTS `Product`");
		$this->db->query("CREATE TABLE IF NOT EXISTS `Product` (
		  `Id` int(11) NOT NULL AUTO_INCREMENT,
		  `ArticleNumber` varchar(255) NOT NULL,
		  `Description` varchar(255) NOT NULL,
		  `SupplierId` int(11) DEFAULT NULL,
		  `EanCode` varchar(255) NOT NULL,
		  `PurchasePrice` decimal(10,2) NOT NULL,
		  `SalesPrice` decimal(10,2) NOT NULL,
		  `BTW` enum('21','6','0') NOT NULL,
		  `Vvp` decimal(10,2) DEFAULT NULL,
		  `ProductGroup` int(11) NOT NULL,
		  `Active` int(11) NOT NULL DEFAULT '0',
		  `ProductKind` int(11) NOT NULL,
		  `Type` int(11) NOT NULL DEFAULT '0',
		  `Stock` int(11) NOT NULL DEFAULT '0',
		  `StockReadonly` int(11) NOT NULL DEFAULT '0',
		  `NatureOfWork` int(11) NOT NULL,
		  `WarehouseLocation` varchar(255) NOT NULL,
		  `Warehouse` int(11) NOT NULL,
		  `BulkLocation` varchar(255) DEFAULT NULL,
		  `UserId` int(11) NOT NULL,
		  `BusinessId` int(11) NOT NULL,
		  `isShop` tinyint(1) NOT NULL DEFAULT '0',
		  `shopId` int(11) DEFAULT '0',
		  `Woocommerce_Description` varchar(225) DEFAULT NULL,
		  `SoldIndividually` int(11) DEFAULT '0',
		  `WoocommerceInSale` tinyint(1) NOT NULL DEFAULT '0',
		  `SalePrice` decimal(10,2) DEFAULT NULL,
		  `SalePriceDatesTo` date DEFAULT NULL,
		  `SalePriceDatesFrom` date DEFAULT NULL,
		  `Weight` int(11) DEFAULT NULL,
		  `Height` int(11) DEFAULT NULL,
		  `Length` int(11) DEFAULT NULL,
		  `Width` int(11) DEFAULT NULL,
		  `LongDescription` longtext,
		  `Image` varchar(2083) DEFAULT NULL,
		  PRIMARY KEY (`Id`)
		) ENGINE=MyISAM DEFAULT CHARSET=latin1");
		$this->db->query("DROP TABLE IF EXISTS `Productgroup`");
		$this->db->query("CREATE TABLE IF NOT EXISTS `Productgroup` (
		  `Id` int(11) NOT NULL AUTO_INCREMENT,
		  `Name` varchar(255) NOT NULL,
		  `Description` text,
		  `Image` varchar(255) DEFAULT NULL,
		  `BusinessId` int(11) NOT NULL,
		  PRIMARY KEY (`Id`)
		) ENGINE=MyISAM DEFAULT CHARSET=latin1");
		$this->db->query("DROP TABLE IF EXISTS `Project`");
		$this->db->query("CREATE TABLE IF NOT EXISTS `Project` (
		  `Id` int(11) NOT NULL AUTO_INCREMENT,
		  `ProjectNumber` varchar(255) NOT NULL,
		  `Description` varchar(255) NOT NULL,
		  `LongDescription` text NOT NULL,
		  `NatureOfWorkId` int(11) NOT NULL,
		  `CustomerId` int(11) NOT NULL,
		  `BusinessId` int(11) NOT NULL,
		  PRIMARY KEY (`Id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1");
		$this->db->query("DROP TABLE IF EXISTS `ProjectPhase`");
		$this->db->query("CREATE TABLE IF NOT EXISTS `ProjectPhase` (
		  `Id` int(11) NOT NULL AUTO_INCREMENT,
		  `Name` varchar(255) NOT NULL,
		  `ProjectId` int(11) NOT NULL,
		  `BusinessId` int(11) NOT NULL,
		  PRIMARY KEY (`Id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1");
		$this->db->query("DROP TABLE IF EXISTS `PurchaseOrderRules`");
		$this->db->query("CREATE TABLE IF NOT EXISTS `PurchaseOrderRules` (
		  `Id` int(11) NOT NULL AUTO_INCREMENT,
		  `PurchaseOrderRules` int(11) DEFAULT NULL,
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
		  `BusinessId` int(11) NOT NULL,
		  PRIMARY KEY (`Id`)
		) ENGINE=MyISAM DEFAULT CHARSET=latin1");
		$this->db->query("DROP TABLE IF EXISTS `PurchaseOrders`");
		$this->db->query("CREATE TABLE IF NOT EXISTS `PurchaseOrders` (
		  `Id` int(11) NOT NULL AUTO_INCREMENT,
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
		  `OrderDate` int(11) DEFAULT NULL,
		  `contact` varchar(255) NOT NULL,
		  `HeadCustomerId` int(11) NOT NULL,
		  `SupplierId` int(11) DEFAULT NULL,
		  `CompanyName` varchar(255) DEFAULT NULL,
		  `FrontName` varchar(255) DEFAULT NULL,
		  `LastName` varchar(255) DEFAULT NULL,
		  `Address` varchar(255) DEFAULT NULL,
		  `AddressNumber` varchar(255) DEFAULT NULL,
		  `AddressAddition` varchar(255) DEFAULT NULL,
		  `ZipCode` varchar(255) DEFAULT NULL,
		  `City` varchar(255) DEFAULT NULL,
		  `Country` varchar(255) DEFAULT NULL,
		  `MailAddress` varchar(225) DEFAULT NULL,
		  `Reference` text,
		  `Invoiced` int(11) NOT NULL DEFAULT '0',
		  `BusinessId` int(11) NOT NULL,
		  PRIMARY KEY (`Id`)
		) ENGINE=MyISAM DEFAULT CHARSET=latin1");
		$this->db->query("DROP TABLE IF EXISTS `Quotation`");
		$this->db->query("CREATE TABLE IF NOT EXISTS `Quotation` (
		  `Id` int(11) NOT NULL AUTO_INCREMENT,
		  `BusinessId` int(11) NOT NULL,
		  `CustomerId` int(11) NOT NULL,
		  `CustomerName` varchar(255) NOT NULL,
		  `CustomerStreet` varchar(255) NOT NULL,
		  `CustomerHousenumber` varchar(255) NOT NULL,
		  `CustomerHousenumberAddition` varchar(255) NOT NULL,
		  `CustomerZipCode` varchar(255) NOT NULL,
		  `CustomerCity` varchar(255) NOT NULL,
		  `CustomerCountry` varchar(255) NOT NULL,
		  `CreatedDate` date NOT NULL,
		  `ContactId` int(11) NOT NULL,
		  `ContactFirstName` varchar(255) NOT NULL,
		  `ContactInsertion` varchar(255) NOT NULL,
		  `ContactLastName` varchar(255) NOT NULL,
		  `ContactIsMale` tinyint(1) NOT NULL,
		  `ContactSalutation` varchar(255) NOT NULL,
		  `QuotationNumber` varchar(255) NOT NULL,
		  `Subject` varchar(255) NOT NULL,
		  `Reason` text NOT NULL,
		  `ContactDate` date NOT NULL,
		  `WorkDescription` longblob NOT NULL,
		  `WorkAmount` decimal(11,2) NOT NULL,
		  `ProductDescription` longblob,
		  `RecurringDescription` longblob,
		  `ProjectDescription` longblob NOT NULL,
		  `ValidDays` int(11) NOT NULL,
		  `DeliveryTime` varchar(255) NOT NULL,
		  `PaymentConditionId` int(11) NOT NULL,
		  `PaymentTerm` int(11) NOT NULL,
		  `Creator` int(11) NOT NULL,
		  `IsComparison` tinyint(1) NOT NULL DEFAULT '0',
		  `Status` varchar(255) NOT NULL,
		  `Template` varchar(255) DEFAULT NULL,
		  PRIMARY KEY (`Id`)
		) ENGINE=MyISAM DEFAULT CHARSET=latin1");
		$this->db->query("DROP TABLE IF EXISTS `QuotationRules`");
		$this->db->query("CREATE TABLE IF NOT EXISTS `QuotationRules` (
		  `Id` int(11) NOT NULL AUTO_INCREMENT,
		  `BusinessId` int(11) NOT NULL,
		  `QuotationId` int(11) NOT NULL,
		  `ArticleC` varchar(255) NOT NULL,
		  `EanCode` varchar(255) NOT NULL,
		  `ArticleDescription` varchar(255) NOT NULL,
		  `Amount` int(11) NOT NULL,
		  `SalesPrice` decimal(11,2) NOT NULL,
		  `Tax` int(11) NOT NULL,
		  `Type` int(11) NOT NULL,
		  PRIMARY KEY (`Id`)
		) ENGINE=MyISAM DEFAULT CHARSET=latin1");
		$this->db->query("DROP TABLE IF EXISTS `QuotationStatus`");
		$this->db->query("CREATE TABLE IF NOT EXISTS `QuotationStatus` (
		  `Id` int(11) NOT NULL AUTO_INCREMENT,
		  `Description` varchar(255) NOT NULL,
		  `SortingOrder` int(11) NOT NULL,
		  `BusinessId` int(11) NOT NULL,
		  PRIMARY KEY (`Id`)
		) ENGINE=MyISAM DEFAULT CHARSET=latin1");
		$this->db->query("DROP TABLE IF EXISTS `Reasons`");
		$this->db->query("CREATE TABLE IF NOT EXISTS `Reasons` (
		  `Id` int(11) NOT NULL AUTO_INCREMENT,
		  `Description` varchar(255) NOT NULL,
		  `BusinessId` int(11) NOT NULL,
		  PRIMARY KEY (`Id`)
		) ENGINE=MyISAM DEFAULT CHARSET=latin1");
		$this->db->query("DROP TABLE IF EXISTS `Reminders`");
		$this->db->query("CREATE TABLE IF NOT EXISTS `Reminders` (
		  `Id` int(11) NOT NULL AUTO_INCREMENT,
		  `InvoiceId` int(11) NOT NULL,
		  `ReminderDate` varchar(11) NOT NULL,
		  `ReminderType` int(11) NOT NULL,
		  `BusinessId` int(11) NOT NULL,
		  PRIMARY KEY (`Id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1");
		$this->db->query("DROP TABLE IF EXISTS `RepeatingInvoice`");
		$this->db->query("CREATE TABLE IF NOT EXISTS `RepeatingInvoice` (
		  `Id` int(11) NOT NULL AUTO_INCREMENT,
		  `InvoiceDate` int(11) NOT NULL DEFAULT '0',
		  `TimePeriod` varchar(255) NOT NULL DEFAULT '0',
		  `PaymentCondition` varchar(255) NOT NULL DEFAULT '0',
		  `TermOfPayment` int(11) NOT NULL DEFAULT '0',
		  `ContactId` int(11) NOT NULL DEFAULT '0',
		  `InvoiceDescription` varchar(255) NOT NULL DEFAULT '0',
		  `InvoiceRules` longblob NOT NULL,
		  `CustomerId` int(11) NOT NULL,
		  `BusinessId` int(11) NOT NULL,
		  PRIMARY KEY (`Id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1");
		$this->db->query("DROP TABLE IF EXISTS `SalesOrderRules`");
		$this->db->query("CREATE TABLE IF NOT EXISTS `SalesOrderRules` (
		  `Id` int(11) NOT NULL AUTO_INCREMENT,
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
		  `CustomerId` int(11) DEFAULT NULL,
		  `BusinessId` int(11) NOT NULL,
		  PRIMARY KEY (`Id`)
		) ENGINE=MyISAM DEFAULT CHARSET=latin1");
		$this->db->query("DROP TABLE IF EXISTS `SalesOrders`");
		$this->db->query("CREATE TABLE IF NOT EXISTS `SalesOrders` (
		  `Id` int(11) NOT NULL AUTO_INCREMENT,
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
		  `OrderDate` int(11) DEFAULT NULL,
		  `contact` varchar(255) NOT NULL,
		  `HeadCustomerId` int(11) NOT NULL,
		  `CustomerId` int(11) DEFAULT NULL,
		  `CompanyName` varchar(255) DEFAULT NULL,
		  `FrontName` varchar(255) DEFAULT NULL,
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
		  `Reference` text,
		  `Colli` varchar(225) DEFAULT NULL,
		  `Invoiced` int(11) NOT NULL DEFAULT '0',
		  `Printed` tinyint(1) NOT NULL DEFAULT '0',
		  `BusinessId` int(11) NOT NULL,
		  PRIMARY KEY (`Id`)
		) ENGINE=MyISAM DEFAULT CHARSET=latin1");
		$this->db->query("DROP TABLE IF EXISTS `Seller`");
		$this->db->query("CREATE TABLE IF NOT EXISTS `Seller` (
		  `Seller_id` int(11) NOT NULL AUTO_INCREMENT,
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
		  `BusinessId` int(11) NOT NULL,
		  PRIMARY KEY (`Seller_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1");
		$this->db->query("DROP TABLE IF EXISTS `Status`");
		$this->db->query("CREATE TABLE IF NOT EXISTS `Status` (
		  `Id` int(11) NOT NULL AUTO_INCREMENT,
		  `Color` varchar(255) NOT NULL DEFAULT '#000000',
		  `Description` varchar(255) NOT NULL,
		  `Order` int(11) NOT NULL DEFAULT '0',
		  `ProgressPercentage` decimal(11,2) NOT NULL,
		  `BusinessId` int(11) NOT NULL,
		  PRIMARY KEY (`Id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1");
		$this->db->query("DROP TABLE IF EXISTS `Supplier`");
		$this->db->query("CREATE TABLE IF NOT EXISTS `Supplier` (
		  `Id` int(11) NOT NULL AUTO_INCREMENT,
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
		  `BusinessId` int(11) NOT NULL,
		  PRIMARY KEY (`Id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1");
		$this->db->query("DROP TABLE IF EXISTS `SystemManagementGroup`");
		$this->db->query("CREATE TABLE IF NOT EXISTS `SystemManagementGroup` (
		  `Id` int(11) NOT NULL AUTO_INCREMENT,
		  `Name` varchar(255) NOT NULL,
		  `Password` varchar(255) DEFAULT NULL,
		  `Type` varchar(255) NOT NULL,
		  `Comments` longblob NOT NULL,
		  `CustomerId` int(11) NOT NULL DEFAULT '0',
		  `BusinessId` int(11) NOT NULL,
		  PRIMARY KEY (`Id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1");
		$this->db->query("DROP TABLE IF EXISTS `SystemManagementHardware`");
		$this->db->query("CREATE TABLE IF NOT EXISTS `SystemManagementHardware` (
		  `Id` int(11) NOT NULL AUTO_INCREMENT,
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
		  `CustomerId` int(11) NOT NULL DEFAULT '0',
		  `BusinessId` int(11) NOT NULL,
		  PRIMARY KEY (`Id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1");
		$this->db->query("DROP TABLE IF EXISTS `SystemManagementInternetData`");
		$this->db->query("CREATE TABLE IF NOT EXISTS `SystemManagementInternetData` (
		  `Id` int(11) NOT NULL AUTO_INCREMENT,
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
		  `CustomerId` int(11) NOT NULL DEFAULT '0',
		  `BusinessId` int(11) NOT NULL,
		  PRIMARY KEY (`Id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1");
		$this->db->query("DROP TABLE IF EXISTS `SystemManagementLogonScript`");
		$this->db->query("CREATE TABLE IF NOT EXISTS `SystemManagementLogonScript` (
		  `Id` int(11) NOT NULL AUTO_INCREMENT,
		  `FileName` varchar(255) NOT NULL,
		  `NetworkName` varchar(255) NOT NULL,
		  `LocationServer` varchar(255) NOT NULL,
		  `Script` longblob NOT NULL,
		  `Comments` longblob NOT NULL,
		  `CustomerId` int(11) NOT NULL DEFAULT '0',
		  `BusinessId` int(11) NOT NULL,
		  PRIMARY KEY (`Id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1");
		$this->db->query("DROP TABLE IF EXISTS `SystemManagementNetworkInformation`");
		$this->db->query("CREATE TABLE IF NOT EXISTS `SystemManagementNetworkInformation` (
		  `Id` int(11) NOT NULL AUTO_INCREMENT,
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
		  `Note` longblob,
		  `CustomerId` int(11) DEFAULT NULL,
		  `BusinessId` int(11) NOT NULL,
		  PRIMARY KEY (`Id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1");
		$this->db->query("DROP TABLE IF EXISTS `SystemManagementShare`");
		$this->db->query("CREATE TABLE IF NOT EXISTS `SystemManagementShare` (
		  `Id` int(11) NOT NULL AUTO_INCREMENT,
		  `DriveLetter` varchar(255) NOT NULL,
		  `Description` varchar(255) NOT NULL,
		  `NetworkName` varchar(255) NOT NULL,
		  `LocationServer` varchar(255) NOT NULL,
		  `Permission` varchar(255) NOT NULL,
		  `Comments` longblob NOT NULL,
		  `CustomerId` int(11) NOT NULL DEFAULT '0',
		  `BusinessId` int(11) NOT NULL,
		  PRIMARY KEY (`Id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1");
		$this->db->query("DROP TABLE IF EXISTS `SystemManagementSoftware`");
		$this->db->query("CREATE TABLE IF NOT EXISTS `SystemManagementSoftware` (
		  `Id` int(11) NOT NULL AUTO_INCREMENT,
		  `Developer` varchar(255) NOT NULL,
		  `ProductName` varchar(255) NOT NULL,
		  `KindSoftware` varchar(255) NOT NULL,
		  `LicenseNumber` varchar(255) NOT NULL,
		  `SupplierName` varchar(255) NOT NULL,
		  `SupplierPhonenumber` varchar(255) NOT NULL,
		  `SupplierWebsite` varchar(255) NOT NULL,
		  `Comments` longblob NOT NULL,
		  `CustomerId` int(11) DEFAULT NULL,
		  `BusinessId` int(11) NOT NULL,
		  PRIMARY KEY (`Id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1");
		$this->db->query("DROP TABLE IF EXISTS `SystemManagementUser`");
		$this->db->query("CREATE TABLE IF NOT EXISTS `SystemManagementUser` (
		  `Id` int(11) NOT NULL AUTO_INCREMENT,
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
		  `CustomerId` int(11) NOT NULL DEFAULT '0',
		  `BusinessId` int(11) NOT NULL,
		  PRIMARY KEY (`Id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1");
		$this->db->query("DROP TABLE IF EXISTS `Ticket`");
		$this->db->query("CREATE TABLE IF NOT EXISTS `Ticket` (
		  `Id` int(11) NOT NULL AUTO_INCREMENT,
		  `Number` varchar(255) NOT NULL,
		  `Description` varchar(255) NOT NULL,
		  `Priority` int(11) NOT NULL DEFAULT '2' COMMENT '1: Laag, 2: Gemiddeld, 3: hoog.',
		  `Status` int(11) NOT NULL,
		  `CustomerNotification` longblob NOT NULL,
		  `LatestTicketRule` int(11) NOT NULL,
		  `Prognosis` decimal(11,2) NOT NULL,
		  `PhaseId` int(11) DEFAULT NULL,
		  `CustomerId` int(11) NOT NULL,
		  `BusinessId` int(11) NOT NULL,
		  PRIMARY KEY (`Id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1");
		$this->db->query("DROP TABLE IF EXISTS `TicketProduct`");
		$this->db->query("CREATE TABLE IF NOT EXISTS `TicketProduct` (
		  `Id` int(11) NOT NULL AUTO_INCREMENT,
		  `TicketId` int(11) NOT NULL DEFAULT '0',
		  `ArticleC` varchar(255) DEFAULT '0',
		  `EanCode` varchar(225) DEFAULT NULL,
		  `Amount` decimal(11,2) DEFAULT '0.00',
		  `Description` varchar(255) DEFAULT '0',
		  `Price` decimal(11,2) DEFAULT '0.00',
		  `Discount` decimal(11,2) DEFAULT '0.00',
		  `Tax` int(11) DEFAULT '0',
		  `Total` decimal(11,2) DEFAULT '0.00',
		  `CustomerId` int(11) DEFAULT '0',
		  `BusinessId` int(11) DEFAULT '0',
		  PRIMARY KEY (`Id`)
		) ENGINE=MyISAM DEFAULT CHARSET=latin1");
		$this->db->query("DROP TABLE IF EXISTS `TicketRules`");
		$this->db->query("CREATE TABLE IF NOT EXISTS `TicketRules` (
		  `Id` int(11) NOT NULL AUTO_INCREMENT,
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
		  `BusinessId` int(11) NOT NULL,
		  PRIMARY KEY (`Id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1");
		$this->db->query("DROP TABLE IF EXISTS `Transporter`");
		$this->db->query("CREATE TABLE IF NOT EXISTS `Transporter` (
		  `Transporter_id` int(11) NOT NULL AUTO_INCREMENT,
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
		  `BusinessId` int(11) NOT NULL,
		  PRIMARY KEY (`Transporter_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1");
		$this->db->query("DROP TABLE IF EXISTS `Transporter_Import`");
		$this->db->query("CREATE TABLE IF NOT EXISTS `Transporter_Import` (
		  `Id` int(11) NOT NULL AUTO_INCREMENT,
		  `TransporterId` int(11) NOT NULL,
		  `Import` varchar(255) NOT NULL,
		  PRIMARY KEY (`Id`)
		) ENGINE=MyISAM DEFAULT CHARSET=latin1");
		$this->db->query("DROP TABLE IF EXISTS `User`");
		$this->db->query("CREATE TABLE IF NOT EXISTS `User` (
		  `Id` int(11) NOT NULL AUTO_INCREMENT,
		  `Username` varchar(255) NOT NULL,
		  `FirstName` varchar(255) NOT NULL,
		  `Insertion` varchar(255) NOT NULL,
		  `LastName` varchar(255) NOT NULL,
		  `Mobile` varchar(255) NOT NULL,
		  `BSN` int(10) NOT NULL DEFAULT '0',
		  `Salt` varchar(255) NOT NULL,
		  `Password` varchar(255) NOT NULL,
		  `Email` varchar(255) NOT NULL,
		  `Level` int(2) NOT NULL DEFAULT '1',
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
		  `BusinessId` int(11) NOT NULL,
		  PRIMARY KEY (`Id`),
		  KEY `BusinessId` (`BusinessId`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1");
		$this->db->query("DROP TABLE IF EXISTS `Warehouse`");
		$this->db->query("CREATE TABLE IF NOT EXISTS `Warehouse` (
		  `Id` int(11) NOT NULL AUTO_INCREMENT,
		  `Name` varchar(255) NOT NULL,
		  `Description` longblob NOT NULL,
		  `BusinessId` int(11) NOT NULL DEFAULT '0',
		  PRIMARY KEY (`Id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1");
		$this->db->query("DROP TABLE IF EXISTS `Webshop`");
		$this->db->query("CREATE TABLE IF NOT EXISTS `Webshop` (
		  `Id` int(11) NOT NULL AUTO_INCREMENT,
		  `Description` varchar(225) DEFAULT NULL,
		  `Url` varchar(225) NOT NULL,
		  `ApiKey` varchar(225) NOT NULL,
		  `Secret` varchar(225) NOT NULL,
		  `Active` tinyint(1) NOT NULL DEFAULT '0',
		  `BusinessId` int(11) NOT NULL,
		  PRIMARY KEY (`Id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1");
		$this->db->query("DROP TABLE IF EXISTS `Website`");
		$this->db->query("CREATE TABLE IF NOT EXISTS `Website` (
		  `Id` int(11) NOT NULL AUTO_INCREMENT,
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
		  `CustomerId` int(11) NOT NULL DEFAULT '0',
		  `BusinessId` int(11) NOT NULL DEFAULT '0',
		  PRIMARY KEY (`Id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1");
		$this->db->query("DROP TABLE IF EXISTS `Year`");
		$this->db->query("CREATE TABLE IF NOT EXISTS `Year` (
		  `Id` int(11) NOT NULL AUTO_INCREMENT,
		  `Year` year(4) NOT NULL,
		  `BusinessId` int(11) NOT NULL,
		  PRIMARY KEY (`Id`)
		) ENGINE=MyISAM DEFAULT CHARSET=latin1");
		$this->db->query("COMMIT");
	}

	public function down()
	{
		$this->dbforge->drop_table('Attachments', true);
		$this->dbforge->drop_table('Business', true);
		$this->dbforge->drop_table('ci_sessions', true);
		$this->dbforge->drop_table('ContactMoment', true);
		$this->dbforge->drop_table('Contacts', true);
		$this->dbforge->drop_table('ContactsS', true);
		$this->dbforge->drop_table('Customers', true);
		$this->dbforge->drop_table('Defaulttexts', true);
		$this->dbforge->drop_table('Domain', true);
		$this->dbforge->drop_table('ImportType', true);
		$this->dbforge->drop_table('Invoice', true);
		$this->dbforge->drop_table('InvoicePayments', true);
		$this->dbforge->drop_table('InvoiceRules', true);
		$this->dbforge->drop_table('InvoiceRulesSupplier', true);
		$this->dbforge->drop_table('InvoiceSupplier', true);
		// $this->dbforge->drop_table('Migrations', true);
		$this->dbforge->drop_table('NatureOfWork', true);
		$this->dbforge->drop_table('PaymentConditions', true);
		$this->dbforge->drop_table('PriceAgreement', true);
		$this->dbforge->drop_table('Product', true);
		$this->dbforge->drop_table('Productgroup', true);
		$this->dbforge->drop_table('Project', true);
		$this->dbforge->drop_table('ProjectPhase', true);
		$this->dbforge->drop_table('PurchaseOrderRules', true);
		$this->dbforge->drop_table('PurchaseOrders', true);
		$this->dbforge->drop_table('Quotation', true);
		$this->dbforge->drop_table('QuotationRules', true);
		$this->dbforge->drop_table('QuotationStatus', true);
		$this->dbforge->drop_table('Reasons', true);
		$this->dbforge->drop_table('Reminders', true);
		$this->dbforge->drop_table('RepeatingInvoice', true);
		$this->dbforge->drop_table('SalesOrderRules', true);
		$this->dbforge->drop_table('SalesOrders', true);
		$this->dbforge->drop_table('Seller', true);
		$this->dbforge->drop_table('Status', true);
		$this->dbforge->drop_table('Supplier', true);
		$this->dbforge->drop_table('SystemManagementGroup', true);
		$this->dbforge->drop_table('SystemManagementHardware', true);
		$this->dbforge->drop_table('SystemManagementInternetData', true);
		$this->dbforge->drop_table('SystemManagementLogonScript', true);
		$this->dbforge->drop_table('SystemManagementNetworkInformation', true);
		$this->dbforge->drop_table('SystemManagementShare', true);
		$this->dbforge->drop_table('SystemManagementSoftware', true);
		$this->dbforge->drop_table('SystemManagementUser', true);
		$this->dbforge->drop_table('Ticket', true);
		$this->dbforge->drop_table('TicketProduct', true);
		$this->dbforge->drop_table('TicketRules', true);
		$this->dbforge->drop_table('Transporter', true);
		$this->dbforge->drop_table('Transporter_Import', true);
		$this->dbforge->drop_table('User', true);
		$this->dbforge->drop_table('Warehouse', true);
		$this->dbforge->drop_table('Webshop', true);
		$this->dbforge->drop_table('Website', true);
		$this->dbforge->drop_table('Year', true);
	}
}
