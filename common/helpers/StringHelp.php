<?php


namespace app\common\helpers;

class StringHelp
{
    /**
     * Returns the number of bytes in the given string.
     * This method ensures the string is treated as a byte array by using `mb_strlen()`.
     * @param string $string the string being measured for length
     * @return int the number of bytes in the given string.
     */
    public static function byteLength($string)
    {
        return mb_strlen($string, '8bit');
    }

    /**
     * Returns the portion of string specified by the start and length parameters.
     * This method ensures the string is treated as a byte array by using `mb_substr()`.
     * @param string $string the input string. Must be one character or longer.
     * @param int $start the starting position
     * @param int $length the desired portion length. If not specified or `null`, there will be
     * no limit on length i.e. the output will be until the end of the string.
     * @return string the extracted part of string, or FALSE on failure or an empty string.
     * @see http://www.php.net/manual/en/function.substr.php
     */
    public static function byteSubstr($string, $start, $length = null)
    {
        return mb_substr($string, $start, $length === null ? mb_strlen($string, '8bit') : $length, '8bit');
    }

    /**
     * Returns the trailing name component of a path.
     * This method is similar to the php function `basename()` except that it will
     * treat both \ and / as directory separators, independent of the operating system.
     * This method was mainly created to work on php namespaces. When working with real
     * file paths, php's `basename()` should work fine for you.
     * Note: this method is not aware of the actual filesystem, or path components such as "..".
     *
     * @param string $path A path string.
     * @param string $suffix If the name component ends in suffix this will also be cut off.
     * @return string the trailing name component of the given path.
     * @see http://www.php.net/manual/en/function.basename.php
     */
    public static function basename($path, $suffix = '')
    {
        if (($len = mb_strlen($suffix)) > 0 && mb_substr($path, -$len) === $suffix) {
            $path = mb_substr($path, 0, -$len);
        }
        $path = rtrim(str_replace('\\', '/', $path), '/\\');
        if (($pos = mb_strrpos($path, '/')) !== false) {
            return mb_substr($path, $pos + 1);
        }
        return $path;
    }

    /**
     * Returns parent directory's path.
     * This method is similar to `dirname()` except that it will treat
     * both \ and / as directory separators, independent of the operating system.
     *
     * @param string $path A path string.
     * @return string the parent directory's path.
     * @see http://www.php.net/manual/en/function.basename.php
     */
    public static function dirname($path)
    {
        $pos = mb_strrpos(str_replace('\\', '/', $path), '/');
        if ($pos !== false) {
            return mb_substr($path, 0, $pos);
        }
        return '';
    }


    /**
     * Truncate a string while preserving the HTML.
     *
     * @param string $string The string to truncate
     * @param int $count
     * @param string $suffix String to append to the end of the truncated string.
     * @param string|bool $encoding
     * @return string
     * @since 2.0.1
     */
    /**
     * Check if given string starts with specified substring.
     * Binary and multibyte safe.
     *
     * @param string $string Input string
     * @param string $with Part to search inside the $string
     * @param bool $caseSensitive Case sensitive search. Default is true. When case sensitive is enabled, $with must exactly match the starting of the string in order to get a true value.
     * @return bool Returns true if first input starts with second input, false otherwise
     */
    public static function startsWith($string, $with, $caseSensitive = true)
    {
        if (!$bytes = static::byteLength($with)) {
            return true;
        }
        if ($caseSensitive) {
            return strncmp($string, $with, $bytes) === 0;
        }
        $encoding = 'UTF-8';
        return mb_strtolower(mb_substr($string, 0, $bytes, '8bit'), $encoding) === mb_strtolower($with, $encoding);
    }

    /**
     * Check if given string ends with specified substring.
     * Binary and multibyte safe.
     *
     * @param string $string Input string to check
     * @param string $with Part to search inside of the $string.
     * @param bool $caseSensitive Case sensitive search. Default is true. When case sensitive is enabled, $with must exactly match the ending of the string in order to get a true value.
     * @return bool Returns true if first input ends with second input, false otherwise
     */
    public static function endsWith($string, $with, $caseSensitive = true)
    {
        if (!$bytes = static::byteLength($with)) {
            return true;
        }
        if ($caseSensitive) {
            // Warning check, see http://php.net/manual/en/function.substr-compare.php#refsect1-function.substr-compare-returnvalues
            if (static::byteLength($string) < $bytes) {
                return false;
            }
            return substr_compare($string, $with, -$bytes, $bytes) === 0;
        }
        $encoding = 'UTF-8';
        return mb_strtolower(mb_substr($string, -$bytes, mb_strlen($string, '8bit'), '8bit'), $encoding) === mb_strtolower($with, $encoding);
    }

