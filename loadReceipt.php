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
		$time = strtotime("2 months ago") ;
		$receipt_year = date("Y", $time) - 1911 ; // 108
		$receipt_month= date("m", $time) ; // 11
		//$receipt_date = $receipt_year.$receipt_month ; // 10811
		$receipt_date = "10811" ;
		$log->info("取得 $receipt_year-$receipt_month 統一發票號碼", __FILE__, array()) ;

		// 取得本次統一發票頁面 HTML String
		$curlobj = curl_init();
    	curl_setopt($curlobj, CURLOPT_URL, "https://www.etax.nat.gov.tw/etw-main/web/ETW183W2_$receipt_date/");
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
    	$log->info("特別獎", __FILE__, array("special_prize" => $special_prize)) ;
    	$log->info("特獎", __FILE__, array("grand_prize" => $grand_prize)) ;

    	// 頭獎 20 萬
    	$first_prize = array() ;
    	preg_match_all("/\d{8}/", $td_numbers[1][2], $match) ;
		$first_prize = $match[0] ;
		$log->info("頭獎", __FILE__, array("first_prize" => $first_prize)) ;

		// 增開獎 200 元
		$add_prize = array() ;
		if (isset($td_numbers[1][3])) {
			preg_match_all("/\d{3}/", $td_numbers[1][3], $match) ;
			$add_prize = $match[0] ;
			$log->info("增開獎", __FILE__, array("add_prize" => $add_prize)) ;
		}
		else {
			$log->info("本期沒有增開獎", __FILE__, array()) ;
		}

		// 將統一發票號碼寫入資料庫

	} catch (\Exception $e) {
		$log->info($e->getMessage(), __FILE__, array()) ;
		exit ;
	}
	
?>