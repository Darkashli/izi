<?php

defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('form_dropdown_disabled')) {

    /**
     * Drop-down Menu
     *
     * @param	mixed	$data
     * @param	mixed	$options
     * @param	mixed	$selected
     * @param	mixed	$extra
     * @return	string
     */
    function form_dropdown_disabled($data = '', $options = array(), $selected = array(), $extra = '', $disabled = array()) {
        $defaults = array();

        if (is_array($data)) {
            if (isset($data['selected'])) {
                $selected = $data['selected'];
                unset($data['selected']); // select tags don't have a selected attribute
            }

            if (isset($data['options'])) {
                $options = $data['options'];
                unset($data['options']); // select tags don't use an options attribute
            }
        } else {
            $defaults = array('name' => $data);
        }

        is_array($selected) OR $selected = array($selected);
        is_array($options) OR $options = array($options);

        // If no selected state was submitted we will attempt to set it automatically
        if (empty($selected)) {
            if (is_array($data)) {
                if (isset($data['name'], $_POST[$data['name']])) {
                    $selected = array($_POST[$data['name']]);
                }
            } elseif (isset($_POST[$data])) {
                $selected = array($_POST[$data]);
            }
        }

        $extra = _attributes_to_string($extra);

        $multiple = (count($selected) > 1 && stripos($extra, 'multiple') === FALSE) ? ' multiple="multiple"' : '';

        $form = '<select ' . rtrim(_parse_form_attributes($data, $defaults)) . $extra . $multiple . ">\n";

        foreach ($options as $key => $val) {
            $key = (string) $key;

            if (is_array($val)) {
                if (empty($val)) {
                    continue;
                }

                $form .= '<optgroup label="' . $key . "\">\n";

                foreach ($val as $optgroup_key => $optgroup_val) {
                    $sel = in_array($optgroup_key, $selected) ? ' selected="selected"' : '';
                    $dis = (in_array($optgroup_key, $disabled)) ? ' disabled="disabled"' : '';
                    $form .= '<option value="' . html_escape($optgroup_key) . '"' . $sel . $dis . '>'
                            . (string) $optgroup_val . "</option>\n";
                }

                $form .= "</optgroup>\n";
            } else {
                $dis = (in_array($key, $disabled)) ? ' disabled="disabled"' : '';
                $form .= '<option value="' . html_escape($key) . '"'
                        . (in_array($key, $selected) ? ' selected="selected"' : '') . $dis . '>'
                        . (string) $val . "</option>\n";
            }
        }

        return $form . "</select>\n";
    }

}