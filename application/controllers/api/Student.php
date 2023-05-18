<?php
// Require REST_Controller class from the library directory
require APPPATH . 'libraries/REST_Controller.php';

// Define Student class which extends REST_Controller class
class Student extends REST_Controller {

    // Constructor function, which is called when a Student object is created
    public function __construct() {
        // Call the parent class constructor
        parent::__construct();
        // Load the student_model
        $this->load->model('api/student_model');
        $this->load->library( 'form_validation');
    }

    // POST request handler for the URL <project url>/index.php/student
    public function index_post() {
        // Get JSON data from the request body and decode it into a PHP object
        // $request_data = json_decode($this->input->raw_input_stream);
    
            // collecting form data inputs
            $name = $this->input->post("name");
            $email = $this->input->post("email"); 
            $mobile = $this->input->post("mobile"); 
            $course = $this->input->post("course");  
    
        // Form validation rules
        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required');
        $this->form_validation->set_rules('mobile', 'Mobile', 'required');
        $this->form_validation->set_rules('course', 'Course', 'required');
    
        // Validate form data
        if ($this->form_validation->run() === FALSE) {
            // If form validation fails, send an error response with a status code of 400
            $this->response(array(
                'status' => 0,
                'message' => validation_errors()
            ), REST_Controller::HTTP_BAD_REQUEST);
        } else {
            // If all fields are not empty
            if (!empty($name) && !empty($email) && !empty($mobile) && !empty($course)) {
                // Create an array of student data
                $student = array(
                    'name' => $name,
                    'email' => $email,
                    'mobile' => $mobile,
                    'course' => $course
                );
    
                // Insert the student data into the database using the student_model's insert_student method
                if ($this->student_model->insert_student($student)) {
                    // If successful, send a success response with a status code of 201
                    $this->response(array(
                        'status' => 1,
                        'message' => 'Student has been created',
                        'data' => $student
                    ), REST_Controller::HTTP_CREATED);
                } else {
                    // If unsuccessful, send an error response with a status code of 500
                    $this->response(array(
                        'status' => 0,
                        'message' => 'Student creation failed'
                    ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                }
            } else {
                // If any field is empty, send a not found error response with a status code of 400
                $this->response(array(
                    'status' => 0,
                    'message' => 'All fields are required'
                ), REST_Controller::HTTP_BAD_REQUEST);
            }
        }
    }
    
        

        
      
    

    // PUT request handler for the URL <project url>/index.php/student
    public function index_put() {
        // Decode the JSON data received in the request payload
        $data = json_decode(file_get_contents("php://input"));
    
        if (!empty($data->id) && !empty($data->name) && !empty($data->email) && !empty($data->mobile) && !empty($data->course)) {
    
            $student_id = $data->id;
            $student_info = array(
                "name" => $data->name,
                "email" => $data->email,
                "mobile" => $data->mobile,
                "course" => $data->course,
            );
    
            if ($this->Student_model->update_student_information($student_id, $student_info)) {
                // Send a success response with a status code of 200
                $this->response(array(
                    'status' => 1,
                    'message' => 'Updated'
                ), REST_Controller::HTTP_OK);
            } else {
                // Send a bad request error response with a status code of 400
                $this->response(array(
                    'status' => 0,
                    'message' => 'Update failed'
                ), REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {
            // Send a bad request error response with a status code of 400
            $this->response(array(
                'status' => 0,
                'message' => 'All fields are required'
            ), REST_Controller::HTTP_BAD_REQUEST);
        }
    }
    
    












    public function index_delete() {
        // Decode the JSON data received in the request payload
        $data = json_decode(file_get_contents("php://input"));
        // Get the student_id from the decoded data
        $student_id = $data->student_id;
        print_r($student_id);
    
        // Attempt to delete the student from the database using the student_model
        if ($this->student_model->delete_student($student_id)) {
            // If the deletion was successful, send a success response with a status code of 201
            $this->response(array(
                'status' => 1,
                'message' => 'Student has been deleted successfully',
            ), REST_Controller::HTTP_CREATED);
        } else {
            // If the deletion failed, send a not found error response with a status code of 404
            $this->response(array(
                'status' => 0,
                'message' => 'Not Found'
            ), REST_Controller::HTTP_NOT_FOUND);
        }
    }
    


    // GET: <project url>/index.php/student
    public function index_get() {
        // get data method 
        // This function handles the GET request sent to <project url>/index.php/student
        // It retrieves all the student records from the database and returns them in JSON format
    
        $students = $this->student_model->get_students(); // Call the get_students method of the student_model object to retrieve all student records
    
        // Check if any records were found
        if (count($students) > 0) {
            // If at least one record was found, return a JSON response with status = 1, message = "Students found", and data = $students
            $this->response(array( 
                "status" => 1, 
                "message" => "Students found",
                "data" => $students 
            ), REST_Controller::HTTP_OK);
        } else {
            // If no records were found, return a JSON response with status = 0, message = "Students not found", and data = $students (which will be an empty array)
            $this->response(array( 
                "status" => 0, 
                "message" => "Students not found",
                "data" => $students 
            ), REST_Controller::HTTP_NOT_FOUND);
        }
    }
    
}