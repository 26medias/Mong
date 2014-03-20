<?php
	class mong {
		function mong($dbname) {
			$this->dbname 		= $dbname;
			$this->mongo		= new Mongo();
			$this->db 			= $this->mongo->$dbname;
		}
		function insert($collection, $params=array()) {
			$collection = $this->db->$collection;
			$results 	= $collection->insert($params);
			return $results;
		}
		function remove($collection, $params=array()) {
			$collection = $this->db->$collection;
			$results 	= $collection->remove($params);
			return $results;
		}
		function update($collection, $params=array(), $update=array(), $options=array("upsert"=>true)) {
			if ($_argc == 1 && is_array($collection)) {
				$options = extend($collection, array(	// $collection is the name of the option array, due to legacy code
					"collection"=> "",
					"query"		=> array(),
					"data"		=> array(),
					"options"	=> array("upsert"=>true),
				));
				$collection = $this->db->$options["collection"];
				$results 	= $collection->update($options["query"], $options["data"], $options["options"]);
			} else {
				$collection = $this->db->$collection;
				$results 	= $collection->update($params, $update, $options);
			}
			return $results;
		}
		function count($collection, $params=array()) {
			$collection = $this->db->$collection;
			$results 	= $collection->find($params);
			return $results->count();
		}
		function distinct($collection, $key, $params=array()) {
			$collection = $this->db->$collection;
			$results 	= $collection->distinct($key, $params);
			return $results;
		}
		function find($collection, $params=array(), $sort=false, $skip=false, $limit=false, $fields=array()) {
			
			$_argv = func_get_args();
			$_argc = func_num_args();
			if ($_argc == 1 && is_array($collection)) {
				
				// Better code
				$options = extend($collection, array(	// $collection is the name of the option array, due to legacy code
					"collection"		=> "",
					"query"		=> array(),
					"fields"	=> array(),
					"options"	=> array(),
					"page"		=> false,
					"perpage"	=> 10,
					"limit"		=> false,
					"skip"		=> false,
					"sort"		=> false
				));
				
				// Get the data
				$collection 		= $options["collection"];
				$collection = $this->db->$collection;
				$results 	= $collection->find($options["query"],$options["fields"]);
				
				if ($options["page"]) {
					// We'll use the pagination
					$options["limit"]	= $options["perpage"]*1;
					$options["skip"]	= $options["perpage"]*$options["page"]-$options["perpage"];
				}
				
				if ($options["limit"]) {
					$results = $results->limit($options["limit"]);
				}
				if ($options["skip"]) {
					$results = $results->skip($options["skip"]);
				}
				if ($options["sort"]) {
					$results = $results->sort($options["sort"]);
				}
				
				$output = array();
				foreach ($results as $obj) {
					$__id = "\$id";
					$obj["_id"] = $obj["_id"]->$__id;
					array_push($output, $obj);
				}
				
				return $output;
				
			} else {
				// Legacy
				$collection = $this->db->$collection;
				$results 	= $collection->find($params,$fields);
				$count		= $collection->count($params);
				
				if ($sort !== false && $count > 0) {
					$results = $results->sort($sort);
				}
				if ($limit !== false && $count > 0) {
					$results = $results->limit($limit);
				}
				if ($skip !== false && $count > 0) {
					$results = $results->skip($skip);
				}
				
				$output = array();
				foreach ($results as $obj) {
					$__id = "\$id";
					$obj["_id"] = $obj["_id"]->$__id;
					array_push($output, $obj);
				}
				
				return $output;
			}
		}
		
		
		function paginationInfo($options) {
			$options = extend($options, array(
				"collection"		=>		"",
				"query"		=>		array(),
				"perpage"	=>		10,
				"page"		=>		1,
				"sub"		=>		false
			));
			
			//debug("options",$options);
			if ($options["sub"]) {
				$count = $this->countsub($options["collection"], $options["query"], $options["sub"]);
			} else {
				$count 	= $this->count($options["collection"], $options["query"]);
			}
			//debug("count",$count);
			$np		= ceil($count/$options["perpage"]);
			
			return array(
				"perpage"	=> $options["perpage"],
				"total"		=> $count,
				"pages"		=> $np
			);
		}
		function paginate($options) {
			$options = extend($options, array(
				"collection"		=>		"",
				"query"		=>		array(),
				"perpage"	=>		10,
				"page"		=>		1,
				"sub"		=>		false,
				"sort"		=>		false
			));
			
			$info	= $this->paginationInfo($options);
			
			$data	= $this->find($options);
			
			return array(
				"pagination"	=> $info,
				"data"			=> $data
			);
		}
	}
	
	// required
	function extend($params, $default) {
		if (!is_array($params)) {
			$params = json_decode(stripslashes($params), true);
		}
		foreach ($default as $paramName => $paramValue) {
			if (!array_key_exists($paramName, $params)) {
				$params[$paramName] = $paramValue;
			}
		}
		return $params;
	}
?>