<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Afiliaciones extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Afiliacion_model');
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language']);
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
    }

    public function index() {
        $data['title'] = 'Listado de Afiliaciones';
        $data['main_content'] = 'afiliaciones/index';
        $this->load->view('templates/adminlte_layout', $data);
    }

    public function create() {
        $data['title'] = 'Formulario de Afiliación';
        $data['main_content'] = 'afiliaciones/create';
        $this->load->view('templates/adminlte_layout', $data);
    }

    public function store() {
        $this->form_validation->set_rules('titular[nombres]', 'Nombres', 'required');
        $this->form_validation->set_rules('titular[apellidos]', 'Apellidos', 'required');
        $this->form_validation->set_rules('titular[cedula]', 'Cédula', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('afiliaciones/create');
        } else {
            $titular_data = $this->input->post('titular');
            $afiliacion_data = [
                'contract_number' => $this->input->post('contract_number'),
                'fecha'           => date('Y-m-d'),
                'asesor_id'       => $this->input->post('asesor_id'),
                'plan_id'         => $this->input->post('plan_id'),
                'plan_amount'     => $this->input->post('plan_amount'),
                'cuotas'          => $this->input->post('cuotas'),
                'created_by'      => $this->ion_auth->user()->row()->id,
                'estatus'         => 1
            ];
            $familiares_data = $this->input->post('familiares');
            $pago_data = [
                'payment_type'   => $this->input->post('payment_type'),
                'banco_id'       => $this->input->post('banco_id'),
                'account_number' => $this->input->post('account_number'),
                'account_type'   => $this->input->post('account_type'),
                'created_by'     => $this->ion_auth->user()->row()->id
            ];
            $observaciones_data = [
                'observacion' => $this->input->post('observaciones')
            ];

            $afiliacion_id = $this->Afiliacion_model->save_afiliacion(
                $afiliacion_data, $titular_data, $familiares_data, $pago_data, $observaciones_data
            );

            if ($afiliacion_id) {
                $this->session->set_flashdata('message', 'Afiliación registrada correctamente.');
                redirect('afiliaciones');
            } else {
                $this->session->set_flashdata('error', 'Error al registrar afiliación.');
                redirect('afiliaciones/create');
            }
        }
    }

    public function view($id) {
        $afiliacion = $this->Afiliacion_model->get_afiliacion_by_id($id);
        if (!$afiliacion) {
            show_404();
            return;
        }
        $data['title'] = 'Detalles de Afiliación';
        $data['afiliacion'] = $afiliacion;
        $data['main_content'] = 'afiliaciones/view';
        $this->load->view('templates/adminlte_layout', $data);
    }

    // Add edit/update/delete as needed!
}