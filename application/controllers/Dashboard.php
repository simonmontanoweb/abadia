<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library(['ion_auth']);
        $this->load->model('agente_model');
        $this->load->model('afiliacion_model');
        $this->load->model('visitante_model');   
        $this->load->helper(['url']);
        
        // All methods in this controller require a login
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
    }

    /**
     * Main dashboard entry point.
     * Routes the user to the correct dashboard based on their group.
     */
    public function index() {
        $data['title'] = 'Dashboard';
        $data['breadcrumbs'] = [['label' => 'Dashboard', 'url' => '']];
        
        // Get counts for dashboard widgets
        $data['agentes_count'] = $this->agente_model->count_agentes();
        $data['afiliaciones_count'] = $this->afiliacion_model->count_all_afiliaciones();
        
        $user = $this->ion_auth->user()->row();
        if ($this->ion_auth->is_admin()) {
            $this->_admin_dashboard($data);
        } elseif ($this->ion_auth->in_group('Gerente General')) {
            // Assuming Gerente General has a view similar to admin for now
            $this->_admin_dashboard($data);
        } elseif ($this->ion_auth->in_group('Gerente de Zona')) {
            $this->_gerente_zona_dashboard($data, $user->id);
        } elseif ($this->ion_auth->in_group('Supervisor')) {
            $this->_supervisor_dashboard($data, $user->id);
        } elseif ($this->ion_auth->in_group('Agente')) {
            $this->_agente_dashboard($data, $user->id);
        } else {
            // Default 'members' group, considered a client
            $this->_client_dashboard($data, $user->id);
        }
    }

    /**
     * Prepares data for the Administrator / Gerente General dashboard.
     */
    private function _admin_dashboard($data) {
        // Get additional statistics for admin dashboard
        $data['recent_afiliaciones'] = $this->afiliacion_model->get_recent_afiliaciones(5);
        $data['top_agentes'] = $this->agente_model->get_top_agentes(5);
        $data['afiliaciones_by_month'] = $this->afiliacion_model->get_afiliaciones_by_month();
        
        $data['main_content'] = 'dashboard/admin_dashboard';
        $this->load->view('templates/adminlte_layout', $data);
    }

    /**
     * Prepares data for the Gerente de Zona dashboard.
     */
    private function _gerente_zona_dashboard($data, $user_id) {
        // Get the agent record for this user
        $gerente = $this->agente_model->get_agent_by_user_id($user_id);
        
        if (!$gerente) {
            show_error('No se encontró el registro de gerente de zona para este usuario.');
            return;
        }
        
        // Get agents in this zone (assuming we have a zona_id in agentes table)
        $agentes_in_zone = $this->agente_model->get_agentes_by_zona($gerente->zona_id);
        $agentes_ids = array_column($agentes_in_zone, 'id');
        
        // Get affiliations made by agents in this zone
        $data['afiliaciones'] = $this->afiliacion_model->get_afiliaciones_by_agentes($agentes_ids);
        $data['agentes'] = $agentes_in_zone;
        $data['zone_stats'] = $this->afiliacion_model->get_stats_by_agentes($agentes_ids);
        
        $data['main_content'] = 'dashboard/gerente_zona_dashboard';
        $this->load->view('templates/adminlte_layout', $data);
    }

    /**
     * Prepares data for the Supervisor dashboard.
     */
    private function _supervisor_dashboard($data, $user_id) {
        // Get the agent record for this user
        $supervisor = $this->agente_model->get_agent_by_user_id($user_id);
        
        if (!$supervisor) {
            show_error('No se encontró el registro de supervisor para este usuario.');
            return;
        }
        
        // Get agents supervised by this supervisor
        $agentes_supervised = $this->agente_model->get_agentes_by_supervisor_id($supervisor->id);
        $agentes_ids = array_column($agentes_supervised, 'id');
        
        // Get affiliations made by supervised agents
        $data['afiliaciones'] = $this->afiliacion_model->get_afiliaciones_by_agentes($agentes_ids);
        $data['agentes'] = $agentes_supervised;
        $data['team_stats'] = $this->afiliacion_model->get_stats_by_agentes($agentes_ids);
        
        $data['main_content'] = 'dashboard/supervisor_dashboard';
        $this->load->view('templates/adminlte_layout', $data);
    }

    /**
     * Prepares data for the Agente (Sales Agent) dashboard.
     */
    private function _agente_dashboard($data, $user_id) {
        // Get the agent record for this user
        $agente = $this->agente_model->get_agent_by_user_id($user_id);
        
        if (!$agente) {
            show_error('No se encontró el registro de agente para este usuario.');
            return;
        }
        
        // Get agent's affiliations
        $data['afiliaciones'] = $this->afiliacion_model->get_afiliaciones_by_asesor_id($agente->id);
        $data['agente'] = $agente;
        $data['stats'] = $this->afiliacion_model->get_stats_by_asesor($agente->id);
        $data['recent_afiliaciones'] = $this->afiliacion_model->get_recent_afiliaciones_by_asesor($agente->id, 5);
        
        $data['main_content'] = 'dashboard/agente_dashboard';
        $this->load->view('templates/adminlte_layout', $data);
    }

    /**
     * Prepares data for the Client (standard user) dashboard.
     */
    private function _client_dashboard($data, $user_id) {
        // Get the client's affiliation
        $afiliacion = $this->afiliacion_model->get_afiliacion_by_client_user_id($user_id);
        
        if (!$afiliacion) {
            // If no affiliation found, show a message
            $data['no_afiliacion'] = true;
        } else {
            $data['afiliacion'] = $afiliacion;
            $data['titular'] = $this->afiliacion_model->get_titular_by_afiliacion_id($afiliacion->id);
            $data['familiares'] = $this->afiliacion_model->get_familiares_by_afiliacion_id($afiliacion->id);
            $data['pagos'] = $this->afiliacion_model->get_pagos_by_afiliacion_id($afiliacion->id);
        }
        
        $data['main_content'] = 'dashboard/client_dashboard';
        $this->load->view('templates/adminlte_layout', $data);
    }
}
/* End of file Dashboard.php */
/* Location: ./application/controllers/Dashboard.php */