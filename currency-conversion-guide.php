<?php
/*
Plugin Name: Currency Conversion Guide
Description: Currency Conversion Guide displays a table of to and from currency values, allows a quick lookup of conversion values. Any currencies in the world.
Author: enclick
Version: 1.0
Author URI: http://fx-rate.net
Plugin URI: http://fx-rate.net/wordpress-currency-conversion-guide-plugin/
*/

function currency_conversion_guide_init() 
{


     if ( !function_exists('register_sidebar_widget') || !function_exists('register_widget_control') )
    	   return; 

    function currency_conversion_guide_control() 
    {

        $newoptions = get_option('currency_conversion_guide');
    	$options = $newoptions;
	$options_flag=0;

      	if(empty($currency_list)){
		$file_location = dirname(__FILE__)."/currencies.ser"; 
		if ($fd = fopen($file_location,'r')){
	   	   $currency_list_ser = fread($fd,filesize($file_location));
	   	   fclose($fd);
		}
		$currency_list = array();
		$currency_list = unserialize($currency_list_ser);
        }

    	if ( empty($newoptions) )
	{
	   $options_flag=1;
      	   $newoptions = array(
	   	'currency_code1'=>'EUR',
	   	'currency_name1'=>'Euro',
	   	'currency_code2'=>'USD',
	   	'currency_name2'=>'American Dollar',
	   	'title'=>'Euro to American Dollar Conversion',
           	'country_code1' => 'EU',
           	'country_code2' => 'US',
   		'layout' => 'vertical',
           	'width' => '150',
           	'text_color' => '#000000',
           	'border_color' => '#BBBBBB',
           	'background_color' => '#FFFFFF',
           	'transparentflag'=>'0'
	   );
	}

	if ( $_POST['currency-conversion-guide-submit'] ) {
	     $options_flag=1;
	      $currency_code1 = strip_tags(stripslashes($_POST['currency-conversion-guide-currency-code1']));
	      $currency_code2 = strip_tags(stripslashes($_POST['currency-conversion-guide-currency-code2']));
              $newoptions['currency_code1'] = $currency_code1;
	      $newoptions['currency_name1'] = $currency_list[$currency_code1]['currency_name'];
	      $newoptions['country_code1'] = $currency_list[$currency_code1]['country_code'];
              $newoptions['currency_code2'] = $currency_code2;
	      $newoptions['currency_name2'] = $currency_list[$currency_code2]['currency_name'];
	      $newoptions['country_code2'] = $currency_list[$currency_code2]['country_code'];
              $newoptions['layout'] = strip_tags(stripslashes($_POST['currency-conversion-guide-layout']));
              $newoptions['title'] = $currency_name1 . " to ". $currency_name2 ;
              $newoptions['width'] = strip_tags(stripslashes($_POST['currency-conversion-guide-width']));
              $newoptions['text_color'] = strip_tags(stripslashes($_POST['currency-conversion-guide-text-color']));
              $newoptions['border_color'] = strip_tags(stripslashes($_POST['currency-conversion-guide-border-color']));
              $newoptions['background_color'] = strip_tags(stripslashes($_POST['currency-conversion-guide-background-color']));
              $newoptions['transparentflag'] = strip_tags(stripslashes($_POST['currency-conversion-guide-transparent-flag']));
        }


      	if ( $options_flag ==1 ) {
              $options = $newoptions;
              update_option('currency_conversion_guide', $options);
      	}

      	// Extract value from vars
      	$currency_code1 = htmlspecialchars($options['currency_code1'], ENT_QUOTES);
	$currency_name1 = htmlspecialchars($options['currency_name1'], ENT_QUOTES);
      	$currency_code2 = htmlspecialchars($options['currency_code2'], ENT_QUOTES);
	$currency_name2 = htmlspecialchars($options['currency_name2'], ENT_QUOTES);
	$title = $currency_name1  . " to " . $currency_name2;
      	$country_code1 = htmlspecialchars($options['country_code1'], ENT_QUOTES);
      	$country_code2 = htmlspecialchars($options['country_code2'], ENT_QUOTES);
      	$layout = htmlspecialchars($options['layout'], ENT_QUOTES);
      	$width = htmlspecialchars($options['width'], ENT_QUOTES);
      	$text_color = htmlspecialchars($options['text_color'], ENT_QUOTES);
      	$border_color = htmlspecialchars($options['border_color'], ENT_QUOTES);
      	$background_color = htmlspecialchars($options['background_color'], ENT_QUOTES);
      	$transparentflag = htmlspecialchars($options['transparentflag'], ENT_QUOTES);

      	echo '<ul><li style="text-align:center;list-style: none;"><label for="currency-conversion-guide-title">Currency Conversion Guide<br> by <a href="http://fx-rate.net">fx-rate.net</a></label></li>';

       	// Get currency, length and label type 


       	echo '<li style="list-style: none;"><label for="currency-conversion-guide-currency-code1">From Currency:'.
               '<select id="currency-conversion-guide-currency-code1" name="currency-conversion-guide-currency-code1" style="width:125px">';
      	echo '<OPTION value=""></option>';
     	ccg_print_thecurrency_list($currency_code1, $currency_list);
      	echo '</select></label></li>';


       	echo '<li style="list-style: none;"><label for="currency-conversion-guide-currency-code2">To Currency:'.
               '<select id="currency-conversion-guide-currency-code2" name="currency-conversion-guide-currency-code2" style="width:125px">';
      	echo '<OPTION value=""></option>';
     	ccg_print_thecurrency_list($currency_code2, $currency_list);
      	echo '</select></label></li>';

      	// Set layout type
      	echo '<li style="list-style: none;"><label for="currency-conversion-guide-label-type">'.'Layout:&nbsp;&nbsp;';
       	echo '<select id="currency-conversion-guide-layout" name="currency-conversion-guide-layout"  style="width:120px" >';
      	ccg_print_layout_list($layout);
      	echo '</select></label>';
      	echo '</li>';


      	// Set Width
	echo "\n";
      	echo '<li style="list-style: none;text-align:bottom"><label for="currency-conversion-guide-width">'.'Width: &nbsp;&nbsp;&nbsp;'.
         '<select id="currency-conversion-guide-width" name="currency-conversion-guide-width"  style="width:75px">';
      	ccg_print_thewidth_list($width);
      	echo '</select></label></li>';

      	// Set Text Widget color
      	echo '<li style="list-style: none;"><label for="currency-conversion-guide-text-color">'.'Text Color: &nbsp;&nbsp;&nbsp;&nbsp;';
       	echo '<select id="currency-conversion-guide-text-color" name="currency-conversion-guide-text-color"  style="width:95px" >';
      	ccg_print_textcolor_list($text_color);
      	echo '</select></label>';
      	echo '</li>';

      	// Set Border Widget color
      	echo '<li style="list-style: none;"><label for="currency-conversion-guide-border-color">'.'Header Color:&nbsp;';
       	echo '<select id="currency-conversion-guide-border-color" name="currency-conversion-guide-border-color"  style="width:95px" >';
      	ccg_print_bordercolor_list($border_color);
      	echo '</select></label>';
      	echo '</li>';

      	// Set Background Widget color
      	echo '<li style="list-style: none;"><label for="currency-conversion-guide-background-color">'.'Background Color:&nbsp;';
       	echo '<select id="currency-conversion-guide-background-color" name="currency-conversion-guide-background-color"  style="width:95px" >';
      	ccg_print_backgroundcolor_list($background_color);
      	echo '</select></label>';
      	echo '</li>';


	//   Transparent option

	$transparent_checked = "";
	if ($transparentflag =="1")
	   $transparent_checked = "CHECKED";
	echo "\n";
        echo '<li style="list-style: none;"><label for="currency-conversion-guide-transparent-flag"> Transparent: 
	<input type="checkbox" id="currency-conversion-guide-transparent-flag" name="currency-conversion-guide-transparent-flag" value=1 '.$transparent_checked.' /> 
	</label></li>';


      	// Hidden "OK" button
      	echo '<label for="currency-conversion-guide-submit">';
      	echo '<input id="currency-conversion-guide-submit" name="currency-conversion-guide-submit" type="hidden" value="Ok" />';
      	echo '</label>';

        echo '<label for="currency-conversion-guide-title"> <input type="hidden" id="currency-conversion-guide-title" name="currency-conversion-guide-title" value="'.$title.'" /> </label>';

	echo '</ul>';


    }


    /////////////////////////////////////////////////////////////////////////////////////////////////////
    //
    //	OUTPUT TABLE WIDGET
    //
    /////////////////////////////////////////////////////////////////////////////////////////////////////

     function currency_conversion_guide($args) 
     {

	// Get values 
      	extract($args);

      	$options = get_option('currency_conversion_guide');

      	// Extract value from vars
      	$currency_code1 = htmlspecialchars($options['currency_code1'], ENT_QUOTES);
	$currency_name1 = htmlspecialchars($options['currency_name1'], ENT_QUOTES);
      	$currency_code2 = htmlspecialchars($options['currency_code2'], ENT_QUOTES);
	$currency_name2 = htmlspecialchars($options['currency_name2'], ENT_QUOTES);
	$title = $currency_name1 . " to " . $currency_name2 . " Conversion";
      	$country_code1 = htmlspecialchars($options['country_code1'], ENT_QUOTES);
      	$country_code2 = htmlspecialchars($options['country_code2'], ENT_QUOTES);
      	$layout = htmlspecialchars($options['layout'], ENT_QUOTES);
      	$width = htmlspecialchars($options['width'], ENT_QUOTES);
      	$text_color = htmlspecialchars($options['text_color'], ENT_QUOTES);
      	$border_color = htmlspecialchars($options['border_color'], ENT_QUOTES);
      	$background_color = htmlspecialchars($options['background_color'], ENT_QUOTES);
      	$transparentflag = htmlspecialchars($options['transparentflag'], ENT_QUOTES);


	if($transparentflag == "1"){
  	     $background_color ="";
  	     $border_color ="";
	}

	if($currency_code)
		$length = "medium";

	$text_color = str_replace("#","",$text_color);

	echo $before_widget; 


	// Output title
	echo $before_title . $title . $after_title; 
	

	// Output calculator

	$widget_call_string = 'http://fx-rate.net/wp_conversion.php?';
	if($currency_code1) $widget_call_string .= 'currency='.$currency_code1 ."&";
	$widget_call_string .="tcolor=". $text_color ."&";
	if($currency_code2) $widget_call_string .= 'currency_pair='.$currency_code2 ;

	$widget_call_string .="&layout=". $layout;




	$target_url= "http://fx-rate.net/$currency_code1/$currency_code2/";
	
	$tsize=12;
#	if($layout == "vertical" && $length =="short") $tsize = 10;

#
#
#


	echo '<!-Currency Conversion Guide widget - HTML code - fx-rates.net -->
<div  style="width:'.$width.'px; background-color:'.$background_color.';border:2px solid #888;text-align:center;margin: 0px; padding: 0px;margin-top:10px!important">';

	echo '<div style="margin: 0px; padding: 0px;text-align:center;align:center;background-color:'.$border_color. ';border-bottom:1px solid #888;width:100%">
 	     <a style="font-size:'.$tsize.'px!important;line-height:16px!important;font-family:arial;text-weight:bold;margin-bottom:6px;text-decoration:none;color:#'.$text_color.'" href="'.$target_url.'">';

	
	echo "<b>" . $title . '</b></a></div>';
     	echo'<script type="text/javascript" src="'.$widget_call_string.'"></script></div><!-end of code-->';
	echo $after_widget;


    }
  
    register_sidebar_widget('Currency Conversion Guide', 'currency_conversion_guide');
    register_widget_control('Currency Conversion Guide', 'currency_conversion_guide_control', 245, 300);


}


add_action('plugins_loaded', 'currency_conversion_guide_init');



include("functions.php");


?>