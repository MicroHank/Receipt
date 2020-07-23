<h3>:newspaper: 統一發票兌獎程式</h3>
<li>統一發票資料來源：https://www.etax.nat.gov.tw/etw-main/web/ETW183W1/</li>
<li>簡易爬蟲: 至財政部網站抓取各期獎號儲存至資料庫, 包括頭獎、特別獎、特獎、增開獎</li>
<li>利用套件 TesseractOCR + 正規表示式, 取得統一發票圖片之獎號</li>
<li>撰寫兌獎程式, 將結果寫至 Log 檔</li>

[MySQL Schema]

Database: receipt
Table: receipt_month(本月統一號碼), receipt_list(個人統一發票)

CREATE DATABASE `receipt` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ;

CREATE TABLE `receipt_phase` (
    `phase` char(5) NOT NULL,
    `prize_name` VARCHAR(20)  NOT NULL,
    `prize_number` VARCHAR(8)  NOT NULL,
    PRIMARY KEY(`phase`, `prize_name`, `prize_number`)
) ENGINE=InnoDB CHARACTER SET=utf8 ;

CREATE TABLE `receipt_list` (
    `id` INT(5) NOT NULL auto_increment,
    `phase` char(5) NOT NULL,
    `number` VARCHAR(8)  NOT NULL,
    `result` VARCHAR(18),
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
