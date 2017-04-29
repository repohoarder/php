<?php 

echo $this->template->load_view('header'); 

echo $this->template->load_view('errors');

echo $template['body'];

echo $this->template->load_view('footer'); 