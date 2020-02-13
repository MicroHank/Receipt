<?php
	/**
	 *  辨識統一發票號碼，並將號碼清單儲存至資料表 receipt_list
	 *  資料來源：img/phase_number/*.jpg png 圖檔
	 */
	include "vendor/autoload.php" ;

	use Henwen\Logger\Log ;
	use thiagoalessio\TesseractOCR\TesseractOCR;
	
	try {
		$log = new Log() ;

		// 取得統一發票第幾期, e.g. 10901 (109年1月~2月)
		$receipt_phase = getReceiptPhase() ;
		$receipt_phase = "10901" ;
		$receipt_phase_dir = "img/$receipt_phase" ;

		$log->info("開始辨識 $receipt_phase 期統一發票", __FILE__, array()) ;

		// 取得所有統一發票圖片
		$log->info("取得 $receipt_phase 期統一發票圖檔", __FILE__, array()) ;		
		$receipt_DI = new DirectoryIterator($receipt_phase_dir) ;
		$receipt_img = array() ;
		foreach ($receipt_DI as $obj) {
			// 跳過 . 和 ..
			if ($obj->isDot()) continue ;
			array_push($receipt_img, $obj->getFilename()) ;
		}
		
		// 辨識統一發票：使用 Library "TesseractORC"
		$log->info("開始辨識 $receipt_phase 期統一發票號碼", __FILE__, array()) ;	
		$receipt_list = array() ;
		$ocr = new TesseractOCR() ;
		$ocr->executable(OCR_PATH) ;

		foreach ($receipt_img as $key => $filename) {
			$src = "$receipt_phase_dir/$filename" ;
			//echo "<img width='30%' height='50%' src='$src' />" ;
			$ocr->image($src) ;
			$text = $ocr->run() ;
			
			// 取得發票號碼
			if (! empty($text)) {
				preg_match("/(\d{8})/", $text, $match) ;
				if (! empty($match)) {
					array_push($receipt_list,
						array(
							"phase" => $receipt_phase, 
							"number" => $match[1]
						)
					) ;
					$log->info("統一發票號碼：".$match[1], __FILE__, array()) ;
				}
			}
		}
	} catch (\Exception $e) {
		$log->info($e->getMessage(), __FILE__, array()) ;
		\DB::rollback() ;
	}

	\DB::startTransaction() ;

	try {
		// 將統一發票號碼寫入資料庫：Table receipt_list
		\DB::insertIgnore("receipt_list", $receipt_list) ;
		\DB::commit() ;
		$log->info("將統一發票輸入資料庫完成", __FILE__, array()) ;
		
		$total = \DB::queryFirstField("SELECT count(number) AS total FROM receipt_list WHERE phase = %s", $receipt_phase) ;
		if (! empty($total)) {
			$log->info("統一發票 $receipt_phase 期共有 $total 張發票", __FILE__, array()) ;
		}

	} catch (\Exception $e) {
		$log->info($e->getMessage(), __FILE__, array()) ;
		\DB::rollback() ;
	}
?>