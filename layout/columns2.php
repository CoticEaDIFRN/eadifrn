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

user_preference_allow_ajax_update('drawer-open-nav', PARAM_ALPHA);
require_once($CFG->libdir . '/behat/lib.php');
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

$calendar = $PAGE->flatnav->get("calendar");
//$PAGE->navbar->add($strcalendar, $viewcalendarurl);
//$event = $PAGE->event->get("event");

//In Course
//  echo '<pre>'; var_dump($PAGE->flatnav->get_key_list());

$PAGE->flatnav->remove("sitesettings");
if ($PAGE->pagelayout == "course") {
  $PAGE->flatnav->remove("participants");
  $PAGE->flatnav->remove("badgesview");
  $PAGE->flatnav->remove("competencies");
  $PAGE->flatnav->remove("home");
  $PAGE->flatnav->remove("privatefiles");
//   var_dump(get_class_methods($PAGE->flatnav->get('mycourses')));
  foreach ($PAGE->flatnav as $key => $value) {
    //   var_dump($key);
      if ($key != 'grades') {
        //   var_dump($value);
      }
  }
} else {
    $PAGE->flatnav->remove("home");
    $PAGE->flatnav->remove("privatefiles");
    $PAGE->flatnav->remove("sitesettings");
    // var_dump($PAGE->flatnav->get('mycourses')->get_children()); 
    foreach ($PAGE->flatnav->get('mycourses')->get_children_key_list() as $child_key) {
        $PAGE->flatnav->remove($child_key);
    }
    $PAGE->flatnav->remove("mycourses");
}

//$PAGE->flatnav->get_children_key_list();
//var_dump($PAGE->flatnav);

//  var_dump($PAGE->flatnav->get_key_list()); die();

$templatecontext['flatnavigation'] = $PAGE->flatnav;
echo $OUTPUT->render_from_template('theme_boost_eadifrn/columns2', $templatecontext);
