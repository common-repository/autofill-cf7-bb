<?php
class afcfbb_options {
	private $sections;
	private $checkboxes;
	private $selcheckboxes;
	
	public function __construct() {
		$this->checkboxes = array();
		$this->selcheckboxes = array();
		
		$this->sections['default_option']      = __( 'Default Options', AFCFBB_TEXT_DOMAIN );
		$this->sections['shortcode_use']      = __( 'Shortcode Uses', AFCFBB_TEXT_DOMAIN );
		$this->sections['about']      = __( 'About', AFCFBB_TEXT_DOMAIN );
		add_action( 'admin_menu', array( &$this, 'add_pages' ) );
		add_action( 'admin_init', array( &$this, 'register_settings' ) );
		add_action( 'admin_head', array( &$this, 'admin_css' ) );
	}
	public function add_pages() {
		$page=add_plugins_page(  __( 'autofill-CF7-BB', AFCFBB_TEXT_DOMAIN ), __( 'autofill-CF7-BB', AFCFBB_TEXT_DOMAIN ), 'manage_options', "afcfbb-plugin-options", array( &$this, 'display_option_page' ));		
		add_action( 'admin_print_scripts-' .$page, array( &$this, 'scripts' ) );		
	}
	
	public function display_option_page() {
		$path2=get_home_url(null, "/wp-admin/admin.php?page=afcfbb-plugin-options");
		
		echo '<div class="wrap">
		<div class="icon32" id="icon-options-general"></div>
		<h2>' . __( 'autofill-CF7-BB Options page', AFCFBB_TEXT_DOMAIN ) . '</h2>';
		echo '<form action="options.php" method="post">';
		settings_fields( 'afcfbb_options' );
		echo '<div class="ui-tabs">
			<ul class="ui-tabs-nav">';
		
		foreach ( $this->sections as $sectName=>$section )
			echo '<li><a href="#' . strtolower( str_replace( ' ', '_',$this->stripAccents($section) ) ) .  '">' . $section . '</a></li>';
		
		
		echo '</ul>';
		do_settings_sections( $_GET['page'] );
		echo '</div>';
		echo '</form>';
		echo '<script type="text/javascript" src="'.WP_PLUGIN_URL.'/autofill-CF7-BB/script/AFCFBB_js.js"></script>';
		echo '</div>';
	
	}
	public function register_settings() {
		register_setting( 'afcfbb_options', 'afcfbb_options', array ( &$this, 'validate_settings' ) );
		
		add_settings_section( 'default_option', $this->sections['default_option'], array( &$this, 'display_option_section' ), 'afcfbb-plugin-options' );
		add_settings_section( 'shortcode_use', $this->sections['shortcode_use'], array( &$this, 'display_shortcode_section' ), 'afcfbb-plugin-options' );
		add_settings_section( 'about', $this->sections['about'], array( &$this, 'display_about_section' ), 'afcfbb-plugin-options' );
					
		$this->create_option_setting( array(
			'id'      => 'use_copy',
			'title'   => __( 'Prefer use field copy' , AFCFBB_TEXT_DOMAIN),
			'desc'    => 'uncheck it if you want systematically use above html instead of trying copy a current field. It needs at least one input choice for adding',
			'std'     => '',
			'type'    => 'checkbox',
			'section' => 'default_option'
			), 'display_setting' );
			
		$this->create_option_setting( array(
			'id'      => 'select_string',
			'title'   => __( 'Select string' , AFCFBB_TEXT_DOMAIN),
			'desc'    => '',
			'std'     => '',
			'type'    => 'textarea',
			'section' => 'default_option'
			), 'display_setting' );
			
		$this->create_option_setting( array(
			'id'      => 'radio_string',
			'title'   => __( 'Radio string' , AFCFBB_TEXT_DOMAIN),
			'desc'    => '',
			'std'     => '',
			'type'    => 'textarea',
			'section' => 'default_option'
			), 'display_setting' );
			
		$this->create_option_setting( array(
			'id'      => 'checkbox_string',
			'title'   => __( 'Checbox string' , AFCFBB_TEXT_DOMAIN),
			'desc'    => '',
			'std'     => '',
			'type'    => 'textarea',
			'section' => 'default_option'
			), 'display_setting' );
			
		$this->create_option_setting( array(
			'id'      => 'cf7_wrap',
			'title'   => __( 'Contact Form 7 wrapper class' , AFCFBB_TEXT_DOMAIN),
			'desc'    => '',
			'std'     => '',
			'type'    => 'text',
			'section' => 'default_option'
			), 'display_setting' );
		
	}
	public function display_setting( $args = array() ) {
		
		extract( $args );
		
		$options = get_option( 'afcfbb_options' );
		
		if ( !isset( $options[$id] ) && 'type' != 'checkbox' )
			$options[$id] = $std;
		
		$field_class = '';
		if ( $class != '' )
			$field_class = ' ' . $class;
		
		switch ( $type ) {
			
			case 'heading':
				echo '</td></tr><tr valign="top"><td colspan="2">' . $desc;
				break;
			case 'checkbox':
				
				echo '<input class="checkbox' . $field_class . '" type="checkbox" id="' . $id . '" name="afcfbb_options[' . $id . ']" value="1" ' . checked( $options[$id], 1, false ) . ' /> <label for="' . $id . '">' . $id . '</label>';
				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';
				
				break;
				
			case 'tinyMCETA' :
				echo '<div id="poststuff">';
				echo '<div id="editor-toolbar">';
				echo '<a id="edButtonHTML" class="toggleHTML" name="'.$id.'">HTML</a>
					<a id="edButtonPreview" class="toggleVisual" name="'.$id.'">Visuel</a>';
				echo '</div>';
				
				echo '<textarea class="tinyMCETA" id="' . $id . '" name="afcfbb_options[' . $id . ']" placeholder="' . $std . '" rows="5" cols="30">' . $options[$id] . '</textarea>';
				echo '</div>';
				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';
				break;
				
				case 'textarea':
				echo '<textarea class="' . $field_class . '" id="' . $id . '" name="afcfbb_options[' . $id . ']" placeholder="' . $std . '" rows="5" cols="30">' . $options[$id] . '</textarea>';
				
				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';
				
				break;
					
			case 'text':
			default:
		 		echo '<input class="regular-text' . $field_class . '" type="text" id="' . $id . '" name="afcfbb_options[' . $id . ']" placeholder="' . $std . '" value="' . $options[$id] . '" />';
		 		if ( $desc != '' )
		 			echo '<br /><span class="description">' . $desc . '</span>';
		 		
		 		break;
		 	
		}
		
	}
	public function create_option_setting( $args = array(), $innerCallBack='display_setting' ) {
		
		$defaults = array(
			'id'      => 'default_field',
			'title'   => __( 'Default Field', AFCFBB_TEXT_DOMAIN ),
			'desc'    => __( 'This is a default description.', AFCFBB_TEXT_DOMAIN ),
			'std'     => '',
			'type'    => 'text',
			'section' => 'general',
			'choices' => array(),
			'class'   => ''
		);
			
		extract( wp_parse_args( $args, $defaults ) );
		
		$field_args = array(
			'type'      => $type,
			'id'        => $id,
			'desc'      => $desc,
			'std'       => $std,
			'choices'   => $choices,
			'label_for' => $id,
			'class'     => $class
		);
		
		add_settings_field( $id, $title, array( $this, $innerCallBack ), 'afcfbb-plugin-options', $section, $field_args );	
		if ( $type == 'checkbox' )
			$this->selcheckboxes[] = $id;	
	}
	public function validate_settings( $input ) {
		$c=count($this->selcheckboxes);
		for ($i=0; $i<$c; $i++){
			$idCB=	$this->selcheckboxes[$i];
			$input[$idCB]=(isset($input[$idCB]))?true:false;
		}		
		return $input;		
	}
	
