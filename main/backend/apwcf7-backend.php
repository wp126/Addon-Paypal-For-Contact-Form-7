<?php

if (!defined('ABSPATH'))
  exit;
      
add_action( 'admin_menu', 'APWCF7_my_menu_pages');
function APWCF7_my_menu_pages() {

    add_submenu_page( 'wpcf7', __( 'Paypal Entries', 'apwcf7' ), __( 'Paypal Entries', 'apwcf7' ),'manage_options', 'apwcf7_paypal_entries',  'APWCF7_list_table_page' );
}

function recursive_sanitize_text_field($array) {
        $new_arr = array();
        if (is_array($array) || is_object($array)){
            foreach ( $array as $key => $value ) {
                if ( is_array( $value ) ) {
                    $value = recursive_sanitize_text_field($value);
                }
                else {
                    $value = sanitize_text_field( $value );
                    $new_arr[] = $value;
                }
            }
        }
        return $new_arr;
    }

function APWCF7_list_table_page() {

    $cfywpay_formid  = empty($_GET['cfywpay_formid']) ? 0 : (int)  sanitize_text_field($_GET['cfywpay_formid']);
    $cfywpay_entryid = empty($_GET['cfywpay_entryid']) ? 0 : (int)  sanitize_text_field($_GET['cfywpay_entryid']);

    if ( !empty($cfywpay_formid) && empty($_GET['cfywpay_entryid']) ) {
        // new apwcf7_Wp_Sub_Page();
        $ListTable = new apwcf7_Data_List_Table();
            $ListTable->data_prepare_items();  ?>
                <div class="wrap">
                    <div id="icon-users" class="icon32"></div>
                    <h2><?php echo get_the_title( (int)  sanitize_text_field($_GET['cfywpay_formid'])); ?></h2>
                    <form method="post" action="">
                        <?php $ListTable->search_box('Search', 'search'); ?>
                        <input type="hidden" name="cf7_pap" value="forcsv">
                        <?php $ListTable->display(); ?>
                    </form>
                </div>
            <?php
        return;
    }

    if( !empty($cfywpay_entryid) && !empty($cfywpay_formid) ) {
        new apwcf7_Form_Details();
        return;
    }

    
    if(empty($cfywpay_formid)) {
        $ListTable = new apwcf7_Main_List_Table();
        $ListTable->prepare_items();
    ?>
        <div class="wrap">
            <div id="icon-users" class="icon32"></div>
            <h2><?php _e( 'Contact Forms Data List', 'apwcf7' ); ?></h2>
            <?php $ListTable->display(); ?>
        </div>
    <?php
    }
}


function APWCF7_download_send_headers( $filename ) {
    $now = gmdate("D, d M Y H:i:s");
    header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
    header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
    header("Last-Modified: {$now} GMT");
    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/download");
    header("Content-Disposition: attachment;filename={$filename}");
    header("Content-Transfer-Encoding: binary");
}


function APWCF7_array2csv(array &$array, $df) {

    if (count($array) == 0) {
        return null;
    }

    $array_keys = array_keys($array);
    $heading    = array();
    $unwanted   = array('cfdb7_', 'your-');

    foreach ( $array_keys as $aKeys ) {
        $tmp       = str_replace( $unwanted, '', $aKeys );
        $heading[] = ucfirst( $tmp );
    }
    fputcsv( $df, $heading );

    foreach ( $array['form_id'] as $line => $form_id ) {
        $line_values = array();
        foreach($array_keys as $array_key ) {
            $val = isset( $array[ $array_key ][ $line ] ) ? $array[ $array_key ][ $line ] : '';
            $line_values[ $array_key ] = $val;
        }
        fputcsv($df, $line_values);
    }
}


