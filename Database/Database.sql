SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `seeder` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `seeder` ;

-- -----------------------------------------------------
-- Table `seeder`.`Developer`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `seeder`.`Developer` (
  `idDeveloper` INT NOT NULL AUTO_INCREMENT ,
  `vendorId` INT NULL ,
  `twitter` VARCHAR(250) NULL ,
  `github` VARCHAR(250) NULL ,
  `facebook` VARCHAR(250) NULL ,
  `lindekin` VARCHAR(250) NULL ,
  PRIMARY KEY (`idDeveloper`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `seeder`.`User`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `seeder`.`User` (
  `idUser` INT NOT NULL AUTO_INCREMENT ,
  `idDeveloper` INT NOT NULL ,
  `firstName` VARCHAR(45) NULL ,
  `lastName` VARCHAR(45) NULL ,
  `email` VARCHAR(250) NULL ,
  `gender` VARCHAR(45) NULL ,
  `salt` VARCHAR(250) NULL ,
  `hash` VARCHAR(250) NULL ,
  `photoURL` VARCHAR(250) NULL ,
  `coins` INT NULL ,
  `isDeveloper` TINYINT(1) NULL ,
  `isAdmin` TINYINT(1) NULL ,
  `isBanned` TINYINT(1) NULL ,
  PRIMARY KEY (`idUser`) ,
  INDEX `fk_User_Developer1_idx` (`idDeveloper` ASC) ,
  CONSTRAINT `fk_User_Developer1`
    FOREIGN KEY (`idDeveloper` )
    REFERENCES `seeder`.`Developer` (`idDeveloper` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `seeder`.`Comment`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `seeder`.`Comment` (
  `idComment` INT NOT NULL AUTO_INCREMENT ,
  `date` DATETIME NULL ,
  `content` VARCHAR(250) NULL ,
  PRIMARY KEY (`idComment`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `seeder`.`User_Comment`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `seeder`.`User_Comment` (
  `idComment` INT NOT NULL ,
  `idUser` INT NOT NULL ,
  INDEX `fk_User_Comment_Comment1_idx` (`idComment` ASC) ,
  INDEX `fk_User_Comment_User1_idx` (`idUser` ASC) ,
  CONSTRAINT `fk_User_Comment_Comment1`
    FOREIGN KEY (`idComment` )
    REFERENCES `seeder`.`Comment` (`idComment` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_User_Comment_User1`
    FOREIGN KEY (`idUser` )
    REFERENCES `seeder`.`User` (`idUser` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `seeder`.`Category`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `seeder`.`Category` (
  `idCategory` INT NOT NULL AUTO_INCREMENT ,
  `categoryType` VARCHAR(45) NULL ,
  `name` VARCHAR(45) NULL ,
  PRIMARY KEY (`idCategory`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `seeder`.`Idea`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `seeder`.`Idea` (
  `idIdea` INT NOT NULL AUTO_INCREMENT ,
  `idUser` INT NOT NULL ,
  `idCategory` INT NOT NULL ,
  `title` VARCHAR(250) NULL ,
  `description` VARCHAR(500) NULL ,
  `date` DATETIME NULL ,
  `votes` INT NULL ,
  `reportNumber` INT NULL ,
  `isTaken` TINYINT(1) NULL ,
  PRIMARY KEY (`idIdea`) ,
  INDEX `fk_Idea_User1_idx` (`idUser` ASC) ,
  INDEX `fk_Idea_Category1_idx` (`idCategory` ASC) ,
  CONSTRAINT `fk_Idea_User1`
    FOREIGN KEY (`idUser` )
    REFERENCES `seeder`.`User` (`idUser` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Idea_Category1`
    FOREIGN KEY (`idCategory` )
    REFERENCES `seeder`.`Category` (`idCategory` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `seeder`.`Idea_Comment`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `seeder`.`Idea_Comment` (
  `idIdea` INT NOT NULL ,
  `idComment` INT NOT NULL ,
  INDEX `fk_Idea_Comment_Idea1_idx` (`idIdea` ASC) ,
  INDEX `fk_Idea_Comment_Comment1_idx` (`idComment` ASC) ,
  CONSTRAINT `fk_Idea_Comment_Idea1`
    FOREIGN KEY (`idIdea` )
    REFERENCES `seeder`.`Idea` (`idIdea` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Idea_Comment_Comment1`
    FOREIGN KEY (`idComment` )
    REFERENCES `seeder`.`Comment` (`idComment` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `seeder`.`Developer_Idea`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `seeder`.`Developer_Idea` (
  `idIdea` INT NOT NULL ,
  `idDeveloper` INT NOT NULL ,
  `progress` INT NULL ,
  `appId` INT NULL ,
  INDEX `fk_Developer_Idea_Idea1_idx` (`idIdea` ASC) ,
  INDEX `fk_Developer_Idea_Developer1_idx` (`idDeveloper` ASC) ,
  CONSTRAINT `fk_Developer_Idea_Idea1`
    FOREIGN KEY (`idIdea` )
    REFERENCES `seeder`.`Idea` (`idIdea` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Developer_Idea_Developer1`
    FOREIGN KEY (`idDeveloper` )
    REFERENCES `seeder`.`Developer` (`idDeveloper` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

USE `seeder` ;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `seeder`.`Category`
-- -----------------------------------------------------
START TRANSACTION;
USE `seeder`;
INSERT INTO `seeder`.`Category` (`idCategory`, `categoryType`, `name`) VALUES (NULL, 'Apps', 'Books');
INSERT INTO `seeder`.`Category` (`idCategory`, `categoryType`, `name`) VALUES (NULL, 'Apps', 'Finance');
INSERT INTO `seeder`.`Category` (`idCategory`, `categoryType`, `name`) VALUES (NULL, 'Apps', 'Navigation & Travel');
INSERT INTO `seeder`.`Category` (`idCategory`, `categoryType`, `name`) VALUES (NULL, 'Apps', 'Social');
INSERT INTO `seeder`.`Category` (`idCategory`, `categoryType`, `name`) VALUES (NULL, 'Apps', 'Business');
INSERT INTO `seeder`.`Category` (`idCategory`, `categoryType`, `name`) VALUES (NULL, 'Apps', 'Health & Fitness');
INSERT INTO `seeder`.`Category` (`idCategory`, `categoryType`, `name`) VALUES (NULL, 'Apps', 'News & Magazines');
INSERT INTO `seeder`.`Category` (`idCategory`, `categoryType`, `name`) VALUES (NULL, 'Apps', 'Sports');
INSERT INTO `seeder`.`Category` (`idCategory`, `categoryType`, `name`) VALUES (NULL, 'Apps', 'Education & Reference');
INSERT INTO `seeder`.`Category` (`idCategory`, `categoryType`, `name`) VALUES (NULL, 'Apps', 'Lifestyle');
INSERT INTO `seeder`.`Category` (`idCategory`, `categoryType`, `name`) VALUES (NULL, 'Apps', 'Photo & Video');
INSERT INTO `seeder`.`Category` (`idCategory`, `categoryType`, `name`) VALUES (NULL, 'Apps', 'Utilities');
INSERT INTO `seeder`.`Category` (`idCategory`, `categoryType`, `name`) VALUES (NULL, 'Apps', 'Entertainment');
INSERT INTO `seeder`.`Category` (`idCategory`, `categoryType`, `name`) VALUES (NULL, 'Apps', 'Music & Audio');
INSERT INTO `seeder`.`Category` (`idCategory`, `categoryType`, `name`) VALUES (NULL, 'Apps', 'Productivity');
INSERT INTO `seeder`.`Category` (`idCategory`, `categoryType`, `name`) VALUES (NULL, 'Apps', 'Weather');
INSERT INTO `seeder`.`Category` (`idCategory`, `categoryType`, `name`) VALUES (NULL, 'Apps', 'Other');
INSERT INTO `seeder`.`Category` (`idCategory`, `categoryType`, `name`) VALUES (NULL, 'Games', 'Action');
INSERT INTO `seeder`.`Category` (`idCategory`, `categoryType`, `name`) VALUES (NULL, 'Games', 'Children\'s Games');
INSERT INTO `seeder`.`Category` (`idCategory`, `categoryType`, `name`) VALUES (NULL, 'Games', 'Space');
INSERT INTO `seeder`.`Category` (`idCategory`, `categoryType`, `name`) VALUES (NULL, 'Games', 'Arcade');
INSERT INTO `seeder`.`Category` (`idCategory`, `categoryType`, `name`) VALUES (NULL, 'Games', 'Combat');
INSERT INTO `seeder`.`Category` (`idCategory`, `categoryType`, `name`) VALUES (NULL, 'Games', 'Sports');
INSERT INTO `seeder`.`Category` (`idCategory`, `categoryType`, `name`) VALUES (NULL, 'Games', 'Board Games');
INSERT INTO `seeder`.`Category` (`idCategory`, `categoryType`, `name`) VALUES (NULL, 'Games', 'Movies & TV');
INSERT INTO `seeder`.`Category` (`idCategory`, `categoryType`, `name`) VALUES (NULL, 'Games', 'Strategy');
INSERT INTO `seeder`.`Category` (`idCategory`, `categoryType`, `name`) VALUES (NULL, 'Games', 'Cards');
INSERT INTO `seeder`.`Category` (`idCategory`, `categoryType`, `name`) VALUES (NULL, 'Games', 'Puzzles');
INSERT INTO `seeder`.`Category` (`idCategory`, `categoryType`, `name`) VALUES (NULL, 'Games', 'Other');

COMMIT;
