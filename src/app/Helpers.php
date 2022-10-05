<?php

namespace App;

class Helpers
{
    
    public static function getAlias($txt)
	{
		// tableau des caractÃ¨res spÃ©ciaux
		$chars = array('ÑŠ' => '-', 'Ð¬' => '-', 'Ðª' => '-', 'ÑŒ' => '-', 'Ä‚' => 'A', 'Ä„' => 'A', 'Ã€' => 'A', 'Ãƒ' => 'A', 'Ã' => 'A', 'Ã†' => 'A', 'Ã‚' => 'A', 'Ã…' => 'A', 'Ã„' => 'Ae', 'Ãž' => 'B', 'Ä†' => 'C', '×¥' => 'C', 'Ã‡' => 'C', 'Ãˆ' => 'E', 'Ä˜' => 'E', 'Ã‰' => 'E', 'Ã‹' => 'E', 'ÃŠ' => 'E', 'Äž' => 'G', 'Ä°' => 'I', 'Ã' => 'I', 'ÃŽ' => 'I', 'Ã' => 'I', 'ÃŒ' => 'I', 'Å' => 'L', 'Ã‘' => 'N', 'Åƒ' => 'N', 'Ã˜' => 'O', 'Ã“' => 'O', 'Ã’' => 'O', 'Ã”' => 'O', 'Ã•' => 'O', 'Ã–' => 'Oe', 'Åž' => 'S', 'Åš' => 'S', 'È˜' => 'S', 'Å ' => 'S', 'Èš' => 'T', 'Ã™' => 'U', 'Ã›' => 'U', 'Ãš' => 'U', 'Ãœ' => 'Ue', 'Ã' => 'Y', 'Å¹' => 'Z', 'Å½' => 'Z', 'Å»' => 'Z', 'Ã¢' => 'a', 'ÇŽ' => 'a', 'Ä…' => 'a', 'Ã¡' => 'a', 'Äƒ' => 'a', 'Ã£' => 'a', 'Ç' => 'a', 'Ð°' => 'a', 'Ð' => 'a', 'Ã¥' => 'a', 'Ã ' => 'a', '×' => 'a', 'Çº' => 'a', 'Ä€' => 'a', 'Ç»' => 'a', 'Ä' => 'a', 'Ã¤' => 'ae', 'Ã¦' => 'ae', 'Ç¼' => 'ae', 'Ç½' => 'ae', 'Ð±' => 'b', '×‘' => 'b', 'Ð‘' => 'b', 'Ã¾' => 'b', 'Ä‰' => 'c', 'Äˆ' => 'c', 'ÄŠ' => 'c', 'Ä‡' => 'c', 'Ã§' => 'c', 'Ñ†' => 'c', '×¦' => 'c', 'Ä‹' => 'c', 'Ð¦' => 'c', 'ÄŒ' => 'c', 'Ä' => 'c', 'Ð§' => 'ch', 'Ñ‡' => 'ch', '×“' => 'd', 'Ä' => 'd', 'Ä' => 'd', 'ÄŽ' => 'd', 'Ä‘' => 'd', 'Ð´' => 'd', 'Ð”' => 'd', 'Ã°' => 'd', 'Ñ”' => 'e', '×¢' => 'e', 'Ðµ' => 'e', 'Ð•' => 'e', 'Æ' => 'e', 'Ä™' => 'e', 'Ä•' => 'e', 'Ä“' => 'e', 'Ä’' => 'e', 'Ä–' => 'e', 'Ä—' => 'e', 'Ä›' => 'e', 'Äš' => 'e', 'Ð„' => 'e', 'Ä”' => 'e', 'Ãª' => 'e', 'É™' => 'e', 'Ã¨' => 'e', 'Ã«' => 'e', 'Ã©' => 'e', 'Ñ„' => 'f', 'Æ’' => 'f', 'Ð¤' => 'f', 'Ä¡' => 'g', 'Ä¢' => 'g', 'Ä ' => 'g', 'Äœ' => 'g', 'Ð“' => 'g', 'Ð³' => 'g', 'Ä' => 'g', 'ÄŸ' => 'g', '×’' => 'g', 'Ò' => 'g', 'Ò‘' => 'g', 'Ä£' => 'g', '×—' => 'h', 'Ä§' => 'h', 'Ð¥' => 'h', 'Ä¦' => 'h', 'Ä¤' => 'h', 'Ä¥' => 'h', 'Ñ…' => 'h', '×”' => 'h', 'Ã®' => 'i', 'Ã¯' => 'i', 'Ã­' => 'i', 'Ã¬' => 'i', 'Ä¯' => 'i', 'Ä­' => 'i', 'Ä±' => 'i', 'Ä¬' => 'i', 'Ð˜' => 'i', 'Ä©' => 'i', 'Ç' => 'i', 'Ä¨' => 'i', 'Ç' => 'i', 'Ð¸' => 'i', 'Ä®' => 'i', '×™' => 'i', 'Ð‡' => 'i', 'Äª' => 'i', 'Ð†' => 'i', 'Ñ—' => 'i', 'Ñ–' => 'i', 'Ä«' => 'i', 'Ä³' => 'ij', 'Ä²' => 'ij', 'Ð¹' => 'j', 'Ð™' => 'j', 'Ä´' => 'j', 'Äµ' => 'j', 'Ñ' => 'ja', 'Ð¯' => 'ja', 'Ð­' => 'je', 'Ñ' => 'je', 'Ñ‘' => 'jo', 'Ð' => 'jo', 'ÑŽ' => 'ju', 'Ð®' => 'ju', 'Ä¸' => 'k', '×›' => 'k', 'Ä¶' => 'k', 'Ðš' => 'k', 'Ðº' => 'k', 'Ä·' => 'k', '×š' => 'k', 'Ä¿' => 'l', 'Å€' => 'l', 'Ð›' => 'l', 'Å‚' => 'l', 'Ä¼' => 'l', 'Äº' => 'l', 'Ä¹' => 'l', 'Ä»' => 'l', 'Ð»' => 'l', 'Ä½' => 'l', 'Ä¾' => 'l', '×œ' => 'l', '×ž' => 'm', 'Ðœ' => 'm', '×' => 'm', 'Ð¼' => 'm', 'Ã±' => 'n', 'Ð½' => 'n', 'Å…' => 'n', '×Ÿ' => 'n', 'Å‹' => 'n', '× ' => 'n', 'Ð' => 'n', 'Å„' => 'n', 'ÅŠ' => 'n', 'Å†' => 'n', 'Å‰' => 'n', 'Å‡' => 'n', 'Åˆ' => 'n', 'Ð¾' => 'o', 'Ðž' => 'o', 'Å‘' => 'o', 'Ãµ' => 'o', 'Ã´' => 'o', 'Å' => 'o', 'Å' => 'o', 'ÅŽ' => 'o', 'ÅŒ' => 'o', 'Å' => 'o', 'Ã¸' => 'o', 'Ç¿' => 'o', 'Ç’' => 'o', 'Ã²' => 'o', 'Ç¾' => 'o', 'Ç‘' => 'o', 'Æ¡' => 'o', 'Ã³' => 'o', 'Æ ' => 'o', 'Å“' => 'oe', 'Å’' => 'oe', 'Ã¶' => 'oe', '×¤' => 'p', '×£' => 'p', 'Ð¿' => 'p', 'ÐŸ' => 'p', '×§' => 'q', 'Å•' => 'r', 'Å™' => 'r', 'Å˜' => 'r', 'Å—' => 'r', 'Å–' => 'r', '×¨' => 'r', 'Å”' => 'r', 'Ð ' => 'r', 'Ñ€' => 'r', 'È™' => 's', 'Ñ' => 's', 'Åœ' => 's', 'Å¡' => 's', 'Å›' => 's', '×¡' => 's', 'ÅŸ' => 's', 'Ð¡' => 's', 'Å' => 's', 'Ð©' => 'sch', 'Ñ‰' => 'sch', 'Ñˆ' => 'sh', 'Ð¨' => 'sh', 'ÃŸ' => 'ss', 'Ñ‚' => 't', '×˜' => 't', 'Å§' => 't', '×ª' => 't', 'Å¥' => 't', 'Å£' => 't', 'Å¢' => 't', 'Ð¢' => 't', 'È›' => 't', 'Å¦' => 't', 'Å¤' => 't', 'â„¢' => 'tm', 'Å«' => 'u', 'Ñƒ' => 'u', 'Å¨' => 'u', 'Å©' => 'u', 'Æ¯' => 'u', 'Æ°' => 'u', 'Åª' => 'u', 'Ç“' => 'u', 'Å³' => 'u', 'Å²' => 'u', 'Å­' => 'u', 'Å¬' => 'u', 'Å®' => 'u', 'Å¯' => 'u', 'Å±' => 'u', 'Å°' => 'u', 'Ç•' => 'u', 'Ç”' => 'u', 'Ç›' => 'u', 'Ã¹' => 'u', 'Ãº' => 'u', 'Ã»' => 'u', 'Ð£' => 'u', 'Çš' => 'u', 'Çœ' => 'u', 'Ç™' => 'u', 'Ç—' => 'u', 'Ç–' => 'u', 'Ç˜' => 'u', 'Ã¼' => 'ue', 'Ð²' => 'v', '×•' => 'v', 'Ð’' => 'v', '×©' => 'w', 'Åµ' => 'w', 'Å´' => 'w', 'Ñ‹' => 'y', 'Å·' => 'y', 'Ã½' => 'y', 'Ã¿' => 'y', 'Å¸' => 'y', 'Å¶' => 'y', 'Ð«' => 'y', 'Å¾' => 'z', 'Ð—' => 'z', 'Ð·' => 'z', 'Åº' => 'z', '×–' => 'z', 'Å¼' => 'z', 'Å¿' => 'z', 'Ð–' => 'zh', 'Ð¶' => 'zh');
		$txt = strtr($txt, $chars);
		$txt = mb_strtolower($txt, 'UTF-8'); // mettre le lien en miniscule
		// $txt = preg_replace('/[^a-z0-9]/', '-', $txt); // remplace tous les charactÃ¨res non-valides
		//$txt = preg_replace('/[^a-z0-9~%.:_-]/', '-', $txt); // remplace tous les charactÃ¨res non-valides
		$txt = preg_replace('/[^a-z0-9-]/', '-', $txt); // remplace tous les charactÃ¨res non-valides (ELAZ)
		$txt = preg_replace('/-+/', '-', $txt); // Ã©limininer les "-" doublants
		$txt = preg_replace('/(^-+)|(-+$)/', '', $txt); // supprimer les "-" en dÃ©but et fin
		return $txt;
	} 

