document.addEventListener("DOMContentLoaded", async () => {
  const showToast = (icon, title) => {
    const Toast = Swal.mixin({
      toast: true,
      position: "top-end",
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true,
      didOpen: (toast) => {
        toast.addEventListener("mouseenter", Swal.stopTimer);
        toast.addEventListener("mouseleave", Swal.resumeTimer);
      },
    });

    Toast.fire({
      icon: icon,
      title: title,
    });
  };

  const cargarContratos = async () => {
    const tbodyTabla = document.getElementById("body-tabla-contratos");
    tbodyTabla.innerHTML = `<tr><td colspan="9" class="text-center">Cargando contratos...</td></tr>`;

    try {
      const response = await fetch(
        `http://localhost/pagos-web/app/controllers/ContratoController.php`
      );

      const result = await response.json();

      if (result.status) {
        tbodyTabla.innerHTML = "";
        result.data.forEach((contrato, index) => {
          const row = document.createElement("tr");
          row.innerHTML = `
                            <th scope="row">${index + 1}</th>
                            <td>${contrato.apellidos} ${contrato.nombres}</td>
                            <td>${parseFloat(contrato.monto).toFixed(2)}</td>
                            <td>${parseFloat(contrato.interes).toFixed(2)}%</td>
                            <td>${contrato.fechainicio}</td>
                            <td>${contrato.diapago}</td>
                            <td>${contrato.numcuotas}</td>
                            <td>${
                              contrato.estado === "ACT"
                                ? '<span class="badge bg-success">Activo</span>'
                                : '<span class="badge bg-secondary">Finalizado</span>'
                            }</td>
                            <td>
                                <a href="http://localhost/pagos-web/public/views/contrato/contrato.edit.php?id=${
                                  contrato.idcontrato
                                }" class="btn btn-sm btn-info me-2" title="Editar Contrato">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="#;" class="btn btn-sm btn-danger delete-btn me-2" data-id="${
                                  contrato.idcontrato
                                }" title="Eliminar Contrato">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                                <a href="http://localhost/pagos-web/public/views/pagos/index.php?idContrato=${
                                  contrato.idcontrato
                                }" class="btn btn-sm btn-primary" title="Ver Cronograma">
                                    <i class="fas fa-calendar-alt"></i>
                                </a>
                            </td>
                        `;
          tbodyTabla.appendChild(row);
        });
      } else {
        tbodyTabla.innerHTML = `<tr><td colspan="9" class="text-center">No se encontraron contratos.</td></tr>`;
        showToast(
          "info",
          result.message || "No se encontraron contratos registrados."
        );
      }
    } catch (error) {
      console.error("Error al obtener datos de contratos:", error);
      tbodyTabla.innerHTML = `<tr><td colspan="9" class="text-center text-danger">Error de conexión o datos inválidos.</td></tr>`;
      showToast(
        "error",
        "Error de conexión al cargar contratos. Intenta de nuevo más tarde."
      );
    }
  };

  cargarContratos();


//   const confirmDelete = (idcontrato) => {
//     Swal.fire({
//       title: "¿Estás seguro?",
//       text: "¡Esto eliminará el contrato y todos sus pagos asociados!",
//       icon: "warning",
//       showCancelButton: true,
//       confirmButtonColor: "#d33",
//       cancelButtonColor: "#3085d6",
//       confirmButtonText: "Sí, eliminar",
//       cancelButtonText: "Cancelar",
//     }).then((result) => {
//       if (result.isConfirmed) {
//         deleteContrato(idcontrato);
//       }
//     });
//   };

//   const deleteContrato = async (idcontrato) => {
//     try {
//       const response = await fetch(
//         `http://localhost/pagos-web/app/controllers/ContratoController.php?id=${idcontrato}`,
//         {
//           method: "DELETE",
//         }
//       );

//       const result = await response.json();

//       if (response.ok && result.status) {
//         showToast(
//           "success",
//           result.message || "Contrato eliminado exitosamente."
//         );
//         cargarContratos();
//       } else {
//         showToast(
//           "error",
//           result.message || "Hubo un error al eliminar el contrato."
//         );
//       }
//     } catch (error) {
//       console.error("Error al eliminar contrato:", error);
//       showToast(
//         "error",
//         "Error de conexión al intentar eliminar el contrato. Intenta de nuevo más tarde."
//       );
//     }
//   };
// });
});