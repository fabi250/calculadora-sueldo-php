<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calculadora de Sueldo Quincenal | Sistema de Nómina</title>
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
            --card-border-radius: 1.25rem;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
            min-height: 100vh;
            color: #1e293b;
        }

        .main-card {
            border-radius: var(--card-border-radius);
            border: 1px solid rgba(226, 232, 240, 0.8);
            background-color: #ffffff;
            overflow: hidden;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card-header-gradient {
            background: var(--primary-gradient);
            padding: 2.25rem 2rem;
            color: #ffffff;
            border-bottom: none;
        }

        .icon-badge {
            width: 58px;
            height: 58px;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(8px);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            margin-bottom: 0.85rem;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }

        .section-title {
            font-size: 0.825rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-weight: 700;
            color: #64748b;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .shift-card {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            padding: 1.15rem;
            transition: all 0.2s ease;
        }

        .shift-card:hover, .shift-card:focus-within {
            background-color: #ffffff;
            border-color: #6366f1;
            box-shadow: 0 6px 18px rgba(99, 102, 241, 0.12);
            transform: translateY(-2px);
        }

        .form-control {
            border-radius: 0.75rem;
            padding: 0.7rem 1rem;
            border: 1px solid #cbd5e1;
            font-size: 0.95rem;
        }

        .form-control:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.15);
        }

        .input-group-text {
            border-radius: 0.75rem 0 0 0.75rem;
            background-color: #f8fafc;
            border: 1px solid #cbd5e1;
            border-right: none;
            color: #64748b;
        }

        .input-group > .form-control {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }

        .rate-badge {
            font-size: 0.825rem;
            padding: 0.35rem 0.65rem;
            border-radius: 2rem;
            font-weight: 600;
        }

        .badge-diurno {
            background-color: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
        }

        .badge-vespertino {
            background-color: #ffedd5;
            color: #9a3412;
            border: 1px solid #fed7aa;
        }

        .badge-nocturno {
            background-color: #e0e7ff;
            color: #3730a3;
            border: 1px solid #c7d2fe;
        }

        .btn-submit {
            background: var(--primary-gradient);
            border: none;
            border-radius: 0.85rem;
            padding: 0.95rem 1.5rem;
            font-weight: 600;
            font-size: 1.05rem;
            letter-spacing: 0.02em;
            transition: all 0.25s ease;
            box-shadow: 0 10px 20px rgba(79, 70, 229, 0.25);
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 28px rgba(79, 70, 229, 0.35);
            background: linear-gradient(135deg, #4338ca 0%, #2563eb 100%);
        }

        .info-pill {
            background: #f1f5f9;
            border-radius: 0.75rem;
            padding: 0.85rem 1.15rem;
            font-size: 0.875rem;
            color: #475569;
        }
    </style>
</head>
<body class="py-4 py-md-5">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8 col-xl-7">
            
            <!-- Tarjeta Principal con shadow-lg -->
            <div class="card main-card shadow-lg">
                
                <!-- Encabezado Atractivo con Icono -->
                <div class="card-header card-header-gradient text-center">
                    <div class="icon-badge">
                        <i class="bi bi-wallet2"></i>
                    </div>
                    <h2 class="h3 fw-bold mb-1">Calculadora de Sueldo Quincenal</h2>
                    <p class="text-white-50 mb-0 small">
                        Complete el formulario para procesar las horas laboradas y generar el recibo de pago correspondiente.
                    </p>
                </div>

                <!-- Cuerpo del Formulario -->
                <div class="card-body p-4 p-md-5">

                    <!-- Instrucciones Claras -->
                    <div class="info-pill mb-4 d-flex align-items-center">
                        <i class="bi bi-info-circle-fill text-primary fs-5 me-3 flex-shrink-0"></i>
                        <div>
                            <strong>Instrucciones:</strong> Ingrese los datos de identificación del trabajador y la cantidad de horas laboradas por turno. Las deducciones de ley se aplicarán de acuerdo a las escalas vigentes.
                        </div>
                    </div>

                    <!-- Formulario con método POST a procesar.php -->
                    <form action="procesar.php" method="POST" id="salaryForm" novalidate>
                        
                        <!-- SECCIÓN 1: DATOS DEL TRABAJADOR -->
                        <div class="section-title">
                            <i class="bi bi-person-vcard text-primary"></i> 1. Información del Trabajador
                        </div>

                        <div class="row g-3 mb-4">
                            <!-- Nombre Completo -->
                            <div class="col-12 col-md-7">
                                <label for="nombre" class="form-label fw-semibold small text-secondary">
                                    Nombre Completo <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                    <input type="text" class="form-control" id="nombre" name="nombre" 
                                           placeholder="Ej. Alexander Morales" required autofocus>
                                </div>
                                <div class="invalid-feedback">Por favor ingrese el nombre del trabajador.</div>
                            </div>

                            <!-- Cédula de Identidad -->
                            <div class="col-12 col-md-5">
                                <label for="cedula" class="form-label fw-semibold small text-secondary">
                                    Cédula de Identidad <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-card-heading"></i></span>
                                    <input type="text" class="form-control" id="cedula" name="cedula" 
                                           placeholder="Ej. V-24.589.123" required>
                                </div>
                                <div class="invalid-feedback">Por favor ingrese la cédula de identidad.</div>
                            </div>
                        </div>

                        <!-- SECCIÓN 2: HORAS TRABAJADAS POR TURNO -->
                        <div class="section-title">
                            <i class="bi bi-clock-history text-primary"></i> 2. Horas Laboradas por Turno
                        </div>

                        <div class="d-flex flex-column gap-3 mb-4">
                            
                            <!-- Turno Diurno -->
                            <div class="shift-card">
                                <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                                    <label for="horas_diurnas" class="form-label fw-bold mb-0 d-flex align-items-center">
                                        <i class="bi bi-sun-fill text-warning me-2 fs-5"></i> Turno Diurno
                                    </label>
                                    <span class="rate-badge badge-diurno">
                                        <i class="bi bi-tag-fill me-1"></i> Tarifa: 675 Bs. / hora
                                    </span>
                                </div>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-stopwatch"></i></span>
                                    <input type="number" class="form-control" id="horas_diurnas" name="horas_diurnas" 
                                           placeholder="0" min="0" step="0.5" value="0" required>
                                    <span class="input-group-text bg-white text-muted">hrs</span>
                                </div>
                                <small class="text-muted d-block mt-1 ps-1">Horario regular diurno matutino</small>
                            </div>

                            <!-- Turno Vespertino -->
                            <div class="shift-card">
                                <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                                    <label for="horas_vespertinas" class="form-label fw-bold mb-0 d-flex align-items-center">
                                        <i class="bi bi-sunset-fill text-warning-emphasis me-2 fs-5"></i> Turno Vespertino
                                    </label>
                                    <span class="rate-badge badge-vespertino">
                                        <i class="bi bi-tag-fill me-1"></i> Tarifa: 700 Bs. / hora
                                    </span>
                                </div>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-stopwatch"></i></span>
                                    <input type="number" class="form-control" id="horas_vespertinas" name="horas_vespertinas" 
                                           placeholder="0" min="0" step="0.5" value="0" required>
                                    <span class="input-group-text bg-white text-muted">hrs</span>
                                </div>
                                <small class="text-muted d-block mt-1 ps-1">Horario vespertino de tarde</small>
                            </div>

                            <!-- Turno Nocturno -->
                            <div class="shift-card">
                                <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                                    <label for="horas_nocturnas" class="form-label fw-bold mb-0 d-flex align-items-center">
                                        <i class="bi bi-moon-stars-fill text-indigo me-2 fs-5" style="color: #4f46e5;"></i> Turno Nocturno
                                    </label>
                                    <span class="rate-badge badge-nocturno">
                                        <i class="bi bi-tag-fill me-1"></i> Tarifa: 956.23 Bs. / hora
                                    </span>
                                </div>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-stopwatch"></i></span>
                                    <input type="number" class="form-control" id="horas_nocturnas" name="horas_nocturnas" 
                                           placeholder="0" min="0" step="0.5" value="0" required>
                                    <span class="input-group-text bg-white text-muted">hrs</span>
                                </div>
                                <small class="text-muted d-block mt-1 ps-1">Horario nocturno con recargo legal</small>
                            </div>

                        </div>

                        <!-- Botón de Envío Estilizado de Ancho Completo -->
                        <div class="d-grid pt-2">
                            <button type="submit" class="btn btn-primary btn-submit text-white">
                                <i class="bi bi-calculator-fill me-2"></i> Procesar y Calcular Sueldo Quincenal
                            </button>
                        </div>

                    </form>

                </div>

                <!-- Footer de la tarjeta con resumen de retenciones -->
                <div class="card-footer bg-light border-top text-center py-3 px-4">
                    <p class="small text-muted mb-0">
                        <i class="bi bi-shield-check text-success me-1"></i>
                        Cálculo automático de retenciones: Fondo de Ahorro Habitacional y Seguro Social Obligatorio.
                    </p>
                </div>

            </div>

            <!-- Firma / Créditos mínimos -->
            <div class="text-center mt-3 text-muted small">
                Sistema de Liquidación Quincenal &bull; PHP & Bootstrap 5
            </div>

        </div>
    </div>
</div>

<!-- Bootstrap 5 JS Bundle CDN -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<script>
    // Validación interactiva Bootstrap en el cliente
    (() => {
        'use strict'
        const form = document.getElementById('salaryForm');
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    })();
</script>

</body>
</html>
