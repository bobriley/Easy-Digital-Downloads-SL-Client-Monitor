<?php
    $global = SC_EDD_CM_Global_Entity::get_instance();

	if (isset($_GET['tab'])) {

		$active_tab = $_GET['tab'];
	} else {

		$active_tab = 'licensing';
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

    <?php screen_icon(SC_EDD_CM_Constants::PLUGIN_SLUG); ?>
    <h2><?php SC_EDD_CM_U::_e('Tools'); ?></h2>
    <?php
		if (isset($_GET['settings-updated'])) {
			echo "<div class='updated'><p>" . SC_EDD_CM_U::__('If you have a caching plugin, be sure to clear the cache!') . "</p></div>";
		}
    ?>
    
    <div id="sc-edd-cm-options" class="inside">
        <h2 class="nav-tab-wrapper">
            <a href="?page=<?php echo SC_EDD_CM_Constants::PLUGIN_SLUG . '&tab=licensing' ?>" class="nav-tab <?php echo $active_tab == 'licensing' ? 'nav-tab-active' : ''; ?>"><?php SC_EDD_CM_U::_e('Licensing'); ?></a>
            <a href="?page=<?php echo SC_EDD_CM_Constants::PLUGIN_SLUG . '&tab=main' ?>" class="nav-tab <?php echo $active_tab == 'main' ? 'nav-tab-active' : ''; ?>"><?php SC_EDD_CM_U::_e('Main'); ?></a>           
        </h2>
        <form id="sc-edd-cm-main-form" method="post" action="<?php echo admin_url('admin.php?page=' . SC_EDD_CM_Constants::$TOOLS_SUBMENU_SLUG . '&tab=' . $active_tab); ?>" >    
			<input type="hidden" name="sc-edd-cm-form-action" id="sc-edd-cm-form-action" />
            <div id='tab-holder'>
                <?php
                if ($active_tab == 'main') {
                    include 'page-tools-edd-clients-tab.php';                
                } else if ($active_tab == 'licensing') {
                    include 'page-tools-licensing-tab.php';
                }
                
                ?>         
                <!-- after redirect -->
            </div>
        </form>
    </div>
</div>

