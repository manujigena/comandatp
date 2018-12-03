<?php
class producto
{
    public $idProducto;
    public $descripcion;
    public $monto;
    public $idTipo;
    

    /***
    -----------------------------------------------
    ---- Cambiar para mostrar lo pedido en Doc ----
    -----------------------------------------------
    ***/
    public static function SelectLosProductos()
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta(
            "select idProducto, descripcion, monto, idTipo
             from producto");
		$consulta->execute();
		return $consulta->fetchAll(PDO::FETCH_CLASS, "producto");		
	}

    public static function SelectUnProducto($idProducto) 
	{
		/* $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("select idEmpleado, legajo, nombre, apellido, password, operaciones, idTipo, idEstado from empleado where legajo = $legajo");
		$consulta->execute();
		$empleadoBuscado= $consulta->fetchObject('empleado');
        return $empleadoBuscado; */
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("SELECT * FROM producto where idProducto = $idProducto");
		$consulta->execute();
		$empleadoBuscado= $consulta->fetchObject('producto');
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


    public function InsertProducto()
	{
        
        
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta = $objetoAccesoDato->RetornarConsulta("INSERT into producto (descripcion, monto, idTipo) values ('$this->descripcion', '$this->monto', '$this->idTipo')");
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

    public static function EliminarProducto($idProducto)
	{
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("
            delete 
            from producto 				
            WHERE idProducto=:idProducto");	
        $consulta->bindValue(':idProducto',$idProducto, PDO::PARAM_INT);		
        $consulta->execute();
        return $consulta->rowCount();
	}

    public function ModificarMontoProducto()
    {

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta = $objetoAccesoDato->RetornarConsulta('UPDATE producto set monto=:monto WHERE idProducto=:idProducto');
        $consulta->bindParam(':idProducto',$this->idProducto,PDO::PARAM_INT); 
        $consulta->bindParam(':monto',$this->monto);        
        return $consulta->execute();
    }


}
?>