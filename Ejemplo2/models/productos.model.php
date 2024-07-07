<?php
require_once('../config/conexion.php');

class Clase_Productos{

    public function todos()
    {
        //instanciar la clase conectar
        $con = new Clase_Conectar();
        //usar el procedimiento para conectar
        $con = $con->Procedimiento_Conectar();
        //ejecutar la consulta
        $cadena = "SELECT * FROM `productos`";
        //guardar la consulta en una variable
        $todos = mysqli_query($con, $cadena);
        //cerrar la conexion
        $con->close();
        //retornar la consulta
        return $todos;

    }
    public function uno($id)
    {
        $con = new Clase_Conectar();
        $con = $con->Procedimiento_Conectar();
        $cadena = "SELECT * FROM `productos` WHERE `id` = $id";
        $todos= mysqli_query($con, $cadena);
        $con->close();
        return $todos;

    }
    public function insertar($nombre, $precio, $stock)
    {
        $con = new Clase_Conectar();
        $con = $con->Procedimiento_Conectar();
        $cadena = "INSERT INTO `productos` (`id`, `nombre`, `precio`, `stock`) VALUES (NULL, '$nombre', '$precio', '$stock')";
        $todos = mysqli_query($con, $cadena);
        $con->close();
        return $todos;

    }
    public function actualizar($id, $nombre, $precio, $stock)
    {
        $con = new Clase_Conectar();
        $con = $con->Procedimiento_Conectar();
        $cadena = "UPDATE `productos` SET `nombre` = '$nombre', `precio` = '$precio', `stock` = '$stock' WHERE `productos`.`id` = $id";
        $todos = mysqli_query($con, $cadena);
        $con->close();
        return $todos;

    }
    public function eliminar($id)
    {
        $con = new Clase_Conectar();
        $con = $con->Procedimiento_Conectar();
        $cadena = "DELETE FROM `productos` WHERE `productos`.`id` = $id";
        $todos= mysqli_query($con, $cadena);
        $con->close();
        return $todos;

    }
}