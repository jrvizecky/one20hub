<?php

namespace LeadpagesWP\Admin\MetaBoxes;

use LeadpagesWP\Admin\CustomPostTypes\LeadpagesPostType;
use LeadpagesWP\models\LeadPagesPostTypeModel;
use TheLoop\Contracts\MetaBox;

class LeadpagesCreate extends LeadpagesPostType implements MetaBox
{

    /**
     * @var \LeadpagesWP\models\LeadPagesPostTypeModel
     */
    private $postTypeModel;

    /**
     * @var \Leadpages\Pages\LeadpagesPages
     */
    private $pagesApi;

    public function __construct()
    {
        global $leadpagesApp;

        $this->pagesApi      = $leadpagesApp['pagesApi'];
        $this->postTypeModel = $leadpagesApp['lpPostTypeModel'];
        add_action('wp_ajax_get_pages_dropdown', [$this, 'generateSelectList']);
        add_action('wp_ajax_get_pages_dropdown_nocache', [$this, 'generateSelectListNoCache']);
        add_action('wp_ajax_nopriv_get_pages_dropdown', [$this, 'generateSelectList']);
        add_action('wp_ajax_nopriv_get_pages_dropdown_nocache', [$this, 'generateSelectListNoCache']);
    }

    public static function getName()
    {
        return get_called_class();
    }

    public function defineMetaBox()
    {
        add_meta_box(
            "leadpage-create",
            "Leadpages Create",
            [$this, 'callback'],
            $this->postTypeName,
            "normal",
            "high",
            null
        );
    }

