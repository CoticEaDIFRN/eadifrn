<?php
/**
 * Theme EaD - Settings file
 *
 * @package    theme_ead
 * @copyright  2017 Kathrin Osswald, Ulm University <kathrin.osswald@uni-ulm.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
require_once __DIR__ . '/lib.php';

if ($ADMIN->fulltree) {

    // Create settings page with tabs.
    $settings = new theme_boost_admin_settingspage_tabs('themesettingead', get_string('configtitle', 'theme_ead'));


    // Create general tab.
    $page = new admin_settingpage('theme_ead_general', get_string('generalsettings', 'theme_boost', null, true));

    // SKIN GROUP
    $page->add(new admin_setting_heading('theme_ead/skinheading', get_string('skinheadingsetting', 'theme_ead'), null));
    $setting = new admin_setting_configselect('theme_ead/skin', 
                                              get_string('skin', 'theme_ead'), 
                                              get_string('skin_desc', 'theme_ead'),
                                              'abacate.scss',
                                              get_ead_theme_skins());
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // HOME PAGE
    $page->add(new admin_setting_heading('theme_ead/homepage', get_string('homepage_title', 'theme_ead'), null));

    $page->add(new admin_setting_configselect('theme_ead/homepage_student',
                                              get_string('homepage_student', 'theme_ead'), 
                                              get_string('homepage_student_desc', 'theme_ead'), 
                                              '$SITE/vitrine/', 
                                              ['$SITE/vitrine/'=>get_string('showcase', 'theme_ead'),
                                               '$SITE/my/'=>get_string('dashboard', 'theme_ead'),
                                               '$SITE/'=>get_string('frontpage', 'theme_ead')
                                               ]));

    $page->add(new admin_setting_configselect('theme_ead/homepage_teacher',
                                              get_string('homepage_teacher', 'theme_ead'), 
                                              get_string('homepage_teacher_desc', 'theme_ead'), 
                                              '$SITE/diarios/', 
                                              ['$SITE/diarios/'=>get_string('to_teach', 'theme_ead'),
                                               '$SITE/course/'=>get_string('to_learn', 'theme_ead'),
                                               '$SITE/vitrine/'=>get_string('showcase', 'theme_ead'),
                                               '$SITE/my/'=>get_string('dashboard', 'theme_ead'),
                                               '$SITE/'=>get_string('frontpage', 'theme_ead')
                                               ]));
                                              
    $page->add(new admin_setting_configselect('theme_ead/homepage_managers',
                                              get_string('homepage_teacher', 'theme_ead'), 
                                              get_string('homepage_teacher_desc', 'theme_ead'), 
                                              '$SITE/admin/search.php', 
                                              ['$SITE/admin/search.php'=>get_string('administration', 'theme_ead'),
                                               '$SITE/diarios/'=>get_string('to_teach', 'theme_ead'), 
                                               '$SITE/course/'=>get_string('to_learn', 'theme_ead'),
                                               '$SITE/vitrine/'=>get_string('showcase', 'theme_ead'),
                                               '$SITE/my/'=>get_string('dashboard', 'theme_ead'),
                                               '$SITE/'=>get_string('frontpage', 'theme_ead')
                                               ]));

    // Settings title to group favicon related settings together with a common heading. We don't want a description here.
    $name = 'theme_ead/faviconheading';
    $title = get_string('faviconheadingsetting', 'theme_ead', null, true);
    $setting = new admin_setting_heading($name, $title, null);
    $page->add($setting);

    // // Favicon upload.
    // $setting = new admin_setting_configstoredfile('theme_ead/favicon', 
    //                                               get_string('faviconsetting', 'theme_ead'), 
    //                                               get_string('faviconsetting_desc', 'theme_ead', 
    //                                               'favicon', 
    //                                               0,
    //                                               ['maxfiles' => 1, 'accepted_types' => ['.ico', '.png']]));
    // $setting->set_updatedcallback('theme_reset_all_caches');
    // $page->add($setting);


    // Add tab to settings page.
    $settings->add($page);

}
