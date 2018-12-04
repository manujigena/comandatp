<?php
class empleado
{
    public $idEmpleado;    
    public $nombre;
    public $apellido;
    public $password;
    public $operaciones;
    public $idTipo;
    public $idEstado;
    

    /***
    -----------------------------------------------
    ---- Cambiar para mostrar lo pedido en Doc ----
    -----------------------------------------------
    ***/
    public static function SelectLosEmpleados()
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta(
            "select idEmpleado, nombre, apellido, password, operaciones, idTipo, idEstado
             from empleado");
		$consulta->execute();
		return $consulta->fetchAll(PDO::FETCH_CLASS, "empleado");		
	}

    public static function SelectUnEmpleado($idEmpleado) 
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        //$consulta =$objetoAccesoDato->RetornarConsulta("SELECT e.idEmpleado, e.nombre, e.apellido, e.password, e.operaciones, t.tipo as idTipo, es.descripcion as idEstado FROM empleado e INNER JOIN estado es on e.idEstado=es.idEstado INNER JOIN tipoempleado t on e.idTipo=t.idTipo where e.idEmpleado = $idEmpleado");
        $consulta =$objetoAccesoDato->RetornarConsulta("SELECT idEmpleado, nombre, apellido, password, operaciones, idTipo, idEstado FROM empleado where idEmpleado = $idEmpleado");
		$consulta->execute();
		$empleadoBuscado= $consulta->fetchObject('empleado');
		return $empleadoBuscado;
			
	}



    ///////SELECT COMPLETO CON TIEMPO///////
    ///////Trae todos los tiempos por el id q pase///////
    /*
    public static function SelectUnUsuarioCompleto($id) 
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta(
            "select * FROM usuario u INNER JOIN tiempo t on u.id =$id AND t.idUsuario =u.id");
        //$consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
        
        $usuarioBuscado= $consulta->fetchAll(PDO::FETCH_ASSOC);
        return $usuarioBuscado;
            
    }*/



    public static function Login($idEmpleado, $pass)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT * FROM `empleado` 
        WHERE idEmpleado =:idEmpleado and password=:pass");
        $consulta->bindValue(':idEmpleado', $idEmpleado, PDO::PARAM_INT);
        $consulta->bindValue(':pass', $pass, PDO::PARAM_STR);
        $consulta->execute();
        $usuarioLogeado=$consulta->fetchObject('empleado');
        return $usuarioLogeado;
        
    }

    public function InsertEmpleado()
	{
        
        
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta = $objetoAccesoDato->RetornarConsulta("INSERT into empleado (nombre, apellido, password, operaciones, idTipo, idEstado) values ('$this->nombre','$this->apellido','$this->password','$this->operaciones','$this->tipo','$this->estado')");
        $consulta->execute();
        return $objetoAccesoDato->RetornarUltimoIdInsertado();

	}

    public function DeleteEmpleado()
	{
        
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("UPDATE empleado set idEstado=12 WHERE idEmpleado='$this->idEmpleado'");
        //$consulta->bindValue(':idEmpleado',$idEmpleado, PDO::PARAM_INT);		
        $consulta->execute();
        return $consulta->rowCount();
	}

    public function PutEmpleado()
    {

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta = $objetoAccesoDato->RetornarConsulta('UPDATE empleado set nombre=:nombre, apellido=:apellido, idTipo=:idTipo, idEstado=:idEstado WHERE idEmpleado=:idEmpleado');
        $consulta->bindParam(':idEmpleado',$this->idEmpleado,PDO::PARAM_INT);
        $consulta->bindParam(':nombre',$this->nombre,PDO::PARAM_STR);
        $consulta->bindParam(':apellido',$this->apellido,PDO::PARAM_STR); 
        $consulta->bindParam(':idTipo',$this->idTipo,PDO::PARAM_INT); 
        $consulta->bindParam(':idEstado',$this->idEstado,PDO::PARAM_INT); 
              
        $consulta->execute();
        return $consulta->rowCount();
    }


}
?>