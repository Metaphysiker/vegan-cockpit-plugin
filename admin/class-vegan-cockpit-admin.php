<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.philosophische-insel.ch/
 * @since      1.0.0
 *
 * @package    Vegan_Cockpit
 * @subpackage Vegan_Cockpit/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Vegan_Cockpit
 * @subpackage Vegan_Cockpit/admin
 * @author     Sandro Räss <s.raess@me.com>
 */
class Vegan_Cockpit_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Vegan_Cockpit_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Vegan_Cockpit_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/vegan-cockpit-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Vegan_Cockpit_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Vegan_Cockpit_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/vegan-cockpit-admin.js', array( 'jquery' ), $this->version, false );
		//wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/categories_analytics.js', array( 'jquery' ), $this->version, false );


	}

	public function settings_init(){
		// register a new setting for "vegan_cockpit" page
    register_setting('vegan_cockpit', 'vegan_cockpit_setting');

		// register a new section in the "vegan_cockpit" page
    add_settings_section(
        'vegan_cockpit_settings_section',
        'Vegan Cockpit Settings Section',
				array( $this, 'vegan_cockpit_settings_section_callback' ),
        'vegan_cockpit'
    );

		// register a new field in the "vegan_cockpit_settings_section" section, inside the "vegan_cockpit" page
    add_settings_field(
        'api_key',
        'Api Key',
				array( $this, 'api_key_html' ),
        'vegan_cockpit',
        'vegan_cockpit_settings_section'
    );

		add_settings_field(
				'google_view_id',
				'Google View ID',
				array( $this, 'google_view_id_html' ),
				'vegan_cockpit',
				'vegan_cockpit_settings_section'
		);

		add_settings_field(
				'payrexx_api_key',
				'Payrexx Api Key',
				array( $this, 'payrexx_api_key_html' ),
				'vegan_cockpit',
				'vegan_cockpit_settings_section'
		);

		add_settings_field(
				'wordpress_json_endpoint',
				'Wordpress Json Endpoint',
				array( $this, 'wordpress_json_endpoint_html' ),
				'vegan_cockpit',
				'vegan_cockpit_settings_section'
		);

	}

	public function vegan_cockpit_settings_section_callback() {
    echo "Allgmeine Einstellungen";
	}

	public function api_key_html() {
		$options = get_option( 'vegan_cockpit_setting' );
    echo "<input id='api_key' name='vegan_cockpit_setting[api_key]' type='text' value='" . esc_attr( $options['api_key'] ) . "' />";
	}

	public function google_view_id_html() {
		$options = get_option( 'vegan_cockpit_setting' );
		echo "<input id='google_view_id' name='vegan_cockpit_setting[google_view_id]' type='text' value='" . esc_attr( $options['google_view_id'] ) . "' />";
	}

	public function payrexx_api_key_html() {
		$options = get_option( 'vegan_cockpit_setting' );
		echo "<input id='payrexx_api_key' name='vegan_cockpit_setting[payrexx_api_key]' type='text' value='" . esc_attr( $options['payrexx_api_key'] ) . "' />";
	}


	public function wordpress_json_endpoint_html() {
		$options = get_option( 'vegan_cockpit_setting' );
		echo "<input id='wordpress_json_endpoint' name='vegan_cockpit_setting[wordpress_json_endpoint]' type='text' value='" . esc_attr( $options['wordpress_json_endpoint'] ) . "' />";
	}

	#payrexx
	public function register_get_payrexx_transactions_route() {
		register_rest_route( 'vegan_cockpit/v1', '/get_payrexx_transactions/(?P<offset>\d+)/(?P<limit>\d+)/(?P<startdate>[^/]+)/(?P<enddate>[^/]+)', array(
		'methods' => 'GET',
		'callback' => array( $this, 'get_payrexx_transactions' ),
		'permission_callback' => function(){
			return current_user_can( 'manage_options');
			}
	) );
	}

	public function get_payrexx_transactions( $data ) {


		//$nonce = $_REQUEST['_wpnonce'];
		//if (  wp_verify_nonce( $nonce, 'angular-nonce' ) ) {
		//echo $_REQUEST['_wpnonce'];
		//if (!wp_verify_nonce( $_REQUEST['_wpnonce'], 'angular-nonce' )) {

		//}


		//if ( ! current_user_can( 'manage_options' ) ) {
			//	return "user cannot";
		//}

		// $instanceName is a part of the url where you access your payrexx installation.
		// https://{$instanceName}.payrexx.com
		$instanceName = 'veganegesellschaftschweiz';

		// $secret is the payrexx secret for the communication between the applications
		// if you think someone got your secret, just regenerate it in the payrexx administration
		$secret = get_option( 'vegan_cockpit_setting' )["payrexx_api_key"];

		$result = "whut?";

		$final_array = [];

		$payrexx = new \Payrexx\Payrexx($instanceName, $secret);

		//$start_date = "2022-01-01 00:00:00";
		$startdate = $data["startdate"] . " 00:00:00";
		$enddate = $data["enddate"] . " 23:59:59";

		$transaction = new \Payrexx\Models\Request\Transaction();
		$transaction->setFilterDatetimeUtcGreaterThan(new \DateTime($startdate));
		$transaction->setFilterDatetimeUtcLessThan(new \DateTime($enddate));
		$transaction->setOffset($data['offset']);
		$transaction->setLimit($data['limit']);

		try {
		    $transactions = $payrexx->getAll($transaction);
		   //var_dump($transactions);

			 foreach ($transactions as &$transaction) {
				 //var_dump($transaction);

			 	$single_transaction = [];

				$single_transaction["amount"] = $transaction-> getAmount();
				$single_transaction["user_id"] = $transaction-> contact["id"];
				$single_transaction["firstname"] = $transaction-> contact["firstname"];
				$single_transaction["lastname"] = $transaction-> contact["lastname"];
				$single_transaction["time"] = $transaction-> getTime();
				$single_transaction["status"] = $transaction-> getStatus();

				$products_string = "";
				foreach ($transaction -> invoice["products"] as &$product) {
					$products_string = $products_string . " " . $product["name"];
				}
				unset($product);
				$single_transaction["products"] = $products_string;

				$final_array[] = $single_transaction;
				//$final_array[$transaction-> getId()] = $single_transaction;
			}
			unset($transaction);
		} catch (\Payrexx\PayrexxException $e) {
		   //print $e->getMessage();
		}

		//$age = array("Peter"=>35, "Ben"=>37, "Joe"=>43);
		//return json_encode($age);
		//return json_encode($result);

		//foreach($result as $transaction) {
		 //   echo $transaction->id;
		//}

		//$array_final_array = array($final_array);
		return rest_ensure_response($final_array);
		//return $enddate;

	}

	public function register_payrexx_route() {
		register_rest_route( 'vegan_cockpit/v1', '/author/(?P<id>\d+)', array(
		'methods' => 'GET',
		'callback' => array( $this, 'my_awesome_func' ),
	) );
	}

	public function my_awesome_func( $data ) {

		#if ( ! current_user_can( 'manage_options' ) ) {
		#		return "not allowed";
		#}


	  $posts = get_posts( array(
	    'author' => $data['id'],
	  ) );

	  if ( empty( $posts ) ) {
	    return null;
	  }

	  return $posts[0]->post_title;
	}

	public function options_page(){

			add_menu_page(
				'Vegan Cockpit',
				'Vegan Cockpit',
				'manage_options',
				'vegan-cockpit-options',
				array( $this, 'options_page_html' ),
				'dashicons-book-alt'
		);

	}

	public function options_page_html(){
		// check user capabilities
		if ( ! current_user_can( 'manage_options' ) ) {
				return;
		}

		?>

    <form action="options.php" method="post">
        <?php
        settings_fields( 'vegan_cockpit' );
        do_settings_sections( 'vegan_cockpit' ); ?>
        <input name="submit" class="button button-primary" type="submit" value="<?php esc_attr_e( 'Save' ); ?>" />
    </form>
    <?php
	}

	public function add_type_attribute($tag, $handle, $src) {
    // if not your script, do nothing and return original $tag
    if ( 'd3_charts' !== $handle ) {
        return $tag;
    }
    // change the script tag by adding type="module" and return it.
    $tag = '<script type="module" src="' . esc_url( $src ) . '"></script>';


	//	<script type="module">
	//	  import { greet } from "./app.js";
	//	  window.greetFromModule = greet;
	//	</script>


    return $tag;
}

