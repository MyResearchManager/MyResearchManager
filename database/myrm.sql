--
-- Banco de Dados: `myresearchmanager`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `Areas`
--

CREATE TABLE IF NOT EXISTS `Areas` (
  `idArea` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`idArea`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `DynamicCells`
--

CREATE TABLE IF NOT EXISTS `DynamicCells` (
  `idDynamicCell` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `column` int(11) NOT NULL,
  `row` int(11) NOT NULL,
  `value` varchar(100) NOT NULL,
  `idDynamicTable` int(11) NOT NULL,
  PRIMARY KEY (`idDynamicCell`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `DynamicTables`
--

CREATE TABLE IF NOT EXISTS `DynamicTables` (
  `idDynamicTable` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(100) NOT NULL,
  `key` varchar(32) NOT NULL,
  `locked` tinyint(1) NOT NULL,
  `lastUpdate` datetime NOT NULL,
  `idSection` int(10) unsigned NOT NULL,
  PRIMARY KEY (`idDynamicTable`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `Files`
--

CREATE TABLE IF NOT EXISTS `Files` (
  `idFile` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `filename` varchar(150) NOT NULL,
  `size` int(10) unsigned NOT NULL,
  `creation` datetime NOT NULL,
  `visible` int(1) NOT NULL,
  `idSection` int(11) NOT NULL,
  PRIMARY KEY (`idFile`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `ImportantDates`
--

CREATE TABLE IF NOT EXISTS `ImportantDates` (
  `idImportantDate` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(100) NOT NULL,
  `datetime` datetime NOT NULL,
  `idSection` int(11) NOT NULL,
  PRIMARY KEY (`idImportantDate`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `Links`
--

CREATE TABLE IF NOT EXISTS `Links` (
  `idLink` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `idSection` int(11) NOT NULL,
  PRIMARY KEY (`idLink`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `Logs`
--

CREATE TABLE IF NOT EXISTS `Logs` (
  `idLog` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `when` datetime NOT NULL,
  `what` varchar(255) NOT NULL,
  PRIMARY KEY (`idLog`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `PublicationFiles`
--

CREATE TABLE IF NOT EXISTS `PublicationFiles` (
  `idPublicationFile` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idPublication` int(10) unsigned NOT NULL,
  `idFile` int(10) unsigned NOT NULL,
  PRIMARY KEY (`idPublicationFile`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `PublicationMembers`
--

CREATE TABLE IF NOT EXISTS `PublicationMembers` (
  `idPublicationMember` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  `idPublication` int(11) NOT NULL,
  PRIMARY KEY (`idPublicationMember`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `Publications`
--

CREATE TABLE IF NOT EXISTS `Publications` (
  `idPublication` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `abstract` text NOT NULL,
  `year` int(11) NOT NULL,
  `journal` varchar(100) NOT NULL,
  `doi` varchar(50) NOT NULL,
  `bibtex` text NOT NULL,
  `filename` varchar(50) NOT NULL,
  `publicFile` tinyint(1) NOT NULL,
  `visible` int(1) NOT NULL,
  `idSection` int(11) NOT NULL,
  PRIMARY KEY (`idPublication`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `Researches`
--

CREATE TABLE IF NOT EXISTS `Researches` (
  `idResearch` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `abstract` text NOT NULL,
  `idArea` int(11) NOT NULL,
  PRIMARY KEY (`idResearch`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `ResearchMembers`
--

CREATE TABLE IF NOT EXISTS `ResearchMembers` (
  `idResearchMember` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idResearch` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  PRIMARY KEY (`idResearchMember`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `SectionMembers`
--

CREATE TABLE IF NOT EXISTS `SectionMembers` (
  `idSectionMember` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idSection` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  PRIMARY KEY (`idSectionMember`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `Sections`
--

CREATE TABLE IF NOT EXISTS `Sections` (
  `idSection` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(250) NOT NULL,
  `idResearch` int(11) NOT NULL,
  PRIMARY KEY (`idSection`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `Users`
--

CREATE TABLE IF NOT EXISTS `Users` (
  `idUser` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `password` varchar(32) NOT NULL,
  `salt` char(4) NOT NULL,
  `email` varchar(100) NOT NULL,
  `confirmationCode` varchar(32) NOT NULL,
  `uhash` varchar(10) NOT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`idUser`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `VCFiles`
--

CREATE TABLE IF NOT EXISTS `VCFiles` (
  `idVCFile` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `revision` varchar(150) NOT NULL,
  `lastUpdateDate` datetime NOT NULL,
  `lastCommitDate` datetime NOT NULL,
  `lastCommitUser` varchar(100) NOT NULL,
  `public` tinyint(1) NOT NULL,
  `idVCType` int(11) NOT NULL,
  `idResearch` int(11) NOT NULL,
  PRIMARY KEY (`idVCFile`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `VCTypes`
--

CREATE TABLE IF NOT EXISTS `VCTypes` (
  `idVCType` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(30) NOT NULL,
  PRIMARY KEY (`idVCType`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
