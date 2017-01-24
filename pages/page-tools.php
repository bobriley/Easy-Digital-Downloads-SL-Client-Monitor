<?php
    $global = SC_EDD_Global_Entity::get_instance();

	if (isset($_GET['tab'])) {

		$active_tab = $_GET['tab'];
	} else {

		$active_tab = 'main';
	}
?>

<style lang="text/css">
    .compound-setting { line-height:20px;}
    .narrow-input { width:66px;}
    .long-input { width: 345px;}
	
	.postbox .inside {margin-bottom: 6px}
	.form-table th{padding: 8px 8px 8px 25px}
	.form-table td{padding: 3px 0 3px 0}
</style>

<div class="wrap">

    <?php screen_icon(SC_EDD_Constants::PLUGIN_SLUG); ?>
    <h2><?php SC_EDD_U::_e('Tools'); ?></h2>
    <?php
		if (isset($_GET['settings-updated'])) {
			echo "<div class='updated'><p>" . SC_EDD_U::__('If you have a caching plugin, be sure to clear the cache!') . "</p></div>";
		}
    ?>
    
    <div id="easypie-cs-options" class="inside">
        <h2 class="nav-tab-wrapper">  
            <a href="?page=<?php echo SC_EDD_Constants::PLUGIN_SLUG . '&tab=main' ?>" class="nav-tab <?php echo $active_tab == 'main' ? 'nav-tab-active' : ''; ?>"><?php SC_EDD_U::_e('Main'); ?></a>  
        </h2>
        <form id="easy-pie-cs-main-form" method="post" action="<?php echo admin_url('admin.php?page=' . SC_EDD_Constants::$TOOLS_SUBMENU_SLUG . '&tab=' . $active_tab); ?>" > 
            <?php
            //  settings_fields(SC_EDD_Constants::MAIN_PAGE_KEY);
            //do_settings_sections(SC_EDD_Constants::MAIN_PAGE_KEY);                        

            ?>      
            <div id='tab-holder'>
                <?php
                if ($active_tab == 'main') {
                    include 'page-tools-main-tab.php';                
                }                                
                
                ?>         
                <!-- after redirect -->
            </div>           

<!--            <input type="hidden" id="sc-edd-sl-cm-submit-type" name="sc-edd-sl-cm-submit-type" value="save"/>
            
            <p>
     
           <input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes" />
                <input style="margin-left:15px" type="submit" name="submit" id="submit" class="button button-primary" value="Save & Preview" onclick="document.getElementById('sc-edd-sl-cm-submit-type').value = 'preview';debugger;return true;"/>
            </p>                -->

            <a href="https://snapcreek.com/ezp-coming-soon/docs/faqs-tech/" target="_blank"><?php SC_EDD_U::_e('FAQ'); ?></a>
            |           
            <a href="https://snapcreek.com/support/" target="_blank"><?php SC_EDD_U::_e('Contact') ?></a>
        </form>
    </div>
</div>

