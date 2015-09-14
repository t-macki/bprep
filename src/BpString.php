<?php
/**
 * Created by PhpStorm.
 * User: makino
 * Date: 2015/09/14
 * Time: 10:23
 */

namespace Bprep;


class BpString
{
    /**
     * ランダムな文字列の生成
     * @param $num 生成する文字数
     * @return ランダムな文字列
     */
    public static function random_id($num=0)
    {
        if (!$num) {
            $num = 21;
        }
        $bytes = openssl_random_pseudo_bytes($num);
        $hex = bin2hex($bytes);
        return substr($hex, 0, $num);
    }

    /**
     * ランダムな31進文字列の生成
     * @param $num 生成する文字数
     * @return ランダムな文字列
     */
    public static function random_id_31($num=0)
    {
        if (!$num) {
            $num = 21;
        }
        $str = sha1(uniqid(mt_rand(), true));
        $str = substr($str, 0, $num);

        $str = preg_replace('/0/', '', $str);
        $str = preg_replace('/1/', '', $str);
        $str = preg_replace('/i/', '', $str);
        $str = preg_replace('/o/', '', $str);
        $str = preg_replace('/l/', '', $str);

        $cur_len = strlen($str);
        if ($num > $cur_len) {
            $diff_val = $num - $cur_len;
            for ($i = 0; $i < $diff_val; $i++) {
                $str .= self::random_char_31();
            }
        }

        return $str;
    }

    /**
     * 31進数文字列生成
     */
    public static function random_char_31()
    {
        $val = [
            '2', '3', '4', '5', '6', '7', '8', '9',
            'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'j', 'k', 'm', 'n', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'
        ];
        $hit = (int)(mt_rand(0, 30));
        return $val[$hit];
    }


}