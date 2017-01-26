<?php
/*
  Easy Pie Site Spy Plugin
  Copyright (C) 2014, Synthetic Thought LLC
  website: easypiewp.com contact: bob@easypiewp.com

  Easy Pie Site Spy Plugin is distributed under the GNU General Public License, Version 3,
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

if (!class_exists('WP_List_Table'))
{
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

require_once(SC_EDD_CM_U::$PLUGIN_DIRECTORY . '/classes/Utilities/class-sc-edd-cm-u.php');

if (!class_exists('SC_EDD_CM_Client_List_Control'))
{

	/**
	 * @author Snap Creek Software <bob@easypiewp.com>
	 * @copyright 2014 Synthetic Thought LLC
	 */
	class SC_EDD_CM_Client_List_Control extends WP_List_Table
	{
//        private $stage = -1;
//        private $event = -1;
//        private $event_parameter = -1;
//        private $event_range = -1;
		private $search = null;
	//	private $query_info = null;
		private $nonce_action = null;

		//     private $contact_cache = null;
		//public function __construct($stage, $event, $event_parameter, $event_range, $search, $query_info, $nonce_action)
		public function __construct($search, $nonce_action)
		{
			//$this->stage = $stage;
//            $this->event = $event;
//            $this->event_parameter = $event_parameter;
//            $this->event_range = $event_range;
			$this->search = $search;
			$this->nonce_action = $nonce_action;

			parent::__construct();
		}

		/**
		 * Define what data to show on each column of the table
		 *
		 * @param  Array $item        Data
		 * @param  String $column_name - Current column name
		 *
		 * @return Mixed
		 */
		public function column_default($item, $column_name)
		{
			switch ($column_name)
			{
				case 'item_id':
				case 'url':
				case 'ip':
				case 'first_hit_timestamp':
				case 'last_hit_timestamp':
				case 'num_hits':
				case 'license_key':
					return $item[$column_name];

				default:
					return print_r($item, true);
			}
		}

		public function prepare_items()
		{

			apply_filters("debug", "prepare items start");
			$columns = $this->get_columns();
			$hidden = $this->get_hidden_columns();
			$sortable = $this->get_sortable_columns();

			$this->process_bulk_action();

			$data = $this->get_table_data();
			usort($data, array(&$this, 'sort_data'));

			$perPage = $this->get_items_per_page('sc_edd_sl_clients_per_page', 10);
			$currentPage = $this->get_pagenum();
			$totalItems = count($data);

			$this->set_pagination_args(array(
				'total_items' => $totalItems,
				'per_page' => $perPage
			));

			$data = array_slice($data, (($currentPage - 1) * $perPage), $perPage);

			// The following are used inside parent class
			$this->_column_headers = array($columns, $hidden, $sortable);
			$this->items = $data;
			//  apply_filters("debug", "prepare items end");
		}

		// Overriding the tablenav to prevent the referer from getting out of control
		function display_tablenav($which)
		{
			?>
			<div class="tablenav <?php echo esc_attr($which); ?>">

				<div class="alignleft actions">
					<?php $this->bulk_actions(); ?>
				</div>
				<?php
				$this->extra_tablenav($which);
				$this->pagination($which);
				?>
				<br class="clear" />
			</div>
			<?php
		}

		public function extra_tablenav($which)
		{
			if ($which == 'top')
			{
				//      apply_filters("debug", "extra table nav start");
				$advanced_display = '';
				$filter_display = '';

//                if ($this->query_info != null)
//                {
//                    $filter_display = 'display:none';
//                }
//
//                if ($this->event == -1)
//                {
//                    $advanced_display = 'display:none';
//                }
				// RSR TODO: If want any filters at top add them here
				?>


				<?php
				//    apply_filters("debug", "extra table nav end");
			}
		}

		public function process_bulk_action()
		{
			apply_filters("debug", "process bulk start");
			// RSR TODO
			// security check!
//            if (isset($_POST['_wpnonce']) && !empty($_POST['_wpnonce'])) {
//
//                $nonce = filter_input(INPUT_POST, '_wpnonce', FILTER_SANITIZE_STRING);
//                $action = 'bulk-' . $this->_args['plural'];
//
//                if (!wp_verify_nonce($nonce, $action))
//                    wp_die('Nope! Security check failed!');
//            }

			$action = $this->current_action();

			if ($action == 'delete')
			{
				if (isset($_REQUEST['id']))
				{
					$entity_ids = $_REQUEST['id'];

					foreach ($entity_ids as $entity_id)
					{
						SC_EDD_CM_U::debug("deleting $entity_id");
						SC_EDD_CM_Client_Entity::delete_by_id($entity_id);
					}
				}
			}

			// apply_filters("debug", "process bulk end");

			return;
		}

		public function get_columns()
		{
			$columns = array(
				// RSR TODO: Where does this cb come fropm?
				'cb' => '<input type="checkbox" />',
				//   'id' => SC_EDD_CM_U::__('ID'),                
				//'item_id' => SC_EDD_CM_U::__('Item ID'),
				'item_id' => SC_EDD_CM_U::__('Item'),
				'url' => SC_EDD_CM_U::__('URL'),
				'ip' => SC_EDD_CM_U::__('IP'),
				'first_hit_timestamp' => SC_EDD_CM_U::__('Initial Hit'),
				'last_hit_timestamp' => SC_EDD_CM_U::__('Latest Hit'),
				'num_hits' => SC_EDD_CM_U::__('Hits'),
				'hits_per_day' => SC_EDD_CM_U::__('Hits/Day'),
				'activations' => SC_EDD_CM_U::__('Sites'),
				'license_key' => SC_EDD_CM_U::__('License'),
				'expiration_date' => SC_EDD_CM_U::__('Expiration'),
				'customer_id' => SC_EDD_CM_U::__('Customer'),
			);

			return $columns;
		}

		public function get_hidden_columns()
		{
			return array('id');
		}

		public function get_sortable_columns()
		{
			return array('num_hits' => array('num_hits', false),
				'last_hit_timestamp' => array('last_hit_timestamp', false),
				'first_hit_timestamp' => array('first_hit_timestamp', false));
		}

		public function get_bulk_actions()
		{
			$actions = array();

			$actions ['delete'] = SC_EDD_CM_U::__('Delete');

			return $actions;
		}

		private function get_table_data()
		{
			//apply_filters("debug", "get table data start");

			$data = array();

			$clients = SC_EDD_CM_Client_Entity::get_all();

			foreach ($clients as $client)
			{	
				$seconds_diff = $client->last_hit_timestamp - $client->first_hit_timestamp;
				$days_diff = $seconds_diff / 86400;
				
				$client->hits_per_day = $client->num_hits / $days_diff;

				$edd_swl = EDD_Software_Licensing::instance();

				$result = $edd_swl->check_license($args);

				$license_id = $edd_swl->get_license_by_key($client->license_key);

				$client->activations = $edd_swl->get_site_count($license_id);

				$item_name = get_the_title($client->item_id);

				$args = array(
					'item_id' => $client->item_id,
					'item_name' => $item_name,
					'key' => $client->license_key,
					'url' => $client->url,
				);
				
				$client->expiration_date = get_license_expiration( $license_id );
				
				$payment_id  = get_post_meta( $license_id, '_edd_sl_payment_id', true );
				$client->customer_id = edd_get_payment_customer_id( $payment_id );

				$data[] = (array) $client;
			}

			return $data;
		}

		private function sort_data($a, $b)
		{
			//apply_filters("debug", "sort data start");
			// Set defaults
			$orderby = 'num_hits';
			$order = 'desc';

			// If orderby is set, use this as the sort column
			if (!empty($_GET['orderby']))
			{
				$orderby = $_GET['orderby'];
			}

			// If order is set use this as the order
			if (!empty($_GET['order']))
			{
				$order = $_GET['order'];
			}

			$result = strnatcasecmp($a[$orderby], $b[$orderby]);

			if ($order === 'asc')
			{
				return $result;
			}

			//    apply_filters("debug", "sort data end");

			return -$result;
		}

		function column_cb($item)
		{
			return sprintf('<input type="checkbox" name="entity_id[]" value="%s" />', $item['id']);
		}

		function column_first_hit_timestamp($item)
		{
			if ($item['first_hit_timestamp'] == null)
			{
				echo _('Unknown');
			}
			else
			{
				return date('M j, g:i:s a', $item['column_first_hit_timestamp']);
			}
		}

		function column_last_hit_timestamp($item)
		{
			if ($item['last_hit_timestamp'] == null)
			{
				echo _('Unknown');
			}
			else
			{
				return date('M j, g:i:s a', $item['column_first_hit_timestamp']);
			}
		}
	}

}