add_action( 'init',  'APWCF7_bulk_action_csv' );
function APWCF7_bulk_action_csv() {

    if(isset($_REQUEST['cf7_pap']) && sanitize_text_field($_REQUEST['cf7_pap'])=='forcsv' && sanitize_text_field($_REQUEST['action'])=='csv'){

        global $wpdb;
        $table_name  = $wpdb->prefix.'apwcf7_forms';
        $form_ids = recursive_sanitize_text_field($_POST['contact_form']);
        $all_ids = implode(",", $form_ids);

         $heading_row  = $wpdb->get_results($wpdb->prepare( "SELECT  * FROM `$table_name` WHERE `form_id` IN(%d)",$all_ids));
        //$heading_row = $wpdb->get_results( "SELECT * FROM $table_name WHERE form_id IN($all_ids)", OBJECT );// phpcs:ignore WordPress.Security.EscapeOutput;


        $heading_row    = reset( $heading_row );
        $heading_row    = unserialize( $heading_row->form_value );
        $heading_key    = array_keys( $heading_row );
       

        $total_rows  = COUNT($form_ids); 
        $per_query    = 1000;
        $total_query  = ( $total_rows / $per_query );


        APWCF7_download_send_headers( "cfdb7-" . date("Y-m-d-h-i-s") . ".csv" );
        $df = fopen("php://output", 'w');
       
        ob_start();
        for( $p = 0; $p <= $total_query; $p++ ) {

            $offset  = $p * $per_query;
            $results  = $wpdb->get_results($wpdb->prepare( "SELECT form_id, form_value, form_date FROM `$table_name` WHERE `form_id` IN(%d) LIMIT %s,%s",$all_ids,$offset, $per_query));
            // $results = $wpdb->get_results("SELECT form_id, form_value, form_date FROM $table_name
            // WHERE form_id IN($all_ids) LIMIT $offset, $per_query",OBJECT) ;// phpcs:ignore WordPress.Security.EscapeOutput;
            
            $data  = array();
            $i     = 0;
            foreach ($results as $result) :
                
                $i++;
                $data['form_id'][$i]    = $result->form_id;
                $data['form_date'][$i]  = $result->form_date;
                $resultTmp              = unserialize( $result->form_value );
                $upload_dir             = wp_upload_dir();
                $cfdb7_dir_url          = $upload_dir['baseurl'].'/apwcf7_uploads';

                foreach ($resultTmp as $key => $value):
                    $matches = array();

                    if ( ! in_array( $key, $heading_key ) ) continue;
                    if( ! empty($matches[0]) ) continue;

                    if (strpos($key, 'cfdb7_file') !== false ){
                        $data[$key][$i] = $cfdb7_dir_url.'/'.$value;
                        continue;
                    }
                    if ( is_array($value) ) {

                        $data[$key][$i] = implode(', ', $value);
                        continue;
                    }

                    $data[$key][$i] = str_replace( array('&quot;','&#039;','&#047;','&#092;')
                    , array('"',"'",'/','\\'), $value );

                endforeach;

            endforeach;

            echo APWCF7_array2csv( $data, $df );
        }
        echo ob_get_clean();
        fclose( $df );
        die();
    }
}

if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

//main cf7 list
class apwcf7_Main_List_Table extends WP_List_Table {

    public function prepare_items() {

        global $wpdb;

        $columns     = $this->get_columns();
        $hidden      = $this->get_hidden_columns();
        $data        = $this->table_data();
        $perPage     = 10;
        $currentPage = $this->get_pagenum();
        $totalItems  = count($data);
        $this->set_pagination_args( array(
            'total_items' => $totalItems,
            'per_page'    => $perPage
        ) );
        $data = array_slice($data,(($currentPage-1)*$perPage),$perPage);
        $this->_column_headers = array($columns, $hidden );
        $this->items = $data;
    }
    
    public function get_columns() {

        $columns = array(
            'name' => __( 'Contact Form Name', 'apwcf7' ),
            'count'=> __( 'Number of Entries', 'apwcf7' )
        );
        return $columns;
    }

    public function get_hidden_columns() {
        return array();
    }

