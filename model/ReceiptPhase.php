<?php
	namespace Henwen\Model ;

	class ReceiptPhase
	{
		private $phase = "" ;
		private $special_number = "" ;
		private $grand_number = "" ;
		private $first_number = array() ;
		private $add_number = array() ;

		public function __construct($phase = "")
		{
			$this->phase = $phase ;
		}

		public function setPhase($phase = "")
	    {
	        $this->phase = $phase ;
	    }

	    public function setPrize()
	    {
	        $prize = $this->getPhase($this->phase) ;
	        foreach ($prize as $key => $obj) {
	        	switch ($obj["prize_name"]) {
	        	 	case "特別獎":
	        	 		$this->special_number = $obj["prize_number"] ;
	        	 		break ;
	        	 	case "特獎":
	        	 		$this->grand_number = $obj["prize_number"] ;
	        	 		break ;
	        	 	case "頭獎":
	        	 		array_push($this->first_number, $obj["prize_number"]) ;
	        	 		break ;
	        	 	case "增開獎":
	        	 		array_push($this->add_number, $obj["prize_number"]) ;
	        	 		break ;
	        	 	default:
	        	 		break ;
	        	 }
	        }
	    }

		public function getPhase($phase = "")
	    {
	        return \DB::query("SELECT * FROM receipt_phase WHERE phase = %s", $phase) ;
	    }

	    public function checkNumber($number = "")
	    {
	    	// 特別獎
	    	if ($number === $this->special_number) {
	    		return "特別獎" ;
	    	}

	    	// 特獎
	    	if ($number === $this->grand_number) {
	    		return "特獎" ;
	    	}

	    	// 頭獎
	    	foreach ($this->first_number as $key => $prize_number) {
	    		for ($i = 0 ; $i <= 5 ; $i++) {
	    			// 從第一位開始比對至後三位
	    			$substr = substr($number, $i, 8-$i) ;
	    			$pos = strrpos($prize_number, $substr) ;
	    			if ($pos === false) continue ;
	    			if ($pos >= 0) {
	    				return "頭獎 ".(8-$i)." 位" ;
	    			}
	    		}
	    	}

	    	// 增開獎
	    	foreach ($this->add_number as $key => $prize_number) {
	    		$pos = strrpos($number, $prize_number) ;
	    		if ($pos === false) continue ;
	    		if ($pos >= 0) {
	    			return "增開獎" ;
	    		}
	    	}

	    	return "X" ;
	    }
	}
?>