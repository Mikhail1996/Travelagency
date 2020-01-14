# Creating Schema
CREATE SCHEMA `myshop` ;
ALTER SCHEMA `myshop`  DEFAULT COLLATE utf8_unicode_ci ;

use `myshop`;

# Creating Tables

CREATE TABLE `myshop`.`user` (
  `id_user` INT(11) NOT NULL AUTO_INCREMENT,
  `user_name` VARCHAR(50) NOT NULL,
  `user_login` VARCHAR(50) NOT NULL,
  `user_password` VARCHAR(50) NOT NULL,
  `id_role` INT(11) NOT NULL,
  `user_last_action` TIMESTAMP,
  PRIMARY KEY (`id_user`));
 
 CREATE TABLE `myshop`.`order` (
  `id_order` INT(11) NOT NULL AUTO_INCREMENT,
  `id_user` INT(11) NOT NULL,
  `amount` DOUBLE NOT NULL,
  `datetime_create` DATETIME NOT NULL,
  `id_order_status` INT(11) NOT NULL,
  PRIMARY KEY (`id_order`));
  
CREATE TABLE `myshop`.`order_status` (
  `id_order_status` INT(11) NOT NULL,
  `order_status_name` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id_order_status`)); 
  
CREATE TABLE `myshop`.`basket` (
  `id_basket` INT(11) NOT NULL AUTO_INCREMENT,
  `id_user` INT(11) NOT NULL,
  `id_good` INT(11) NOT NULL,
  `is_in_order` TINYINT,
  `id_order` INT(11),
  PRIMARY KEY (`id_basket`));
  
 CREATE TABLE `myshop`.`role` (
  `id_role`  INT(11) NOT NULL,
  `role_name` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id_role`));
  
