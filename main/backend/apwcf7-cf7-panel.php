<?php
add_filter( 'wpcf7_editor_panels','apwcf7_editor_panels', 10, 1 ); 
function apwcf7_editor_panels( $panels ) {
    $paypal = array(
        'paypal-panel' => array(
            'title' => __( 'Paypal Settings', 'contact-form-7' ),
            'callback' => 'apwcf7_pp_strp_editor_panel_popup',
        ),
    );
    $panels = array_merge($panels,$paypal);
    return $panels;
}


function apwcf7_pp_strp_editor_panel_popup() {
    
    if(isset($_REQUEST['post']) && $_REQUEST['post'] != '') {
        $formid = sanitize_text_field($_REQUEST['post']);     
    } else {
        $formid = NULL;
    }
    
    ?>
    <h2><?php echo esc_html_e('Paypal','paypal-with-contact-form-7');?></h2>
        <fieldset>
            <table class="apwcf7_paypal_main">
                <tbody>
                    <tr>
                        <th scope="row">
                            <label><?php echo esc_html_e('Use Paypal','paypal-with-contact-form-7');?> </label>
                        </th>
                        <td>
                            <input type="checkbox" name="<?php echo esc_attr('enabled_use_paypal'); ?>" value="on" <?php if(get_post_meta( $formid, 'enabled_use_paypal', true ) == "on") { echo "checked"; } ?>><label><?php echo esc_html_e('Use Paypal','paypal-with-contact-form-7');?></label>
                        </td>
                    </tr>
                   
                    <tr>
                        <th scope="row">
                            <label><?php echo esc_html_e('Customer Email','paypal-with-contact-form-7');?></label>
                        </th>
                        <td>
                            <input type="text" name="customer_email" value="<?php echo esc_attr(get_post_meta( $formid, 'apwcf7_customer_email', true ));?>">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label><?php echo esc_html_e('Payment Gateway','paypal-with-contact-form-7');?></label>
                        </th>
                        <td>
                            <p><?php echo esc_html_e(' [payment payment] - Use this custom tag to add payment method option to your form.','paypal-with-contact-form-7');?></p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </fieldset>
     <h2><?php echo esc_html_e('PayPal Settings','paypal-with-contact-form-7');?></h2>
        <fieldset>
            <table class="apwcf7_paypal_main">
                <tbody>
                    <tr>
                        <th scope="row">
                            <label><?php echo esc_html_e('Use Sandbox','paypal-with-contact-form-7');?></label>
                        </th>
                        <td>
                            <input type="checkbox" name="enabled_use_Sandbox" value="on" <?php if(get_post_meta( $formid, 'apwcf7_enabled_use_Sandbox', true ) == "on"){ echo "checked"; } ?>><label>Use Sandbox</label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label><?php echo esc_html_e('Pay with Paypal Label','paypal-with-contact-form-7');?></label>
                        </th>
                        <td>
                            <input type="text" name="pw_paypal_label" value="<?php if(get_post_meta( $formid, 'pw_paypal_label', true ) != '') { echo esc_attr(get_post_meta( $formid, 'pw_paypal_label', true )); } else { echo "Pay with Paypal"; } ?>">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label><?php echo esc_html_e('PayPal Business Email','paypal-with-contact-form-7');?></label>
                        </th>
                        <td>
                            <input type="text" name="paypal_bus_email" value="<?php echo esc_attr(get_post_meta( $formid, 'apwcf7_paypal_bus_email', true ));?>">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label><?php echo esc_html_e('Currency','paypal-with-contact-form-7');?></label>
                        </th>
                        <td>
                            <?php $currency = get_post_meta( $formid, 'apwcf7_currency', true ); ?>
                            <select name="currency">
                                <option value="AUD" <?php if($currency == "AUD"){ echo "selected"; }?>>
                                    Australian dollar (AUD)
                                </option>
                                <option value="BRL" <?php if($currency == "BRL"){ echo "selected"; }?>>
                                    Brazilian real (BRL)
                                </option>
                                <option value="GBP" <?php if($currency == "GBP"){ echo "selected"; }?>>
                                    British pound (GBP)
                                </option>
                                <option value="CAD" <?php if($currency == "CAD"){ echo "selected"; }?>>
                                    Canadian dollar (CAD)
                                </option>
                                <option value="CZK" <?php if($currency == "CZK"){ echo "selected"; }?>>
                                    Czech koruna (CZK)
                                </option>
                                <option value="DKK" <?php if($currency == "DKK"){ echo "selected"; }?>>
                                    Danish krone (DKK)
                                </option>
                                <option value="EUR" <?php if($currency == "EUR"){ echo "selected"; }?>>
                                    Euro (EUR)
                                </option>
                                <option value="HKD" <?php if($currency == "HKD"){ echo "selected"; }?>>
                                    Hong kong Dollar (HKD)
                                </option>
                                <option value="HUF" <?php if($currency == "HUF"){ echo "selected"; }?>>
                                    Hungarian forint (HUF)
                                </option>
                                <option value="ILS" <?php if($currency == "ILS"){ echo "selected"; }?>>
                                    Israeli new shekel (ILS)
                                </option>
                                <option value="JPY" <?php if($currency == "JPY"){ echo "selected"; }?>>
                                    Japanese yen (JPY)
                                </option>
                                <option value="MYR" <?php if($currency == "MYR"){ echo "selected"; }?>>
                                    Malaysian Ringgit (MYR)
                                </option>
                                <option value="MXN" <?php if($currency == "MXN"){ echo "selected"; }?>>
                                    Mexican peso (MXN)
                                </option>
                                <option value="TWD" <?php if($currency == "TWD"){ echo "selected"; }?>>
                                    New Taiwan dollar (TWD)
                                </option>
                                <option value="NZD" <?php if($currency == "NZD"){ echo "selected"; }?>>
                                    New Zealand dollar (NZD)
                                </option>
                                <option value="NOK" <?php if($currency == "NOK"){ echo "selected"; }?>>
                                    Norwegian krone (NOK)
                                </option>
                                <option value="PHP" <?php if($currency == "PHP"){ echo "selected"; }?>>
                                    Philippine peso (PHP)
                                </option>
                                <option value="PLN" <?php if($currency == "PLN"){ echo "selected"; }?>>
                                    Polish złoty (PLN)
                                </option>
                                <option value="RUB" <?php if($currency == "RUB"){ echo "selected"; }?>>
                                    Russian ruble (RUB)
                                </option>
                                <option value="SGD" <?php if($currency == "SGD"){ echo "selected"; }?>>
                                    Singapore dollar (SGD)
                                </option>
                                <option value="SEK" <?php if($currency == "SEK"){ echo "selected"; }?>>
                                    Swedish krona (SEK)
                                </option>
                                <option value="CHF" <?php if($currency == "CHF"){ echo "selected"; }?>>
                                    Swiss franc (CHF)
                                </option>
                                <option value="THB" <?php if($currency == "THB"){ echo "selected"; }?>>
                                    Thai baht (THB)
                                </option>
                                <option value="TRY" <?php if($currency == "TRY"){ echo "selected"; }?>>
                                    Turkish Lira (TRY)
                                </option>
                                <option value="USD" <?php if($currency == "USD"){ echo "selected"; }?>>
                                    U.S dollar (USD)
                                </option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label><?php echo esc_html_e('Success Return URL','paypal-with-contact-form-7');?></label>
                        </th>
                        <td>
                            <input type="text" name="suc_url" value="<?php echo esc_attr(get_post_meta( $formid, 'apwcf7_suc_url', true ));?>">
                        </td>
                    </tr> 
                    <tr>
                        <th scope="row">
                            <label><?php echo esc_html_e('Cancel Return URL (Optional)','paypal-with-contact-form-7');?></label>
                        </th>
                        <td>
                            <input type="text" name="can_url" value="<?php echo esc_attr(get_post_meta( $formid, 'apwcf7_can_url', true ));?>">
                        </td>
                    </tr> 
                    
                </tbody>
            </table>
             <h2><?php echo esc_html_e('Static Value','paypal-with-contact-form-7');?></h2>
            <table class="apwcf7_paypal_main">
                <tbody>
                    <tr>
                        <th scope="row">
                            <label><?php echo esc_html_e('Item Description','paypal-with-contact-form-7');?></label>
                        </th>
                        <td>
                            <input type="text" name="item_description" value="<?php echo esc_attr(get_post_meta( $formid, 'apwcf7_item_description', true ));?>">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label><?php echo esc_html_e('Item ID / SKU','paypal-with-contact-form-7');?></label>
                        </th>
                        <td>
                            <input type="text" name="item_id_sku" value="<?php echo esc_attr(get_post_meta( $formid, 'apwcf7_item_id_sku', true ));?>">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label><?php echo esc_html_e('Custom Amount','paypal-with-contact-form-7');?></label>
                        </th>
                        <td>
                            <?php //$amount_choice = get_post_meta( $formid, 'apwcf7_amount_choice', true ); ?>
                            <input type="number" name="paypal_amount" value="<?php echo esc_attr(get_post_meta( $formid, 'apwcf7_paypal_amount', true ));?>">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label><?php echo esc_html_e('Custom Quantity','paypal-with-contact-form-7');?></label>
                        </th>
                        <td>
                        
                            <input type="number" name="paypal_quantity" value="<?php echo esc_attr(get_post_meta( $formid, 'apwcf7_paypal_quantity', true ));?>"  min="1">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label><?php echo esc_html_e('Tax Fixed Rates','paypal-with-contact-form-7');?></label>

                        </th>
                        <td>
                            <input type="number" name="tax_rates" value="<?php echo esc_attr(get_post_meta( $formid, 'apwcf7_tax_rates', true ));?>">
                            <span class="cfppl7notice"><?php echo esc_html_e('Set a fixed amount,for Example for $0.70 tax,enter 0.70','paypal-with-contact-form-7');?></span>

                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label><?php echo esc_html_e('Discount rates','paypal-with-contact-form-7');?></label>
                        </th>
                        <td>
                            <input type="number" name="discount_rate" value="<?php echo esc_attr(get_post_meta( $formid, 'apwcf7_discount_rate', true ));?>">
                        </td>
                    </tr>
                </tbody>
            </table>
            <h2><?php echo esc_html_e('Dynamic Value','paypal-with-contact-form-7');?></h2>
            <p><?php echo esc_html_e('Note: When using Custom Amount and Custom Quantity dynamic values defined below will be ignored, means either use Custom Amount and Custom Quantity for single static product or use multiple products adding details below.','paypal-with-contact-form-7');?></p>
            <div class="after-add-more">
                <div class="field_wrapper">
                    <div class="custom_product">
                        <div class="ocscw_child_div">
                            <?php 
                                $table_dis = get_post_meta( $formid, 'apwcf7_dis', true);
                                $table_prices = get_post_meta( $formid, 'apwcf7_prices', true);
                                $table_qunty = get_post_meta( $formid, 'apwcf7_qunty', true);
                                $table_array = unserialize($table_dis);
                                $table_price_array = unserialize($table_prices);
                                $table_qunty_array = unserialize($table_qunty);


                                if(!empty($table_array[0])) {
                                    $totalcol = get_post_meta( $formid, 'apwcf7_totalcol', true);

                                    if($totalcol == '') {
                                        $totalcol = 1;
                                    }

                                    $totalrow  = get_post_meta( $formid, 'apwcf7_totalrow', true);
                                    
                                    if($totalrow == '') {
                                        $totalrow = 1;
                                    }

                                    echo '<table class="ococf7_tbl">';
                                    echo '<input type="hidden" name="totalrow" value="'.esc_attr($totalrow).'">';
                                    echo '<input type="hidden" name="totalcol" value="'.esc_attr($totalcol).'">';

                                    $count = 0;
                                    ?>
                                    <tr>
                                        <td></td>
                                            <?php 
                                                /*first row create*/
                                                $count = 0;
                                                for($j=0; $j<$totalcol-1; $j++) { ?>
                                                   <td><a class="addcolumn"><img src= "<?php echo  APWCF7_PLUGIN_DIR; ?>/assets/image/plus-circular-button_1.png"></a><a class="deletecolumn"><img src= "<?php echo APWCF7_PLUGIN_DIR; ?>/assets/images/delete.png"></a></td>
                                            <?php  } ?>
                                        <td></td>
                                    </tr>

                                    <?php 

                                    /*end first row create*/
                                    for($i=0; $i<$totalrow; $i++) { ?>
                                        <tr>
                                            <?php 
                                            for($j=0; $j<$totalcol; $j++) { ?>
                                                <td><label>Price</label><input type="text" name="prices[]" value="<?php echo esc_attr($table_price_array[$count]); ?>"></td>
                                               <td><label>Quantity</label><input type="text" name="qunty[]" value="<?php echo esc_attr($table_qunty_array[$count]); ?>"></td>
                                                <td><label>Description</label><input type="text" name="dis[]" value="<?php echo esc_attr($table_array[$count]); ?>"></td>
                                                <?php 
                                                $count++;
                                                if($count == $totalcol) { ?>
                                                    <td><a class="addrow"><img src="<?php echo APWCF7_PLUGIN_DIR; ?>/assets/image/plus-circular-button_1.png"></a></td>
                                                <?php } else {?>
                                                   <td><a class="addrow"><img src= "<?php echo APWCF7_PLUGIN_DIR; ?>/assets/image/plus-circular-button_1.png"></a><a class="deleterow"><img src="<?php echo APWCF7_PLUGIN_DIR; ?>/assets/image/minus.png"></a></td>
                                                <?php  } ?>
                                           <?php  } ?>
                                        </tr>
                                    <?php } ?>

                                    </table>
                                    <?php
                                } else { ?>
                                    <table class="ococf7_tbl">
                                        <input type="hidden" name="totalrow">
                                        <input type="hidden" name="totalcol">
                                        <tr>
                                           <td>
                                                <a class="addcolumn">
                                                    <img src= "<?php //echo apwcf7_PLUGIN_DIR . '/includes/images/plus.png' ?>">
                                                </a>
                                            </td> 
                                            <td>    
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><label><?php echo esc_html_e('Price','paypal-with-contact-form-7');?></label><input type="text" name="prices[]"></td>
                                            <td><label><?php echo esc_html_e('Quantity','paypal-with-contact-form-7');?></label><input type="text" name="qunty[]"></td>
                                            <td><label><?php echo esc_html_e('Description','paypal-with-contact-form-7');?></label><input type="text" name="dis[]"></td>
                                            <td>
                                                <a class="addrow">
                                                    <img src= " <?php echo APWCF7_PLUGIN_DIR . '/assets/image/plus-circular-button_1.png' ?>">
                                                </a>   
                                            </td>
                                        </tr>
                                    </table> 
                                <?php
                                }
                                
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <p><strong><?php echo esc_html_e('Note:','paypal-with-contact-form-7');?></strong> <?php echo esc_html_e('If you want to use radio buttons or select dropdown in form, you can use ','paypal-with-contact-form-7');?><strong><?php echo esc_html_e('[radio price "item1-$10--10" "item2-$20--20"]','paypal-with-contact-form-7');?></strong><?php echo esc_html_e(', in this example ','paypal-with-contact-form-7');?><strong><?php echo esc_html_e('item1-$10','paypal-with-contact-form-7');?></strong><?php echo esc_html_e(' will be your label and','paypal-with-contact-form-7');?> <strong><?php echo esc_html_e('10','paypal-with-contact-form-7');?></strong> <?php echo esc_html_e('will be value of radio button elements. simply seperate label and value with ','paypal-with-contact-form-7');?><strong><?php echo esc_html_e('"--"','paypal-with-contact-form-7');?></strong> <?php echo esc_html_e('seperator.','paypal-with-contact-form-7');?></p>
        </fieldset>
    <?php
}