	public static function dateFormat($date, $format = '')
	{
		setlocale(LC_TIME, 'fr_FR', 'fr-FR', 'fra', 'french', 'fr');
		if ($date instanceof DateTime || $date instanceof \DateTime) $date = $date->getTimeStamp();
		if (getType($date) === 'string') $date = strtotime($date);
		if (!$format) $format = '%d %b %Y';
		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
			return iconv('ISO-8859-1', 'UTF-8', strftime($format, $date));
		} else {
			return utf8_encode(strftime($format, $date));
		}
	}

	public static function numberFormat($nbr, $decimals=2) {
		return number_format($nbr, $decimals, ',', '.');
	}

	public static function sort_array_multidim(array $array, $order_by)
    {
        //TODO -c flexibility -o tufanbarisyildirim : this error can be deleted if you want to sort as sql like "NULL LAST/FIRST" behavior.
        if(!is_array($array[0]))
            throw new \Exception('$array must be a multidimensional array!',E_USER_ERROR);

        $columns = explode(',',$order_by);
        foreach ($columns as $col_dir)
        {
            if(preg_match('/(.*)([\s]+)(ASC|DESC)/is',$col_dir,$matches))
            {
                if(!array_key_exists(trim($matches[1]),$array[0]))
                    trigger_error('Unknown Column <b>' . trim($matches[1]) . '</b>',E_USER_NOTICE);
                else
                {
                    if(isset($sorts[trim($matches[1])]))
                        trigger_error('Redundand specified column name : <b>' . trim($matches[1] . '</b>'));

                    $sorts[trim($matches[1])] = 'SORT_'.strtoupper(trim($matches[3]));
                }
            }
            else
            {
                throw new \Exception("Incorrect syntax near : '{$col_dir}'",E_USER_ERROR);
            }
        }

        //TODO -c optimization -o tufanbarisyildirim : use array_* functions.
        $colarr = array();
        foreach ($sorts as $col => $order)
        {
            $colarr[$col] = array();
            foreach ($array as $k => $row)
            {
                $colarr[$col]['_'.$k] = strtolower($row[$col]);
            }
        }
       
        $multi_params = array();
        foreach ($sorts as $col => $order)
        {
            $multi_params[] = '$colarr[\'' . $col .'\']';
            $multi_params[] = $order;
        }

        $rum_params = implode(',',$multi_params);
        eval("array_multisort({$rum_params});");


        $sorted_array = array();
        foreach ($colarr as $col => $arr)
        {
            foreach ($arr as $k => $v)
            {
                $k = substr($k,1);
                if (!isset($sorted_array[$k]))
                    $sorted_array[$k] = $array[$k];
                $sorted_array[$k][$col] = $array[$k][$col];
            }
        }

        return array_values($sorted_array);

    }
}