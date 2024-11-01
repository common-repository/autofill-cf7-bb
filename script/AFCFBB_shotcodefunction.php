<?php 

function afcfbb_shortcodehandler($atts, $content){
	include_once('AFCFBB_class.php');	
	$modeler=new AFCFBB_class();	
	$htmlString.=$modeler->getModification($atts,$content);	
	return $htmlString;	
}
?>