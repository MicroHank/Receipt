[MySQL Schema]

Database: receipt
Table: receipt_month(本月統一號碼), receipt_list(個人統一發票)

CREATE DATABASE `receipt` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ;

CREATE TABLE `receipt_phase` (
    `phase` char(5) NOT NULL,
    `prize_name` VARCHAR(20)  NOT NULL,
    `prize_number` VARCHAR(8)  NOT NULL,
    PRIMARY KEY(`phase`, `prize_name`)
) ENGINE=InnoDB CHARACTER SET=utf8 ;

CREATE TABLE `receipt_list` (
    `id` INT(5) NOT NULL auto_increment,
    `phase` char(5) NOT NULL,
    `number` VARCHAR(8)  NOT NULL,
    PRIMARY KEY(`id`),
    UNIQUE KEY(`phase`, `number`)
) ENGINE=InnoDB CHARACTER SET=utf8 ;

[Log 範例：辨識統一發票號碼並儲存至資料表 receipt_list]
[2020-02-13 02:11:39] E:\xampp\htdocs\receipt\recognizeReceipt.php.INFO: [IP=127.0.0.1] 開始辨識 10901 期統一發票號碼 []
[2020-02-13 02:11:39] E:\xampp\htdocs\receipt\recognizeReceipt.php.INFO: [IP=127.0.0.1] 統一發票號碼：17428136 []
[2020-02-13 02:11:40] E:\xampp\htdocs\receipt\recognizeReceipt.php.INFO: [IP=127.0.0.1] 統一發票號碼：65826079 []
[2020-02-13 02:11:40] E:\xampp\htdocs\receipt\recognizeReceipt.php.INFO: [IP=127.0.0.1] 統一發票號碼：51304570 []
[2020-02-13 02:11:40] E:\xampp\htdocs\receipt\recognizeReceipt.php.INFO: [IP=127.0.0.1] 統一發票號碼：65827853 []
[2020-02-13 02:11:41] E:\xampp\htdocs\receipt\recognizeReceipt.php.INFO: [IP=127.0.0.1] 統一發票號碼：74295298 []
[2020-02-13 02:11:41] E:\xampp\htdocs\receipt\recognizeReceipt.php.INFO: [IP=127.0.0.1] 將統一發票輸入資料庫完成 []
[2020-02-13 02:11:41] E:\xampp\htdocs\receipt\recognizeReceipt.php.INFO: [IP=127.0.0.1] 統一發票 10901 期共有 5 張發票 []
