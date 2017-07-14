<?php

/**
 * Details for the provider AWeber
 */
function provider_aweber() {

    $provider_id = 'aweber';

    do_action('fca_eoi_before_render_setting_field');
    $eoi_settings = get_option('easy_opt_in_oath_infor_settings');

    if (K::get_var('authorize_success', $eoi_settings)) {
        
        return array(
            'info' => array(
                'id' => 'aweber',
                'name' => 'AWeber',
            ),
            'settings' => array(
                'api_key' => array(
                    'title' => '',
                    'html' => K::wrap('<b>You have successfully connected to your AWeber account!</b>'
                            ,null
                            , array(
                        'return' => true,
                        'format' => ' :input',
                            )
                    ),
                ),
                'webform_oauth_removed' => array(
                    'title' => '',
                    'html' => K::input('{{setting_name}}'
                            , array(
                        'value' => 'TRUE',
                        'type' => 'hidden',
                            )
                            , array(
                        'return' => true,
                            )
                    ),
                ),
            ),
        );
    } else {       
        return array(
            'info' => array(
                'id' => 'aweber',
                'name' => 'AWeber',
            ),
            'settings' =>  array(
                'api_key' => array(
                    'title' => 'Step 1:',
                    'html' => K::input(''
                            , null
                            , array(
                        'return' => true,
                        'format' => '<a tabindex="-1" href="https://auth.aweber.com/1.0/oauth/authorize_app/30de7ab3" target="_blank">Click here to get your authorization code</a>',
                            )
                    ),
                ),
                'client_code' => array(
                    'title' => 'Step 2: Paste in your authorization code:',
                    'html' => K::input('{{setting_name}}'
                            , array(
                        'value' => K::get_var($provider_id . '_client_code', $eoi_settings),
                        'class' => 'regular-text',
                            )
                            , array(
                        'return' => true,
                            )
                    ),
                ),
                'webform_oauth_removed' => array(
                    'title' => '',
                    'html' => K::input('{{setting_name}}'
                            , array(
                        'value' => 'FAIL',
                        'type' => 'hidden',
                            )
                            , array(
                        'return' => true,
                            )
                    ),
                ),
            ),
        );
    }
}