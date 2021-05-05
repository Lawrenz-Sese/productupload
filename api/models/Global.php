<?php  
	class GlobalMethods {
		protected $pdo;

		public function __construct(\PDO $pdo) {
			$this->pdo = $pdo;
		}

// SELECT FEEDBACKS

		public function select_feedbacks($table, $filter_data) {

			$sql = "SELECT * FROM $table t1 JOIN tbl_feedback_categories t2 ON t1.category_id=t2.category_id JOIN tbl_feedback_accounts t3 ON t1.acc_id = t3.acc_id JOIN tbl_profiling_residents t4 ON t3.res_id = t4.res_id ORDER BY fb_id ASC";

			if($filter_data != null){
				$sql .="WHERE ='$filter_data'";
			}

			$data = array(); $code = 0; $msg= ""; $remarks = "";
			try {
				if ($res = $this->pdo->query($sql)->fetchAll()) {
					foreach ($res as $rec) { array_push($data, $rec);}
					$res = null; $code = 200; $msg = "Successfully retrieved the requested records"; $remarks = "success";
				}
			} catch (\PDOException $e) {
				$msg = $e->getMessage(); $code = 401; $remarks = "failed";
			}
			return $this->sendPayload($data, $remarks, $msg, $code);
		}

// SELECT ACCOUNTS

		public function select_accounts($table, $filter_data) {

			$sql = "SELECT * FROM $table ";

			if($filter_data != null){
				$sql .="WHERE fld_is_admin='$filter_data'";
			}

			$data = array(); $code = 0; $msg= ""; $remarks = "";
			try {
				if ($res = $this->pdo->query($sql)->fetchAll()) {
					foreach ($res as $rec) { array_push($data, $rec);}
					$res = null; $code = 200; $msg = "Successfully retrieved the requested records"; $remarks = "success";
				}
			} catch (\PDOException $e) {
				$msg = $e->getMessage(); $code = 401; $remarks = "failed";
			}
			return $this->sendPayload($data, $remarks, $msg, $code);
		}

//SELECT forms	

public function select_forms ($table, $filter_data) {

	$sql = "SELECT * FROM $table";

	if($filter_data != null){
		$sql .=" WHERE form_id=$filter_data";
	}

	$data = array(); $code = 0; $msg= ""; $remarks = "";
	try {
		if ($res = $this->pdo->query($sql)->fetchAll()) {
			foreach ($res as $rec) { array_push($data, $rec);}
			$res = null; $code = 200; $msg = "Successfully retrieved the requested records"; $remarks = "success";
		}
	} catch (\PDOException $e) {
		$msg = $e->getMessage(); $code = 401; $remarks = "failed";
	}
	return $this->sendPayload($data, $remarks, $msg, $code);
}
//DELETE
public function delete($table, $data){

	$sql = "DELETE FROM $table  WHERE fb_id = $data";

	$data = array(); $code = 0; $msg= ""; $remarks = "";
	try {
		if ($res = $this->pdo->query($sql)->fetchAll()) {
			foreach ($res as $rec) { array_push($data, $rec);}
			$res = null; $code = 200; $msg = "Successfully deleted feedback"; $remarks = "success";
		}
	} catch (\PDOException $e) {
		$msg = $e->getMessage(); $code = 401; $remarks = "failed";
	}
}
//INSERT

		public function add($table, $data){


			 $fields=[]; 
			 $values=[];

			foreach ($data as $key => $value) {
				array_push($fields, $key);
				array_push($values, $value);
			}
			try {
				$ctr = 0;
				$sqlstr="INSERT INTO $table (";
				foreach ($fields as $value) {
					$sqlstr.=$value; $ctr++;
					if($ctr<count($fields)) {
						$sqlstr.=", ";
					} 	
				} 

				$sqlstr.=") VALUES (".str_repeat("?, ", count($values)-1)."?)";

				$sql = $this->pdo->prepare($sqlstr);
				$sql->execute($values);
				return array("code"=>200, "remarks"=>"success");
			} catch (\PDOException $e) {
				$errmsg = $e->getMessage();
				$code = 403;
			}
			return array("code"=>$code, "errmsg"=>$errmsg);
		}

//UPDATE

		public function update($table, $data, $conditionStringPassed){
			$fields=[]; 
			$values=[];
			$setStr = "";
			foreach ($data as $key => $value) {
				array_push($fields, $key);
				array_push($values, $value);
			}
			try{
				$ctr = 0;
				$sqlstr = "UPDATE $table SET ";
					foreach ($data as $key => $value) {
						$sqlstr .="$key=?"; $ctr++;
						if($ctr<count($fields)){
							$sqlstr.=", ";
						}
					}
					$sqlstr .= " WHERE ".$conditionStringPassed;
					$sql = $this->pdo->prepare($sqlstr);
					$sql->execute($values);
				return array("code"=>200, "remarks"=>"success");	
			}
			catch(\PDOException $e){
				$errmsg = $e->getMessage();
				$code = 403;
			}
			return array("code"=>$code, "errmsg"=>$errmsg);
		}

		public function sendPayload($payload, $remarks, $message, $code) {
			$status = array("remarks"=>$remarks, "message"=>$message);
			http_response_code($code);
			return array(
				"status"=>$status,
				"payload"=>$payload,
				'prepared_by'=>'Jason Paul Cruz, Developer',
				"timestamp"=>date_create());
		} 
	}
?>