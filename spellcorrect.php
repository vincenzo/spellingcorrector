<?php
$dict = unserialize(file_get_contents('dic.txt'));

function correct($word, $dic) {
	if (array_key_exists($word, $dic)) {
		return $word;
	}	
	
	$search_result = $dic[soundex($word)];
	
	foreach ($search_result as $key => &$res) {
		$dist = levenshtein($key,$word);
		// consider just distance equals to 1 (the best) or 2
		if ($dist == 1 || $dist == 2) {
			$res = $res / $dist;
		}
		// discard all the other candidates that have distances other than 1 and 2 
		// from the original word 
		else {
			unset($search_result[$key]);
		}
	}

	// reverse sorting of the words by frequence
	arsort($search_result);
	
	// return the first key of the array (which will be the word suggested)
	foreach ($search_result as $key => $res) {
		return $key;
	}
}

// Snippit rather than all the items. 'correct' => 'wrong wrong wrong' 
$test = array( 'access' => 'acess', 'accessing' => 'accesing', 'accommodation' =>
    'accomodation acommodation acomodation', 'account' => 'acount');
$good = $bad = 0;

foreach($test as $word => $wrongs) {
        $wrongs = explode(' ', $wrongs); 
        foreach($wrongs as $wrong) {
                echo $word, '=', correct($wrong, $dict), "\n";
				if($word == correct($wrong, $dict)) {
                        $good++;
                } else {
                        $bad++;
                }
        }
}
echo "Good: $good\n";
echo "Bad: $bad\n";
echo "Percent: " . round(($good / ($good+$bad)) * 100) . "%\n";
