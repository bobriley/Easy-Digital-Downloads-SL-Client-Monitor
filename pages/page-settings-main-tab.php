<div class="wrap">

    <?php screen_icon(SC_EDD_Constants::PLUGIN_SLUG); ?>
    <?php

    ?>
    <div id="easypie-cs-options" class="inside">
        <?php
        $action_updated = null;
		/* @var $global SC_EDD_Global_Entity */
        $global = SC_EDD_Global_Entity::get_instance();

        $error_string = "";

        if (isset($_POST['action']) && $_POST['action'] == 'save')
        {
            check_admin_referer('sc-edd-sl-cm-save-settings');
            // Artificially set the bools since they aren't part of the postback
			
			$global->collection_enabled = false;

            $error_string = $global->set_post_variables($_POST);

            if ($error_string == "")
            {
                $action_updated = $global->save();
            }
        }
        ?>

        <?php wp_nonce_field('sc-edd-sl-cm-save-settings'); ?>
        <input type="hidden" name="action" value="save"/>            
        <?php  if ($error_string != "") :  ?>
            <div id="message" class="error below-h2"><p><?php echo SC_EDD_U::__('Errors present:') . "<br/> $error_string" ?></p></div>
        <?php endif; ?>

        <?php if ($action_updated) : ?>
            <div id="message" class="updated below-h2"><p><span><?php echo SC_EDD_U::__('Settings Saved.'); ?></span><strong style="margin-left:7px;"><?php echo '  ' . SC_EDD_U::__('If you have a caching plugin be sure to clear it.'); ?></strong></p></div>
        <?php endif; ?>

        <div class="postbox" style="margin-top:12px;" >
            <div class="inside" >
				<h3 class="sc-edd-sl-cm-subtitle"><?php SC_EDD_U::_e("Basic") ?></h3>
				
				<div class="sc-edd-sl-cm-subtitle2" style="margin-top:15px"><?php SC_EDD_U::_e("MONITORING") ?></div>	
                <table class="form-table"> 
                    <tr>
                        <th scope="row"><?php echo SC_EDD_U::_e("Enabled") ?></th>
                        <td>                        
							<input type="checkbox" name="collection_enabled" <?php echo $global->collection_enabled ? 'checked' : ''; ?> />                                                                                                                
							<span><?php SC_EDD_U::_e("Yes") ?></span>
                        </td>
                    </tr>   
                </table>				                
            </div>
		</div>      		
</div>
</div>

