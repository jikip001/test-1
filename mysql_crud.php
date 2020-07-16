<?php
/*
 * @Author Rory Standley <rorystandley@gmail.com>
 * @Version 1.0
 * @Package Database
 */
class Database{
	/* 
	 * Create variables for credentials to MySQL database
	 * The variables have been declared as private. This
	 * means that they will only be available with the 
	 * Database class
	 */

	 /*
	private $db_host = "localhost";  // Change as required
	private $db_user = "";  // Change as required
	private $db_pass = "";  // Change as required
	private $db_name = "";	// Change as required
	*/

	/*
	 * Extra variables that are required by other function such as boolean con variable
	 */
	private $con; //connect
	private $charset = "UTF8"; // Charset Default: "UTF8"
	private $result = array(); // Any results from a query will be stored here
	private $numResults; // fetch
	
	// Fix set database by Aummua
	public function __construct($DBCONFIG){
		$this->db_host = $DBCONFIG['db_host'];
		$this->db_user = $DBCONFIG['db_user'];
		$this->db_pass = $DBCONFIG['db_pass'];
		$this->db_name = $DBCONFIG['db_name'];
	}
	
	// Function to make connection to database
	public function connect(){
		$this->con = new mysqli($this->db_host,$this->db_user,$this->db_pass,$this->db_name);  // mysql_connect() with variables defined at the start of Database class
		@mysqli_query($this->con,'SET NAMES '.$this->charset); // Set Charset
	}
	
	// Function to disconnect from the database
    public function disconnect(){
    	@mysqli_close($this->con);
    }
	
	public function sql($sql){
		$query = mysqli_query($this->con,$sql);
		if($query){
			// If the query returns >= 1 assign the number of rows to numResults
			$this->numResults = mysqli_num_rows($query);
			// Loop through the query results by the number of rows returned
			for($i = 0; $i < $this->numResults; $i++){
				$r = mysqli_fetch_array($query);
               	$key = array_keys($r);
               	for($x = 0; $x < count($key); $x++){
               		// Sanitizes keys so only alphavalues are allowed
                   	if(!is_int($key[$x])){
                   		if(mysqli_num_rows($query) > 1){
                   			$this->result[$i][$key[$x]] = $r[$key[$x]];
						}else if(mysqli_num_rows($query) < 1){
							$this->result = null;
						}else{
							$this->result[$key[$x]] = $r[$key[$x]];
						}
					}
				}
			}
			return true; // Query was successful
		}else{
			array_push($this->result,mysqli_error($this->con));
			return false; // No rows where returned
		}
	}
	
	// Function to SELECT from the database
    public function select($table, $rows = '*', $join = null, $where = null, $order = null){
    	// Create query from the variables passed to the function
    	$q = 'SELECT '.$rows.' FROM `'.$table .'`';
		if($join != null){
			$q .= ' JOIN '.$join;
		}
		if($where != null){
        	$q .= ' WHERE '.$where;
		}
        if($order != null){
            $q .= ' ORDER BY '.$order;
		}
		// Check to see if the table exists
        if($this->tableExists($table)){
        	// The table exists, run the query
        	$query = mysqli_query($this->con,$q);
			$this->numResults=$q;
			if($query){
				// If the query returns >= 1 assign the number of rows to numResults
				$this->numResults = mysqli_num_rows($query);
				// Loop through the query results by the number of rows returned
				for($i = 0; $i < $this->numResults; $i++){
					$r = mysqli_fetch_array($query);
                	$key = array_keys($r);
                	for($x = 0; $x < count($key); $x++){
                		// Sanitizes keys so only alphavalues are allowed
                    	if(!is_int($key[$x])){
                    		if(mysqli_num_rows($query) > 1){
                    			$this->result[$i][$key[$x]] = $r[$key[$x]];
							}else if(mysqli_num_rows($query) < 1){
								$this->result = null;
							}else{
								$this->result[$key[$x]] = $r[$key[$x]];
							}
						}
					}
				}
				return true; // Query was successful
			}else{
				array_push($this->result,mysqli_error($this->con));
				return false; // No rows where returned
			}
      	}else{
      		return false; // Table does not exist
    	}
    }
	
	// Function to insert into the database
    public function insert($table,$params=array()){
    	// Check to see if the table exists
    	 if($this->tableExists($table)){
    	 	$sql='INSERT INTO `'.$table.'` ('.implode(',',array_keys($params)).') VALUES ("' . implode('", "', $params) . '")';
            // Make the query to insert to the database
            if($ins = @mysqli_query($this->con,$sql)){
            	array_push($this->result,mysqli_insert_id($this->con));
                return true; // The data has been inserted
            }else{
            	array_push($this->result,mysqli_error($this->con));
                return false; // The data has not been inserted
            }
        }else{
        	return false; // Table does not exist
        }
    }
	
	//Function to delete table or row(s) from database
    public function delete($table,$where = null){
    	// Check to see if table exists
    	 if($this->tableExists($table)){
    	 	// The table exists check to see if we are deleting rows or table
    	 	if($where == null){
                $delete = 'DELETE `'.$table.'`'; // Create query to delete table
            }else{
                $delete = 'DELETE FROM `'.$table.'` WHERE '.$where; // Create query to delete rows
            }
            // Submit query to database
            if($del = @mysqli_query($this->con,$delete)){
            	array_push($this->result,mysqli_affected_rows($this->con));
                return true; // The query exectued correctly
            }else{
            	array_push($this->result,mysqli_error($this->con));
               	return false; // The query did not execute correctly
            }
        }else{
            return false; // The table does not exist
        }
    }
	
	// Function to update row in database
    public function update($table,$params=array(),$where){
    	// Check to see if table exists
    	if($this->tableExists($table)){
    		// Create Array to hold all the columns to update
            $args=array();
			foreach($params as $field=>$value){
				// Seperate each column out with it's corresponding value
				$args[]=$field.'="'.$value.'"';
			}
			// Create the query
			$sql='UPDATE `'.$table.'` SET '.implode(',',$args).' WHERE '.$where;
			// Make query to database
            if($query = @mysqli_query($this->con,$sql)){
            	array_push($this->result,mysqli_affected_rows($this->con));
            	return true; // Update has been successful
            }else{
            	array_push($this->result,mysqli_error($this->con));
                return false; // Update has not been successful
            }
        }else{
            return false; // The table does not exist
        }
    }
	
	// Private function to check if table exists for use with queries
	private function tableExists($table){
		$tablesInDb = @mysqli_query($this->con,'SHOW TABLES FROM `'.$this->db_name.'` LIKE \''.$table.'\'');
        if($tablesInDb){
        	if(mysqli_num_rows($tablesInDb)==1){
                return true; // The table exists
            }else{
            	array_push($this->result,$table." does not exist in this database");
                return false; // The table does not exist
            }
        }
    }
	
	// Public function to return the data to the user
    public function getResult(){
        $val = $this->result;
        $this->result = array();
        //$this->disconnect();
        return $val;
    }
    
	// Public function to return numrows()
    public function getNumrows(){
        $val = $this->numResults;
        //$this->disconnect();
        return $val;
    }
	
	public function clean_input($input) {
		if(get_magic_quotes_gpc()) {
			$input = stripslashes($input);
		}
		$input = strip_tags($input);
		$conoo = mysqli_escape_string($this->con,$input);
		return $conoo;
	}
	
	public function getconnect(){
		return $this->con;
	}
}
?>