	public function scripts() {		
		wp_enqueue_script( 'jquery-ui-tabs' );
		wp_enqueue_script(array('jquery', 'editor', 'thickbox', 'media-upload'));
		wp_enqueue_style('thickbox');
	}
	
	public function admin_css() {
		
		 $path=WP_PLUGIN_URL."/autofill-CF7-BB/script/" ;
		if ( isset( $_GET['page'] ) && $_GET['page'] == 'afcfbb-plugin-options' ){
			echo '<link rel="stylesheet" href="' . $path . 'AFCFBB_style.css" />' . "\n";
			wp_tiny_mce( false );
		}
			
	}
	public function display_option_section($args){
		$options = $this->get_default_options();
		echo "<div class='afcfbb'><p> <b>Be carefull by changing these settings !</b> it could break the shortcode use and lead to unwanted result with contact form 7</p>
		<p> Each field correspond to the html finger print of the different type of field, for the adding method of atofill-CF7-BB</p>";
		echo '<input name="Submit" type="submit" class="button-primary" value="' . __( 'Save Changes', AFCFBB_TEXT_DOMAIN ) . '" />';
		echo '<input name="Reset" type="button" class="button" value="' . __( 'Reset to default', AFCFBB_TEXT_DOMAIN ) . '" onclick="afcfbb_reset_to_default(\''.urlencode($options["select_string"]).'\',\''.urlencode($options["radio_string"]).'\',\''.urlencode($options["checkbox_string"]).'\',\''.urlencode($options["cf7_wrap"]).'\');"/>';
		echo "<p> use <i><b>\$value\$</b></i> in each field where the <i><u>value</u></i> should be inserted and <i><b>\$name\$</b></i> for <i><u>name</u></i> attribute</p></div>";
	}
	
