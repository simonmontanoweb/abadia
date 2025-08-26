<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language']);
        
        // All methods in this controller require a login
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
    }

    public function index() {
        $user = $this->ion_auth->user()->row();
        
        $data['title'] = 'Mi Perfil';
        $data['breadcrumbs'] = [
            ['label' => 'Inicio', 'url' => 'dashboard'],
            ['label' => 'Mi Perfil', 'url' => '']
        ];
        $data['user'] = $user;
        $data['main_content'] = 'profile/index';
        $this->load->view('templates/adminlte_layout', $data);
    }

    public function edit() {
        $user = $this->ion_auth->user()->row();
        
        $data['title'] = 'Editar Perfil';
        $data['breadcrumbs'] = [
            ['label' => 'Inicio', 'url' => 'dashboard'],
            ['label' => 'Mi Perfil', 'url' => 'profile'],
            ['label' => 'Editar', 'url' => '']
        ];
        $data['user'] = $user;
        $data['main_content'] = 'profile/edit';
        $this->load->view('templates/adminlte_layout', $data);
    }

    public function update() {
        $user = $this->ion_auth->user()->row();
        $user_id = $user->id;
        
        $this->form_validation->set_rules('first_name', 'Nombre', 'trim|required');
        $this->form_validation->set_rules('last_name', 'Apellido', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|callback_email_check['.$user_id.']');
        $this->form_validation->set_rules('phone', 'Teléfono', 'trim');
        $this->form_validation->set_rules('company', 'Empresa', 'trim');
        
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('profile/edit', 'refresh');
        } else {
            $data = [
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'email' => $this->input->post('email'),
                'phone' => $this->input->post('phone'),
                'company' => $this->input->post('company')
            ];
            
            // Handle file upload for profile picture
            if (!empty($_FILES['profile_picture']['name'])) {
                $upload_path = './uploads/profile_pictures/';
                if (!is_dir($upload_path)) {
                    mkdir($upload_path, 0755, TRUE);
                }
                $config['upload_path'] = $upload_path;
                $config['allowed_types'] = 'gif|jpg|jpeg|png';
                $config['max_size'] = '2048';
                $config['file_name'] = 'user_' . $user_id . '_' . time();
                $config['overwrite'] = TRUE;
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                
                if ($this->upload->do_upload('profile_picture')) {
                    $upload_data = $this->upload->data();
                    $data['profile_picture'] = $upload_path . $upload_data['file_name'];
                } else {
                    $this->session->set_flashdata('error', $this->upload->display_errors());
                    redirect('profile/edit', 'refresh');
                    return;
                }
            }
            
            if ($this->ion_auth->update($user_id, $data)) {
                $this->session->set_flashdata('message', 'Perfil actualizado exitosamente.');
                redirect('profile', 'refresh');
            } else {
                $this->session->set_flashdata('error', 'Error al actualizar el perfil.');
                redirect('profile/edit', 'refresh');
            }
        }
    }
    
    // Custom validation callback for email uniqueness
    public function email_check($email, $user_id) {
        $this->db->where('email', $email);
        $this->db->where('id !=', $user_id);
        $query = $this->db->get('users');
        
        if ($query->num_rows() > 0) {
            $this->form_validation->set_message('email_check', 'El {field} ya está en uso por otro usuario.');
            return FALSE;
        }
        
        return TRUE;
    }
}
/* End of file Profile.php */
/* Location: ./application/controllers/Profile.php */