function  apwcf7_recursive_sanitize_text_field($array) {
    
    if(!empty($array)) {
        foreach ( $array as $key => &$value ) {
            if ( is_array( $value ) ) {
                $value = apwcf7_recursive_sanitize_text_field($value);
            }else{
                $value = sanitize_text_field( $value );
            }
        }
    }
    return $array;
}


add_action( 'wpcf7_after_save','apwcf7_after_save', 10, 1 ); 
function apwcf7_after_save( $instance) {

    $formid = $instance->id;

    if(isset($_POST['enabled_use_paypal']) && !empty($_POST['enabled_use_paypal'])) {
        $enabled_use_paypal = sanitize_text_field($_POST['enabled_use_paypal']);
    } else {
        $enabled_use_paypal = 'off';
    }
    update_post_meta( $formid, 'enabled_use_paypal', $enabled_use_paypal );

    if(isset($_POST['enabled_use_Sandbox']) && !empty($_POST['enabled_use_Sandbox'])) {
        $enabled_use_Sandbox = sanitize_text_field($_POST['enabled_use_Sandbox']);
     } else {
        $enabled_use_Sandbox = 'off';
    }
    update_post_meta( $formid, 'apwcf7_enabled_use_Sandbox', $enabled_use_Sandbox );
    
    /*$enabled_use_Sandbox = sanitize_text_field($_POST['enabled_use_Sandbox']);
    update_post_meta( $formid, 'apwcf7_enabled_use_Sandbox', $enabled_use_Sandbox );*/

    $pw_paypal_label = sanitize_text_field($_POST['pw_paypal_label']);
    update_post_meta( $formid, 'pw_paypal_label', $pw_paypal_label );

    $totalrow         = sanitize_text_field( $_REQUEST['totalrow'] );
    update_post_meta( $formid, 'apwcf7_totalrow', $totalrow );

    $totalcol         = sanitize_text_field( $_REQUEST['totalcol'] );
    update_post_meta( $formid, 'apwcf7_totalcol', $totalcol );

    $dis_data   = apwcf7_recursive_sanitize_text_field( $_REQUEST['dis'] );
    update_post_meta(  $formid, 'apwcf7_dis', serialize($dis_data) );

    $prices_data   = apwcf7_recursive_sanitize_text_field( $_REQUEST['prices'] );
    update_post_meta(  $formid, 'apwcf7_prices', serialize($prices_data) );

    $qunty_data   = apwcf7_recursive_sanitize_text_field( $_REQUEST['qunty'] );
    update_post_meta(  $formid, 'apwcf7_qunty', serialize($qunty_data) );

    $paypal_bus_email = sanitize_text_field($_POST['paypal_bus_email']);
    update_post_meta( $formid, 'apwcf7_paypal_bus_email', $paypal_bus_email );

    $customer_email = sanitize_text_field($_POST['customer_email']);
    update_post_meta( $formid, 'apwcf7_customer_email', $customer_email );

    $item_description = sanitize_text_field($_POST['item_description']);
    update_post_meta( $formid, 'apwcf7_item_description', $item_description );

    $item_id_sku = sanitize_text_field($_POST['item_id_sku']);
    update_post_meta( $formid, 'apwcf7_item_id_sku', $item_id_sku );

    $tax_rates = sanitize_text_field($_POST['tax_rates']);
    update_post_meta( $formid, 'apwcf7_tax_rates', $tax_rates );

    // $amount = sanitize_text_field($_POST['amount']);
    // update_post_meta( $formid, 'apwcf7_amount', $amount );

    $discount_rate = sanitize_text_field($_POST['discount_rate']);
    update_post_meta( $formid, 'apwcf7_discount_rate', $discount_rate );

    $paypal_amount = sanitize_text_field($_POST['paypal_amount']);
    update_post_meta( $formid, 'apwcf7_paypal_amount', $paypal_amount );

    $paypal_quantity = sanitize_text_field($_POST['paypal_quantity']);
    update_post_meta( $formid, 'apwcf7_paypal_quantity', $paypal_quantity );

    $currency = sanitize_text_field($_POST['currency']);
    update_post_meta( $formid, 'apwcf7_currency', $currency );

    $suc_url = sanitize_text_field($_POST['suc_url']);
    update_post_meta( $formid, 'apwcf7_suc_url',$suc_url );

    $can_url = sanitize_text_field($_POST['can_url']);
    update_post_meta( $formid, 'apwcf7_can_url', $can_url );
}


