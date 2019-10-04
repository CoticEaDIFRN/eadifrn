<?php
/**
 * MIT License
 * 
 * Copyright 2017 IFRN
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a 
 * copy of this software and associated documentation files (the "Software"),
 * to deal in the Software without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons to whom the 
 * Software is furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER 
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING 
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS
 * IN THE SOFTWARE.
 * 
 * Renderers to align Moodle's HTML with that expected by Bootstrap
 * 
 * PHP Version 7
 * 
 * @category  MoodleTheme
 * @package   Theme_Boost_EadIfrn.Classes.Output
 * @author    Sueldo Sales <sueldosales@gmail.com>
 * @author    Kelson C. Medeiros <kelsoncm@gmail.com>
 * @copyright 2019 IFRN
 * @license   MIT https://opensource.org/licenses/MIT    
 * @link      https://github.com/CoticEaDIFRN/eadifrn
 */
defined('MOODLE_INTERNAL') || die;


/**
 * Extending the core_course_renderer interface.
 * 
 * lib/outputrenderers.php --> core_course_renderer
 * 
 * @category  Renderer
 * @package   Theme_Boost_EadIfrn.Classes.Output
 * @author    Sueldo Sales <sueldosales@gmail.com>
 * @author    Kelson C. Medeiros <kelsoncm@gmail.com>
 * @copyright 2017 IFRN
 * @license   MIT https://opensource.org/licenses/MIT    
 * @link      https://github.com/CoticEaDIFRN/eadifrn
 */
class theme_boost_eadifrn_core_course_renderer extends \core_course_renderer {

    public function frontpage() {
        return "caracas, moodle";
    }

    public function frontpage_my_courses() {
        return "este é um my_courses";
    }
    public function frontpage_available_courses() {
        return "frontpage_available_courses";
    }
   
}