    public function callBack($post, $box)
    {
        $useCache = LeadPagesPostTypeModel::getMetaCache($post->ID);
        $currentType = LeadPagesPostTypeModel::getMetaPageType($post->ID);
        $slug = LeadPagesPostTypeModel::getMetaPagePath($post->ID);
        $is_edit = isset($_GET['action']) && $_GET['action'] == 'edit';
        $action = $is_edit ? 'Edit' : 'Add New';
        ?>
    <style>
    .select2-container--default .select2-results>.select2-results__options {
        max-height: 400px !important;
    }
    </style>
    <div class="leadpages-edit-wrapper" data-is-edit="<?php echo $is_edit; ?>">
        <div id="leadpages-header-wrapper" class="flex flex--xs-between flex--xs-middle">
            <div class="ui-title-nav" aria-controls="navigation">
                <div class="ui-title-nav__img">
                    <i class="lp-icon lp-icon--alpha">leadpages_mark</i>
                </div>
                <div class="ui-title-nav__content">
                    <?php echo $action; ?> Leadpage
                </div>
            </div>

            <button id="publish" name="publish" class="ui-btn">
                Publish
                <div class="ui-loading ui-loading--sm ui-loading--inverted">
                    <div class="ui-loading__dots ui-loading__dots--1"></div>
                    <div class="ui-loading__dots ui-loading__dots--2"></div>
                    <div class="ui-loading__dots ui-loading__dots--3"></div>
                </div>
            </button>
        </div>

        <div class="leadpages-edit-body">
            <div class="flex leadpages-loading">
                <div class="ui-loading">
                    <div class="ui-loading__dots ui-loading__dots--1"></div>
                    <div class="ui-loading__dots ui-loading__dots--2"></div>
                    <div class="ui-loading__dots ui-loading__dots--3"></div>
                </div>
            </div>
            <div class="flex">
                <div class="flex__item--xs-12">
                    <p class="header_text">
                        Welcome to the Leadpages admin.
                        Publish a Leadpage to your site in a few easy steps below:
                    </p>
                </div>
                <h3 class="flex__item--xs-12">Select a Leadpage</h3>
            </div>
            <div class="select_a_leadpage flex">

                <div class="leadpages_search_container flex__item--xs-7">
                    <div id="leadpages_my_selected_page"></div>
                </div>
                <div class="flex__item--xs-4" >
                <p class="flex" style="align-items: center; color: #888; margin-left: -4px;">
                    <i class="sync-leadpages lp-icon lp-icon--xsm lp-icon-sync" style="display: inline;"></i>
                    <small class="human-diff" style="padding-top: 6px; padding-bottom: 4px; padding-left: 4px; display: none;">Page listing synced: <span class="diff-message"></span>. </small>
                </p>
                </div>
            </div>

            <div class="flex">
            <div class="flex__item-xs-12">
                <p><small>Have a lot of Leadpages? Use the
                search box to quickly find your Leadpage by name.</small></p>
            </div>
            </div>

            <div class="select_a_leadpage_type flex">
                <h3 class="flex__item--xs-12">Select a Page Type</h3>

                <p class="flex__item--xs-12"> Please select a Leadpage display type below.</p>

                <div class="leadpage_type_container flex">
                    <label id="leadpage-normal-page" class="leadpage_type_box">
                        <h3 class="header">Normal Page</h3>

                        <p class="section_description">
                            This display type will allow you to direct people to this leadpage by using the
                            slug below.
                        </p>
                        <input id="leadpage-normal-page" type="radio" name="leadpages-post-type" class="leadpages-post-type leadpage-normal-page"
                               value="lp" <?php echo $currentType == "lp" ? 'checked=checked"' : ""; ?> >
                    </label>

                    <label for="leadpage-home-page" class="leadpage_type_box">
                        <h3 class="header">Home Page</h3>

                        <p>
                            This will take over your home page on your blog.
                            Anytime someone goes to your home page it will show
                            this page.
                        </p>
                        <input id="leadpage-home-page" type="radio" name="leadpages-post-type" class="leadpages-post-type leadpage-home-page"
                               value="fp" <?php echo $currentType == "fp" ? 'checked=checked"' : ""; ?> >
                    </label>

                    <label for="leadpage-welcome-page" class="leadpage_type_box">
                        <h3 class="header">Welcome Gate &trade;</h3>

                        <p>
                            A Welcome Gate &trade; page will be the first page any new visitor to your site sees.
                        </p>
                        <input id="leadpage-welcome-page" type="radio" name="leadpages-post-type" class="leadpages-post-type leadpage-welcomegate-page"
                               value="wg" <?php echo $currentType == "wg" ? 'checked=checked"' : ""; ?> >
                    </label>

                    <label for="leadpage-404-page" class="leadpage_type_box">
                        <h3 class="header">404 Page</h3>

                        <p>
                            Set a Leadpage as your 404
                            page to ensure you are not missing out on any conversions.
                        </p>
                        <input id="leadpage-404-page" type="radio" name="leadpages-post-type" class="leadpages-post-type leadpage-404-page"
                               value="nf" <?php echo $currentType == "nf" ? 'checked=checked"' : ""; ?> >
                    </label>

                </div>
            </div>

            <div id="leadpage-slug" class="leadbox_slug flex">
                <h3 class="flex__item--xs-12">Set a Custom Slug</h3>

                <p class="flex__item--xs-12">
                    Enter a custom slug for your Leadpage.
                    <small>This will be the url to view your Leadpage on your site.</small>
                    <br />
                </p>

                <div class="flex__item--xs-12 leadpage_slug_container">
                    <span class="lp_site_main_url"><?php echo $this->leadpages_permalink(); ?></span>
                    <input type="text" name="leadpages_slug" class="leadpages_slug_input" value="<?php echo $slug; ?>">
                </div>
            </div>
            <div id="leadpage-cache" class="leadbox_slug flex">
                <h3 class="flex__item--xs-12">Set Page Cache</h3>

                <p class="flex__item--xs-12">
                    Choose whether or not you would like to cache your page html locally.
                    This will create faster page loads, however if a page is split tested, the split tested version
                    will not load.
                </p>

                <div class="flex__item--xs-12 leadpage_cache_container">
                    <input type="radio" id="cache_this_true" name="cache_this" value="true"  <?php echo ($useCache == 'true') ? 'checked="checked"': ''; ?>> Yes, cache for improved performance. <br />
                    <input type="radio" id="cache_this_false" name="cache_this" value="false"  <?php echo ($useCache != 'true') ? 'checked="checked"': ''; ?>> No, re-fetch on each visit; slower, but required for split testing.
                </div>
            </div>
            <input type="hidden" name="leadpages_name" id="leadpages_name">
            <input type="hidden" name="leadpage_type" id="leadpageType">
        </div>
        <div id="leadpages-footer-wrapper" class="flex flex--xs-end flex--xs-middle">

            <button id="publish" name="publish" class="ui-btn">
                Publish
                <div class="ui-loading ui-loading--sm ui-loading--inverted">
                    <div class="ui-loading__dots ui-loading__dots--1"></div>
                    <div class="ui-loading__dots ui-loading__dots--2"></div>
                    <div class="ui-loading__dots ui-loading__dots--3"></div>
                </div>
            </button>
        </div>
        <?php
    }

    public function registerMetaBox()
    {
        add_action('add_meta_boxes', [$this, 'defineMetaBox']);
    }

