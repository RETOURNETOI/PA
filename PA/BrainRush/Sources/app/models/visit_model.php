<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Visit_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Récupère toutes les visites d'un patient
     * @param int $patient_id
     * @return array
     */
    public function get_visits_by_patient($patient_id) {
        $this->db->where('patient_id', $patient_id);
        $this->db->order_by('visit_date', 'DESC');
        return $this->db->get('visits')->result();
    }

    /**
     * Récupère une visite par son ID
     * @param int $visit_id
     * @return object
     */
    public function get_visit_by_id($visit_id) {
        return $this->db->get_where('visits', array('id' => $visit_id))->row();
    }

    /**
     * Crée une nouvelle visite
     * @param array $data
     * @return int
     */
    public function create_visit($data) {
        $this->db->insert('visits', $data);
        return $this->db->insert_id();
    }

    /**
     * Met à jour une visite
     * @param int $visit_id
     * @param array $data
     * @return bool
     */
    public function update_visit($visit_id, $data) {
        $this->db->where('id', $visit_id);
        return $this->db->update('visits', $data);
    }

    /**
     * Supprime une visite
     * @param int $visit_id
     * @return bool
     */
    public function delete_visit($visit_id) {
        $this->db->where('id', $visit_id);
        return $this->db->delete('visits');
    }

    /**
     * Récupère les visites entre deux dates
     * @param string $start_date
     * @param string $end_date
     * @return array
     */
    public function get_visits_between_dates($start_date, $end_date) {
        $this->db->where('visit_date >=', $start_date);
        $this->db->where('visit_date <=', $end_date);
        $this->db->order_by('visit_date', 'ASC');
        return $this->db->get('visits')->result();
    }

    /**
     * Récupère les visites d'un professionnel de santé
     * @param int $professional_id
     * @return array
     */
    public function get_visits_by_professional($professional_id) {
        $this->db->where('professional_id', $professional_id);
        $this->db->order_by('visit_date', 'DESC');
        return $this->db->get('visits')->result();
    }

    /**
     * Récupère les statistiques des visites
     * @return object
     */
    public function get_visit_stats() {
        $stats = new stdClass();
        
        // Nombre total de visites
        $stats->total_visits = $this->db->count_all('visits');
        
        // Visites aujourd'hui
        $today = date('Y-m-d');
        $this->db->where('visit_date', $today);
        $stats->today_visits = $this->db->count_all_results('visits');
        
        // Visites ce mois
        $first_day_month = date('Y-m-01');
        $last_day_month = date('Y-m-t');
        $this->db->where('visit_date >=', $first_day_month);
        $this->db->where('visit_date <=', $last_day_month);
        $stats->month_visits = $this->db->count_all_results('visits');
        
        return $stats;
    }
}