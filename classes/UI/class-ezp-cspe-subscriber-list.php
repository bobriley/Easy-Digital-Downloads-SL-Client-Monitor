<?php
/*
  Easy Pie Lead Watch Plugin
  Copyright (C) 2016, Snap Creek LLC
  website: snapcreek.com contact: support@snapcreek.com

  Easy Pie Lead Watch Plugin is distributed under the GNU General Public License, Version 3,
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

require_once(EZP_CSPE_U::$PLUGIN_DIRECTORY . '/classes/Utilities/class-ezp-cspe-utility.php');

if (!class_exists('EZP_CSPE_Subscriber_List_Control'))
{

    /**
     * @author Bob Riley <support@snapcreek.com>
     * @copyright 2015 Snap Creek LLC
     */
    class EZP_CSPE_Subscriber_List_Control extends WP_List_Table
    {
//        private $search = null;
//        private $query_info = null;
        private $nonce_action = null;
        //private $subscriber_cache = null;

        public function __construct($search, $nonce_action)
        {
          //  $this->search = $search;
         //   $this->query_info = $query_info;
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
                case 'id':
                case 'friendly_name':
                case 'creation_date':
                case 'email_address':
                    return $item[$column_name];

                default:
                    return print_r($item, true);
            }
        }

        public function prepare_items()
        {
            $columns = $this->get_columns();
            $hidden = $this->get_hidden_columns();
            $sortable = $this->get_sortable_columns();

            //$this->process_bulk_action();

            $data = $this->get_table_data();
            usort($data, array(&$this, 'sort_data'));

            $perPage = $this->get_items_per_page('contacts_per_page', 10);
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
              //  $this->extra_tablenav($which);
                $this->pagination($which);
                ?>
                <br class="clear" />
            </div>
            <?php
        }
        
//        public function extra_tablenav($which)
//        {
//            if($which == 'bottom')
//            {                
//                $nonce = wp_create_nonce($this->nonce_action);
//                echo '<button style="margin-top:2px;" id="btn-export" type="button button-secondary" onclick="location.href = ajaxurl + \'?action=EZP_CSPE_export_all_subscribers&_wpnonce=' . $nonce . '\';return false;">' . __('CSV Export') . '</button>';
//            }
//        }

//        public function process_bulk_action()
//        {
//
//            // RSR TODO
//            // security check!
////            if (isset($_POST['_wpnonce']) && !empty($_POST['_wpnonce'])) {
////
////                $nonce = filter_input(INPUT_POST, '_wpnonce', FILTER_SANITIZE_STRING);
////                $action = 'bulk-' . $this->_args['plural'];
////
////                if (!wp_verify_nonce($nonce, $action))
////                    wp_die('Nope! Security check failed!');
////            }
//
//            $action = $this->current_action();
//
//            if ($action == 'delete')
//            {
//                if (isset($_REQUEST['contact_id']))
//                {
//                    $contact_ids = $_REQUEST['contact_id'];
//
//                    foreach ($contact_ids as $contact_id)
//                    {
//                        EZP_IBC_U::debug("deleting $contact_id");
//                        EZP_IBC_Contact_Entity::delete_by_id($contact_id);
//                    }
//                }
//            }
//            else if (EZP_IBC_U::starts_with($action, 'listadd-'))
//            {
//                EZP_IBC_U::debug("list add action $action");
//                $list_id = (int) substr($action, 8);
//
//                $contact_ids = $_REQUEST['contact_id'];
//
//                foreach ($contact_ids as $contact_id)
//                {
//                    EZP_IBC_U::debug("adding $contact_id to list $list_id");
//
//                    $contact = EZP_IBC_Contact_Entity::get_by_id($contact_id);
//
//                    if ($contact != null)
//                    {
//                        $contact->add_to_list($list_id);
//                        $contact->save();
//                    }
//                }
//            }
//
//            apply_filters("debug", "process bulk end");
//
//            return;
//        }

        public function get_columns()
        {
            $columns = array(
              //  'cb' => '<input type="checkbox" />',
                //   'id' => __('ID'),                
                'friendly_name' => __('Name'),              
                'email_address' => ('Email'),
                'creation_date' => ('Created')                
            );

            return $columns;
        }

        public function get_hidden_columns()
        {
            return array('id');
        }

        public function get_sortable_columns()
        {
            return array('friendly_name' => array('friendly_name', false),
                'creation_date' => array('creation_date', false));
        }

//        public function get_bulk_actions()
//        {
//
//            $actions = array();
//
//            $lists = EZP_IBC_List_Entity::get_all();
//
//            foreach ($lists as $list)
//            {
//                $actions["listadd-$list->id"] = __('Add to ') . $list->name;
//            }
//
//            $actions ['delete'] = __('Delete');
//
//            return $actions;
//        }

        private function get_table_data()
        {

            global $wpdb;

//            if ($this->query_info == null)
//            {
//                // A custom query overrides all else
//                $contacts = EZP_IBC_Report_Helper::get_filtered_contacts($this->stage, $this->event, $this->event_parameter, $this->event_range, $this->search);
//            }
//            else
//            {
//                $contacts = EZP_IBC_Contact_Entity::get_all_by_custom_query($this->query_info->query);
//            }

            $subscribers = EZP_CSPE_Subscriber_Entity::get_all();
            
            $data = array();
        //    $this->subscriber_cache = array();
            
            foreach($subscribers as $subscriber)
            {
                $data[] = (array)$subscriber;
           //     $this->subscriber_cache[$subscriber->id] = $subscriber;
            }

//            foreach ($contacts as $contact)
//            {
//
//                // $contact->list_string = '';
//                //      $contact->event_count = -1;
//                $contact->display_name = '';
//
//                $data[] = (array) $contact;
//
//                $this->contact_cache[$contact->id] = $contact;
//            }


            return $data;
        }

        private function sort_data($a, $b)
        {
            apply_filters("debug", "sort data start");
            // Set defaults
            $orderby = 'creation_date';
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

            $result = strnatcmp($a[$orderby], $b[$orderby]);

            if ($order === 'asc')
            {
                return $result;
            }

            apply_filters("debug", "sort data end");

            return -$result;
        }

        function column_cb($item)
        {
            return sprintf('<input type="checkbox" name="contact_id[]" value="%s" />', $item['id']);
        }

        function column_creation_date($item)
        {
            if ($item['creation_date'] == null)
            {
                echo _('Unknown');
            }
            else
            {
                return EZP_CSPE_U::get_simplified_local_time_from_formatted_gmt($item['creation_date'], true);
            }
        }

        // <editor-fold desc="Column display functions">

        function column_friendly_name($item)
        {
            $delete_url = menu_page_url(EZP_CSPE_Constants::$SUBSCRIBERS_SUBMENU_SLUG, false);

            $delete_url = EZP_CSPE_U::append_query_value($delete_url, 'subscriber_id', $item['id']);
            $delete_url = EZP_CSPE_U::append_query_value($delete_url, 'action', 'delete');
            $delete_url = wp_nonce_url($delete_url, $this->nonce_action);
           

            $delete_anchor = "<a href='$delete_url'>" . __('Delete') . '</a>';

            $actions = array(

                'delete' => $delete_anchor,
            );

            $subscriber = EZP_CSPE_Subscriber_Entity::get_by_id((int) $item['id']);

            return sprintf("%1\$s %2\$s", $subscriber->friendly_name, $this->row_actions($actions));
        }


        // </editor-fold>
    }
}