	public function display_about_section($args){
			$copyright_year = ( date( 'Y' ) == '2011' ? '2011' : '2011&ndash;' . date( 'Y' ) );
		
		echo '<h4>' . sprintf( __( 'autofill-CF7-BB %s version %s', AFCFBB_TEXT_DOMAIN ), '<small class="quiet">', AFCFBB_VERSION_NUM ) . '</small></h4>';
		echo '<p><span class="description"> Author <a href="http://asblog.etherocliquecite.eu" target="_blank" title="' . __( 'Visit BillyBen Page', AFCFBB_TEXT_DOMAIN ) . '">BillyBen</a></span></p>';
		
		echo '<p>&copy;' . $copyright_year . ' <a href="http://asblog.etherocliquecite.eu/?page_id=774&lang=en" target="_blank" title="visit afcfbbpage">' . __( 'Visit autofill-CF7-BB Page', AFCFBB_TEXT_DOMAIN ) . '</a>.</p><p>
		
		' . sprintf( __( 'autofill-CF7-BB is licensed under the %s GNU General Public License version 2.0%s', AFCFBB_TEXT_DOMAIN ), '<a href="http://www.gnu.org/licenses/gpl-3.0-standalone.html">', '</a>.</p>' );
		
		echo '<h4> '.__("Support autofill-CF7-BB plugin", 	 AFCFBB_TEXT_DOMAIN ).'</h4>';
		echo '<p>'. __("If you like AFCFBB plugin you could support it's creator by donating, it should help it's dev!", 	 AFCFBB_TEXT_DOMAIN ).'</p>';
		echo '<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
">
<input type="image" src="https://www.paypalobjects.com/WEBSCR-640-20110429-1/en_GB/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online.">
<img alt="" border="0" src="https://www.paypalobjects.com/WEBSCR-640-20110429-1/fr_FR/i/scr/pixel.gif" width="1" height="1">
</form>';
}

public function display_shortcode_section($args){
		echo '<div class="afcfbb"><h4>'.__('Selection Use',BBSWF_TEXT_DOMAIN).':</h4>
		<P>First of all, go into the template editor of contact form 7, and choose the template which you want to "autofill".</br>

the shortcode looks like :</p>
<pre>[AFCF_BB getvar="myVar"][CF7 field][/AFCF_BB]</pre>
<p>* getvar : the URL GET variable name used to fill the CF7 field
eg : </p><pre>[AFCF_BB getvar="myId"][select whatevername "Roger" "Jessica" "Steve" "Suzie"][/AFCF_BB]</pre>
<p>and url as : <pre>http://mysite.com/?page_id=1&<b>myId=2</b></pre></p>	

<p>will select id 2 for the select field so "Steve" (numbering start by 0 (not 1)).</p>
<P>For checkboxes, you can provide multi selected value (or id) with the get variable. You should séparate each value by the caracter <b>\'$\'</b>
eg url as : <pre>http://mysite.com/?page_id=1&<b>myId=2$3$5</b></pre></p>
<p>* For select, radio and checkboxes fields, you can spécify wether it will select by id or by value of the field, by the parameter meth:</p>
<pre>[AFCF_BB getvar="myId" <b>meth="id"</b>][field][/AFCF_BB]</pre>
<p>or</p>
<pre>[AFCF_BB getvar="myId" <b>meth="value"</b>][field][/AFCF_BB]</pre>
<p>By default it\'s set to id.</br>
For <i><u>checkboxes</u></i>, multiselection is available. You have to pass the different id or value separating them by \'$\' caracter eg :</br>
 http://mysite.com/?page_id=1&<b>myId=2$4$3</b>
</p>


<h4>'.__('Addition Use',BBSWF_TEXT_DOMAIN).':</h4>
<p>"meth" parameter can accept <b>\'add\'</b> value, so it will add url get var to the diferent option. You can add several values to the field by separating them with \'$\' caracter, eg :</p>

<p>Initial field : o radio 1 o radio 2 o radio 3</p>
<pre>defined by : [radio radio-XXX "radio 1" "radio 2" "radio 3"]</pre>
<pre>with : [AFCF_BB getvar=\'brid\' <b>meth=\'add\'</b>][radio radio-XXX "radio 1" "radio 2" "radio 3"][/AFCF_BB]</pre>

<pre>and url : http://www.msite.com/?page_id=XX&<b>brid=radio 4&radio 5</b></pre>

<p>will result in : o radio 1 o radio 2 o radio 3 o radio 4 o radio 5</p>

<h4>'.__('Replacement Use',BBSWF_TEXT_DOMAIN).':</h4>
<p>"meth" parameter can accept <b>\'rep\'</b> value, so it will replace previous options (set in CF7 template editor) by url get var. You can add several values to the field by separating them with \'$\' caracter, eg :</p>

<p>Initial field : o radio 1 o radio 2 o radio 3</p>
<pre>defined by : [radio radio-XXX "radio 1" "radio 2" "radio 3"]</pre>
<pre>with : [AFCF_BB getvar=\'brid\' <b>meth=\'rep\'</b>][radio radio-XXX "radio 1" "radio 2" "radio 3"][/AFCF_BB]</pre>

<pre>and url : http://www.msite.com/?page_id=XX&<b>brid=radio 4&radio 5</b></pre>

<p>will result in : o radio 4 o radio 5</p>
<h4>'.__('Preselect Values',BBSWF_TEXT_DOMAIN).':</h4>
<p>You can pass values which could be directly selected. You ust have to add "*" character before the value you want to be selected, eg :</br>
<pre>http://mysite.com/?page_id=1&<b>addval=*Steeve$Rebecca$*julie</b></pre>
will add (or replace) values with Steeve, Rebecca and Julie with Steeve and Julie selected.
</p>

<h4>'.__('Field Type detection',BBSWF_TEXT_DOMAIN).':</h4>
<p>autofill-CF7-BB would autodetect what kind of field is selected. But, I\'m certainly not know very well CF7, so it may fail to detect.... So you can specify what king of field it is :</p>

<pre>[AFCF_BB getvar="myId" <b>type="select"</b>][field][/AFCF_BB]</pre>

<p>available value for type :
<ol>
<li> select</li>
<li> radio </li>
<li> checkbox</li>
<li> text</li>
</ol></p>

<h4>'.__('Tips',BBSWF_TEXT_DOMAIN).':</h4>
<h5> For Adding method (meth="add")</h5>
<p>If you want to <i>fill entirely a field from 0</i>, autofill-CF7-BB would fail in detecting the field type</br>
To prevent it, you could either :
<ol>
<li> fill the type attribute of the shortcode : [AFCF_BB getvar="nn" <b>type="checkbox"</b>]...</li>
<li> use a field name containing the type : [AFCF_BB getvar="nn" ][checkbox <b>checkbox-XXX</b>][/AFCF_BB]...</li>
</ol>
</p>
<h5> For filling input options from 0 </h5>
<p> Do not hesitate to define at least 1 options in CF7 template editor for your field, and use "replacement" method of autofill-CF7-BB with "use_copy" setting (option page) so the adding would fit exactly your CF7 settings.</p>
<p> For Select field, if it\'s at first empty, prefer using "replacement" method, it would prevent the automatic addition of an "---" option at first</p>

</div>
';
	}
	
	
	// utilitaire
	function stripAccents($string){
	
     $remplace = array('à'=>'a',
                         'á'=>'a',
                         'â'=>'a',
                         'ã'=>'a',
                         'ä'=>'a',
                         'å'=>'a',
                         'ò'=>'o',
                         'ó'=>'o',
                         'ô'=>'o',
                         'õ'=>'o',
                         'ö'=>'o',
                         'è'=>'e',
                         'é'=>'e',
                         'ê'=>'e',
                         'ë'=>'e',
                         'ì'=>'i',
                         'í'=>'i',
                         'î'=>'i',
                         'ï'=>'i',
                         'ù'=>'u',
                         'ú'=>'u',
                         'û'=>'u',
                         'ü'=>'u',
                         'ÿ'=>'y',
                         'ñ'=>'n',
                         'ç'=>'c',
                         'ø'=>'0'
                         ); 
	return strtr($string,$remplace);
}
public static function get_default_options(){
	$default_opt=array(
		"select_string"=>'<option value="$value$">$value$</option>',
		"radio_string"=>'<span class="wpcf7-list-item"><input type="radio" name="$name$" value="$value$"><span class="wpcf7-list-item-label">$value$</span></span>',	
		"checkbox_string"=>'<span class="wpcf7-list-item"><input type="checkbox" name="$name$[]" value="$value$"><span class="wpcf7-list-item-label">$value$</span></span>',
		"cf7_wrap"=>"wpcf7-form-control",
	);
	return $default_opt;
}
// end class
}
?>