<?php
class pedProd
{
    public $idPedido;
    public $idProducto;
    public $idEmpleado;       
    public $idEstado;
    public $tEstimado;
    public $cantidad;

    /*
    idEmpleado && tEstimado se hacen en modificacion por el q tome el pedido
    */
    
    public function InsertPedProd()
	{        
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta = $objetoAccesoDato->RetornarConsulta("INSERT into ped_prod (idPedido, idProducto, idEstado, cantidad) values ('$this->idPedido','$this->idProducto','$this->idEstado','$this->cantidad')");
        return $consulta->execute();
        //return $objetoAccesoDato->RetornarUltimoIdInsertado();    
        //return $consulta->rowCount();
	}

    /***
    -----------------------------------------------
    ---- Cambiar para mostrar lo pedido en Doc ----
    -----------------------------------------------
    ***/
    public static function SelectLosEmpleados()
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta(
            "select idEmpleado, legajo, nombre, apellido, password, operaciones, idTipo, idEstado
             from empleado");
		$consulta->execute();
		return $consulta->fetchAll(PDO::FETCH_CLASS, "empleado");		
	}

    public static function SelectUnEmpleado($legajo) 
	{
		/* $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("select idEmpleado, legajo, nombre, apellido, password, operaciones, idTipo, idEstado from empleado where legajo = $legajo");
		$consulta->execute();
		$empleadoBuscado= $consulta->fetchObject('empleado');
        return $empleadoBuscado; */
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("SELECT e.idEmpleado, e.legajo, e.nombre, e.apellido, e.password, e.operaciones, t.tipo as idTipo, es.descripcion as idEstado FROM empleado e INNER JOIN estado es on e.idEstado=es.idEstado INNER JOIN tipoempleado t on e.idTipo=t.idTipo where e.legajo = $legajo");
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



    public static function Login($legajo, $pass)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT * FROM `empleado` 
        WHERE legajo =:legajo and password=:pass");
        $consulta->bindValue(':legajo', $legajo, PDO::PARAM_INT);
        $consulta->bindValue(':pass', $pass, PDO::PARAM_STR);
        $consulta->execute();
        $usuarioLogeado=$consulta->fetchObject('empleado');
        return $usuarioLogeado;
        
    }

    public function InsertEmpleado()
	{
        
        
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta = $objetoAccesoDato->RetornarConsulta("INSERT into empleado (legajo, nombre, apellido, password, operaciones, idTipo, idEstado) values ('$this->legajo','$this->nombre','$this->apellido','$this->password','$this->operaciones','$this->tipo','$this->estado')");
        $consulta->execute();
        return $objetoAccesoDato->RetornarUltimoIdInsertado();

        /* $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("INSERT into empleado (legajo, nombre, apellido, password, operaciones, idTipo, idEstado)values(:legajo,:nombre,:apellido,:password,:operaciones,:idTipo,:idEstado)");
        $consulta->bindParam(':legajo',$this->legajo);
        $consulta->bindParam(':nombre', $this->nombre);
        $consulta->bindParam(':apellido', $this->apellido);
        $consulta->bindParam(':password', $this->password);
        $consulta->bindParam(':operaciones',$this->operaciones);
        $consulta->bindParam(':idTipo',$this->idTipo);
        $consulta->bindParam(':idEstado',$this->idEstado);
        $consulta->execute();	
                
        return $objetoAccesoDato->RetornarUltimoIdInsertado(); */

	}

    public static function EliminarUsuario($id)
	{
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("
            delete 
            from usuario 				
            WHERE id=:id");	
        $consulta->bindValue(':id',$id, PDO::PARAM_INT);		
        $consulta->execute();
        return $consulta->rowCount();
	}

    public function ModificarEstadoUsuario()
    {

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta = $objetoAccesoDato->RetornarConsulta('UPDATE usuario set estado=:estado WHERE id=:id');
        $consulta->bindParam(':id',$this->id,PDO::PARAM_INT); 
        $consulta->bindParam(':estado',$this->estado);        
        return $consulta->execute();
    }


}
?>