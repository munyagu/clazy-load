<?php
/*
Plugin Name: cLazy Load
Plugin URI: http://munyagu.com/
Description: Set up Unveil Lazy Load jQuery plugin to your site.
Version: 1.0.0
Author: munyagu
Author URI: http://munyagu.com/
License: GPL2
*/

if (!is_admin()) {

    add_action( 'wp_enqueue_scripts', array('clazy_load', 'load_js'));
    add_filter( 'the_content' , array('clazy_load', 'replace_attribute'));
}


/**
 * Class clazy_load
 */
class clazy_load{
    private static $pattern_src = '/<img\s.*?(src)\s?=\s?\".*?>/i';
    private static $pattern_srcset = '/<img\s.*?(srcset)\s?=\s?\".*?>/i';
    private static $pattern_sizes = '/<img\s.*?(sizes)\s?=\s?\".*?>/i';

    private static $dist_src = 'data-src';
    private static $dist_srcset = 'data-srcset';
    private static $dist_sizes = 'data-sizes';

    /**
     * @param $content
     * @return mixed
     */
    public static function replace_attribute($content){
        $content = self::replace($content, self::$pattern_src, self::$dist_src);
        $content = self::replace($content, self::$pattern_srcset, self::$dist_srcset);
        $content = self::replace($content, self::$pattern_sizes, self::$dist_sizes);
        return $content;
    }


    public static function replace($content, $pattern, $dist){
        $result = preg_match_all($pattern, $content, $matches, PREG_OFFSET_CAPTURE);
        if($result !== false){
            $count = count($matches[1]);
            for($i=$count-1; $i>=0; $i--){
                $content = substr_replace($content, $dist,  $matches[1][$i][1], mb_strlen($matches[1][$i][0]));
            }
        }
        return $content;
    }

    public static function load_js(){
        wp_enqueue_script( 'clazy-load-lazy', plugins_url().'/clazy-load/js/jquery.unveil.js', array('jquery'), '1.3.1', true );
        wp_enqueue_script( 'clazy-load', plugins_url().'/clazy-load/js/clazy-load.js', array('jquery','clazy-load-lazy'), '1.0.0', true );

    }

}


