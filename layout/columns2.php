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
 * A two column layout for the boost theme.
 *
 * @package   theme_boost
 * @copyright 2016 Damyon Wiese
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

function dump(...$params) { echo '<pre>'; var_dump(func_get_args()); echo '</pre>'; }
function dumpd(...$params) { echo '<pre>'; var_dump(func_get_args()); echo '</pre>'; }

user_preference_allow_ajax_update('drawer-open-nav', PARAM_ALPHA);
require_once($CFG->libdir . '/behat/lib.php');
require_once($CFG->dirroot.'/calendar/lib.php');

global $PAGE;

if (isloggedin()) {
    $navdraweropen = (get_user_preferences('drawer-open-nav', 'true') == 'true');
} else {
    $navdraweropen = false;
}
$extraclasses = [];
if ($navdraweropen) {
    $extraclasses[] = 'drawer-open-left';
}
$bodyattributes = $OUTPUT->body_attributes($extraclasses);
$blockshtml = $OUTPUT->blocks('side-pre');
$hasblocks = strpos($blockshtml, 'data-block=') !== false;
$regionmainsettingsmenu = $OUTPUT->region_main_settings_menu();
$templatecontext = [
    'sitename' => format_string($SITE->shortname, true, ['context' => context_course::instance(SITEID), "escape" => false]),
    'output' => $OUTPUT,
    'sidepreblocks' => $blockshtml,
    'hasblocks' => $hasblocks,
    'bodyattributes' => $bodyattributes,
    'navdraweropen' => $navdraweropen,
    'regionmainsettingsmenu' => $regionmainsettingsmenu,
    'hasregionmainsettingsmenu' => !empty($regionmainsettingsmenu)
];

//dumpd($calendar);
//$PAGE->navbar->add($strcalendar, $viewcalendarurl);
//$event = $PAGE->event->get("event");

//dump($PAGE->flatnav->get_key_list());


if ($PAGE->pagelayout == "course") {
    $PAGE->flatnav->remove("sitesettings");
    $PAGE->flatnav->remove("home");
    $PAGE->flatnav->remove("participants");
    $PAGE->flatnav->remove("badgesview");
    $PAGE->flatnav->remove("competencies");
    $PAGE->flatnav->remove("privatefiles");
    $PAGE->flatnav->remove("calendar");
    dump($PAGE->flatnav->get_key_list());
    foreach ($PAGE->flatnav as $child_key) {
        dumpd($child_key);
        // dumpd($child_key->key, 
        //       $child_key->text, 
        //       $child_key->title, 
        //       $child_key->action, 
        //       $child_key->icon, 
        //       $child_key->type, 
        //       $child_key->nodetype, 
        //       $child_key->children, 
        //       $child_key->isactive, 
        //       $child_key->hidden, 
        //       $child_key->display
        //     );
    }
    die();
    $templatecontext['flatnavigation'] = $PAGE->flatnav;
} else {
    // $PAGE->flatnav->remove("privatefiles");
    // $PAGE->flatnav->remove("sitesettings");
    // if (!empty($PAGE->flatnav->get('mycourses'))) {
    //    foreach ($PAGE->flatnav->get('mycourses')->get_children_key_list() as $child_key) {
    //        $PAGE->flatnav->remove($child_key);
    //    }
    // }
    // $PAGE->flatnav->remove("mycourses");
}

//dumpd($PAGE->flatnav);
//dumpd($PAGE->flatnav->get_key_list());

function get_nosso_calendario() {
    global $CFG, $COURSE;
    $calendar = \calendar_information::create(time(), $COURSE->id, $COURSE->category);
    list($data, $template) = calendar_get_view($calendar, 'upcoming_mini');
    $result = [];
    foreach ($data->events as $key => $value) {
        $shortdate = date('d M', $value->timestart);
        if (!array_key_exists($shortdate, $result)) {
            $result[$shortdate] = new stdClass();
            $result[$shortdate]->shortdate = $shortdate;
            $result[$shortdate]->viewurl = $value->viewurl;
            $result[$shortdate]->events = [];
        }
        $result[$shortdate]->events[] = $value;
    }
    return new ArrayIterator($result);
}


$templatecontext['in_course_page'] = $PAGE->pagelayout == "course";
$templatecontext['nosso_calendario'] = get_nosso_calendario();
//dumpd($templatecontext['nosso_calendario']);

echo $OUTPUT->render_from_template('theme_boost_eadifrn/columns2', $templatecontext);
