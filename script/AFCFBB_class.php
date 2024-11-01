<?php 
/* Class pour gestion des modifications de la string html passée va le shortcode. Défini automatiquement la balise du champ selectionné par le tag. Modification ensuite des attribut select (radio, CB, select) ou value (text)

-------------------------------------------------------------------------------- */
include_once('simple_html_dom.php');
class AFCFBB_class{
	// availbale tag array type=>tag
	private $availableTag=array('select'=>'select', 'radio'=>'input', 'checkbox'=>'input', 'textarea'=>'textarea', 'text'=>'input' );
	public function __construct() {
	}
	public function getModification($atts, $content){
		$html=new simple_html_dom();
		$html->load($content);
		$type=isset($atts['type'])?$atts['type']:$this->getType($html);
		
		switch($type){
			case "select" :
				return $this->getSelectModification($atts, $html);
			break;
			case "text" :
				return $this->getInputModification($atts, $html);
			break;
			case 'checkbox' :
				return $this->getCheckboxModification($atts, $html);
			break;
			case 'radio' :
				return $this->getRadioModification($atts, $html);
			break;
			case 'textarea' :
				return $this->getTextareaModification($atts, $html);
			break;

			default :
			return $html;
		}
		
	}
	/* Fonctions SELECT 
	------------------------------------------------ */
	private function getSelectModification($atts, $html){
		$getvarName=$atts['getvar'];
		$meth=isset($atts['meth'])?$atts['meth']:'id';
		$value=isset($_GET[$getvarName])?$_GET[$getvarName]:NULL;
		
		if($value==NULL)return $html;
		
		//pb autocomplete FF !!
		$html->find('select', 0)->autocomplete="off";
		
		switch($meth){
			case 'id' :
				$html->find('select', 0)->find('option', $value)->selected="selected";
			break;
			case 'value' :
				$opt=$html->find('select', 0)->find('option');
				foreach ($opt as $k)if($k->value==$value)$k->selected="selected";				
			break;
			case 'add' :
			case 'rep' :
				$options=get_option('afcfbb_options');
				$parent=$html->find('select', 0);
				$allVal=explode('$', $value);
				$parent=$this->addElement($meth, $parent, $options['select_string'], $allVal);
			break;
		}		
		return $html;	
	}
	/* Fonctions INPUT 
	------------------------------------------------ */
	private function getInputModification($atts, $html){
		$getvarName=$atts['getvar'];
		$value=isset($_GET[$getvarName])?$_GET[$getvarName]:NULL;
		if($value==NULL)return $html;
		
		//pb autocomplete FF !!
		$html->find('input', 0)->autocomplete="off";
		$html->find('input', 0)->value=$value;
		return $html;
	}
	/* Fonctions Textarea 
	------------------------------------------------ */
	private function getTextareaModification($atts, $html){
		$getvarName=$atts['getvar'];
		$value=isset($_GET[$getvarName])?$_GET[$getvarName]:NULL;
		if($value==NULL)return $html;
		
		//pb autocomplete FF !!
		$html->find('textarea', 0)->autocomplete="off";
		$html->find('textarea', 0)->innertext=$value;
		return $html;
	}
	/* Fonctions Checkboxes
	------------------------------------------------ */
	private function getCheckboxModification($atts, $html){
		$getvarName=$atts['getvar'];
		$meth=isset($atts['meth'])?$atts['meth']:'id';
		$value=isset($_GET[$getvarName])?$_GET[$getvarName]:NULL;
		
		if($value==NULL)return $html;
		
		$cbToCheck=explode('$', $value);
		switch($meth){
			case 'id' :
				foreach($cbToCheck as $v){
					//$html.="<p> val :".$v."</p>";
					$html->find('input', $v)->autocomplete="off";
					$html->find('input', $v)->checked="checked";
				}
			break;
			case 'value' :
				$opt=$html->find('input');
				foreach ($opt as $k)if(in_array($k->value, $cbToCheck))$k->checked="checked";
			break;
			case 'add' :
			case 'rep' :
				$options=get_option('afcfbb_options');
				$parent=$this->getParent($html);
				if($options['use_copy']){
					$node=$html->find('[class^=wpcf7-list-item]', 0);
					if(isset($node)){
						$cbval=$node->find('[type^=checkbox]',0)->value;
						$str=preg_replace('/'.$cbval.'/','$value$',$node->outertext);
					}
				}
				if(!$str)
					$str=$options['checkbox_string'];
				
				$parent=$this->addElement($meth, $parent,$str,$cbToCheck,$this->getFieldName($html));
			break;
		}		
		return $html;
		
	}
	
	
	/* Fonctions Radio
	------------------------------------------------ */
	private function getRadioModification($atts, $html){
		$getvarName=$atts['getvar'];
		$meth=isset($atts['meth'])?$atts['meth']:'id';
		$value=isset($_GET[$getvarName])?$_GET[$getvarName]:NULL;
		
		if($value==NULL)return $html;
		
		switch($meth){
			case 'id' :
					$html->find('input', $value)->autocomplete="off";
					$html->find('input', $value)->checked="checked";
			break;
			case 'value' :
				$opt=$html->find('input');
				foreach ($opt as $k)if($k->value==$value)$k->checked="checked";
			break;
			case 'add' :
			case 'rep' :
				$options=get_option('afcfbb_options');
				$parent=$this->getParent($html);
				$values=explode('$', $value);
				if($options['use_copy']){
					$node=$html->find('[class^=wpcf7-list-item]', 0);
					if(isset($node)){
						$cbval=$node->find('[type^=radio]',0)->value;
						$str=preg_replace('/'.$cbval.'/','$value$',$node->outertext);
					}
				}
				if(!$str)
					$str=$options['radio_string'];		
				
				$parent=$this->addElement($meth, $parent,$str, $values, $this->getFieldName($html));
			break;	
		}		
		return $html;
		
	}
	private function getParent($html){
		$options=get_option('afcfbb_options');
		
		if($html->find('[class*=wpcf7-list-item]',0)){
			$parent=$html->find('[class*=wpcf7-list-item]',0)->parentNode();
		}else{
			$count=count($html->find('[class*='.$options['cf7_wrap'].']'));
			$parent=$html->find('[class*='.$options['cf7_wrap'].']',$count-1);
		}
		return $parent;
	}
	
	
	private function addElement($meth, $parent, $str, $values, $name=NULL){
		if($meth=='rep')$parent->innertext="";
		foreach($values as $val){
					$newchild=new simple_html_dom();
					$selected=substr($val,0,1)=='*';
					$val=($selected)?substr($val,1):$val;
					$hstr=preg_replace('/\$value\$/',$val,$str);	
					if($name!=NULL)$hstr=preg_replace('/\$name\$/',$name,$hstr);
					$newchild->load($hstr);					
					if($selected){
						$newchild->find('input',0)->checked="checked";
						$newchild->find('option', 0)->selected="selected";
					}
					$parent->innertext =$parent->innertext.$newchild->outertext;
				}
			return $parent;
	}
	/* detection balise pour Type 
	------------------------------------------------ */
	private function getType($html){
		$curr=$html;
		foreach($this->availableTag as $type=>$tag){
			if(count($html->find('[class*='.$type.']'))>0)return $type;
			if($type==$tag){
				if($curr->find($tag))return $tag;
			}else {
				if($curr->find($tag)&&$curr->find($tag,0)->type==$type)return $type;				
			}
		}
		return "NO_tag";		
	}
	
	private function getFieldName($html){
		$options=get_option('afcfbb_options');
		$cclass=$html->find('[class*='.$options['cf7_wrap'].']',0)->class;		
		$name=preg_replace('#'.$options['cf7_wrap'].'.*\s#','',$cclass);
		return $name;
	}
	
}

?>