# 💼 Calculadora de Sueldo Quincenal

Aplicación web desarrollada en **PHP** con interfaz moderna, limpia y responsive utilizando **Bootstrap 5 (CDN)** para calcular la nómina y liquidación del sueldo quincenal de un trabajador, aplicando tarifas horarias diferenciadas por turno y deducciones de ley según escalas salariales.

---

## 🚀 Características Principales

- **Diseño UI/UX Moderno**: Tarjetas centradas con sombra (`shadow-lg`), bordes redondeados, tipografía cuidada e iconografía intuitiva mediante **Bootstrap Icons**.
- **Formulario Interactivo (`index.php`)**: Captura de datos del empleado (Nombre, Cédula) y horas laboradas por turno con visualización directa de tarifas.
- **Validación y Sanitización**: Limpieza de datos recibidos por el método `POST`, prevención de inyecciones XSS y soporte para entradas decimales.
- **Cálculo Automatizado de Nómina (`procesar.php`)**: Aplicación de tarifas por turno, cómputo del Sueldo Bruto y deducciones automáticas por escalas.
- **Comprobante de Pago Profesional**: Recibo detallado con desglose de asignaciones, retenciones legales y el **Sueldo Neto a Cobrar destacado en verde**.
- **Compatibilidad con Impresión**: Estilos `@media print` optimizados para imprimir el recibo o guardarlo como PDF en un clic.

---

## 📁 Estructura del Proyecto

El proyecto consta exactamente de los siguientes 3 archivos:

```plaintext
calculadora-sueldo/
│
├── index.php        # Formulario principal interactivo de captura de datos
├── procesar.php     # Procesamiento lógico, cálculos y comprobante de pago
└── README.md        # Documentación técnica y manual de ejecución
```

---

## 📊 Reglas de Negocio y Fórmulas

### 1. Tarifas por Turno de Trabajo

Cada hora trabajada se remunera según el turno correspondiente:

| Turno | Tarifa por Hora (Bs.) | Descripción |
| :--- | :---: | :--- |
| **Turno Diurno** | **675,00 Bs.** | Jornada matutina regular |
| **Turno Vespertino** | **700,00 Bs.** | Jornada vespertina |
| **Turno Nocturno** | **956,23 Bs.** | Jornada nocturna con recargo de ley |

$$\text{Sueldo Bruto} = (H_{\text{diurnas}} \times 675.00) + (H_{\text{vespertinas}} \times 700.00) + (H_{\text{nocturnas}} \times 956.23)$$

---

### 2. Escalas de Retención Legal

Las retenciones de **Ahorro Habitacional** y **Seguro Social** se calculan sobre el **Sueldo Bruto** de acuerdo al rango salarial del trabajador:

| Rango de Sueldo Bruto | Ahorro Habitacional (%) | Seguro Social (%) | Total Deducción (%) |
| :--- | :---: | :---: | :---: |
| **Menor a 85.000 Bs.** | `0.10%` | `0.15%` | `0.25%` |
| **Entre 85.000 y 150.000 Bs. (inclusive)** | `0.15%` | `0.20%` | `0.35%` |
| **Mayor a 150.000 Bs.** | `0.30%` | `0.25%` | `0.55%` |

---

### 3. Fórmulas de Deducción y Liquidación Neta

$$\text{Deducción Ahorro Habitacional} = \text{Sueldo Bruto} \times \left(\frac{\% \text{ Ahorro Habitacional}}{100}\right)$$

$$\text{Deducción Seguro Social} = \text{Sueldo Bruto} \times \left(\frac{\% \text{ Seguro Social}}{100}\right)$$

$$\text{Total Deducciones} = \text{Deducción Ahorro Habitacional} + \text{Deducción Seguro Social}$$

$$\text{Sueldo Neto a Cobrar} = \text{Sueldo Bruto} - \text{Total Deducciones}$$

---

## 🧮 Ejemplo de Cálculo

Supongamos un trabajador con los siguientes datos:
- **Nombre:** Alexander Morales (C.I. V-24.589.123)
- **Horas Diurnas:** 40 hrs
- **Horas Vespertinas:** 40 hrs
- **Horas Nocturnas:** 40 hrs

1. **Cálculo del Sueldo Bruto:**
   - Diurno: $40 \times 675 = 27.000,00\text{ Bs.}$
   - Vespertino: $40 \times 700 = 28.000,00\text{ Bs.}$
   - Nocturno: $40 \times 956.23 = 38.249,20\text{ Bs.}$
   - **Sueldo Bruto Total:** $27.000,00 + 28.000,00 + 38.249,20 = \mathbf{93.249,20\text{ Bs.}}$

2. **Escala Aplicable:**
   - Dado que $85.000 \le 93.249,20 \le 150.000$, aplica la **Escala 2**.
   - Ahorro Habitacional: `0.15%`
   - Seguro Social: `0.20%`

3. **Cálculo de Deducciones:**
   - Ahorro Habitacional: $93.249,20 \times 0.0015 = \mathbf{139,87\text{ Bs.}}$
   - Seguro Social: $93.249,20 \times 0.0020 = \mathbf{186,50\text{ Bs.}}$
   - **Total Deducciones:** $139,87 + 186,50 = \mathbf{326,37\text{ Bs.}}$

4. **Sueldo Neto a Cobrar:**
   - $93.249,20 - 326,37 = \mathbf{92.922,83\text{ Bs.}}$

---

## 💻 Requisitos y Tecnologías

- **PHP 7.4 o PHP 8.x**
- **Navegador web moderno** (Chrome, Firefox, Edge, Safari, Brave)
- Conexión a internet (para cargar Bootstrap 5 y Bootstrap Icons vía CDN)
- **Bootstrap 5.3.3 CDN**
- **Bootstrap Icons 1.11.3 CDN**

---

## 🛠️ Cómo Ejecutar el Proyecto Localmente

### Opción 1: Con el servidor web integrado de PHP (Recomendado / Rápido)

Si tienes PHP configurado en tu terminal (o usando la ruta de PHP de XAMPP):

1. Abre una terminal (PowerShell o CMD) en la carpeta del proyecto:
   ```bash
   cd c:\Users\dell\OneDrive\Desktop\calculadora-sueldo
   ```

2. Inicia el servidor integrado:
   ```bash
   # Si 'php' está en el PATH del sistema:
   php -S localhost:8000

   # Si utilizas XAMPP en Windows:
   C:\xampp\php\php.exe -S localhost:8000
   ```

3. Abre tu navegador web e ingresa a:
   ```text
   http://localhost:8000
   ```

---

### Opción 2: Usando XAMPP / WampServer / Laragon

1. Copia o mueve la carpeta `calculadora-sueldo` dentro del directorio público del servidor web:
   - **XAMPP:** `C:\xampp\htdocs\calculadora-sueldo`
   - **WampServer:** `C:\wamp64\www\calculadora-sueldo`
   - **Laragon:** `C:\laragon\www\calculadora-sueldo`

2. Inicia el servicio **Apache** desde el panel de control correspondiente.

3. Abre tu navegador web e ingresa a:
   ```text
   http://localhost/calculadora-sueldo
   ```
