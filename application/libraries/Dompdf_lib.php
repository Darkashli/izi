<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*require_once APPPATH.'third_party/dompdf/lib/html5lib/Parser.php';
require_once APPPATH.'third_party/dompdf/lib/php-font-lib/src/FontLib/Autoloader.php';
require_once APPPATH.'third_party/dompdf/src/functions.inc.php';
require_once APPPATH.'third_party/dompdf/src/autoload.inc.php';*/

require_once APPPATH.'third_party/dompdf/autoload.inc.php';

class Dompdf_lib extends Dompdf\Dompdf {
    

    /**
     * Get an instance of CodeIgniter
     *
     * @access	protected
     * @return	void
     */
    protected function ci() {
        return get_instance();
    }

    /**
     * Load a CodeIgniter view into domPDF
     *
     * @access	public
     * @param	string	$view The view to load
     * @param	array	$data The view data
     * @return	void
     */
    public function load_view($view, $data = array()) {
        $html = $this->ci()->load->view($view, $data, TRUE);
        $this->load_html($html);
    }

}
