<?php
	$strTimeAgo = ""; 
	if(!empty($_POST["date-field"])) {
		$strTimeAgo = timeago($_POST["date-field"]);
	}
	function timeago($date) {
	   $timestamp = strtotime($date);	
	   
	   $strTime = array("second", "minute", "hour", "day", "month", "year");
	   $length = array("60","60","24","30","12","10");

	   $currentTime = time();
	   if($currentTime >= $timestamp) {
			$diff     = time()- $timestamp;
			for($i = 0; $diff >= $length[$i] && $i < count($length)-1; $i++) {
			$diff = $diff / $length[$i];
			}

			$diff = round($diff);
			return $diff . " " . $strTime[$i] . "(s) ago ";
	   }
	}
	
?>
<form name="frmTimeAgo" method="post">
Enter Date: <input type="date" name="date-field" value="<?php if(!empty($_POST["date-field"])) { echo $_POST["date-field"]; } ?>"/>
<input type="submit" name="submit-date" value="Submit Date" >
</form>
<?php
if(!empty($strTimeAgo)) {
	echo "Result: " . $strTimeAgo;
}
?>
