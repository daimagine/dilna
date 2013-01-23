<?php
/**
 * Created by JetBrains PhpStorm.
 * User: adi
 * Date: 10/29/12
 * Time: 6:31 AM
 * To change this template use File | Settings | File Templates.
 */

namespace Utilities;

class Stringutils {

    /*
        snippet(phrase,[max length],[phrase tail])
        snippetgreedy(phrase,[max length before next space],[phrase tail])
    */
    public static function snippet($text,$length=64,$tail="...") {
        $text = trim($text);
        $txtl = strlen($text);
        if($txtl > $length) {
            for($i=1;$text[$length-$i]!=" ";$i++) {
                if($i == $length) {
                    return substr($text,0,$length) . $tail;
                }
            }
            $text = substr($text,0,$length-$i+1) . $tail;
        }
        return $text;
    }

    // It behaves greedy, gets length characters ore goes for more

    public static function snippetgreedy($text,$length=64,$tail="...") {
        $text = trim($text);
        if(strlen($text) > $length) {
            for($i=0;$text[$length+$i]!=" ";$i++) {
                if(!$text[$length+$i]) {
                    return $text;
                }
            }
            $text = substr($text,0,$length+$i) . $tail;
        }
        return $text;
    }

    // The same as the snippet but removing latest low punctuation chars,
    // if they exist (dots and commas). It performs a later suffixal trim of spaces

    public static  function snippetwop($text,$length=64,$tail="...") {
        $text = trim($text);
        $txtl = strlen($text);
        if($txtl > $length) {
            for($i=1;$text[$length-$i]!=" ";$i++) {
                if($i == $length) {
                    return substr($text,0,$length) . $tail;
                }
            }
            for(;$text[$length-$i]=="," || $text[$length-$i]=="." || $text[$length-$i]==" ";$i++) {;}
            $text = substr($text,0,$length-$i+1) . $tail;
        }
        return $text;
    }

    /*
        echo(snippet("this is not too long to run on the column on the left, perhaps, or perhaps yes, no idea") . "<br>");
        echo(snippetwop("this is not too long to run on the column on the left, perhaps, or perhaps yes, no idea") . "<br>");
        echo(snippetgreedy("this is not too long to run on the column on the left, perhaps, or perhaps yes, no idea"));
    */

    public static function js_str($s) {
        return '"'.addcslashes($s, "\0..\37\"\\").'"';
    }

    public static function js_array($array) {
        $temp=array();
        foreach ($array as $value)
            $temp[] = Stringutils::js_str($value);
        return '['.implode(',', $temp).']';
    }
}
