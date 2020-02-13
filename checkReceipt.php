<?php
	/**
	 *  統一發票對獎
	 */
	include "vendor/autoload.php" ;

	use Henwen\Logger\Log ;
	use Henwen\Model\ReceiptPhase ;
	use Henwen\Model\ReceiptList ;

	try {
		$log = new Log() ;
		//$receipt_phase = getReceiptPhase() ;
		$receipt_phase = "10811" ; // 測試用
		$log->info("開始對獎：$receipt_phase 期統一發票", __FILE__, array()) ;

		// 設定本期開獎號碼
		$rp = new ReceiptPhase($receipt_phase) ;
		$rp->setPrize() ;

		// 取得本期發票號碼清單
		$rl = new ReceiptList($receipt_phase) ;
		$receipt_list_number = $rl->getList() ;
		
		// 對獎流程
		foreach ($receipt_list_number as $obj) {
			$result = $rp->checkNumber($obj["number"]) ;
			$log->info("發票號碼 ".$obj["number"]. " 結果：$result", __FILE__, array()) ;
			$rl->updateList($obj["number"], $result) ;
		}
		$log->info("結束對獎", __FILE__, array()) ;

	} catch (\Exception $e) {
		$log->info($e->getMessage(), __FILE__, array()) ;
		exit ;
	}
	
?>