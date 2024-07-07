<?php
error_reporting(1);
require_once('../config/cors.php');
require_once('../models/productos.model.php');

$productos = new Clase_Productos();
$metodo = $_SERVER['REQUEST_METHOD'];

switch ($metodo) {
    case 'GET':
        if (isset($_GET["id"])) {

            $uno = $productos->uno($_GET["id"]);
            echo json_encode(mysqli_fetch_assoc($uno));
        } else {
            $datos = $productos->todos();
            $todos = array();
            while ($fila = mysqli_fetch_assoc($datos)) {
                array_push($todos, $fila);
            }
            echo json_encode($todos);
        }
        break;
    case 'POST':
        $datos = json_decode(file_get_contents('php://input'));

        if (!empty($datos->nombre) && isset($datos->precio) && isset($datos->stock)) {
            $insertar = $productos->insertar($datos->nombre, $datos->precio, $datos->stock);

            if ($insertar) {
                echo json_encode(array("message" => "Se insertó correctamente"));
            } else {
                echo json_encode(array("message" => "Error, no se insertó"));
            }
        } else {
            echo json_encode(array("message" => "Error, faltan datos"));
        }
        break;
    case "PUT":
        $datos = json_decode(file_get_contents('php://input'));

        if (!empty($datos->id) && !empty($datos->nombre) && isset($datos->precio) && isset($datos->stock)) {
            $actualizar = $productos->actualizar($datos->id, $datos->nombre, $datos->precio, $datos->stock);

            if ($actualizar) {
                echo json_encode(array("message" => "Se actualizó correctamente"));
            } else {
                echo json_encode(array("message" => "Error, no se actualizó"));
            }
        } else {
            echo json_encode(array("message" => "Error, faltan datos"));
        }
        break;
    case "DELETE":
        $datos = json_decode(file_get_contents('php://input'));

        if (!empty($datos->id)) {
            try {
                $eliminar = $productos->eliminar($datos->id);
                echo json_encode(array("message" => "Se eliminó correctamente"));
            } catch (Exception $th) {
                echo json_encode(array("message" => "Error, no se eliminó"));
            }
        } else {
            echo json_encode(array("message" => "Error, no se envió el id"));
        }
        break;
}
