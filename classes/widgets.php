<?php

new SkolmatenWidget();

class SkolmatenWidget extends WP_Widget
{

	public function __construct()
	{

		// load plugin text domain
		add_action('init', array($this, 'widget_snillrik_skolmaten'));
		add_action('widgets_init', [$this, 'create_the_widget']);
		// Widget information
		parent::__construct(
			'skolmaten',
			__('Skolmaten widget', 'skolmaten'),
			array(
				'classname'  => 'skolmaten',
				'description' => __('Din skolmat widget som är en del av pluginet.', 'skolmaten')
			)
		);
	} // end constructor

	/*--------------------------------------------------*/
	/* Widget API Functions
	 /*--------------------------------------------------*/

	/**
	 * Outputs the content of the widget.
	 *
	 * @param array args  The array of form elements
	 * @param array instance The current instance of the widget
	 */
	public function widget($args, $instance)
	{
		extract($args, EXTR_SKIP);
		wp_enqueue_style("skolmaten", SNILLRIK_SKOLMATEN_PLUGIN_PATH . '/skolmaten.css', false, "1.2", "all");
		$widget_string = $before_widget;
		$number_to_show = isset($instance["number"]) && is_numeric($instance["number"]) ? $instance["number"] : 3;

		if (isset($instance["title"]) && $instance["title"] != "")
			echo "<h3 class='widget-title'>" . $instance["title"] . "</h3>";
		if (isset($instance["weektext"]))
			$widget_string .= do_shortcode("[skolmaten_vecka weektext='" . esc_attr($instance["weektext"]) . " days_to_show='" . intval($number_to_show) . "']");

		$widget_string .= $after_widget;

		echo $widget_string;
	} // end widget

	/**
	 * Generates the administration form for the widget.
	 *
	 * @param array instance The array of keys and values for the widget.
	 */
	public function form($instance)
	{
		wp_enqueue_style("skolmaten", SNILLRIK_SKOLMATEN_PLUGIN_PATH . '/skolmaten.css', false, "1.2", "all");
		// Display the admin form
		$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
		$number = isset($instance['number']) ? esc_attr($instance['number']) : 3;
		$weektext = isset($instance['weektext']) ? esc_attr($instance['weektext']) : "";
?>
		<p><label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Title:', 'title'); ?></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
		</p>
		<p><label for="<?php echo esc_attr($this->get_field_id('weektext')); ?>"><?php _e('Text istället för vecka:', 'weektext'); ?></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('weektext')); ?>" name="<?php echo esc_attr($this->get_field_name('weektext')); ?>" type="text" value="<?php echo esc_attr($weektext); ?>" />
		</p>
		<p><label for="<?php echo esc_attr($this->get_field_id('number')); ?>"><?php _e('Antal dagar (1 dag visar dagens meny, 0 eller tomt visar hela veckan och andra siffror visar antal dagar med början från måndag.):', 'number'); ?></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('number')); ?>" name="<?php echo esc_attr($this->get_field_name('number')); ?>" type="text" value="<?php echo esc_attr($number); ?>" />
		</p>
<?php

	} // end form

	/*--------------------------------------------------*/
	/* Public Functions
	 /*--------------------------------------------------*/

	/**
	 * Loads the Widget's text domain for localization and translation.
	 */
	public function widget_snillrik_skolmaten()
	{
		load_plugin_textdomain('skolmaten', false, plugin_dir_path(__FILE__) . 'lang/');
	} // end widget_textdomain

	public function create_the_widget()
	{
		register_widget("SkolmatenWidget");
	}
} // end class

?>