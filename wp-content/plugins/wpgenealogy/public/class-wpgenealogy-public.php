<?php
namespace WPGenealogy_Public;

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://bitbucket.org/blackandwhitedigital
 * @since      1.0.0
 *
 * @package    WPGenealogy
 * @subpackage WPGenealogy/public
 */
/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    WPGenealogy
 * @subpackage WPGenealogy/public
 * @author     Black and White Digital <paul@blackandwhitedigital.com>
 */
class WPGenealogy_Public
{
    use \WPGenealogy_Public\Traits\Tree;
    use \WPGenealogy_Public\Traits\Branch;
    use \WPGenealogy_Public\Traits\Chart;
    use \WPGenealogy_Public\Traits\People;
    use \WPGenealogy_Public\Traits\Family;
    use \WPGenealogy_Public\Traits\Children;
    use \WPGenealogy_Public\Traits\Event;
    use \WPGenealogy_Public\Traits\Address;
    use \WPGenealogy_Public\Traits\EventType;
    use \WPGenealogy_Public\Traits\Place;
    use \WPGenealogy_Public\Traits\Citation;
    use \WPGenealogy_Public\Traits\Note;
    use \WPGenealogy_Public\Traits\NoteLink;
    use \WPGenealogy_Public\Traits\Source;
    use \WPGenealogy_Public\Traits\FamilyTree;
    use \WPGenealogy_Public\Traits\User;
    use \WPGenealogy_Public\Traits\Association;
    use \WPGenealogy_Public\Traits\Others;
    use \WPGenealogy_Public\Traits\TempEvent;
    use \WPGenealogy_Public\Traits\Timeline;
    use \WPGenealogy_Public\Traits\Report;
    use \WPGenealogy_Public\Traits\Setting;
    use \WPGenealogy_Public\Traits\Shortcode;
    use \WPGenealogy_Public\Traits\Pdf;
    use \WPGenealogy_Public\Traits\Import;
    use \WPGenealogy_Public\Traits\Export;
    use \WPGenealogy_Public\Traits\Todo;
    use \WPGenealogy_Public\Traits\Relationship;
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;
    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;
    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {
        if (WP_DEBUG && WP_DEBUG_LOG) {
            //sleep(2);
        }
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }
    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in WPGenealogy_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The WPGenealogy_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        if (file_exists(plugin_dir_path(__FILE__) . 'css/wpgenealogy-public-vendor.css')) {
            wp_enqueue_style($this->plugin_name . '-vendor', plugin_dir_url(__FILE__) . 'css/wpgenealogy-public-vendor.css', array(), $this->version, 'all');
            wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/wpgenealogy-public.css', array($this->plugin_name . '-vendor'), $this->version, 'all');
        } else {
            wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/wpgenealogy-public.css', array(), $this->version, 'all');
        }
        wp_enqueue_style($this->plugin_name . '-chart', plugin_dir_url(__FILE__) . 'css/wpgenealogy-public-chart.css', array(), $this->version, 'all');
    }
    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in WPGenealogy_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The WPGenealogy_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_script($this->plugin_name . '-timeline', plugin_dir_url(__FILE__) . 'js/timeline_js/timeline-api.js', array('jquery'), $this->version, true);
        if (file_exists(plugin_dir_path(__FILE__) . 'js/wpgenealogy-public-vendor.js')) {
            wp_enqueue_script($this->plugin_name . '-vendor', plugin_dir_url(__FILE__) . 'js/wpgenealogy-public-vendor.js', array('jquery'), $this->version, true);
            wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/wpgenealogy-public.js', array('jquery', $this->plugin_name . '-vendor', $this->plugin_name . '-timeline'), $this->version, true);
        } else {
            wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/wpgenealogy-public.js', array('jquery', $this->plugin_name . '-timeline'), $this->version, true);
        }
        wp_enqueue_script($this->plugin_name . '-panzoom', plugin_dir_url(__FILE__) . 'js/panzoom.min.js', array('jquery'), $this->version, true);
        wp_enqueue_script($this->plugin_name . '-flipclock', plugin_dir_url(__FILE__) . 'js/flipclock.min.js', array('jquery'), $this->version, true);

        $wpgenealogy_settings = get_option('wpgenealogy_settings');
        $wpgenealogy_page_id = $wpgenealogy_settings['configuration']['general']['pages']['wpgenealogy_page_id'];
        $user = wp_get_current_user();
        $user_meta = get_user_meta($user->ID);
        if ($user_meta) {
            foreach ($user_meta as $key => $value) {
                $user_meta[$key] = current($value);
            }
        }
        $user->meta = $user_meta;
        $avatar = 'http://gravatar.com/avatar/' . md5(0);
        if ($user->data && isset($user->data->user_email)) {
            $avatar = 'http://gravatar.com/avatar/' . md5($user->data->user_email);
        }
        $user->avatar = $avatar;
        $setting = get_option('wpgenealogy_settings');
        $base = $this->setting();
        $settings = $this->merge_setting($base, $setting);
        $pages = get_posts(array('post_type' => 'page', 'posts_per_page' => -1));
        $ids = array();
        foreach ($pages as $key => $value) {
            array_push($ids, array(
                'ID' => $value->ID,
                'post_title' => $value->post_title,
                'url' => get_the_permalink($value->ID),
            ));
        }
        $addons = array();
        if (class_exists('WPGenealogy_Lds')) {
            array_push($addons, 'WPGenealogy_Lds');
        }
        $settings['configuration']['general']['pages']['all'] = $ids;

        $timeline = \WPGenealogy\Models\Timeline::orderBy('evyear')->get()->first();

        $wpgenealogy_local = array(
            'site_url' => site_url(),
            'wp_logout_url' => wp_logout_url(),
            'wp_login_url' => wp_login_url(),
            'plugin_dir_url' => plugin_dir_url(dirname(plugin_basename(__FILE__))),
            'wp_registration_url' => wp_registration_url(),
            'dashboard_page' => get_the_permalink($wpgenealogy_page_id),
            'admin_url' => admin_url(),
            'ajax_url' => admin_url('admin-ajax.php'),
            'get_rest_url' => get_rest_url(),
            'nonce' => wp_create_nonce('wpgenealogy-ajax-nonce'),
            'user' => $user,
            'setting' => $settings,
            'addons' => $addons,
            'Timeline_ajax_url' => plugin_dir_url(dirname(plugin_basename(__FILE__))) . 'public/js/timeline_ajax/simile-ajax-api.js',
            'Timeline_urlPrefix' => plugin_dir_url(dirname(plugin_basename(__FILE__))) . 'public/js/timeline_js/',
            'Timeline_parameters' => 'bundle=true',
            'tlstartdate2' => $timeline ? $timeline->evyear : '1970',
        );
        if (wpg_fs()->is_trial() || wpg_fs()->is_plan('premium') || !wpg_fs()->is_not_paying()) {
            $wpgenealogy_local['plan'] = 'premium';
        } else {
            $wpgenealogy_local['plan'] = 'free';
        }
        wp_localize_script($this->plugin_name . '-timeline', 'wpGenealogy', $wpgenealogy_local);
    }
    /**
     * wpgenealogy main shortcode for frontend dashboard.
     *
     * @since    3.0.0
     */
    public function wpgenealogy_shortcode($atts, $content = null)
    {
        $attr = shortcode_atts(array(
        ), $atts);
        $content .= '<div id="treeprerss" class="treeprerss"></div>';
        return $content;
    }
    /**
     * wpgenealogy function to import ged file.
     *
     * @since    3.0.0
     */
    public function generate()
    {
        $type = sanitize_text_field($_POST['type']);
        switch ($type) {
            case 'personID':
                $id = \WPGenealogy\Models\People::get()->last()->id;
                $id = $id + 1;
                $id = 'I' . $id;
                break;
            case 'familyID':
                $id = \WPGenealogy\Models\Family::get()->last()->id;
                $id = $id + 1;
                $id = 'F' . $id;
                break;
            case 'sourceID':
                $id = \WPGenealogy\Models\Source::get()->last()->id;
                $id = $id + 1;
                $id = 'S' . $id;
                break;
            case 'repoID':
                $id = \WPGenealogy\Models\Repo::get()->last()->id;
                $id = $id + 1;
                $id = 'R' . $id;
                break;
        }
        wp_send_json($id, 200);

    }
    /**
     * wpgenealogy function to import ged file.
     *
     * @since    3.0.0
     */
    public function check()
    {
        $type = sanitize_text_field($_POST['type']);
        switch ($type) {
            case 'personID':
                $personID = sanitize_text_field($_POST['personID']);
                $id = \WPGenealogy\Models\People::where('personID', $personID)->first()->id;
                break;
            case 'familyID':
                $familyID = sanitize_text_field($_POST['familyID']);
                $id = \WPGenealogy\Models\Family::where('familyID', $familyID)->first()->id;
                break;
            case 'sourceID':
                $sourceID = sanitize_text_field($_POST['sourceID']);
                $id = \WPGenealogy\Models\Source::where('sourceID', $sourceID)->first()->id;
                break;
            case 'repoID':
                $id = \WPGenealogy\Models\Repo::get()->last()->id;
                break;
        }
        wp_send_json($id, 200);
    }
    /**
     * wpgenealogy function to import ged file.
     *
     * @since    3.0.0
     */
    public function lock()
    {
        $data = $this->post_fixer($_POST);
        $type = sanitize_text_field($data['type']);
        $send_data = [];
        switch ($type) {
            case 'personID':
                $personID = $data['personID'];
                $people = new \WPGenealogy\Models\People;
                $people = $people->create($data);
                $send_data['people'] = $people;
                break;
            case 'familyID':
                $familyID = $data['familyID'];
                $family = new \WPGenealogy\Models\Family;
                $family = $family->create($data);
                $send_data['family'] = $family;
                break;
            case 'sourceID':
                $familyID = $data['sourceID'];
                $family = new \WPGenealogy\Models\Source;
                $family = $family->create($data);
                $send_data['source'] = $family;
                break;
            case 'repoID':
                break;
        }
        wp_send_json($send_data, 200);
    }

    /**
     * wpgenealogy function to fix post data.
     *
     * @since    3.0.0
     */
    public function post_fixer($data)
    {
        /**
         * Check if data exist and is array is it.
         *
         */
        if (!$data || !is_array($data)) {
            return $data;
        }
        /**
         * Unset action
         *
         */
        if (isset($data['action']) && $data['action']) {
            unset($data['action']);
        }
        /**
         * Unset security
         *
         */
        if (isset($data['security']) && $data['security']) {
            unset($data['security']);
        }
        /**
         * Fix each key where value is true or false
         *
         */
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = $this->post_fixer($value);
            } else {
                if ($data[$key] == 'false' || !$value) {
                    $data[$key] = null;
                } elseif ($data[$key] == 'true') {
                    $data[$key] = 1;
                } /*elseif(strpos($data[$key], 'url') !== false) {
                $data[$key] = sanitize_text_field($value);
                }*/else {
                    $data[$key] = sanitize_text_field($value);
                }
                if (strpos($key, 'date') !== false) {
                    $data[$key] = $this->removeSpace(sanitize_text_field($data[$key]));
                    //print_r(sanitize_text_field('0000-00-00'));
                }
                if (strpos($key, 'datetr') !== false) {
                    $data[$key] = $this->convertDate(sanitize_text_field($data[str_replace('datetr', 'date', $key)]));
                }
            }
        }

        /**
         * return data
         *
         */
        return $data;
    }

    public function removeSpace($date)
    {

        return preg_replace('/\s+/', ' ', $date);
    }
    /**
     * wpgenealogy function to ged all data of a model.
     *
     * @since    3.0.0
     */
    public function get($model)
    {
        if (wpg_fs()->is_trial() || wpg_fs()->is_plan('premium') || !wpg_fs()->is_not_paying()) {
            /**
             * Get all model data.
             *
             */
            $model = $model::where('created_by', wp_get_current_user()->ID)->get();
        } else {
            /**
             * Get all model data.
             *
             */
            $model = $model::where('created_by', wp_get_current_user()->ID)->take(200)->get();
        }
        /**
         * Return response to frontend.
         *
         */
        wp_send_json($model, 200);
    }
    /**
     * wpgenealogy function to check exist.
     *
     * @since    3.0.0
     */
    public function exist($model, $keyValue, $get = false)
    {
        /**
         * Get model name.
         *
         */
        $modelName = strtolower(class_basename($model));
        /**
         * create query instance.
         *
         */
        $query = $model::query();
        foreach ($keyValue as $key => $value) {
            /**
             * prepare query according input.
             *
             */
            $query->when([$key, $value], function ($q, $data) {
                return $q->where($data[0], $data[1])->get();
            });
        }
        /**
         * get query result.
         *
         */
        $model = $query->first();

        /**
         * Create data to response.
         *
         */
        if ($model) {
            $data = array(
                'messages' => array(
                    array(
                        'type' => 'alert-info',
                        'text' => ucfirst($modelName) . ' already exist.',
                    ),
                ),
            );
        }
        /**
         * Create data to response.
         *
         */
        if (!$model) {
            $data = false;
        }
        /**
         * Return response to frontend.
         *
         */
        return $data;
    }
    /**
     * wpgenealogy function to add model
     *
     * @since    3.0.0
     */
    public function add($model, $data, $get = false)
    {
        if (wpg_fs()->is_trial() || wpg_fs()->is_plan('premium') || !wpg_fs()->is_not_paying()) {

        } else {
            if ($model == 'WPGenealogy\Models\Tree' || $model == 'WPGenealogy\Models\Chart' || $model == 'WPGenealogy\Models\Branch') {
                $modelCount = $model::all()->count();
                if ($modelCount >= 1) {
                    $data = array(
                        'messages' => array(
                            array(
                                'type' => 'alert-success',
                                'text' => 'Your free limit is over. Please upgrade to premium version to add more tree.',
                            ),
                        ),
                    );
                    return $this->send_json($data);
                }
            }
        }

        $get_user = wp_get_current_user();


        /**
         * Get model name.
         *
         */
        $modelName = strtolower(class_basename($model));
        /**
         * Create model instance.
         *
         */
        $model = new $model;
        /**
         * make post data useable.
         *
         */
        $data = $this->post_fixer($data);
        /**
         * set current user to post
         *
         */
        $data['created_by'] = wp_get_current_user()->ID;

       // print_r( $data);

        /**
         * make post
         *
         */
        $model = $model->create($data);
        /**
         * Create data to response.
         *
         */
        $data = (object)array(
            $modelName => $model,
            'messages' => array(
                array(
                    'type' => 'alert-success',
                    'text' => ucfirst($modelName) . ' has been added successfully.',
                ),
            ),
        );
        /**
         * Sent data to callback function.
         *
         */
        if ($get == true) {
            return $data;
        }
        /**
         * Return response to frontend.
         *
         */
        return $this->send_json($data);

    }
    /**
     * wpgenealogy function to update model.
     *
     * @since    3.0.0
     */
    public function error_with_code($code)
    {
        return array(
            'messages' => array(
                array(
                    'type' => 'alert-danger',
                    'text' => $code . ' : You have not enough permission',
                ),
            ),
        );
    }
    /**
     * wpgenealogy function to update model.
     *
     * @since    3.0.0
     */
    public function update($model, $data, $keyValue, $get = false)
    {
        $get_user = wp_get_current_user();
        $tp_level = get_user_meta($get_user->ID, 'administrator', true);
        if (current_user_can('guest')) {
            wp_send_json($this->error_with_code(1), 200);
        }
        if (current_user_can('subm')) {
            wp_send_json($this->error_with_code(2), 200);
        }
        if (current_user_can('contrib')) {
            wp_send_json($this->error_with_code(3), 200);
        }
        if (current_user_can('mcontrib')) {
            wp_send_json($this->error_with_code(4), 200);
        }
        if (current_user_can('meditor')) {
            wp_send_json($this->error_with_code(5), 200);
        }
        if (current_user_can('custom')) {
            if (!current_user_can('allow_edit_data')) {
                wp_send_json($this->error_with_code(6), 200);
            }
            if (current_user_can('allow_edit_data')) {
                if ($tp_level == 'tp_level_0') {
                    if (get_user_meta($get_user->ID, 'gedcom', true) != $data['gedcom'] && get_user_meta($get_user->ID, 'branch', true) != $data['branch']) {
                        wp_send_json($this->error_with_code(7), 200);
                    }
                }
                if ($tp_level == 'tp_level_2') {
                    $gedcom_mult = get_user_meta($get_user->ID, 'gedcom_mult', true) ? get_user_meta($get_user->ID, 'gedcom_mult', true) : [];
                    if (!in_array($data['gedcom'], $gedcom_mult)) {
                        wp_send_json($this->error_with_code(8), 200);
                    }
                }
            }
        }
        if (current_user_can('editor')) {
        }
        if (current_user_can('administrator')) {
        }
        if ($tp_level == 'tp_level_1') {
            // all tree , check capability now
        }
        //
        if ($tp_level == 'tp_level_0') {
            // selected tree and branch , check capability now
        }
        if ($tp_level == 'tp_level_2') {
            // selected multiple tree  , check capability now
        }
        /**
         * Get model name.
         */
        $modelName = strtolower(class_basename($model));
        /**
         * create query instance.
         *
         */
        $query = $model::query();
        foreach ($keyValue as $key => $value) {
            /**
             * prepare query according input.
             *
             */
            $query->when([$key, $value], function ($q, $data) {
                return $q->where($data[0], $data[1])->get();
            });
        }
        /**
         * get query result.
         *
         */
        $model = $query->first();
        /**
         * make post data useable.
         *
         */
        $data = $this->post_fixer($data);
        /**
         * Update model.
         *
         */
        $model = $model->update($data);
        /**
         * Create data to response.
         *
         */
        $data = array(
            $modelName => $model,
            'messages' => array(
                array(
                    'type' => 'alert-success',
                    'text' => ucfirst($modelName) . ' has been updated successfully.',
                ),
            ),
        );
        /**
         * Sent data to callback function.
         *
         */
        if ($get == true) {
            return $data;
        }
        /**
         * Return response to frontend.
         *
         */
        return $this->send_json($data);
    }
    /**
     * wpgenealogy function to return response.
     *
     * @since    3.0.0
     */
    public function send_json($data = array())
    {
        /**
         * Return response to frontend.
         */
        wp_send_json($data, 200);
    }
    /**
     * wpgenealogy function to delete model / models.
     *
     * @since    3.0.0
     */
    public function delete($model, $ids)
    {
        $get_user = wp_get_current_user();
        /**
         * check if ids exist and type of ids.
         *
         */
        if (!$ids || !is_array($ids)) {
            return;
        }
        /**
         * Delete each model through id.
         *
         */
        foreach ($ids as $key => $id) {
            $model = $model::where('id', $id)->first();
            if ($model) {
                $model->delete();
            }
            global $wpdb;
            $wpdb_prefix = $wpdb->prefix;

            if ($model->getModel()->getTable() == $wpdb_prefix . 'tp_peoples') {
                $childrens = \WPGenealogy\Models\Children::where('gedcom', $model->gedcom)->where('personID', $model->personID)->get();
                foreach ($childrens as $key => $children) {
                   $children->delete();
                }
                $families = \WPGenealogy\Models\Family::where('gedcom', $model->gedcom)->where('husband', $model->personID)->get();
                foreach ($families as $key => $familiy) {
                   $familiy->husband = NULL;
                   $familiy->save();
                }

            }

            if ($model->getModel()->getTable() == $wpdb_prefix . 'tp_trees') {
                $branches = \WPGenealogy\Models\Branch::where('gedcom', $model->gedcom)->get();
                foreach ($branches as $key => $branch) {
                   $branch->delete();
                }
                $peoples = \WPGenealogy\Models\People::where('gedcom', $model->gedcom)->get();
                foreach ($peoples as $key => $people) {
                   $people->delete();
                }
                $families = \WPGenealogy\Models\Family::where('gedcom', $model->gedcom)->get();
                foreach ($families as $key => $familiy) {
                   $familiy->delete();
                }
                $childrens = \WPGenealogy\Models\Children::where('gedcom', $model->gedcom)->get();
                foreach ($childrens as $key => $children) {
                   $children->delete();
                }
                $events = \WPGenealogy\Models\Event::where('gedcom', $model->gedcom)->get();
                foreach ($events as $key => $event) {
                   $event->delete();
                }
                $places = \WPGenealogy\Models\Place::where('gedcom', $model->gedcom)->get();
                foreach ($places as $key => $place) {
                   $place->delete();
                }
                $notes = \WPGenealogy\Models\Note::where('gedcom', $model->gedcom)->get();
                foreach ($notes as $key => $note) {
                   $note->delete();
                }
                $charts = \WPGenealogy\Models\Chart::where('gedcom', $model->gedcom)->get();
                foreach ($charts as $key => $chart) {
                   $chart->delete();
                }
                $citations = \WPGenealogy\Models\Citation::where('gedcom', $model->gedcom)->get();
                foreach ($citations as $key => $citation) {
                   $citation->delete();
                }
                $note_links = \WPGenealogy\Models\NoteLink::where('gedcom', $model->gedcom)->get();
                foreach ($note_links as $key => $note_link) {
                   $note_link->delete();
                }
                $sources = \WPGenealogy\Models\Source::where('gedcom', $model->gedcom)->get();
                foreach ($sources as $key => $source) {
                   $source->delete();
                }

            }


        }
        /**
         * Send response to frontend.
         *
         */
        wp_send_json(array(
            'status' => 'OK',
            'messages' => array(
                array(
                    'type' => 'alert-info',
                    'text' => count($ids) . ' ' . self::pluralize(count($ids), 'item') . ' has been deleted successfully.',
                ),
            ),
        ));
    }
    /**
     * wpgenealogy function to make pluralize a word.
     *
     * @since    3.0.0
     */
    public static function pluralize($quantity, $singular, $plural = null)
    {
        /**
         * Check item quantity. If quentity is 1, then return input as plural.
         *
         */
        if ($quantity == 1 || !strlen($singular)) {
            return $singular;
        }
        /**
         * Check if plural in inputed default, return that as plural.
         *
         */
        if ($plural !== null) {
            return $plural;
        }
        /**
         * Check the last later.
         *
         */
        $last_letter = strtolower($singular[strlen($singular) - 1]);
        /**
         * Return plural based on last letter.
         *
         */
        switch ($last_letter) {
            /**
                 * if last letter is y, remove ya and add ies.
                 *
                 */
            case 'y':
                return substr($singular, 0, -1) . 'ies';
            /**
                 * if last letter is s, add es.
                 *
                 */
            case 's':
                return $singular . 'es';
            /**
                 * otherwise add s for all.
                 *
                 */
            default:
                return $singular . 's';
        }
    }

    /**
     * wpgenealogy function to ged all data of a model.
     *
     * @since    3.0.0
     */
    public function query_fixer($query)
    {
        foreach ($query as $key => $value) {
            if (is_array($value)) {
                $query[$key] = $this->query_fixer($value);
            } else {
                $query[$key] = sanitize_text_field($value);
            }
        }
        return $query;
    }

    /**
     * wpgenealogy function to ged all data of a model.
     *
     * @since    3.0.0
     */
    public function get_alt($model, $post, $return = false)
    {

        global $wpdb;

        $wpdb_prefix = $wpdb->prefix;
        $model = $model::query();

        $perPage = isset($post['per_page']) ? sanitize_text_field($post['per_page']) : 10000;
        $columns = isset($post['columns']) ? sanitize_text_field($post['columns']) : ['*'];
        $pageName = isset($post['page_name']) ? sanitize_text_field($post['page_name']) : 'page';
        $page = isset($post['current_page']) ? sanitize_text_field($post['current_page']) : 1;
        $sort = isset($post['sort']) ? sanitize_text_field($post['sort']) : 'created_at';
        $order = isset($post['order']) ? sanitize_text_field($post['order']) : 'DESC';

        $query = isset($post['query']) ? $this->query_fixer($post['query']) : array();

        /**
         * Get all model data.
         *
         */
        if ($model->getModel()->getTable() != $wpdb_prefix . 'users') {
            $model->where('created_by', wp_get_current_user()->ID);
        }
        /**
         * Get all model data.
         *
         */
        if ($model->getModel()->getTable() == $wpdb_prefix . 'tp_todos') {
            $model->orWhere('for', wp_get_current_user()->ID);
        }
        /**
         * Get all model data.
         *
         */
        if ($model->getModel()->getTable() != $wpdb_prefix . 'tp_trees') {
            if (isset($query['gedcom']) && $query['gedcom']) {
                $model->where('gedcom', $query['gedcom']);
            }
        }
        /**
         * Get all model data.
         *
         */
        if ($model->getModel()->getTable() == $wpdb_prefix . 'tp_trees') {
            if (isset($query['gedcom']) && $query['gedcom']) {
                $model->where('gedcom', 'like', '%' . $query['gedcom'] . '%');
            }
        }
        /**
         * Get all model data.
         *
         */
        if ($model->getModel()->getTable() == $wpdb_prefix . 'tp_branches') {
            if (isset($query['branch']) && $query['branch']) {
                $model->where('branch', 'like', '%' . $query['branch'] . '%');
            }
        }
        /**
         * Get all model data.
         *
         */
        if ($model->getModel()->getTable() == $wpdb_prefix . 'tp_charts') {
            if (isset($query['branch']) && $query['branch']) {
                $model->where('branch', $query['branch']);
            }
        }
        /**
         * Get all model data.
         *
         */
        if ($model->getModel()->getTable() == $wpdb_prefix . 'tp_peoples') {
            if (isset($query['firstname']) && $query['firstname'] && $query['firstname']['value']) {
                if ($query['firstname']['operator'] == 'contains') {
                    $model->where('firstname', 'like', '%' . $query['firstname']['value'] . '%');
                } elseif ($query['firstname']['operator'] == 'startswith') {
                    $model->where('firstname', 'like', $query['firstname']['value'] . '%');
                } else {}
            }
            if (isset($query['lastname']) && $query['lastname'] && $query['lastname']['value']) {
                if ($query['lastname']['operator'] == 'contains') {
                    $model->where('lastname', 'like', '%' . $query['lastname']['value'] . '%');
                } elseif ($query['lastname']['operator'] == 'startswith') {
                    $model->where('lastname', 'like', $query['lastname']['value'] . '%');
                } else {}
            }
            if (isset($query['name']) && $query['name'] && $query['name']['value']) {
                if ($query['name']['operator'] == 'contains') {
                    $model->where(function ($q) use ($query) {
                        $q->where('firstname', 'LIKE', '%' . $query['name']['value'] . '%');
                        $q->orWhere('lastname', 'LIKE', '%' . $query['name']['value'] . '%');
                        $q->orWhereRaw("concat(firstname, ' ', lastname) like '%" . $query['name']['value'] . "%' ");
                    });

                } elseif ($query['name']['operator'] == 'startswith') {
                    $model->where(function ($q) use ($query) {
                        $q->where('firstname', 'LIKE', $query['name']['value'] . '%');
                        $q->orWhere('lastname', 'LIKE', $query['name']['value'] . '%');
                        $q->orWhereRaw("concat(firstname, ' ', lastname) like " . $query['name']['value'] . "%' ");
                    });

                } else {}

            }
            if (isset($query['noChildren']) && ($query['noChildren'] == 'true' || $query['noChildren'] == '1')) {
                $model->whereHas('childrens');
            }
            if (isset($query['living']) && ($query['living'] == 'true' || $query['living'] == '1')) {
                $model->whereNotNull('living');
            }
            if (isset($query['private']) && ($query['private'] == 'true' || $query['private'] == '1')) {
                $model->whereNotNull('private');
            }
        }
        /**
         * Get all model data.
         *
         */
        if ($model->getModel()->getTable() == $wpdb_prefix . 'tp_families') {
            if (isset($query['name']) && $query['name']) {
                if (!$query['spouseType']) {
                    $model->whereHas('get_husband', function ($q) use ($query) {
                        $q->where('firstname', 'like', '%' . $query['name'] . '%');
                        $q->orWhere('lastname', 'like', '%' . $query['name'] . '%');
                    });
                    $model->orWhereHas('get_wife', function ($q) use ($query) {
                        $q->where('firstname', 'like', '%' . $query['name'] . '%');
                        $q->orWhere('lastname', 'like', '%' . $query['name'] . '%');
                    });
                }
                if ($query['spouseType'] && $query['spouseType'] == 'husband') {
                    $model->whereHas('get_husband', function ($q) use ($query) {
                        $q->where('firstname', 'like', '%' . $query['name'] . '%');
                        $q->orWhere('lastname', 'like', '%' . $query['name'] . '%');
                    });
                }
                if ($query['spouseType'] && $query['spouseType'] == 'wife') {
                    $model->whereHas('get_wife', function ($q) use ($query) {
                        $q->where('firstname', 'like', '%' . $query['name'] . '%');
                        $q->orWhere('lastname', 'like', '%' . $query['name'] . '%');
                    });
                }
            }
            if (isset($query['living']) && ($query['living'] == 'true' || $query['living'] == '1')) {
                $model->whereNotNull('living');
            }
            if (isset($query['private']) && ($query['private'] == 'true' || $query['private'] == '1')) {
                $model->whereNotNull('private');
            }
        }
        /**
         * Get all model data.
         *
         */
        if ($model->getModel()->getTable() == $wpdb_prefix . 'tp_note_links') {
            if (isset($query['persfamID']) && $query['persfamID']) {
                $model->where('persfamID', $query['persfamID']);
            }
            if (isset($query['eventID']) && $query['eventID']) {
                $model->where('eventID', $query['eventID']);
            }
        }
        /**
         * Get all model data.
         *
         */
        if ($model->getModel()->getTable() == $wpdb_prefix . 'tp_citations') {
            if (isset($query['persfamID']) && $query['persfamID']) {
                $model->where('persfamID', $query['persfamID']);
            }
            if (isset($query['eventID']) && $query['eventID']) {
                $model->where('eventID', $query['eventID']);
            }
        }
        /**
         * Get all model data.
         *
         */
        if ($model->getModel()->getTable() == $wpdb_prefix . 'tp_associations') {
            if (isset($query['persfamID']) && $query['persfamID']) {
                $model->where('persfamID', $query['persfamID']);
            }
            if (isset($query['eventID']) && $query['eventID']) {
                $model->where('eventID', $query['eventID']);
            }
        }
        /**
         * Get all model data.
         *
         */
        if ($model->getModel()->getTable() == $wpdb_prefix . 'tp_sources') {
            if (isset($query['title']) && $query['title']) {
                if ($query['title']['value']) {
                    if ($query['title']['operator'] && $query['title']['operator'] == 'contains') {
                        $model->where('title', 'like', '%' . $query['title']['value'] . '%');
                    } else if ($query['title']['operator'] && $query['title']['operator'] == 'startswith') {
                        $model->where('title', 'like', $query['title']['value'] . '%');
                    } else {
                        $model->where('title', 'like', '%' . $query['title']['value'] . '%');
                    }
                }
            }
        }
        /**
         * Get all model data.
         *
         */
        if ($model->getModel()->getTable() == $wpdb_prefix . 'tp_places') {
            if (isset($query['place']) && $query['place']) {
                if ($query['place']['value']) {
                    if ($query['place']['operator'] && $query['place']['operator'] == 'contains') {
                        $model->where('place', 'like', '%' . $query['place']['value'] . '%');
                    } else if ($query['place']['operator'] && $query['place']['operator'] == 'startswith') {
                        $model->where('place', 'like', $query['place']['value'] . '%');
                    } else if ($query['place']['operator'] && $query['place']['operator'] == 'equals') {
                        $model->where('place', $query['place']['value']);
                    } else {
                        $model->where('place', 'like', '%' . $query['place']['value'] . '%');
                    }
                }
                if ($query['mll']) {
                    $model->whereNull('longitude');
                    $model->whereNull('latitude');
                }
                if ($query['noe']) {
                    $model->doesntHave('events');
                }
                if ($query['nol']) {
                    $model->whereNull('placelevel');
                }
            }
        }
        /**
         * Get all model data.
         *
         */
        if ($model->getModel()->getTable() == $wpdb_prefix . 'tp_temp_events') {
            if (isset($query['type']) && $query['type']) {
                $model->where('type', $query['type']);
            }
        }
        /**
         * Get all model data.
         *
         */

        if ($model->getModel()->getTable() == $wpdb_prefix . 'tp_families') {
            $model = $model->orderBy($sort, $order)->paginate($perPage, $columns, $pageName, $page);
        } else {
             $model = $model->orderBy($sort, $order)->paginate($perPage, $columns, $pageName, $page);
        }


        /**
         * Return response to frontend.
         *
         */

        if ($return) {
            return $model;
        }

        wp_send_json($model, 200);
    }

    /**
     * wpgenealogy function to get single row for model.
     *
     * @since    3.0.0
     */
    public function single($model, $post, $get = false)
    {

        $id = $post['id'] ? $post['id'] : ($post['ID'] ? $post['ID'] : '');
        $model = $model::find($id);
        if ($get) {
            return $model;
        }
        wp_send_json($model, 200);
    }

    /**
     * wpgenealogy function to get single row for model.
     *
     * @since    3.0.0
     */
    public function url_does_not_exist()
    {
        wp_send_json(
            array(
                'messages' => array(
                    array(
                        'type'=>'alert-info', 
                        'text'=>  'URL does not exist.'
                    )
                )
            ), 200
        );
    }

}