    /**
     * Helper for wp ajax action to refresh pages w/o using cache
     */
    public function generateSelectListNoCache()
    {
        $this->generateSelectList(true);
    }

    public function generateSelectList($refresh_cache = false)
    {
        global $leadpagesApp;

        $id = sanitize_text_field($_POST['id']);
        $currentPage = LeadPagesPostTypeModel::getMetaPageId($id);
        if (!$currentPage) {
            $currentPage = $leadpagesApp['lpPostTypeModel']->getPageByXORId($id);
        }

        $pages = $this->fetchPages($refresh_cache);
        $items['_items'] = $pages['_items'];
        $items = $leadpagesApp['pagesApi']->sortPages($items);
        $optionString = '<select id="select_leadpages" '
                            . 'class="leadpage_select_dropdown" name="leadpages_my_selected_page">';

        foreach ($items['_items'] as $page) {
            $pageId = $page['id'];
            $is_split = $page['isSplit'];
            $last_published = date('Y-m-d', $page['updated']);
            $slug = $page['slug'];

            $composite_id = $this->makeCompositeId($page);

            $edit_url = $page['editUrl'];
            $preview_url = $page['previewUrl'];
            $publish_url = $page['publishUrl'];
            $optins = $page['optins'] ?: 0;
            $views = $page['views'] ?: 0;

            $optionString .= "
                <option data-slug='{$slug}'
                        data-issplit='{$is_split}'
                        data-published='{$last_published}'
                        data-optins='{$optins}'
                        data-views='{$views}'
                        data-preview-url='{$preview_url}'
                        data-publish-url='{$publish_url}'
                        data-edit-url='{$edit_url}'
                        value='{$composite_id}'"
                        . ($this->isCurrentPage($page, $currentPage) ? ' selected="selected"' : '')
                .">{$page['name']}</option>";
        }

        $optionString .= '</select>';
        echo $optionString;
        die();
    }

    /**
     * Helper to compare selected page id variations with current
     *
     * @param mixed  $page          page api row
     * @param string $currentPageId active edit page's id
     *
     * @return bool
     */
    private function isCurrentPage($page, $currentPageId)
    {
        return $page['id'] == $currentPageId
            || $page['xor_hex_id'] == $currentPageId
            || $page['publicMetaId'] == $currentPageId
            || $page['contentUuid'] == $currentPageId
            || $this->makeCompositeId($page) == $currentPageId;
    }

    /**
     * Helper to determine if data structure is for split test
     *
     * @param mixed $page From pages api
     *
     * @return bool
     */
    private function isSplit($page)
    {
        return isset($page['isSplit']) && (bool)$page['isSplit'];
    }

    /**
     * Helper to strip temporary prepended id string
     *
     * @param mixed $page
     *
     * @return string
     */
    private function cleanId($page)
    {
        return str_replace('cid-', '', $page['id']);
    }

    /**
     * Helper to choose which value to use for legacy xor id
     *
     * @param mixed $page Page data
     *
     * @return string id with ':' replaced by ';' to prevent conflicts
     */
    private function whichXorId($page)
    {
        $hex_id = $this->isSplit($page)
            ? $page['_meta']['id']
            : $page['xor_hex_id'];

        return str_replace(':', ';', $hex_id);

    }
    /**
     * Helper to create composite id wordpress uses to identify assets
     *
     * @param mixed $page Page data
     *
     * @return string
     */
    private function makeCompositeId($page)
    {
        $hex_id = $this->whichXorId($page);
        $page_id = $this->cleanId($page);
        return $hex_id . ':' . $page_id;
    }

    protected function fetchPages($refresh_cache = false)
    {
        if ($refresh_cache) {
            $this->clearPagesCache();
        }

        if (false === ($pages = get_transient('user_leadpages'))) {
            global $leadpagesApp;
            $pages = $leadpagesApp['pagesApi']->getAllUserPages();
            set_transient('user_leadpages', $pages, 120);
        }


        return $pages;
    }

    protected function clearPagesCache()
    {
        delete_transient('user_leadpages');
        return $this;
    }

    //replace with get_permalink
    public function leadpages_permalink()
    {
        global $post;
        $permalink = home_url() .'/';
        if ($post->post_status != 'publish') {
            $permalink = 'Publish to see full url';
        }
        $permalink = str_replace('/leadpages_post/', '', $permalink);
        $permalink = str_replace('/'.$post->post_name.'/', '/', $permalink);
        return $permalink;
    }

}
