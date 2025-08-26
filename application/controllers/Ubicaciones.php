<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ubicaciones extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('ubicacion_model');
        $this->load->library(['ion_auth', 'form_validation', 'TablesIgniterCI3']);
        $this->load->helper(['url', 'language']);

        // Only admins can access this controller
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth/login', 'refresh');
        }
    }

    public function index() {
        $data['title'] = 'Gestión de Ubicaciones Geográficas';
        $data['breadcrumbs'] = [
            ['label' => 'Inicio', 'url' => 'dashboard'],
            ['label' => 'Ubicaciones', 'url' => '']
        ];
        $data['main_content'] = 'ubicaciones/index';
        $this->load->view('templates/adminlte_layout', $data);
    }

    public function estados() {
        $data['title'] = 'Gestión de Estados';
        $data['breadcrumbs'] = [
            ['label' => 'Inicio', 'url' => 'dashboard'],
            ['label' => 'Ubicaciones', 'url' => 'ubicaciones'],
            ['label' => 'Estados', 'url' => '']
        ];
        $data['main_content'] = 'ubicaciones/estados';
        $this->load->view('templates/adminlte_layout', $data);
    }

    public function ciudades() {
        $data['title'] = 'Gestión de Ciudades';
        $data['breadcrumbs'] = [
            ['label' => 'Inicio', 'url' => 'dashboard'],
            ['label' => 'Ubicaciones', 'url' => 'ubicaciones'],
            ['label' => 'Ciudades', 'url' => '']
        ];
        $data['estados'] = $this->ubicacion_model->get_estados();
        $data['main_content'] = 'ubicaciones/ciudades';
        $this->load->view('templates/adminlte_layout', $data);
    }

    public function municipios() {
        $data['title'] = 'Gestión de Municipios';
        $data['breadcrumbs'] = [
            ['label' => 'Inicio', 'url' => 'dashboard'],
            ['label' => 'Ubicaciones', 'url' => 'ubicaciones'],
            ['label' => 'Municipios', 'url' => '']
        ];
        $data['estados'] = $this->ubicacion_model->get_estados();
        $data['main_content'] = 'ubicaciones/municipios';
        $this->load->view('templates/adminlte_layout', $data);
    }

    public function parroquias() {
        $data['title'] = 'Gestión de Parroquias';
        $data['breadcrumbs'] = [
            ['label' => 'Inicio', 'url' => 'dashboard'],
            ['label' => 'Ubicaciones', 'url' => 'ubicaciones'],
            ['label' => 'Parroquias', 'url' => '']
        ];
        $data['estados'] = $this->ubicacion_model->get_estados();
        $data['main_content'] = 'ubicaciones/parroquias';
        $this->load->view('templates/adminlte_layout', $data);
    }

    // API methods for DataTables
	
	public function get_estados_list() {
		$this->load->library('TablesIgniterCI3_fixed', NULL, 'table');
		$this->load->model('Ubicacion_model','model');
		
		$builder = $this->model->get_estados_builder();
		$this->table->setBuilder($builder);
		$this->table->setSearch(['estado']);
		$this->table->setDefaultOrder("id_estado", "ASC");
		$this->table->setOrder([
			0 => 'id_estado',
			1 => 'estado'
		]);
		$this->table->setOutput([
			'id_estado',
			'estado',
			'actions' => function($row) {
				$edit_url = site_url('ubicaciones/edit_estado/'.$row['id_estado']);
				
				$actions = '<div class="btn-group">';
				$actions .= '<a href="'.$edit_url.'" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>';
				$actions .= '<button class="btn btn-sm btn-danger delete-estado" data-id="'.$row['id_estado'].'" data-name="'.$row['estado'].'"><i class="fas fa-trash"></i></button>';
				$actions .= '</div>';
				
				return $actions;
			}
		]);
		
		echo $this->table->getDatatable();
	}


 public function get_ciudades_list() {
    $this->load->library('TablesIgniterCI3', NULL, 'table');
    $this->load->model('Ubicacion_model','model');
    
    $builder = $this->model->get_ciudades_builder();
    $this->table->setBuilder($builder);
    $this->table->setSearch(['ciudad', 'estado']);
    $this->table->setDefaultOrder("id_ciudad", "ASC");
    $this->table->setOrder([
        0 => 'id_ciudad',
        1 => 'ciudad',
        2 => 'estado'
    ]);
    $this->table->setOutput([
        'id_ciudad',
        'ciudad',
        'estado',
        'actions' => function($row) {
            $edit_url = site_url('ubicaciones/edit_ciudad/'.$row['id_ciudad']);
            
            $actions = '<div class="btn-group">';
            $actions .= '<a href="'.$edit_url.'" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>';
            $actions .= '<button class="btn btn-sm btn-danger delete-ciudad" data-id="'.$row['id_ciudad'].'" data-name="'.$row['ciudad'].'"><i class="fas fa-trash"></i></button>';
            $actions .= '</div>';
            
            return $actions;
        }
    ]);
    
    echo $this->table->getDatatable();
}

