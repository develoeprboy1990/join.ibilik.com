<?php 
/**
 * User Page Controller
 * @category  Controller
 */
class UserController extends SecureController{
	function __construct(){
		parent::__construct();
		$this->tablename = "user";
	}
	/**
     * List page records
     * @param $fieldname (filter record by a field) 
     * @param $fieldvalue (filter field value)
     * @return BaseView
     */
	function index($fieldname = null , $fieldvalue = null){
		$request = $this->request;
		$db = $this->GetModel();
		$tablename = $this->tablename;
		$fields = array("id", 
			"username", 
			"agency", 
			"agent_name", 
			"phone_number", 
			"remark", 
			"role", 
			"priority", 
			"status", 
			"pass_text");
		$pagination = $this->get_pagination(MAX_RECORD_COUNT); // get current pagination e.g array(page_number, page_limit)
		//search table record
		if(!empty($request->search)){
			$text = trim($request->search); 
			$search_condition = "(
				user.id LIKE ? OR 
				user.username LIKE ? OR 
				user.password LIKE ? OR 
				user.agency LIKE ? OR 
				user.agent_name LIKE ? OR 
				user.phone_number LIKE ? OR 
				user.remark LIKE ? OR 
				user.role LIKE ? OR 
				user.priority LIKE ? OR 
				user.status LIKE ? OR 
				user.pass_text LIKE ?
			)";
			$search_params = array(
				"%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%"
			);
			//setting search conditions
			$db->where($search_condition, $search_params);
			 //template to use when ajax search
			$this->view->search_template = "user/search.php";
		}
		if(!empty($request->orderby)){
			$orderby = $request->orderby;
			$ordertype = (!empty($request->ordertype) ? $request->ordertype : ORDER_TYPE);
			$db->orderBy($orderby, $ordertype);
		}
		else{
			$db->orderBy("user.id", ORDER_TYPE);
		}
		$allowed_roles = array ('administrator');
		if(!in_array(strtolower(USER_ROLE), $allowed_roles)){
		$db->where("user.username", get_active_user('username') );
		}
		if($fieldname){
			$db->where($fieldname , $fieldvalue); //filter by a single field name
		}
		$tc = $db->withTotalCount();
		$records = $db->get($tablename, $pagination, $fields);
		$records_count = count($records);
		$total_records = intval($tc->totalCount);
		$page_limit = $pagination[1];
		$total_pages = ceil($total_records / $page_limit);
		$data = new stdClass;
		$data->records = $records;
		$data->record_count = $records_count;
		$data->total_records = $total_records;
		$data->total_page = $total_pages;
		if($db->getLastError()){
			$this->set_page_error();
		}
		$page_title = $this->view->page_title = "User";
		$this->view->report_filename = date('Y-m-d') . '-' . $page_title;
		$this->view->report_title = $page_title;
		$this->view->report_layout = "report_layout.php";
		$this->view->report_paper_size = "A4";
		$this->view->report_orientation = "portrait";
		$this->render_view("user/list.php", $data); //render the full page
	}
	/**
     * View record detail 
	 * @param $rec_id (select record by table primary key) 
     * @param $value value (select record by value of field name(rec_id))
     * @return BaseView
     */
	function view($rec_id = null, $value = null){
		$request = $this->request;
		$db = $this->GetModel();
		$rec_id = $this->rec_id = urldecode($rec_id);
		$tablename = $this->tablename;
		$fields = array("id", 
			"username", 
			"agent_name", 
			"phone_number", 
			"remark", 
			"role", 
			"status", 
			"pass_text", 
			"agency");
		$allowed_roles = array ('administrator');
		if(!in_array(strtolower(USER_ROLE), $allowed_roles)){
		$db->where("user.username", get_active_user('username') );
		}
		if($value){
			$db->where($rec_id, urldecode($value)); //select record based on field name
		}
		else{
			$db->where("user.id", $rec_id);; //select record based on primary key
		}
		$record = $db->getOne($tablename, $fields );
		if($record){
			$page_title = $this->view->page_title = "View  User";
		$this->view->report_filename = date('Y-m-d') . '-' . $page_title;
		$this->view->report_title = $page_title;
		$this->view->report_layout = "report_layout.php";
		$this->view->report_paper_size = "A4";
		$this->view->report_orientation = "portrait";
		}
		else{
			if($db->getLastError()){
				$this->set_page_error();
			}
			else{
				$this->set_page_error("No record found");
			}
		}
		return $this->render_view("user/view.php", $record);
	}
	/**
     * Insert new record to the database table
	 * @param $formdata array() from $_POST
     * @return BaseView
     */
	function add($formdata = null){
		if($formdata){
			$db = $this->GetModel();
			$tablename = $this->tablename;
			$request = $this->request;
			//fillable fields
			$fields = $this->fields = array("username","password","pass_text","agent_name","phone_number","remark","role","priority","status","agency");
			$postdata = $this->format_request_data($formdata);
			$cpassword = $postdata['confirm_password'];
			$password = $postdata['password'];
			if($cpassword != $password){
				$this->view->page_error[] = "Your password confirmation is not consistent";
			}
			$this->rules_array = array(
				'username'     => 'required',
				'password'     => 'required',
				'agent_name'   => 'required',
				'phone_number' => 'required',
				'priority'     => 'numeric',
				'status'       => 'numeric',
				'agency'       => 'required',
			);
			$this->sanitize_array = array(
				'username'     => 'sanitize_string',
				'pass_text'    => 'sanitize_string',
				'agent_name'   => 'sanitize_string',
				'phone_number' => 'sanitize_string',
				'remark'       => 'sanitize_string',
				'role'         => 'sanitize_string',
				'priority'     => 'sanitize_string',
				'status'       => 'sanitize_string',
				'agency'       => 'sanitize_string',
			);
			$this->filter_vals = true; //set whether to remove empty fields
			$modeldata = $this->modeldata = $this->validate_form($postdata);
			$password_text = $modeldata['password'];
			//update modeldata with the password hash
			$modeldata['password'] = $this->modeldata['password'] = password_hash($password_text , PASSWORD_DEFAULT);
			//Check if Duplicate Record Already Exit In The Database
			$db->where("username", $modeldata['username']);
			if($db->has($tablename)){
				$this->view->page_error[] = $modeldata['username']." Already exist!";
			}
			//Check if Duplicate Record Already Exit In The Database
			$db->where("agent_name", $modeldata['agent_name']);
			if($db->has($tablename)){
				$this->view->page_error[] = $modeldata['agent_name']." Already exist!";
			} 
			if($this->validated()){
				$rec_id = $this->rec_id = $db->insert($tablename, $modeldata);
				if($rec_id){
		# Statement to execute after adding record
		$modeldata['pass_text'] = $modeldata['password'];
		# End of after add statement
					$this->set_flash_msg("Record added successfully", "success");
					return	$this->redirect("user");
				}
				else{
					$this->set_page_error();
				}
			}
		}
		$page_title = $this->view->page_title = "Add New User";
		$this->render_view("user/add.php");
	}
	/**
     * Update table record with formdata
	 * @param $rec_id (select record by table primary key)
	 * @param $formdata array() from $_POST
     * @return array
     */
	function edit($rec_id = null, $formdata = null){
		$request = $this->request;
		$db = $this->GetModel();
		$this->rec_id = $rec_id;
		$tablename = $this->tablename;
		 //editable fields
		$fields = $this->fields = array("id","username","password","pass_text","agent_name","phone_number","remark","role","priority","status","agency");
		if($formdata){
			$postdata = $this->format_request_data($formdata);
			$cpassword = $postdata['confirm_password'];
			$password = $postdata['password'];
			if($cpassword != $password){
				$this->view->page_error[] = "Your password confirmation is not consistent";
			}
			$this->rules_array = array(
				'username' => 'required|valid_email',
				'password' => 'required',
				'pass_text' => 'required',
				'agent_name' => 'required|valid_email',
				'phone_number' => 'required',
				'priority' => 'numeric',
				'status' => 'numeric',
				'agency' => 'required',
			);
			$this->sanitize_array = array(
				'username' => 'sanitize_string',
				'pass_text' => 'sanitize_string',
				'agent_name' => 'sanitize_string',
				'phone_number' => 'sanitize_string',
				'remark' => 'sanitize_string',
				'role' => 'sanitize_string',
				'priority' => 'sanitize_string',
				'status' => 'sanitize_string',
				'agency' => 'sanitize_string',
			);
			$modeldata = $this->modeldata = $this->validate_form($postdata);
			$password_text = $modeldata['password'];
			//update modeldata with the password hash
			$modeldata['password'] = $this->modeldata['password'] = password_hash($password_text , PASSWORD_DEFAULT);
			//Check if Duplicate Record Already Exit In The Database
			if(isset($modeldata['username'])){
				$db->where("username", $modeldata['username'])->where("id", $rec_id, "!=");
				if($db->has($tablename)){
					$this->view->page_error[] = $modeldata['username']." Already exist!";
				}
			}
			//Check if Duplicate Record Already Exit In The Database
			if(isset($modeldata['agent_name'])){
				$db->where("agent_name", $modeldata['agent_name'])->where("id", $rec_id, "!=");
				if($db->has($tablename)){
					$this->view->page_error[] = $modeldata['agent_name']." Already exist!";
				}
			} 
			if($this->validated()){
		$allowed_roles = array ('administrator');
		if(!in_array(strtolower(USER_ROLE), $allowed_roles)){
		$db->where("user.username", get_active_user('username') );
		}
				$db->where("user.id", $rec_id);;
				$bool = $db->update($tablename, $modeldata);
				$numRows = $db->getRowCount(); //number of affected rows. 0 = no record field updated
				if($bool && $numRows){
		# Statement to execute after adding record
			$modeldata['pass_text'] = $modeldata['password'];
		# End of after update statement
					$this->set_flash_msg("Yeah! Record updated successfully", "success");
					return $this->redirect("user");
				}
				else{
					if($db->getLastError()){
						$this->set_page_error();
					}
					elseif(!$numRows){
						//not an error, but no record was updated
						$page_error = "No record updated";
						$this->set_page_error($page_error);
						$this->set_flash_msg($page_error, "warning");
						return	$this->redirect("user");
					}
				}
			}
		}
		$allowed_roles = array ('administrator');
		if(!in_array(strtolower(USER_ROLE), $allowed_roles)){
		$db->where("user.username", get_active_user('username') );
		}
		$db->where("user.id", $rec_id);;
		$data = $db->getOne($tablename, $fields);
		$page_title = $this->view->page_title = "Edit  User";
		if(!$data){
			$this->set_page_error();
		}
		return $this->render_view("user/edit.php", $data);
	}
	/**
     * Update single field
	 * @param $rec_id (select record by table primary key)
	 * @param $formdata array() from $_POST
     * @return array
     */
	function editfield($rec_id = null, $formdata = null){
		$db = $this->GetModel();
		$this->rec_id = $rec_id;
		$tablename = $this->tablename;
		//editable fields
		$fields = $this->fields = array("id","username","password","pass_text","agent_name","phone_number","remark","role","priority","status","agency");
		$page_error = null;
		if($formdata){
			$postdata = array();
			$fieldname = $formdata['name'];
			$fieldvalue = $formdata['value'];
			$postdata[$fieldname] = $fieldvalue;
			$postdata = $this->format_request_data($postdata);
			$this->rules_array = array(
				'username' => 'required|valid_email',
				'password' => 'required',
				'pass_text' => 'required',
				'agent_name' => 'required|valid_email',
				'phone_number' => 'required',
				'priority' => 'numeric',
				'status' => 'numeric',
				'agency' => 'required',
			);
			$this->sanitize_array = array(
				'username' => 'sanitize_string',
				'pass_text' => 'sanitize_string',
				'agent_name' => 'sanitize_string',
				'phone_number' => 'sanitize_string',
				'remark' => 'sanitize_string',
				'role' => 'sanitize_string',
				'priority' => 'sanitize_string',
				'status' => 'sanitize_string',
				'agency' => 'sanitize_string',
			);
			$this->filter_rules = true; //filter validation rules by excluding fields not in the formdata
			$modeldata = $this->modeldata = $this->validate_form($postdata);
			//Check if Duplicate Record Already Exit In The Database
			if(isset($modeldata['username'])){
				$db->where("username", $modeldata['username'])->where("id", $rec_id, "!=");
				if($db->has($tablename)){
					$this->view->page_error[] = $modeldata['username']." Already exist!";
				}
			}
			//Check if Duplicate Record Already Exit In The Database
			if(isset($modeldata['agent_name'])){
				$db->where("agent_name", $modeldata['agent_name'])->where("id", $rec_id, "!=");
				if($db->has($tablename)){
					$this->view->page_error[] = $modeldata['agent_name']." Already exist!";
				}
			} 
			if($this->validated()){
		$allowed_roles = array ('administrator');
		if(!in_array(strtolower(USER_ROLE), $allowed_roles)){
		$db->where("user.username", get_active_user('username') );
		}
				$db->where("user.id", $rec_id);;
				$bool = $db->update($tablename, $modeldata);
				$numRows = $db->getRowCount();
				if($bool && $numRows){
					return render_json(
						array(
							'num_rows' =>$numRows,
							'rec_id' =>$rec_id,
						)
					);
				}
				else{
					if($db->getLastError()){
						$page_error = $db->getLastError();
					}
					elseif(!$numRows){
						$page_error = "No record updated";
					}
					render_error($page_error);
				}
			}
			else{
				render_error($this->view->page_error);
			}
		}
		return null;
	}
	/**
     * Delete record from the database
	 * Support multi delete by separating record id by comma.
     * @return BaseView
     */
	function delete($rec_id = null){
		Csrf::cross_check();
		$request = $this->request;
		$db = $this->GetModel();
		$tablename = $this->tablename;
		$this->rec_id = $rec_id;
		//form multiple delete, split record id separated by comma into array
		$arr_rec_id = array_map('trim', explode(",", $rec_id));
		$db->where("user.id", $arr_rec_id, "in");
		$allowed_roles = array ('administrator');
		if(!in_array(strtolower(USER_ROLE), $allowed_roles)){
		$db->where("user.username", get_active_user('username') );
		}
		$bool = $db->delete($tablename);
		if($bool){
			$this->set_flash_msg("Record deleted successfully", "success");
		}
		elseif($db->getLastError()){
			$page_error = $db->getLastError();
			$this->set_flash_msg($page_error, "danger");
		}
		return	$this->redirect("user");
	}
}