CREATE TABLE `myshop`.`categories` (
  `id_category` INT(11) NOT NULL AUTO_INCREMENT,
  `status` INT(11),
  `name` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id_category`));
  
 CREATE TABLE `myshop`.`goods` (
  `id_good` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL,
  `price` DOUBLE NOT NULL,
  `description` TEXT NULL,
  `image_source` VARCHAR(50) NULL,
  `id_category` INT(11),
  `status` INT(11),
  PRIMARY KEY (`id_good`)); 

  
  


# Creating Foreign Keys  
  
ALTER TABLE `myshop`.`order` 
ADD INDEX `ordertouser_idx` (`id_user` ASC) VISIBLE;
;
ALTER TABLE `myshop`.`order` 
ADD CONSTRAINT `ordertouser`
  FOREIGN KEY (`id_user`)
  REFERENCES `myshop`.`user` (`id_user`)
  ON DELETE CASCADE
  ON UPDATE NO ACTION;
  
ALTER TABLE `myshop`.`order` 
ADD INDEX `ordertoorder_status_idx` (`id_order_status` ASC) VISIBLE;
;
ALTER TABLE `myshop`.`order` 
ADD CONSTRAINT `ordertoorder_status`
  FOREIGN KEY (`id_order_status`)
  REFERENCES `myshop`.`order_status` (`id_order_status`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;
  
ALTER TABLE `myshop`.`basket` 
ADD INDEX `baskettouser_idx` (`id_user` ASC) VISIBLE;
;
ALTER TABLE `myshop`.`basket` 
ADD CONSTRAINT `baskettouser`
  FOREIGN KEY (`id_user`)
  REFERENCES `myshop`.`user` (`id_user`)
  ON DELETE CASCADE
  ON UPDATE NO ACTION;

ALTER TABLE `myshop`.`basket` 
ADD INDEX `baskettoorder_idx` (`id_order` ASC) VISIBLE;
;
ALTER TABLE `myshop`.`basket` 
ADD CONSTRAINT `baskettoorder`
  FOREIGN KEY (`id_order`)
  REFERENCES `myshop`.`order` (`id_order`)
  ON DELETE CASCADE
  ON UPDATE NO ACTION;

ALTER TABLE `myshop`.`basket` 
ADD INDEX `baskettogoods_idx` (`id_good` ASC) VISIBLE;
;
ALTER TABLE `myshop`.`basket` 
ADD CONSTRAINT `baskettogoods`
  FOREIGN KEY (`id_good`)
  REFERENCES `myshop`.`goods` (`id_good`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `myshop`.`goods` 
ADD INDEX `goodstocategories_idx` (`id_category` ASC) VISIBLE;
;
ALTER TABLE `myshop`.`goods` 
ADD CONSTRAINT `goodstocategories`
  FOREIGN KEY (`id_category`)
  REFERENCES `myshop`.`categories` (`id_category`)
  ON DELETE SET NULL
  ON UPDATE NO ACTION;
  
ALTER TABLE `myshop`.`user` 
ADD INDEX `usertorole_idx` (`id_role` ASC) VISIBLE;
;
ALTER TABLE `myshop`.`user` 
ADD CONSTRAINT `usertorole`
  FOREIGN KEY (`id_role`)
  REFERENCES `myshop`.`role` (`id_role`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;
  

  
INSERT INTO `myshop`.`role` (`id_role`, `role_name`) VALUES ('1', 'user');
INSERT INTO `myshop`.`role` (`id_role`, `role_name`) VALUES ('2', 'editor');
INSERT INTO `myshop`.`role` (`id_role`, `role_name`) VALUES ('3', 'admin');

INSERT INTO `myshop`.`order_status` (`id_order_status`, `order_status_name`) VALUES ('1', 'Ожидает обработки');
INSERT INTO `myshop`.`order_status` (`id_order_status`, `order_status_name`) VALUES ('2', 'Обработан');
INSERT INTO `myshop`.`order_status` (`id_order_status`, `order_status_name`) VALUES ('3', 'Выполнен');

INSERT INTO `myshop`.`categories` (`name`) VALUES ('Новые');
INSERT INTO `myshop`.`categories` (`name`) VALUES ('Эксклюзивные');
INSERT INTO `myshop`.`categories` (`name`, `status`) VALUES ('Старые', '1');

INSERT INTO `myshop`.`goods` (`name`, `price`, `description`, `image_source`, `id_category`) VALUES ('Индия', '50000', 'Древняя и загадочная Индия хотя и относится к странам «третьего мира», все же привлекает бесчисленное количество гостей. А все потому, что имеет уникальную историю и культуру, памятки которой сохранились по всей территории страны. Но достопримечательности Индии – это не только храмы и крепости. Здесь еще есть множество роскошных парков и заповедников, где туристы могут увидеть в естественной среде редких животных. А отдых на тихих индийских пляжах останется в памяти на всю жизнь.', 'india.jpg', '2');
INSERT INTO `myshop`.`goods` (`name`, `price`, `description`, `image_source`, `id_category`) VALUES ('Испания', '70000', 'Испания — красивая и разнообразная страна. Здесь есть всё для насыщенного и незабываемого отдыха: огромные пляжи, множество развлечений, красивая европейская архитектура и даже горнолыжные курорты. С каждым годом Испания набирает всё больше популярности у российских туристов. Самые популярные туристические направления Испании — это Барселона, Канарские острова, Мадрид, Севилья и некоторые другие известные места.', 'spain.jpg', '1');
INSERT INTO `myshop`.`goods` (`name`, `price`, `description`, `image_source`, `id_category`) VALUES ('Греция', '45000', 'Достопримечательности Греции и древние археологические памятники, бесчисленные острова, песчаные пляжи и мягкий средиземноморский климат делают ее одним из главных туристических направлений Европы.', 'greece.jpg', '3');

INSERT INTO `myshop`.`user` (`user_name`, `user_login`, `user_password`, `id_role`) VALUES ('admin', 'admin@yandex.ru', '21232f297a57a5a743894a0e4a801fc3', '3');


