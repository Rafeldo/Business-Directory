<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Themes_model_core extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function activate()
	{
		$theme = $this->input->post('theme');
		add_option('active_theme',$theme);
	}

}

/* End of file themes_model_core.php */
/* Location: ./system/application/models/themes_model_core.php */
