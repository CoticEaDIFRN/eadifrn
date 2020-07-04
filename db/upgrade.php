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

/**
 * Plugin upgrade steps are defined here.
 *
 * @package     theme_ead
 * @category    upgrade
 * @copyright   2020 Kelson Meeiros <kelsoncm@gmail.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

function create_or_update_course_custom_field($shortname, $name, $categoryid, $datatype='text', $configdata='{"required":"0","uniquevalues":"0","checkbydefault":"0","locked":"0","visibility":"0"}') {
    global $DB;
    if ($DB->record_exists('customfield_field', ['shortname'=>$shortname])) {
        return;
    }
    $course_custom_field = (object)[
        'shortname' => $shortname,
        'name' => $name,
        'categoryid' => $categoryid,
        'datatype' => $datatype,
        'configdata' => $configdata,
        'timecreated'=>1593823353,
        'timemodified'=>1593823353
    ];
    return $DB->insert_record("customfield_field", $course_custom_field, true);
}

function xmldb_theme_ead_upgrade($oldversion) {
    global $DB;
    $category_fields = [
        'name' => 'EaD',
        'component'=>'core_course',
        'area'=>'course',
        'itemid'=>0,
        'contextid'=>1,
        'timecreated'=>1593823353,
        'timemodified'=>1593823353
    ];
    $categoria = $DB->get_record('customfield_category', $category_fields);
    if (empty($DB->get_record('customfield_category', $category_fields))) {
        $ultimo = $DB->get_record_sql('SELECT coalesce(max(sortorder), 0) + 1 as sortorder from {customfield_category}' .
                                      " WHERE component='core_course' AND area='course'");
        $category = (object)$category_fields;
        $category->sortorder = $ultimo->sortorder;
        $category->id = $DB->insert_record("customfield_category", $category);
    }

    create_or_update_course_custom_field('show_in_frontpage', 'Mostrar na vitrine de cursos?', $category->id, 'checkbox');
    create_or_update_course_custom_field('duration', 'Duração do curso', $category->id, 'text', '{"required":"1","uniquevalues":"0","defaultvalue":"*","displaysize":3,"maxlength":3,"ispassword":"0","link":"","locked":"0","visibility":"0"}');
    create_or_update_course_custom_field('featured', 'Em destaque na vitríne de cursos?', $category->id, 'checkbox');

    return true;
}
