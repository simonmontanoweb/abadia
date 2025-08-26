<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'libraries/TablesIgniterCI3.php';

class TablesIgniterCI3_fixed extends TablesIgniterCI3 {
    
    public function setBuilder($builder) {
        // Ensure builder is a CI_DB_query_builder object
        if (is_object($builder) && method_exists($builder, 'get_compiled_select')) {
            parent::setBuilder($builder);
        } else {
            // Create a new builder if needed
            $CI =& get_instance();
            $this->builder = $CI->db;
        }
    }
    
    // Override the method that's causing the __clone error
    protected function _clone_builder() {
        if (is_object($this->builder) && method_exists($this->builder, 'get_compiled_select')) {
            return clone $this->builder;
        }
        // Return a new builder if we can't clone
        $CI =& get_instance();
        return $CI->db;
    }
}
/* End of file TablesIgniterCI3_fixed.php */
/* Location: ./application/libraries/TablesIgniterCI3_fixed.php */