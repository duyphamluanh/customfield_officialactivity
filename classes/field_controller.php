<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

namespace customfield_officialactivity;

use coding_exception;

/**
 * Class field
 *
 * @package customfield_officialactivity
 * @copyright 2018 David Matamoros <davidmc@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class field_controller extends \core_customfield\field_controller {
    /**
     * Customfield type
     */
    const TYPE = 'officialactivity';

    /**
     * Add fields for editing a officialactivity field.
     *
     * @param \MoodleQuickForm $mform
     */
    public function config_form_definition(\MoodleQuickForm $mform) {
    }

    /**
     * @deprecated since Moodle 3.10 - MDL-68569 please use $field->get_options
     */
    public static function get_options_array(): void {
        throw new coding_exception('get_options_array() is deprecated, please use $field->get_options() instead');
    }

    /**
     * Return configured field options
     *
     * @return array
     */
    public function get_options($context): array {

        $options = array();
        list($context, $course, $cm) = get_context_info_array($context->id);
        $modules = get_all_instances_in_course($cm->modname, $course);
        foreach($modules as $module) {
            if($module->coursemodule != $context->instanceid) {
                $coursemodule = get_coursemodule_from_instance($cm->modname, $module->id, $course->id);
                if($coursemodule->idnumber != "") {
                    $options[$coursemodule->idnumber] = $module->name;
                }
            }
        }
        return ['None']+  $options;
    }

    /**
     * Locate the value parameter in the field options array, and return it's index
     *
     * @param string $value
     * @return int
     */
    public function parse_value(string $value) {
        return (int) array_search($value, $this->get_options());
    }
}