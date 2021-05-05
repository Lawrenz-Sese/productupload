<?php 
	require_once("./config/Config.php");

	$db = new Connection();
	$pdo = $db->connect();
	$gm = new GlobalMethods($pdo);
	$auth = new Auth($pdo);

	if (isset($_REQUEST['request'])) {
		$req = explode('/', rtrim($_REQUEST['request'], '/'));
	} else {
		$req = array("errorcatcher");
	}

	switch($_SERVER['REQUEST_METHOD']) {
		case 'POST':
			switch($req[0]) {
				// PULL DATA OF accounts table
				case 'accounts':
					if(count($req)>1){
						echo json_encode($gm->select_accounts('tbl_feedback_'.$req[0], $req[1]),JSON_PRETTY_PRINT);
					} else {
						echo json_encode($gm->select_accounts('tbl_feedback_'.$req[0], null),JSON_PRETTY_PRINT);
					}
				break;
				// PULL DATA OF feedbacks table
				case 'feedbacks':
					if(count($req)>1){
						echo json_encode($gm->select_feedbacks('tbl_feedback_'.$req[0], $req[1]),JSON_PRETTY_PRINT);
					} else {
						echo json_encode($gm->select_feedbacks('tbl_feedback_'.$req[0], null),JSON_PRETTY_PRINT);
					}
				break;
				// PULL DATA of feedback forms and genform
				case 'forms':
					if(count($req)>1){
						echo json_encode($gm->select_forms('tbl_feedback_'.$req[0], $req[1]),JSON_PRETTY_PRINT);
					} else {
						echo json_encode($gm->select_forms('tbl_feedback_'.$req[0], null),JSON_PRETTY_PRINT);
					}
				break;
				// ADD form
				case 'addForms':
					$d = json_decode(base64_decode(file_get_contents("php://input")));
					echo json_encode($gm->add("tbl_feedback_forms", $d), JSON_PRETTY_PRINT);
				break;
				// ADD genform
				case 'addGenform':
					$d = json_decode(file_get_contents("php://input"));
					echo json_encode($gm->add("tbl_feedback_genform", $d), JSON_PRETTY_PRINT);
				break;
				// add Feedback
				case 'addFeedback':
					$d = json_decode(file_get_contents("php://input"));
					echo json_encode($gm->add("tbl_feedback_feedbacks", $d), JSON_PRETTY_PRINT);
				break;
				case 'deleteFeedback':
					$d = json_decode(base64_decode(file_get_contents("php://input")));
					echo json_encode($gm->delete("tbl_feedback_feedbacks", $d), JSON_PRETTY_PRINT);
				break;
				case 'feedbacklogin':
					$d = json_decode(base64_decode(file_get_contents("php://input")));
					echo json_encode($auth->feedback_login($d));
				break;

				default:
					http_response_code(400);
					echo "Invalid Route";
				break;
			}
		break;

		case 'GET':
			switch ($req[0]) {

				default:
				http_response_code(400);
				echo "Bad Request";
				break;
			}
		break;

		default:
			http_response_code(403);
			echo "Please contact the Systems Administrator";
		break;
	}
?>