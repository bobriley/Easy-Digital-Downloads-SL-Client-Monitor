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

require_once(dirname(__FILE__) .  '/class-sc-edd-sl-cm-standard-entity-base.php');

//rsr todo: rename this entity
if (!class_exists('SC_EDD_Client_Entity')) {

    /**     
     * @author Bob Riley <support@snapcreek.com>
     * @copyright 2015 Snap Creek LLC
     */
    class SC_EDD_Client_Entity extends SC_EDD_Standard_Entity_Base {
 
        public $item_id = -1;   
		public $source_ip = '0.0.0.0';
        public $source_site = '';
		public $first_hit_timestamp = -1;
		public $last_hit_timestamp = -1;
		public $num_hits = 0;
		public $license_key = '';		
        
        const NUMBER_PER_PAGE = 10;
        
        public static $TABLE_NAME = "sc_edd_clients";
        
        function __construct() {
            
            parent::__construct(self::$TABLE_NAME);            
        }   
        
        public static function init_table() {
            
            $field_info = array();
            
            $field_info['item_id'] = 'int';
			$field_info['source_ip'] = 'varchar(15)';
			$field_info['source_site'] = 'varchar(2084)';
            $field_info['first_hit_timestamp'] = 'int';
			$field_info['last_hit_timestamp'] = 'int';
			$field_info['num_hits'] = 'int';
            $field_info['license_key'] = 'varchar(50)';
            
            $index_array = array();
            			
            $index_array['source_ip_idx'] = 'source_ip';
			
            self::generic_init_table($field_info, self::$TABLE_NAME, $index_array, 'utf8', 'utf8_unicode_ci');
        } 
                
        public static function delete_by_id($id) {
        
            self::delete_by_id_and_table($id, self::$TABLE_NAME);
        }
        
        public static function get_all($page = 0)
        {
            return parent::get_all_objects(get_class(), self::$TABLE_NAME, $page);
        }
        
         public static function get_num_pages()
        {
            $contacts = self::get_all();
            
            $count = count($contacts);
                        
            $num_pages = $count / self::NUMBER_PER_PAGE;

            if(($count % self::NUMBER_PER_PAGE) != 0) {

                return floor($num_pages ) + 1;    
            } else {

                return $num_pages;
            }                                          
        }

        public static function get_by_id($id)
        {
            return self::get_by_id_and_type($id, get_class(), self::$TABLE_NAME);
        }
		
		public static function get_by_license_key($license_key)
		{
			$license_key = trim($license_key);
			
			return self::get_by_unique_field_and_type('license_key', $license_key, get_class($this), self::$TABLE_NAME);
		}
		
		public static function get_by_source_site($source_site)
		{
			$source_site = trim($source_site);
			
			return self::get_by_unique_field_and_type('source_site', $source_site, get_class($this), self::$TABLE_NAME);
		}
    }
}
?>