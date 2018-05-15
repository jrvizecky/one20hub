<?php


namespace TheLoop\Contracts;

/**
 * Interface CustomPostTypeColumns
 * @package TheLoop\Contracts
 *
 * @description add custom post type columns for display.
 * Seperate form CustomPostType.php for seperation of concerns
 */

interface CustomPostTypeColumns
{

    /**
     * define new or override colummns on the post type list / edit.php
     *
     * @param $columns
     *
     * @return mixed
     */
    public function defineColumns($columns);

    /**
     * populate columns with data
     *
     * @param $column
     *
     * @return mixed
     */
    public function populateColumns($column);

    /**
     * add these filters to this
     * add_filter( 'manage_edit-{post type name}_columns', array( &$this, 'defineColumns' ) );
     * add_action( 'manage_posts_custom_column', array( &$this, 'populateColumns' ) );
     * add this to function to the buildPostType function
     *
     * @return mixed
     */
    public function addColumns();

}