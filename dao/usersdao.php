<?php

//PENSADO PARA UNA TABLA "Users"
//CON COLUMNAS ID, USERNAME, PASSWORD, MAIL, TIPO, STATUS
//SUJETO A CAMBIOS

require_once __DIR__ .'/Core/Database.php';

    class user_model{
        //Clase para operaciones CRUD de usuarios
        private $conexion;

        public function __construct(){
            try
            {
                $this->conexion = new Database;
                $this->conexion = this->conexion->getInstance();
            }
            catch(Exception $e)
            {
                die($e->getMessage());
            }
        }

    //registro de usuarios
    public function register(user $data){
		try{
			//Sentencia SQL.
            //id autoincrementable?
            //datos deben ser proporcionados
			$sql = "INSERT INTO users (username, password, mail, tipo, status)
		        VALUES (?, ?, ?, ?, ?)";  // por default usuario normal?
            $stmt = $this->conexion->prepare($sql);
			$stmt->execute(
				array(
                    $data->username,
                    $data->password,
                    $data->mail,
                    'basico',   // Valor predeterminado para 'tipo'
                    1    // Valor predeterminado para 'status'
                )
			);
		} 
        catch (Exception $e){
			die($e->getMessage());
		}
	}

    //actualizar usuarios
    public function update($data,$id){
        try{
            $sql = "UPDATE users SET username = ?, password= ?, mail = ? WHERE id = :id" ; //ASUMIENDO QUE SON LAS UNICAS 3 VARIABLES MODIFICABLES
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute(                             //CONSIDERAR USAR BINDPARAM EN LUGAR DE EXECUTE
                array(
                    $data->username,
                    $data->password,
                    $data->mail
                )
            );
        }
        catch(Exception $e){
            die($e->getMessage());
        }
    }

    //borrado logico
    public function delete($id){
        try{
            $sql = "UPDATE users SET status = 0  WHERE id = :id" ; //ASUMIENDO QUE SON LAS UNICAS 3 VARIABLES MODIFICABLES
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute();
        }
        catch(Exception $e){
            die($e->getMessage());
        }
    }

    }

    //VENTAJAS "bindParam"
    //Control del tipo de dato: Puedes especificar explícitamente el tipo de dato que se espera para cada valor vinculado. Por ejemplo, PDO::PARAM_STR para cadenas de caracteres o PDO::PARAM_INT para enteros.

//Vinculación por referencia: Puedes vincular valores por referencia, lo que significa que los valores de las variables se pueden actualizar y reflejarán esos cambios en la consulta.

//Mayor flexibilidad: Puedes reutilizar la consulta preparada con diferentes valores sin necesidad de redefinirla.