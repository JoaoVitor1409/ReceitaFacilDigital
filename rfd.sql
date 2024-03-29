-- --------------------------------------------------------
-- Servidor:                     127.0.0.1
-- Versão do servidor:           8.0.27 - MySQL Community Server - GPL
-- OS do Servidor:               Win64
-- HeidiSQL Versão:              11.3.0.6295
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Copiando estrutura do banco de dados para rfd
CREATE DATABASE IF NOT EXISTS `rfd` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `rfd`;

-- Copiando estrutura para tabela rfd.endereco
CREATE TABLE IF NOT EXISTS `endereco` (
  `EnderecoId` int NOT NULL AUTO_INCREMENT,
  `EnderecoCEP` varchar(9) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `EnderecoUF` varchar(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `EnderecoCidade` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `EnderecoBairro` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `EnderecoLogradouro` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `EnderecoNumero` varchar(5) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `EnderecoComplemento` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`EnderecoId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela rfd.farmacia
CREATE TABLE IF NOT EXISTS `farmacia` (
  `FarmaciaID` int NOT NULL AUTO_INCREMENT,
  `FarmaciaNome` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `FarmaciaCNPJ` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `FarmaciaEmail` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `FarmaciaTelefone` varchar(13) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `FarmaciaSenha` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `FarmaciaAtivo` tinyint(1) NOT NULL DEFAULT '1',
  `EnderecoID` int NOT NULL,
  PRIMARY KEY (`FarmaciaID`),
  KEY `EnderecoID` (`EnderecoID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela rfd.medico
CREATE TABLE IF NOT EXISTS `medico` (
  `MedicoID` int NOT NULL AUTO_INCREMENT,
  `MedicoNome` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `MedicoCRM` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `MedicoDataNasc` date NOT NULL,
  `MedicoEmail` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `MedicoCelular` varchar(14) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `MedicoSenha` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `MedicoAtivo` tinyint(1) NOT NULL DEFAULT '1',
  `EnderecoID` int NOT NULL,
  PRIMARY KEY (`MedicoID`),
  KEY `EnderecoID` (`EnderecoID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela rfd.modelo
CREATE TABLE IF NOT EXISTS `modelo` (
  `ModeloID` int NOT NULL AUTO_INCREMENT,
  `ModeloNome` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `ModeloAtivo` tinyint(1) NOT NULL DEFAULT '1',
  `MedicoId` int NOT NULL,
  PRIMARY KEY (`ModeloID`),
  KEY `MedicoId` (`MedicoId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela rfd.modelo_medicamento
CREATE TABLE IF NOT EXISTS `modelo_medicamento` (
  `MedicamentoID` int NOT NULL AUTO_INCREMENT,
  `MedicamentoDesc` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `MedicamentoDosagem` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `MedicamentoFrequencia` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `MedicamentoObs` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `ModeloID` int NOT NULL,
  PRIMARY KEY (`MedicamentoID`),
  KEY `ModeloID` (`ModeloID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela rfd.paciente
CREATE TABLE IF NOT EXISTS `paciente` (
  `PacienteID` int NOT NULL AUTO_INCREMENT,
  `PacienteCPF` varchar(14) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `PacienteNome` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `PacienteDataNasc` date NOT NULL,
  `PacienteEmail` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `PacienteCelular` varchar(14) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `PacienteSenha` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `PacienteAtivo` tinyint(1) NOT NULL DEFAULT '1',
  `EnderecoID` int NOT NULL,
  PRIMARY KEY (`PacienteID`,`PacienteCPF`),
  KEY `EnderecoID` (`EnderecoID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela rfd.receita
CREATE TABLE IF NOT EXISTS `receita` (
  `ReceitaId` int NOT NULL AUTO_INCREMENT,
  `ReceitaData` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `PacienteCPF` varchar(14) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `PacienteCelular` varchar(14) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `ReceitaAtiva` tinyint(1) NOT NULL DEFAULT '1',
  `MedicoId` int NOT NULL,
  `FarmaciaID` int DEFAULT NULL,
  PRIMARY KEY (`ReceitaId`),
  KEY `MedicoId` (`MedicoId`),
  KEY `FarmaciaID` (`FarmaciaID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela rfd.receita_medicamento
CREATE TABLE IF NOT EXISTS `receita_medicamento` (
  `MedicamentoID` int NOT NULL AUTO_INCREMENT,
  `MedicamentoDesc` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `MedicamentoDosagem` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `MedicamentoFrequencia` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `MedicamentoObs` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `ReceitaId` int NOT NULL,
  PRIMARY KEY (`MedicamentoID`),
  KEY `ReceitaId` (`ReceitaId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

-- Exportação de dados foi desmarcado.

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
