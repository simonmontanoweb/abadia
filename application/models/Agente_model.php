<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Agente_model extends CI_Model {

    /**
     * @var CI_DB_query_builder The Query Builder instance for DataTables
     */
    public $builder;

    public function __construct() {
        parent::__construct();
        $this->load->database(); // Ensure database is loaded
        $this->set_builder_for_datatables(); // Initialize the builder
    }

    /**
     * Initializes the Query Builder object ($this->builder) for use with TablesIgniter.
     * It includes the necessary SELECT columns and JOINs.
     */
    public function set_builder_for_datatables() {
        // Reset any existing query to start fresh
        $this->db->reset_query();
        
        // Explicitly set the table to agentes
        $this->builder = $this->db
                              ->from('agentes')
                              ->select('agentes.id, agentes.nombres, agentes.apellidos, agentes.cedula, agentes.rif, agentes.correo_electronico, agentes.telefono_celular, cargos.cargo as cargo_nombre, agentes.foto_perfil')
                              ->join('cargos', 'agentes.id_cargo = cargos.id_cargo', 'left')
                              ->where('agentes.deleted_at', NULL); // Only show active agents
    }
	
	/**
	 * Count the total number of active agents
	 * @return int
	 */
	public function count_agentes() {
		$this->db->reset_query();
		$this->db->where('deleted_at', NULL);
		return $this->db->count_all_results('agentes');
	}	

    // ------------------------------------------------------------------------
    // AGENTES CRUD Methods (for create/edit/delete forms)
    // ------------------------------------------------------------------------

    /**
     * Get a single ACTIVE agent by ID
     * @param int $id
     * @param bool $include_user Set to true to fetch user data as well
     * @return object or NULL
     */
    public function get_agent_by_id($id, $include_user = false) {
        // Reset any existing query to start fresh
        $this->db->reset_query();
        
        // Start from agentes table
        $this->db->from('agentes');
        
        // Select basic agent data
        $this->db->select('agentes.*, cargos.cargo as cargo_nombre');
        
        // Join with cargos
        $this->db->join('cargos', 'cargos.id_cargo = agentes.id_cargo', 'left');
        
        // Add conditions
        $this->db->where('agentes.id', $id);
        $this->db->where('agentes.deleted_at', NULL);

        // Optionally include user data
        if ($include_user) {
            $this->db->select('users.id as user_id, users.first_name, users.last_name, users.email, users.active', FALSE);
            $this->db->join('users', 'users.id = agentes.user_id', 'left');
        }

        $query = $this->db->get();
        return $query->row();
    }
    
    /**
     * Insert a new agent
     * @param array $data
     * @return int (insert ID) or false
     */
    public function insert_agent($data) {
        $this->db->reset_query();
        return $this->db->insert('agentes', $data);
    }

    /**
     * Update an existing agent
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update_agent($id, $data) {
        $this->db->reset_query();
        $this->db->where('id', $id);
        return $this->db->update('agentes', $data);
    }

    /**
     * Soft delete an agent
     * @param int $id
     * @param int $user_id The ID of the user performing the deletion
     * @return bool
     */
    public function delete_agent($id, $user_id) {
        $this->db->reset_query();
        $data = [
            'deleted_at' => date('Y-m-d H:i:s'),
            'deleted_by' => $user_id
        ];
        $this->db->where('id', $id);
        return $this->db->update('agentes', $data);
    }

    /**
     * Restore a soft-deleted agent
     * @param int $id
     * @return bool
     */
    public function restore_agent($id) {
        $this->db->reset_query();
        $data = [
            'deleted_at' => NULL,
            'deleted_by' => NULL
        ];
        $this->db->where('id', $id);
        return $this->db->update('agentes', $data);
    }

    /**
     * Provides a query builder object for fetching DELETED agents for DataTables.
     * Includes joins to 'users' to show who deleted the agent.
     */
    public function get_deleted_agents_builder() {
        $this->db->reset_query();
        return $this->db
                     ->from('agentes')
                     ->select('agentes.id, agentes.nombres, agentes.apellidos, agentes.cedula, agentes.deleted_at, u.first_name as deleted_by_firstname, u.last_name as deleted_by_lastname')
                     ->join('users u', 'agentes.deleted_by = u.id', 'left')
                     ->where('agentes.deleted_at IS NOT NULL');
    }

    // ------------------------------------------------------------------------
    // LOOKUP TABLE Methods (for forms)
    // ------------------------------------------------------------------------

    public function get_estados() {
        $this->db->reset_query();
        $this->db->order_by('estado', 'ASC');
        $query = $this->db->get('estados');
        return $query->result();
    }

    public function get_ciudades_by_estado($estado_id) {
        $this->db->reset_query();
        $this->db->where('id_estado', $estado_id);
        $this->db->order_by('ciudad', 'ASC');
        $query = $this->db->get('ciudades');
        return $query->result();
    }

    public function get_municipios_by_estado($estado_id) {
        $this->db->reset_query();
        $this->db->where('id_estado', $estado_id);
        $this->db->order_by('municipio', 'ASC');
        $query = $this->db->get('municipios');
        return $query->result();
    }

    public function get_parroquias_by_municipio($municipio_id) {
        $this->db->reset_query();
        $this->db->where('id_municipio', $municipio_id);
        $this->db->order_by('parroquia', 'ASC');
        $query = $this->db->get('parroquias');
        return $query->result();
    }

    public function get_cargos() {
        $this->db->reset_query();
        $this->db->order_by('cargo', 'ASC');
        $query = $this->db->get('cargos');
        return $query->result();
    }

    // --- Methods for Dashboards ---

	/**
	 * Get all active agents
	 * @return array
	 */
	public function get_all_agentes() {
		$this->db->reset_query();
		$this->db->where('deleted_at', NULL);
		$this->db->order_by('nombres', 'ASC');
		$query = $this->db->get('agentes');
		return $query->result();
	}

    /**
     * Get an agent's record based on their Ion Auth user_id.
     * @param int $user_id
     * @return object or NULL
     */
    public function get_agent_by_user_id($user_id) {
        $this->db->reset_query();
        $this->db->where('user_id', $user_id);
        $this->db->where('deleted_at', NULL); // Ensure agent is active
        $query = $this->db->get('agentes');
        return $query->row();
    }

    /**
     * Get all agents assigned to a specific supervisor.
     * @param int $supervisor_id The ID of the supervisor (from the 'agentes' table)
     * @return array
     */
    public function get_agents_by_supervisor_id($supervisor_id) {
        $this->db->reset_query();
        $this->db->where('supervisor_id', $supervisor_id);
        $this->db->where('deleted_at', NULL);
        $query = $this->db->get('agentes');
        return $query->result();
    }

	/**
	 * Get agents by zone ID
	 * @param int $zona_id
	 * @return array
	 */
	public function get_agentes_by_zona($zona_id) {
		$this->db->reset_query();
		$this->db->where('zona_id', $zona_id);
		$this->db->where('deleted_at', NULL);
		$query = $this->db->get('agentes');
		return $query->result();
	}

	/**
	 * Get top performing agents
	 * @param int $limit
	 * @return array
	 */
	public function get_top_agentes($limit = 5) {
		$this->db->reset_query();
		$this->db->select('agentes.*, COUNT(afiliaciones.id) as total_afiliaciones');
		$this->db->from('agentes');
		$this->db->join('afiliaciones', 'agentes.id = afiliaciones.asesor_id', 'left');
		$this->db->where('agentes.deleted_at', NULL);
		$this->db->group_by('agentes.id');
		$this->db->order_by('total_afiliaciones', 'DESC');
		$this->db->limit($limit);
		$query = $this->db->get();
		return $query->result();
	}
	
}
/* End of file Agente_model.php */
/* Location: ./application/models/Agente_model.php */