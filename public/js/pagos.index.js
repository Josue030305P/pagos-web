document.addEventListener("DOMContentLoaded", async () => {
  const urlParams = new URLSearchParams(window.location.search);
  const idContrato = urlParams.get("idContrato");

  const tablaPagosBody = document.querySelector("#tabla-pagos tbody");
  const tablaPagosFooter = document.getElementById("table-footer");
  const contractDetailsContainer = document.getElementById(
    "contract-details-container"
  );
  const loadingDetailsMessage = document.getElementById("loading-details");
  const loadingCronogramaMessage = document.getElementById(
    "loading-cronograma-message"
  );
  const errorMessage = document.getElementById("error-message");

  if (!idContrato) {
    loadingDetailsMessage.style.display = "none";
    loadingCronogramaMessage.style.display = "none";
    errorMessage.textContent = "Error: ID de contrato no especificado.";
    errorMessage.style.display = "block";
    return;
  }



  try {
    const response = await fetch(`http://localhost/pagos-web/app/controllers/PagoController.php?id=${idContrato}`);
    loadingDetailsMessage.style.display = "none";
    loadingCronogramaMessage.style.display = "none";
    
    const result = await response.json();

    if (result.status) {
      const contrato = result.data;
      const cronograma = result.cronograma;

      contractDetailsContainer.innerHTML = `
            <div class="card border-info mb-3">
              <div class="card-header bg-info text-white d-flex align-items-center">
               <i class="fas fa-file-contract me-2"></i> Detalles del Contrato
              </div>
              <div class="card-body text-dark">
                <div class="row">
                  <div class="col-md-6">
                    <p class="card-text"><strong>ID Contrato:</strong> ${
                      contrato.idcontrato
                    }</p>
                    <p class="card-text"><strong>Beneficiario:</strong> ${
                      contrato.nombres
                    } ${contrato.apellidos}</p>
                    <p class="card-text"><strong>Monto a Financiar:</strong> S/ ${parseFloat(
                      contrato.monto
                    ).toLocaleString("es-PE", {
                      minimumFractionDigits: 2,
                      maximumFractionDigits: 2,
                    })}</p>
                    <p class="card-text"><strong>Interés Mensual:</strong> ${parseFloat(
                      contrato.interes
                    ).toLocaleString("es-PE", {
                      minimumFractionDigits: 2,
                      maximumFractionDigits: 2,
                    })}%</p>
                  </div>
                  <div class="col-md-6">
                    <p class="card-text"><strong>Número de Cuotas:</strong> ${
                      contrato.numcuotas
                    }</p>
                    <p class="card-text"><strong>Fecha Inicio:</strong> ${
                      contrato.fechainicio
                    }</p>
                    <p class="card-text"><strong>Día de Pago:</strong> ${
                      contrato.diapago
                    }</p>
                    <p class="card-text"><strong>Estado:</strong> ${
                      contrato.estado === "ACT"
                        ? '<span class="badge bg-success">Activo</span>'
                        : '<span class="badge bg-secondary">Finalizado</span>'
                    }</p>
                  </div>
                </div>
              </div>
            </div>
          `;

      let totalInteres = 0;
      let totalAbonoCapital = 0;
      let totalValorCuota = 0;

      if (cronograma && cronograma.length > 0) {
        tablaPagosBody.innerHTML = "";
        cronograma.forEach((item) => {
          const row = tablaPagosBody.insertRow();
          row.insertCell().textContent = item.ITEM;
          row.insertCell().textContent = item.FECHA_PAGO;
          row.insertCell().textContent = `S/ ${parseFloat(
            item.INTERES_DEL_PERIODO
          ).toLocaleString("es-PE", {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
          })}`;
          row.insertCell().textContent = `S/ ${parseFloat(
            item.ABONO_A_CAPITAL
          ).toLocaleString("es-PE", {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
          })}`;
          row.insertCell().textContent = `S/ ${parseFloat(
            item.VALOR_CUOTA
          ).toLocaleString("es-PE", {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
          })}`;
          row.insertCell().textContent = `S/ ${parseFloat(
            item.SALDO_CAPITAL
          ).toLocaleString("es-PE", {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
          })}`;

          totalInteres += item.INTERES_DEL_PERIODO;
          totalAbonoCapital += item.ABONO_A_CAPITAL;
          totalValorCuota += item.VALOR_CUOTA;
        });

        const totalRow = tablaPagosFooter.insertRow();
        totalRow.classList.add("total-row");
        totalRow.insertCell().setAttribute("colspan", "2");
        totalRow.cells[0].textContent = "TOTALES";
        totalRow.cells[0].style.textAlign = "center";
        totalRow.insertCell().textContent = `S/ ${totalInteres.toLocaleString(
          "es-PE",
          { minimumFractionDigits: 2, maximumFractionDigits: 2 }
        )}`;
        totalRow.insertCell().textContent = `S/ ${totalAbonoCapital.toLocaleString(
          "es-PE",
          { minimumFractionDigits: 2, maximumFractionDigits: 2 }
        )}`;
        totalRow.insertCell().textContent = `S/ ${totalValorCuota.toLocaleString(
          "es-PE",
          { minimumFractionDigits: 2, maximumFractionDigits: 2 }
        )}`;
        totalRow.insertCell().textContent = "";
      } else {
        tablaPagosBody.innerHTML = `<tr><td colspan="6" class="text-center">No se pudo generar el cronograma de pagos para este contrato.</td></tr>`;
        tablaPagosFooter.innerHTML = "";
      }
    } else {
      errorMessage.textContent =
        result.message ||
        "Contrato no encontrado o error al procesar la solicitud.";
      errorMessage.style.display = "block";
    }
  } catch (error) {
    console.error("Error al obtener cronograma:", error);
    loadingDetailsMessage.style.display = "none";
    loadingCronogramaMessage.style.display = "none";
    errorMessage.textContent = `Error al cargar el cronograma: ${error.message}.`;
    errorMessage.style.display = "block";
    tablaPagosBody.innerHTML = `<tr><td colspan="6" class="text-center text-danger">Error al cargar el cronograma. ${error.message}</td></tr>`;
    tablaPagosFooter.innerHTML = "";
  }
});