    /**
     * Explodes string into array, optionally trims values and skips empty ones.
     *
     * @param string $string String to be exploded.
     * @param string $delimiter Delimiter. Default is ','.
     * @param mixed $trim Whether to trim each element. Can be:
     *   - boolean - to trim normally;
     *   - string - custom characters to trim. Will be passed as a second argument to `trim()` function.
     *   - callable - will be called for each value instead of trim. Takes the only argument - value.
     * @param bool $skipEmpty Whether to skip empty strings between delimiters. Default is false.
     * @return array
     * @since 2.0.4
     */
    public static function explode($string, $delimiter = ',', $trim = true, $skipEmpty = false)
    {
        $result = explode($delimiter, $string);
        if ($trim !== false) {
            if ($trim === true) {
                $trim = 'trim';
            } elseif (!is_callable($trim)) {
                $trim = function ($v) use ($trim) {
                    return trim($v, $trim);
                };
            }
            $result = array_map($trim, $result);
        }
        if ($skipEmpty) {
            // Wrapped with array_values to make array keys sequential after empty values removing
            $result = array_values(array_filter($result, function ($value) {
                return $value !== '';
            }));
        }
        return $result;
    }

    /**
     * Counts words in a string.
     * @since 2.0.8
     *
     * @param string $string
     * @return int
     */
    public static function countWords($string)
    {
        return count(preg_split('/\s+/u', $string, null, PREG_SPLIT_NO_EMPTY));
    }

    /**
     * Returns string representation of number value with replaced commas to dots, if decimal point
     * of current locale is comma.
     * @param int|float|string $value
     * @return string
     * @since 2.0.11
     */
    public static function normalizeNumber($value)
    {
        $value = (string)$value;
        $localeInfo = localeconv();
        $decimalSeparator = isset($localeInfo['decimal_point']) ? $localeInfo['decimal_point'] : null;
        if ($decimalSeparator !== null && $decimalSeparator !== '.') {
            $value = str_replace($decimalSeparator, '.', $value);
        }
        return $value;
    }

    /**
     * Encodes string into "Base 64 Encoding with URL and Filename Safe Alphabet" (RFC 4648).
     *
     * > Note: Base 64 padding `=` may be at the end of the returned string.
     * > `=` is not transparent to URL encoding.
     *
     * @see https://tools.ietf.org/html/rfc4648#page-7
     * @param string $input the string to encode.
     * @return string encoded string.
     * @since 2.0.12
     */
    public static function base64UrlEncode($input)
    {
        return strtr(base64_encode($input), '+/', '-_');
    }

    /**
     * Decodes "Base 64 Encoding with URL and Filename Safe Alphabet" (RFC 4648).
     *
     * @see https://tools.ietf.org/html/rfc4648#page-7
     * @param string $input encoded string.
     * @return string decoded string.
     * @since 2.0.12
     */
    public static function base64UrlDecode($input)
    {
        return base64_decode(strtr($input, '-_', '+/'));
    }

    /**
     * Safely casts a float to string independent of the current locale.
     *
     * The decimal separator will always be `.`.
     * @param float|int $number a floating point number or integer.
     * @return string the string representation of the number.
     * @since 2.0.13
     */
    public static function floatToString($number)
    {
        // . and , are the only decimal separators known in ICU data,
        // so its safe to call str_replace here
        return str_replace(',', '.', (string)$number);
    }

    /**
     * Checks if the passed string would match the given shell wildcard pattern.
     * This function emulates [[fnmatch()]], which may be unavailable at certain environment, using PCRE.
     * @param string $pattern the shell wildcard pattern.
     * @param string $string the tested string.
     * @param array $options options for matching. Valid options are:
     *
     * - caseSensitive: bool, whether pattern should be case sensitive. Defaults to `true`.
     * - escape: bool, whether backslash escaping is enabled. Defaults to `true`.
     * - filePath: bool, whether slashes in string only matches slashes in the given pattern. Defaults to `false`.
     *
     * @return bool whether the string matches pattern or not.
     * @since 2.0.14
     */
    public static function matchWildcard($pattern, $string, $options = [])
    {
        if ($pattern === '*' && empty($options['filePath'])) {
            return true;
        }
        $replacements = [
            '\\\\\\\\' => '\\\\',
            '\\\\\\*' => '[*]',
            '\\\\\\?' => '[?]',
            '\*' => '.*',
            '\?' => '.',
            '\[\!' => '[^',
            '\[' => '[',
            '\]' => ']',
            '\-' => '-',
        ];
        if (isset($options['escape']) && !$options['escape']) {
            unset($replacements['\\\\\\\\']);
            unset($replacements['\\\\\\*']);
            unset($replacements['\\\\\\?']);
        }
        if (!empty($options['filePath'])) {
            $replacements['\*'] = '[^/\\\\]*';
            $replacements['\?'] = '[^/\\\\]';
        }
        $pattern = strtr(preg_quote($pattern, '#'), $replacements);
        $pattern = '#^' . $pattern . '$#us';
        if (isset($options['caseSensitive']) && !$options['caseSensitive']) {
            $pattern .= 'i';
        }
        return preg_match($pattern, $string) === 1;
    }

