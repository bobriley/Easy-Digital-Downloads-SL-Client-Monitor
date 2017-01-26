
<?php
if (isset($_GET['tab']))
{
    $active_tab = $_GET['tab'];
}
else
{
    $active_tab = 'main';
}
?>
	
<style lang="text/css">
    .narrow-input { width:66px;}
	.medium-input { width: 230px; }
    .long-input { width: 280px;}

	.postbox .inside {margin-bottom: 6px}
	.form-table th{padding: 8px 8px 8px 25px}
	.form-table td{padding: 3px 0 3px 0}
</style>

<div class="wrap">

    <h2><?php SC_EDD_CM_U::_e('Settings'); ?></h2>
    <?php

    $global = SC_EDD_CM_Global_Entity::get_instance();

    ?>

    <div id="sc-edd-cm-options" class="inside">
        <h2 class="nav-tab-wrapper">  
            <a href="?page=<?php echo SC_EDD_CM_Constants::$SETTINGS_SUBMENU_SLUG . '&tab=main' ?>" class="nav-tab <?php echo $active_tab == 'main' ? 'nav-tab-active' : ''; ?>"><?php SC_EDD_CM_U::_e('General'); ?></a>  
        </h2>
        
        <form name="sc_edd_cm_main_form" id="sc_edd_cm_main_form" method="post" action="<?php echo admin_url('admin.php?page=' . SC_EDD_CM_Constants::$SETTINGS_SUBMENU_SLUG . '&tab=' . $active_tab); ?>" > 
            <div id='tab-holder'>
                <?php
                if ($active_tab == 'main')
                {
                    include 'page-settings-main-tab.php';
                }              
                ?>   
            </div>           

            <input type="hidden" id="sc-edd-cm-submit-type" name="sc-edd-cm-submit-type" value="save"/>

            <p>
                <input type="submit" name="submit2" id="submit2" class="button button-primary" value="Save Changes" />
            </p>
        </form>
    </div>
</div>

