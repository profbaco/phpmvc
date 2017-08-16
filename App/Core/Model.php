<?php
require (LIBS . 'Database.php');

class Model {

    private static $db;
    protected static $table;

    /**
     * Faz a conexão com o banco de dados
     */
    private static function getConnection(){
        if(!isset(self::$db)){
            self::$db = new Database(_DB_TYPE,_DB_HOST,_DB_NAME,_DB_USER,_DB_PASS);
        }
    }

    /**
     * Verifica o relacionamento entre as tabelas
     * @param $t
     * @return mixed
     */
    public function getRelationship($t){
        self::getConnection();
        return self::$db->getRelationship($t);
    }


    /**
     * Seta a tabela destinada no Model
     * @param $table
     */
    public static function setTable($table){
        self::$table = $table;
    }

    /**
     * Traz todos os registros
     * @return mixed
     */
    public static function getAll(){
        self::getConnection();
        $sql = "SELECT * FROM ".static::$table.";";
        return $results = self::$db->select($sql);
    }

    /**
     * Utilizar a função WHERE para busca com restrições
     * @param $field
     * @param $value
     * @return mixed
     */
    public static function where($field, $value){
        self::getConnection();
        $sql = "SELECT * FROM ".static::$table." WHERE ".$field." = :".$field;
        $arrayToSend = array($field=>$value);
        $results = self::$db->select($sql, $arrayToSend); // array(":".$field=>$value)
        return $results;
    }


    /**
     * Função para se criar um registro
     * @return array
     */
    public function create(){
        self::getConnection();

        $values = $this->getMyVars($this);
        $has_many = self::checkRelationship("has_many",$values);
        self::checkRelationship("has_one",$values);
        self::checkRelationship("known_as",$values);

        $result = self::$db->insert(static::$table,$values);

        if($result === true){
            $response = array('error'=>0,'getID'=> self::$db->lastInsertId(),'msg'=>  get_class($this).' Created');
            $this->setId( $response["getID"] ) ;
        }else{
            $response = array('error'=>1,'msg'=> 'Error '.$result);
        }
        if($has_many){
            $rStatus = self::saveRelationships($has_many);
            if($rStatus["error"]){

            }
        }

        return $response;
    }

    /**
     * Atualizar um registro no sistema
     * @param $id
     * @param $values
     * @return bool
     */
    public static function update($id, $values){
        self::getConnection();

        $result = self::$db->update(static::$table,$values,"id = ".$id);
        if($result){
            $r = true;
        } else {
            $r = false;
        }
        return $r;
    }

    /**
     * Salvar relacionamento entre as tabelas
     * @param $relationships
     * @return array
     */
    public function saveRelationships($relationships){
        $log = array("error"=>0,"trace"=>array());
        foreach ($relationships as $name => $rules) {
            if(isset($rules['relationships'])){
                foreach ($rules['relationships'] as $key => $relacion) {
                    $table = $rules["join_table"];
                    $result = self::$db->insert($table,$relacion);
                }
            }
        }
        return $log;
    }

    /**
     * Relacionamento PARA MUITOS
     * @param $rName
     * @param $obj
     */
    public function has_many($rName,$obj){
        $has_many = $this->getHas_many();
        if($has_many[$rName] != null){
            $rule = $has_many[$rName];
            $rule['my_key'] = ucfirst($rule['my_key']);
            $rule['other_key'] = ucfirst($rule['other_key']);
            if(get_class($obj) == $rule["class"]){
                if( $this->{"get".$rule['my_key']}()
                    != null && $obj->{"get".$rule['other_key']}() != null ){
                    $rule['relationships'][]= array(
                        $rule['join_self_as']=>$this->{"get".$rule['my_key']}(),
                        $rule['join_other_as']=>$obj->{"get".$rule['other_key']}()
                    );
                    $has_many[$rName] = $rule;
                    $this->setHas_many($has_many);
                }else{
                    print("chaves primárias são necessários para o relacionamento");
                }
            }else{
                print("Ele não atende o tipo de objeto");
            }
        }else{
            print("Tipo de relacionamento não encontrado");
        }
    }

