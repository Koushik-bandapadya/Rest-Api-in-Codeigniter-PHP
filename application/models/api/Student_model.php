<?php
// Define a class named Student_model
class Student_model extends CI_Model {

    // Constructor method that loads the CodeIgniter database library
    public function __construct() {
        parent::__construct(); // Calls the parent constructor method
        $this->load->database(); // Loads the CodeIgniter database library
    }

    // Define a method named get_students that retrieves all students from the database
    public function get_students() {
        // SELECT * from tbl_students
        $this->db->select("*"); // Specifies the columns to select in the query
        $this->db->from("tbl_students"); // Specifies the table to select data from

        $query = $this->db->get(); // Executes the query and stores the result in a variable
        // print_r($query->result());
        return $query->result(); // Returns the query result as an array of objects
    }

    // Define a method named insert_student that inserts a new student record into the database
    public function insert_student($data = array()){ 
        return $this->db->insert("tbl_students", $data); // Inserts a new record into the tbl_students table
    }

    public function delete_student($student_id) {
        // Delete student with matching ID from the database
        $this->db->where('id', $student_id);
        return $this->db->delete('tbl_students');
        

    }



    public function update_student_imformation($id, $informations){
$this->db->where("id", $id);
return $this->db->update("tbl students", $informations);
}


}