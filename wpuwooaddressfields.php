<?php
/*
Plugin Name: WPU Woo Address Fields
Plugin URI: http://github.com/Darklg/WPUtilities
Description: Quickly add fields to WooCommerce addresses : handle display & save
Version: 0.1.0
Author: Darklg
Author URI: http://darklg.me/
License: MIT License
License URI: http://opensource.org/licenses/MIT
*/

class WPUWooAddressFields {
    private $fields = array();

    public function __construct() {
        add_action('plugins_loaded', array(&$this, 'plugins_loaded'));
        add_filter('woocommerce_default_address_fields', array(&$this, 'add_default_address_fields'));
    }

    public function plugins_loaded() {
        $this->fields = $this->set_fields(apply_filters('wpuwooaddressfields_fields', array()));
    }

    public function set_fields($fields) {
        foreach ($fields as $id => &$field) {
            if (!isset($field['label'])) {
                $field['label'] = $id;
            }
            if (!isset($field['required'])) {
                $field['required'] = false;
            }
            if (!isset($field['class'])) {
                $field['class'] = array('form-row-wide');
            }
            if (!isset($field['type'])) {
                $field['type'] = 'text';
            }
            if (!isset($field['placeholder'])) {
                $field['placeholder'] = 'text';
            }
        }

        return $fields;
    }

    public function add_default_address_fields($address_fields) {
        foreach ($this->fields as $id => $field) {
            if (isset($field['add_top'])) {
                /* Insert at the top */
                unset($field['add_top']);
                $address_fields = array($id => $field) + $address_fields;
            } elseif (isset($field['remove'])) {
                /* Remove field */
                if (isset($address_fields[$id])) {
                    unset($address_fields[$id]);
                }
            } else {
                /* Insert at the end */
                $address_fields[$id] = $field;
            }
        }
        return $address_fields;
    }

}

$WPUWooAddressFields = new WPUWooAddressFields();
