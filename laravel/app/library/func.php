<?php

class func
{
    public static function HelloWorld()
    {
        echo "<p>Hello, world.</p>";
    }
    //產生亂數字串（長度：1～32）
    //用法：func::RandomString(長度)
    public static function RandomString($lenght)
    {
        $lenght = (int)$lenght;
        $lenght = ($lenght>0 && $lenght<=32) ? $lenght : 32;
        return substr(md5(uniqid(rand(), true)),0,$lenght);
    }
    //密碼加密法
    public static function Hash($str)
    {
        $salt1 = Config::get('config.salt1');
        $salt2 = Config::get('config.salt2');
        $result = md5($salt2.md5(md5($str).$salt1));
        return $result;
    }
    //檢查是否符合Email格式
    public static function isEmail($email)
    {
        $result = preg_match('/^([^@\s]+)@((?:[-a-z0-9]+\.)+[a-z]{2,})$/',$email);
        return $result;
    }
    //特定兩個字串之間的字串
    /*
    CatchStr(字串, 開頭關鍵字, 結尾關鍵字)
    例：
    $Str = "ABCDE{1234567890}FGHIJK";
    $StrArray = CatchStr($Str, "{", "}");
    結果：
    $StrArray[0] = 1234567890
    $StrArray[1] = ABCDE
    $StrArray[2] = FGHIJK
    */
    public static function CatchStr($Str, $StaKey, $EndKey){
        $StaPos = strpos($Str, $StaKey);
        $EndPos = strpos($Str, $EndKey);
        $StaLen = strlen($StaKey);
        $EndLen = strlen($EndKey);
        
        $CatchKey = substr($Str, $StaPos + $StaLen , $EndPos - ($StaPos + $StaLen) );
        $OtherKeyA = substr($Str, 0, $StaPos);
        $OtherKeyB = substr($Str, $EndPos + $EndLen);

        return array($CatchKey, $OtherKeyA, $OtherKeyB);
    }
	//檢查是否為整數
	public static function isInt($input){
		return(ctype_digit(strval($input)));
	}
    //限制輸入行數
    public static function keepXLines($str, $num=10) {
        $lines = explode("\n", $str);
        $firsts = array_slice($lines, 0, $num);
        return implode("\n", $firsts);
    }
    //HTML成對檢查
    // By David <david@24k.com.sg>
    public static function closeHtmlTags($html) {
        $arr_single_tags = array('meta', 'img', 'br', 'link', 'area', 'hr', 'input', '!');
        $at = 0;
        $end = strlen($html);
        $isInQuote1 = false;
        $isInQuote2 = false;
        $isInTag = false;
        $isInOpeningTag = false;
        $isReadingTag = false;
        $tagClosing = array();
        $tagClosingCount = 0;
        while ($at < $end) {
            $char = $html{$at};
            if ($char == '<') {
                if ($isInQuote1) {
                    // Pass
                } else if ($isInQuote2) {
                    // Pass
                } else if ($isInTag) {
                    // Pass
                } else {
                    if ($at == $end - 1) {
                        if ($tagClosingCount) {
                            $html .= "/";
                            $isInTag = true;
                            $isInOpeningTag = false;
                            $isReadingTag = true;
                            $tagCurr = '';
                        } else {
                            $html .= " />";
                        }
                        break;
                    } else {
                        $charNext = $html{++$at};
                        if (($charNext >= 'a' && $charNext <= 'z') || ($charNext >= 'A' && $charNext <= 'Z') || ($charNext == '!')) {
                            $isInTag = true;
                            $isInOpeningTag = true;
                            $isReadingTag = $charNext != '!';
                            $tagCurr = $charNext;
                        } else if ($charNext == '/') {
                            if ($at == $end - 1) {
                                $isInTag = true;
                                $isInOpeningTag = false;
                                $isReadingTag = true;
                                $tagCurr = '';
                                break;
                            } else {
                                $charNext = $html{++$at};
                                if (($charNext >= 'a' && $charNext <= 'z') || ($charNext >= 'A' && $charNext <= 'Z')) {
                                    $isInTag = true;
                                    $isInOpeningTag = false;
                                    $isReadingTag = true;
                                    $tagCurr = $charNext;
                                } else {
                                    // Pass
                                }
                            }
                        } else {
                            // Pass
                        }
                    }
                }
            } else if ($char == '>') {
                if ($isInQuote1) {
                    // Pass
                } else if ($isInQuote2) {
                    // Pass
                } else if (!$isInTag) {
                    // Pass
                } else {
                    $isInTag = false;
                    $isReadingTag = false;
                    $tagCurr = strtolower($tagCurr);
                    if ($isInOpeningTag) {
                        if ($tagCurr === "script") {
                            $pos = stripos($html, "</script", $at);
                            if ($pos === false) {
                                $len = strlen($html);
                                if (!strcmp(strtolower(substr($html, $len - 1)), "<")) {
                                    $html .= "/script>";
                                } else if (!strcmp(strtolower(substr($html, $len - 2)), "</")) {
                                    $html .= "script>";
                                } else if (!strcmp(strtolower(substr($html, $len - 3)), "</s")) {
                                    $html .= "cript>";
                                } else if (!strcmp(strtolower(substr($html, $len - 4)), "</sc")) {
                                    $html .= "ript>";
                                } else if (!strcmp(strtolower(substr($html, $len - 5)), "</scr")) {
                                    $html .= "ipt>";
                                } else if (!strcmp(strtolower(substr($html, $len - 6)), "</scri")) {
                                    $html .= "pt>";
                                } else if (!strcmp(strtolower(substr($html, $len - 7)), "</scrip")) {
                                    $html .= "t>";
                                } else if (!strcmp(strtolower(substr($html, $len - 8)), "</script")) {
                                    $html .= ">";
                                } else {
                                    $html .= "</script>";
                                }
                                break;
                            } else {
                                $at = $pos + 8;
                                array_push($tagClosing, "script");
                                $tagClosingCount++;
                                $isInTag = true;
                                $isInOpeningTag = false;
                                $isReadingTag = false;
                                $tagCurr = "script";
                            }
                        } else if (in_array($tagCurr, $arr_single_tags, true)) {
                            // Pass
                        } else {
                            array_push($tagClosing, $tagCurr);
                            $tagClosingCount++;
                        }
                    } else {
                        if ($tagClosingCount && $tagClosing[$tagClosingCount - 1] === $tagCurr) {
                            array_pop($tagClosing);
                            $tagClosingCount--;
                        } else {
                            $tagAt = $tagClosingCount - 2;
                            while ($tagAt >= 0) {
                                if ($tagClosing[$tagAt] === $tagCurr) {
                                    break;
                                }
                                $tagAt--;
                            }
                            if ($tagAt >= 0) {
                                $tagClosingCount--;
                                while ($tagAt < $tagClosingCount) {
                                    $tagAt2 = $tagAt + 1;
                                    $tagClosing[$tagAt] = $tagClosing[$tagAt2];
                                    $tagAt = $tagAt2;
                                }
                                array_pop($tagClosing);
                            } else {
                                // Pass
                            }
                        }
                    }
                }
            } else if ($char == '"') {
                if ($isInQuote1) {
                    $isInQuote1 = false;
                } else if ($isInQuote2) {
                    // Pass
                } else if ($isInTag) {
                    $isReadingTag = false;
                    $isInQuote1 = true;
                } else {
                    // Pass
                }
            } else if ($char == "'") {
                if ($isInQuote1) {
                    // Pass
                } else if ($isInQuote2) {
                    $isInQuote2 = false;
                } else if ($isInTag) {
                    $isReadingTag = false;
                    $isInQuote2 = true;
                } else {
                    // Pass
                }
            } else if (($char >= 'a' && $char <= 'z') || ($char >= 'A' && $char <= 'Z') || ($char == "_") || ($char >= '0' && $char <= '9')) {
                if ($isInQuote1) {
                    // Pass
                } else if ($isInQuote2) {
                    // Pass
                } else if ($isInTag) {
                    if ($isReadingTag) {
                        $tagCurr .= $char;
                    } else {
                        // Pass
                    }
                } else {
                    // Pass
                }
            } else {
                if ($isInQuote1) {
                    // Pass
                } else if ($isInQuote2) {
                    // Pass
                } else if ($isInTag) {
                    $isReadingTag = false;
                } else {
                    // Pass
                }
            }
            $at++;
        }
        if ($isInQuote1) {
            $html .= '"';
        }
        if ($isInQuote2) {
            $html .= "'";
        }
        if ($isInTag) {
            if ($isInOpeningTag) {
                $html .= "/>";
            } else {
                $tagCurr = strtolower($tagCurr);
                $tagCurrLen = strlen($tagCurr);
                if ($tagClosingCount && !strncmp($tagClosing[$tagClosingCount - 1], $tagCurr, $tagCurrLen)) {
                    if (strlen($tagClosing[$tagClosingCount - 1]) != $tagCurrLen) {
                        $html .= substr($tagClosing[$tagClosingCount - 1], $tagCurrLen);
                    }
                    $html .= ">";
                    array_pop($tagClosing);
                    $tagClosingCount--;
                } else {
                    $tagAt = $tagClosingCount - 2;
                    while ($tagAt >= 0) {
                        if (!strncmp($tagClosing[$tagAt], $tagCurr, $tagCurrLen)) {
                            break;
                        }
                        $tagAt--;
                    }
                    if ($tagAt >= 0) {
                        if (strlen($tagClosing[$tagAt]) != $tagCurrLen) {
                            $html .= substr($tagClosing[$tagAt], $tagCurrLen);
                        }
                        $html .= ">";
                        $tagClosingCount--;
                        while ($tagAt < $tagClosingCount) {
                            $tagAt2 = $tagAt + 1;
                            $tagClosing[$tagAt] = $tagClosing[$tagAt2];
                            $tagAt = $tagAt2;
                        }
                        array_pop($tagClosing);
                    } else {
                        // Pass
                    }
                }
            }
        }
        while (--$tagClosingCount >= 0) {
            $html .= "</{$tagClosing[$tagClosingCount]}>";
        }
        return $html;
    }
}