<?php
	namespace Henwen\Model ;
	
	class ReceiptList
	{
		private $phase = "" ;

		public function __construct($phase = "")
		{
			$this->phase = $phase ;
		}

		public function getList()
	    {
	        return \DB::query("SELECT phase, `number` FROM receipt_list WHERE phase = %s", $this->phase) ;
	    }

	    public function updateList($number = "", $result = "")
	    {
	    	\DB::update("receipt_list", array("result" => $result), "phase=%s AND number=%s", $this->phase, $number) ;
	    }
	}
?>