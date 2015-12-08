<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Result Controller for handle AJAX requests
 *
 */

class Controller_Result extends Controller_BaseAjax {

	protected $modelName = "Result";
	
	public function action_update()
	{
		// nothing to do :-)
		$this->response->body(json_decode(array("response" => "error")));
	}
	
	public function action_getRecordsByStudent()
	{
		return $this->getEntityRecordsBy("getResultByStudent");
	}
	
	public function action_countTestPassesByStudent()
	{
		$student_id = $this->request->param("id");
		$test_id = $this->request->param("id1");
		$result = array();
		if (!is_numeric($student_id) || !is_numeric($test_id)) 
		{
			$result["response"] = "Wrong request";
		}
		else 
		{
			$numberOfRecords = Model::factory($this->modelName)->countTestPassesByStudent($student_id, $test_id);
			$result["numberOfRecords"] = $numberOfRecords;
			$this->response->body(json_encode($result));
		}
	}

}
