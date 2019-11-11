<?php
/**
 * Renderers to align Moodle's HTML with that expected by Bootstrap
 * 
 * PHP Version 7
 * 
 * @category  MoodleTheme
 * @package   Theme_Ead.Classes.Output
 * @author    Sueldo Sales <sueldosales@gmail.com>
 * @author    Kelson C. Medeiros <kelsoncm@gmail.com>
 * @copyright 2019 IFRN
 * @license   MIT https://opensource.org/licenses/MIT    
 * @link      https://github.com/CoticEaDIFRN/moodle_theme_ead
 */
defined('MOODLE_INTERNAL') || die;


/**
 * Extending the core_course_renderer interface.
 * 
 * lib/outputrenderers.php --> core_course_renderer
 * 
 * @category  Renderer
 * @package   Theme_Ead.Classes.Output
 * @author    Sueldo Sales <sueldosales@gmail.com>
 * @author    Kelson C. Medeiros <kelsoncm@gmail.com>
 * @copyright 2017 IFRN
 * @license   MIT https://opensource.org/licenses/MIT    
 * @link      https://github.com/CoticEaDIFRN/moodle_theme_ead
 */
class theme_ead_core_course_renderer extends \core_course_renderer {

    public function frontpage() {
        global $OUTPUT;
        return $OUTPUT->render_from_template('block_myoverview/main', get_ead_template_context());

    }
   
}
