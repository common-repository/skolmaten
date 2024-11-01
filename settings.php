<?php

// create custom plugin settings menu
add_action('admin_menu', 'skolmaten_create_menu');

function skolmaten_create_menu()
{
	add_menu_page(
		'Skolmaten Plugin Settings',
		'Skolmaten',
		'administrator',
		__FILE__,
		'skolmaten_settings_page',
		SNILLRIK_SKOLMATEN_PLUGIN_URL . 'images/snillrik_icon.svg'
	);
	add_action('admin_init', 'register_skolmaten_settings');
}

function register_skolmaten_settings()
{
	//register our settings
	$sanitize_args_str = array(
		'type' => 'string',
		'sanitize_callback' => 'sanitize_text_field',
	);

	register_setting('skolmaten-settings-group', 'skolmaten_adresses', $sanitize_args_str);
	register_setting('skolmaten-settings-group', 'skolmaten_texten', $sanitize_args_str);
}

function skolmaten_settings_page()
{
	$snillrik_logo = SNILLRIK_SKOLMATEN_PLUGIN_URL . 'images/snillrik_logo_modern.svg';
?>
	<div class="wrap snillrik-main-wrap snillrik-skolmaten-main-wrap">
		<div class="snillrik-main-left-side">
			<div class="snillrik-main-side-inner">
				<img src="<?php echo $snillrik_logo; ?>" alt="Snillrik logo" class="snillrik-logo" />
				<h1>Skolmaten</h1>
				<h3>Skolmaten är ett plugin som använder skolmaten.se's API för lite listiga funktioner och shortcodes för att visa upp veckans skolmat på din site.</h3>
				<div class="skolmatsettings-admin-settings">
					<div class="skolmatsettings-block">

						<form method="post" action="options.php">
							<?php settings_fields('skolmaten-settings-group'); ?>
							<?php do_settings_sections('skolmaten-settings-group'); ?>
							<table class="form-table">
								<tr>
									<td>
										<h3>Välj namnet på din skola</h3>
										<p>Välj namnet på din skola, det är det namnet som används i sista delen på adressen på Skolmaten.se.<br />tex. https://skolmaten.se/<strong>->dinskola<-</strong>/</p>
										<input id="skolmaten_adresses" name="skolmaten_adresses" value="<?php echo esc_attr(get_option('skolmaten_adresses')); ?>" />
									</td>
									<td>
										<h3>Texten om skolans mat</h3>
										<p>Skriv in en text som kommer synas i samband med menyn på sidan<br /><br /></p>
										<input id="skolmaten_texten" name="skolmaten_texten" value="<?php echo esc_attr(get_option('skolmaten_texten')); ?>" />
									</td>
								</tr>
							</table>

							<?php submit_button(); ?>
					</div>
					<div class="skolmatsettings-block">
						</form>
						<h2>Shortcodes</h2>
						Om du vill lägga till saker på en sida, i en widget eller så, så kan du använda shortcodes och bara klistra in där du vill ha dem.
						<h4>För att lägga till en vecka.</h4>
						Man kan välja vilka saker som ska visas genom att fylla i eller bara utelämna dem enligt exemplet nedan:<br />
						<strong>[skolmaten_vecka vecka=44 visavecka=true alla_veckor=0 days_to_show=0 weektext="Veckans skolmat!" title="title"]</strong><br />
						vecka: lämnas tom för innevarande vecka.<br />
						visavecka: visa veckonummer som titel eller inte<br />
						days_to_show: lämnas tom för att visa hela veckan<br />
						weektext och title: texter som visas tillsammas med menyn.<br />
						<h4>Dagens mat</h4>
						Helt enkelt bara klistra in den där, så kommer den visa dagens mat.
						<strong>[skolmaten_day]</strong>
					</div>
				</div>
			</div>
		</div>

	</div>
<?php } ?>