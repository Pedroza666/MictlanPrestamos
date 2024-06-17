let tblClientes;
document.addEventListener("DOMContentLoaded", function () {
  tblClientes = $("#tblClientes").DataTable({
    ajax: {
      url: base_url + "clientes/list",
      dataSrc: "",
    },
    columns: [
      {
        data: null,
        render: function (data, type) {
          if (type === "display") {
            return `<a class="btn btn-primary" href="${
              base_url + "clientes/" + data.id + "/edit"
            }"><i class="fas fa-edit"></i></a>
                        <form action="${
                          base_url + "clientes/" + data.id
                        }" method="post" class="d-inline eliminar">
                            <input type="hidden" name="${csrf_token.getAttribute(
                              "content"
                            )}" value="${csrf_hash.getAttribute("content")}" />
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i></button>
                        </form>`;
          }
          return data;
        },
      },
      { data: "id" },
      { data: "identidad" },
      { data: "num_identidad" },
      { data: "nombre" },
      { data: "apellido" },
      

      { data: "telefono" },
      { data: "correo" },
      { data: "direccion" },

      {
        data: null,
        render: function (data, type, row) {
          if (type === "display") {
            // Crear un enlace que abra la imagen en otra ventana o pestaña al hacer clic en la miniatura
            const fotoIdentificacionLink = `<a href="/prestamos/writable/uploads/clientes/${row.foto_identificacion}" target="_blank"><img src="/prestamos/writable/uploads/clientes/${row.foto_identificacion}" alt="Foto de Identificación" width="50" height="50"></a>`;

            return fotoIdentificacionLink;
          }
          return data;
        },
      },
      {
        data: null,
        render: function (data, type, row) {
          if (type === "display") {
            // Crear un enlace que abra la imagen de foto_garantia en otra ventana o pestaña al hacer clic en la miniatura
            const fotoIdentificacionReversoLink = `<a href="/prestamos/writable/uploads/clientes/${row.foto_identificacion_reverso}" target="_blank"><img src="/prestamos/writable/uploads/clientes/${row.foto_identificacion_reverso}" alt="Reverso Identifición" width="50" height="50"></a>`;

            return fotoIdentificacionReversoLink;
          }
          return data;
        },
      },
      {
        data: null,
        render: function (data, type, row) {
          if (type === "display") {
            // Crear un enlace que abra la imagen de foto_domicilio en otra ventana o pestaña al hacer clic en la miniatura
            const fotoDomicilioLink = `<a href="/prestamos/writable/uploads/clientes/${row.foto_domicilio}" target="_blank"><img src="/prestamos/writable/uploads/clientes/${row.foto_domicilio}" alt="Foto de Domicilio" width="50" height="50"></a>`;

            return fotoDomicilioLink;
          }
          return data;
        },
      },

      {
        data: null,
        render: function (data, type, row) {
          if (type === "display") {
            // Crear un enlace que abra la imagen de foto_cliente en otra ventana o pestaña al hacer clic en la miniatura
            const fotoClienteLink = `<a href="/prestamos/writable/uploads/clientes/${row.foto_cliente}" target="_blank"><img src="/prestamos/writable/uploads/clientes/${row.foto_cliente}" alt="Foto de Cliente" width="50" height="50"></a>`;

            return fotoClienteLink;
          }
          return data;
        },
      },

      { data: "nombre_referencia" },
      { data: "direccion_referencia" },
      { data: "telefono_referencia" },
      {
        data: null,
        render: function (data, type) {
          if (type === "display") {
            return `<span class="badge bg-success">Activo</span>`;
          }
          return data;
        },
      },
    ],
    responsive: true,
    language: {
      url: "//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json",
    },
  });

  tblClientes.on("draw", function () {
    let lista = document.querySelectorAll(".eliminar");
    for (let i = 0; i < lista.length; i++) {
      lista[i].addEventListener("submit", function (e) {
        e.preventDefault();
        eliminarRegistro(this);
      });
    }
  });
});

function eliminarRegistro(form) {
  Swal.fire({
    title: "Mensaje?",
    text: "Esta seguro de eliminar!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, Eliminar!",
  }).then((result) => {
    if (result.isConfirmed) {
      form.submit();
    }
  });
}
