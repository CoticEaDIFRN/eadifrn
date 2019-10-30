<?php
/**
 * Theme EaD - Settings file
 *
 * @package    theme_ead
 * @copyright  2017 Kathrin Osswald, Ulm University <kathrin.osswald@uni-ulm.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree) {

    // Create settings page with tabs.
    $settings = new theme_boost_admin_settingspage_tabs('themesettingead', get_string('configtitle', 'theme_ead'));


    // Create general tab.
    $page = new admin_settingpage('theme_ead_general', get_string('generalsettings', 'theme_boost', null, true));

    // SKIN GROUP
    $page->add(new admin_setting_heading('theme_ead/skinheading', 
                                         get_string('skinheadingsetting', 
                                         'theme_ead'),
                                         null));

    $choices = [];
    $choices['abacate.scss'] = 'Verde - Abacate';
    // $choices['alface.scss'] = 'Verde - Alface';
    // $choices['oliva.scss'] = 'Verde - Oliva';
    // $choices['alto-contraste-claro.scss'] = 'Alto contrate - claro';
    // $choices['alto-contraste-escuro.scss'] = 'Alto contrate - escuro';
    // $choices['anil.scss'] = 'Azul - Anil';
    // $choices['safira.scss'] = 'Azul - Safira';
    // $choices['ipe.scss'] = 'Vívido - Ipê';
    // $choices['jerimum.scss'] = 'Vívido - Jerimum';
    $choices['solar.scss'] = 'Vívido - Solar';

    $setting = new admin_setting_configselect('theme_ead/skin', 
                                              get_string('skin', 'theme_ead'), 
                                              get_string('skin_desc', 'theme_ead'),
                                              'abacate.scss',
                                              $choices);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // HOME PAGE
    $page->add(new admin_setting_heading('theme_ead/homepage', get_string('homepage_title', 'theme_ead'), null));

    $page->add(new admin_setting_configselect('theme_ead/homepage_student',
                                              get_string('homepage_student', 'theme_ead'), 
                                              get_string('homepage_student_desc', 'theme_ead'), 
                                              '$SITE/vitrine/', 
                                              ['$SITE/vitrine/'=>'Vitrine', 
                                               '$SITE/my/'=>'Painel',
                                               '$SITE/'=>'Página pública']));

    $page->add(new admin_setting_configselect('theme_ead/homepage_teacher',
                                              get_string('homepage_teacher', 'theme_ead'), 
                                              get_string('homepage_teacher_desc', 'theme_ead'), 
                                              '$SITE/diarios/', 
                                              ['$SITE/diarios/'=>'Diários', 
                                               '$SITE/course/'=>'Cursos', 
                                               '$SITE/vitrine/'=>'Vitrine', 
                                               '$SITE/my/'=>'Painel',
                                               '$SITE/'=>'Página pública']));
                                              
    $page->add(new admin_setting_configselect('theme_ead/homepage_managers',
                                              get_string('homepage_teacher', 'theme_ead'), 
                                              get_string('homepage_teacher_desc', 'theme_ead'), 
                                              '$SITE/admin/search.php', 
                                              ['$SITE/admin/search.php'=>'Administração', 
                                               '$SITE/diarios/'=>'Diários', 
                                               '$SITE/course/'=>'Cursos', 
                                               '$SITE/vitrine/'=>'Vitrine', 
                                               '$SITE/my/'=>'Painel',
                                               '$SITE/'=>'Página pública']));

    // Settings title to group favicon related settings together with a common heading. We don't want a description here.
    $name = 'theme_ead/faviconheading';
    $title = get_string('faviconheadingsetting', 'theme_ead', null, true);
    $setting = new admin_setting_heading($name, $title, null);
    $page->add($setting);

    // Favicon upload.
    $name = 'theme_ead/favicon';
    $title = get_string('faviconsetting', 'theme_ead', null, true);
    $description = get_string('faviconsetting_desc', 'theme_ead', null, true);
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'favicon', 0,
        array('maxfiles' => 1, 'accepted_types' => array('.ico', '.png')));
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);


    // Add tab to settings page.
    $settings->add($page);

}
