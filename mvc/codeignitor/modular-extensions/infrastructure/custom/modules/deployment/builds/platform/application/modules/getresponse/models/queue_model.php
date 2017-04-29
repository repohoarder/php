<?php

class Queue_model extends CI_Model {

	function __construct() 
    {
		parent::__construct();
        
		// set version variables (from variables loaded into ci vars)
		$this->_version	= $this->config->item($this->load->_ci_cached_vars['domain']);

        // set the database object (from cached global vars)
        $this->load->database($this->_version['database']);
    }


    function store($order_id, $api, $params)
    {

    	if (is_array($params) || is_object($params)):
    		$params = json_encode($params);
    	endif;

    	$sql = '
    		INSERT INTO 
    			platform_allphase.get_response_queue
    		(order_id, api, params)
    		VALUES
    			(?, ?, ?)
    	';

    	$result = $this->db->query($sql, array($api, $params));

    	if ( ! $this->db->affected_rows()):
    		return FALSE;
    	endif;

    	return $this->db->insert_id();

    }

    function get_due_items()
    {

        $sql = '
            SELECT 
                id, order_id, api, params
            FROM
                platform_allphase.get_response_queue
            WHERE 
                done = 0 AND
                NOW() > DATE_ADD(date_added, INTERVAL wait_mins MINUTE)
        ';

        $query = $this->db->query($sql);

        if ($query->num_rows() < 1):

            return FALSE;

        endif;

        return $query->result_array();

    }

    function mark_completed($row_ids, $cancelled = FALSE, $column = 'id')
    {

        $column = ($column == 'id') ? 'id' : 'order_id';

        if (is_numeric($row_ids) && ! is_array($row_ids)):

            $row_ids = array($row_ids);

        endif;

        if ( ! is_array($row_ids) || ! count($row_ids)):

            return FALSE;

        endif;

        $placeholders = array_fill(0, count($row_ids), '?');
        $params       = $row_ids;

        $cancel       = ($cancelled) ? 1 : 0;

        $sql = '
            UPDATE
                platform_allphase.get_response_queue
            SET 
                done = 1,
                cancelled = '.$cancel.'
            WHERE 
                '.$column.' IN ('.implode(', ',$placeholders).')
            LIMIT
                '.count($placeholders).'
        ';

        $query = $this->db->query($sql, $params);

        return $this->db->affected_rows();

    }

    function mark_cancelled($row_ids, $column = 'id')
    {

        return $this->mark_completed($row_ids, TRUE, $column);

    }

}