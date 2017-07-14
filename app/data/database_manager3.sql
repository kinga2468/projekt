SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Table `categorie`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `categorie` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `icon` VARCHAR(45) NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC))
ENGINE = InnoDB;

ALTER TABLE `categorie` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
INSERT INTO `categorie` VALUES (NULL, 'wpłaty', 'fa fa-money');
INSERT INTO `categorie` VALUES (NULL, 'zakupy', 'fa fa-shopping-cart');
INSERT INTO `categorie` VALUES (NULL, 'paliwo', 'fa fa-car');
INSERT INTO `categorie` VALUES (NULL, 'rachunki', 'fa fa-credit-card');
INSERT INTO `categorie` VALUES (NULL, 'rozrywka', 'fa fa-gamepad');

-- -----------------------------------------------------
-- Table `role`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `role` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC))
ENGINE = InnoDB;

INSERT INTO `role` VALUES (NULL, 'admin');
INSERT INTO `role` VALUES (NULL, 'user');

ALTER TABLE `role` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
-- -----------------------------------------------------
-- Table `user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `user` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `password` VARCHAR(128) NOT NULL,
  `login` VARCHAR(45) NOT NULL,
  `role_id` INT NOT NULL,
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  PRIMARY KEY (`id`),
  UNIQUE INDEX `login_UNIQUE` (`login` ASC),
  INDEX `fk_user_role1_idx` (`role_id` ASC),
  CONSTRAINT `fk_user_role1`
    FOREIGN KEY (`role_id`)
    REFERENCES `role` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

ALTER TABLE `user` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
-- -----------------------------------------------------
-- Table `information`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `information` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(128) NOT NULL,
  `name` VARCHAR(45) NOT NULL,
  `surname` VARCHAR(45) NOT NULL,
  `gender` CHAR(1) NOT NULL,
  `user_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC),
  INDEX `fk_information_user_idx` (`user_id` ASC),
  CONSTRAINT `fk_information_user`
    FOREIGN KEY (`user_id`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

ALTER TABLE `information` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
-- -----------------------------------------------------
-- Table `month`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `month` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(128) NOT NULL,
  `date_from` DATETIME NOT NULL,
  `date_to` DATETIME NOT NULL,
  `limit` DECIMAL(12,2) NOT NULL,
  `user_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `fk_month_user1_idx` (`user_id` ASC),
  CONSTRAINT `fk_month_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

INSERT INTO `month` VALUES (NULL, 'listopad 2017', 2017-11-01, 2017-11-30, 3002, 1);
ALTER TABLE `month` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
-- -----------------------------------------------------
-- Table `operation`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `operation` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NULL,
  `value` DECIMAL(12,2) NOT NULL,
  `month_id` INT NOT NULL,
  `categorie_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `fk_operation_month1_idx` (`month_id` ASC),
  INDEX `fk_operation_categorie1_idx` (`categorie_id` ASC),
  CONSTRAINT `fk_operation_month1`
    FOREIGN KEY (`month_id`)
    REFERENCES `month` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_operation_categorie1`
    FOREIGN KEY (`categorie_id`)
    REFERENCES `categorie` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

ALTER TABLE `operation` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;