public function angular(){

		add_submenu_page(
			'vegan-cockpit-options',
			'Angular',
			'Angular',
			'manage_options',
			'vegan-cockpit-angular',
			array( $this, 'angular_html' ),
			'1',
			'dashicons-book-alt'
	);

}

public function angular_html(){

	if ( ! current_user_can( 'manage_options' ) ) {
			return;
	}

	wp_enqueue_style( "angular-styles", plugin_dir_url( __FILE__ ) . 'css/angular/styles.css', array(), $this->version, 'all' );
	//wp_enqueue_style('angular/styles.css');
	wp_register_style( 'bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css' );
	wp_enqueue_style('bootstrap-css');

	wp_enqueue_script( 'angular-main', plugin_dir_url( __FILE__ ) . 'js/angular/main.js');
	wp_enqueue_script( 'angular-polyfill', plugin_dir_url( __FILE__ ) . 'js/angular/polyfills.js');
	wp_enqueue_script( 'angular-runtime', plugin_dir_url( __FILE__ ) . 'js/angular/runtime.js');
	wp_enqueue_script( 'angular-scripts', plugin_dir_url( __FILE__ ) . 'js/angular/scripts.js');


	foreach( glob( plugin_dir_url( __FILE__ ) . '/js/angular/*.js' ) as $file ) {
		// $file contains the name and extension of the file
		echo "something";
		wp_enqueue_script( $file, get_template_directory_uri().'/js/angular/'.$file);
	}

	?>
		<?php
			include( plugin_dir_path( __FILE__ ) . 'partials/angular.php');
		?>
	<?php
}


	public function categories(){

			add_submenu_page(
				'vegan-cockpit-options',
				'Categories',
				'Categories',
				'manage_options',
				'vegan-cockpit-categories',
				array( $this, 'categories_html' ),
				'1',
				'dashicons-book-alt'
		);

	}


	public function categories_html(){

		// check user capabilities
		if ( ! current_user_can( 'manage_options' ) ) {
				return;
		}

		wp_enqueue_script( 'google-analytics-api', plugin_dir_url( __FILE__ ) . 'js/google-analytics-api.js', array( 'jquery' ) );
		wp_enqueue_script( 'general-tools', plugin_dir_url( __FILE__ ) . 'js/general-tools.js', array( 'jquery' ) );
		wp_enqueue_script( 'd3_charts', plugin_dir_url( __FILE__ ) . 'js/d3-charts.js', array( 'jquery' ) );

		wp_register_style( 'bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css' );
		wp_enqueue_style('bootstrap-css');

		wp_register_style( 'jquery-datepicker-css', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.5/themes/base/jquery-ui.css' );
		wp_enqueue_style('jquery-datepicker-css');

		wp_register_script( 'd3', 'https://d3js.org/d3.v7.min.js', null, null, true );
		wp_enqueue_script('d3');

		wp_enqueue_script( 'categories', plugin_dir_url( __FILE__ ) . 'js/categories.js', array( 'jquery', 'jquery-ui-datepicker', 'google-analytics-api', 'd3', 'd3_charts', 'general-tools' ) );





		?>
			<?php
				include( plugin_dir_path( __FILE__ ) . 'partials/categories.php');
			?>
		<?php
	}

	public function categories_analytics(){

			add_submenu_page(
				'vegan-cockpit-options',
				'Categories Analytics',
				'Categories Analytics',
				'manage_options',
				'vegan-cockpit-categories_analytics',
				array( $this, 'categories_analytics_html' ),
				'1',
				'dashicons-book-alt'
		);

	}

	public function categories_analytics_enqueue_script(){
		wp_enqueue_script( 'categories_analytics', plugin_dir_url( __FILE__ ) . 'js/categories_analytics.js', array( 'jquery' ) );
	}

	public function categories_analytics_html(){
		//add_action( 'wp_enqueue_scripts', 'categories_analytics_enqueue_script' );
		wp_enqueue_script( 'google-analytics-api', plugin_dir_url( __FILE__ ) . 'js/google-analytics-api.js', array( 'jquery' ) );
		wp_enqueue_script( 'd3_charts', plugin_dir_url( __FILE__ ) . 'js/d3-charts.js', array( 'jquery' ) );

		wp_register_style( 'bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css' );
		wp_enqueue_style('bootstrap-css');

		wp_register_style( 'jquery-datepicker-css', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.5/themes/base/jquery-ui.css' );
		wp_enqueue_style('jquery-datepicker-css');

		wp_register_script( 'd3', 'https://d3js.org/d3.v7.min.js', null, null, true );
		wp_enqueue_script('d3');

		wp_enqueue_script( 'categories_analytics', plugin_dir_url( __FILE__ ) . 'js/categories-analytics.js', array( 'jquery', 'jquery-ui-datepicker', 'google-analytics-api', 'd3', 'd3_charts' ) );
		//add_filter('script_loader_tag', array( $this, 'add_type_attribute' ), 10, 3);

		//wp_enqueue_script( 'custom-gallery', plugins_url( '/js/gallery.js' , __FILE__ ), array( 'jquery' ) );
    //wp_enqueue_script( 'custom-gallery-lightbox', plugins_url( '/js/gallery-lightbox.js' , __FILE__ ), array( 'custom-gallery' ) );

		// check user capabilities
		if ( ! current_user_can( 'manage_options' ) ) {
				return;
		}

		$categories = get_categories( array(
		    'orderby' => 'name',
		    'order'   => 'ASC'
		) );

		?>
		<h1>Vegan Cockpit</h1>
		<h2>Datumsbereich auswählen</h2>
		<div class="card card-body my-4">
			<div class="row">
				<div class="col-6">
					<div class="input-group mb-3">
						<span class="input-group-text">Von</span>
						<input type="text" id="start_date" class="form-control" placeholder="Von">
					</div>
				</div>
				<div class="col-6">
					<div class="input-group mb-3">
						<span class="input-group-text">bis</span>
						<input type="text" id="end_date" class="form-control" placeholder="Bis">
					</div>
				</div>
			</div>
			<button type="button" class="btn btn-primary select_date_range_button">Datumsbereich anwenden</button>
		</div>

		<?php
			include( plugin_dir_path( __FILE__ ) . 'partials/categories-analytics.php');
		?>

		<h2>Wordpress Categories</h2>
		<p>
			This table will be filled with information, after you selected a date range.
		</p>
		<p>
			After the table is filled, a Donut-Chart appears.
		</p>
		<table id="table_with_categories" class="table table-bordered table-striped">
      <thead>
        <tr>
					<th>name</th>
					<th>slug</th>
					<th>term_id</th>
					<th>category-post-count</th>
					<th>urls</th>
					<th>
						total unique users
					</th>
					<th>
						total sessions
					</th>
					<th>
						total unique pageviews
					</th>
				</tr>
      </thead>
      <tbody>

			<?php
			  foreach ($categories as $row) {
			    echo "<tr id=\"tr-id-{$row->slug}\">";
					echo "<td data-filter=\"name\">{$row->name}</td>";
					echo "<td data-filter=\"slug\">{$row->slug}</td>";
					echo "<td data-filter=\"term_id\">{$row->term_id}</td>";
					echo "<td data-filter=\"category_count\">{$row->category_count}</td>";
					echo "<td data-filter=\"urls\">";
					$posts_by_category = get_posts( array ('category' => $row->term_id, 'numberposts' => -1) );
					$numItems = count($posts_by_category);
					$i = 0;
					foreach ($posts_by_category as $post){
						echo str_replace( home_url(), "", get_permalink($post) );
						if(++$i !== $numItems) {
								echo ",<br />";
							}
					}
					echo "</td>";
					echo "<td id=\"td-id-total-unique-users-{$row->slug}\" data-filter=\"total_unique_users\">0</td>";
					echo "<td id=\"td-id-total-sessions-{$row->slug}\" data-filter=\"total_sessions\">0</td>";
					echo "<td id=\"td-id-total-unique-pageviews-{$row->slug}\" data-filter=\"total_pageviews\">0</td>";
			    echo '</tr>';
			  }
			?>
      </tbody>
    </table>

		<hr>

		<h2>
			Log
		</h2>
		<p>
			Here you can see what happens in realtime.
		</p>
		<table class="table table-bordered table-striped">
		  <thead>
		    <tr>
		      <th>
		        category_slug
		      </th>
		      <th>url</th>
		      <th>users</th>
		      <th>sessions</th>
		      <th>pageviews</th>
		    </tr>
		  </thead>
		  <tbody id="tbody-of-table2">

		  </tbody>
		</table>

		<hr>
		<h2>
			Single Categories
		</h2>
		<p>
			Here are the charts for the single categories.
		</p>

		<div class="single_categories">


		</div>




		<table class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>
						category_slug
					</th>
					<th>url</th>
					<th>users</th>
					<th>sessions</th>
					<th>pageviews</th>
				</tr>
			</thead>
			<tbody id="">

			</tbody>
		</table>



		<?php
	}

}
