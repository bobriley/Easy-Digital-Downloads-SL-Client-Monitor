<?php
require_once(SC_EDD_CM_U::$PLUGIN_DIRECTORY . '/classes/UI/class-sc-edd-cm-client-list.php');

$export_nonce = wp_create_nonce('easy-pie-cspe-export-subscribers');
?>

<div class="wrap">

	<?php screen_icon(SC_EDD_CM_Constants::PLUGIN_SLUG); ?>
	<?php
	if (isset($_GET['settings-updated']))
	{
		echo "<div class='updated'><p>" . SC_EDD_CM_U::__('If you have a caching plugin, be sure to clear the cache!') . "</p></div>";
	}
	?>
    <div id="sc-edd-cm-options" class="inside">
<?php
/*
  Coming Soon & Maintenance Elite Plugin
  Copyright (C) 2017, Snap Creek LLC
  website: snapcreek.com contact: support@snapcreek.com

  Coming Soon & Maintenance Elite Plugin is distributed under the GNU General Public License, Version 3,
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

$nonce_action = 'easy-pie-cspe-export-subscribers';
$nonce = wp_create_nonce($nonce_action);

if(isset($_REQUEST['action']))
{
    $action = $_REQUEST['action'];
    
    if($action == 'delete')
    {
        $subscriber_id = $_REQUEST['subscriber_id'];
        
        SC_EDD_CMSubscriber_Entity::delete_by_id($subscriber_id);
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
    
$global = SC_EDD_CM_Global_Entity::get_instance();
?>

<style lang="text/css">
    .compound-setting { line-height:20px;}
    .narrow-input { width:66px;}
    .long-input { width: 345px;}
</style>

<div class="wrap">

    <?php screen_icon(SC_EDD_CM_Constants::PLUGIN_SLUG); ?>
    <h2><?php SC_EDD_CM_U::_e('EDD SL Clients'); ?></h2>

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
	sc.edd.cm.tools.import = {};
});
</script>