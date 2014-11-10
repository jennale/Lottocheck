<?php
define('HTML_FILE', 'http://portalseven.com/lottery/canada_lotto_649_winning_numbers.jsp?viewType=2&timeRange=3');
define('CSV_FILE',  'hw2.csv');
define('ERROR', 'Process Script Error');
define('NOT_FOUND', 'Combination not found');

//Creates a CSV by grabbing data from a local HTML file sample
function createCSV() {
// 	if (file_exists(HTML_FILE)){ //Use if testing with the local file
		$html_content = file_get_contents(HTML_FILE); //Place in string
		$writeString = "";
		$i = 0;
/*
	}
	else {
		echo "CSV Script error";
		throw new Exception(ERROR);
	}
*/
	//First match whole content row
	preg_match_all("/(?s)<TR class=\"odd\">(.*?)<\/TR>|<TR class=\"even\">(.*?)<\/TR>/", $html_content, $out, PREG_SET_ORDER);
	foreach ($out as $item){
		$j = 0;
		$trItem = $out[$i][0];
		//Second match date block
		preg_match_all("/<TD odd\" >(.*?)  <\/TD>|<TD even\" >(.*?)  <\/TD>/", $trItem, $out2, PREG_SET_ORDER);
		//Lastly match number blocks
		preg_match_all("/<TD><b>(.*?)<\/b><\/TD>/", $trItem, $out3, PREG_SET_ORDER);

		foreach ($out2 as $item){
			$k = 0;
			$tdItem = $out2[$j][0];

			if (!empty($out2[$j][1])){
				$writeString = $writeString."\"".$out2[$j][1]."\"";
			}
			if (!empty($out2[$j][2])){
				$writeString = $writeString."\"".$out2[$j][2]."\"";
			}
			foreach ($out3 as $item){
				$writeString = $writeString.",\"".$out3[$k][1]."\"";
				++$k;
			}
			$writeString = $writeString."\n";
			++$j;
		}
		++$i;
	}
	file_put_contents(CSV_FILE, $writeString);
}

function findLotto($numEntry) {
	//$numEntry = substr($numEntry, 1,-1);
	//echo $numEntry;
	try {
		createCSV();
		$numArray = preg_split("/-|\D/", $numEntry,0,PREG_SPLIT_NO_EMPTY);
// 		print_r($numArray);
		$csv_handle = fopen(CSV_FILE,"r");

		if (empty($numArray[0])){
			echo " ";
			exit;
		}

		while (!feof($csv_handle)) {
			$win = 1;
			$csvLine = fgetcsv($csv_handle);

			foreach ($numArray as $item){
				if (!in_array($item, (array)$csvLine) && !empty($item)) {
					$win = 0;
				}
			}
			if ($win){
				echo "Most recently winner on: <br/>".$csvLine[0];
				break;
			}
		}
		if (!$win) {
			echo NOT_FOUND;
		}
		fclose($csv_handle);
	}
	catch (Exception $e){
		echo ERROR;
	}
}

$numbers=$_GET["numbers"];
sleep(1);
findLotto($numbers);

?>
