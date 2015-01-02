<?php

/**
	* This is the base class for all possible database classes to handle database connection
	* and queries. Default is mySQL database connection.	
	* @package HC-PHPLIB
	* @author Vu Hoang Huynh
	* @copyright Vu Hoang Huynh
	* @version 1.0
	*/
class Database
{

    /**
     * The hostname for the database connection
     * 
     * @var string
     */
    private $db_hostname;

    /**
     * The username for database connection
     * 
     * @var string
     */
    private $db_username;

    /**
     * The password for database connection
     * 
     * @var string
     */
    private $db_password;

    /**
     * The database name to use for this database connection
     * 
     * @var string
     */
    private $db_name;

    /**
     * The most recent INSERT id
     * 
     * @var number
     */
    private $most_recent_insert_id;

    /**
     * The class constructor
     * 
     * @param string $db_hostname
     *            The hostname to connect to the database
     * @param string $db_username
     *            The username to connect to the database
     * @param string $db_password
     *            The password to use to connect to database
     * @param string $db_name
     *            The database name to use
     * @return Database An instance of the Database class
     */
    public function __construct ($db_hostname, $db_username, $db_password, 
            $db_name)
    {
        $this->db_hostname = $db_hostname;
        $this->db_username = $db_username;
        $this->db_password = $db_password;
        $this->db_name = $db_name;
    }

    /**
     * Creates a database connection bases on the information provided
     * 
     * @return mixed An object which represents the connection to a database
     *         server or FALSE if the connection failed.
     */
    public function connect ()
    {
        $ret_value = "";
        
        try {
            $ret_value = new mysqli($this->db_hostname, $this->db_username, 
                    $this->db_password, $this->db_name);
            
            if (mysqli_connect_errno())             // check the connection
            {
                echo $alert->displayError(
                        'Unable to connect to database server with error:' .
                                 mysqli_connect_error() . '');
            }
        } catch (Exception $e) {
            // throw an exception message
            // echo $alert->exception_message( $e->getFile(), $e->getLine(),
            // $e->getMessage() );
            
            echo $alert->displayError(
                    'Exception occurred on File "' . $e->getFile() . '", Line "' .
                             $e->getLine() . '" with Message "' .
                             $e->getMessage() . '"');
        }
        
        return $ret_value;
    }

    /**
     * Executes the statement from the given $query
     * 
     * @param string $query
     *            The query to execute
     * @return mixed The SQL result after the $query is executed. For SELECT
     *         query it'll be a resultset, TRUE on success for other query,
     *         FALSE otherwise
     */
    public function execute ($query)
    {
        $ret_value = "";
        
        $working_result = "";
        
        // Establish a database connect first
        $db = $this->connect();
        
        // Determine the different type of query
        $query_components = explode(" ", $query);
        
        $query_type = strtoupper($query_components[0]);
        
        $working_result = $db->query($query);
        
        // echo $query;
        
        switch ($query_type) {
            case "SELECT":
                if (! $working_result) {
                    die (
                            'Error in executing SELECT query:' . $query .
                                     ' with error ' . $db->error . '');
                }
                
                // Need to pass the result into a function to convert
                // mysql_fetch_assoc to a normal PHP array
                $ret_value = $this->convert_to_array($working_result);
                break;
            
            case "INSERT":
                if (! $working_result) {
                    die(
                            'Error in executing INSERT query: ' . $query .
                                     ' with error ' . $db->error . '');
                }
                
                $ret_value = $working_result;
                
                // Save the most recent INSERT id for use
                $this->most_recent_insert_id = $db->insert_id;
                
                break;
            
            case "UPDATE":
                if (! $working_result) {
                    die (
                            'Error in executing UPDATE query: ' . $query .
                                     ' with error' . $db->error . '');
                }
                
                $ret_value = $working_result;
                
                // Need to show the number of affected rows
                
                break;
            
            case "DELETE":
                if (! $working_result) {
                    echo 'Database.php', 
                            'execute( $query )', 
                            'Error in executing DELETE query: ' . $query .
                                     ' with error ' . $db->error . '</font>';
                }
                
                // Need to show the number of affected rows
                $ret_value = $working_result;
                break;
            
            case "CREATE":
                if (! $working_result) {
                    echo $alert->displayError('Database.php', 
                            'execute( $query )', 
                            'Error in executing CREATE query: ' . $query .
                                     ' with error ' . $db->error . '</font>');
                }
                
                $ret_value = $working_result;
                
                break;
            
            case "DROP":
                if (! $working_result) {
                    echo $alert->displayError('Database.php', 
                            'execute( $query )', 
                            'Error in executing DROP query: ' . $query .
                                     ' with error ' . $db->error . '</font>');
                }
                
                $ret_value = $working_result;
                
                break;
        }
        
        // Close the database connection
        $db->close();
        
        // Return the value
        return $ret_value;
    }

