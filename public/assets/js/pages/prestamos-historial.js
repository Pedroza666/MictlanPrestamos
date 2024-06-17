let tblPrestamosActivos, tblPrestamosLiquidados;
const fecha_actual = document.querySelector("#fecha_actual");

document.addEventListener("DOMContentLoaded", function () {
  const commonOptions = {
    ajax: {
      url: base_url + "prestamos/listHistorial",
      dataSrc: function (json) {
        return json;
      },
    },
    columns: [
      {
        data: null,
        render: function (data, type) {
          return type === "display" ? generarBotones(data) : data;
        },
      },
      { data: "id" },
      {
        data: null,
        render: function (data, type) {
          if (type === "display") {
            return `<li>${data.identidad + ":" + data.num_identidad}</li>
                    <li>${data.nombre + " " + data.apellido}</li>
                    <li>Fecha: ${data.fecha}</li>
                    <li>Tasa de interés: ${data.tasa_interes}</li>
                    <li>Cuotas: ${data.cuotas}</li>`;
          }
          return data.nombre + " " + data.apellido + " " + data.num_identidad;
        },
      },
      { data: "importe" },
      { data: "modalidad" },
      { data: "vencimiento" },
      { data: "usuario" },
      {
        data: null,
        render: function (data, type) {
          if (type === "display") {
            return data.estado == "0"
              ? '<span class="badge bg-secondary">Liquidado</span>'
              : '<span class="badge bg-success">Activo</span>';
          }
          return data;
        },
      },
    ],
    responsive: true,
    language: {
      url: "//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json",
    },
    dom: "Bfrtip",
    buttons: ["copy", "csv", "excel", "pdf", "print"],
    createdRow: function (row, data, index) {
      if (data.vencimiento) {
        $(row).addClass(
          data.vencimiento < fecha_actual.value ? "vencido" : "por-vencer"
        );
      } else {
        $(row).addClass("liquidado");
      }
    },
    order: [[1, "desc"]],
  };

  tblPrestamosActivos = $("#tblPrestamosActivos").DataTable({
    ...commonOptions,
    ajax: {
      ...commonOptions.ajax,
      dataSrc: function (json) {
        return json.filter((data) => data.estado == "1");
      },
    },
  });

  tblPrestamosLiquidados = $("#tblPrestamosLiquidados").DataTable({
    ...commonOptions,
    ajax: {
      ...commonOptions.ajax,
      dataSrc: function (json) {
        return json.filter((data) => data.estado == "0");
      },
    },
    columnDefs: [
      {
        targets: 5,
        render: function (data, type, row) {
          return data === null ? "Préstamo liquidado" : data;
        },
      },
    ],
    createdRow: function (row, data, index) {
      if (data.vencimiento === null) {
        $(row).addClass("liquidado");
      } else {
        $(row).addClass(
          data.vencimiento < fecha_actual.value ? "vencido" : "por-vencer"
        );
      }
    },
  });

  $(document).on("click", ".btn-eliminar", function (e) {
    e.preventDefault();
    const form = $(this).closest("form");

    if (confirm("¿Estás seguro de que quieres eliminar este préstamo?")) {
      form.submit();
    }
  });
});

function generarBotones(data) {
  return `
    <a class="btn btn-primary" href="${base_url}prestamos/${data.id}/detail"><i class="fas fa-eye"></i></a>
    <form action="${base_url}prestamos/${data.id}" method="post" class="d-inline eliminar">
        <input type="hidden" name="${csrf_token.getAttribute("content")}" value="${csrf_hash.getAttribute("content")}" />
        <input type="hidden" name="_method" value="DELETE">
        <button type="submit" class="btn btn-danger btn-eliminar"><i class="fas fa-trash"></i></button>
    </form>
  `;
}
