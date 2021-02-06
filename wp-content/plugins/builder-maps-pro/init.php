<?php

/*
Plugin Name:  Builder Maps Pro
Plugin URI:   https://themify.me/addons/maps-pro
Version:      2.0.1
Author:       Themify
Author URI:   https://themify.me
Description:  Maps Pro module allows you to insert Google Maps with multiple location markers with custom icons, tooltip text, and various map styles. It requires to use with the latest version of any Themify theme or the Themify Builder plugin.
Text Domain:  builder-maps-pro
Domain Path:  /languages
Compatibility: 5.0.0
*/

defined('ABSPATH') or die('-1');

class Builder_Maps_Pro {

    public $url;
    private $dir;
    public $version;

    /**
     * Creates or returns an instance of this class.
     *
     * @return	A single instance of this class.
     */
    public static function get_instance() {
        static $instance = null;
        if ($instance === null) {
            $instance = new self;
        }
        return $instance;
    }

    private function __construct() {
        $this->constants();
        $this->i18n();
        add_action('themify_builder_setup_modules', array($this, 'register_module'));
        if (is_admin()) {
	    add_filter( 'plugin_row_meta', array( $this, 'themify_plugin_meta'), 10, 2 );
	    add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array( $this, 'action_links') );
            add_action('themify_builder_admin_enqueue', array($this, 'admin_enqueue'));
        } else {
            add_action('themify_builder_frontend_enqueue', array($this, 'admin_enqueue'), 15);
        }
		include $this->dir . 'providers/base.php';
    }

    public function constants() {
        $data = get_file_data(__FILE__, array('Version'));
        $this->version = $data[0];
        $this->url = trailingslashit(plugin_dir_url(__FILE__));
        $this->dir = trailingslashit(plugin_dir_path(__FILE__));
    }

	public function themify_plugin_meta( $links, $file ) {
		if ( plugin_basename( __FILE__ ) === $file ) {
			$row_meta = array(
			  'changelogs'    => '<a href="' . esc_url( 'https://themify.me/changelogs/' ) . basename( dirname( $file ) ) .'.txt" target="_blank" aria-label="' . esc_attr__( 'Plugin Changelogs', 'themify' ) . '">' . esc_html__( 'View Changelogs', 'themify' ) . '</a>'
			);
	 
			return array_merge( $links, $row_meta );
		}
		return (array) $links;
	}
	public function action_links( $links ) {
		if ( is_plugin_active( 'themify-updater/themify-updater.php' ) ) {
			$tlinks = array(
			 '<a href="' . admin_url( 'index.php?page=themify-license' ) . '">'.__('Themify License', 'themify') .'</a>',
			 );
		} else {
			$tlinks = array(
			 '<a href="' . esc_url('https://themify.me/docs/themify-updater-documentation') . '">'. __('Themify Updater', 'themify') .'</a>',
			 );
		}
		return array_merge( $links, $tlinks );
	}
    public function i18n() {
        load_plugin_textdomain('builder-maps-pro', false, '/languages');
    }

    private function localization() {
        $map_styles = array();
        foreach ($this->get_map_styles() as $key => $value) {
            $name = str_replace('.json', '', $key);
            $map_styles[$name] = $this->get_map_style($name);
        }
        return array(
            'key' => Themify_Builder_Model::getMapKey(),
            'styles' => $map_styles,
	    'admin_css'=>themify_enque($this->url . 'assets/admin.css'),
	    'v'=>$this->version
	);
    }

    public function admin_enqueue() {
        wp_enqueue_script('themify-builder-maps-pro-admin', themify_enque($this->url . 'assets/admin.js'), array('themify-builder-app-js'), $this->version, true);
        wp_localize_script('themify-builder-maps-pro-admin', 'builderMapsPro',$this->localization());
    }


    public function register_module() {
	Themify_Builder_Model::register_directory('templates', $this->dir . 'templates');
	Themify_Builder_Model::register_directory('modules', $this->dir . 'modules');
    }

    public function get_map_styles() {
        $dir = get_stylesheet_directory() . '/builder-maps-pro/styles/';
        $theme_styles = is_dir($dir) ? self::list_dir($dir) : array();

        return array_merge(self::list_dir($this->dir . 'styles/'), $theme_styles);
    }

    private static function list_dir($path) {
        $dh = opendir($path);
        $files = array();
        while (false !== ( $filename = readdir($dh) )) {
            if ($filename !== '.' && $filename !== '..' && '.json'===substr($filename,-5,5)) {
                $files[$filename] = $filename;
            }
        }

        return $files;
    }

    public function get_map_style($name) {
        $file = get_stylesheet_directory() . '/builder-maps-pro/styles/' . $name . '.json';
        if(!file_exists($file)){
            $file =  $this->dir . 'styles/' . $name . '.json';
            if (!file_exists($file)) {
                return '';
            }
        }
        ob_start();
        include $file;
        return json_decode(ob_get_clean());
    }

}
Builder_Maps_Pro::get_instance();
