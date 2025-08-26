<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Visitor_tracker {

    protected $CI;

    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->database();
        $this->CI->load->library('user_agent');
    }

    public function track() {
        // Don't track admin pages
        $current_url = current_url();
        if (strpos($current_url, '/admin') !== false || 
            strpos($current_url, '/auth') !== false ||
            strpos($current_url, '/dashboard') !== false) {
            return;
        }

        // Get visitor information
        $ip_address = $this->CI->input->ip_address();
        $user_agent = $this->CI->agent->agent_string();
        $page_visited = $current_url;

        // Insert into database
        $data = [
            'ip_address' => $ip_address,
            'user_agent' => $user_agent,
            'page_visited' => $page_visited
        ];

        $this->CI->db->insert('visitantes', $data);
    }
}
/* End of file Visitor_tracker.php */
/* Location: ./application/libraries/Visitor_tracker.php */