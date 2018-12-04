<?php
//Guardo inicio y fin de Login Empleado
//Guardo inicio y fin del Pedido
class horario
{
    public $idTiempo;
    public $idEmpleado;
    public $idPedido;    
    public $inicio;
    public $fin;

    public static function InsertHorarioPedido($idPedido)
	{        
        
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta('INSERT into horario (idPedido, inicio) values (:idPedido, NOW())');
        $consulta->bindParam(':idPedido',$idPedido);
        $consulta->execute();
        return $objetoAccesoDato->RetornarUltimoIdInsertado();    
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



    

}
?>