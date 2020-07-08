<?php
/**
 * Override core renderer
 * 
 * PAGE LAYOUTS: 
 *  mypage
 *  cbase
 *  course
 *  coursecategory
 *  embedded
 *  frametop
 *  frontpage
 *  incourse
 *  login
 *  maintenance
 *  mydashboard
 *  mypublic
 *  popup
 *  print
 *  redirect
 *  type
 *  report
 *  secure
 *  standard
 * 
 * PAGE TYPES:
 *  admin-{$PAGE->pagetype}
 *  admin-auth-{$auth}
 *  admin-portfolio-{$plugin}
 *  admin-repository-{$plugin}
 *  admin-repository-{$repository}
 *  admin-setting-{$category}
 *  admin-setting-{$section}
 *  bogus-page
 *  course-index-category
 *  course-view
 *  course-view-{$course->format}
 *  maintenance-message
 *  mod-{$cm->modname}-delete
 *  mod-$mod-view
 *  mod-assign-{$action}
 *  mod-data-field-{$newtype}
 *  mod-forum-view 
 *  mod-lesson-view 
 *    branchtable
 *    cluster
 *    endofbranch
 *    endofcluster
 *    essay
 *    matching
 *    multichoice
 *    numerical
 *    shortanswer
 *    truefalse
 *  mod-quiz-edit
 *  my-index 
 *  page-type
 *  question-type{$question->qtype}
 *  site-index 
 *  user-files 
 *  user-preferences
 *  user-profile 
 * 
 * @category  MoodleTheme
 * @package   Theme_Ead.Classes.Output
 * @author    Sueldo Sales <sueldosales@gmail.com>
 * @author    Kelson C. Medeiros <kelsoncm@gmail.com>
 * @copyright 2017 IFRN
 * @license   MIT https://opensource.org/licenses/MIT
 * @link      https://github.com/CoticEaDIFRN/moodle_theme_ead
 */
namespace theme_ead\output;

use coding_exception;
use html_writer;
use tabobject;
use tabtree;
use custom_menu_item;
use custom_menu;
use block_contents;
use navigation_node;
use action_link;
use stdClass;
use moodle_url;
use preferences_groups;
use action_menu;
use help_icon;
use single_button;
use single_select;
use paging_bar;
use url_select;
use context_course;
use pix_icon;

defined('MOODLE_INTERNAL') || die;


/**
 * Extending the core_renderer interface.
 * 
 * lib/outputrenderers.php --> core_renderer
 * 
 * @category  Renderer
 * @package   Theme_Ead.Classes.Output
 * @author    Sueldo Sales <sueldosales@gmail.com>
 * @author    Kelson C. Medeiros <kelsoncm@gmail.com>
 * @copyright 2017 IFRN
 * @license   MIT https://opensource.org/licenses/MIT    
 * @link      https://github.com/CoticEaDIFRN/moodle_theme_ead
 */
class core_renderer extends \theme_boost\output\core_renderer {
    public function breadcrumb() {
        return $this->render_from_template('core/breadcrumb', $this->page->navbar);
        // return $this->render_from_template('core/breadcrumb', new \ArrayIterator([]));
    }

    public function navbar_pagetitle_output() {
        $output = '';
        $output .= $this->header_pagetitle();
        return $output;
    }

    public function user_menu($user = null, $withlinks = null, $usermenuclasses=null) {
        global $PAGE;
        if ( ($PAGE->pagelayout == "frontpage") && (!isloggedin()))  {
             return '<div class="login"><a class="login_ead" href="'. (new moodle_url('/login/index.php'))->out() .'">Entrar</a></div>';
        }else{
            return html_writer::div(parent::user_menu($user, $withlinks), $usermenuclasses);
        }
    }

    public function page_heading_button() {
        $btn = $this->page->button;
        //<i class="icon fa fa-plus"></i>
        $icon = '<i class="icon fa %s" style="font-size: 24px; margin: 0 4px 0 0;" title="%s"></i>';
        if (!empty($btn)) {
            $btn = str_replace(get_string('blocksediton'), sprintf($icon, 'fa-pencil-square-o', get_string('blocksediton')), $btn);
            $btn = str_replace(get_string('blockseditoff'), sprintf($icon, 'fa-pencil-square', get_string('blockseditoff')), $btn);
            $btn = str_replace(get_string('updatemymoodleon'), sprintf($icon, 'fa-pencil-square-o', get_string('updatemymoodleon')), $btn);
            $btn = str_replace(get_string('updatemymoodleoff'), sprintf($icon, 'fa-pencil-square', get_string('updatemymoodleoff')), $btn);
            $btn = str_replace(get_string('resetpage', 'my'), sprintf($icon, 'fa-retweet', get_string('resetpage', 'my')), $btn);
            $btn = str_replace(get_string('reseteveryonesdashboard', 'my'), sprintf($icon, 'fa-retweet', get_string('reseteveryonesdashboard', 'my')), $btn);
            $btn = str_replace(get_string('reseteveryonesprofile', 'my'), sprintf($icon, 'fa-retweet', get_string('reseteveryonesprofile', 'my')), $btn);
            // $btn = str_replace(, $icon, $btn);
        }
        return $btn;
    }

