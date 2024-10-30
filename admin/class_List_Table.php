<?php
/*
Author: Ajay Lulia
Version: 1.0
Author URI: http://www.joomlaserviceprovider.com
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

require_once (ABSPATH . 'wp-admin/includes/class-wp-list-table.php');

class JSPSL_Link_List_Table extends WP_List_Table

{



    function __construct()

    {

        global $status, $page;

        parent::__construct(array(

            'singular' => 'store',

            'plural' => 'stores',

        ));

    }



    function column_default($item, $column_name)

    {

        return $item[$column_name];

    }



    function column_age($item)

    {

        return '<em>' . $item['COUNTRY'] . '</em>';

    }



    function column_name($item)

    { 

        $actions = array(

            'edit' => sprintf('<a href="?page=jspsl-add-new-store&id=%s">%s</a>', $item['id'], __('Edit', 'cltd_example')),

            'delete' => sprintf('<a href="?page=%s&action=delete&id=%s">%s</a>', sanitize_text_field($_REQUEST['page']), $item['id'], __('Delete', 'cltd_example')),

        );

        return sprintf('%s %s',

            $item['NAME'],

            $this->row_actions($actions)

        );



    }

   

    function column_cb($item)

    {

        return sprintf('<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['id']);

    }

   

    function get_columns()

    {

        $columns = array(

            'cb' => '<input type="checkbox" />', //Render a checkbox instead of text

            'NAME' => __('NAME', 'cltd_example'),

            'COUNTRY' => __('COUNTRY', 'cltd_example'),

            'STATE' => __('STATE', 'cltd_example'),

            'CITY' => __('CITY', 'cltd_example'),

            'AREA' => __('AREA', 'cltd_example'),

            'CATEGORY_NAME' => __('CATEGORY NAME', 'cltd_example'),

            'ADDRESS' => __('ADDRESS', 'cltd_example'),

        );

        return $columns;

    }



    function get_sortable_columns()

    {

        $sortable_columns = array(

            'NAME' => array('NAME', true),

            'COUNTRY' => array('COUNTRY', false),

            'STATE' => array('STATE', false),

            'CITY' => array('CITY', false),

            'AREA' => array('AREA', false),

            'CATEGORY_NAME' => array('CATEGORY_NAME', false),

            'ADDRESS' => array('ADDRESS', false),

        );

        return $sortable_columns;

    }



    function get_bulk_actions()

    {

        $actions = array(

            'delete' => 'Delete'

        );

        return $actions;

    }

    /**

     * [OPTIONAL] This method processes bulk actions

     * it can be outside of class

     * it can not use wp_redirect coz there is output already

     * in this example we are processing delete action

     * message about successful deletion will be shown on page in next part

     */

    function process_bulk_action()

    {

        global $wpdb;

        $table_name = JSPSL_DB_PREFIX.'stores'; // do not forget about tables prefix

        if ('delete' === $this->current_action()) {

            $ids = isset($_REQUEST['id']) ? sanitize_text_field($_REQUEST['id']) : $_REQUEST['bulk-delete'];

            if (is_array($ids)) $ids = sanitize_text_field(implode(',', $ids));

            if (!empty($ids)) {
                $wpdb->query("DELETE FROM $table_name WHERE ID IN($ids)");

            }

        }

    }

    /**

     * [REQUIRED] This is the most important method

     *

     * It will get rows from database and prepare them to be showed in table

     */

    function prepare_items()

    {

        global $wpdb;

        $table_name = JSPSL_DB_PREFIX.'stores';

        $per_page = 10; 

        $columns = $this->get_columns();

        $hidden = array();

        $sortable = $this->get_sortable_columns();

        $this->_column_headers = array($columns, $hidden, $sortable);

        $this->process_bulk_action();

        $total_items = $wpdb->get_var("SELECT COUNT(ID) FROM $table_name");

        $paged = isset($_REQUEST['paged']) ? max(0, intval($_REQUEST['paged']) - 1) : 0;

        $orderby = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array_keys($this->get_sortable_columns()))) ? sanitize_text_field($_REQUEST['orderby']) : 'NAME';

        $order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? sanitize_text_field($_REQUEST['order']) : 'asc';
        
        $this->items = $wpdb->get_results($wpdb->prepare("SELECT *, a.ID as id FROM $table_name a inner join ".JSPSL_DB_PREFIX."store_categories b on a.CATEGORY_ID=b.ID ORDER BY $orderby $order LIMIT %d OFFSET %d", $per_page, $paged*$per_page), ARRAY_A);

        $this->set_pagination_args(array(

            'total_items' => $total_items, 

            'per_page' => $per_page, 

            'total_pages' => ceil($total_items / $per_page) 

        ));

    }

}





