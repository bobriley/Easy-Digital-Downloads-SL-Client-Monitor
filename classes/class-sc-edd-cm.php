<?php
/*
  Easy Digital Downloads Software Licensing Client Monitor Plugin
  Copyright (C) 2017, Snap Creek LLC
  website: snapcreek.com contact: support@snapcreek.com

  Easy Digital Downloads Software Licensing Client Monitor Plugin is distributed under the GNU General Public License, Version 3,
  June 2007. Copyright (C) 2007 Free Software Foundation, Inc., 51 Franklin
  St, Fifth Floor, Boston, MA 02110, USA

  THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
  ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
  WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
  DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR
  ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
  (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
  LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
  ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
  (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
  SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */
require_once(dirname(__FILE__) . '/Utilities/class-sc-edd-cm-u.php');
require_once(dirname(__FILE__) . '/Utilities/class-sc-edd-cm-query-u.php');

//require_once(SC_EDD_CM_U::$PLUGIN_DIRECTORY . '/../../../wp-admin/includes/upgrade.php');
//require_once("class-easy-pie-options.php");
require_once("Entities/class-sc-edd-cm-global-entity.php");
require_once("Entities/class-sc-edd-cm-client-entity.php");

require_once('class-sc-edd-cm-plugin-base.php');
require_once('class-sc-edd-cm-constants.php');

