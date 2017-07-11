SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Table `categorie`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `categorie` (
  `id_categorie` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(32) NOT NULL,
  `icon` VARCHAR(32),
  UNIQUE INDEX `id_kategoria_UNIQUE` (`id_categorie` ASC),
  PRIMARY KEY (`id_categorie`),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC))
ENGINE = InnoDB;

/*INSERT INTO `categorie` VALUES (NULL, `fa fa-money`, `wpłaty`), (NULL, `fa fa-shopping-cart`, `zakupy`),
(NULL,`fa fa-car`, `paliwo`), (NULL, `fa fa-credit-card` ,`rachunki`), (NULL, `fa fa-gamepad`, `rozrywka`);*/

-- -----------------------------------------------------
-- Table `role`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `role` (
  `id_role` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  UNIQUE INDEX `id_rola_UNIQUE` (`id_role` ASC),
  PRIMARY KEY (`id_role`))
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `user` (
  `id_user` INT NOT NULL AUTO_INCREMENT,
  `password` VARCHAR(128) NOT NULL,
  `login` VARCHAR(45) NOT NULL,
  `id_role` INT NOT NULL,
  PRIMARY KEY (`id_user`),
  UNIQUE INDEX `id_uzytkownika_UNIQUE` (`id_user` ASC),
  INDEX `fk_uzytkownik_role1_idx` (`id_role` ASC),
  UNIQUE INDEX `login_UNIQUE` (`login` ASC),
  CONSTRAINT `fk_uzytkownik_role1`
    FOREIGN KEY (`id_role`)
    REFERENCES `role` (`id_role`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `information`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `information` (
  `id_information` INT NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(128) NOT NULL,
  `name` VARCHAR(45) NOT NULL,
  `surname` VARCHAR(45) NOT NULL,
  `gender` CHAR(1) NOT NULL,
  `id_user` INT NOT NULL,
  PRIMARY KEY (`id_information`),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC),
  INDEX `fk_dane_uzytkownikow_uzytkownik1_idx` (`id_user` ASC),
  UNIQUE INDEX `id_information_UNIQUE` (`id_information` ASC),
  CONSTRAINT `fk_dane_uzytkownikow_uzytkownik1`
    FOREIGN KEY (`id_user`)
    REFERENCES `user` (`id_user`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `month`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `month` (
  `id_month` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(128) NOT NULL,
  `date_from` DATETIME NOT NULL,
  `date_to` DATETIME NOT NULL,
  `limit` DECIMAL(12,2) NOT NULL,
  `id_user` INT NOT NULL,
  UNIQUE INDEX `id_budzet_UNIQUE` (`id_month` ASC),
  PRIMARY KEY (`id_month`),
  INDEX `fk_budzet_uzytkownik1_idx` (`id_user` ASC),
  CONSTRAINT `fk_budzet_uzytkownik1`
    FOREIGN KEY (`id_user`)
    REFERENCES `user` (`id_user`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `operation`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `operation` (
  `id_operation` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NULL,
  `value` DECIMAL(12,2) NOT NULL,
  `id_month` INT NOT NULL,
  `id_categorie` INT NOT NULL,
  PRIMARY KEY (`id_operation`),
  UNIQUE INDEX `id_miesiac_UNIQUE` (`id_operation` ASC),
  INDEX `fk_operacje_budzet1_idx` (`id_month` ASC),
  INDEX `fk_operacje_kategorie1_idx` (`id_categorie` ASC),
  CONSTRAINT `fk_operacje_budzet1`
    FOREIGN KEY (`id_month`)
    REFERENCES `month` (`id_month`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_operacje_kategorie1`
    FOREIGN KEY (`id_categorie`)
    REFERENCES `categorie` (`id_categorie`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;