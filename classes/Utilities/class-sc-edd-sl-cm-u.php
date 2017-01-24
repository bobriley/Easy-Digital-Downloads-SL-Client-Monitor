<?php
/*
  Easy Digital Downloads Software Licensing Client Monitor Plugin
  Copyright (C) 2016, Snap Creek LLC
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

require_once(dirname(__FILE__) . '/../class-sc-edd-sl-cm-constants.php');

if (!class_exists('SC_EDD_U'))
{

    /**
     * @author Bob Riley <support@snapcreek.com>
     * @copyright 2015 Snap Creek LLC
     */
    class SC_EDD_U
    {
        // Pseudo-constants
        public static $MINI_THEMES_TEMPLATE_DIRECTORY;
        public static $PLUGIN_URL;
        public static $PLUGIN_DIRECTORY;
        private static $type_format_array;

        public static function init()
        {

            $__dir__ = dirname(__FILE__);

            self::$MINI_THEMES_TEMPLATE_DIRECTORY = $__dir__ . "/../templates/";

            self::$PLUGIN_URL = plugins_url() . "/" . SC_EDD_Constants::PLUGIN_SLUG;

            self::$PLUGIN_DIRECTORY = (WP_CONTENT_DIR . "/plugins/" . SC_EDD_Constants::PLUGIN_SLUG);

            self::$type_format_array = array('boolean' => '%s', 'integer' => '%d', 'double' => '%g', 'string' => '%s');
        }
        
		static public function PHP53()
		{
			return version_compare(PHP_VERSION, '5.3.0', '>=');
		}
		
        public static function bool_to_string($b)
        {
            return ($b ? self::__('True') : self::__('False'));
        }

        public static function _e($text)
        {

            _e($text, SC_EDD_Constants::PLUGIN_SLUG);
        }

        public static function __($text)
        {

            return __($text, SC_EDD_Constants::PLUGIN_SLUG);
        }

        public static function _he($text)
        {

            echo htmlspecialchars($text);
        }

        public static function boolstring($val)
        {
            if ($val)
            {
                return 'true';
            }
            else
            {
                return 'false';
            }
        }

        public static function get_local_ticks_from_gmt_formatted_time($timestamp)
        {
            $ticks = strtotime($timestamp);

            $ticks += ((int) get_option('gmt_offset') * 3600);

            return $ticks;
        }

        public static function get_simplified_local_time_from_formatted_gmt($timestamp, $date_only = false)
        {
            $local_ticks = self::get_local_ticks_from_gmt_formatted_time($timestamp);
            //F j, hh:MM meridian
            //return EZP_IBC_U::get_wp_formatted_from_gmt_formatted_time($item['timestamp']);

            $date_portion = date('F j, Y', $local_ticks);
            
            if($date_only == false)
            {
                $time_portion = ' ' . date('g:i:s a', $local_ticks);
            }
            else
            {
                $time_portion = '';
            }

            return "$date_portion$time_portion";
        }

        public static function get_db_type_format($variable)
        {

            $type_string = gettype($variable);

            if ($type_string == "NULL")
            {

                self::log("get_db_type_format: Error. Variable is not initialized.");
                return "";
            }

            return self::$type_format_array[$type_string];
        }

        public static function get_public_properties($object)
        {

            $publics = get_object_vars($object);
            unset($publics['id']);
            unset($publics['type']);

            return $publics;
        }

        public static function get_public_class_properties($class_name)
        {

            $publics = get_class_vars($class_name);
            unset($publics['id']);

            return $publics;
        }

        public static function get_guid()
        {

            if (function_exists('com_create_guid') === true)
            {
                return trim(com_create_guid(), '{}');
            }

            return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
        }

//        public static function display_coming_soon_admin_notice($coming_soon_on)
//        {
//            if ($coming_soon_on)
//            {
//
//                echo "<div class='error'><a href='" . admin_url() . "admin.php?page=" . SC_EDD_Constants::$SETTINGS_SUBMENU_SLUG . "'>" . self::__("Coming Soon is On") . "</a></div>";
//            }
//            else
//            {
//
//                echo "<div style='text-decoration:underline' class='updated'><a href='" . admin_url() . "admin.php?page=" . SC_EDD_Constants::$SETTINGS_SUBMENU_SLUG . "'>" . self::__("Coming Soon is Off") . "</a></div>";
//            }
//        }
		
//		 public static function display_coming_soon_admin_bar_alert($coming_soon_on)
//        {
//            if ($coming_soon_on)
//            {
//
//                echo "<div class='error'><a href='" . admin_url() . "admin.php?page=" . SC_EDD_Constants::$SETTINGS_SUBMENU_SLUG . "'>" . self::__("Coming Soon is On") . "</a></div>";
//            }
//            else
//            {
//
//                echo "<div style='text-decoration:underline' class='updated'><a href='" . admin_url() . "admin.php?page=" . SC_EDD_Constants::$SETTINGS_SUBMENU_SLUG . "'>" . self::__("Coming Soon is Off") . "</a></div>";
//            }
//        }
        
        
        public static function echo_checked($val)
        {
            echo $val ? 'checked' : '';
        }

        public static function echo_disabled($val)
        {
            echo $val ? 'disabled' : '';
        }

        public static function echo_selected($val)
        {
            echo $val ? 'selected' : '';
        }
		
		public static function echo_display($val, $true_display, $false_display)
		{
			$display_val;
			
			if($val)
			{
				$display_val = $true_display;
			}
			else
			{
				$display_val = $false_display;
			}
			
			echo "display: $display_val";
		}

        /* -- Option Field Help Methods -- */
        public static function render_option($value, $text, $current_value)
        {
            $selected = "";

            if ($value == $current_value)
            {
                $selected = 'selected="selected"';
            }

            echo "<option value='$value' $selected>$text</option>";
        }

        public static function get_manifest_by_key($key)
        {

            $manifests = self::get_manifests();

            foreach ($manifests as $manifest)
            {

                if ($manifest->key == $key)
                {

                    return $manifest;
                }
            }

            return null;
        }

        public static function get_manifests()
        {

            $user_manifest_array = self::get_manifests_in_directory(self::$MINI_THEMES_USER_DIRECTORY, self::$MINI_THEMES_USER_URL);
            $standard_manifest_array = self::get_manifests_in_directory(self::$MINI_THEMES_STANDARD_DIRECTORY, self::$MINI_THEMES_STANDARD_URL);

            $combined_manifest_array = &$user_manifest_array;

            // stuff in user manifest array can override standard manifests
            foreach ($standard_manifest_array as $sman)
            {

                $contains = false;

                foreach ($combined_manifest_array as $man)
                {

                    if ($sman->key == $man->key)
                    {
                        $contains = true;
                        break;
                    }
                }

                if (!$contains)
                {
                    array_push($combined_manifest_array, $sman);
                }
            }
            return $combined_manifest_array;
        }

        public static function get_manifests_in_directory($directory, $mini_theme_base_url)
        {

            $manifest_array = array();
            $dirs = glob($directory . "*", GLOB_ONLYDIR);

            sort($dirs);

            foreach ($dirs as $dir)
            {

                $manifest = null;
                $manifest_path = $dir . "/manifest.json";

                if (file_exists($manifest_path))
                {

                    $manifest_text = file_get_contents($manifest_path);

                    if ($manifest_text != false)
                    {

                        $manifest = json_decode($manifest_text);
                    }
                    else
                    {

                        self::log("Problem reading manifest in $dir ($dirs)");
                    }
                }
                else
                {

                    // Manifest not present so assumption is they just want a generic mini-theme
                    $manifest = new stdClass();

                    self::add_property($manifest, 'title', basename($dir));
                    self::add_property($manifest, 'page', 'index.html');
                    self::add_property($manifest, 'description', 'User Mini Theme');
                    self::add_property($manifest, 'author_name', '');
                    self::add_property($manifest, 'website_url', '');
                    self::add_property($manifest, 'google_plus_author_url', '');
                    self::add_property($manifest, 'original_release_date', '2013/01/01');
                    self::add_property($manifest, 'latest_version_date', '2013/01/01');
                    self::add_property($manifest, 'version', '1.0.0');
                    self::add_property($manifest, 'release_notes', '');
                    self::add_property($manifest, 'screenshot', self::$MINI_THEMES_IMAGES_URL . "user-defined.png");
                    self::add_property($manifest, 'autodownload', false);
                    self::add_property($manifest, 'responsive', true);
                }

                if ($manifest != null)
                {

                    // RSR TODO: Have a way to give each item a unique key if it conflicts..?
                    self::add_property($manifest, 'key', basename($dir));
                    self::add_property($manifest, 'dir', $dir);
                    self::add_property($manifest, 'manifest_path', $manifest_path);
                    self::add_property($manifest, 'mini_theme_url', $mini_theme_base_url . $manifest->key);

                    array_push($manifest_array, $manifest);
                }
            }

            return $manifest_array;
        }

        public static function add_property(&$obj, $property, $value)
        {

            $obj = (array) $obj;
            $obj[$property] = $value;
            $obj = (object) $obj;
        }

        public static function log($message)
        {

            if (WP_DEBUG === true)
            {
                if (is_array($message) || is_object($message))
                {
                    error_log(SC_EDD_Constants::PLUGIN_SLUG . ":" . print_r($message, true));
                }
                else
                {
                    error_log(SC_EDD_Constants::PLUGIN_SLUG . ":" . $message);
                }
            }
        }

        public static function log_object($message, &$object)
        {

            self::log($message);
            self::log(var_export($object, true));
        }

        public static function debug_dump($message, $object)
        {

            SC_EDD_U::log($message . ":" . var_export($object, true));
        }

        public static function is_current_url_unfiltered($config)
        {
            $requested = strtolower($_SERVER['REQUEST_URI']);

            $config->unfiltered_urls = strtolower($config->unfiltered_urls);
            $urls = preg_split('/\r\n|[\r\n]/', $config->unfiltered_urls);

            $is_unfiltered = false;           
            
            foreach ($urls as $url)
            {                
                $trimmed_url = trim($url);
                if ((strpos($requested, $trimmed_url) === 0))
                {
                    $is_unfiltered = true;
                    break;
                }
            }

            return $is_unfiltered;
        }
		
		public static function is_current_ip_allowed($config)
        {
			/* @var $config SC_EDD_Config_Entity */
            $remote_addr = strtolower($_SERVER['REMOTE_ADDR']);

            $allowed_ips = preg_split('/\r\n|[\r\n]/', $config->allowed_ips);

            $is_allowed = false;           
            
            foreach ($allowed_ips as $allowed_ip)
            {
                if ($remote_addr == $allowed_ip)
                {
                    $is_allowed = true;
                    break;
                }
            }

            return $is_allowed;
        }

        public static function append_query_value($url, $key, $value)
        {
            $separator = (parse_url($url, PHP_URL_QUERY) == NULL) ? '?' : '&';

            $modified_url = $url . "$separator$key=$value";

            return $modified_url;
        }

        public static function echo_footer_links()
        {

            $faq_text = __('FAQ');
            $contact_text = __('Contact');

            $coming_soon_pro_url = menu_page_url(SC_EDD_Constants::$COMING_SOON_PRO_SUBMENU_SLUG, false);

            $after_launch_text = __('After Launch');
            $after_launch_url = menu_page_url(SC_EDD_Constants::$SUBSCRIBERS_SUBMENU_SLUG, false);
            $after_launch_url = self::append_query_value($after_launch_url, 'tab', 'leadwatch');

            echo "<a href='https://snapcreek.com/ezp-coming-soon/docs/faqs/' target='_blank'>$faq_text</a> | ";
            echo "<a href='https://snapcreek.com/support/' target='_blank'>$contact_text</a> | ";
        }

    }

    SC_EDD_U::init();
}
?>