<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Smarty Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Smarty
 * @author		Kepler Gelotte
 * @link		http://www.coolphptools.com/codeigniter-smarty
 */
class SmartyLib extends Smarty
{

    public function __construct()
    {
        parent::__construct();

        $ci = & get_instance();

        $this->error_reporting = E_ALL & ~E_NOTICE & ~E_DEPRECATED;

        $this->compile_dir  = APPPATH . "cache/templates_c";
        $this->template_dir = APPPATH . "views/smarty/templates";
        $this->assign('APPPATH', APPPATH);
        $this->assign('BASEPATH', BASEPATH);
        $this->assign('BASE_URL', base_url());
        $this->assign('ci', get_instance());

        if (defined('APPLICATION_TITLE'))
        {
            $this->assign('APPLICATION_TITLE', APPLICATION_TITLE);
        }

        $ci->load->library('calendar');
        $this->assign('DIAS_SEMANA', $ci->calendar->get_day_names('long'));

        // Assign CodeIgniter object by reference to CI
        if (method_exists($this, 'assignByRef'))
        {
            $this->assignByRef("ci", $ci);
        }

        log_message('debug', "Smarty Class Initialized");
    }

    /**
     *  Parse a template using the Smarty engine
     *
     * This is a convenience method that combines assign() and
     * display() into one step.
     *
     * Values to assign are passed in an associative array of
     * name => value pairs.
     *
     * If the output is to be returned as a string to the caller
     * instead of being output, pass true as the third parameter.
     *
     * @access	public
     * @param	string
     * @param	array
     * @param	bool
     * @return	string
     */
    public function view($template, $data = array(), $return = FALSE)
    {
        $this->assign('APPLICATION_TITLE', APPLICATION_TITLE);

        if (!preg_match('/^string:/', $template))
        {
            $template .= '.tpl';
        }

        foreach ($data as $key => $val)
        {
            $this->assign($key, $val);
        }

        if ($return == FALSE)
        {
            $CI = & get_instance();
            if (method_exists($CI->output, 'set_output'))
            {
                $CI->output->set_output($this->fetch($template));
            }
            else
            {
                $CI->output->final_output = $this->fetch($template);
            }
            return;
        }
        else
        {
            return $this->fetch($template);
        }
    }
}

// END Smarty Class
