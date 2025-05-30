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

const loadContratos = async () => {
  const tbodyTabla = document.getElementById("body-tabla-contratos");
  tbodyTabla.innerHTML = `<tr><td colspan="9" class="text-center">Cargando contratos...</td></tr>`;

  try {
    const response = await fetch(
      `http://localhost/pagos-web/app/controllers/ContratoController.php`
    );

    if (!response.ok) {
      const errorText = await response.text();
      let errorMessage = "Error desconocido al cargar los contratos.";
      try {
        const errorData = JSON.parse(errorText);
        errorMessage = errorData.message || errorMessage;
      } catch (e) {
        errorMessage = `Error: ${response.status} ${response.statusText}. Respuesta: ${errorText}`;
      }

      tbodyTabla.innerHTML = `<tr><td colspan="9" class="text-center text-danger">Error al cargar los contratos: ${errorMessage}</td></tr>`;
      showToast("error", `Error al cargar los contratos: ${errorMessage}`);
      return;
    }

    const result = await response.json();

    if (result.status && result.data && result.data.length > 0) {
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
                                <a href="javascript:void(0);" class="btn btn-sm btn-danger delete-btn me-2" data-id="${
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

      attachDeleteEventListeners();
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

const attachDeleteEventListeners = () => {
  document.querySelectorAll(".delete-btn").forEach((link) => {
    link.removeEventListener("click", handleDeleteClick);
    link.addEventListener("click", handleDeleteClick);
  });
};

const handleDeleteClick = (event) => {
  event.preventDefault();
  const idcontrato = event.target.closest(".delete-btn").dataset.id;
  confirmDelete(idcontrato);
};

const confirmDelete = (idcontrato) => {
  Swal.fire({
    title: "¿Estás seguro?",
    text: "¡Esto eliminará el contrato y todos sus pagos asociados!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#3085d6",
    confirmButtonText: "Sí, eliminar",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (result.isConfirmed) {
      deleteContrato(idcontrato);
    }
  });
};

const deleteContrato = async (idcontrato) => {
  try {
    const response = await fetch(
      `http://localhost/pagos-web/app/controllers/ContratoController.php?id=${idcontrato}`,
      {
        method: "DELETE",
      }
    );

    const result = await response.json();

    if (response.ok && result.status) {
      showToast(
        "success",
        result.message || "Contrato eliminado exitosamente."
      );
      loadContratos();
    } else {
      showToast(
        "error",
        result.message || "Hubo un error al eliminar el contrato."
      );
    }
  } catch (error) {
    console.error("Error al eliminar contrato:", error);
    showToast(
      "error",
      "Error de conexión al intentar eliminar el contrato. Intenta de nuevo más tarde."
    );
  }
};

document.addEventListener("DOMContentLoaded", loadContratos);
