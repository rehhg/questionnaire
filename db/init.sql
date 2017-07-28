
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema qst
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema qst
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `qst` DEFAULT CHARACTER SET utf8 ;
USE `qst` ;

-- -----------------------------------------------------
-- Table `qst`.`users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qst`.`users` (
  `id_user` INT(11) NOT NULL AUTO_INCREMENT,
  `firstname` TINYTEXT NOT NULL,
  `lastname` TINYTEXT NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `username` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `user_role` ENUM('Admin', 'Manager', 'Employee') NOT NULL,
  `created_date` DATETIME NOT NULL DEFAULT '1000-01-01 00:00:00',
  `deleted` TINYINT(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_user`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `qst`.`department`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qst`.`department` (
  `id_department` INT(11) NOT NULL AUTO_INCREMENT,
  `department` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id_department`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `qst`.`task_type`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qst`.`task_type` (
  `id_type` INT(11) NOT NULL AUTO_INCREMENT,
  `type` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id_type`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `qst`.`tasks`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qst`.`tasks` (
  `id_task` INT(11) NOT NULL AUTO_INCREMENT,
  `subject` VARCHAR(45) NOT NULL,
  `description` VARCHAR(255) NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT '1000-01-01 00:00:00',
  `id_department` INT(11) NOT NULL,
  `id_type` INT(11) NOT NULL,
  PRIMARY KEY (`id_task`, `id_department`, `id_type`),
  INDEX `fk_tasks_department1_idx` (`id_department` ASC),
  INDEX `fk_tasks_task_type1_idx` (`id_type` ASC),
  CONSTRAINT `fk_tasks_department1`
    FOREIGN KEY (`id_department`)
    REFERENCES `qst`.`department` (`id_department`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_tasks_task_type1`
    FOREIGN KEY (`id_type`)
    REFERENCES `qst`.`task_type` (`id_type`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `qst`.`task_history`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qst`.`task_history` (
  `id_history` INT(11) NOT NULL AUTO_INCREMENT,
  `changed_date` DATETIME NOT NULL DEFAULT '1000-01-01 00:00:00',
  `id_task` INT(11) NOT NULL,
  `id_user` INT(11) NOT NULL,
  PRIMARY KEY (`id_history`, `id_user`),
  INDEX `fk_task_history_tasks1_idx` (`id_task` ASC),
  INDEX `fk_task_history_users1_idx` (`id_user` ASC),
  CONSTRAINT `fk_task_history_tasks1`
    FOREIGN KEY (`id_task`)
    REFERENCES `qst`.`tasks` (`id_task`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_task_history_users1`
    FOREIGN KEY (`id_user`)
    REFERENCES `qst`.`users` (`id_user`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `qst`.`users_tasks`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qst`.`users_tasks` (
  `id_user` INT(11) NOT NULL,
  `id_task` INT(11) NOT NULL,
  `status` ENUM('Done', 'In progress', 'Open') NULL,
  PRIMARY KEY (`id_user`, `id_task`),
  INDEX `fk_users_has_tasks_tasks1_idx` (`id_task` ASC),
  INDEX `fk_users_has_tasks_users1_idx` (`id_user` ASC),
  CONSTRAINT `fk_users_has_tasks_users1`
    FOREIGN KEY (`id_user`)
    REFERENCES `qst`.`users` (`id_user`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_users_has_tasks_tasks1`
    FOREIGN KEY (`id_task`)
    REFERENCES `qst`.`tasks` (`id_task`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

-- -----------------------------------------------------
-- Insert Admin into table `users`
-- -----------------------------------------------------
INSERT INTO users VALUES (NULL, 'AdminName', 'AdminSurname', 'admin@gmail.com', 'admin666', SHA(123456), 'Admin', NOW(), 0);

-- -----------------------------------------------------
-- Insert departments into table `department`
-- -----------------------------------------------------
INSERT INTO department (id_department, department) VALUES (NULL, 'HR'), (NULL, 'Development'), (NULL, 'Delivery'), (NULL, 'Testing'), (NULL, 'English Department'), (NULL, 'Training Center');

-- -----------------------------------------------------
-- Insert tasks type into table `task_type`
-- -----------------------------------------------------
INSERT INTO task_type (id_type, type) VALUES (NULL, 'Urgent'), (NULL, 'High'), (NULL, 'Standart'), (NULL, 'Minor'), (NULL, 'Low Priority'), (NULL, 'Critical');

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
