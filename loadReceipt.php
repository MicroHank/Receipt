<?php
	/**
	 *  下載統一發票號碼
	 *  資料來源：https://www.etax.nat.gov.tw/etw-main/web/ETW183W1/
	 */
	include "vendor/autoload.php" ;

	use Henwen\Logger\Log ;

    //----------------------取得統一發票資料--------------------------//
	try {
		$log = new Log() ;
		//$receipt_phase = getReceiptPhase() ;
		$receipt_phase = "10811" ;
		$log->info("取得 $receipt_phase 統一發票號碼", __FILE__, array()) ;

		// 取得本次統一發票頁面 HTML String
		$curlobj = curl_init();
    	curl_setopt($curlobj, CURLOPT_URL, "https://www.etax.nat.gov.tw/etw-main/web/ETW183W2_$receipt_phase/");
    	curl_setopt($curlobj, CURLOPT_RETURNTRANSFER, true) ;
    	$html = curl_exec($curlobj) ;

    	/*
    		解析統一發票號碼
    		array (size=4)
      		0 => string '59647042' (length=8) 特別獎
      		1 => string '01260528' (length=8) 特獎
      		2 => string '<p>01616970</p> <p>69921388</p> <p>53451508</p> <p></p>' (length=55) 頭獎
      		3 => string '710、585、633' (length=15) 增開獎
      	*/
    	preg_match_all("/<td.+headers.+class=.number.> (.*) <\/td>/", $html, $td_numbers) ;
    	$special_prize = $td_numbers[1][0] ; // 特別獎 1000 萬
    	$grand_prize   = $td_numbers[1][1] ; // 特獎 200 萬
    	$log->info("特別獎 $special_prize", __FILE__) ;
    	$log->info("特獎 $grand_prize", __FILE__) ;

    	// 頭獎 20 萬
    	$first_prize = array() ;
    	preg_match_all("/\d{8}/", $td_numbers[1][2], $match) ;
		$first_prize = $match[0] ;
		$log->info("頭獎 ".join(",", $first_prize), __FILE__) ;


		// 增開獎 200 元
		$add_prize = array() ;
		if (isset($td_numbers[1][3])) {
			preg_match_all("/\d{3}/", $td_numbers[1][3], $match) ;
			$add_prize = $match[0] ;
			$log->info("增開獎 ".join(",", $add_prize), __FILE__) ;
		}
		else {
			$log->info("本期沒有增開獎", __FILE__, array()) ;
		}

		\DB::startTransaction() ;

		// 將統一發票號碼寫入資料庫
		\DB::insertIgnore("receipt_phase", 
			array("phase" => $receipt_phase, "prize_name" => "特別獎", "prize_number" => $special_prize));

		\DB::insertIgnore("receipt_phase",
			array("phase" => $receipt_phase, "prize_name" => "特獎", "prize_number" => $grand_prize));
		
		foreach ($first_prize as $key => $number) {
			\DB::insertIgnore("receipt_phase",
				array("phase" => $receipt_phase, "prize_name" => "頭獎", "prize_number" => $number));
		}
		
		foreach ($add_prize as $key => $number) {
			\DB::insertIgnore("receipt_phase", 
				array("phase" => $receipt_phase, "prize_name" => "增開獎", "prize_number" => $number));
		}
		\DB::commit() ;
		$log->info("將統一發票開獎號碼寫入資料庫", __FILE__, array()) ;

	} catch (\Exception $e) {
		$log->info($e->getMessage(), __FILE__, array()) ;
		\DB::rollback() ;
		exit ;
	}
	
?>