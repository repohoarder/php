<?php

class Star_wars extends MX_Controller {


	function index()
	{

		$data['caption'] = 'Here we assign header information to cells by setting the scope attribute.';
		
		// Key = CSS ID, value = heading title
		// Associative array to allow for sorting
		// Keys should keep 'heading-' as a prefix, and must be unique
		$data['headings'] = array(
			'heading-col1' => 'Column 1',
			'heading-col2' => 'Column 2',
			'heading-col3' => 'Column 3',
			'heading-col4' => 'Column 4',
			'heading-col5' => 'Column 5',
			'heading-col6' => 'Column 6',
			'heading-col7' => 'Column 7'
		);

		$data['rows'] = array(
			'row-row1' => array('Row 1 Cell 1','Row 1 Cell 2','Row 1 Cell 3','Row 1 Cell 4','Row 1 Cell 5','Row 1 Cell 6','Row 1 Cell 7'),
			'row-row2' => array('Row 2 Cell 1','Row 2 Cell 2','Row 2 Cell 3','Row 2 Cell 4','Row 2 Cell 5','Row 2 Cell 6','Row 2 Cell 7'),
			'row-row3' => array('Row 3 Cell 1','Row 3 Cell 2','Row 3 Cell 3','Row 3 Cell 4','Row 3 Cell 5','Row 3 Cell 6','Row 3 Cell 7'),
			'row-row4' => array('Row 4 Cell 1','Row 4 Cell 2','Row 4 Cell 3','Row 4 Cell 4','Row 4 Cell 5','Row 4 Cell 6','Row 4 Cell 7'),
			'row-row5' => array('Row 5 Cell 1','Row 5 Cell 2','Row 5 Cell 3','Row 5 Cell 4','Row 5 Cell 5','Row 5 Cell 6','Row 5 Cell 7'),
			'row-row6' => array('Row 6 Cell 1','Row 6 Cell 2','Row 6 Cell 3','Row 6 Cell 4','Row 6 Cell 5','Row 6 Cell 6','Row 6 Cell 7'),
			'row-row7' => array('Row 7 Cell 1','Row 7 Cell 2','Row 7 Cell 3','Row 7 Cell 4','Row 7 Cell 5','Row 7 Cell 6','Row 7 Cell 7')
		);


		$this->load->view('highcharts/table', $data);
	}

}