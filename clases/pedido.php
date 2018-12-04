<?php
class pedido
{
    public $idPedido;
    public $idMesa;
    public $idEmpleado;
    public $codigo;
    public $nombreCte;
    public $foto;    
    public $idEstado;
    public $monto;


    public function InsertPedido()
	{        
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta = $objetoAccesoDato->RetornarConsulta("INSERT into pedido (idMesa, idEmpleado, codigo, nombreCte, foto, idEstado) values ('$this->idMesa','$this->idEmpleado','$this->codigo','$this->nombreCte','$this->foto','$this->idEstado')");
        $consulta->execute();
        return $objetoAccesoDato->RetornarUltimoIdInsertado();  
	}

    /***
    -----------------------------------------------
    ---- Cambiar para mostrar lo pedido en Doc ----
    -----------------------------------------------
    ***/
    //Por Socio & Mozo
    public static function SelectLosPedidos()
	{

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("select pr.idProducto, pr.descripcion, pr.monto as PrecioUnidad, pr.idTipo, pp.idPedido, pp.idEmpleado as PreparadoPor, pp.idEstado as EstadoProducto, pp.tEstimado, pp.cantidad, p.idMesa, p.idEmpleado as Mozo, p.codigo, p.nombreCte, p.foto, p.idEstado as EstadoFinal, p.monto as Total from producto pr INNER JOIN ped_prod pp on pr.idProducto=pp.idProducto INNER JOIN pedido p on p.idPedido=pp.idPedido INNER JOIN horario h on h.idPedido=pp.idPedido order by inicio asc");
        
        //$consulta =$objetoAccesoDato->RetornarConsulta("SELECT pr.idProducto as prod,pp.idProducto as produ FROM producto pr INNER JOIN ped_prod pp on pr.idProducto=pp.idProducto  where pr.idTipo=4");
        $consulta->execute();
        $listado = $consulta->fetchAll(PDO::FETCH_ASSOC);
        return $listado;
    }



    //Por tipo
    public static function SelectLosPedidosPorEmpleado($idTipo)
	{

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("select p.idPedido, pr.descripcion, pp.cantidad, pp.idEmpleado, pp.idEstado,p.idEstado as EstadoFinal, h.inicio, pp.tEstimado, pr.monto as PrecioUnidad, p.monto as Total from producto pr INNER JOIN ped_prod pp on pr.idProducto=pp.idProducto INNER JOIN pedido p on p.idPedido=pp.idPedido INNER JOIN horario h on h.idPedido=pp.idPedido where pr.idTipo=$idTipo order by inicio asc");
        
        //$consulta =$objetoAccesoDato->RetornarConsulta("SELECT pr.idProducto as prod,pp.idProducto as produ FROM producto pr INNER JOIN ped_prod pp on pr.idProducto=pp.idProducto  where pr.idTipo=4");
        $consulta->execute();

        $listado = $consulta->fetchAll(PDO::FETCH_ASSOC);
        return $listado;
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

    
    //Actualiza montos
    public static function ActualizarMontoTotal($idPedido,$acumulador)
	{
        
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("UPDATE pedido set monto=:acumulador where idPedido=:idPedido");	
        $consulta->bindValue(':idPedido',$idPedido, PDO::PARAM_INT);
        $consulta->bindValue(':acumulador',$acumulador, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta->rowCount();
    }
    

    //Para saber si el codigo generado ya existe
    public static function ValidarCodigoMesa($codigo)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT * from pedido where codigo = :codigo");
        $consulta->bindValue(':codigo', $codigo, PDO::PARAM_STR);
        $consulta->execute();
        
        return $consulta->rowCount();
        

    }

}
?>