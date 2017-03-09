<?php
require_once(SC_EDD_CM_U::$PLUGIN_DIRECTORY . '/classes/UI/class-sc-edd-cm-client-list.php');

?>

<div class="wrap">

	<?php screen_icon(SC_EDD_CM_Constants::PLUGIN_SLUG); ?>
    <div id="sc-edd-cm-options" class="inside">
<?php
$nonce_action = 'sc-edd-cm-export-subscribers';
$nonce = wp_create_nonce($nonce_action);


if(isset($_REQUEST['sc-edd-cm-form-action']))
{
	switch($_REQUEST['sc-edd-cm-form-action'])
	{
		case 'add-test-client':
			$client = new SC_EDD_CM_Client_Entity();
			
			$client->first_hit_timestamp = time() - 120;
			$client->last_hit_timestamp = time();
			$client->ip = "127.0.0.1";
			$client->item_name = 'Duplicator Pro';
			$client->license_key = "12345678901234567890123456789012";
			$client->num_hits = 3;
			$client->url = "http://localhost";
			
			$client->save();
			break;
		
		case 'purge-all':
			$clients = SC_EDD_CM_Client_Entity::get_all();
			
			foreach($clients as $client)
			{
				$client->delete();
			}

			break;
	}
}

$search = null;

if (isset($_REQUEST['s']))
{
     $search = trim($_REQUEST['s']);

     if($search == '')
     {
         $search = null;
     }
 } 

$client_list_control = new SC_EDD_CM_Client_List_Control($search, $nonce_action);
$client_list_control->prepare_items();

/* @var $global SC_EDD_CM_Global_Entity */
$global = SC_EDD_CM_Global_Entity::get_instance();

if($global->collection_enabled)
{
	$collecting = SC_EDD_CM_U::__("(Collecting)");
}
else
{
	$collecting = SC_EDD_CM_U::__("(Not Collecting)");
}
?>

<style lang="text/css">
    .compound-setting { line-height:20px;}
    .narrow-input { width:66px;}
    .long-input { width: 345px;}
</style>

<div class="wrap">

    <?php screen_icon(SC_EDD_CM_Constants::PLUGIN_SLUG); ?>
	<h2><?php echo SC_EDD_CM_U::__('EDD SL Clients') . " {$collecting}"; ?></h2>

    <div id="sc-edd-cm-options" class="inside">

<!--        <script type="text/javascript" src='<?php echo SC_EDD_CM_U::$PLUGIN_URL . "/js/page-subscribers.js?" . SC_EDD_CM_Constants::PLUGIN_VERSION; ?>'></script>-->

        <style lang="text/css">
            .compound-setting { line-height:20px;}
            .narrow-input { width:66px;}
            .long-input { width: 345px;}

            #sc-edd-cm-client-table {font-family: "Lucida Sans Unicode", "Lucida Grande", Sans-Serif;
                                          font-size: 12px;
                                          background: #fff;
                                          width: 100%;
                                          border-collapse: collapse;
                                          text-align: left;
                                          margin: 20px;}
            #sc-edd-cm-client-table th  {
                font-weight:bold;
                text-decoration: underline;
                padding-bottom: 4px;        
                padding-left:10px;
                width: 150px;
                text-align:left;

            }

            #sc-edd-cm-client-table td  {        
                border-bottom: 1px solid #ccc;
                color: #669;
                padding-bottom: 4px;        
                padding-left:10px;
                text-align:left;
                max-width: 150px;
                width: 150px;
                text-overflow: ellipsis;
                overflow: hidden;

            }    

            #sc-edd-cm-client-table button  {
                float:right;
                /*padding: 6px 8px;*/
            }    

            #easy-pie-cs-subscriber-controls {
                text-align:left;
                margin-left:15px;
            }

            #easy-pie-cs-postbox-inside { width: 550px; }

            #easy-pie-cs-delete-confirm { display:none; }

            #easy-pie-cs-subscriber-delete-column { width: 30px!important;}
        </style>

        <div class="wrap">             
            <div id="sc-edd-cm-options" class="inside">
            <?php $client_list_control->display(); ?>            
				
				<button onclick="sc.edd.cm.tools.addTestClient(); return false;" /><?php echo SC_EDD_CM_U::_e('Add Client'); ?> </button>
				<button onclick="sc.edd.cm.tools.purgeAll(); return false;" /><?php echo SC_EDD_CM_U::_e('PurgeAll'); ?> </button>
            </div>                                    
        </div>
    </div>


	</div>
		
</div>


<script>
jQuery(document).ready(function ($) {

	sc = {};
	sc.edd = {};
	sc.edd.cm = {};
	sc.edd.cm.tools = {};
	
	sc.edd.cm.tools.addTestClient = function() {
		
		jQuery("#sc-edd-cm-form-action").val("add-test-client");
		jQuery("#sc-edd-cm-main-form").submit();
	}
	
	sc.edd.cm.tools.purgeAll = function() {
		
		jQuery("#sc-edd-cm-form-action").val("purge-all");
		jQuery("#sc-edd-cm-main-form").submit();
	}
});
</script>