    /**
     * This method provides a unicode-safe implementation of built-in PHP function `ucfirst()`.
     *
     * @param string $string the string to be proceeded
     * @param string $encoding Optional, defaults to "UTF-8"
     * @return string
     * @see http://php.net/manual/en/function.ucfirst.php
     * @since 2.0.16
     */
    public static function mb_ucfirst($string, $encoding = 'UTF-8')
    {
        $firstChar = mb_substr($string, 0, 1, $encoding);
        $rest = mb_substr($string, 1, null, $encoding);
        return mb_strtoupper($firstChar, $encoding) . $rest;
    }

    /**
     * This method provides a unicode-safe implementation of built-in PHP function `ucwords()`.
     *
     * @param string $string the string to be proceeded
     * @param string $encoding Optional, defaults to "UTF-8"
     * @see http://php.net/manual/en/function.ucwords.php
     * @return string
     */
    public static function mb_ucwords($string, $encoding = 'UTF-8')
    {
        $words = preg_split("/\s/u", $string, -1, PREG_SPLIT_NO_EMPTY);
        $titelized = array_map(function ($word) use ($encoding) {
            return static::mb_ucfirst($word, $encoding);
        }, $words);
        return implode(' ', $titelized);
    }


    //php获取中文字符拼音首字母
    public static function getFirstCharter($str)
    {
        if (empty($str)) {
            return '';
        }
        $fchar = ord($str{0});
        if ($fchar >= ord('A') && $fchar <= ord('z')) return strtoupper($str{0});
        $s1 = iconv('UTF-8', 'gb2312', $str);
        $s2 = iconv('gb2312', 'UTF-8', $s1);
        $s = $s2 == $str ? $s1 : $str;
        $asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
        if ($asc >= -20319 && $asc <= -20284) return 'A';
        if ($asc >= -20283 && $asc <= -19776) return 'B';
        if ($asc >= -19775 && $asc <= -19219) return 'C';
        if ($asc >= -19218 && $asc <= -18711) return 'D';
        if ($asc >= -18710 && $asc <= -18527) return 'E';
        if ($asc >= -18526 && $asc <= -18240) return 'F';
        if ($asc >= -18239 && $asc <= -17923) return 'G';
        if ($asc >= -17922 && $asc <= -17418) return 'H';
        if ($asc >= -17417 && $asc <= -16475) return 'J';
        if ($asc >= -16474 && $asc <= -16213) return 'K';
        if ($asc >= -16212 && $asc <= -15641) return 'L';
        if ($asc >= -15640 && $asc <= -15166) return 'M';
        if ($asc >= -15165 && $asc <= -14923) return 'N';
        if ($asc >= -14922 && $asc <= -14915) return 'O';
        if ($asc >= -14914 && $asc <= -14631) return 'P';
        if ($asc >= -14630 && $asc <= -14150) return 'Q';
        if ($asc >= -14149 && $asc <= -14091) return 'R';
        if ($asc >= -14090 && $asc <= -13319) return 'S';
        if ($asc >= -13318 && $asc <= -12839) return 'T';
        if ($asc >= -12838 && $asc <= -12557) return 'W';
        if ($asc >= -12556 && $asc <= -11848) return 'X';
        if ($asc >= -11847 && $asc <= -11056) return 'Y';
        if ($asc >= -11055 && $asc <= -10247) return 'Z';
        return '';
    }

    /**
     * 获取中文字母的首字母并转为大写
     * @param $chinese
     * @return string
     */
    public static function getAllFirstCharter($chinese)
    {
        $result = '';
        for ($i = 0; $i < mb_strlen($chinese); $i++) {
            $result .= self::getFirstCharter(mb_substr($chinese, $i, 1, "utf8"));
        }
        return $result;

    }


    /**
     * 截取某个字符串之前,字符为唯一,不含搜索字符串
     * @param $subject
     * @param $search
     * @return mixed
     */
    public static function before($subject, $search)
    {
        return $search === '' ? $subject : explode($search, $subject)[0];
    }

    /**
     * 截取某个字符串之后,字符为唯一,不含搜索字符串
     * @param $subject
     * @param $search
     * @return mixed
     */
    public static function str_after($subject, $search)
    {
        return $search === '' ? $subject : array_reverse(explode($search, $subject, 2))[0];
    }

}