add_filter( 'wpcf7_feedback_response', 'apwcf7_ajax_json_echo', 20, 2 );
function apwcf7_ajax_json_echo( $response, $result ) {
    global $wpdb;
    $table_name    = $wpdb->prefix.'apwcf7_forms';
    $time_now      = time();

    $form = WPCF7_Submission::get_instance();

    if ( $form ) {

        $black_list   = array('_wpcf7', '_wpcf7_version', '_wpcf7_locale', '_wpcf7_unit_tag',
        '_wpcf7_is_ajax_call','cfdb7_name', '_wpcf7_container_post','_wpcf7cf_hidden_group_fields',
        '_wpcf7cf_hidden_groups', '_wpcf7cf_visible_groups', '_wpcf7cf_options','g-recaptcha-response');

        $data           = $form->get_posted_data();
        $files          = $form->uploaded_files();

        $uploaded_files = array();
        foreach ($files as $file_key => $file) {
            array_push($uploaded_files, $file_key);
        }

        $form_data   = array();
        $form_data['apwcf7_status'] = 'unread';

        foreach ($data as $key => $d) {
           
            $matches = array();

            if ( !in_array($key, $black_list ) && !in_array($key, $uploaded_files ) && empty( $matches[0] ) ) {

                $tmpD = $d;

                if ( ! is_array($d) ) {

                    $bl   = array('\"',"\'",'/','\\','"',"'");
                    $wl   = array('&quot;','&#039;','&#047;', '&#092;','&quot;','&#039;');

                    $tmpD = str_replace($bl, $wl, $tmpD );
                }

                $form_data[$key] = $tmpD;
            }
            if ( in_array($key, $uploaded_files ) ) {
                $form_data[$key.'cfdb7_file'] = sanitize_text_field($_SESSION['image_name']);
            }
        }

        $form_post_id = $result['contact_form_id'];
        $form_value   = serialize( $form_data );
        $form_date    = current_time('Y-m-d H:i:s');
        $paymentgateway         = 'payment';
        $insert_id= "";


            if($paymentgateway != '') {
                $payment_gateway     = $data[$paymentgateway];
            } else {
                $payment_gateway     = '';
            }
            if($payment_gateway == 'paypal'){
                    $wpdb->insert( $table_name, array(
                        'form_post_id' => $form_post_id,
                        'form_value'   => $form_value,
                        'form_date'    => $form_date
                    ) );

                $insert_id = $wpdb->insert_id;
            }
    }

    $formid                 = $result['contact_form_id'];
    $customer_email         = get_post_meta( $formid, 'apwcf7_customer_email', true );
    $customer               = get_post_meta( $formid, 'apwcf7_customer_email', true );
    $item_id_sku            = get_post_meta( $formid, 'apwcf7_item_id_sku', true );
    $tax_rates              = get_post_meta( $formid, 'apwcf7_tax_rates', true );
    
    //paypal settings
    $enabled_use_paypal     = get_post_meta( $formid, 'enabled_use_paypal', true );
    $enabled_use_Sandbox    = get_post_meta( $formid, 'apwcf7_enabled_use_Sandbox', true );
    $paypal_bus_email       = get_post_meta( $formid, 'apwcf7_paypal_bus_email', true );
    $currency               = get_post_meta( $formid, 'apwcf7_currency', true );
    $suc_url                = get_post_meta( $formid, 'apwcf7_suc_url', true );
    $can_url                = get_post_meta( $formid, 'apwcf7_can_url', true );

 
    $table_prices               = get_post_meta( $formid, 'apwcf7_prices', true); 
    $discount_rate              = get_post_meta( $formid, 'apwcf7_discount_rate', true );
    $table_price_array          = unserialize($table_prices);
    $table_dis                  = get_post_meta( $formid, 'apwcf7_dis', true); 
    $table_qunty                = get_post_meta( $formid, 'apwcf7_qunty', true); 
    $table_array                = unserialize($table_dis);
    $table_qunty_array          = unserialize($table_qunty);


    if($enabled_use_Sandbox == "on") {
        $paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
        define("APWCF7_USE_SANDBOX", 1);
    } else {
        $paypal_url = "https://www.paypal.com/cgi-bin/webscr";

        define("APWCF7_USE_SANDBOX", 0);
    }

    if($paymentgateway != '') {
        $payment_gateway     = $data[$paymentgateway];
    } else {
        $payment_gateway     = '';
    }
    
    $paypal_amount = get_post_meta( $formid, 'apwcf7_paypal_amount', true );
    
    if(!empty($paypal_amount)) {
        $amount_papl = get_post_meta( $formid, 'apwcf7_paypal_amount', true );
    }

    $paypal_quntity = get_post_meta( $formid, 'apwcf7_paypal_quantity', true );
    
    if(!empty($paypal_quntity)) {
        $quantity_papl = $paypal_quntity;
    } else {
        $quantity_papl = 1;
    }

    //paypal data and paypal form data
    $response[ 'enabled_use_paypal' ]  = $enabled_use_paypal;
    $response[ 'paypal_url' ]          = $paypal_url;
    $response[ 'paypal_bus_email' ]    = $paypal_bus_email;
    $response[ 'payment_gateway' ]     = $payment_gateway;
    $response[ 'suc_url' ]             = $suc_url;
    $response[ 'can_url' ]             = $can_url;
    $html  = '<form action="'.$paypal_url.'" id="apwcf7_paypal" method="post" target="_top">';
    $html .= "<input type='hidden' name='business' value='".$paypal_bus_email."'>";

    $x = 1;
    if(!empty($paypal_amount)) {
        $item_descriptionn = get_post_meta( $formid, 'apwcf7_item_description', true );

        if(!empty($item_descriptionn)) {
            $item_description = $item_descriptionn;
        } else {
            $item_description = 'Payment Using CF7';
        }
        
        $html .= "<input type='hidden' name='item_name_1' value='".$item_description."'>";

    } else {
        if(!empty($table_array)) {
            foreach ($table_array as $description) {
                if(is_array($data[$description])) {
                    if(!empty($data[$description])) {
                        foreach ( $data[$description] as $desc) {
                            $item_description = $desc;
                            if(!empty($item_description)) {
                                    $html .= "<input type='hidden' name='item_name_".$x."' value='".$item_description."'> ";
                            } else {
                                  $html .= "<input type='hidden' name='item_name_".$x."' value='Payment Using CF7'> ";
                            }
                        }
                    }
                } else {
                    if(!empty($data[$description])) {
                        $html .= "<input type='hidden' name='item_name_".$x."' value='".$data[$description]."'> ";
                    } else {
                        $html .= "<input type='hidden' name='item_name_".$x."' value='Payment Using CF7'> ";
                    }
                }

                $x++;
            }
        }
    }

    $x = 1;
    if(!empty($paypal_amount)) {
        $html .= "<input type='hidden' name='amount_1' value='".$amount_papl."'>";   
    } else {
        if(!empty($table_price_array)) {
            foreach ($table_price_array as $key => $value) {
                if(is_array($data[$value])) {
                    if(!empty($data[$value])) {
                        foreach ($data[$value] as $keyy => $valuerr) {
                            
                            $pieces = explode("--", $valuerr);
                            if(!empty($pieces[1])) {
                                $amountaa = $pieces[1];
                            } else {
                                $amountaa = $pieces[0];
                            }

                            $html .= "<input type='hidden' name='amount_".$x."' value='".$amountaa."'>"; 
                        }
                    }
                } else {
                    $html .= "<input type='hidden' name='amount_".$x."' value='".$data[$value]."'>"; 
                }

                $x++;
            }
        }
    }


    $y = 1;
    
    if(!empty($paypal_amount)) {
        $html .= "<input type='hidden' name='quantity_1' value='".$quantity_papl."'>";
    } else {
        if(!empty($table_qunty_array)) {
            foreach ($table_qunty_array as $quantityy) {
                if(is_array($data[$quantityy])) {
                    if(!empty($data[$quantityy])) {
                        foreach ($data[$quantityy] as $qny) {
                            $quantity = $qny;
                            $html .= "<input type='hidden' name='quantity_".$y."' value='".$quantity."'>";
                        }
                    }
                } else {
                    $html .= "<input type='hidden' name='quantity_".$y."' value='".$data[$quantityy]."'>";
                }

                $y++;
            }
        }
    }

   
    $html .= "<input type='hidden' name='item_number' value='".$item_id_sku."'> ";
    $html .="<input  type='hidden' name='discount_amount_cart' value='".$discount_rate."'>";
    $html .= "<input type='hidden' name='no_shipping' value='1'>";
    $html .= "<input type='hidden' name='currency_code' value='".$currency."'> ";
    $html .= "<input type='hidden' name='notify_url' value='".admin_url( 'admin-ajax.php' )."?action=paypal_callback'>";
    $html .= "<input type='hidden' name='cancel_return' value='".$can_url."'>";
    $html .= "<input type='hidden' name='return' value='".$suc_url."'>";
    $html .= "<input type='hidden' name='tax_cart' value='".$tax_rates."'>";
    $html .= "<input type='hidden' name='cmd' value='_cart'>";
    $html .= "<input type='hidden' name='upload' value='1'>";
    $html .= "<input type='hidden' name='custom' value='".$insert_id."'>";
    $html .= "</form>";

    $response[ 'paypal_form' ] = $html;

    return $response;
}


