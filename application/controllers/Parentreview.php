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

class parentreview extends MY_Controller 
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('qrcode_attendance_model');
    }

    public function prepareWhatsappData($branch_id,$section_id){
        $this->db->select('section.*');
        $this->db->from('section');
        $this->db->where('id', $section_id);
        $result = $this->db->get()->row();

        $data = []; $i = 0;
        if($result->name == 'الصف الاول') {
            $data[] = ['name'=>'سنتر K الصف الاول الثانوي','link'=>'https://chat.whatsapp.com/GT5Z6THbvG0H1Cv17s6zdq'];
            $data[] = ['name'=>'سنتر دار السعاده الصف الاول الثانوي','https://chat.whatsapp.com/KHga75ihtc2BC2SZhm3bxW'];
            $data[] = ['name'=>'سنتر الخليفه الصف الاول الثانوي','link'=>'https://chat.whatsapp.com/DPoKMoItVMbFVBinul7MuM'];
        } else {
            $data[] = ['name'=>'سنتر الخليفه الصف الثاني الثانوي','link'=>'https://chat.whatsapp.com/E1ZKVQQZuvwKl0f2aBp47r'];
            $data[] = ['name'=>'سنتر سان جوزيف الصف الثاني الثانوي','link'=>'https://chat.whatsapp.com/GENkRUdXfOWIJaq9Pf9CAC'];
            $data[] = ['name'=>'سنتر دار السعاده الصف الثاني الثانوي','link'=>'https://chat.whatsapp.com/CxV0KEJBVFk2xzx5SyzecF'];
            $data[] = ['name'=>'سنتر K الصف الثاني الثانوي','link'=>'https://chat.whatsapp.com/HCvRXseN5qsAupDtIAZGBI'];
            $data[] = ['name'=>'سنتر الماسه الصف الثاني الثانوي','link'=>'https://chat.whatsapp.com/KvlV6QnxlhZ5Ta673q4jJP'];
        }
        return $data;
    }

    public function index($studentID = '', $branch_id = '', $section_id = '')
    {                
        // ob_start();
        // error_reporting(0);
        // error_reporting(E_ALL);
        // ini_set('display_errors',1);

        if(isset($_POST['branch_id']) && isset($_POST['section_id']) && isset($_POST['student_code'])){
            $student = $this->getStudentByBarcode2($_POST['student_code'],$_POST['branch_id'],$_POST['section_id']); 
            if($student){
                $this->data['marks'] = $this->get_student_marks($student->id,$_POST['section_id']);
                $this->data['marks_chart'] = $this->get_student_marks_chart($student->id,$_POST['section_id']);
                $this->data['student'] = $student;
                $this->data['whatsapp'] = $this->prepareWhatsappData($_POST['branch_id'],$_POST['section_id']);

                $this->load->view('parents/parentview', $this->data);    
            } else {
                $this->data['not_found'] = '1';
                $this->load->view('parents/parentviewsearch', $this->data);
            }
        } else {            
            $this->load->view('parents/parentviewsearch', $this->data);
        }

        // if(isset($_POST['student_code'])){
        //     redirect(base_url('parentreview/index/' . $_POST['student_code']));
        // }
    }

    public function get_student_marks($studentID,$sectionID){
        // $this->db->select('mark.*, student.first_name, student.last_name, student.register_no, timetable_exam.exam_date as exam_date, exam.id as exam_id, exam_period, exam.name as exam_name');
        // $this->db->from('mark');
        // $this->db->where('student_id', $studentID);
        // $this->db->join('student', 'student.id = mark.student_id', 'left');
        // $this->db->join('exam', 'exam.id = mark.exam_id', 'left');
        // $this->db->join('timetable_exam', 'timetable_exam.exam_id = exam.id', 'left'); // Corrected join condition

        // $this->db->order_by('mark.id', 'ASC');
        // $result = $this->db->get()->result_array();
        // return $result;
        $this->db->select('saa.*,student.first_name, student.last_name, student.register_no, exam.id as exam_id, exam.total_mark, exam_period, exam.name as exam_name, exam_date, enroll.section_id , enroll.student_id');
        $this->db->from('student_attendance as saa');
        $this->db->where('enroll.student_id', $studentID);
        $this->db->where('enroll.section_id', $sectionID);
        $this->db->join('student', 'student.id = saa.enroll_id', 'left');
        $this->db->join('enroll', 'enroll.student_id = saa.enroll_id', 'left');
        $this->db->join('exam', 'exam.id = saa.exam_id', 'left');
        $result = $this->db->get()->result_array();
        return $result;
    }

    public function get_student_marks_chart($studentID,$sectionID){
        $this->db->select('saa.*,student.first_name, student.last_name, student.register_no, exam.id as exam_id, exam.total_mark as totalmark, exam_period, exam.name as exam_name, exam_date, enroll.section_id , enroll.student_id');
        $this->db->from('student_attendance as saa');
        $this->db->where('enroll.student_id', $studentID);
        $this->db->where('enroll.section_id', $sectionID);
        $this->db->join('student', 'student.id = saa.enroll_id', 'left');
        $this->db->join('enroll', 'enroll.student_id = saa.enroll_id', 'left');
        $this->db->join('exam', 'exam.id = saa.exam_id', 'left');
        $result = $this->db->get()->result_array();

        $data = [];
        foreach($result as $single){
            if(is_numeric($single['mark']) && is_numeric($single['totalmark'])){
                $data[] = ($single['mark'] * 100) / $single['totalmark'];
            }
        }
        return $data;        
    }


    public function getStudentByBarcode2($barcode = '', $branch_id = '', $section_id = '')
    {
        $this->db->select('s.*,c.name as class_name,se.name as section_name');
        $this->db->from('student as s');
        $this->db->join('enroll as e', 's.id = e.student_id', 'left');
        $this->db->join('class as c', 's.class_id = c.id', 'left');
        $this->db->join('section as se', 's.section_id = se.id', 'left');
        $this->db->where('register_no', $barcode);
        $this->db->where('e.branch_id', $branch_id);
        $this->db->where('e.section_id', $section_id);

        return $this->db->get()->row();
        
    }

    public function getSectionByBranch()
    {
        $html = "";
        $branch_id = $this->input->post("branch_id");
        if (!empty($branch_id)) {
            $result = $this->db->select('section.*')
            ->from('section')
            ->where('branch_id',$branch_id)
            ->get()->result_array();
            if (is_array($result) && count($result)) {
                $html .= '<option value="">' . translate('select_year') . '</option>';
                foreach ($result as $row) {
                    $html .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                }
            } else {
                $html .= '<option value="">' . translate('no_selection_available') . '</option>';
            }
        } else {
            $html .= '<option value="">' . translate('select_branch_first') . '</option>';
        }
        echo $html;
    }

    public function getBranches()
    {
        $html = "";
        $result = $this->db->select('branch.*')
        ->from('branch')
        ->get()->result_array();
        if (is_array($result) && count($result)) {
            $html .= '<option value="">' . translate('select_center') . '</option>';
            foreach ($result as $row) {
                $html .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
            }
        } else {
            $html .= '<option value="">' . translate('no_selection_available') . '</option>';
        }
        
        echo $html;
    }
}