    private function table_data() {
        global $wpdb;

        $data         = array();
        
        $table_name   = $wpdb->prefix.'apwcf7_forms';

        $args = array(
            'post_type'=> 'wpcf7_contact_form',
            'order'    => 'ASC',
            'meta_query' => array (
                'relation' => 'OR',
                array (
                'key' => 'enabled_use_paypal',
                'value' => 'on',
                'compare' => '=',
                ),

            )
        );

        $the_query = new WP_Query( $args );
        while ( $the_query->have_posts() ) : $the_query->the_post();
            $form_post_id = get_the_id();
            $totalItems  = $wpdb->get_var($wpdb->prepare( "SELECT count(*) FROM `$table_name` WHERE `form_post_id`=%d",$form_post_id));
            //$totalItems   = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE form_post_id = $form_post_id"); // phpcs:ignore WordPress.Security.EscapeOutput;

       
            $title = get_the_title();
            $link  = "<a class='row-title' href=admin.php?page=apwcf7_paypal_entries&cfywpay_formid=$form_post_id>%s</a>";
            $data_value['name']  = sprintf( $link, $title );
            $data_value['count'] = $totalItems;
            $data[] = $data_value;
        endwhile;
        return $data;
    }

    public function column_default( $item, $column_name ){
        return $item[ $column_name ];
    }
}

class apwcf7_Data_List_Table extends WP_List_Table{
    private $form_post_id;
    private $column_titles;


    public function __construct() {

        parent::__construct(
            array(
                'singular' => 'contact_form',
                'plural'   => 'contact_forms',
                'ajax'     => false
            )
        );
    }


    public function data_prepare_items() {

        global $wpdb;

        $search = empty( $_REQUEST['s'] ) ? false :  recursive_sanitize_text_field($_POST['s']);
        $this->form_post_id =  (int)  sanitize_text_field($_GET['cfywpay_formid']);

        $form_post_id  = $this->form_post_id;

        $columns     = $this->get_columns();
        $hidden      = $this->get_hidden_columns();
        $sortable    = $this->get_sortable_columns();
        $data        = $this->table_data();
        $perPage     = 15;
        $currentPage = $this->get_pagenum();
        $this->process_bulk_action();
        $data = array_slice($data,(($currentPage-1)*$perPage),$perPage);

        $table_name  = $wpdb->prefix.'apwcf7_forms';
        
        if ( ! empty($search) ) {
            $totalItems  = $wpdb->get_var($wpdb->prepare( "SELECT count(*) FROM `$table_name` WHERE `form_value`LIKE '%$search%' AND `form_post_id` =%d",$form_post_id));
         //   $totalItems  = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE form_value LIKE '%$search%' AND form_post_id = '$form_post_id'"); // phpcs:ignore WordPress.Security.EscapeOutput


        }else{
             $totalItems  = $wpdb->get_var($wpdb->prepare( "SELECT count(*) FROM `$table_name` WHERE `form_post_id`=%d ",$form_post_id));
          //  $totalItems  = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE form_post_id = '$form_post_id'") ;// phpcs:ignore WordPress.Security.EscapeOutput


        }

        $this->set_pagination_args( array(
            'total_items' => $totalItems,
            'per_page'    => $perPage
        ) );
        $this->_column_headers = array($columns, $hidden ,$sortable);
        $this->items = $data;

    }


    public function get_columns() {
        $form_post_id  = $this->form_post_id;

        global $wpdb;
        $table_name    = $wpdb->prefix.'apwcf7_forms';
        $results       =$wpdb->get_results( $wpdb->prepare( "SELECT * FROM `$table_name` WHERE `form_post_id`=%d ORDER BY `form_id` DESC LIMIT 20",$form_post_id));
        //  $results       = $wpdb->get_results( "
        //      SELECT * FROM $table_name 
        //     WHERE form_post_id = $form_post_id ORDER BY form_id DESC LIMIT 20", OBJECT 
        // );
        // phpcs:ignore WordPress.Security.EscapeOutput

        $first_row            = isset($results[0]) ? unserialize( $results[0]->form_value ): 0 ;
        $columns              = array();
        $rm_underscore        = apply_filters('remove_underscore_data', true);

        if( !empty($first_row) ){
            $columns['cb']      = '<input type="checkbox" />';
            foreach ($first_row as $key => $value) {

                $matches = array();

                if( $key == 'apwcf7_status' ) continue;
                if( $rm_underscore ) preg_match('/^_.*$/m', $key, $matches);
                if( ! empty($matches[0]) ) continue;

                $key_val       = str_replace( array('your-', 'cfdb7_file'), '', $key);
                $columns[$key] = ucfirst( $key_val );
                
                $this->column_titles[] = $key_val;

                if ( sizeof($columns) > 4) break;
            }
            $columns['form-date'] = 'Date';
            $columns['action'] = 'View';
        }

        return $columns;
    }
    