class JSPSL_Category_List_Table extends WP_List_Table

{



    function __construct()

    {

        global $status, $page;

        parent::__construct(array(

            'singular' => 'category',

            'plural' => 'categories',

        ));

    }



    function column_default($item, $column_name)

    {

        return $item[$column_name];

    }



    function column_age($item)

    {

        return '<em>' . $item['CATEGORY_NAME'] . '</em>';

    }



    function column_name($item)

    { 

        $actions = array(

            'edit' => sprintf('<a href="?page=jspsl-add-new-categories&id=%s">%s</a>', $item['id'], __('Edit', 'cltd_example')),

            'delete' => sprintf('<a href="?page=%s&action=delete&id=%s">%s</a>', sanitize_text_field($_REQUEST['page']), $item['id'], __('Delete', 'cltd_example')),

        );

        return sprintf('%s %s',

            $item['CATEGORY_NAME'],

            $this->row_actions($actions)

        );



    }

   

    function column_cb($item)

    {

        return sprintf('<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['id']);

    }

   

    function get_columns()

    {

        $columns = array(

            'cb' => '<input type="checkbox" />', //Render a checkbox instead of text

            'NAME' => __('CATEGORY NAME', 'cltd_example'),

            'DESCRIPTION' => __('DESCRIPTION', 'cltd_example')

        );

        return $columns;

    }



    function get_sortable_columns()

    {

        $sortable_columns = array(

            'NAME' => array('CATEGORY_NAME', true),

            'DESCRIPTION' => array('DESCRIPTION', false)

        );

        return $sortable_columns;

    }



    function get_bulk_actions()

    {

        $actions = array(

            'delete' => 'Delete'

        );

        return $actions;

    }

    /**

     * [OPTIONAL] This method processes bulk actions

     * it can be outside of class

     * it can not use wp_redirect coz there is output already

     * in this example we are processing delete action

     * message about successful deletion will be shown on page in next part

     */

    function process_bulk_action()

    { 

        global $wpdb;

        $table_name = JSPSL_DB_PREFIX.'store_categories'; // do not forget about tables prefix

        if ('delete' === $this->current_action()) {

            $ids = isset($_REQUEST['id']) ? sanitize_text_field($_REQUEST['id']) : $_REQUEST['bulk-delete'];

            if (is_array($ids)) $ids = sanitize_text_field(implode(',', $ids));

            if (!empty($ids)) {

                $wpdb->query("DELETE FROM $table_name WHERE ID IN($ids)");

            }

        }

    }

    /**

     * [REQUIRED] This is the most important method

     *

     * It will get rows from database and prepare them to be showed in table

     */

    function prepare_items()

    {

        global $wpdb;

        $table_name = JSPSL_DB_PREFIX.'store_categories';

        $per_page = 10; 

        $columns = $this->get_columns();

        $hidden = array();

        $sortable = $this->get_sortable_columns();

        $this->_column_headers = array($columns, $hidden, $sortable);

        $this->process_bulk_action();

        $total_items = $wpdb->get_var("SELECT COUNT(ID) FROM $table_name");

        $paged = isset($_REQUEST['paged']) ? max(0, intval($_REQUEST['paged']) - 1) : 0;

        $orderby = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array_keys($this->get_sortable_columns()))) ? sanitize_text_field($_REQUEST['orderby']) : 'CATEGORY_NAME';

        $order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? sanitize_text_field($_REQUEST['order']) : 'asc';

        $this->items = $wpdb->get_results($wpdb->prepare("SELECT *, ID as id FROM $table_name ORDER BY $orderby $order LIMIT %d OFFSET %d", $per_page, $paged*$per_page), ARRAY_A);

       // echo $wpdb->prepare("SELECT *, ID as id FROM $table_name ORDER BY $orderby $order LIMIT %d OFFSET %d", $per_page, $paged);die;

        $this->set_pagination_args(array(

            'total_items' => $total_items, 

            'per_page' => $per_page, 

            'total_pages' => ceil($total_items / $per_page) 

        ));

    }

}

