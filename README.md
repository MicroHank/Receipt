[MySQL Schema]

Database: receipt
Table: receipt_month(本月統一號碼), receipt_list(個人統一發票)

CREATE DATABASE `receipt` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ;

CREATE TABLE `receipt_month` (
    `month` char(5) NOT NULL,
    `prize_name` VARCHAR(20)  NOT NULL,
    `prize_number` VARCHAR(8)  NOT NULL,
    PRIMARY KEY(`month`, `prize_name`)
) ENGINE=InnoDB CHARACTER SET=utf8 ;

CREATE TABLE `receipt_list` (
    `id` INT(5) NOT NULL auto_increment,
    `month` char(5) NOT NULL,
    `number` VARCHAR(8)  NOT NULL,
    PRIMARY KEY(`id`),
    FOREIGN KEY (`month`) REFERENCES receipt_month (`month`),
    UNIQUE KEY(`month`, `number`)
) ENGINE=InnoDB CHARACTER SET=utf8 ;
