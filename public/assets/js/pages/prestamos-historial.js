$(document).ready(function() {
  // Variable global para DataTable
  let tblPrestamos;

  // Función para inicializar DataTable
  function inicializarDataTable(estado) {
      // Configuración común para ambas tablas
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

      // Inicializar DataTable según el estado (activos o liquidados)
      tblPrestamos = $("#tblPrestamos").DataTable({
          ...commonOptions,
          ajax: {
              url: base_url + "prestamos/listHistorial",
              dataSrc: function (json) {
                  if (estado === 'activos') {
                      return json.filter((data) => data.estado == "1");
                  } else if (estado === 'liquidados') {
                      return json.filter((data) => data.estado == "0");
                  }
                  return json;
              },
          },
      });
  }

  // Función para generar botones de acciones
  function generarBotones(data) {
      let accionesHtml = `
          <a class="btn btn-primary" href="${base_url}prestamos/${data.id}/detail"><i class="fas fa-eye"></i></a>
          <form action="${base_url}prestamos/${data.id}" method="post" class="d-inline eliminar">
              <input type="hidden" name="${csrf_token.getAttribute("content")}" value="${csrf_hash.getAttribute("content")}" />
              <input type="hidden" name="_method" value="DELETE">
              <button type="submit" class="btn btn-danger btn-eliminar"><i class="fas fa-trash"></i></button>
          </form>
      `;

      // Verificar si hay cuotas pendientes solo para préstamos activos
      if (data.estado == "1" && data.cuotas > 0) {
          accionesHtml += `<script>
                              $(document).on("submit", ".eliminar", function(e) {
                                  e.preventDefault();
                                  alert("Este préstamo tiene cuotas pendientes y no se puede eliminar.");
                                  return false; // Evitar que se envíe el formulario
                              });
                          </script>`;
      }

      return accionesHtml;
  }

  // Manejar cambio en el selector de estado (activos o liquidados)
  $("#estadoSelector").on("change", function() {
      const estadoSeleccionado = $(this).val();
      tblPrestamos.clear().destroy(); // Limpiar y destruir la tabla actual
      inicializarDataTable(estadoSeleccionado); // Inicializar tabla con nuevo estado seleccionado
  });

  // Al cargar el documento, inicializar DataTable con préstamos activos por defecto
  inicializarDataTable('activos');
});