    /**
     * Relacionamento PARA UM
     * @param $rName
     * @param $obj
     */
    public function has_one($rName,$obj){
        $relacion = $this->getHas_one();
        if( isset($relacion[$rName]) ){

            $rule = $relacion[$rName];
            if(get_class($obj) == $rule["class"]){
                $this->{"set".ucfirst($rule["join_as"])}($obj->{"get".ucfirst($rule["join_with"])}());
            }else{
                print("Não cumpre o tipo de relacionamento");
            }

        }else{
            print("Não existe este tipo de relação");
        }
    }


    /**
     * Relacionamento de DADOS
     * @param $rName
     * @param $obj
     * @param bool $update
     */
    public function known_as($rName,$obj,$update = true){
        $relacion = $this->getKnown_as();
        if( isset($relacion[$rName]) ){

            $rule = $relacion[$rName];
            if(get_class($obj) == $rule["class"]){

                print_r( "set".ucfirst($rule["join_with"]) );
                $obj->{"set".ucfirst($rule["join_with"])}($this->{"get".ucfirst($rule["join_as"])}());
                $obj->update();

            }else{
                print("Não cumpre o tipo de relacionamento");
            }

        }else{
            print("Não existe este tipo de relação");
        }
    }


    /**
     * Determinar um atributo a um determinado registro
     * @param $attr
     * @param $value
     */
    public function set($attr,$value){
        $this->{$attr} = $value;
    }


    /**
     * Checar o relacinamento
     * @param $rType
     * @param $data
     * @return bool
     */
    public function checkRelationship($rType,&$data){
        if( isset($data[$rType]) ){
            $relationship = $data[$rType];
            unset($data[$rType]);
            return $relationship;
        }else{
            return false;
        }
    }

    /**
     * Excluir um registro
     * @param $id
     * @return array
     */
    public static function delete($id){
        self::getConnection();
        $result = self::$db->delete(static::$table,"id = ".$id);

        if($result){
            $result = array('error'=>0,'message'=>'Objeto Eliminado');
        }else{
            $result = array('error'=>1,'message'=> self::$db->getError());
        }
        return $result;
    }

    /**
     * Selecionar um registro pelo ID
     * @param $id
     * @return object
     */
    public static function getById($id){
        $paramsReference = self::where("id",$id);
        $data = array_shift($paramsReference);
        #$result = self::instanciate($data);
        $result = $paramsReference->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    /**
     * Selecionar um registro por outro campo
     * @param $field
     * @param $data
     * @return object
     */
    public static function getBy($field,$data){
        $paramsReference = self::where($field,$data);
        $data = array_shift($paramsReference);
        $result = self::instanciate($data);
        return $result;
    }


    /**
     * Pegando o atributo de uma determinada tabela
     * @param $table
     * @return mixed
     */
    public function getAttrTable($table){
        self::getConnection();
        $attr = self::$db->getAttr($table);
        return $attr;
    }


    /**
     * Converter dados em ARRAY
     * @return array
     */
    public function toArray(){
        $arr = [];
        foreach ($this->getMyVars() as $key => $value) {
            if($key != "has_one" && $key != "has_many"){
                $arr[$key] = $this->{"get".$key}();
            }
        }
        return $arr;
    }

    /**
     * Instanciando um arbumento
     * @param $args
     * @return object
     */
    public static function instanciate($args){

        if (count($args) > 1)
        {
            $refMethod = new ReflectionMethod(get_called_class(),  '__construct');
            $params = $refMethod->getParameters();
            $re_args = array();
            foreach($params as $key => $param)
            {
                if ($param->isPassedByReference())
                {
                    $re_args[$param->getName()] = &$args[$param->getName()];
                }
                else
                {
                    $re_args[$param->getName()] = $args[$param->getName()];
                }
            }

            $refClass = new ReflectionClass(get_called_class());
            return $refClass->newInstanceArgs((array) $re_args);
        }
    }
}