    /**
     * Converts a result set from mySQL to a multidimensional 2D array
     * 
     * @param string $mysql_result
     *            The result set with the current database connection to convert
     * @return array The array representation of the mySQL result set
     */
    public function convert_to_array ($result)
    {
        $ret_value = array();
        
        $i = 0;
        
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                foreach ($row as $key => $value) {
                    // display entities except for &lt; to prevent cross-site
                    // scripting attack
                    
                    $value = html_entity_decode($value, ENT_NOQUOTES);
                    $ret_value[$i][$key] = str_replace("<", "&lt;", $value);
                }
                $i ++;
            }
        }
        return $ret_value;
    }

    /**
     * This is the function that handles a select statement and return an array
     * of results if the query succeeds
     * 
     * @param string $table_name
     *            The name of the table to do the select on
     * @param array $arr_arguments
     *            The array of arguments to use for the select statement
     * @param array $arr_sort
     *            The array of sorting parameters
     * @return mixed The result set of the select statement on success or FALSE
     *         otherwise
     */
    public function select ($table_name, $arr_arguments, $arr_sort)
    {
        $ret_value = "";
        
        // -- Do all the data type validations before doing anything else ---\\
        if (! is_string($table_name)) { // check to make sure that the $table_name is a string
            echo $alert->displayError(
                    'Parameter with wrong data type: $table_name is NOT a string !!!');
        }
        
        if (! is_array($arr_arguments)) { // check to make sure that the $arr_arguments is an array
            echo $alert->displayError(
                    'Parameter with wrong data type: $arr_arguments is NOT an array !!!');
        }
        
        if (! is_array($arr_sort)) { // check to make sure that the $arr_sort is an array
            echo $alert->displayError(
                    'Parameter with wrong data type: $arr_sort is NOT an array !!!');
        }
        // ------------------ End of data validation
        // --------------------------\\
        
        // Build up the query
        $query = "SELECT * FROM $table_name WHERE ";
        
        // -- Begin build up the argument string --\\
        if (count($arr_arguments) == 0) { // If there is nothing in the arguments then just select everything
          // without conditions
            $query = "SELECT * FROM $table_name "; // change the query to
                                                   // reflects no arguments
        } else { // when there is more than one arguments
          // Need to parse the arguments to build the query from the argument
          // array
            $counter = 0;
            while ($element = each($arr_arguments)) {
                if ($counter == 0) { // first element
                    if (is_string($element['value'])) { // check if a string is the value, need to add slashes to
                      // escape any characters before sending to the database
                        if (! get_magic_quotes_gpc()) { // checks if magic_quotes_gpc is turned on or not
                            $query .= $element['key'] . '=' . '"' .
                                     $this->clean_data(
                                            (trim($element['value']))) . '"';
                        } else {
                            $query .= $element['key'] . '=' . '"' .
                                     $this->clean_data(
                                            trim($element['value'])) . '"';
                        }
                    } else { // when element is NOT a string
                        $query .= $element['key'] . '=' .
                                 $this->clean_data($element['value']);
                    }
                } else { // other elements
                    if (is_string($element['value'])) { // check if a string is the value, need to add slashes to
                      // escape any characters before adding to the database
                        if (! get_magic_quotes_gpc()) { // checks if magic_quotes_gpc is turned on or not
                            $query .= " AND " . $element['key'] . '=' . '"' .
                                     $this->clean_data(
                                            (trim($element['value']))) . '"';
                        } else {
                            $query .= " AND " . $element['key'] . '=' . '"' .
                                     $this->clean_data(
                                            trim($element['value'])) . '"';
                        }
                    } else { // when element is NOT a string
                        $query .= " AND " . $element['key'] . '=' .
                                 $this->clean_data($element['value']);
                    }
                }
                $counter ++;
            }
        }
        // -- End of build up the argument string --\\
        
        // -- Build up the sort string --\\
        if (count($arr_sort) > 0) { // only when there are more sort order that we need to sort,
          // otherwise, we do nothing
            $query .= " ORDER BY ";
            
            $counter = 0;
            while ($element = each($arr_sort)) {
                if ($counter == 0) { // first element
                    $query .= $element["key"] . " " .
                             $this->clean_data($element["value"]);
                } else { // other elements
                    $query .= ", " . $element["key"] . " " .
                             $this->clean_data($element["value"]);
                }
                
                $counter ++;
            }
        }
        // -- End of build up sort string --\\
        
        // echo $alert->displayInfo($query);
        
        // now I need to take this query and execute it
        $ret_value = $this->execute($query);
        
        // return the result recordsets now in array back to the user
        return $ret_value;
    }

    /**
     * This function allows insertion of data into a specified table
     * 
     * @param string $table_name
     *            The name of the table to insert data into
     * @param array $arr_values
     *            The array of values to use to insert data into
     * @return boolean true/1 if successful, false/0 otherwise
     */
    public function insert ($table_name, $arr_values)
    {
        $ret_value = "";
        
        // -- Do all the data type validations before doing anything else ---\\
        if (! is_string($table_name)) { // check to make sure that the $table_name is a string
            echo $alert->displayError(
                    'Parameter with wrong data type: $table_name is NOT a string !!!');
        }
        
        if (! is_array($arr_values)) { // check to make sure that the $arr_values is an array
            echo $alert->displayError(
                    'Parameter with wrong data type: $arr_values is NOT an array !!!');
        }
        
        if (count($arr_values) == 0) {
            echo $alert->displayError(
                    'Parameter with missing data: $arr_values MUST have at least one element for query to work.');
        }
        // ------------------ End of data validation
        // --------------------------\\
        
        // Build the query string
        $query = "INSERT INTO $table_name (";
        
        $count = 1;
        foreach ($arr_values as $key => $value) {
            if ($count == count($arr_values)) {
                $query .= $key;
            } else {
                $query .= $key . ",";
            }
            $count ++;
        }
        
        $query .= ") VALUES(";
        
        reset($arr_values); // reset the array
        
        if (count($arr_values) > 0) {
            $counter = 0;
            while ($element = each($arr_values)) {
                if ($counter == 0) { // first element
                    if (is_string($element['value'])) { // check if a string is the value, need to add slashes to
                      // escape any characters before adding to the database
                        if (! get_magic_quotes_gpc()) { // checks if magic_quotes_gpc is turned on or not
                            $query .= '"' .
                                     $this->clean_data(
                                            (trim($element['value']))) . '"';
                        } else {
                            $query .= '"' .
                                     $this->clean_data(
                                            trim($element['value'])) . '"';
                        }
                    } else { // when element is NOT a string
                        $query .= $this->clean_data($element['value']);
                    }
                } else { // other elements
                    if (is_string($element['value'])) { // check if a string is the value, need to add slashes to
                      // escape any characters before adding to the database
                        if (! get_magic_quotes_gpc()) { // checks if magic_quotes_gpc is turned on or not
                            $query .= ", " . '"' .
                                     $this->clean_data(
                                            (trim($element['value']))) . '"';
                        } else {
                            $query .= ", " . '"' .
                                     $this->clean_data(
                                            trim($element['value'])) . '"';
                        }
                    } else { // when element is NOT a string
                        $query .= ", " . $this->clean_data($element['value']);
                    }
                }
                $counter ++;
            }
        }
        $query .= ")";
        
        // now I need to take this query and execute it
        $ret_value = $this->execute($query);
        
        // return the result back to the user
        return $ret_value;
    }

    /**
     * This function allows update of record in a specified table
     * 
     * @param string $table_name
     *            The table to update records
     * @param array $arr_update
     *            The array of key/value with all the parameters need to be
     *            updated
     * @param array $arr_arguments
     *            The array of argument(s) to use when the update query runs
     * @return boolean true/1 if successful, false/0 otherwise
     */
    public function update ($table_name, $arr_update, $arr_arguments)
    {
        // -- Do all the data type validations before doing anything else ---\\
        if (! is_string($table_name)) { // check to make sure that the $table_name is a string
            echo $alert->displayError(
                    'Parameter with wrong data type: $table_name is NOT a string !!!');
        }
        
        if (! is_array($arr_update)) { // check to make sure that the $arr_arguments is an array
            echo $alert->displayError(
                    'Parameter with wrong data type: $arr_update is NOT an array !!!');
        }
        
        if (! is_array($arr_arguments)) { // check to make sure that the $arr_sort is an array
            echo $alert->displayError(
                    'Parameter with wrong data type: $arr_arguments is NOT an array !!!');
        }
        
        if (count($arr_update) == 0) {
            echo $alert->displayError(
                    'Parameter with missing data: $arr_update MUST have at least one element for query to work !!!');
        }
        
        if (count($arr_arguments) == 0) {
            echo $alert->displayError(
                    'Parameter with missing data: $arr_arguments MUST have at least one element for query to work !!!');
        }
        
        // ------------------ End of data validation
        // --------------------------\\
        
        $query = "UPDATE $table_name SET ";
        
        // -- Build up the update array --\\
        if (count($arr_update) > 0) { // Need to make sure at least one update key/value is there
            $counter = 0;
            while ($element = each($arr_update)) {
                if ($counter == 0) { // first element
                    if (is_string($element['value'])) { // check if a string is the value, need to add slashes to
                      // escape any characters before sending to the database
                        if (! get_magic_quotes_gpc()) { // checks if magic_quotes_gpc is turned on or not
                            $query .= $element['key'] . "=" . '"' .
                                     $this->clean_data(
                                            (trim($element['value']))) . '"';
                        } else {
                            $query .= $element['key'] . "=" . '"' .
                                     $this->clean_data(
                                            trim($element['value'])) . '"';
                        }
                    } else { // when element is NOT a string
                        $query .= $element['key'] . "=" .
                                 $this->clean_data($element['value']);
                    }
                } else { // other elements
                    if (is_string($element['value'])) { // check if a string is the value, need to add slashes to
                      // escape any characters before sending to the database
                        if (! get_magic_quotes_gpc()) { // checks if magic_quotes_gpc is turned on or not
                            $query .= ", " . $element['key'] . "=" . '"' .
                                     $this->clean_data(
                                            (trim($element['value']))) . '"';
                        } else {
                            $query .= ", " . $element['key'] . "=" . '"' .
                                     $this->clean_data(
                                            trim($element['value'])) . '"';
                        }
                    } else { // when element is NOT a string
                        $query .= ", " . $element['key'] . "=" .
                                 $this->clean_data($element['value']);
                    }
                }
                $counter ++;
            }
        }
        // -- End of build up update array --\\
        
        // -- Begin of build up arguments array --\\
        if (count($arr_arguments) > 0) {
            $query .= " WHERE ";
            
            $counter = 0;
            while ($element = each($arr_arguments)) {
                if ($counter == 0) { // first element
                    if (is_string($element['value'])) { // check if a string is the value, need to add slashes to
                      // escape any characters before sending to the database
                        if (! get_magic_quotes_gpc()) { // checks if magic_quotes_gpc is turned on or not
                            $query .= $element['key'] . "=" . '"' .
                                     $this->clean_data(
                                            (trim($element['value']))) . '"';
                        } else {
                            $query .= $element['key'] . "=" . '"' .
                                     $this->clean_data(
                                            trim($element['value'])) . '"';
                        }
                    } else { // when element is NOT a string
                        $query .= $element['key'] . "=" .
                                 $this->clean_data($element['value']);
                    }
                } else { // other elements
                    if (is_string($element['value'])) { // check if a string is the value, need to add slashes to
                      // escape any characters before sending to the database
                        if (! get_magic_quotes_gpc()) { // checks if magic_quotes_gpc is turned on or not
                            $query .= " AND " . $element['key'] . "=" . '"' .
                                     $this->clean_data(
                                            (trim($element['value']))) . '"';
                        } else {
                            $query .= " AND " . $element['key'] . "=" . '"' .
                                     $this->clean_data(
                                            trim($element['value'])) . '"';
                        }
                    } else { // when element is NOT a string
                        $query .= " AND " . $element['key'] . "=" .
                                 $this->clean_data($element['value']);
                    }
                }
                $counter ++;
            }
        }
        // -- End of build up arguments array --\\
        
        // echo $alert->displayInfo($query);
        
        // now I need to take this query and execute it
        $ret_value = $this->execute($query);
        
        // return the result back to the user
        return $ret_value;
    }

    /**
     * This function allows deletion of record from a specified table
     * 
     * @param string $table_name
     *            The table to delete record from
     * @param array $arr_arguments
     *            The array of argument(s) to use when delete record(s)
     * @return boolean true/1 if successful, false/0 otherwise
     */
    public function delete ($table_name, $arr_arguments)
    {
        // -- Do all the data type validations before doing anything else ---\\
        if (! is_string($table_name)) { // check to make sure that the $table_name is a string
            echo $alert->displayError(
                    'Parameter with wrong data type: $table_name is NOT a string !!!');
        }
        
        if (! is_array($arr_arguments)) { // check to make sure that the $arr_sort is an array
            echo $alert->displayError(
                    'Parameter with wrong data type: $arr_arguments is NOT an array !!!');
        }
        // ------------------ End of data validation
        // --------------------------\\
        
        // Build the query
        $query = "DELETE FROM $table_name ";
        
        if (count($arr_arguments) == 0) {
            echo $alert->displayError(
                    'Parameter with missing data: $arr_arguments MUST have at least one element for query to work !!!');
        }
        
        // -- Begin of build up argument array --\\
        if (count($arr_arguments) > 0) {
            $query .= " WHERE ";
            
            $counter = 0;
            while ($element = each($arr_arguments)) {
                if ($counter == 0) { // first element
                    if (is_string($element['value'])) { // check if a string is the value, need to add slashes to
                      // escape any characters before sending to the database
                        if (! get_magic_quotes_gpc()) { // checks if magic_quotes_gpc is turned on or not
                            $query .= $element['key'] . "=" . '"' .
                                     $this->clean_data(
                                            (trim($element['value']))) . '"';
                        } else {
                            $query .= $element['key'] . "=" . '"' .
                                     $this->clean_data(
                                            trim($element['value'])) . '"';
                        }
                    } else { // when element is NOT a string
                        $query .= $element['key'] . "=" .
                                 $this->clean_data($element['value']);
                    }
                } else { // other elements
                    if (is_string($element['value'])) { // check if a string is the value, need to add slashes to
                      // escape any characters before sending to the database
                        if (! get_magic_quotes_gpc()) { // checks if magic_quotes_gpc is turned on or not
                            $query .= " AND " . $element['key'] . "=" . '"' .
                                     $this->clean_data(
                                            (trim($element['value']))) . '"';
                        } else {
                            $query .= " AND " . $element['key'] . "=" . '"' .
                                     $this->clean_data(
                                            trim($element['value'])) . '"';
                        }
                    } else { // when element is NOT a string
                        $query .= " AND " . $element['key'] . "=" .
                                 $this->clean_data($element['value']);
                    }
                }
                $counter ++;
            }
        }
        // -- End of build up argument array --\\
        echo $query;
        
        // now I need to take this query and execute it
        $ret_value = $this->execute($query);
        
        // return the result back to the user
        return $ret_value;
    }

    /**
     * This function cleans the input data before inserting / updating the
     * database
     * 
     * @param string $original_data
     *            The original data to clean
     * @return string integer data that can be used to insert / update the
     *         database
     */
    public function clean_data ($original_data)
    {
        $ret_value = $original_data;
        
        // prevent SQL injection attacks
        if (! get_magic_quotes_gpc()) {
            $ret_value = mysqli_real_escape_string($this->connect(), $ret_value);
        }
        
        // prevent cross-site scripting attacks, need to apply filters for
        // incoming data from user
        $ret_value = htmlentities($ret_value);
        $ret_value = strip_tags($ret_value);
        
        // prevent shell attacks
        // $ret_value = escapeshellarg( $ret_value );
        
        return $ret_value;
    }
}
?>