public function get_municipios_list() {
    $this->load->library('TablesIgniterCI3', NULL, 'table');
    $this->load->model('Ubicacion_model','model');
    
    $builder = $this->model->get_municipios_builder();
    $this->table->setBuilder($builder);
    $this->table->setSearch(['municipio', 'estado']);
    $this->table->setDefaultOrder("id_municipio", "ASC");
    $this->table->setOrder([
        0 => 'id_municipio',
        1 => 'municipio',
        2 => 'estado'
    ]);
    $this->table->setOutput([
        'id_municipio',
        'municipio',
        'estado',
        'actions' => function($row) {
            $edit_url = site_url('ubicaciones/edit_municipio/'.$row['id_municipio']);
            
            $actions = '<div class="btn-group">';
            $actions .= '<a href="'.$edit_url.'" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>';
            $actions .= '<button class="btn btn-sm btn-danger delete-municipio" data-id="'.$row['id_municipio'].'" data-name="'.$row['municipio'].'"><i class="fas fa-trash"></i></button>';
            $actions .= '</div>';
            
            return $actions;
        }
    ]);
    
    echo $this->table->getDatatable();
}

public function get_parroquias_list() {
    $this->load->library('TablesIgniterCI3', NULL, 'table');
    $this->load->model('Ubicacion_model','model');
    
    $builder = $this->model->get_parroquias_builder();
    $this->table->setBuilder($builder);
    $this->table->setSearch(['parroquia', 'municipio', 'estado']);
    $this->table->setDefaultOrder("id_parroquia", "ASC");
    $this->table->setOrder([
        0 => 'id_parroquia',
        1 => 'parroquia',
        2 => 'municipio',
        3 => 'estado'
    ]);
    $this->table->setOutput([
        'id_parroquia',
        'parroquia',
        'municipio',
        'estado',
        'actions' => function($row) {
            $edit_url = site_url('ubicaciones/edit_parroquia/'.$row['id_parroquia']);
            
            $actions = '<div class="btn-group">';
            $actions .= '<a href="'.$edit_url.'" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>';
            $actions .= '<button class="btn btn-sm btn-danger delete-parroquia" data-id="'.$row['id_parroquia'].'" data-name="'.$row['parroquia'].'"><i class="fas fa-trash"></i></button>';
            $actions .= '</div>';
            
            return $actions;
        }
    ]);
    
    echo $this->table->getDatatable();
}	

    // CRUD operations for estados
    public function add_estado() {
        $this->form_validation->set_rules('estado', 'Estado', 'required|trim|max_length[100]');
        
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('ubicaciones/estados', 'refresh');
        } else {
            $data = ['estado' => $this->input->post('estado')];
            
            if ($this->ubicacion_model->insert_estado($data)) {
                $this->session->set_flashdata('message', 'Estado agregado exitosamente.');
            } else {
                $this->session->set_flashdata('error', 'Error al agregar el estado.');
            }
            
            redirect('ubicaciones/estados', 'refresh');
        }
    }

    public function edit_estado($id) {
        $data['title'] = 'Editar Estado';
        $data['breadcrumbs'] = [
            ['label' => 'Inicio', 'url' => 'dashboard'],
            ['label' => 'Ubicaciones', 'url' => 'ubicaciones'],
            ['label' => 'Estados', 'url' => 'ubicaciones/estados'],
            ['label' => 'Editar', 'url' => '']
        ];
        $data['estado'] = $this->ubicacion_model->get_estado_by_id($id);
        
        if (!$data['estado']) {
            show_404();
        }
        
        $data['main_content'] = 'ubicaciones/edit_estado';
        $this->load->view('templates/adminlte_layout', $data);
    }

    public function update_estado($id) {
        $this->form_validation->set_rules('estado', 'Estado', 'required|trim|max_length[100]');
        
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('ubicaciones/edit_estado/'.$id, 'refresh');
        } else {
            $data = ['estado' => $this->input->post('estado')];
            
            if ($this->ubicacion_model->update_estado($id, $data)) {
                $this->session->set_flashdata('message', 'Estado actualizado exitosamente.');
            } else {
                $this->session->set_flashdata('error', 'Error al actualizar el estado.');
            }
            
            redirect('ubicaciones/estados', 'refresh');
        }
    }

    public function delete_estado($id) {
        if (!$this->input->is_ajax_request() || $this->input->method() !== 'post') {
            $this->output->set_status_header(400)->set_output(json_encode(['success' => false, 'message' => 'Método de solicitud no válido.']));
            return;
        }

        $this->output->set_content_type('application/json');
        
        if ($this->ubicacion_model->delete_estado($id)) {
            echo json_encode(['success' => true, 'message' => 'Estado eliminado exitosamente.']);
        } else {
            $this->output->set_status_header(500)->set_output(json_encode(['success' => false, 'message' => 'Error al eliminar el estado.']));
        }
    }

    // CRUD operations for ciudades
    public function add_ciudad() {
        $this->form_validation->set_rules('ciudad', 'Ciudad', 'required|trim|max_length[100]');
        $this->form_validation->set_rules('id_estado', 'Estado', 'required|integer');
        
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('ubicaciones/ciudades', 'refresh');
        } else {
            $data = [
                'ciudad' => $this->input->post('ciudad'),
                'id_estado' => $this->input->post('id_estado')
            ];
            
            if ($this->ubicacion_model->insert_ciudad($data)) {
                $this->session->set_flashdata('message', 'Ciudad agregada exitosamente.');
            } else {
                $this->session->set_flashdata('error', 'Error al agregar la ciudad.');
            }
            
            redirect('ubicaciones/ciudades', 'refresh');
        }
    }

    public function edit_ciudad($id) {
        $data['title'] = 'Editar Ciudad';
        $data['breadcrumbs'] = [
            ['label' => 'Inicio', 'url' => 'dashboard'],
            ['label' => 'Ubicaciones', 'url' => 'ubicaciones'],
            ['label' => 'Ciudades', 'url' => 'ubicaciones/ciudades'],
            ['label' => 'Editar', 'url' => '']
        ];
        $data['ciudad'] = $this->ubicacion_model->get_ciudad_by_id($id);
        $data['estados'] = $this->ubicacion_model->get_estados();
        
        if (!$data['ciudad']) {
            show_404();
        }
        
        $data['main_content'] = 'ubicaciones/edit_ciudad';
        $this->load->view('templates/adminlte_layout', $data);
    }

    public function update_ciudad($id) {
        $this->form_validation->set_rules('ciudad', 'Ciudad', 'required|trim|max_length[100]');
        $this->form_validation->set_rules('id_estado', 'Estado', 'required|integer');
        
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('ubicaciones/edit_ciudad/'.$id, 'refresh');
        } else {
            $data = [
                'ciudad' => $this->input->post('ciudad'),
                'id_estado' => $this->input->post('id_estado')
            ];
            
            if ($this->ubicacion_model->update_ciudad($id, $data)) {
                $this->session->set_flashdata('message', 'Ciudad actualizada exitosamente.');
            } else {
                $this->session->set_flashdata('error', 'Error al actualizar la ciudad.');
            }
            
            redirect('ubicaciones/ciudades', 'refresh');
        }
    }

    public function delete_ciudad($id) {
        if (!$this->input->is_ajax_request() || $this->input->method() !== 'post') {
            $this->output->set_status_header(400)->set_output(json_encode(['success' => false, 'message' => 'Método de solicitud no válido.']));
            return;
        }

        $this->output->set_content_type('application/json');
        
        if ($this->ubicacion_model->delete_ciudad($id)) {
            echo json_encode(['success' => true, 'message' => 'Ciudad eliminada exitosamente.']);
        } else {
            $this->output->set_status_header(500)->set_output(json_encode(['success' => false, 'message' => 'Error al eliminar la ciudad.']));
        }
    }

    // CRUD operations for municipios
    public function add_municipio() {
        $this->form_validation->set_rules('municipio', 'Municipio', 'required|trim|max_length[100]');
        $this->form_validation->set_rules('id_estado', 'Estado', 'required|integer');
        
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('ubicaciones/municipios', 'refresh');
        } else {
            $data = [
                'municipio' => $this->input->post('municipio'),
                'id_estado' => $this->input->post('id_estado')
            ];
            
            if ($this->ubicacion_model->insert_municipio($data)) {
                $this->session->set_flashdata('message', 'Municipio agregado exitosamente.');
            } else {
                $this->session->set_flashdata('error', 'Error al agregar el municipio.');
            }
            
            redirect('ubicaciones/municipios', 'refresh');
        }
    }

    public function edit_municipio($id) {
        $data['title'] = 'Editar Municipio';
        $data['breadcrumbs'] = [
            ['label' => 'Inicio', 'url' => 'dashboard'],
            ['label' => 'Ubicaciones', 'url' => 'ubicaciones'],
            ['label' => 'Municipios', 'url' => 'ubicaciones/municipios'],
            ['label' => 'Editar', 'url' => '']
        ];
        $data['municipio'] = $this->ubicacion_model->get_municipio_by_id($id);
        $data['estados'] = $this->ubicacion_model->get_estados();
        
        if (!$data['municipio']) {
            show_404();
        }
        
        $data['main_content'] = 'ubicaciones/edit_municipio';
        $this->load->view('templates/adminlte_layout', $data);
    }

    public function update_municipio($id) {
        $this->form_validation->set_rules('municipio', 'Municipio', 'required|trim|max_length[100]');
        $this->form_validation->set_rules('id_estado', 'Estado', 'required|integer');
        
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('ubicaciones/edit_municipio/'.$id, 'refresh');
        } else {
            $data = [
                'municipio' => $this->input->post('municipio'),
                'id_estado' => $this->input->post('id_estado')
            ];
            
            if ($this->ubicacion_model->update_municipio($id, $data)) {
                $this->session->set_flashdata('message', 'Municipio actualizado exitosamente.');
            } else {
                $this->session->set_flashdata('error', 'Error al actualizar el municipio.');
            }
            
            redirect('ubicaciones/municipios', 'refresh');
        }
    }

    public function delete_municipio($id) {
        if (!$this->input->is_ajax_request() || $this->input->method() !== 'post') {
            $this->output->set_status_header(400)->set_output(json_encode(['success' => false, 'message' => 'Método de solicitud no válido.']));
            return;
        }

        $this->output->set_content_type('application/json');
        
        if ($this->ubicacion_model->delete_municipio($id)) {
            echo json_encode(['success' => true, 'message' => 'Municipio eliminado exitosamente.']);
        } else {
            $this->output->set_status_header(500)->set_output(json_encode(['success' => false, 'message' => 'Error al eliminar el municipio.']));
        }
    }

    // CRUD operations for parroquias
    public function add_parroquia() {
        $this->form_validation->set_rules('parroquia', 'Parroquia', 'required|trim|max_length[100]');
        $this->form_validation->set_rules('id_municipio', 'Municipio', 'required|integer');
        
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('ubicaciones/parroquias', 'refresh');
        } else {
            $data = [
                'parroquia' => $this->input->post('parroquia'),
                'id_municipio' => $this->input->post('id_municipio')
            ];
            
            if ($this->ubicacion_model->insert_parroquia($data)) {
                $this->session->set_flashdata('message', 'Parroquia agregada exitosamente.');
            } else {
                $this->session->set_flashdata('error', 'Error al agregar la parroquia.');
            }
            
            redirect('ubicaciones/parroquias', 'refresh');
        }
    }

    public function edit_parroquia($id) {
        $data['title'] = 'Editar Parroquia';
        $data['breadcrumbs'] = [
            ['label' => 'Inicio', 'url' => 'dashboard'],
            ['label' => 'Ubicaciones', 'url' => 'ubicaciones'],
            ['label' => 'Parroquias', 'url' => 'ubicaciones/parroquias'],
            ['label' => 'Editar', 'url' => '']
        ];
        $data['parroquia'] = $this->ubicacion_model->get_parroquia_by_id($id);
        $data['estados'] = $this->ubicacion_model->get_estados();
        
        if (!$data['parroquia']) {
            show_404();
        }
        
        $data['main_content'] = 'ubicaciones/edit_parroquia';
        $this->load->view('templates/adminlte_layout', $data);
    }

    public function update_parroquia($id) {
        $this->form_validation->set_rules('parroquia', 'Parroquia', 'required|trim|max_length[100]');
        $this->form_validation->set_rules('id_municipio', 'Municipio', 'required|integer');
        
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('ubicaciones/edit_parroquia/'.$id, 'refresh');
        } else {
            $data = [
                'parroquia' => $this->input->post('parroquia'),
                'id_municipio' => $this->input->post('id_municipio')
            ];
            
            if ($this->ubicacion_model->update_parroquia($id, $data)) {
                $this->session->set_flashdata('message', 'Parroquia actualizada exitosamente.');
            } else {
                $this->session->set_flashdata('error', 'Error al actualizar la parroquia.');
            }
            
            redirect('ubicaciones/parroquias', 'refresh');
        }
    }

    public function delete_parroquia($id) {
        if (!$this->input->is_ajax_request() || $this->input->method() !== 'post') {
            $this->output->set_status_header(400)->set_output(json_encode(['success' => false, 'message' => 'Método de solicitud no válido.']));
            return;
        }

        $this->output->set_content_type('application/json');
        
        if ($this->ubicacion_model->delete_parroquia($id)) {
            echo json_encode(['success' => true, 'message' => 'Parroquia eliminada exitosamente.']);
        } else {
            $this->output->set_status_header(500)->set_output(json_encode(['success' => false, 'message' => 'Error al eliminar la parroquia.']));
        }
    }

    // API method to get municipios by estado
    public function get_municipios_by_estado() {
        $estado_id = $this->input->post('estado_id');
        $municipios = [];
        
        if ($estado_id) {
            $municipios = $this->ubicacion_model->get_municipios_by_estado($estado_id);
        }
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($municipios));
    }

    // API method to get parroquias by municipio
    public function get_parroquias_by_municipio() {
        $municipio_id = $this->input->post('municipio_id');
        $parroquias = [];
        
        if ($municipio_id) {
            $parroquias = $this->ubicacion_model->get_parroquias_by_municipio($municipio_id);
        }
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($parroquias));
    }
	
}
/* End of file Ubicaciones.php */
/* Location: ./application/controllers/Ubicaciones.php */


