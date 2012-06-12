<?php

function csvify($csvarr, $file) {
	$row = 1;
	if (($handle = fopen($file, "r")) !== FALSE) {
		$patternY = "/\d+ : /";
		$patternX = "/\w+ : /";
		$patternY2 = "/^(.+)\((.+)\)(.+)$/";
		$empty = "";
		$child = '\1';
		$type = '\2';
		$parent = '\3';

			$csvarr[2][0] = "";
			$csvarr[2][1] = "";
		while (($data = fgetcsv($handle, 1000, "\t")) !== FALSE) {
			$num = count($data);
			$row++;
			for ($c=0; $c < $num; $c++) {
				if($data[0]==0){
						$csvarr[$row][$c+3]=preg_replace($patternX, $empty, $data[$c]);
						//$csvarr[$row][$c]="yo".$data[$c];
						}
				elseif($c==0){
				$data[$c]=preg_replace($patternY, $empty, $data[$c]);
				$csvarr[$row][$c]=preg_replace($patternY2, $child, $data[$c]);


				$csvarr[$row][$c+1]=preg_replace($patternY2, $type, $data[$c]);

				$csvarr[$row][$c+2]=preg_replace($patternY2, $parent, $data[$c]);

				}
				else {
					$csvarr[$row][$c+3]=$data[$c];
				}

			}
		}
		fclose($handle);
		return $csvarr;
		
	}
	else {
		echo "cannot open file.";
	}

}

class TableSorter {
  protected $column;
  function __construct($column) {
    $this->column = $column;
  }
  function sort($table) {
    usort($table, array($this, 'compare'));
    return $table;
  }
  function compare($a, $b) {
    if ($a[$this->column] == $b[$this->column]) {
      return 0;
    }
    return ($a[$this->column] < $b[$this->column]) ? -1 : 1;
  }
}

$claims = array();
$claims = csvify($claims, "rel_by_claim-april.csv");
$sources = array();
$sources = csvify($sources, "rel_by_source-april.csv");
$unify = array();
		$patternY3 = "/^(\s)/";
		$patternY4 = "/(\s)$/";
		$blank = "";
foreach ($claims as $i => $value) {
	$claimNum=0;
	foreach ($claims[$i+1] as $j => $value) {
		if ($claims[$i+1][$j] != "0") {
			if ($j>2){
				switch($claims[2][$j]){
				case "Claim 1":
					$unify[$i]["claim".$claimNum]=1;
					break;
				case "Claim 2":
					$unify[$i]["claim".$claimNum]=2;
					break;
				case "Claim 3":
					$unify[$i]["claim".$claimNum]=3;
					break;
				case "Claim 4":
					$unify[$i]["claim".$claimNum]=4;
					break;
				case "Claim 5":
					$unify[$i]["claim".$claimNum]=5;
					break;
				case "Claim 6":
					$unify[$i]["claim".$claimNum]=6;
					break;
				case "Claim 7":
					$unify[$i]["claim".$claimNum]=7;
					break;
				case "Claim 8":
					$unify[$i]["claim".$claimNum]=8;
					break;
				case "Claim 9":
					$unify[$i]["claim".$claimNum]=9;
					break;
				case "Claim 10":
					$unify[$i]["claim".$claimNum]=10;
					break;
				}
				$claimNum++;
			}
			else{
				$unify[$i][$j]=preg_replace($patternY3, $blankFirst, $claims[$i+1][$j]);
				$unify[$i][$j]=preg_replace($patternY4, $blankFirst, $unify[$i][$j]);
				//$unify[$i][$j]=$claims[$i+1][$j];
			}
		}
	}
	$authorNum = 0;
	foreach ($sources[$i+1] as $j => $value) {
		if ($sources[$i+1][$j] != "0") {
			if ($j>2){
			$unify[$i]["author".$authorNum]=ucfirst($sources[2][$j]);
			$authorNum++;
			}
		}
	}
}



foreach ($unify as $i => $value) {
	if(($unify[$i]["author0"] != "")&&($unify[$i]["author1"] == "")){
		foreach ($unify[$i] as $j => $value) {
			$complete[$i][$j]=$unify[$i][$j];
			}
		$num++;
	}
	elseif ($unify[$i]["author1"] != ""){
		foreach ($unify[$i] as $j => $value) {
			$multiauthor[$i][$j]=$unify[$i][$j];
		}
	}
	elseif($unify[$i]["author0"] == ""){
		foreach ($unify[$i] as $j => $value) {
			$noauthor[$i][$j] = $unify[$i][$j];
		}
	}
}

foreach ($complete as $key => $row) {
    $author0[$key]  = $row['author0'];
    $claim0[$key] = $row['claim0'];
}
array_multisort($author0, SORT_ASC, $claim0, SORT_ASC, $complete);

function printTable($complete, $multiauthor, $noauthor){
echo "<h2>Complete</h2>";
echo "<table>";
$num=1;
foreach ($complete as $i => $value) {
	echo "<tr>";
	global $num;
	echo "<td>".$num."</td>";
	foreach ($complete[$i] as $j => $value) {
		echo "<td>".$complete[$i][$j]."</td>";
		}
	echo "</tr>";
	$num++;
	}
echo "</table>";
echo "<h2>Multiple Authors</h12>";
echo "<table>";
$num=1;
foreach ($multiauthor as $i => $value) {
	echo "<tr>";
	global $num;
	echo "<td>".$num."</td>";
	foreach ($multiauthor[$i] as $j => $value) {
		echo "<td>".$multiauthor[$i][$j]."</td>";
		}
	echo "</tr>";
	$num++;
	}
echo "</table>";
echo "<h2>No Authors</h12>";
echo "<table>";
$num=1;
foreach ($noauthor as $i => $value) {
	echo "<tr>";
	global $num;
	echo "<td>".$num."</td>";
	foreach ($noauthor[$i] as $j => $value) {
		echo "<td>".$noauthor[$i][$j]."</td>";
		}
	echo "</tr>";
	$num++;
	}
echo "</table>";
}
//printTable($complete, $multiauthor, $noauthor);
?>
