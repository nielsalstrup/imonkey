<?php
/*
Plugin Name: Infinite Monkey
Plugin URI:
Description: Turns world literature into drivel
Version: 1.1
Author: Niels Chr. Alstrup
Author URI: http://nielschralstrup.dk

*/

//parameters

add_shortcode('imonkey', 'monkey_shortcode');

function monkey_shortcode ($atts) {

   extract( shortcode_atts( array(
      'src' => 'Shakespeare_Sonnets.txt',
      'depth' => '6',
      'title' => '',
      'size' => '200'
   ), $atts ) );
   $body .= "<h3>" . esc_attr($title) . "</h3>" . thrum(esc_attr($src), esc_attr($depth), esc_attr($size));
   return $body;
}

function thrum ($src, $depth, $size) {

   //read Source
   $url = WP_PLUGIN_URL . "/imonkey/docs/" . $src;
   $handle = fopen($url, 'rb');
   while (!feof($handle)) {
         $sSource .= fread($handle, 8192);
   }
   fclose($handle);
   $len = strlen($sSource);

   //wrap
   $sSource .= ' ';
   $sSource .= substr($sSource, 1 , $depth);

   //start
   $start = rand(0, $len);
   $sSeed = substr($sSource, $start, $depth);
   $sOutput = $sSeed;
   $ii = 0;

   while ($ii<$size) {

         $start = rand(0, $len);
         $sSearch = substr($sSource, $start);
         $sNew = strchr($sSearch, $sSeed);
         if ($sNew==False) {
             $sNew = strchr($sSource, $sSeed);
         }
         $sNext = substr($sNew, $depth, 1);
         $sSeed = substr($sSeed, 1, $depth-1) . $sNext;
         $sOutput .= $sNext;
         $ii++;
   }
   $sInfo = "<small>IMONKEY  1.1   Source Size: " . $len . ", Depth: " . $depth . "</small>";
   return "<pre>" . $sOutput . "<br/>--<br/>" . $sInfo . "</pre>";
}
?>