    public function column_cb($item){
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            $this->_args['singular'],
            $item['form_id']
        );
    }


    public function get_hidden_columns() {
        return  array('form_id');
    }


    public function get_sortable_columns() {
       return array('form-date' => array('form-date', true));
    }


    public function get_bulk_actions() {
        return array(
            'read'   => __( 'Read', 'apwcf7' ),
            'unread' => __( 'Unread', 'apwcf7' ),
            'delete' => __( 'Delete', 'apwcf7' ),
            'csv'    => __( 'Export CSV', 'apwcf7' )
        );
    }

    private function table_data() {
        $data = array();
        global $wpdb;
        $search       = empty( $_REQUEST['s'] ) ? false : sanitize_text_field($_POST['s']);
        $table_name   = $wpdb->prefix.'apwcf7_forms';
        $form_post_id = $this->form_post_id;

        $orderby = isset($_GET['orderby']) ? 'form_date' : 'form_id';
        $order   = isset($_GET['order']) ?  sanitize_text_field($_GET['order']) : 'desc';
        $order   = esc_sql($order);

        if ( ! empty($search) ) {


              
            // $results = $wpdb->get_results( "SELECT * FROM $table_name WHERE  form_value LIKE '%$search%'
            // AND form_post_id = '$form_post_id'
            // ORDER BY $orderby $order", OBJECT );
            $results       =$wpdb->get_results( $wpdb->prepare( "SELECT * FROM `$table_name` WHERE `form_value` LIKE '%$search%' AND `form_post_id` = %d
        ORDER BY %s %s",$form_post_id,$orderby ,$order));

        }else{
            //print_r($orderby);
           // print_r($order);
            //print_r("SELECT * FROM $table_name WHERE form_post_id = $form_post_id
            //ORDER BY $orderby $order");
            $results       =$wpdb->get_results( $wpdb->prepare( "SELECT * FROM `$table_name` WHERE `form_post_id`= %d
                ORDER BY %s %s",$form_post_id,$orderby ,$order));
            // $results = $wpdb->get_results( "SELECT * FROM $table_name WHERE form_post_id = $form_post_id
            // ORDER BY $orderby $order", OBJECT );

            // phpcs:ignore WordPress.Security.EscapeOutput
        }
        
        foreach ( $results as $result ) {

            $form_value = unserialize( $result->form_value );

            $link = "<b><a href=admin.php?page=apwcf7_paypal_entries&cfywpay_formid=%s&cfywpay_entryid=%s>%s</a></b>";
            if(isset($form_value['apwcf7_status']) && ( $form_value['apwcf7_status'] === 'read' ) )
                $link  = "<a href=admin.php?page=apwcf7_paypal_entries&cfywpay_formid=%s&cfywpay_entryid=%s>%s</a>";

            $cfywpay_formid           = $result->form_post_id;
            $form_values['form_id']   = $result->form_id;

            foreach ( $this->column_titles as $col_title) {
                $form_value[ $col_title ] = isset( $form_value[ $col_title ] ) ? $form_value[ $col_title ] : '';
            }

            foreach ($form_value as $k => $value) {

                $ktmp = $k;
                $can_foreach = is_array($value) || is_object($value);

                if ( $can_foreach ) {

                    foreach ($value as $k_val => $val):
                        $val                = esc_html( $val );
                        $form_values[$ktmp] = ( strlen($val) > 150 ) ? substr($val, 0, 150).'...': $val;
                        $form_values[$ktmp] = $form_values[$ktmp];

                    endforeach;
                }else{
                    $value = esc_html( $value );
                    $form_values[$ktmp] = ( strlen($value) > 150 ) ? substr($value, 0, 150).'...': $value;
                    $form_values[$ktmp] = $form_values[$ktmp];
                }

            }
            $form_values['form-date'] = $result->form_date;
            $form_values['action'] = sprintf($link, $cfywpay_formid, $result->form_id, 'View');
            $data[] = $form_values;
        }

        return $data;
    }


    public function process_bulk_action() {

        global $wpdb;
        $table_name = $wpdb->prefix.'apwcf7_forms';
        $action     = $this->current_action();

        if ( isset( $_POST['_wpnonce'] ) && ! empty( $_POST['_wpnonce'] ) ) {

            $nonce        = filter_input( INPUT_POST, '_wpnonce', FILTER_SANITIZE_STRING );
            $nonce_action = 'bulk-' . $this->_args['plural'];

            if ( !wp_verify_nonce( $nonce, $nonce_action ) ) {

                wp_die( 'Not valid..!!' );
            }
        }

        if( 'delete' === $action ) {

            $form_ids = recursive_sanitize_text_field($_POST['contact_form']);

            foreach ($form_ids as $form_id):

                $results       = $wpdb->get_results( "SELECT * FROM $table_name WHERE form_id = $form_id LIMIT 1", OBJECT );// phpcs:ignore WordPress.Security.EscapeOutput
                 $results       =$wpdb->get_results( $wpdb->prepare( "SELECT * FROM `$table_name` WHERE `form_id`= %d
                LIMIT 1",$form_id));

                $result_value  = $results[0]->form_value;
                $result_values = unserialize($result_value);
                $upload_dir    = wp_upload_dir();
                $apwcf7_dirname = $upload_dir['basedir'].'/apwcf7_uploads';

                foreach ($result_values as $key => $result) {

                    if ( ( strpos($key, 'cfdb7_file') !== false ) &&
                        file_exists($apwcf7_dirname.'/'.$result) ) {
                        unlink($apwcf7_dirname.'/'.$result);
                    }

                }

                $wpdb->delete(
                    $table_name ,
                    array( 'form_id' => $form_id ),
                    array( '%d' )
                );
                ?>
                <script type="text/javascript">
                    window.location.href = "<?php echo esc_attr($_SERVER['HTTP_REFERER']); ?>";
                </script>
                <?php
            endforeach;
        
        }else if( 'read' === $action ) {

            $form_ids = recursive_sanitize_text_field($_POST['contact_form']);
            foreach ($form_ids as $form_id):

               // $results       = $wpdb->get_results( "SELECT * FROM $table_name WHERE form_id = '$form_id' LIMIT 1", OBJECT );
                 $results       =$wpdb->get_results( $wpdb->prepare( "SELECT * FROM `$table_name` WHERE `form_id`= %d
                LIMIT 1",$form_id));
                // phpcs:ignore WordPress.Security.EscapeOutput
                $result_value  = $results[0]->form_value;
                $result_values = unserialize( $result_value );
                $result_values['apwcf7_status'] = 'read';
                $form_data = serialize( $result_values );
                $wpdb->query(
                    "UPDATE $table_name SET form_value = '$form_data' WHERE form_id = '$form_id'"
                );

            endforeach;

        }else if( 'unread' === $action ){

            $form_ids =recursive_sanitize_text_field($_POST['contact_form']);
            foreach ($form_ids as $form_id):
                      $results       =$wpdb->get_results( $wpdb->prepare( "SELECT * FROM `$table_name` WHERE `form_id`= %d
                LIMIT 1",$form_id));
                //$results       = $wpdb->get_results( "SELECT * FROM $table_name WHERE form_id = '$form_id' LIMIT 1", OBJECT );// phpcs:ignore WordPress.Security.EscapeOutput
                $result_value  = $results[0]->form_value;
                $result_values = unserialize( $result_value );
                $result_values['apwcf7_status'] = 'unread';
                $form_data = serialize( $result_values );
                $wpdb->query(
                    "UPDATE $table_name SET form_value = '$form_data' WHERE form_id = '$form_id'"
                );
            endforeach;

        }else{

        }
    }
    

    public function column_default( $item, $column_name ){
        return $item[ $column_name ];
    }


    private function sort_data( $a, $b ){
        $orderby = 'form_date';
        $order = 'asc';
        
        if(!empty($_GET['orderby']))
        {
            $orderby =  sanitize_text_field($_GET['orderby']);
        }
        
        if(!empty($_GET['order']))
        {
            $order =  sanitize_text_field($_GET['order']);
        }
        $result = strcmp( $a[$orderby], $b[$orderby] );
        if($order === 'asc')
        {
            return $result;
        }
        return -$result;
    }


    protected function bulk_actions( $which = '' ) {
        if ( is_null( $this->_actions ) ) {
            $this->_actions = $this->get_bulk_actions();
           
            $this->_actions = apply_filters( "bulk_actions-{$this->screen->id}", $this->_actions );
            $two = '';
        } else {
            $two = '2';
        }

        if ( empty( $this->_actions ) )
            return;

        echo '<label for="bulk-action-selector-' . esc_attr( $which ) . '" class="screen-reader-text">' . esc_html( 'Select bulk action', 'paypal-with-contact-form-7' ) . '</label>';
        echo '<select name="action' . esc_attr($two) . '" id="bulk-action-selector-' . esc_attr( $which ) . "\">\n";
        echo '<option value="-1">' . esc_html( 'Bulk Actions', 'paypal-with-contact-form-7' ) . "</option>\n";

        foreach ( $this->_actions as $name => $title ) {
            $class = 'edit' === $name ? ' class="hide-if-no-js"' : '';

            echo "\t" . '<option value="' . esc_attr($name) . '"' . esc_attr($class) . '>' . esc_attr($title) . "</option>\n";
        }

        echo "</select>\n";

        submit_button( esc_html( 'Apply', 'paypal-with-contact-form-7' ), 'action', '', false, array( 'id' => "doaction$two" ) );
        echo "\n";
        $nonce = wp_create_nonce( 'dnonce' );
        echo "<a href='".esc_attr($_SERVER['REQUEST_URI'])."&csv=true&nonce=".esc_attr($nonce)."' style='float:right; margin:0;' class='button'>";
        _e( 'Export CSV', 'paypal-with-contact-form-7' );
        echo '</a>';
        
    }
}
//end main cf7 sub form list

class apwcf7_Form_Details{
    private $form_id;
    private $form_post_id;

    public function __construct() {
       $this->form_post_id = recursive_sanitize_text_field($_GET['cfywpay_formid']);
       $this->form_id = recursive_sanitize_text_field($_GET['cfywpay_entryid']);
       $this->apwcf7_form_details_page();
    }


    public function apwcf7_form_details_page() {
        global $wpdb;
        $table_name    = $wpdb->prefix.'apwcf7_forms';
        $upload_dir    = wp_upload_dir();
        $cfdb7_dir_url = $upload_dir['baseurl'].'/apwcf7_uploads';


        if ( is_numeric($this->form_post_id) && is_numeric($this->form_id) ) {
             $results       =$wpdb->get_results( $wpdb->prepare( "SELECT * FROM `$table_name` WHERE `form_post_id`= %d
                AND `form_id`= %d LIMIT 1",$this->form_post_id,$this->form_id));
                                                                                               
        }

        if ( empty($results) ) {
            wp_die( $message = 'Not valid contact form' );
        }
        ?>
        <div class="apwcf7_trandetais">
            <div class="wrap">
                <div id="welcome-panel" class="welcome-panel">
                    <div class="welcome-panel-content">
                        <div class="welcome-panel-column-container">
                            <h2><?php echo get_the_title( $this->form_post_id ); ?></h2>
                            <p><span><?php echo esc_attr($results[0]->form_date); ?></span></p>
                            <table>
                                <?php $form_data  = unserialize( $results[0]->form_value );

                                    $payment_method = $form_data['payment'];

                                    foreach ($form_data as $key => $data):
                                        echo "<tr>";
                                            $matches = array();

                                            if ( $key == 'apwcf7_status' )  continue;
                                            if( ! empty($matches[0]) ) continue;

                                            if ( strpos($key, 'cfdb7_file') !== false ) {

                                                $key_val = str_replace('cfdb7_file', '', $key);
                                                $key_val = str_replace('your-', '', $key_val);
                                                $key_val = ucfirst( $key_val );
                                                echo '<td><b>'.esc_attr($key_val).'</b></td><td><a href="'.esc_attr($cfdb7_dir_url).'/'.esc_attr($data).'">'
                                                .esc_attr($data).'</a></td>';

                                            } else {


                                                if ( is_array($data) ) {

                                                    $key_val = str_replace('your-', '', $key);
                                                    $key_val = ucfirst( $key_val );
                                                    $arr_str_data =  implode(', ',$data);
                                                    $arr_str_data =  esc_html( $arr_str_data );
                                                    echo '<td><b>'.esc_attr($key_val).'</b></td><td> '. esc_attr(nl2br($arr_str_data)) .'</td>';

                                                } else {

                                                    $key_val = str_replace('your-', '', $key);
                                                    $key_val = ucfirst( $key_val );
                                                    $data    = esc_html( $data );
                                                    echo '<td><b>'.esc_attr($key_val).'</b></td><td> '.esc_attr(nl2br($data)).'</td>';
                                                }
                                            }
                                        echo "</tr>";
                                    endforeach;
                                    
                                ?>
                                <tr>
                                    <td><b><?php echo esc_html('Item (Description)');?> </b></td>
                                    <td><?php echo esc_attr(get_post_meta( $this->form_id, 'apwcf7_item_name', true ));?></td>
                                </tr>
                                <tr>
                                    <td><b><?php echo esc_html('Item ID / SKU');?> </b></td>
                                    <td><?php echo esc_attr(get_post_meta( $this->form_id, 'apwcf7_item_number', true ));?></td>
                                </tr>
                                <?php
                                if($payment_method == 'stripe') {
                                ?>
                                <tr>
                                    <td><b><?php echo esc_html('Payment Status');?></b></td>
                                    <td><?php echo esc_attr(get_post_meta( $this->form_id, 'apwcf7_payment_status', true ));?></td>
                                </tr>
                                <?php
                                }
                                ?>
                                <tr>
                                    <td><b><?php echo esc_html('Payment Amount');?></b></td>
                                    <td><?php echo esc_attr(get_post_meta( $this->form_id, 'apwcf7_payment_amount', true ));?></td>
                                </tr>
                                <tr>
                                    <td><b><?php echo esc_html('Payment Currency');?></b></td>
                                    <td><?php echo esc_attr(get_post_meta( $this->form_id, 'apwcf7_payment_currency', true ));?></td>
                                </tr>
                                <tr>
                                    <td><b><?php echo esc_html('Transaction Id');?></b></td>
                                    <td><?php echo esc_attr(get_post_meta( $this->form_id, 'apwcf7_txn_id', true ));?></td>
                                </tr>

                                <?php
                                if($payment_method == 'paypal') {
                                ?>
                                <tr>
                                    <td><b>Receiver Email</b></td>
                                    <td><?php echo esc_attr(get_post_meta( $this->form_id, 'apwcf7_receiver_email', true ));?></td>
                                </tr>
                                <tr>
                                    <td><b>Payer Email</b></td>
                                    <td><?php echo esc_attr(get_post_meta( $this->form_id, 'apwcf7_payer_email', true ));?></td>
                                </tr>
                                <?php
                                }
                                ?>
                            </table>
                            <?php
                                $form_data['apwcf7_status'] = 'read';
                                $form_data = serialize( $form_data );
                                $form_id = $results[0]->form_id;
                                $wpdb->query( "UPDATE $table_name SET form_value = '$form_data' WHERE form_id = $form_id");
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}