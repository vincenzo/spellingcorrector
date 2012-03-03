<?php

function train($file = 'big.txt') {
        $contents = file_get_contents($file);
        // get all strings of word letters
        preg_match_all('/\w+/', $contents, $matches);
        unset($contents);
        $dictionary = array();
        foreach($matches[0] as $word) {
                $word = strtolower($word);
				$soundex_key = soundex($word);
				if(!isset($dictionary[$soundex_key][$word])) {     
					$dictionary[$soundex_key][$word] = 0;
				}

                $dictionary[$soundex_key][$word] += 1;
        } 
        unset($matches);
        return $dictionary;
}

$dic = train();
file_put_contents('dic.txt', serialize($dic));