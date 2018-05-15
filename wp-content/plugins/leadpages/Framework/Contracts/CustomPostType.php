<?php


namespace TheLoop\Contracts;


abstract class CustomPostType
{

    private $labels = array();
    private $args   = array();
    public $postTypeNamePlurarl = 'POSTTYPE_NAME_PLURAL';
    public $postTypeName        = 'POSTTYPE_NAME_PLURAL';

    /**
     * Define the labels for your custom post type here
     * @return mixed
     */
    public function defineLabels(){
        $this->labels = array(
          'name'               => _x($this->postTypeNamePlurarl, 'post type general name'),
          'singular_name'      => _x($this->postTypeName, 'post type singular name'),
          'add_new'            => _x($this->postTypeName, 'leadpage'),
          'add_new_item'       => __('Add New '.$this->postTypeName),
          'edit_item'          => __('Edit '.$this->postTypeName),
          'new_item'           => __('New '.$this->postTypeName),
          'view_item'          => __('View '.$this->postTypeNamePlurarl),
          'search_items'       => __('Search '.$this->postTypeNamePlurarl),
          'not_found'          => __('Nothing found'),
          'not_found_in_trash' => __('Nothing found in Trash'),
          'parent_item_colon'  => ''
        );
    }

    /**
     * setup your custom post type here and run register post type hook
     * @return mixed
     */
    public function registerPostType(){
        $this->args   = array(
          'labels'               => $this->labels,
          'description'          => '',
          'public'               => true,
          'publicly_queryable'   => true,
          'show_ui'              => true,
          'query_var'            => true,
          'menu_icon'            => '',
          'capability_type'      => 'page',
          'menu_position'        => null,
          'rewrite'              => array(
            'with_front' => false,
            'slug'       => '/'
          ),
          'can_export'           => false,
          'hierarchical'         => true,
          'has_archive'          => true,
          'supports'             => array(),
        );

        register_post_type( $this->postTypeName, $this->args );
    }

    /**
     * run definelabels function, add init action with registerPostType method
     * add any other added functionality needed for post type
     * @return mixed
     */
    public function buildPostType(){
        $this->defineLabels();
        add_action('init', array($this, 'registerPostType'));
    }
}