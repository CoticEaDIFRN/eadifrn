<?php
/**
 * Theme EaD - Locallib file
 *
 * @package    theme_ead
 * @author     Sueldo Sales <sueldosales@gmail.com>
 * @author     Kelson C. Medeiros <kelsoncm@gmail.com>
 * @package    theme_ead
 * @copyright  2017 IFRN <https://ifrn.edu.br>
 * @license    MIT https://opensource.org/licenses/MIT
 * @link       https://github.com/CoticEaDIFRN/moodle_theme_ead
*/

 defined('MOODLE_INTERNAL') || die();

/**
 * Returns a modified flat_navigation object.
 *
 * @param flat_navigation $flatnav The flat navigation object.
 * @return flat_navigation.
 */
function theme_ead_process_flatnav(flat_navigation $flatnav) {
    global $USER;
    // If the setting defaulthomepageontop is enabled.
    if (get_config('theme_ead', 'defaulthomepageontop') == 'yes') {
        // Only proceed processing if we are in a course context.
        if (($coursehomenode = $flatnav->find('coursehome', global_navigation::TYPE_CUSTOM)) != false) {
            // If the site home is set as the deafult homepage by the admin.
            if (get_config('core', 'defaulthomepage') == HOMEPAGE_SITE) {
                // Return the modified flat_navigtation.
                $flatnavreturn = theme_ead_set_node_on_top($flatnav, 'home', $coursehomenode);
            } else if (get_config('core', 'defaulthomepage') == HOMEPAGE_MY) { // If the dashboard is set as the default homepage
                // by the admin.
                // Return the modified flat_navigtation.
                $flatnavreturn = theme_ead_set_node_on_top($flatnav, 'myhome', $coursehomenode);
            } else if (get_config('core', 'defaulthomepage') == HOMEPAGE_USER) { // If the admin defined that the user can set
                // the default homepage for himself.
                // Site home.
                if (get_user_preferences('user_home_page_preference', $USER) == 0) {
                    // Return the modified flat_navigtation.
                    $flatnavreturn = theme_ead_set_node_on_top($flatnav, 'home', $coursehomenode);
                } else if (get_user_preferences('user_home_page_preference', $USER) == 1 || // Dashboard.
                    get_user_preferences('user_home_page_preference', $USER) == false) { // If no user preference is set,
                    // use the default value of core setting default homepage (Dashboard).
                    // Return the modified flat_navigtation.
                    $flatnavreturn = theme_ead_set_node_on_top($flatnav, 'myhome', $coursehomenode);
                } else { // Should not happen.
                    // Return the passed flat navigation without changes.
                    $flatnavreturn = $flatnav;
                }
            } else { // Should not happen.
                // Return the passed flat navigation without changes.
                $flatnavreturn = $flatnav;
            }
        } else { // Not in course context.
            // Return the passed flat navigation without changes.
            $flatnavreturn = $flatnav;
        }
    } else { // Defaulthomepageontop not enabled.
        // Return the passed flat navigation without changes.
        $flatnavreturn = $flatnav;
    }
    return $flatnavreturn;
}

/**
 * Modifies the flat_navigation to add the node on top.
 *
 * @param flat_navigation $flatnav The flat navigation object.
 * @param string $nodename The name of the node that is to modify.
 * @param navigation_node $beforenode The node before which the to be modified node shall be added.
 * @return flat_navigation.
 */
function theme_ead_set_node_on_top(flat_navigation $flatnav, $nodename, $beforenode) {
    // Get the node for which the sorting shall be changed.
    $pageflatnav = $flatnav->find($nodename, global_navigation::TYPE_SYSTEM);
    // Set the showdivider of the new top node to false that no empty nav-element will be created.
    $pageflatnav->set_showdivider(false);
    // Add the showdivider to the coursehome node as this is the next one and this will add a margin top to it.
    $beforenode->set_showdivider(true);
    // Remove the site home navigation node that it does not appear twice in the menu.
    $flatnav->remove($nodename);
    // Add the saved site home node before the $beforenode.
    $flatnav->add($pageflatnav, $beforenode->key);

    // Return the modified changes.
    return $flatnav;
}


/**
 * Provides the node for the in-course course or activity settings.
 *
 * @return navigation_node.
 */
function theme_ead_get_incourse_settings() {
    global $COURSE, $PAGE;
    // Initialize the node with false to prevent problems on pages that do not have a courseadmin node.
    $node = false;
    // If setting showsettingsincourse is enabled.
    if (get_config('theme_ead', 'showsettingsincourse') == 'yes') {
        // Only search for the courseadmin node if we are within a course or a module context.
        if ($PAGE->context->contextlevel == CONTEXT_COURSE || $PAGE->context->contextlevel == CONTEXT_MODULE) {
            // Get the courseadmin node for the current page.
            $node = $PAGE->settingsnav->find('courseadmin', navigation_node::TYPE_COURSE);
            // Check if $node is not empty for other pages like for example the langauge customization page.
            if (!empty($node)) {
                // If the setting 'incoursesettingsswitchtorole' is enabled add these to the $node.
                if (get_config('theme_ead', 'incoursesettingsswitchtorole') == 'yes' && !is_role_switched($COURSE->id)) {
                    // Build switch role link
                    // We could only access the existing menu item by creating the user menu and traversing it.
                    // So we decided to create this node from scratch with the values copied from Moodle core.
                    $roles = get_switchable_roles($PAGE->context);
                    if (is_array($roles) && (count($roles) > 0)) {
                        // Define the properties for a new tab.
                        $properties = array('text' => get_string('switchroleto', 'theme_ead'),
                                            'type' => navigation_node::TYPE_CONTAINER,
                                            'key'  => 'switchroletotab');
                        // Create the node.
                        $switchroletabnode = new navigation_node($properties);
                        // Add the tab to the course administration node.
                        $node->add_node($switchroletabnode);
                        // Add the available roles as children nodes to the tab content.
                        foreach ($roles as $key => $role) {
                            $properties = array('action' => new moodle_url('/course/switchrole.php',
                                array('id'         => $COURSE->id,
                                      'switchrole' => $key,
                                      'returnurl'  => $PAGE->url->out_as_local_url(false),
                                      'sesskey'    => sesskey())),
                                                'type'   => navigation_node::TYPE_CUSTOM,
                                                'text'   => $role);
                            $switchroletabnode->add_node(new navigation_node($properties));
                        }
                    }
                }
            }
        }
        return $node;
    }
}

/**
 * Provides the node for the in-course settings for other contexts.
 *
 * @return navigation_node.
 */
function theme_ead_get_incourse_activity_settings() {
    global $PAGE;
    $context = $PAGE->context;
    $node = false;
    // If setting showsettingsincourse is enabled.
    if (get_config('theme_ead', 'showsettingsincourse') == 'yes') {
        // Settings belonging to activity or resources.
        if ($context->contextlevel == CONTEXT_MODULE) {
            $node = $PAGE->settingsnav->find('modulesettings', navigation_node::TYPE_SETTING);
        } else if ($context->contextlevel == CONTEXT_COURSECAT) {
            // For course category context, show category settings menu, if we're on the course category page.
            if ($PAGE->pagetype === 'course-index-category') {
                $node = $PAGE->settingsnav->find('categorysettings', navigation_node::TYPE_CONTAINER);
            }
        } else {
            $node = false;
        }
    }
    return $node;
}
