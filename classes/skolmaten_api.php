<?php
defined('ABSPATH') or die('This script cannot be accessed directly.');
/**
 * For fetching data from skolmaten.se
 */

//new SNSkolmaten_API();

class SNSkolmaten_API
{
    //get the data from skolmaten.se
    public static function get_data()
    {
        $urls = array();
        $week = date("W", time());
        $year = date("Y", time());
        $title = "";
        $vcontent = "";
        $names = explode("\r\n", get_option('skolmaten_adresses'));

        foreach ($names as $name) {
            $urls[] = "http://skolmaten.se/$name/rss/weeks/?limit=2";
        }

        $weeks_transient = get_transient('snillrik_skolmaten_data');

        if ($weeks_transient === false) {
            foreach ($urls as $url) {
                if (strpos($url, "http") !== false) {
                    $all_the_shit = array();
                    $content = wp_remote_get($url);
                    if (is_wp_error($content))
                        return;
                    if (strpos($content["body"], "Not found") > 0) {
                        add_action('admin_notices', function () use ($content) {
                            $class = 'notice notice-error';
                            $message = __("Antagligen fel namn p&aring; skolan ifyllt. (serverns svarade: " . esc_html($content["body"]) . ")");

                            printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), esc_html($message));
                        });
                        return;
                    }
                    if ($content["body"] == "")
                        return;

                    $xml = simplexml_load_string($content["body"], 'SimpleXMLElement', LIBXML_NOCDATA);
                    $weeks_transient = json_encode($xml);
                    set_transient('snillrik_skolmaten_data', $weeks_transient, 60 * 60 * 24);
                }
            }
        }
        return $weeks_transient;
    }
}
