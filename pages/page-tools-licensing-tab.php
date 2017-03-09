<?php
require_once(SC_EDD_CM_U::$PLUGIN_DIRECTORY.'/classes/Utilities/class-sc-edd-crypt.php');
?>

<div class="wrap">

    <?php screen_icon(SC_EDD_CM_Constants::PLUGIN_SLUG); ?>
    <div id="sc-edd-cm-options" class="inside">
        <h2><?php echo SC_EDD_CM_U::__('OVR Key Generator'); ?></h2>
        <?php
        $nonce_action = 'sc-edd-cm-license-operation';
        $nonce        = wp_create_nonce($nonce_action);

        if (isset($_REQUEST['sc-edd-cm-form-action'])) {
            switch ($_REQUEST['sc-edd-cm-form-action']) {
                case 'generate-override-file':

                    $real_license_key = $_REQUEST['license_key'];
                    $edd_swl = EDD_Software_Licensing::instance();
                    $license_id = $edd_swl->get_license_by_key($real_license_key);



                    if($license_id === false)
                    {
                        echo "<span style='font-size:20px'>Invalid key: {$real_license_key}!</span><br/>";
                    }
                    else
                    {
						echo "KEY:{$real_license_key}<br/><br/>";
						$numsites = $edd_swl->license_limit($license_id);
                        echo "SITE COUNT:{$numsites}<br/><br/>";

                        $unscrambledOvrKey = 'SCOVRK' . $real_license_key . '_' . $numsites;
                        $scrambledOvrKey = SC_EDD_CM_Crypt::scramble($unscrambledOvrKey);

                        echo 'UNSCRAMBLED OVR KEY:' . SC_EDD_CM_Crypt::unscramble($scrambledOvrKey) . '<br/><br/>';
                        echo "<input readonly style='margin-right:20px; width:600px; font-size:20px;' id='ovr_key' value ='${scrambledOvrKey}'/><br/>";
                        echo "<button type='button' onclick=sc.edd.cm.tools.copyOvrKey();return false;'>Copy</button>";
                        echo "<br/><br/>";
                        ?>
                            <script type="text/javascript">
                                copyOvrKey = true;
                            </script>
                        <?php
                    }

                    break;
            }
        }

        /* @var $global SC_EDD_CM_Global_Entity */
        $global = SC_EDD_CM_Global_Entity::get_instance();
        ?>


        <div class="wrap">

        <?php screen_icon(SC_EDD_CM_Constants::PLUGIN_SLUG); ?>

            <div id="sc-edd-cm-options" class="inside">

                <div class="wrap">
                    <span>License:</span> <input style="width:400px"; type="text" name="license_key" />
                    <button onclick="sc.edd.cm.tools.generateOverrideFile()">Generate</button>
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


            sc.edd.cm.tools.generateOverrideFile = function () {

                jQuery("#sc-edd-cm-form-action").val("generate-override-file");
                jQuery("#sc-edd-cm-main-form").submit();
            }

            sc.edd.cm.tools.copyOvrKey = function() {
                var ovrKey = document.getElementById("ovr_key");

                ovrKey.focus();
                ovrKey.setSelectionRange(0, ovrKey.value.length);
                var successful = document.execCommand('copy');
                ovrKey.setSelectionRange(0, 0);

                if(successful) {
                    alert('OVR Key Copied.');
                } else
                {
                    alert('Error Copying Key.');
                }
            }
        });
    </script>