add_action( 'wp_footer','apwcf7_footer');
function apwcf7_footer() { ?>
    <script>
        document.addEventListener( 'wpcf7mailsent', function( event ) {
            var enabled_use_paypal  = event.detail.apiResponse.enabled_use_paypal;
            var payment_gateway     = event.detail.apiResponse.payment_gateway;
            if((event.detail.unitTag) ){
                var apwcf7_id_long     = event.detail.unitTag;
            }else{
                var apwcf7_id_long     = event.detail.id;
            }

            var apwcf7_id          = event.detail.contactFormId;

            var apwcf7_formid      = apwcf7_id;

            if(payment_gateway == 'paypal') {
                if(enabled_use_paypal == "on") {
                    var paypal_form = event.detail.apiResponse.paypal_form;
                    jQuery('body').append(paypal_form);
                    setTimeout(function() {
                        jQuery( "#apwcf7_paypal" ).submit();
                    }, 2000);
                }
            }

        }, false );
    </script>
    <?php
}


add_action( 'wp_ajax_paypal_callback', 'apwcf7_paypal_callback' );
add_action( 'wp_ajax_nopriv_paypal_callback', 'apwcf7_paypal_callback');
function apwcf7_paypal_callback() {

    $ipn_response = !empty($_POST) ? $_POST : false;

    if (!$ipn_response) {
        wp_die( "Empty PayPal IPN Request", "PayPal IPN", array( 'response' => 200 ) );
        return;
    }

    if(APWCF7_USE_SANDBOX == true) {
        $paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
    } else {
        $paypal_url = "https://www.paypal.com/cgi-bin/webscr";
    }

    $validate_ipn = array('cmd' => '_notify-validate');
    $validate_ipn += stripslashes_deep($ipn_response);
    
    //Send back post vars to paypal
    $params = array(
        'body' => $validate_ipn,
        'sslverify' => false,
        'timeout' => 60,
        'httpversion' => '1.1',
        'compress' => false,
        'decompress' => false,
        'user-agent' => 'WP PayPal/' . APWCF7_PAYPAL_VERSION
    );
    $response = wp_remote_post($paypal_url, $params);

    $ipn_verified = false;
    if (!is_wp_error($response) && $response['response']['code'] >= 200 && $response['response']['code'] < 300 && strstr($response['body'], 'VERIFIED')) {
        header( 'HTTP/1.1 200 OK' );
        $ipn_verified = true;
        $item_name        = sanitize_text_field( $ipn_response['item_name'] );
        $item_number      = sanitize_text_field( $ipn_response['item_number'] );
        $payment_status   = sanitize_text_field( $ipn_response['payment_status'] );
        $payment_amount   = sanitize_text_field( $ipn_response['mc_gross'] );
        $payment_currency = sanitize_text_field( $ipn_response['mc_currency'] );
        $txn_id           = sanitize_text_field( $ipn_response['txn_id'] );
        $receiver_email   = sanitize_text_field( $ipn_response['receiver_email'] );
        $payer_email      = sanitize_text_field( $ipn_response['payer_email'] );
        $form_id          = sanitize_text_field( $ipn_response['custom'] );

        update_post_meta($form_id,'apwcf7_item_name',$item_name);
        update_post_meta($form_id,'apwcf7_item_name_1',$item_name_1);
        update_post_meta($form_id,'apwcf7_item_number',$item_number);
        update_post_meta($form_id,'apwcf7_payment_status',$payment_status);
        update_post_meta($form_id,'apwcf7_payment_amount',$payment_amount);
        update_post_meta($form_id,'apwcf7_payment_currency',$payment_currency);
        update_post_meta($form_id,'apwcf7_txn_id',$txn_id);
        update_post_meta($form_id,'apwcf7_receiver_email',$receiver_email);
        update_post_meta($form_id,'apwcf7_payer_email',$payer_email);
    }
}