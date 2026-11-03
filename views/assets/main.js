document.addEventListener("DOMContentLoaded", function () {
    const formVenta = document.querySelector("#formCrearVenta");

    if (formVenta) {
        formVenta.addEventListener("submit", function (e) {
            e.preventDefault();

            let datos = new FormData(formVenta);

            fetch(formVenta.action, {
                method: "POST",
                body: datos
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === "success" || data.success) {
                    alert(data.mensaje || "Operación realizada con éxito");

                    // Abre automáticamente el ticket en una pestaña nueva
                    if (data.url_ticket || data.ticket_url) {
                        window.open(data.url_ticket || data.ticket_url, '_blank');
                    } else if (data.id_venta) {
                        window.open('ver_ticket.php?id=' + data.id_venta, '_blank');
                    }

                    if (data.redirect) {
                        window.location.href = data.redirect;
                    }
                } else {
                    alert(data.mensaje || "Ocurrió un error en el servidor");
                }
            })
            .catch(error => {
                console.error("Error en la petición AJAX:", error);
                alert("Hubo un error al procesar la solicitud.");
            });
        });
    }
});