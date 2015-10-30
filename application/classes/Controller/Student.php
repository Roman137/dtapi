<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Student Controller for handle AJAX requests
 *
 */

class Controller_Student extends Controller_BaseAdmin {

	protected $modelName = "Student";
	
	public function action_insertData()
	{
		if (!Auth::instance()->logged_in($this->ADMIN_ROLE))
		{
			throw new HTTP_Exception_403("You don't have permissions to insert records");
		}
		else 
		{
			$result = array();
			// Read POST data in JSON format
			$params = json_decode(file_get_contents($this->RAW_DATA_SOURCE));
			
			// Convert Object into Array
			$paramsArr = get_object_vars($params);
									
			//$values = array_values($paramsArr);
			$values = $paramsArr;
			
			$model = Model::factory($this->modelName)->registerRecord($values);
			if (!is_string($model) && is_int($model))
			{
				// Creating response in JSON format
				$result["id"] = $model;
				$result["response"] = "ok";
			}
			else
			{
				if (is_string($model))
				{
					$result["response"] = $model;
				}
				else
				{
					$result["response"] = "error";
				}
					
			}
			$this->response->body(json_encode($result));
		}
	}
	
	public function action_update()
	{
		if (!Auth::instance()->logged_in($this->ADMIN_ROLE))
		{
			throw new HTTP_Exception_403("You don't have permissions to insert records");
		}
		else 
		{
			$record_id = $this->request->param("id");
			$results = array();
			
			// check input parameters
			if ((!isset($record_id)) || (!is_numeric($record_id)) || ($record_id <= 0))
			{
				$result["response"] = "Error: Wrong request";
			}
			else
			{
				// Read POST data in JSON format
				$params = json_decode(file_get_contents($this->RAW_DATA_SOURCE));
				
				// Convert Object into Array
				$paramsArr = get_object_vars($params);
					
				//$values = array_values($paramsArr);
				$values = $paramsArr;
				array_unshift($values, $record_id); // Add record_id value for Primary Key
				
				$model = Model::factory($this->modelName)->updateRecord($values);
				if (!is_string($model) && $model)
				{
					// Creating response in JSON format
					$result["response"] = "ok";
				}
				else
				{
					if (is_string($model))
					{
						$result["response"] = $model;
					}
					else
					{
						$result["response"] = "error";
					}
						
				}
			}
			$this->response->body(json_encode($result));
		}
	}
	
	public function action_del() 
	{
		if (!Auth::instance()->logged_in($this->ADMIN_ROLE))
		{
			throw new HTTP_Exception_403("You don't have permissions to delete records");
		}
		else
		{
			$record_id = $this->request->param("id");
			$results = array();
			// check input parameters
			if ((!isset($record_id)) || (!is_numeric($record_id)) || ($record_id <= 0))
			{
				$results["response"] = "Error: Wrong request";
			}
			else 
			{
				// try to delete information from student table
				$model = Model::factory($this->modelName)->eraseRecord($record_id);
				if (!is_string($model) && $model)
				{
					// Everything is OK! then we can delete information from users table
					try {
						$model = ORM::factory("User", $record_id);
						$model->delete();
						$this->response->body(json_encode(array("response" => "ok")));
					} catch (Kohana_Exception $e) {
						$this->response->body(json_encode(array("response" => $e->getMessage())));
					}
				}
				else
				{
					// Some problem
					$this->response->body(json_encode(array("response" => "error")));
				}
			}
		}
	} // end of action_del
	
	public function action_getStudentsByGroup()
	{
		return $this->getEntityRecordsBy("getStudentsByGroup");
	}

}
