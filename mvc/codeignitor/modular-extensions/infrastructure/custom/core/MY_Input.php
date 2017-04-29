<?php

class MY_Input extends CI_Input {

    var $_old_post;

    function __construct()
    {

        $this->_old_post = $_POST;

        if (get_magic_quotes_gpc()):

            $this->_old_post = $this->_stripslashes_recursive($this->_old_post);

        endif;

        parent::__construct();
    }

    function unclean_post($index){

        return (isset($this->_old_post[$index]) ? $this->_old_post[$index] : NULL);

    }

    // If magic quotes are enabled, strip slashes from all user data
    // http://www.dzone.com/snippets/strip-slashes-user-input-if
    function _stripslashes_recursive($var) {

        return (is_array($var) ? array_map(array($this,'_stripslashes_recursive'), $var) : stripslashes($var));
    }


}