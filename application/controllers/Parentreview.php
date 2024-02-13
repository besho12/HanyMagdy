<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @package : Ramom school management system
 * @version : 6.0
 * @developed by : RamomCoder
 * @support : ramomcoder@yahoo.com
 * @author url : http://codecanyon.net/user/RamomCoder
 * @filename : Parents.php
 * @copyright : Reserved RamomCoder Team
 */

class Parentreview extends MY_Controller 
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('qrcode_attendance_model');
    }

    public function index($studentID = '')
    {                
        if($_POST['student_code']){
            redirect(base_url('parentreview/index/' . $_POST['student_code']));
        }        
        $this->data['marks'] = $this->get_student_marks($studentID);
        if($studentID){
            $this->load->view('parents/parentview', $this->data);    
        } else {
            $this->load->view('parents/parentviewsearch');
        }
    }

    public function get_student_marks($studentID){
        $student_id = $this->getStudentIDByBarcode2($studentID);   
        $this->db->select('mark.*, student.first_name, student.last_name, student.register_no, timetable_exam.exam_date as exam_date, exam.id as exam_id, exam_period, exam.name as exam_name');
        $this->db->from('mark');
        $this->db->where('student_id', $student_id);
        $this->db->join('student', 'student.id = mark.student_id', 'left');
        $this->db->join('exam', 'exam.id = mark.exam_id', 'left');
        $this->db->join('timetable_exam', 'timetable_exam.exam_id = exam.id', 'left'); // Corrected join condition

        $this->db->order_by('mark.id', 'ASC');
        $result = $this->db->get()->result_array();
        return $result;
    }


    public function getStudentIDByBarcode2($barcode = '')
    {
        $this->db->select('id');
        $this->db->from('student');
        $this->db->where('register_no', $barcode);
        $row = $this->db->get()->row();
        return $row->id;
    }
}
