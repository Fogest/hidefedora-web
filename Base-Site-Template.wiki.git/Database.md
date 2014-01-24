# Using the Database Class #
The Database.php class located in `phplib` makes it incredibly easy to interact with a Database. Simply initialize the Database in the setup.php file:

	include_once ("phplib/Database.php");
	$database = new Database(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

Due to the way this is laid out, you could theoretically have multiple Database connections opened.

# Various Methods Available #
The Database class provides quite a few options for ways in which you can interact with the Database. Below are a few ways in which you can interact with it.

## Execute() ##
Execute a simply query. This function does not provide access to any string cleaning, therefore it is suggested that this is only used for basic fetching otherwise a manual clean_data() function should be run prior. An example usage of execute() :

	$query = "SELECT * FROM song_requests";
	$song_result = $database->execute($query);
	$request_one = $song_result[0]['column'];

## Insert() ##
Used to simply insert data into a specific table. To insert you must give a `table name` and some `arguments` you'd like to insert. For example:

	$table = "song_requests";
	$args['song_name'] = $_POST['songName_Request'];
	$args['artist_name'] = $_POST['artistName_Request'];
	
	$result = $database->insert($table, $args);

The element used in `$args` is the column name.

## Update() ##
Update() is much like Insert(). In this case you need to specify the data being used in the update, and the `WHERE` element. Example:

	$args['status'] = $status;
	$where['id'] = $value;
	$database->update($table, $args, $where);

## Clean_data() ##
The clean_data() function can be used to simply clean user inputted data to ensure it is safe to be inserted into a database.