if (!class_exists('SC_EDD_CM'))
{

	/**
	 * @author Snap Creek Software <support@snapcreek.com>
	 * @copyright 2017 Snap Creek LLC
	 */
	class SC_EDD_CM extends SC_EDD_CM_Plugin_Base
	{
		/**
		 * Constructor
		 */
		function __construct($plugin_file_path)
		{

			parent::__construct(SC_EDD_CM_Constants::PLUGIN_SLUG);

			$this->add_class_action('plugins_loaded', 'plugins_loaded_handler');
			$this->add_class_action('admin_enqueue_scripts', 'admin_enqueue_scripts_handler');

			$entity_table_present = SC_EDD_Query_U::is_table_present(SC_EDD_CM_JSON_Entity_Base::DEFAULT_TABLE_NAME);

			if (is_admin())
			{

				//- Hook Handlers
				register_activation_hook($plugin_file_path, array('SC_EDD_CM', 'activate'));
				register_deactivation_hook($plugin_file_path, array('SC_EDD_CM', 'deactivate'));
				register_uninstall_hook($plugin_file_path, array('SC_EDD_CM', 'uninstall'));

				//- Actions
				$this->add_class_action('admin_init', 'admin_init_handler');
				$this->add_class_action('admin_menu', 'add_to_admin_menu');
			}
			
			/* @var $global SC_EDD_CM_Global_Entity */
			$global = SC_EDD_CM_Global_Entity::get_instance();
			
			if(($global != null) && ($global->collection_enabled))
			{
				$this->add_class_action('edd_check_license', 'edd_check_license_handler', 8);
			}
		}

		function add_class_action($tag, $method_name, $priority = 10)
		{
			return add_action($tag, array($this, $method_name), $priority);
		}

		function edd_check_license_handler($data)
		{
			SC_EDD_CM_U::log_object("check license handler", $data);
			//$item_id     = ! empty( $data['item_id'] )   ? absint( $data['item_id'] ) : -1;
			$item_name   = ! empty( $data['item_name'] ) ? rawurldecode( $data['item_name'] ) : '';
			$license_key     = urldecode( $data['license'] );
			$url         = isset( $data['url'] ) ? urldecode( $data['url'] ) : '';
		//	$license_id  = $this->get_license_by_key( $license_key );
//			$expires     = $this->get_license_expiration( $license_id );
//			$payment_id  = get_post_meta( $license_id, '_edd_sl_payment_id', true );
//			$download_id = get_post_meta( $license_id, '_edd_sl_download_id', true );
//			$customer_id = edd_get_payment_customer_id( $payment_id );

//			$customer = new EDD_Customer( $customer_id );

			//$client = SC_EDD_CM_Client_Entity::get_by_license_key($license_key);
			$ip = $_SERVER['REMOTE_ADDR'];
			$client = SC_EDD_CM_Client_Entity::get_by_ip($ip);
			
			if($client == null)
			{
				$client = new SC_EDD_CM_Client_Entity();
			}
			
		//	$client->item_id = $item_id;
			$client->item_name = $item_name;
			$client->ip = $ip;
			$client->url = $url;
			$client->last_hit_timestamp = time();
			$client->num_hits++;
			$client->license_key = $license_key;
			
			if($client->first_hit_timestamp == -1)
			{
				$client->first_hit_timestamp = time();
			}
			
			SC_EDD_CM_U::log_object("saving client", $client);
			
			$client->save();
		}
		
		function add_class_filter($tag, $method_name)
		{

			return add_filter($tag, array($this, $method_name));
		}

		// <editor-fold defaultstate="collapsed" desc="Hook Handlers">
		public static function activate()
		{

			SC_EDD_CM_U::log("activate");

			// All version stuff is not in upgrade processing
//            $installed_ver = get_option(SC_EDD_CM_Constants::PLUGIN_VERSION_OPTION_KEY);
			//rsr todo       if($installed_ver != SC_EDD_CM_Constants::PLUGIN_VERSION)
			{
				SC_EDD_CM_JSON_Entity_Base::init_table();

				SC_EDD_CM_Client_Entity::init_table();

				SC_EDD_CM_Global_Entity::initialize_plugin_data();

//                update_option(SC_EDD_CM_Constants::PLUGIN_VERSION_OPTION_KEY, SC_EDD_CM_Constants::PLUGIN_VERSION);
			}
		}

		public static function deactivate()
		{

			SC_EDD_CM_U::log("deactivate");
		}

		public static function uninstall()
		{

			SC_EDD_CM_U::log("uninstall");
		}

		// </editor-fold>

		public function enqueue_scripts()
		{

			$jsRoot = plugins_url() . "/" . SC_EDD_CM_Constants::PLUGIN_SLUG . "/js";

			wp_enqueue_script('jquery');
			wp_enqueue_script('jquery-ui-core');
			wp_enqueue_script('jquery-effects-core');
		}

		/**
		 *  enqueue_styles
		 *  Loads the required css links only for this plugin  */
		public function enqueue_styles()
		{
			$styleRoot = plugins_url() . "/" . SC_EDD_CM_Constants::PLUGIN_SLUG . "/styles";

			wp_register_style('jquery-ui-min-css', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/themes/smoothness/jquery-ui.min.css', array(), SC_EDD_CM_Constants::PLUGIN_VERSION);
			wp_enqueue_style('jquery-ui-min-css');


			wp_register_style('sc-edd-cm-styles.css', $styleRoot . '/sc-edd-cm-styles.css', array(), SC_EDD_CM_Constants::PLUGIN_VERSION);
			wp_enqueue_style('sc-edd-cm-styles.css');

			//FontAwesome
			$font_awesome_directory = plugins_url() . "/" . SC_EDD_CM_Constants::PLUGIN_SLUG . "/lib/font-awesome-4.7.0/css/";
			wp_register_style('sc-edd-cm-fontawesome', $font_awesome_directory . 'font-awesome.min.css', array(), '4.7.0');
			wp_enqueue_style('sc-edd-cm-fontawesome');
		}

		// <editor-fold defaultstate="collapsed" desc=" Action Handlers ">
		public function plugins_loaded_handler()
		{
			$this->init_localization();
			$this->upgrade_processing();
		}

		public function admin_enqueue_scripts_handler()
		{

			$styleRoot = plugins_url() . "/" . SC_EDD_CM_Constants::PLUGIN_SLUG . "/styles";

			wp_register_style('easy-pie-cspe-common-admin-styles.css', $styleRoot . '/easy-pie-cspe-common-admin-styles.css', array(), SC_EDD_CM_Constants::PLUGIN_VERSION);
			wp_enqueue_style('easy-pie-cspe-common-admin-styles.css');
		}

		public function init_localization()
		{
	//		load_plugin_textdomain(SC_EDD_CM_Constants::PLUGIN_SLUG, false, SC_EDD_CM_Constants::PLUGIN_SLUG . '/languages/');
		}

		public function admin_init_handler()
		{

			$this->add_filters_and_actions();
		}

		private function add_filters_and_actions()
		{

			add_filter('plugin_action_links', array($this, 'get_action_links'), 10, 2);
		}

		function get_action_links($links, $file)
		{

			if ($file == "sc-edd-cm/sc-edd-cm.php")
			{

				$settings_link = '<a href="' . get_bloginfo('wpurl') . '/wp-admin/admin.php?page=' . SC_EDD_CM_Constants::PLUGIN_SLUG . '">Settings</a>';

				array_unshift($links, $settings_link);
			}

			return $links;
		}

		function upgrade_processing()
		{
			$installed_ver = get_option(SC_EDD_CM_Constants::PLUGIN_VERSION_OPTION_KEY);

			/* Standard Version Update */
			if (version_compare($installed_ver, SC_EDD_CM_Constants::PLUGIN_VERSION, 'ne'))
			{
				update_option(SC_EDD_CM_Constants::PLUGIN_VERSION_OPTION_KEY, SC_EDD_CM_Constants::PLUGIN_VERSION);

				SC_EDD_CM_U::log("Updated version from $installed_ver to " . SC_EDD_CM_Constants::PLUGIN_VERSION);
			}
		}

		// </editor-fold>

		public function add_to_admin_menu()
		{

			$perms = 'manage_options';

			add_menu_page('Easy Digital Downloads SL Client Monitor', 'SL Clients', $perms, SC_EDD_CM_Constants::PLUGIN_SLUG, array($this, 'display_tools_page'), SC_EDD_CM_U::$PLUGIN_URL . '/images/74-location-lighter.png');
			$tools_page_hook_suffix = add_submenu_page(SC_EDD_CM_Constants::PLUGIN_SLUG, $this->__('Easy Digital Downloads SL Client Monitor Tools'), $this->__('Tools'), $perms, SC_EDD_CM_Constants::$TOOLS_SUBMENU_SLUG, array($this, 'display_tools_page'));
			$settings_page_hook_suffix = add_submenu_page(SC_EDD_CM_Constants::PLUGIN_SLUG, $this->__('Easy Digital Downloads SL Client Monitor Settings'), $this->__('Settings'), $perms, SC_EDD_CM_Constants::$SETTINGS_SUBMENU_SLUG, array($this, 'display_settings_page'));

			add_action('admin_print_scripts-' . $tools_page_hook_suffix, array($this, 'enqueue_scripts'));
			add_action('admin_print_scripts-' . $settings_page_hook_suffix, array($this, 'enqueue_scripts'));

			//Apply Styles
			add_action('admin_print_styles-' . $tools_page_hook_suffix, array($this, 'enqueue_styles'));
			add_action('admin_print_styles-' . $settings_page_hook_suffix, array($this, 'enqueue_styles'));
			
			$this->add_class_action("load-$tools_page_hook_suffix", 'add_clients_screen_options');
		}
		
		public function add_clients_screen_options()
        {
            $option = 'per_page';

            $args = array(
                'label' => SC_EDD_CM_U::__('SL Clients'),
                'default' => 10,
                'option' => 'sc_edd_clients_per_page'
            );

            add_screen_option($option, $args);
        }

		// </editor-fold>

		function display_options_page($page)
		{
			$relative_page_path = '/../pages/' . $page;

			$__dir__ = dirname(__FILE__);

			include($__dir__ . $relative_page_path);
		}

		function display_settings_page()
		{
			$this->display_options_page('page-settings.php');
		}

		function display_tools_page()
		{
			$this->display_options_page('page-tools.php');
		}

	}

}