// productos.js

function cargarProductos() {
    document.getElementById("cuerpoProductos").innerHTML = "";

    var xhr = new XMLHttpRequest();
    xhr.open("GET", "../controllers/productos.controllers.php", true);
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
        xhr.open("PUT", "../controllers/productos.controllers.php", true);
    } else {
        xhr.open("POST", "../controllers/productos.controllers.php", true);
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
        xhr.open("DELETE", "../controllers/productos.controllers.php", true);
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
