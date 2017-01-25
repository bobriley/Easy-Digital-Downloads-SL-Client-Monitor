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

require_once(dirname(__FILE__) . '/../class-sc-edd-sl-cm-constants.php');

if (!class_exists('SC_EDD_SL_CM_U'))
{
    /**
     * @author Snap Creek Software <support@snapcreek.com>
     * @copyright 2017 Snap Creek LLC
     */
    class SC_EDD_SL_CM_U
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

            self::$PLUGIN_URL = plugins_url() . "/" . SC_EDD_SL_CM_Constants::PLUGIN_SLUG;

            self::$PLUGIN_DIRECTORY = (WP_CONTENT_DIR . "/plugins/" . SC_EDD_SL_CM_Constants::PLUGIN_SLUG);

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

            _e($text, SC_EDD_SL_CM_Constants::PLUGIN_SLUG);
        }

        public static function __($text)
        {

            return __($text, SC_EDD_SL_CM_Constants::PLUGIN_SLUG);
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
                    error_log(SC_EDD_SL_CM_Constants::PLUGIN_SLUG . ":" . print_r($message, true));
                }
                else
                {
                    error_log(SC_EDD_SL_CM_Constants::PLUGIN_SLUG . ":" . $message);
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

            SC_EDD_SL_CM_U::log($message . ":" . var_export($object, true));
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

            $coming_soon_pro_url = menu_page_url(SC_EDD_SL_CM_Constants::$COMING_SOON_PRO_SUBMENU_SLUG, false);

            $after_launch_text = __('After Launch');
            $after_launch_url = menu_page_url(SC_EDD_SL_CM_Constants::$SUBSCRIBERS_SUBMENU_SLUG, false);
            $after_launch_url = self::append_query_value($after_launch_url, 'tab', 'leadwatch');

            echo "<a href='https://snapcreek.com/ezp-coming-soon/docs/faqs/' target='_blank'>$faq_text</a> | ";
            echo "<a href='https://snapcreek.com/support/' target='_blank'>$contact_text</a> | ";
        }

    }

    SC_EDD_SL_CM_U::init();
}
?>