    public function full_header() {
        global $PAGE;
        $header = new stdClass();
        $header->settingsmenu = $this->context_header_settings_menu();
        $header->contextheader = $this->context_header();
        $header->hasbreadcrumb = empty($PAGE->layout_options['nobreadcrumb']);
        $header->breadcrumb = $this->breadcrumb();
        $header->pageheadingbutton = $this->page_heading_button();
        $header->courseheader = $this->course_header();
        return $this->render_from_template('core/full_header', $header);
    }

    protected function header_pagetitle() {
        global $PAGE;
        $pagetitle = $PAGE->title;

        if (isloggedin()) {
            if($PAGE->pagelayout == 'frontpage' || $PAGE->pagelayout == 'mydashboard') {
                $pagetitle = "Vitrine de cursos";
            } elseif ($PAGE->pagelayout == 'mypublic') {
                $pagetitle = "Perfil público";
            } elseif ($PAGE->pagelayout == 'mydashboard') {
                $pagetitle = "Salas de aula";
            } elseif ($PAGE->pagelayout == 'course' || $PAGE->pagelayout == 'incourse') {
                $pagetitle = "Sala de aula";
            } elseif ($PAGE->pagelayout == 'admin') {
                $pagetitle = "Administração";
            }
        }

        return '<p id="navbar_pagetitle" class="d-none d-sm-none d-md-block">'. $pagetitle .'</p>';
    }

    protected function header_help() {
        return '<div class="popover-region collapsed popover-region-help" id="nav-help-popover-container" data-userid="2" data-region="popover-region">
        <a href="https://moodle.org/mod/forum/view.php?id=50"><div class="popover-region-toggle nav-link" data-region="popover-region-toggle" aria-role="button" aria-controls="popover-region-container-5a254db9cba625a254db9b2d7016" aria-haspopup="true" aria-label="Mostrar janela de mensagens sem as novas mensagens" tabindex="0">
                    <i class="icon fa fa-question-circle fa-fw " aria-hidden="true" title="Obter ajuda" aria-label="Obter ajuda"></i>
        </div></a></div>';
    }

    protected function header_admin() {
        if (is_siteadmin()) {
            return '<div class="popover-region collapsed popover-region-admin" id="nav-help-popover-container" data-userid="2" data-region="popover-region">
            <a href="'. (new moodle_url('/admin/search.php'))->out() .'"><div class="popover-region-toggle nav-link" data-region="popover-region-toggle" aria-role="button" aria-controls="popover-region-container-5a254db9cba625a254db9b2d7016" aria-haspopup="true" aria-label="Mostrar janela de mensagens sem as novas mensagens" tabindex="0">
                <i class="icon fa fa-cog fa-fw " aria-hidden="true" title="Administração" aria-label="Administração"></i>
            </div></a></div>';
        }
    }

    protected function header_notification() {
        global $USER;
	// Add the notifications popover.
	$output = '';
        $enabled = \core_message\api::is_processor_enabled("popup");
        if ($enabled && isloggedin()) {
            $context = [
                'userid' => $USER->id,
                'urls' => [
                    'seeall' => (new moodle_url('/message/output/popup/notifications.php'))->out(),
                    'preferences' => (new moodle_url('/message/notificationpreferences.php', ['userid' => $USER->id]))->out(),
                ],
            ];
            $output .= $this->render_from_template('message_popup/notification_popover', $context);
        }
        if (is_siteadmin()) {
            return '<div class="popover-region collapsed popover-region-admin" id="nav-help-popover-container" data-userid="2" data-region="popover-region">
            <a href="'. (new moodle_url('/admin/search.php'))->out() .'"><div class="popover-region-toggle nav-link" data-region="popover-region-toggle" aria-role="button" aria-controls="popover-region-container-5a254db9cba625a254db9b2d7016" aria-haspopup="true" aria-label="Mostrar janela de mensagens sem as novas mensagens" tabindex="0">
                <i class="icon fa fa-cog fa-fw " aria-hidden="true" title="Administração" aria-label="Administração"></i>
            </div></a></div>';
        }
    
        return $output;
    }

    protected function header_messsage() {
        global $USER, $CFG;
        $output = "";
        if (!empty($CFG->messaging)) {
            $unreadcount = \core_message\api::count_unread_conversations($USER);
            $context = [
                'userid' => $USER->id,
                'unreadcount' => $unreadcount,
                'urls' => [
                    'seeall' => (new moodle_url('/message/index.php'))->out(),
                    'writeamessage' => (new moodle_url('/message/index.php', ['contactsfirst' => 1]))->out(),
                    'preferences' => (new moodle_url('/message/edit.php', ['id' => $USER->id]))->out(),
                ],
            ];
            $output .= $this->render_from_template('message_popup/message_popover', $context);
        }
        return $output;
    }

}
