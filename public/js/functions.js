// Función genérica para buscar productos
function searchProduct(product, action) {
  if (!product.trim()) {
    $('#name').html('-');
    $('#stock').html('-');
    $('#product_quantity').val('00');
    $('#price').html('00.00');
    $('#total_price').html('00.00');
    $('#product_quantity').attr('disabled', 'disabled');
    $('#product_add').slideUp();
    $('#add_product_estimate').slideUp();

    return;
  }

  // Asignar el valor de #name al input #name antes de la llamada AJAX
  $('#name_input').val($('#name').html()); // Asignamos el valor de #name a #name_input

  $.ajax({
    url: '../models/ProductModel.php',
    type: 'POST',
    async: true,
    data: { action, product },
    success: function (response) {
      if (response == 0) {
        $('#name').html('-');
        $('#stock').html('-');
        $('#product_quantity').val('0');
        $('#price').html('0.00');
        $('#total_price').html('0.00');
        $('#product_quantity').attr('disabled', 'disabled');
        $('#product_add').slideUp();
        $('#add_product_estimate').slideUp();
      } else {
        let info = JSON.parse(response);
        let unidades = info.stock;

        $('#name').html(info.name);
        $('#stock').html(info.stock);
        $('#product_quantity').val('1');
        $('#price').html(info.price);
        $('#total_price').html(info.price);
        $('#sale_btn').show();
        $('#extra_btn').show();
        $('#cancel_sale_btn').show();
        $('#cancel_estimate_btn').show();
        $('#estimate_btn').show();
        $('#product_quantity').removeAttr('disabled');

        if (unidades >= 1 && unidades <= 10) {
          let mensaje =
            unidades === 1 ? 'AVISO: SÓLO TE QUEDA 1 UNIDAD DE ESTE ARTICULO' : `AVISO: SÓLO TE QUEDAN ${unidades} UNIDADES DE ESTE ARTICULO`;
          alert(mensaje);
        }

        if (info.stock <= 0) {
          $('#product_add').slideUp();
          $('#add_product_estimate').slideUp();
          alert('TE QUEDASTE SIN STOCK');
          location.reload();
        } else {
          $('#product_add').slideDown();
          $('#add_product_estimate').slideDown();
        }
      }
    },
    error: function (error) {
      console.log('Tenemos el siguiente error:', error);
    },
  });
}

function generateInvoicePDF(clientId, invoice, total_with_discount) {
  let url = '../../utils/pdf/invoiceGenerate.php?clientId=' + clientId + '&invoiceId=' + invoice + '&totalWithDiscount=' + total_with_discount;
  window.open(url, '_blank');
}

function generateEstimatePDF(clientId, estimate, total_with_discount) {
  let url =
    '../../utils/pdf/estimateGenerate.php?clientId=' + clientId + '&estimateId=' + estimate + '&totalWithDiscount=' + total_with_discount;
  window.open(url, '_blank');
}

function deleteProductDetail(id) {
  let action = 'eliminarProducto';
  let id_detalle = id;

  $.ajax({
    url: '../models/ProductModel.php',
    type: 'POST',
    async: true,
    data: {
      action,
      id_detalle,
    },
    success: function (response) {
      if (response != 0) {
        let info = JSON.parse(response);
        $('#sale_detail').html(info.detalle);
        $('#detalle_totales').html(info.totales);
        $('#product_number').val('');
        $('#name').html('-');
        $('#stock').html('-');
        $('#product_quantity').val('0');
        $('#price').html('0.00');
        $('#total_price').html('0.00');
        $('#product_quantity').attr('disabled', 'disabled');
        $('#agregar_producto_venta').slideUp();
      } else {
        $('#sale_detail').html('');
        $('#detalle_totales').html('');
      }
    },
    error: function (error) {
      console.log('Tenemos el siguiente error:', error);
    },
  });
}

function deleteEstimateDetail(id) {
  let action = 'eliminarProducto';
  let id_detalle = id;

  const rowToDelete = $(`a[onclick*="deleteEstimateDetail(${id})"]`).closest('tr');
  $.ajax({
    url: '../models/EstimateModel.php',
    type: 'POST',
    async: true,
    data: {
      action,
      id_detalle,
    },
    success: function (response) {
      if (response != 0) {
        let info = JSON.parse(response);
        rowToDelete.fadeOut(400, function() {
          $(this).remove();
        });
        
        // Actualizamos los totales
        
        $('#sale_detail').html(info.detalle);
        $('#detalle_totales').html(info.totales);
        $('#product_number').val('');
        $('#name').html('-');
        $('#stock').html('-');
        $('#product_quantity').val('0');
        $('#price').html('0.00');
        $('#total_price').html('0.00');
        $('#product_quantity').attr('disabled', 'disabled');
        $('#agregar_producto_venta').slideUp();
      } else {
        $('#sale_detail').html('');
        $('#detalle_totales').html('');
      }
    },
    error: function (error) {
      console.log('Tenemos el siguiente error:', error);
    },
  });
}

function formatNumber(number) {
  var parts = number.toFixed(2).toString().split('.');
  var formattedInteger = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
  return formattedInteger + ',' + parts[1];
}



function calcularCostoGeneral(totalinicial, costoGeneral) {
  try {
      let total = parseFloat(totalinicial);
      let costo = parseFloat(costoGeneral);
      
      // Calcular total final
      let totalFinal = total + costo;
      
      // Actualizar el display con formato
      $("#montoTotalFinal").text(formatNumber(totalFinal));
      
      // Mostrar fila del total final si estaba oculta
      $("#totalFinal").show();
      
      // Efecto visual para indicar el cambio
      $("#montoTotalFinal").addClass("highlight");
      setTimeout(() => {
          $("#montoTotalFinal").removeClass("highlight");
      }, 500);
      
      return totalFinal;
  } catch (error) {
      console.error("Error al calcular costo general:", error);
      alert("Error al calcular el total. Por favor, verifica los valores.");
      return 0;
  }
}

// Manejador de eventos mejorado para el costo general
$(document).on("input change keyup", "#costoGeneral", function() {
  let totalInicial = parseFloat($("#TotalConIva").text().replace(/[^0-9.-]+/g, ""));
  let costoGeneral = parseFloat($(this).val()) || 0;
  
  if (costoGeneral < 0) {
      $(this).val(0);
      costoGeneral = 0;
  }
  
  calcularCostoGeneral(totalInicial, costoGeneral);
});

// Agregar estilos CSS necesarios
$("<style>")
  .prop("type", "text/css")
  .html(`
      .highlight {
          transition: color 0.3s ease;
          color: #28a745;
      }
      #totalFinal {
          display: none;
      }
  `)
  .appendTo("head"); 

// Detectar cambios en el campo de Costo General


if (document.getElementById('alertContainer')) {
  setTimeout(function () {
    document.getElementById('alertContainer').innerHTML = '';
  }, 5000);
}

if (document.getElementById('editForm')) {
  document.getElementById('editForm').addEventListener('submit', function () {
    document.getElementById('role').disabled = false;
    document.getElementById('branchId').disabled = false;
    document.getElementById('supplierId').disabled = false;
  });
}