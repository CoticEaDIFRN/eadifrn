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
 * Theme Boost eadifrn - Library
 *
 * @package    theme_boost_eadifrn
 * @copyright  2017 Kathrin Osswald, Ulm University <kathrin.osswald@uni-ulm.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Returns the main SCSS content.
 *
 * @param theme_config $theme The theme config object.
 * @return string
 */
function theme_boost_eadifrn_get_main_scss_content($theme) {
    global $CFG;

    $scss = '';
    $filename = !empty($theme->settings->preset) ? $theme->settings->preset : null;
    $fs = get_file_storage();
    
    $context = context_system::instance();
    if ($filename == 'plain.scss') {
        // We still load the default preset files directly from the boost theme. No sense in duplicating them.
        $scss .= file_get_contents($CFG->dirroot . '/theme/boost/scss/preset/plain.scss');
    } else if ($filename == 'eadifrn.scss') {
        // We still load the default preset files directly from the boost theme. No sense in duplicating them.
        $scss .= file_get_contents($CFG->dirroot . '/theme/boost_eadifrn/scss/preset/eadifrn.scss');

    } else if ($filename && ($presetfile = $fs->get_file($context->id, 'theme_boost_eadifrn', 'preset', 0, '/', $filename))) {
        // This preset file was fetched from the file area for theme_boost_eadifrn and not theme_boost (see the line above).
        $scss .= $presetfile->get_content();
    } else {
        // Safety fallback - maybe new installs etc.
        $scss .= file_get_contents($CFG->dirroot . '/theme/boost/scss/preset/default.scss');
    }

    // Pre CSS - this is loaded AFTER any prescss from the setting but before the main scss.
    // $pre = file_get_contents($CFG->dirroot . '/theme/boost_eadifrn/scss/pre.scss');
    // Post CSS - this is loaded AFTER the main scss but before the extra scss from the setting.
    // $post = file_get_contents($CFG->dirroot . '/theme/boost_eadifrn/scss/post.scss');

    // Combine them together.
    return $scss;
}

/**
 * Override to add CSS values from settings to pre scss file.
 *
 * Get SCSS to prepend.
 *
 * @param theme_config $theme The theme config object.
 * @return array
 */
function theme_boost_eadifrn_get_pre_scss($theme) {
    global $CFG;
    // MODIFICATION START.
    require_once($CFG->dirroot . '/theme/boost_eadifrn/locallib.php');
    // MODIFICATION END.

    $scss = '';
    $configurable = [
        // Config key => [variableName, ...].
        'brandcolor' => ['brand-primary'],
        // MODIFICATION START: Add own variables.
        'section0title' => ['section0title'],
        'showswitchedroleincourse' => ['showswitchedroleincourse'],
        'loginform' => ['loginform'],
        'footerhidehelplink' => ['footerhidehelplink'],
        'footerhidelogininfo' => ['footerhidelogininfo'],
        'footerhidehomelink' => ['footerhidehomelink'],
        'blockicon' => ['blockicon'],
        'brandsuccesscolor' => ['brand-success'],
        'brandinfocolor' => ['brand-info'],
        'brandwarningcolor' => ['brand-warning'],
        'branddangercolor' => ['brand-danger'],
        'darknavbar' => ['darknavbar'],
        'footerblocks' => ['footerblocks'],
        'imageareaitemsmaxheight' => ['imageareaitemsmaxheight'],
        'showsettingsincourse' => ['showsettingsincourse'],
        'incoursesettingsswitchtorole' => ['incoursesettingsswitchtorole']
        // MODIFICATION END.
    ];

    // Prepend variables first.
    foreach ($configurable as $configkey => $targets) {
        $value = isset($theme->settings->{$configkey}) ? $theme->settings->{$configkey} : null;
        if (empty($value)) {
            continue;
        }
        array_map(function($target) use (&$scss, $value) {
            $scss .= '$' . $target . ': ' . $value . ";\n";
        }, (array) $targets);
    }

    // MODIFICATION START: Add login background images that are uploaded to the setting 'loginbackgroundimage' to CSS.
    // $scss .= theme_boost_eadifrn_get_loginbackgroundimage_scss();
    // MODIFICATION END.

    // Prepend pre-scss.
    if (!empty($theme->settings->scsspre)) {
        $scss .= $theme->settings->scsspre;
    }

    return $scss;
}

/**
 * Implement pluginfile function to deliver files which are uploaded in theme settings
 *
 * @param stdClass $course course object
 * @param stdClass $cm course module
 * @param stdClass $context context object
 * @param string $filearea file area
 * @param array $args extra arguments
 * @param bool $forcedownload whether or not force download
 * @param array $options additional options affecting the file serving
 * @return bool
 */
function theme_boost_eadifrn_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = array()) {
    if ($context->contextlevel == CONTEXT_SYSTEM) {
        $theme = theme_config::load('boost_eadifrn');
        if ($filearea === 'favicon') {
            return $theme->setting_file_serve('favicon', $args, $forcedownload, $options);
        } else if ($filearea === 'loginbackgroundimage') {
            return $theme->setting_file_serve('loginbackgroundimage', $args, $forcedownload, $options);
        } else if ($filearea === 'fontfiles') {
            return $theme->setting_file_serve('fontfiles', $args, $forcedownload, $options);
        } else if ($filearea === 'imageareaitems') {
            return $theme->setting_file_serve('imageareaitems', $args, $forcedownload, $options);
        } else {
            send_file_not_found();
        }
    } else {
        send_file_not_found();
    }
}

