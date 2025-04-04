<div class="wrapper wrapper-content animated fadeInRight">
  <div class="row">
    <div class="col-lg-2">
      <div class="ibox">
        <div class="ibox-title">
          <span class="label label-success float-right">Mes</span>
          <h5>Proyectos</h5>
        </div>
        <div class="ibox-content">
          <h1 class="no-margins"><?= $total_ventas ?></h1>
          <div class="stat-percent font-bold text-success"> <?= number_format($porcentaje, 0); ?>% <i class="fa fa-bolt"></i></div>
          <small>Proyectos</small>
        </div>
      </div>
    </div>
    <div class="col-lg-2">
      <div class="ibox">
        <div class="ibox-title">
          <span class="label label-info float-right">Mes</span>
          <h5>Cotizaciones</h5>
        </div>
        <div class="ibox-content">
          <h1 class="no-margins"><?= $total_presupuestos ?></h1>
          <div class="stat-percent font-bold text-info"><?= number_format($porcentaje_diferencia_presupuestos, 0); ?>% <i class="fa fa-level-up"></i></div>
          <small>Nuevas cotizaciones</small>
        </div>
      </div>
    </div>
    <div class="col-lg-4">
      <div class="ibox">
        <div class="ibox-title">
          <span class="label label-primary float-right">Mes</span>
          <h5>Ventas <i class="fa fa-dollar"></i></h5>
        </div>
        <div class="ibox-content">
          <div class="row">
            <div class="col-md-6">
              <h1 class="no-margins"><?= $total_recaudado_mes_actual ?></h1>
              <div class="font-bold text-navy"><?= $porcentaje_diferencia_recaudado ?> <i class="fa fa-level-up"></i></div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-4">
      <div class="ibox">
        <div class="ibox-title">
          <h5>Ventas del dia</h5>
          <div class="ibox-tools">
            <span class="label label-primary">Dia</i></span>
          </div>
        </div>
        <div class="ibox-content">
          <div class="row">
            <div class="col-md-6">
              <h1 class="no-margins"><?= number_format($total_recaudado_hoy_formatted, 2) ?></h1>
              <div class="font-bold text-navy"><small>Vista rápida</small></div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
  <div>
    <div>
      <div class="ibox">
        <div class="dashboard">
          <div>
            <span class="float-right text-right">
              <small>Valor promedio de ventas en el <strong>último mes</strong></small>
              <br />
            </span>
            <h3 class="font-bold no-margins">Margen de ingreso mensual</h3>
            <small>Ventas.</small>
          </div>

          <div class="m-t-sm">
            <div class="row">
              <div class="col-md-12">
                <div>
                  <canvas id="lineChart" height="114"></canvas>
                </div>
              </div>
            </div>
          </div>

          <div class="m-t-md">
            <small class="float-right">
              <i class="fa fa-clock-o"> </i>
              <?= "Actualizado el " . $fecha_actual; ?>
            </small>
            <small>        
            </small>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>