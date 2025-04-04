$(function () {
  //funcion para calcular el total

  function calculateTotal() {
    let product_quantity = parseFloat($("#product_quantity").val()) || 0;
    let price = parseFloat($("#price").html()) || 0;
    let costo = parseFloat($("#cost").val()) || 0;

    let total = (price + costo);
    $("#total").html(total.toFixed(2));
  }

  // Eventos para actualizar el total cuando cambien los valores
  $("#product_quantity, #cost , #product_number").on(
    "input change keyup",
    function () {
      calculateTotal();
    }
  );

  $('#editStatusModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var saleId = button.data('id');
    var status = button.data('status');
    var modal = $(this);
    modal.find('#saleId').val(saleId);
    modal.find('#status').val(status);
});

$("#updatestatus").click(function (e) {
  e.preventDefault();
  let status = $("#status").val();
  let saleId = $("#saleId").val();

  $.ajax({
    url: "../models/SaleModel.php",
    type: "POST",
    async: true,
    data: { action: "updateStatus", status, saleId },
    success: function (response) {
      if (response != 0) {
        alert("Estado actualizado correctamente");
        location.reload();
      }
    },
    error: function (error) {
      console.log("Tenemos el siguiente error:", error);
    },
  })
});
  function validateStock() {
    let stock = parseInt($("#stock").html()) || 0;
    let quantity = parseInt($("#product_quantity").val()) || 0;
    
    if (quantity > stock) {
        alert("La cantidad no puede ser mayor al stock disponible (" + stock + ")");
        $("#product_quantity").val(stock);
        return false;
    }
    return true;
  } 
  $("#cost, #product_quantity").on("input change keyup", function() {
    validateStock();
  });

function validatePreciofinal(){
  let preciofinal = parseFloat($("#precioFinal").html()) || 0;
  let total = parseFloat($("#total").html()) || 0;

  if(preciofinal < total){
    alert("El precio final no puede ser menor a " + total);
  }
}
 


  // Buscar Cliente EN LA SECCION DE VENTA Y PRESUPUESTOS
  $("#client_dni").keyup(function (e) {
    e.preventDefault();

    let client_dni = $(this).val();
    let action = "clientSearch";

    $.ajax({
      url: "../models/ClientModel.php",
      type: "POST",
      async: true,
      data: { action, client_dni },
      success: function (response) {
        if (response == 0) {
          $("#client_id").val("");
          $("#client_name").val("");
          $("#client_phone").val("");
          $("#client_address").val("");
          $("#client_email").val("");
          $(".client_new_btn").slideDown(); // Aqui mostramos el boton de agregar
        } else {
          let data = $.parseJSON(response);

          $("#client_id").val(data.id);
          $("#client_name").val(data.name);
          $("#client_phone").val(data.phoneNumber);
          $("#client_address").val(data.address);
          $("#client_email").val(data.email);
          $(".client_new_btn").slideUp(); // Aqui ocultamos el boton de agregar un nuevo cliente
          $("#client_name").attr("disabled", "disabled");
          $("#client_phone").attr("disabled", "disabled");
          $("#client_address").attr("disabled", "disabled");
          $("#client_email").attr("disabled", "disabled");
          $("#client_register_div").slideUp(); // Aqui mostramos el boton de agregar
        }
      },
      error: function (error) {
        console.log("Tenemos el siguiente error:", error);
      },
    });
  });

  // Activamos los campos para registrar a un nuevo cliente desde el menu de ventas y presupuestos
  $("#client_new_btn").click(function (e) {
    e.preventDefault();
    $("#client_name").removeAttr("disabled");
    $("#client_phone").removeAttr("disabled");
    $("#client_address").removeAttr("disabled");
    $("#client_email").removeAttr("disabled");
    $("#client_register_div").slideDown();
  });

  $("#sale_btn").hide();
  $("#extra_btn").hide();
  $("#cancel_sale_btn").hide();
  $("#cancel_estimate_btn").hide();
  $("#estimate_btn").hide();

  // BOTON DE ANULAR VENTA
  $("#cancel_sale_btn").click(function (e) {
    e.preventDefault();

    let rows = $("#sale_detail tr").length;

    if (!rows > 0) {
      alert("Aún no has agregado productos para anular");
      return;
    }

    let action = "cancelSale";

    $.ajax({
      url: "../models/SaleModel.php",
      type: "POST",
      async: true,
      data: { action },
      success: function (response) {
        if (response != 0) {
          location.reload();
        }
      },
      error: function (error) {
        console.log("Tenemos el siguiente error:", error);
      },
    });
  });

  // BOTON DE ANULAR presupuesto
  $("#cancel_estimate_btn").click(function (e) {
    e.preventDefault();

    let rows = $("#sale_detail tr").length;
    let action = "cancelEstimate";

    if (!rows > 0) {
      alert("Aún no has agregado productos para anular");
      return;
    }

    $.ajax({
      url: "../models/EstimateModel.php",
      type: "POST",
      async: true,
      data: { action },
      success: function (response) {
        if (response != 0) {
          location.reload();
        }
      },
      error: function (error) {
        console.log("Tenemos el siguiente error:", error);
      },
    });
  });

  // Búsqueda de productos en la sección de ventas
  $("#product_number").keyup(function (e) {
    e.preventDefault();

    let product = $(this).val().trim();
    let action = /[0-9]/.test(product)
      ? "productInfowihtCode"
      : "productInfowihtName";

    if (/[0-9a-zA-Z]/.test(product)) {
      searchProduct(product, action);
    } else {
      console.log("El valor ingresado no contiene números ni letras");
      // Aquí puedes agregar alguna lógica adicional si el valor no contiene números ni letras
    }
  });

  // Búsqueda de productos en la sección de presupuesto
  $("#budget_product_code").keyup(function (e) {
    e.preventDefault();

    let product = $(this).val().trim();
    let action = /[0-9]/.test(product)
      ? "productInfowihtCode"
      : "productInfowihtName";

    if (/[0-9a-zA-Z]/.test(product)) {
      searchProduct(product, action);
    } else {
      console.log("El valor ingresado no contiene números ni letras");
      // Aquí puedes agregar alguna lógica adicional si el valor no contiene números ni letras
    }
  });

  // Agregar un producto al detalle de la venta
  $("#product_add").click(function (e) {
    e.preventDefault();

    if (!validateStock()) {
      return;
    }

    // Validar precio final solo al agregar
    let precioFinal = parseFloat($("#precioFinal").val()) || 0;
    let total = parseFloat($("#total").html()) || 0;

    if (precioFinal < total) {
        alert(`El precio final no puede ser menor a ${total.toFixed(2)}`);
        return;
    }

    if ($("#product_quantity").val() > 0) {
      // Obtener el valor actual de #product_number y limpiar espacios al inicio y final
      let productNumberValue = $("#product_number").val().trim();

      // Verificar si el valor no es un número
      if (!$.isNumeric(productNumberValue)) {
        // Asignar el valor actual de #name al campo de entrada #product_number
        $("#product_number").val($("#name").html().trim());
      }
      

      let product = $("#product_number").val().trim();
      let total = $("#precioFinal").val().trim();
      let price = $("#price").text();
      let estatus = $("#status").val();
      let cost = parseFloat($("#cost").val()).toFixed(2);
      let product_quantity = $("#product_quantity").val().trim();
      let action = "addProductToDetail";

      $.ajax({
        url: "../models/ProductModel.php",
        type: "POST",
        async: true,
        data: { action, product, product_quantity, total, price, cost , estatus},
        success: function (response) {
          if (response != "error") {
            let info = JSON.parse(response);
            console.log(info);

            $("#sale_detail").html(info.detalle);
            $("#detalle_totales").html(info.totales);
            $("#product_number").val("");
            $("#name").html("-");
            $("#stock").html("-");
            $("#product_quantity").val("0");
            $("#price").text("0.00");
            $("#total").text("0.00");
            $("#precioFinal").text("0.00");
            $("#cost").val("0.00");
            $("#status").val("comprado");
            $("#product_quantity").attr("disabled", "disabled");
            $("#product_add").slideUp();
          } else {
            error_reporting(E_ALL);
            ini_set("display_errors", 1);
            console.log("No hay dato");
          }
        },
        error: function (error) {
          console.log("Tenemos el siguiente error:", error);
        },
      });
    }
  });

  // Boton para agregar producto al detalle del presupuesto
  $("#add_product_estimate").click(function (e) {
    e.preventDefault();

    if (!validateStock()) {
      return;
    }

    // Validar precio final solo al agregar
    let precioFinal = parseFloat($("#precioFinal").val()) || 0;
    let total = parseFloat($("#total").html()) || 0;

    if (precioFinal < total) {
        alert(`El precio final no puede ser menor a ${total.toFixed(2)}`);
        return;
    }

    
    if ($("#product_quantity").val() > 0) {
      // Obtener el valor actual de #budget_product_code y limpiar espacios al inicio y final
      let productCodeValue = $("#budget_product_code").val().trim();

      // Verificar si el valor no es un número
      if (!$.isNumeric(productCodeValue)) {
        // Asignar el valor actual de #name al campo de entrada #budget_product_code
        $("#budget_product_code").val($("#name").html().trim());
      }

      // Obtener el valor actual de #budget_product_code después de la posible asignación
      let product = $("#budget_product_code").val().trim(); // Aquí se usa el valor de #budget_product_code

      let product_quantity = $("#product_quantity").val().trim();

      let action = "addProductToDetail";
      let total = $("#total").text();
      let price = $("#price").text();
      let cost = parseFloat($("#cost").val()).toFixed(2);
      let estatus = $("#status").val();
      let precioFinal = $("#precioFinal").val().trim();
      console.log(precioFinal);

      $.ajax({
        url: "../models/EstimateModel.php",
        type: "POST",
        async: true,
        data: { action, product, product_quantity, total, price, cost , precioFinal,estatus},
        success: function (response) {
          if (response != "error") {
            let info = JSON.parse(response);

            $("#sale_detail").html(info.detalle);
            $("#detalle_totales").html(info.totales);
            $("#product_number").val("");
            $("#name").html("-");
            $("#stock").html("-");
            $("#product_quantity").val("0");
            $("#price").text("0.00");
            $("#total").text("0.00");
            $("#precioFinal").text("0.00");
            $("#cost").val("0.00");
            $("#status").val("comprado");
            $("#product_quantity").attr("disabled", "disabled");
            $("#add_product_estimate").slideUp();
          } else {
            console.log("No hay dato");
          }
        },
        error: function (error) {
          console.log("Tenemos el siguiente error:", error);
        },
      });
    }
  });

  // Generar nueva venta
  $("#sale_btn").click(function (e) {
    e.preventDefault();

    let rows = $("#sale_detail tr").length;
    let action = "processSale"; // Acción que se pasa a SaleModel.php
    let client_dni = $("#client_dni").val().trim();

    let products = [];
    let total = 0; // Variable para el total de la venta

    // Recorrer las filas de la tabla de detalle de la venta
    $("#sale_detail tr").each(function () {
      let productId = $(this).find(".product_id").text().trim();
      let quantity = $(this).find(".product_quantity").text().trim();
      let price = $(this)
        .find(".price")
        .text()
        .trim()
        .replace("$", "")
        .replace(",", ""); // Obtener el precio de la celda, limpiando caracteres especiales
      if (productId && quantity && price) {
        let productTotal = parseFloat(price) * parseInt(quantity);
        products.push({ productId, quantity, price: parseFloat(price) });
        total += productTotal;
      }
    });

    if (rows <= 0) {
      alert("Aún no has agregado productos para generar una venta");
      return;
    }

    if (!client_dni) {
      alert("Aún no has seleccionado un cliente");
      return;
    }

    if (!confirm("Presiona aceptar para generar la venta")) {
      return false;
    }

    $.ajax({
      url: "../models/ClientModel.php",
      type: "POST",
      data: { action: "clientSearch", client_dni },
      success: function (response) {
        if (response == 0) {
          console.log("No hay datos");
          return;
        }
        let datos = $.parseJSON(response);

        $.ajax({
          url: "../models/SaleModel.php",
          type: "POST",
          async: true,
          data: {
            action,
            clientId: datos.id,
            products: JSON.stringify(products),
            total: total.toFixed(2),
          },
          success: function (response) {
            if (response != 0) {
              let info = JSON.parse(response);
              generateInvoicePDF(info.clientId, info.id, total);
              location.reload();
            } else {
              console.log("No hay datos");
            }
          },
          error: function (error) {
            console.log("Tenemos el siguiente error:", error);
          },
        });
      },
      error: function (error) {
        console.log("Tenemos el siguiente error:", error);
      },
    });
  });

  $("#extra_btn").click(function (e) {
    e.preventDefault();
    let rows = $("#sale_detail tr").length;

    const modalBody = document.querySelector(".modal-body");

    // Verificar si el formulario ya existe
    if( rows <= 0) {
      alert("Aún no has agregado productos para agregar costo adicional");
      return;
  }
    
    if (!modalBody.querySelector("#additionalCostsForm")) {
      // Crear el formulario dinámicamente
      const form = document.createElement("form");
      form.id = "additionalCostsForm";
      form.innerHTML = `
            <div class="form-group">
                <label>Tipo de Costo</label>
                <select id="additionalCostType" name="additionalCostType" class="form-control">
                    <option value="flete">Flete</option>
                    <option value="empaque">Empaque</option>
                    <option value="muestra">Muestra</option>
                    <option value="merma">Merma</option>
                    <option value="otros">Otro</option>
                </select>
            </div>
            <div class="form-group">
                <label>Monto</label>
                <input type="number" id="additionalCostAmount" name="additionalCostAmount" class="form-control" step="0.01" min="0">
            </div>
            <button type="button" class="btn btn-primary" id="addAdditionalCost">Agregar</button>
        `;

      // Agregar el formulario al modal
      modalBody.appendChild(form);
      console.log("Formulario dinámico agregado.");
    }


    // Mostrar el modal
    $("#additionalCostsModal").modal("show");
    });
    


    $(document).on("click", "#addAdditionalCost", function () {
      const tipo = $("#additionalCostType").val();
      const monto = $("#additionalCostAmount").val();

      if(!monto || monto <= 0 || !tipo) {
          alert("Por favor, ingresa un monto válido y selecciona un tipo de costo.");
          return;
      }
      
      // Enviar datos por AJAX
      $.ajax({
          url: "../models/SaleModel.php",
          type: "POST",
          data: { action: "ExtraCost", tipo, monto },
          success: function (res) {
              console.log("Respuesta del servidor:", res);
              if (res != 0) {
                  alert("Costo adicional agregado correctamente.");
              } else {
                  alert("Error al agregar el costo.");
              }
          },
          error: function () {
              alert("Hubo un problema con la solicitud AJAX.");
          }
      });
      $("#additionalCostsModal").modal("hide");
      // Cerrar el modal después de enviar los datos
  });
    // Cerrar modal al hacer clic en el botón cerrar
    $(".close").click(function () {
      $("#additionalCostsModal").modal("hide");

  });

  // Generar nuevo presupuesto
  $("#estimate_btn").click(function (e) {
    e.preventDefault();

    let rows = $("#sale_detail tr").length;
    let client_dni = $("#client_dni").val().trim();

    let products = [];
    let total = 0; // Variable para el total de la venta

    // Recorrer las filas de la tabla de detalle de la venta
    $("#sale_detail tr").each(function () {
      let productId = $(this).find(".product_id").text().trim();
      let quantity = $(this).find(".product_quantity").text().trim();
      let price = $(this)
        .find(".price")
        .text()
        .trim()
        .replace("$", "")
        .replace(",", ""); // Obtener el precio de la celda, limpiando caracteres especiales
      if (productId && quantity && price) {
        let productTotal = parseFloat(price) * parseInt(quantity);
        products.push({ productId, quantity, price: parseFloat(price) });
        total += productTotal;
      }
    });


    // Validaciones iniciales
    if (rows <= 0) {
      alert("Aún no has agregado productos para generar el presupuesto");
      return;
    }

    if (!client_dni) {
      alert("Aún no has seleccionado un cliente");
      return;
    }

    // Recolectar datos de productos
    



    if (!confirm("¿Desea generar el presupuesto?")) {
      return false;
    }

    // Proceso de generación del presupuesto
    $.ajax({
      url: "../models/ClientModel.php",
      type: "POST",
      data: { action: "clientSearch", client_dni },
      success: function (response) {
        try {
          if (response == 0) {
            throw new Error("No se encontró el cliente");
          }

          let datos = JSON.parse(response);

          $.ajax({
            url: "../models/EstimateModel.php",
            type: "POST",
            async: true,
            data: {
              action: "procesarPresupuesto",
              clientId: datos.id,
              products: JSON.stringify(products),
              total: total.toFixed(2)
            },
            success: function (response) {
              try {
                if (response == 0) {
                  throw new Error("Error al procesar el presupuesto");
                }

                let info = JSON.parse(response);
                generateEstimatePDF(info.clientId, info.id, total);
                location.reload();
              } catch (error) {
                console.error("Error en procesamiento:", error);
                alert("Error al procesar el presupuesto: " + error.message);
              }
            },
            error: function (error) {
              console.error("Error en la petición:", error);
              alert("Error de conexión al procesar el presupuesto");
            },
          });
        } catch (error) {
          console.error("Error al procesar respuesta del cliente:", error);
          alert("Error al buscar el cliente: " + error.message);
        }
      },
      error: function (error) {
        console.error("Error en la petición del cliente:", error);
        alert("Error de conexión al buscar el cliente");
      },
    });
  });

  /* DataTable BABY */
  $("#table").DataTable({
    dom: "Bfrtilp",
    buttons: [
      {
        extend: "excelHtml5",
        text: '<i class="fa fa-file-excel-o"> </i> ',
        titleAttr: "Exportar a Excel",
        className: "btn btn-primary ",
        exportOptions: {
          columns: ":visible",
          /* modifier: {
            page: 'all', // Exporta todas las filas, no solo la página visible
          }, */
        },
      },
      {
        extend: "pdfHtml5",
        text: '<i class="fa fa-file-pdf-o"> </i>',
        titleAttr: "Exportar a PDF",
        className: "btn btn-danger",
        exportOptions: {
          columns: ":visible",
          /* modifier: {
            page: 'all', // Exporta todas las filas, no solo la página visible
          }, */
        },
      },
      {
        extend: "print",
        text: '<i class="fa fa-print icon-print"> </i> ',
        titleAttr: "Imprimir",
        className: "btn btn-secondary",
        exportOptions: {
          columns: ":visible",
          /* modifier: {
            page: 'all', // Exporta todas las filas, no solo la página visible
          }, */
        },
      },
    ],
    order: [[0, "desc"]], // Asegúrate de que la columna de date tenga el índice 0
    pageLength: 10, // Mostrar 50 registros por página
    language: {
      decimal: "",
      emptyTable: "No hay datos",
      info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
      infoEmpty: "Mostrando 0 a 0 de 0 registros",
      infoFiltered: "(Filtro de _MAX_ total registros)",
      infoPostFix: "",
      thousands: ",",
      lengthMenu: "Mostrar _MENU_ registros",
      loadingRecords: "Cargando...",
      processing: "Procesando...",
      search: "Buscar:",
      zeroRecords: "No se encontraron coincidencias",
      paginate: {
        first: "Primero",
        last: "Ultimo",
        next: "Siguiente",
        previous: "Anterior",
      },
      aria: {
        sortAscending: ": Activar orden de columna ascendente",
        sortDescending: ": Activar orden de columna desendente",
      },
    },
  });

  //Descargar factura
  $(".download_invoice").click(function (e) {
    e.preventDefault();

    var clientId = $(this).attr("clientId");
    var invoiceId = $(this).attr("invoiceId");
    var total = $(this).attr("total");

    generateInvoicePDF(clientId, invoiceId, total);
  });

  //Descargar presupuesto
  $(".download_estimate").click(function (e) {
    e.preventDefault();

    var clientId = $(this).attr("clientId");
    var estimateId = $(this).attr("estimateId");
    var total = $(this).attr("total");

    generateEstimatePDF(clientId, estimateId, total);
  });
}); // fin ready
