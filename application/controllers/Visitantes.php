<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Visitantes extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('visitante_model');
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language']);

        // Only admins can access this controller
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth/login', 'refresh');
        }
    }

    public function index() {
        $data['title'] = 'Gestión de Visitantes';
        $data['breadcrumbs'] = [
            ['label' => 'Inicio', 'url' => 'dashboard'],
            ['label' => 'Visitantes', 'url' => '']
        ];
        $data['main_content'] = 'visitantes/index';
        $this->load->view('templates/adminlte_layout', $data);
    }

    public function get_visitantes_list() {
        $this->load->library('TablesIgniterCI3', NULL, 'table');
        $this->load->model('Visitante_model','model');
        
        $this->model->set_builder_for_datatables();
        $this->table->setTable($this->model->builder, "visitantes");
        $this->table->setSearch(['ip_address', 'user_agent', 'page_visited']);
        $this->table->setDefaultOrder("visit_time", "DESC");
        $this->table->setOrder([
            0 => 'id',
            1 => 'ip_address',
            2 => 'user_agent',
            3 => 'page_visited',
            4 => 'visit_time'
        ]);
        $this->table->setOutput([
            'id',
            'ip_address',
            'user_agent',
            'page_visited',
            'visit_time',
            'actions' => function($row) {
                $delete_url = site_url('visitantes/delete/'.$row['id']);
                
                $actions = '<div class="btn-group">';
                $actions .= '<button class="btn btn-sm btn-danger delete-visitante" data-id="'.$row['id'].'" data-ip="'.$row['ip_address'].'"><i class="fas fa-trash"></i></button>';
                $actions .= '</div>';
                
                return $actions;
            }
        ]);
        
        echo $this->table->getDatatable();
    }

    public function delete($id) {
        if (!$this->input->is_ajax_request() || $this->input->method() !== 'post') {
            $this->output->set_status_header(400)->set_output(json_encode(['success' => false, 'message' => 'Método de solicitud no válido.']));
            return;
        }

        $this->output->set_content_type('application/json');
        
        if ($this->visitante_model->delete($id)) {
            echo json_encode(['success' => true, 'message' => 'Visitante eliminado exitosamente.']);
        } else {
            $this->output->set_status_header(500)->set_output(json_encode(['success' => false, 'message' => 'Error al eliminar el visitante.']));
        }
    }

    public function clear_all() {
        if (!$this->input->is_ajax_request() || $this->input->method() !== 'post') {
            $this->output->set_status_header(400)->set_output(json_encode(['success' => false, 'message' => 'Método de solicitud no válido.']));
            return;
        }

        $this->output->set_content_type('application/json');
        
        if ($this->visitante_model->clear_all()) {
            echo json_encode(['success' => true, 'message' => 'Todos los visitantes han sido eliminados exitosamente.']);
        } else {
            $this->output->set_status_header(500)->set_output(json_encode(['success' => false, 'message' => 'Error al eliminar los visitantes.']));
        }
    }

    public function stats() {
        $data['title'] = 'Estadísticas de Visitantes';
        $data['breadcrumbs'] = [
            ['label' => 'Inicio', 'url' => 'dashboard'],
            ['label' => 'Visitantes', 'url' => 'visitantes'],
            ['label' => 'Estadísticas', 'url' => '']
        ];
        
        // Get statistics
        $data['total_visitors'] = $this->visitante_model->count_all();
        $data['unique_visitors'] = $this->visitante_model->count_unique();
        $data['visitors_today'] = $this->visitante_model->count_today();
        $data['visitors_this_month'] = $this->visitante_model->count_this_month();
        $data['top_pages'] = $this->visitante_model->get_top_pages(10);
        $data['visitor_trend'] = $this->visitante_model->get_visitor_trend(30);
        
        $data['main_content'] = 'visitantes/stats';
        $this->load->view('templates/adminlte_layout', $data);
    }
}
/* End of file Visitantes.php */
/* Location: ./application/controllers/Visitantes.php */