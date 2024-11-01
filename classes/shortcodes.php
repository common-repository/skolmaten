<?php
defined('ABSPATH') or die('This script cannot be accessed directly.');
/*
 *
 * Shortcodes for skolmaten
 *
 *	[skolmaten_vecka vecka=44 visavecka=true days_to_show=0 weektext="some text" title="title"]
 *
 */

new SNSkolmaten_Shortcodes();

class SNSkolmaten_Shortcodes
{
	function __construct()
	{
		add_shortcode('skolmaten_vecka', array($this, 'skolmaten_vecka'));
		add_shortcode('skolmaten_day', array($this, 'skolmaten_day'));
	}

	function skolmaten_vecka($atts)
	{
		wp_enqueue_style('skolmaten');
		$time = time();
		$day = date("N", $time);
		$week = date("W", $time);

		if ($day >= 6 && $week != 52)
			$week++;

		extract(shortcode_atts(array(
			'vecka' => $week,
			'alla_veckor' => false,
			'visavecka' => true,
			'days_to_show' => 7,
			'weektext' => false,
			'show_dates' => true,
			'title' => ""

		), $atts));

		$weeks_data = SNSkolmaten_API::get_data(0, $vecka);
		$dayarray = isset($weeks_data) ?  json_decode($weeks_data, TRUE) : false;
		$output = "";
		$output_days = "";
		$counter = 0;
		$prev_week = false;

		if (isset($dayarray) && is_array($dayarray) && isset($dayarray["channel"]["item"])) {
			foreach ($dayarray["channel"]["item"] as $item) {
				$title = explode(" ", sanitize_text_field($item["title"]));
				$day = sanitize_text_field($title[0]);
				$date = date("Y-m-d H:s:i", strtotime(sanitize_text_field($item["guid"])));
				$first_day = substr(strtotime($date), 0, 10);
				$meal = strip_tags($item["description"], "<br><strong>");
				$item_week = sanitize_text_field($title[3]);
				if ($alla_veckor || $item_week == $week) {
					if($alla_veckor && $prev_week != $item_week) {
						$title_str = $weektext ? $weektext : 'Maten vecka ' . esc_attr($item_week);
						$output_days .= "<li><h3>" . $title_str. "</h3></li>";
					}
					
					$prev_week = $item_week;
					$date_d = date("d", strtotime($date));
					$month = date("m", strtotime($date));
					$today = date("Y-m-d");
					$months = explode(",", "januari,februari,mars,april,maj,juni,juli,augusti,september, oktober,november,december");
					$date_m = ucfirst($months[($month - 1)]);
					$meal_str = $meal != "" ? $meal : "Det finns ingen information";

					if ($days_to_show == 1 && substr($date, 0, 10) == $today)
						$output_days .= '<li><strong>' . esc_attr($day . ' ' . $date_d . ' ' . $date_m) . '</strong><br />' . wp_kses_post($meal_str) . '</li>';
					elseif ($days_to_show != 1)
						$output_days .= '<li><strong>' . esc_attr($day . ' ' . $date_d . ' ' . $date_m) . '</strong><br />' . wp_kses_post($meal_str) . '</li>';
					$last_day = substr($date, 0, 10);
					$counter++;
					if ($days_to_show != 1 && $days_to_show != 0 && $counter >= $days_to_show)
						break;
						
				}
			}

			$text_about = get_option('skolmaten_texten');

			$title_str = $weektext != "" ? $weektext : 'Maten vecka ' . esc_attr($week);
			$title = $visavecka &&  $visavecka != "false" ? '<h4>' . esc_attr($title_str) . '</h4>' : "";
			$output .= '<div class="skolmaten_list">'
				. $title . ($show_dates &&  $show_dates != "false" ? '<span class="skolmaten_fromto">' . esc_attr($first_day) . ' - ' . esc_attr($last_day) . '</span>' : "") . '
		<p>' . esc_attr($text_about) . '</p>
		<ul>';
			$output .= wp_kses($output_days, ["br" => [], "strong" => [], "li" => []]) . '</ul></div>';
		}
		return $output;
	}

	/*
 *
 * Shortcodes for skolmaten
 *
 *	[skolmaten_dag vecka=44 days_to_show=0]
 *
 */

	function skolmaten_day($atts, $content = null)
	{
		$week = date("W", time());

		extract(shortcode_atts(array(
			'vecka' => $week,
			'days_to_show' => 1
		), $atts));

		return do_shortcode('[skolmaten_vecka vecka="' . $vecka . '" days_to_show="' . $days_to_show . '"]');
	}
}
