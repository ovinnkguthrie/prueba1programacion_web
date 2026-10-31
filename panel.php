<?php
session_start();

// Cabeceras Anti-Caché para obligar al navegador a verificar la sesión activa
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

if (!isset($_SESSION['usuario'])) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control - Sistema de Rifa</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6f9; margin: 0; padding: 20px; }
        .header { display: flex; justify-content: space-between; align-items: center; background: #fff; padding: 15px 25px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .container { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .card { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h2 { margin-top: 0; border-bottom: 2px solid #eee; padding-bottom: 10px; }
        form { display: flex; flex-direction: column; gap: 10px; margin-bottom: 20px; }
        input, button { padding: 10px; border-radius: 4px; border: 1px solid #ccc; font-size: 14px; }
        label { font-size: 12px; color: #555; margin-bottom: -5px; }
        button { background: #28a745; color: white; border: none; cursor: pointer; font-weight: bold; }
        button:hover { background: #218838; }
        .btn-danger { background: #dc3545; text-decoration: none; color: white; padding: 8px 15px; border-radius: 4px; font-size: 14px; }
        .btn-draw { background: #a855f7; text-decoration: none; color: white; padding: 8px 15px; border-radius: 4px; font-size: 14px; margin-right: 10px; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f8f9fa; }
        .img-link { color: #2563eb; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>

<div class="header">
    <h1>Bienvenido, <?= htmlspecialchars($_SESSION['usuario']) ?> 👋</h1>
    <div>
        <a href="rifa.php" class="btn-draw">🎲 Ir al Sorteo</a>
        <a href="salir.php" class="btn-danger">Cerrar Sesión</a>
    </div>
</div>

<div class="container">
    <!-- SECCIÓN DE PARTICIPANTES -->
    <div class="card">
        <h2>👥 Participantes</h2>
        <form id="formParticipante">
            <input type="text" id="nombrePart" placeholder="Nombre completo" required>
            <input type="text" id="dniPart" placeholder="DNI / Cédula" required>
            
            <label for="comprobantePart">Comprobante de Pago (Opcional):</label>
            <input type="file" id="comprobantePart" accept="image/*,.pdf">
            
            <button type="submit">Registrar Participante</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>DNI</th>
                    <th>Comprobante</th>
                </tr>
            </thead>
            <tbody id="tablaParticipantes">
                <!-- Carga dinámica -->
            </tbody>
        </table>
    </div>

    <!-- SECCIÓN DE PREMIOS -->
    <div class="card">
        <h2>🎁 Premios</h2>
        <form id="formPremio">
            <input type="text" id="nombrePremio" placeholder="Nombre del premio" required>
            <input type="text" id="descPremio" placeholder="Descripción corta">
            <button type="submit">Registrar Premio</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Premio</th>
                    <th>Descripción</th>
                </tr>
            </thead>
            <tbody id="tablaPremios">
                <!-- Carga dinámica -->
            </tbody>
        </table>
    </div>
</div>

<script>
const URL_API = 'http://localhost/pruebaphp1/api';

// Cargar Participantes desde la API
async function cargarParticipantes() {
    try {
        const res = await fetch(`${URL_API}/participantes.php`);
        const datos = await res.json();
        const tbody = document.getElementById('tablaParticipantes');
        tbody.innerHTML = '';
        
        datos.forEach(p => {
            const linkComprobante = p.comprobante 
                ? `<a href="comprobantes/${p.comprobante}" target="_blank" class="img-link">📎 Ver File</a>` 
                : '<span style="color:#aaa;">Sin archivo</span>';

            tbody.innerHTML += `
                <tr>
                    <td>${p.id}</td>
                    <td>${p.nombre}</td>
                    <td>${p.dni}</td>
                    <td>${linkComprobante}</td>
                </tr>`;
        });
    } catch (e) {
        console.error("Error al cargar participantes", e);
    }
}

// Cargar Premios desde la API
async function cargarPremios() {
    try {
        const res = await fetch(`${URL_API}/premios.php`);
        const datos = await res.json();
        const tbody = document.getElementById('tablaPremios');
        tbody.innerHTML = '';
        
        datos.forEach(p => {
            tbody.innerHTML += `
                <tr>
                    <td>${p.id}</td>
                    <td>${p.nombre_premio}</td>
                    <td>${p.descripcion || '-'}</td>
                </tr>`;
        });
    } catch (e) {
        console.error("Error al cargar premios", e);
    }
}

// Registrar Participante y subir comprobante a la raíz
document.getElementById('formParticipante').addEventListener('submit', async (e) => {
    e.preventDefault();
    const nombre = document.getElementById('nombrePart').value;
    const dni = document.getElementById('dniPart').value;
    const fileInput = document.getElementById('comprobantePart');

    let nombreComprobante = "";

    if (fileInput.files.length > 0) {
        const formData = new FormData();
        formData.append('comprobante', fileInput.files[0]);

        try {
            const uploadRes = await fetch('subir_comprobante.php', {
                method: 'POST',
                body: formData
            });
            const uploadData = await uploadRes.json();

            if (uploadRes.ok) {
                nombreComprobante = uploadData.comprobante;
            } else {
                alert(uploadData.error || 'Error al subir el comprobante.');
                return;
            }
        } catch (err) {
            alert('Error de conexión al subir la imagen.');
            return;
        }
    }

    await fetch(`${URL_API}/participantes.php`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ nombre, dni, comprobante: nombreComprobante })
    });

    document.getElementById('formParticipante').reset();
    cargarParticipantes();
});

// Registrar Premio
document.getElementById('formPremio').addEventListener('submit', async (e) => {
    e.preventDefault();
    const nombre_premio = document.getElementById('nombrePremio').value;
    const descripcion = document.getElementById('descPremio').value;

    await fetch(`${URL_API}/premios.php`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ nombre_premio, descripcion })
    });

    document.getElementById('formPremio').reset();
    cargarPremios();
});

// Inicializar tablas
cargarParticipantes();
cargarPremios();
</script>

</body>
</html>