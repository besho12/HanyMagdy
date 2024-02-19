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
        if($studentID){
            $student = $this->getStudentByBarcode2($studentID); 
            if($student){
                $this->data['marks'] = $this->get_student_marks($student->id);
                $this->data['student'] = $student;

                $this->load->view('parents/parentview', $this->data);    
            } else {
                $this->load->view('parents/parentviewsearch');
            }
        } else {
            $this->load->view('parents/parentviewsearch');
        }
    }

    public function get_student_marks($studentID){
        $this->db->select('mark.*, student.first_name, student.last_name, student.register_no, timetable_exam.exam_date as exam_date, exam.id as exam_id, exam_period, exam.name as exam_name');
        $this->db->from('mark');
        $this->db->where('student_id', $studentID);
        $this->db->join('student', 'student.id = mark.student_id', 'left');
        $this->db->join('exam', 'exam.id = mark.exam_id', 'left');
        $this->db->join('timetable_exam', 'timetable_exam.exam_id = exam.id', 'left'); // Corrected join condition

        $this->db->order_by('mark.id', 'ASC');
        $result = $this->db->get()->result_array();
        return $result;
    }


    public function getStudentByBarcode2($barcode = '')
    {
        $this->db->select('s.*,c.name as class_name,se.name as section_name');
        $this->db->from('student as s');
        $this->db->join('class as c', 's.class_id = c.id', 'left');
        $this->db->join('section as se', 's.section_id = se.id', 'left');
        $this->db->where('register_no', $barcode);
        return $this->db->get()->row();
        
    }
}
