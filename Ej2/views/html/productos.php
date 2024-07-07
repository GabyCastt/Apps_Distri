<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Productos</title>
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!-- Template Stylesheet -->
    <link href="../../public/css/style.css" rel="stylesheet">

    <style>
        .select2-container {
            width: 100% !important;
        }

        textarea {
            width: 100% !important;
        }
    </style>
</head>

<body>

    <!-- Botón para agregar productos -->
    <div class="container mt-3">
        <button class="btn btn-success mb-3" onclick="abrirModal('insertar')">Agregar Producto</button>
    </div>

    <!-- Tabla Productos -->
    <table class="table table-bordered table-striped table-hover table-responsive">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Acciones</th> <!-- Columna para botones de editar y eliminar -->
            </tr>
        </thead>
        <tbody id="cuerpoProductos">
        </tbody>
    </table>
    <!-- Fin de la tabla -->

    <!-- Modal para insertar/editar producto -->
    <div class="modal fade" id="modalProducto" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Insertar/Actualizar Producto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formProducto">
                        <input type="hidden" id="productoId" name="id">
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="form-group">
                            <label for="precio">Precio</label>
                            <input type="number" class="form-control" id="precio" name="precio" required>
                        </div>
                        <div class="form-group">
                            <label for="stock">Stock</label>
                            <input type="number" class="form-control" id="stock" name="stock" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <!-- Tu script principal -->
    <script src="../../public/js/main.js"></script>

    <script>
        function cargarProductos() {
            document.getElementById("cuerpoProductos").innerHTML = "";

            var xhr = new XMLHttpRequest();
            xhr.open("GET", "../../controllers/productos.controllers.php", true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    console.log("Respuesta del servidor: " + xhr.responseText);
                    var productos = JSON.parse(xhr.responseText);

                    productos.forEach(function(producto, index) {
                        var row = "<tr>";
                        row += "<td>" + (index + 1) + "</td>";
                        row += "<td>" + producto.nombre + "</td>";
                        row += "<td>" + producto.precio + "</td>";
                        row += "<td>" + producto.stock + "</td>";
                        row += "<td><button class='btn btn-sm btn-warning' onclick='abrirModal(\"editar\", " + JSON.stringify(producto) + ")'>Editar</button> <button class='btn btn-sm btn-danger' onclick='eliminarProducto(" + producto.id + ")'>Eliminar</button></td>";
                        row += "</tr>";
                        document.getElementById("cuerpoProductos").innerHTML += row;
                    });
                }
            };
            xhr.send();
        }

        window.onload = function() {
            cargarProductos();
        };

        function abrirModal(modo, producto = null) {
            if (modo === "insertar") {
                document.getElementById("formProducto").reset();
                document.getElementById("productoId").value = "";
                $('#modalProducto').modal('show');
            } else if (modo === "editar") {
                document.getElementById("productoId").value = producto.id;
                document.getElementById("nombre").value = producto.nombre;
                document.getElementById("precio").value = producto.precio;
                document.getElementById("stock").value = producto.stock;
                $('#modalProducto').modal('show');
            }
        }

        document.getElementById("formProducto").onsubmit = function(event) {
            event.preventDefault();

            var id = document.getElementById("productoId").value;
            var nombre = document.getElementById("nombre").value;
            var precio = document.getElementById("precio").value;
            var stock = document.getElementById("stock").value;

            var datos = JSON.stringify({
                id: id,
                nombre: nombre,
                precio: precio,
                stock: stock
            });

            var xhr = new XMLHttpRequest();
            if (id) {
                xhr.open("PUT", "../../controllers/productos.controllers.php", true);
            } else {
                xhr.open("POST", "../../controllers/productos.controllers.php", true);
            }
            xhr.setRequestHeader("Content-Type", "application/json");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    $('#modalProducto').modal('hide');
                    cargarProductos();
                }
            };
            xhr.send(datos);
        };

        function eliminarProducto(id) {
            if (confirm("¿Está seguro de eliminar este producto?")) {
                var xhr = new XMLHttpRequest();
                xhr.open("DELETE", "../../controllers/productos.controllers.php", true);
                xhr.setRequestHeader("Content-Type", "application/json");
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        cargarProductos();
                    }
                };
                xhr.send(JSON.stringify({
                    id: id
                }));
            } else {
                return;
            }
        }
    </script>
</body>

</html>
