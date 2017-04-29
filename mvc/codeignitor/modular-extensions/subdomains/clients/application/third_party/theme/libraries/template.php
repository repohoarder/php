<?php

/*
 * Template
 * 
 * This class allows you to use multiple templates within your CI install.
 * 
 * @author	Jérôme Jaglales
 * 
 * @link http://maestric.com/doc/php/codeigniter_template
 * 
 * @method array	set(boolean $success, array $data)
 * @method array	obj_to_arr(object|array $obj)
 * 
 */
class Template
{
	/*
	 * Data to set within the theme's template
	 * 
	 * @var array
	 */
	var $template_data = array();
	
	/*
	 * Load data into template
	 * 
	 * Sets data to the global array to be loaded into the theme's template
	 * 
	 * @author	Jérôme Jaglales
	 * 
	 * @access	public
	 * 
	 * @example	set('content', $this->load->view('dashboard'))
	 * 
	 * @param	string							$name	The name of the variable to be laoded
	 * @param	string|int|array|object|boolean	$value	The value of the variable 
	 * 
	 * @return	void
	 */
    function set($name, $value)
    {
    	// sets data to be loaded into the template
        $this->template_data[$name] = $value;
    }

	/*
	 * 
	 * @author	Jérôme Jaglales
	 * 
	 * @access	public
	 * 
	 * @example	load('default', 'dashboard', array('title' => 'Dashboard'), TRUE) 
	 * 
	 * @return	string
	 */	
    public function load($template = '', $view = '' , $view_data = array(), $return = FALSE)
    {
    	// we need ot get an instance of CI
        $this->CI =& get_instance();
        
        // set the content variable
        $this->set('content', $this->CI->load->view($view, $view_data, TRUE));
        
        // return the view
        return $this->CI->load->view($template, $this->template_data, $return);
    }
    
 	public function load_partial($template = '', $view = '' , $view_data = array(), $return = FALSE)
	{
	    $this->set('contents', $this->template_data['controller']->load->view($view, $view_data, TRUE));
	    return $this->template_data['controller']->load->view($template, $this->template_data, $return);
	}  
	
}

?>