<?php 

class DB{
	private $_tableName;
	private static $writeDBConnect;
  private static $readDBConnect;

  public static function connectWriteDB() {
		if(self::$writeDBConnect === null) {
				self::$writeDBConnect = new PDO('mysql:host=127.0.0.1;dbname=digitmoni_app;charset=utf8', 'root', '');
        //'mysql:host='.$this->serverName .';dbname='.$this->dbname;
				self::$writeDBConnect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				self::$writeDBConnect->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		}

		return self::$writeDBConnect;
	}

	// Static Class Method to connect to DB to perform read only actions (read replicas)
	// handle the PDOException in the controller class to output a json api error
	public static function connectReadDB() {
		if(self::$readDBConnect === null) {
				self::$readDBConnect = new PDO('mysql:host=127.0.0.1;dbname=digitmoni_app;charset=utf8', 'root', '');
				self::$readDBConnect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				self::$readDBConnect->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		}

		return self::$readDBConnect;
	}

	public function findBy($fieldName, $fieldValue){

		$result = $this->find($fieldName,$fieldValue);
		if($result && $result[0]){
			$this->setValues($this, $result[0]);
		}
	}

	public function findAll(){
		$result = [];
		$databaseData = $this->find();

		if($databaseData){
			$classname = static::class;
			foreach($databaseData as $objectData){
				$object = new $classname(self::$readDBConnect);
				$object = $this->setValues($object, $objectData);
				$result[] = $object;
			}

			return $result;
		}
	}

	private function find($fieldName =' ', $fieldValue = ' '){
		$results = [];

		$preparedFields = [];
		$sql = "SELECT * FROM " .$this->_tableName;
		if($fieldName){
			$sql .= " WHERE " . $fieldName . " = :value";
			$preparedFields = ['value' =>$fieldName];
		}
		$stmt = self::connectReadDB()->prepare($sql);
		$stmt->execute($preparedFields);

		$databaseData = $stmt->fetchAll();

		return $databaseData;
	}

	public function setValues($object, $values){
		foreach($object->fields as $fieldName){
			$object->fieldName = $values[$fieldName];
		}
		return $object;
	}

	//public function deleteBy

}

