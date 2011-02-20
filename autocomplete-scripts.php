<?php
header( 'Content-Type: application/javascript' );
	function get_root_directory() {
		$arr_directory = explode('/',dirname(__FILE__));
		for($i=0;$i<count($arr_directory);$i++) {
			$strreturn = '';
			for($j=0;$j<$i;$j++) {
				$strreturn .= $arr_directory[$j].'/';
			}
			if (@file_exists($strreturn.'wp-config.php')) {
				return $strreturn;
			}
		}
		return '';
	}
$path = get_root_directory();
require_once($path.'/wp-config.php');
$wpdb =& $GLOBALS['wpdb'];
$options = $wpdb->get_results("SELECT * FROM `wp_options` WHERE option_name LIKE 'autocomplete_%'");
$arr_options = array();
foreach ($options as $option) {
	$arr_options[$option->option_name] = $option->option_value;
}
?>
(function($) {
	$(function() {
		$('<?php echo $arr_options['autocomplete_search_id'] ?>').autocomplete({
			source: '<?php echo dirname($_SERVER["REQUEST_URI"]).'/includes/tags.php'; ?>',
			minLength: <?php echo $arr_options["autocomplete_minimum"]; ?>,
      select: function(event, ui) {
      	if (ui.item.url !== 'none') {
        	location = ui.item.url;
        } else {
      		$(this).val(ui.item.label);
        }
      }
		});
	});
})(jQuery);
<?php
?>