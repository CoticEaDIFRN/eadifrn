<?php
/**
 * External Web Service Template
 * 
 * Trabalhando com serviços, JS e AJAX:
 *   Web services: https://docs.moodle.org/dev/Web_services
 *   Adding a web service to a plugin: https://docs.moodle.org/dev/Adding_a_web_service_to_a_plugin
 *   Templates: https://docs.moodle.org/dev/Templates
 *   External functions API: https://docs.moodle.org/dev/External_functions_API
 *   AJAX: https://docs.moodle.org/dev/AJAX
 * 
 * $course->get_course_overviewfiles()
 * @author     Sueldo Sales <sueldosales@gmail.com>
 * @author     Kelson C. Medeiros <kelsoncm@gmail.com>
 * @package    theme_ead
 * @copyright  2017 IFRN <https://ifrn.edu.br>
 * @license    MIT https://opensource.org/licenses/MIT
 * @link       https://github.com/CoticEaDIFRN/moodle_theme_ead
 */
require_once($CFG->libdir . "/externallib.php");

require_once(__DIR__ . "/datalib.php");

class theme_ead_external extends external_api {

    public static function lanes_parameters() {
        return new external_function_parameters([]);
    }

    public static function  lanes_returns() {
        return new external_single_structure([
            'home_url' => new external_value(PARAM_TEXT, 'Home url'),
            'categories' => new external_multiple_structure(
                new external_single_structure([
                    'description' => new external_value(PARAM_TEXT, 'Category name'),
                    'url' => new external_value(PARAM_TEXT, 'Category url'),
                    'courses' => new external_multiple_structure(
                        new external_single_structure([
                            'id' => new external_value(PARAM_INT, 'Course id'),
                            'title' => new external_value(PARAM_TEXT, 'Course title'),
                            'see' => new external_value(PARAM_TEXT, 'Course see details url'),
                            'enrol' => new external_value(PARAM_TEXT, 'Course enrol url'),
                            'duration' => new external_value(PARAM_TEXT, 'Course duration'),
                            'thumbnail' => new external_value(PARAM_TEXT, 'Course thumbnail'),
                        ],
                        'Course object'),
                    'Course list')
                ],
                'Category object'), 
                'Category list'
            ),
        ]);
    }


    // public static function lanes($welcomemessage = 'Hello world, ') {
    public static function lanes() {
        return ead_frontpage_lanes();
    }
}
