<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ubicacion_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Estados methods
    public function get_estados_builder() {
        return $this->db->select('id_estado, estado')
                        ->from('estados');
    }

    public function get_estados() {
        return $this->db->get('estados')->result();
    }

    public function get_estado_by_id($id) {
        return $this->db->get_where('estados', array('id_estado' => $id))->row();
    }

    public function insert_estado($data) {
        return $this->db->insert('estados', $data);
    }

    public function update_estado($id, $data) {
        $this->db->where('id_estado', $id);
        return $this->db->update('estados', $data);
    }

    public function delete_estado($id) {
        $this->db->where('id_estado', $id);
        return $this->db->delete('estados');
    }

    // Ciudades methods
    public function get_ciudades_builder() {
        return $this->db->select('c.id_ciudad, c.ciudad, e.estado')
                        ->from('ciudades c')
                        ->join('estados e', 'c.id_estado = e.id_estado', 'left');
    }

    public function get_all_ciudades() {
        return $this->db->get('ciudades')->result();
    }

    public function get_ciudad_by_id($id) {
        return $this->db->get_where('ciudades', array('id_ciudad' => $id))->row();
    }

    public function insert_ciudad($data) {
        return $this->db->insert('ciudades', $data);
    }

    public function update_ciudad($id, $data) {
        $this->db->where('id_ciudad', $id);
        return $this->db->update('ciudades', $data);
    }

    public function delete_ciudad($id) {
        $this->db->where('id_ciudad', $id);
        return $this->db->delete('ciudades');
    }

    // Municipios methods
    public function get_municipios_builder() {
        return $this->db->select('m.id_municipio, m.municipio, e.estado')
                        ->from('municipios m')
                        ->join('estados e', 'm.id_estado = e.id_estado', 'left');
    }

    public function get_all_municipios() {
        return $this->db->get('municipios')->result();
    }

    public function get_municipio_by_id($id) {
        return $this->db->get_where('municipios', array('id_municipio' => $id))->row();
    }

    public function insert_municipio($data) {
        return $this->db->insert('municipios', $data);
    }

    public function update_municipio($id, $data) {
        $this->db->where('id_municipio', $id);
        return $this->db->update('municipios', $data);
    }

    public function delete_municipio($id) {
        $this->db->where('id_municipio', $id);
        return $this->db->delete('municipios');
    }

    // Parroquias methods
    public function get_parroquias_builder() {
        return $this->db->select('p.id_parroquia, p.parroquia, m.municipio, e.estado')
                        ->from('parroquias p')
                        ->join('municipios m', 'p.id_municipio = m.id_municipio', 'left')
                        ->join('estados e', 'm.id_estado = e.id_estado', 'left');
    }

    public function get_all_parroquias() {
        return $this->db->get('parroquias')->result();
    }

    public function get_parroquia_by_id($id) {
        return $this->db->get_where('parroquias', array('id_parroquia' => $id))->row();
    }

    public function insert_parroquia($data) {
        return $this->db->insert('parroquias', $data);
    }

    public function update_parroquia($id, $data) {
        $this->db->where('id_parroquia', $id);
        return $this->db->update('parroquias', $data);
    }

    public function delete_parroquia($id) {
        $this->db->where('id_parroquia', $id);
        return $this->db->delete('parroquias');
    }

    // Helper methods
    public function get_municipios_by_estado($estado_id) {
        return $this->db->get_where('municipios', array('id_estado' => $estado_id))->result();
    }

    public function get_parroquias_by_municipio($municipio_id) {
        return $this->db->get_where('parroquias', array('id_municipio' => $municipio_id))->result();
    }
}
/* End of file Ubicacion_model.php */
/* Location: ./application/models/Ubicacion_model.php */