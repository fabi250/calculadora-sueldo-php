<?php
// procesar.php - Procesamiento de Nómina Quincenal y Generación de Comprobante

// Configurar zona horaria y codificación
date_default_timezone_set('America/Caracas');

// Validar método de solicitud
$es_post = ($_SERVER['REQUEST_METHOD'] === 'POST');

// Definición de tarifas por hora (en Bolívares)
if (!defined('TARIFA_DIURNA')) define('TARIFA_DIURNA', 675.00);
if (!defined('TARIFA_VESPERTINA')) define('TARIFA_VESPERTINA', 700.00);
if (!defined('TARIFA_NOCTURNA')) define('TARIFA_NOCTURNA', 956.23);

// Variables iniciales
$nombre = '';
$cedula = '';
$horas_diurnas = 0.0;
$horas_vespertinas = 0.0;
$horas_nocturnas = 0.0;

$subtotal_diurno = 0.0;
$subtotal_vespertino = 0.0;
$subtotal_nocturno = 0.0;
$total_horas = 0.0;
$sueldo_bruto = 0.0;

$ahorro_hab_pct = 0.0;
$seguro_soc_pct = 0.0;
$ahorro_hab_monto = 0.0;
$seguro_soc_monto = 0.0;
$total_deducciones = 0.0;
$sueldo_neto = 0.0;
$escala_aplicada = '';

if ($es_post) {
    // Sanitización y validación de datos de entrada
    $nombre = isset($_POST['nombre']) ? htmlspecialchars(strip_tags(trim($_POST['nombre'])), ENT_QUOTES, 'UTF-8') : '';
    $cedula = isset($_POST['cedula']) ? htmlspecialchars(strip_tags(trim($_POST['cedula'])), ENT_QUOTES, 'UTF-8') : '';
    
    // Horas trabajadas sanitizadas como flotantes positivos
    $horas_diurnas = isset($_POST['horas_diurnas']) ? max(0, floatval($_POST['horas_diurnas'])) : 0.0;
    $horas_vespertinas = isset($_POST['horas_vespertinas']) ? max(0, floatval($_POST['horas_vespertinas'])) : 0.0;
    $horas_nocturnas = isset($_POST['horas_nocturnas']) ? max(0, floatval($_POST['horas_nocturnas'])) : 0.0;

    $total_horas = $horas_diurnas + $horas_vespertinas + $horas_nocturnas;

    // 1. Cálculo de devengados por turno
    $subtotal_diurno = $horas_diurnas * TARIFA_DIURNA;
    $subtotal_vespertino = $horas_vespertinas * TARIFA_VESPERTINA;
    $subtotal_nocturno = $horas_nocturnas * TARIFA_NOCTURNA;

    // 2. Cálculo del Sueldo Bruto
    $sueldo_bruto = $subtotal_diurno + $subtotal_vespertino + $subtotal_nocturno;

    // 3. Aplicación de las escalas de retención según el Sueldo Bruto
    if ($sueldo_bruto < 85000.0) {
        $ahorro_hab_pct = 0.10;  // 0.1%
        $seguro_soc_pct = 0.15;  // 0.15%
        $escala_aplicada = 'Escala 1: Sueldo Bruto menor a 85.000 Bs.';
    } elseif ($sueldo_bruto <= 150000.0) {
        $ahorro_hab_pct = 0.15;  // 0.15%
        $seguro_soc_pct = 0.20;  // 0.2%
        $escala_aplicada = 'Escala 2: Sueldo Bruto entre 85.000 y 150.000 Bs.';
    } else {
        $ahorro_hab_pct = 0.30;  // 0.3%
        $seguro_soc_pct = 0.25;  // 0.25%
        $escala_aplicada = 'Escala 3: Sueldo Bruto mayor a 150.000 Bs.';
    }

    // 4. Montos de las deducciones
    $ahorro_hab_monto = $sueldo_bruto * ($ahorro_hab_pct / 100.0);
    $seguro_soc_monto = $sueldo_bruto * ($seguro_soc_pct / 100.0);
    $total_deducciones = $ahorro_hab_monto + $seguro_soc_monto;

    // 5. Sueldo Neto a Cobrar
    $sueldo_neto = $sueldo_bruto - $total_deducciones;
}

