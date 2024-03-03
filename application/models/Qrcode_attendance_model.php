<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Qrcode_attendance_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getStudentIDByBarcode($barcode = '', $section_id = null)
    {
        // ob_start();
        // error_reporting(0);
        // error_reporting(E_ALL);
        // ini_set('display_errors',1);
        $this->db->select('student.id as id, student.section_id, enroll.branch_id');
        $this->db->from('student');
        $this->db->join('enroll', 'enroll.student_id = student.id', 'left');
        $this->db->where('register_no', $barcode);
        if($section_id){
            $this->db->where('student.section_id', $section_id);
        }
        if (!is_superadmin_loggedin()) {
            $this->db->where('enroll.branch_id', get_loggedin_branch_id());
        }
        $row = $this->db->get()->row();
        return $row->id;
    }

    public function getStudentDetailsByEid($enrollID = '', $section_id = null)
    {
        // ob_start();
        // error_reporting(0);
        // error_reporting(E_ALL);
        // ini_set('display_errors',1);
        $this->db->select('s.section_id,s.first_name,s.last_name,s.register_no,s.email,student.section_id,student.class_id,s.photo,admission_date,birthday,enroll.student_id,enroll.branch_id,enroll.roll,class.name as class_name,section.name as section_name,student_category.name as cname');
        $this->db->from('enroll');
        $this->db->join('student as s', 's.id = enroll.student_id', 'inner');
        $this->db->join('class', 'class.id = student.class_id', 'left');
        $this->db->join('section', 'section.id = student.section_id', 'left');
        $this->db->join('student_category', 'student_category.id = s.category_id', 'left');
        $this->db->where('enroll.id', $enrollID);
        if($section_id)
            $this->db->where('s.section_id', intval($section_id));
        
        if (!is_superadmin_loggedin())
            $this->db->where('enroll.branch_id', get_loggedin_branch_id());
        $row = $this->db->get()->row();
        return $row;
    }

    public function getStudentIDs($branch_id, $class_id, $section_id, $date)
    {
        // ob_start();
        // error_reporting(0);
        // error_reporting(E_ALL);
        // ini_set('display_errors',1);

        $this->db->select('student_attendance.enroll_id');
        $this->db->from('student_attendance');
        $this->db->join('enroll', 'enroll.id = student_attendance.enroll_id', 'left');
        $this->db->join('student', 'student.id = student_attendance.enroll_id', 'left');
        $this->db->where('student_attendance.date', $date);
        $this->db->where_in('student_attendance.status', ['P', 'L']);
        $this->db->where('student.class_id', $class_id);
        $this->db->where('enroll.branch_id', $branch_id);
        $this->db->where('student.section_id', $section_id);
        $attendance = $this->db->get()->result_array();
          
        $this->db->select('student.id,enroll.branch_id as branch,student.class_id as class, student.section_id as section');
        $this->db->from('student');
        $this->db->join('enroll', 'enroll.student_id = student.id', 'left');
        $this->db->where('enroll.branch_id', $branch_id);
        $this->db->where('student.class_id', $class_id);
        $this->db->where('student.section_id', $section_id);
        if (!empty($attendance)) {
            $this->db->where_not_in('student.id', array_column($attendance, 'enroll_id'));
        }
        $absence = $this->db->get()->result();

        foreach($absence as $single){            
            $arrayAttendance = array(
                'enroll_id' => $single->id,
                'status' => 'A',
                'qr_code' => "1",
                'remark' => null,
                'date' => $date,
                'branch_id' => $single->branch,
                'class_id' => $single->class,
                'section_id' => $single->section,
            );
            $this->db->insert('student_attendance', $arrayAttendance);
        }

        return '1';
    }

    public function getSingleStaff($id = '')
    {
        $this->db->select('staff.*,staff_designation.name as designation_name,staff_department.name as department_name,login_credential.role as role_id,login_credential.active,login_credential.username, roles.name as role');
        $this->db->from('staff');
        $this->db->join('login_credential', 'login_credential.user_id = staff.id and login_credential.role != "6" and login_credential.role != "7"', 'inner');
        $this->db->join('roles', 'roles.id = login_credential.role', 'left');
        $this->db->join('staff_designation', 'staff_designation.id = staff.designation', 'left');
        $this->db->join('staff_department', 'staff_department.id = staff.department', 'left');
        $this->db->where('staff.id', $id);
        if (!is_superadmin_loggedin())
            $this->db->where('staff.branch_id', get_loggedin_branch_id());
        $query = $this->db->get();
        return $query->row();
    }

    public function getStuListDT($postData)
    {
        $response = array();

        // ob_start();
        // error_reporting(0);
        // error_reporting(E_ALL);
        // ini_set('display_errors',1);

        // read value
        $draw = $postData['draw'];
        $branch_id = $postData['branch_id'];
        $class_id = $postData['class_id'];
        $section_id = $postData['section_id'];
        $date = date('Y-m-d', strtotime($postData['date']));
        $start = $postData['start'];
        $rowperpage = $postData['length']; // Rows display per page
        $searchValue = $postData['search']['value']; // Search value

        // order
        $columnIndex = empty($postData['order'][0]['column']) ? 0 : $postData['order'][0]['column']; // Column index
        $columnSortOrder = empty($postData['order'][0]['dir']) ? 'asc' : $postData['order'][0]['dir']; // asc or desc

        $column_order = array('`st`.`id`');
        $column_order[] = '`s`.`first_name`';
        $column_order[] = '`class`.`id`';
        $column_order[] = '`s`.`register_no`';
        // $column_order[] = '`e`.`roll`';

        $search_arr = array();
        $searchQuery = "";
        if ($searchValue != '') {
            $search_arr[] = " (`s`.`register_no` like '%" . trim($searchValue) . "%' OR (`s`.`first_name` like '%" . trim($searchValue) . "%' OR `s`.`last_name` like '%" . trim($searchValue) . "%'))";

        }

        if (!is_superadmin_loggedin()) {
            $branch_id = $this->db->escape(get_loggedin_branch_id());
            $search_arr[] = " `st`.`branch_id` = $branch_id ";
        }

        if (count($search_arr) > 0) {
            $searchQuery = implode("AND", $search_arr);
        }

        // $today = $this->db->escape(date('Y-m-d'));
        $today = $this->db->escape($date);
        $sessionID = $this->db->escape(get_session_id());
        // Total number of records without filtering
        // $branchID = $this->db->escape(get_loggedin_branch_id());
        $branchID = '';
        $sql = "SELECT `st`.`id` FROM `student_attendance` as `st` INNER JOIN `enroll` as `e` ON `e`.`id` = `st`.`enroll_id` WHERE DATE(`st`.`date`) = $today AND `st`.`qr_code` = '1' AND `e`.`session_id` = $sessionID" . (!empty($branchID) ? " AND `st`.`branch_id` = $branchID" : '');
        
        $records = $this->db->query($sql)->result();
        $totalRecords = count($records);

        // Total number of record with filtering
        $sql = "SELECT `st`.`id` FROM `student_attendance` as `st` INNER JOIN `enroll` as `e` ON `e`.`id` = `st`.`enroll_id` INNER JOIN `student` as `s` ON `s`.`id` = `e`.`student_id` WHERE DATE(`st`.`date`) = $today AND `st`.`qr_code` = '1' AND `e`.`session_id` = $sessionID";
        if (!empty($searchQuery)) {
            $sql .= " AND " . $searchQuery;
        }

        $records = $this->db->query($sql)->result();
        $totalRecordwithFilter = count($records);

        // Fetch records
        $sql = "SELECT `e`.`id` as `enroll_id`, `e`.`roll`, `s`.`register_no`, CONCAT_WS(' ', `s`.`first_name`, `s`.`last_name`) as `fullname`, `class`.`name` as `class_name`, `section`.`name` as `section_name`, `s`.`register_no`, `s`.`id` as `studentid` , `st`.`created_at`, `st`.`status` , `st`.`id` as `recordid`, `st`.`mark` as `studentmark` FROM `student_attendance` as `st` INNER JOIN `enroll` as `e` ON `e`.`id` = `st`.`enroll_id` INNER JOIN `student` as `s` ON `s`.`id` = `e`.`student_id` LEFT JOIN `class` ON `class`.`id` = `e`.`class_id` LEFT JOIN `section` ON `section`.`id` = `e`.`section_id` WHERE `e`.`session_id` = $sessionID AND `st`.`qr_code` = '1' AND `st`.`date` = '$date' AND `st`.`branch_id` = $branch_id AND `st`.`class_id` = $class_id AND `st`.`status` != 'A' AND `st`.`section_id` = $section_id AND date(st.date) = $today";
        if (!empty($searchQuery)) {
            $sql .= " AND " . $searchQuery;
        }
        $sql .= " ORDER BY " . $column_order[$columnIndex] . " $columnSortOrder LIMIT $start, $rowperpage";        
        $records = $this->db->query($sql)->result();

        $data = array();
        $count = $start + 1;
        foreach ($records as $record) {
            $row = array();
            $row[] = $count++;
            $row[] = $record->fullname;
            $row[] = $record->register_no;
            // $row[] = empty($record->roll) ? '-' : $record->roll;
            // $row[] = _d($record->created_at) . " " . date('h:i A', strtotime($record->created_at));
            $row[] = date('d/m/Y H:i', strtotime($record->created_at));
            $row[] = '<button class="btn btn-success quiz_remark" data-recordid="'.$record->recordid.'" data-studentid="'.$record->studentid.'" >'.translate('exam').'</button>' . (!empty($record->studentmark) ? "<span class='exam_result'>  /  $record->studentmark</span>" : '<span class="exam_result"></span>');
            $data[] = $row;
        }

        // Response
        $response = array(
            "draw" => intval($draw),
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $totalRecordwithFilter,
            "data" => $data,
        );
        return json_encode($response);
    }

    public function getStuListDTAbsent($postData)
    {
        $response = array();

        // ob_start();
        // error_reporting(0);
        // error_reporting(E_ALL);
        // ini_set('display_errors',1);

        // read value
        $draw = $postData['draw'];
        $branch_id = $postData['branch_id'];
        $class_id = $postData['class_id'];
        $section_id = $postData['section_id'];
        $date = date('Y-m-d', strtotime($postData['date']));
        $start = $postData['start'];
        $rowperpage = $postData['length']; // Rows display per page
        $searchValue = $postData['search']['value']; // Search value

        // order
        $columnIndex = empty($postData['order'][0]['column']) ? 0 : $postData['order'][0]['column']; // Column index
        $columnSortOrder = empty($postData['order'][0]['dir']) ? 'asc' : $postData['order'][0]['dir']; // asc or desc

        $column_order = array('`st`.`id`');
        $column_order[] = '`s`.`first_name`';
        $column_order[] = '`class`.`id`';
        $column_order[] = '`s`.`register_no`';
        // $column_order[] = '`e`.`roll`';

        $search_arr = array();
        $searchQuery = "";
        if ($searchValue != '') {
            $search_arr[] = " (`s`.`register_no` like '%" . trim($searchValue) . "%' OR (`s`.`first_name` like '%" . trim($searchValue) . "%' OR `s`.`last_name` like '%" . trim($searchValue) . "%'))";

        }

        if (!is_superadmin_loggedin()) {
            $branch_id = $this->db->escape(get_loggedin_branch_id());
            $search_arr[] = " `st`.`branch_id` = $branch_id ";
        }

        if (count($search_arr) > 0) {
            $searchQuery = implode("AND", $search_arr);
        }

        // $today = $this->db->escape(date('Y-m-d'));
        $today = $this->db->escape($date);
        $sessionID = $this->db->escape(get_session_id());
        // Total number of records without filtering
        // $branchID = $this->db->escape(get_loggedin_branch_id());
        $branchID = '';
        $sql = "SELECT `st`.`id` FROM `student_attendance` as `st` INNER JOIN `enroll` as `e` ON `e`.`id` = `st`.`enroll_id` WHERE DATE(`st`.`date`) = $today AND `st`.`qr_code` = '1' AND `e`.`session_id` = $sessionID" . (!empty($branchID) ? " AND `st`.`branch_id` = $branchID" : '');
        
        $records = $this->db->query($sql)->result();
        $totalRecords = count($records);

        // Total number of record with filtering
        $sql = "SELECT `st`.`id` FROM `student_attendance` as `st` INNER JOIN `enroll` as `e` ON `e`.`id` = `st`.`enroll_id` INNER JOIN `student` as `s` ON `s`.`id` = `e`.`student_id` WHERE DATE(`st`.`date`) = $today AND `st`.`qr_code` = '1' AND `e`.`session_id` = $sessionID";
        if (!empty($searchQuery)) {
            $sql .= " AND " . $searchQuery;
        }

        $records = $this->db->query($sql)->result();
        $totalRecordwithFilter = count($records);

        // Fetch records
        $sql = "SELECT `e`.`id` as `enroll_id`, `e`.`roll`, `s`.`register_no`, CONCAT_WS(' ', `s`.`first_name`, `s`.`last_name`) as `fullname`, `class`.`name` as `class_name`, `section`.`name` as `section_name`, `s`.`register_no`, `s`.`id` as `studentid` , `st`.`created_at`, `st`.`status`, `st`.`message` , `st`.`id` as `recordid`, `st`.`mark` as `studentmark` FROM `student_attendance` as `st` INNER JOIN `enroll` as `e` ON `e`.`id` = `st`.`enroll_id` INNER JOIN `student` as `s` ON `s`.`id` = `e`.`student_id` LEFT JOIN `class` ON `class`.`id` = `e`.`class_id` LEFT JOIN `section` ON `section`.`id` = `e`.`section_id` WHERE `e`.`session_id` = $sessionID AND `st`.`qr_code` = '1' AND `st`.`date` = '$date' AND `st`.`branch_id` = $branch_id AND `st`.`class_id` = $class_id AND `st`.`status` = 'A' AND `st`.`section_id` = $section_id AND date(st.date) = $today";
        if (!empty($searchQuery)) {
            $sql .= " AND " . $searchQuery;
        }
        $sql .= " ORDER BY " . $column_order[$columnIndex] . " $columnSortOrder LIMIT $start, $rowperpage";        
        $records = $this->db->query($sql)->result();

        $data = array();
        $count = $start + 1;
        foreach ($records as $record) {
            $row = array();
            $row[] = $count++;
            $row[] = $record->fullname;
            $row[] = $record->register_no;
            // $row[] = empty($record->roll) ? '-' : $record->roll;
            // $row[] = _d($record->created_at) . " " . date('h:i A', strtotime($record->created_at));
            $row[] = date('d/m/Y', strtotime($record->created_at));
            $style='';
            if($record->message){
                $style = 'background: orange';
            }
            $row[] = '<button style="'.$style.'" class="btn btn-success message_absense" data-recordid="'.$record->recordid.'" data-studentid="'.$record->studentid.'" >'.translate('message').'</button>' . (!empty($record->studentmark) ? "<span class='exam_result'>  /  $record->studentmark</span>" : '<span class="exam_result"></span>');
            $data[] = $row;
        }

        // Response
        $response = array(
            "draw" => intval($draw),
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $totalRecordwithFilter,
            "data" => $data,
        );
        return json_encode($response);
    }

    public function getStaffListDT($postData)
    {
        $response = array();

        // read value
        $draw = $postData['draw'];
        $start = $postData['start'];
        $rowperpage = $postData['length']; // Rows display per page
        $searchValue = $postData['search']['value']; // Search value

        // order
        $columnIndex = empty($postData['order'][0]['column']) ? 0 : $postData['order'][0]['column']; // Column index
        $columnSortOrder = empty($postData['order'][0]['dir']) ? 'asc' : $postData['order'][0]['dir']; // asc or desc

        $column_order = array('`st`.`id`');
        $column_order[] = '`s`.`first_name`';
        $column_order[] = '`class`.`id`';
        $column_order[] = '`s`.`register_no`';
        $column_order[] = '`e`.`roll`';

        $search_arr = array();
        $searchQuery = "";
        if ($searchValue != '') {
            $search_arr[] = " (`s`.`name` like '%" . trim($searchValue) . "%' OR `s`.`staff_id` like '%" . trim($searchValue) . "%')";
        }

        if (!is_superadmin_loggedin()) {
            $branch_id = $this->db->escape(get_loggedin_branch_id());
            $search_arr[] = " `st`.`branch_id` = $branch_id ";
        }

        if (count($search_arr) > 0) {
            $searchQuery = implode("AND", $search_arr);
        }

        $today = $this->db->escape(date('Y-m-d'));
        $sessionID = $this->db->escape(get_session_id());
        // Total number of records without filtering
        $branchID = $this->db->escape(get_loggedin_branch_id());
        $sql = "SELECT `st`.`id` FROM `staff_attendance` as `st` WHERE DATE(`st`.`date`) = $today AND `st`.`qr_code` = '1'" . (!empty($branchID) ? " AND `st`.`branch_id` = $branchID" : '');

        $records = $this->db->query($sql)->result();
        $totalRecords = count($records);

        // Total number of record with filtering
        $sql = "SELECT `st`.`id` FROM `staff_attendance` as `st` INNER JOIN `staff` as `s` ON `s`.`id` = `st`.`staff_id` WHERE DATE(`st`.`date`) = $today AND `st`.`qr_code` = '1'";
        if (!empty($searchQuery)) {
            $sql .= " AND " . $searchQuery;
        }
        $records = $this->db->query($sql)->result();
        $totalRecordwithFilter = count($records);

        // Fetch records
        $sql = "SELECT `s`.*,`st`.`date`,`st`.`in_time`,`st`.`out_time`, `roles`.`name` as `role` FROM `staff_attendance` as `st` INNER JOIN `staff` as `s` ON `s`.`id` = `st`.`staff_id` INNER JOIN `login_credential` ON `login_credential`.`user_id` = `s`.`id` and `login_credential`.`role` != '6' and `login_credential`.`role` != '7' LEFT JOIN `roles` ON `roles`.`id` = `login_credential`.`role` WHERE `st`.`qr_code` = '1' AND DATE(`st`.`date`) = $today";
        if (!empty($searchQuery)) {
            $sql .= " AND " . $searchQuery;
        }
        $sql .= " ORDER BY " . $column_order[$columnIndex] . " $columnSortOrder LIMIT $start, $rowperpage";
        $records = $this->db->query($sql)->result();

        $data = array();
        $count = $start + 1;
        foreach ($records as $record) {
            $row = array();
            $row[] = $count++;
            $row[] = $record->name;
            $row[] = $record->staff_id;
            $row[] = $record->role;
            $row[] = _d($record->date);
            $row[] = empty($record->in_time) ? '-' : date('h:i a', strtotime($record->in_time));
            $row[] = empty($record->out_time) ? '-' : date('h:i a', strtotime($record->out_time));
            $data[] = $row;
        }

        // Response
        $response = array(
            "draw" => intval($draw),
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $totalRecordwithFilter,
            "data" => $data,
        );
        return json_encode($response);
    }

    public function getDailyStudentReport($branchID = '', $classID = '', $sectionID = '', $date = '')
    {

        $this->db->select('student_attendance.*,CONCAT_WS(" ",first_name, last_name) as fullname,register_no,roll,center,parent_mobileno,mobileno,enroll.student_id');
        $this->db->from('enroll');
        $this->db->join('student_attendance', 'student_attendance.enroll_id = enroll.id', 'right');
        $this->db->join('student', 'student.id = enroll.student_id', 'inner');
        $this->db->where('student.class_id', $classID);
        $this->db->where('student.section_id', $sectionID);
        $this->db->where('student_attendance.qr_code', 1);
        $this->db->where('student_attendance.date', $date);
        $this->db->order_by('student_attendance.id', 'asc');
        return $this->db->get()->result();
    }

    function getDailyStudentQuizReport($branchID = '', $classID = '', $sectionID = '', $date = '')
    {

        $this->db->from('quiz_details');
        $this->db->where('class_id', $classID);
        $this->db->where('section_id', $sectionID);
        $this->db->where('date', $date);
        $this->db->order_by('id', 'asc');
        return $this->db->get()->result();
    }

    public function getDailyStaffReport($branchID = '', $staff_role = '', $date = '')
    {

        $this->db->select('staff_attendance.*,staff.name,staff.staff_id as staffID,staff_department.name as department_name,roles.name as role_name');
        $this->db->from('staff');
        $this->db->join('staff_attendance', 'staff_attendance.staff_id = staff.id', 'right');
        $this->db->join('login_credential', 'login_credential.user_id = staff.id and login_credential.role != "6" and login_credential.role != "7"', 'inner');
        $this->db->join('staff_department', 'staff_department.id = staff.department', 'left');
        $this->db->join('roles', 'roles.id = login_credential.role', 'left');
        if ($staff_role != "")
            $this->db->where('login_credential.role', $staff_role);
        $this->db->where('staff_attendance.qr_code', 1);
        $this->db->where('staff_attendance.date', $date);
        $this->db->order_by('staff_attendance.id', 'asc');
        return $this->db->get()->result();
    }

}
