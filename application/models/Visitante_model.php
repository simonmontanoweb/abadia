<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Visitante_model extends CI_Model {

    public $builder;

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->set_builder_for_datatables();
    }

    public function set_builder_for_datatables() {
        $this->builder = $this->db->from('visitantes');
    }

    public function count_all() {
        return $this->db->count_all('visitantes');
    }

    public function count_unique() {
        $this->db->select('COUNT(DISTINCT ip_address) as count');
        $query = $this->db->get('visitantes');
        $result = $query->row();
        return $result->count;
    }

    public function count_today() {
        $today = date('Y-m-d');
        $this->db->where('DATE(visit_time)', $today);
        return $this->db->count_all_results('visitantes');
    }

    public function count_this_month() {
        $this_month = date('Y-m');
        $this->db->where('DATE_FORMAT(visit_time, "%Y-%m")', $this_month);
        return $this->db->count_all_results('visitantes');
    }

    public function get_top_pages($limit = 10) {
        $this->db->select('page_visited, COUNT(*) as visits');
        $this->db->group_by('page_visited');
        $this->db->order_by('visits', 'DESC');
        $this->db->limit($limit);
        $query = $this->db->get('visitantes');
        return $query->result();
    }

    public function get_visitor_trend($days = 30) {
        $this->db->select('DATE(visit_time) as date, COUNT(*) as visitors');
        $this->db->where('visit_time >=', date('Y-m-d H:i:s', strtotime("-$days days")));
        $this->db->group_by('DATE(visit_time)');
        $this->db->order_by('date', 'ASC');
        $query = $this->db->get('visitantes');
        return $query->result();
    }

    public function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete('visitantes');
    }

    public function clear_all() {
        return $this->db->empty_table('visitantes');
    }
}
/* End of file Visitante_model.php */
/* Location: ./application/models/Visitante_model.php */