// Función auxiliar para formatear montos en Bolívares
if (!function_exists('formato_moneda')) {
    function formato_moneda($monto) {
        return number_format($monto, 2, ',', '.') . ' Bs.';
    }
}

// Función auxiliar para formatear porcentaje
if (!function_exists('formato_porcentaje')) {
    function formato_porcentaje($pct) {
        return number_format($pct, 2, ',', '.') . '%';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprobante de Pago Quincenal | <?php echo htmlspecialchars($nombre ?: 'Nómina'); ?></title>
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            --header-gradient: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
            --success-gradient: linear-gradient(135deg, #059669 0%, #10b981 100%);
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
            min-height: 100vh;
            color: #1e293b;
        }

        .receipt-card {
            border-radius: 1.25rem;
            border: 1px solid rgba(226, 232, 240, 0.9);
            background-color: #ffffff;
            overflow: hidden;
        }

        .receipt-header {
            background: var(--header-gradient);
            color: #ffffff;
            padding: 2rem 2.5rem;
        }

        .employee-badge {
            background: #f8fafc;
            border-left: 5px solid #4f46e5;
            border-radius: 0.75rem;
            padding: 1.25rem 1.5rem;
        }

        .table-custom th {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            font-weight: 700;
            background-color: #f8fafc;
            border-bottom: 2px solid #e2e8f0;
        }

        .table-custom td {
            vertical-align: middle;
            font-size: 0.95rem;
            padding: 0.85rem 1rem;
        }

        .total-box {
            background: #ecfdf5;
            border: 2px solid #10b981;
            border-radius: 1rem;
            padding: 1.5rem;
        }

        .net-amount {
            color: #065f46;
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: -0.02em;
        }

        .watermark {
            font-size: 0.75rem;
            color: #94a3b8;
            letter-spacing: 0.05em;
        }

        /* Estilos específicos para impresión */
        @media print {
            body {
                background: #ffffff !important;
                padding: 0 !important;
            }
            .no-print {
                display: none !important;
            }
            .receipt-card {
                box-shadow: none !important;
                border: 1px solid #cbd5e1 !important;
            }
            .receipt-header {
                background: #1e293b !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .total-box {
                background: #ecfdf5 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body class="py-4 py-md-5">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-9 col-xl-8">

            <?php if (!$es_post || empty($nombre) || empty($cedula)): ?>
                <!-- Estado de Error / Acceso Directo No Válido -->
                <div class="card receipt-card shadow-lg p-4 p-md-5 text-center">
                    <div class="text-danger mb-3">
                        <i class="bi bi-exclamation-triangle-fill display-3"></i>
                    </div>
                    <h3 class="fw-bold mb-2">Solicitud No Válida</h3>
                    <p class="text-muted mb-4">
                        No se han recibido los datos correspondientes para generar el comprobante. Por favor ingrese los datos desde el formulario inicial.
                    </p>
                    <div>
                        <a href="index.php" class="btn btn-primary px-4 py-2 rounded-pill">
                            <i class="bi bi-arrow-left me-2"></i> Ir al Formulario
                        </a>
                    </div>
                </div>

            <?php else: ?>
                <!-- COMPROBANTE DE PAGO FORMAL -->
                <div class="card receipt-card shadow-lg mb-4">
                    
                    <!-- Encabezado del Comprobante -->
                    <div class="receipt-header">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div>
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <i class="bi bi-file-earmark-check-fill fs-3"></i>
                                    <h1 class="h4 fw-bold mb-0">COMPROBANTE DE PAGO QUINCENAL</h1>
                                </div>
                                <p class="text-white-50 mb-0 small">Liquidación de Sueldos y Retenciones de Ley</p>
                            </div>
                            <div class="text-md-end">
                                <span class="badge bg-light text-primary px-3 py-2 fw-semibold rounded-pill">
                                    <i class="bi bi-calendar3 me-1"></i> Período Quincenal
                                </span>
                                <div class="small text-white-50 mt-1">
                                    Fecha: <?php echo date('d/m/Y - h:i A'); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-4 p-md-5">
                        
                        <!-- Encabezado Destacado con Datos del Empleado -->
                        <div class="employee-badge mb-4 shadow-sm">
                            <div class="row align-items-center">
                                <div class="col-md-7 mb-2 mb-md-0">
                                    <span class="text-muted small text-uppercase fw-bold">Trabajador / Beneficiario</span>
                                    <h4 class="fw-bold text-dark mb-0 mt-1">
                                        <i class="bi bi-person-fill text-primary me-2"></i><?php echo $nombre; ?>
                                    </h4>
                                </div>
                                <div class="col-md-5 text-md-end">
                                    <span class="text-muted small text-uppercase fw-bold">Cédula de Identidad</span>
                                    <h5 class="fw-semibold text-secondary mb-0 mt-1">
                                        <i class="bi bi-card-text me-1"></i> <?php echo $cedula; ?>
                                    </h5>
                                </div>
                            </div>
                        </div>

                        <!-- 1. DETALLE DE ASIGNACIONES / HORAS LABORADAS -->
                        <div class="mb-4">
                            <h6 class="fw-bold text-secondary text-uppercase small mb-3">
                                <i class="bi bi-clock-history text-primary me-1"></i> 1. Desglose de Horas Laboradas y Tarifas
                            </h6>
                            <div class="table-responsive">
                                <table class="table table-custom table-bordered align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th>Turno</th>
                                            <th class="text-center">Horas</th>
                                            <th class="text-end">Tarifa Unit.</th>
                                            <th class="text-end">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <i class="bi bi-sun-fill text-warning me-2"></i><strong>Turno Diurno</strong>
                                            </td>
                                            <td class="text-center fw-semibold"><?php echo number_format($horas_diurnas, 2); ?> hrs</td>
                                            <td class="text-end"><?php echo formato_moneda(TARIFA_DIURNA); ?></td>
                                            <td class="text-end fw-semibold text-dark"><?php echo formato_moneda($subtotal_diurno); ?></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <i class="bi bi-sunset-fill text-warning-emphasis me-2"></i><strong>Turno Vespertino</strong>
                                            </td>
                                            <td class="text-center fw-semibold"><?php echo number_format($horas_vespertinas, 2); ?> hrs</td>
                                            <td class="text-end"><?php echo formato_moneda(TARIFA_VESPERTINA); ?></td>
                                            <td class="text-end fw-semibold text-dark"><?php echo formato_moneda($subtotal_vespertino); ?></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <i class="bi bi-moon-stars-fill text-indigo me-2" style="color: #4f46e5;"></i><strong>Turno Nocturno</strong>
                                            </td>
                                            <td class="text-center fw-semibold"><?php echo number_format($horas_nocturnas, 2); ?> hrs</td>
                                            <td class="text-end"><?php echo formato_moneda(TARIFA_NOCTURNA); ?></td>
                                            <td class="text-end fw-semibold text-dark"><?php echo formato_moneda($subtotal_nocturno); ?></td>
                                        </tr>
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <th colspan="3" class="text-end text-dark fs-6">Sueldo Bruto Devengado:</th>
                                            <th class="text-end fs-6 text-primary fw-bold"><?php echo formato_moneda($sueldo_bruto); ?></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <!-- 2. TABLA DESGLOSADA DE DEDUCCIONES DE LEY -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                                <h6 class="fw-bold text-secondary text-uppercase small mb-0">
                                    <i class="bi bi-pie-chart-fill text-danger me-1"></i> 2. Desglose de Deducciones y Retenciones
                                </h6>
                                <span class="badge bg-secondary-subtle text-secondary border px-3 py-1 small">
                                    <?php echo $escala_aplicada; ?>
                                </span>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-custom table-bordered align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th>Concepto de Retención</th>
                                            <th class="text-center">Porcentaje (%)</th>
                                            <th class="text-end">Base de Cálculo</th>
                                            <th class="text-end text-danger">Monto Deducido</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <i class="bi bi-house-door-fill text-primary me-2"></i>
                                                <strong>Ahorro Habitacional</strong>
                                                <small class="text-muted d-block ps-4">Fondo de Ahorro Obligatorio para la Vivienda</small>
                                            </td>
                                            <td class="text-center fw-bold text-secondary">
                                                <span class="badge bg-primary-subtle text-primary fs-6 px-2 py-1">
                                                    <?php echo formato_porcentaje($ahorro_hab_pct); ?>
                                                </span>
                                            </td>
                                            <td class="text-end"><?php echo formato_moneda($sueldo_bruto); ?></td>
                                            <td class="text-end fw-bold text-danger">- <?php echo formato_moneda($ahorro_hab_monto); ?></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <i class="bi bi-shield-shaded text-success me-2"></i>
                                                <strong>Seguro Social</strong>
                                                <small class="text-muted d-block ps-4">Seguro Social Obligatorio de Protección Social</small>
                                            </td>
                                            <td class="text-center fw-bold text-secondary">
                                                <span class="badge bg-success-subtle text-success fs-6 px-2 py-1">
                                                    <?php echo formato_porcentaje($seguro_soc_pct); ?>
                                                </span>
                                            </td>
                                            <td class="text-end"><?php echo formato_moneda($sueldo_bruto); ?></td>
                                            <td class="text-end fw-bold text-danger">- <?php echo formato_moneda($seguro_soc_monto); ?></td>
                                        </tr>
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <th colspan="3" class="text-end text-secondary">Total Deducciones:</th>
                                            <th class="text-end text-danger fw-bold">- <?php echo formato_moneda($total_deducciones); ?></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <!-- 3. LIQUIDACIÓN FINAL: SUELDO NETO A COBRAR EN DESTACADO VERDE -->
                        <div class="total-box shadow-sm mb-4">
                            <div class="row align-items-center">
                                <div class="col-md-7 text-center text-md-start mb-3 mb-md-0">
                                    <span class="badge bg-success text-white px-3 py-1 mb-2">Total Quincenal a Percibir</span>
                                    <h4 class="fw-bold text-dark mb-1">Sueldo Neto a Cobrar</h4>
                                    <p class="text-muted small mb-0">
                                        (Sueldo Bruto: <?php echo formato_moneda($sueldo_bruto); ?> &minus; Deducciones: <?php echo formato_moneda($total_deducciones); ?>)
                                    </p>
                                </div>
                                <div class="col-md-5 text-center text-md-end">
                                    <div class="net-amount">
                                        <?php echo formato_moneda($sueldo_neto); ?>
                                    </div>
                                    <span class="badge bg-success-subtle text-success border border-success px-2 py-1 small fw-semibold">
                                        <i class="bi bi-check-circle-fill me-1"></i> Saldo Neto Liquidado
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Resumen Informativo Final -->
                        <div class="border-top pt-3 text-muted small text-center watermark">
                            Documento generado automáticamente por el Sistema de Liquidación de Sueldos &bull; Los montos expresados en Bolívares (Bs.) cumplen con la normativa laboral vigente.
                        </div>

                    </div>
                </div>

                <!-- Botones de Acción (Ocultos al imprimir) -->
                <div class="no-print d-flex justify-content-between align-items-center flex-wrap gap-3 mb-5">
                    <a href="index.php" class="btn btn-outline-secondary px-4 py-2 rounded-3 shadow-sm">
                        <i class="bi bi-arrow-left me-2"></i> Regresar al Formulario
                    </a>
                    <button type="button" onclick="window.print()" class="btn btn-outline-primary px-4 py-2 rounded-3 shadow-sm">
                        <i class="bi bi-printer-fill me-2"></i> Imprimir Comprobante
                    </button>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<!-- Bootstrap 5 JS Bundle CDN -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>