/**
 * If setting is updated, use this callback to clear the theme_boost_eadifrn' own application cache.
 */
function theme_boost_eadifrn_reset_app_cache() {
    // Get the cache from area.
    $themeboosteadifrncache = cache::make('theme_boost_eadifrn', 'imagearea');
    // Delete the cache for the imagearea.
    $themeboosteadifrncache->delete('imageareadata');
    // To be safe and because there can only be one callback function added to a plugin setting,
    // we also delete the complete theme cache here.
    theme_reset_all_caches();
}


function get_ead_ifrn_commom_moodle_template_context()
{
    global $OUTPUT, $PAGE, $COURSE;

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
    $in_course_page = $PAGE->pagelayout == "course";
    $course_name = $COURSE->fullname;
    $course_code = $COURSE->shortname;
    return [
        'sitename' => format_string($SITE->shortname, true, ['context' => context_course::instance(SITEID), "escape" => false]),
        'output' => $OUTPUT,
        'sidepreblocks' => $blockshtml,
        'hasblocks' => $hasblocks,
        'bodyattributes' => $bodyattributes,
        'navdraweropen' => $navdraweropen,
        'regionmainsettingsmenu' => $regionmainsettingsmenu,
        'hasregionmainsettingsmenu' => !empty($regionmainsettingsmenu),
        'link_calendar' => (new moodle_url('/calendar/view.php?view=month'))->out(),
        'link_sala_aula' => (new moodle_url('/my'))->out(),
        'link_mural' => (new moodle_url('/mural'))->out(),
        'link_secretaria' => (new moodle_url('/secretaria'))->out(),
        'in_course_page' => $in_course_page,
        'course' => $COURSE,
        'course_name' => course_name
    ];
}

function get_ead_ifrn_calendario() {
    global $CFG, $COURSE;
    $calendar = \calendar_information::create(time(), $COURSE->id, $COURSE->category);
    list($data, $template) = calendar_get_view($calendar, 'upcoming_mini');
    if (sizeof($data->events) == 0) {
        return false;
    }
    $result = [];
    foreach ($data->events as $key => $value) {
        $shortdate = date('d M', $value->timestart);
        if (!array_key_exists($shortdate, $result)) {
            $result[$shortdate] = new stdClass();
            $result[$shortdate]->shortdate = $shortdate;

            $data_mes = explode(" ", $shortdate);

            $result[$shortdate]->shortdate_dia = $data_mes[0];
            $result[$shortdate]->shortdate_mes = $data_mes[1];
            $result[$shortdate]->viewurl = $value->viewurl;
            $result[$shortdate]->events = [];
        }
        $result[$shortdate]->events[] = $value;
    }
    return new ArrayIterator($result);
}

function get_ead_ifrn_course_content_actions()
{
    global $PAGE, $COURSE;
    if ($PAGE->pagelayout == "course") {
        $flatnav = [];
        foreach ($PAGE->flatnav as $child_key) {
            if ($child_key->type == 30) {
                $flatnav[] = $child_key;
            }
        }
        return new ArrayIterator($flatnav);
    }
}
    
function get_ead_ifrn_course_common_actions() 
{
    global $PAGE, $COURSE;
    if ($PAGE->pagelayout == "course") {
        $extraflatnav = [];
        
        // Notas
        $notas = new stdClass();
        $notas->action_url = new moodle_url("/grade/report/index.php", ['id'=>$COURSE->id]);
        $notas->icon = "table";
        $notas->label = "Notas";
        $extraflatnav[] = $notas;
    
        // Emblemas
        $emblemas = new stdClass();
        $emblemas->action_url = new moodle_url("/badges/view.php", ['type'=>2, 'id'=>$COURSE->id]);
        $emblemas->icon = "shield";
        $emblemas->label = "Emblemas";
        $extraflatnav[] = $emblemas;
    
        // Competências
        $competencias = new stdClass();
        $competencias->action_url = new moodle_url("/admin/tool/lp/coursecompetencies.php", ['courseid'=>$COURSE->id]);
        $competencias->icon = "check-square-o";
        $competencias->label = "Competências";
        $extraflatnav[] = $competencias;

        // Participantes
        $participantes = new stdClass();
        $participantes->action_url = new moodle_url("/user/index.php", ['id'=>$COURSE->id]);
        $participantes->icon = "users";
        $participantes->label = "Participantes";
        $extraflatnav[] = $participantes;
    
        return new ArrayIterator($extraflatnav);
    }
}



function get_ead_ifrn_template_context()
{
    global $PAGE;

    $templatecontext = get_ead_ifrn_commom_moodle_template_context();

    if ($templatecontext['in_course_page']) {
        $templatecontext['course_content_actions'] = get_ead_ifrn_course_content_actions();
        $templatecontext['course_common_actions'] = get_ead_ifrn_course_common_actions();
    }
    $templatecontext['nosso_calendario'] = get_ead_ifrn_calendario();
    return $templatecontext;
};
