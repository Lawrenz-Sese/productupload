<?php
	class Auth {
		protected $gm, $pdo;
		private $status = array();
		private $failed_stat = array(
			'remarks'=>'failed',
			'message'=>'Failed to retrieve the requested records'
		);
		private $success_stat = array(
			'remarks'=>'success',
			'message'=>'Successfully retrieved the requested records'
		);
		public function __construct(\PDO $pdo) {
			$this->pdo = $pdo;
			$this->gm = new GlobalMethods($pdo);
		}

		# JWT
		protected function generateHeader() {
			$h = [
				"typ"=>"JWT",
				"alg"=>"HS256",
				"app"=>"My App",
				"dev"=>"The Developer"
			];
			return str_replace("=", "", base64_encode(json_encode($h)));
		}

		protected function generatePayload($uc, $ue, $ito) {
			$p = [
				"uc"=>$uc,
				"ue"=>$ue,
				"ito"=>$ito,
				"iby"=>"The Developer",
				"ie"=>"thedeveloper@test.com",
				"exp"=>date("Y-m-d H:i:s") //date_create()
			];
			return str_replace("=", "", base64_encode(json_encode($p)));
		}

		protected function generateToken($usercode, $useremail, $fullname) {
			$header = $this->generateHeader();
			$payload = $this->generatePayload($usercode, $useremail, $fullname);
			$signature = hash_hmac("sha256", "$header.$payload", base64_encode(SECRET));
			return "$header.$payload." .str_replace("=", "", base64_encode($signature));
		}

		#./JWT

		public function showToken(){
			return $this->generateToken("202010100", "202010100@test.com", "Juan Dela Cruz");
		}
		function checkpass($pass, $hash){

			if(md5($pass) == $hash){
				return true;
			}else{
				false;
			}
		}
	 	public function feedback_login($dt){
			
			$uname = $dt->uname;
			$pword = $dt->password;

			// echo($uname." ".$pword);

			$sql = "SELECT * FROM tbl_feedback_accounts WHERE fld_uname = '$uname' LIMIT 0,1";

			$stmt = $this->pdo->query($sql);
			// $stmt->bindParam(':uname', $uname);
			$stmt->execute();
			$numOfRows = $stmt->rowCount();

		
				if($result=$this->pdo->query($sql)){
					if($numOfRows>0){
						$row = $stmt->fetch(PDO::FETCH_ASSOC);
						$num = $row['acc_id'];
						$role = $row['fld_is_admin'];
						$res_id = $row['res_id'];
						$uname = $row['fld_uname'];
						$password = $row['fld_pwd'];
						
						if($this->checkpass($pword, $password)){
							http_response_code(200);
							$this->data = array(
								"num"=> $num,
								"role"=> $role,
								"uname" =>  $uname,
								"res_id" =>  $res_id,
								"date" => date('Y-m-d H:i:s T')
							);
							$this->status = array(
								"remarks"=>"success",
								"message"=>"Successfully logged in",
							);
						}else{


							http_response_code(401);
							$this->status = array(
								'remarks'=>'failed1',
								'message'=>'Incorrect username or password',
							);
						}
					}else{
						http_response_code(401);
						$this->status = array(
							'remarks'=>'failed2',
							'message'=>'Incorrect username or password',
						);
					}
				}else{
					http_response_code(401);
					$this->status = array(
						'remarks'=>'failed3',
						'message'=>'Incorrect username or password',
					);
				}
				return array(
					'status'=>$this->status,
					'payload'=>$this->data,
					'prepared_by'=>'201810109',
					'timestamp'=>date('D M j, Y G:i:s T')
				);


			
		}
		
}
?>