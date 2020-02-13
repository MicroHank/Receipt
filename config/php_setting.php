<?php
	define("OCR_PATH", 'C:\Program Files\Tesseract-OCR\tesseract.exe') ;

	ini_set("max_execution_time", 300) ;

	/**
	 * 取得統一發票第幾期
	 *
	 * @return String：民國年月份, e.g. 10811
	 */
	function getReceiptPhase() {
		$time = strtotime("2 months ago") ;
		$receipt_year = date("Y", $time) - 1911 ;
		$receipt_month = date("m", $time) ;
		return $receipt_year.$receipt_month ;
	}

	// 註冊錯誤處理器
    set_error_handler(
	    function ($severity, $message, $file, $line) {
	        throw new \ErrorException($message, $severity, $severity, $file, $line) ;
	    }
	) ;

    // ErrorHandler For MeekroDB
	function my_error_handler($params) {
		throw new \ErrorException($params["error"]." (".$params["query"].")", 1) ;
	}
?>