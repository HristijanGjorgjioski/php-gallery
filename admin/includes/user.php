<?php  
require_once("new_config.php");

class User extends Db_object {
	protected static $db_table = "users";
	protected static $db_table_fields = array('username', 'password', 'first_name', 'last_name', 'user_image');
	public $id;
	public $username;
	public $password;
	public $first_name;
	public $last_name;
	public $user_image;
	public $upload_directory = "images";
	public $image_placelohder = "http://placehold.it/400x400&text=image";

	public function upload_photo() {

			if(!empty($this->errors)) {
				return false;
			} 

			if(empty($this->user_image) || empty($this->tmp_path)) {
				$this->errors[] = "The file was not avivable";
				return false;
			}

			$target_path = SITE_ROOT . DS . 'admin' . DS . $this->upload_directory . DS . $this->user_image;

			if(file_exists($target_path)) {
				$this->errors[] = "The file {$this->user_image} already exsist";
			}

			if(move_uploaded_file($this->tmp_path, $target_path)) {
				unset($this->tmp_path);
				return true;
			} else {
				$this->errors[] = "The folder probably does not have permission";
				return false;
			}

			$this->create();
	}

	public function image_path_and_placeholder() {
		return empty($this->user_image) ? $this->image_placelohder : $this->upload_directory.DS.$this->user_image;
	}

	public static function register_user($username, $last_name, $first_name, $password) {
		global $database;

		$username = $database->escape_string($username);
		$password = $database->escape_string($password);
		$first_name = $database->escape_string($first_name);
		$last_name = $database->escape_string($last_name);

		$sql = "INSERT INTO " . static::$db_table . " (username, last_name, first_name, password) VALUES ('$username', '$last_name', '$first_name', '$password')";

		if($database->query($sql)) {
			$id = $database->the_insert_id();

			return true;
		} else {
			return false;
		}
	}

	public static function verify_user($username, $password) {
		global $database;

		$username = $database->escape_string($username);
		$password = $database->escape_string($password);

		$sql = "SELECT * FROM " . static::$db_table . " WHERE ";
		$sql .= "username = '{$username}' ";
		$sql .= "AND password = '{$password}' ";
		$sql .= "LIMIT 1";

		$the_result_array = static::find_by_query($sql);

		return !empty($the_result_array) ? array_shift($the_result_array) : false;

	}

	public function ajax_save_user_image($user_image, $user_id) {
		global $database;

		$user_image = $database->escape_String($user_image);
		$user_id = $database->escape_String($user_id);

		$this->user_image = $user_image;
		$this->id = $user_id;

		$sql = "UPDATE " . self::$db_table . " SET user_image = '{$this->user_image}' WHERE id={$this->id}";
		$update_image = $database->query($sql);

		echo $this->image_path_and_placeholder();
	}

	public function delete_photo() {

		if ($this->delete()) {
			$target_path = SITE_ROOT.DS.'admin'.DS.$this->upload_directory.DS.$this->user_image;
			return unlink($target_path) ? true : false;
		} else {
			return false;
		}
	}


}?>