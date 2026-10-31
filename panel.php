<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Control - Sistema de Rifa</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6f9; margin: 0; padding: 20px; }
        .header { display: flex; justify-content: space-between; align-items: center; background: #fff; padding: 15px 25px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .container { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .card { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h2 { margin-top: 0; border-bottom: 2px solid #eee; padding-bottom: 10px; }
        form { display: flex; flex-direction: column; gap: 10px; margin-bottom: 20px; }
        input, button { padding: 10px; border-radius: 4px; border: 1px solid #ccc; font-size: 14px; }
        button { background: #28a745; color: white; border: none; cursor: pointer; font-weight: bold; }
        button:hover { background: #218838; }
        .btn-danger { background: #dc3545; text-decoration: none; color: white; padding: 8px 15px; border-radius: 4px; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f8f9fa; }
    </style>
</head>
<body>

<div class="header">
    <h1>Bienvenido, <?= htmlspecialchars($_SESSION['usuario']) ?> 👋</h1>
    <a href="index.php" class="btn-danger">Cerrar Sesión</a>
</div>

<div class="container">
    <!-- SECCIÓN DE PARTICIPANTES -->
    <div class="card">
        <h2>👥 Participantes</h2>
        <form id="formParticipante">
            <input type="text" id="nombrePart" placeholder="Nombre completo" required>
            <input type="text" id="dniPart" placeholder="DNI / Cédula" required>
            <button type="submit">Registrar Participante</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>DNI</th>
                </tr>
            </thead>
            <tbody id="tablaParticipantes">
                <!-- Se llena mediante AJAX / API -->
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
                <!-- Se llena mediante AJAX / API -->
            </tbody>
        </table>
    </div>
</div>

<script>
const URL_API = 'http://localhost/pruebaphp1/api';

// Cargar Participantes desde la API (GET)
async function cargarParticipantes() {
    const res = await fetch(`${URL_API}/participantes.php`);
    const datos = await res.json();
    const tbody = document.getElementById('tablaParticipantes');
    tbody.innerHTML = '';
    
    datos.forEach(p => {
        tbody.innerHTML += `
            <tr>
                <td>${p.id}</td>
                <td>${p.nombre}</td>
                <td>${p.dni}</td>
            </tr>`;
    });
}

// Cargar Premios desde la API (GET)
async function cargarPremios() {
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
}

// Agregar Participante a través de la API (POST)
document.getElementById('formParticipante').addEventListener('submit', async (e) => {
    e.preventDefault();
    const nombre = document.getElementById('nombrePart').value;
    const dni = document.getElementById('dniPart').value;

    await fetch(`${URL_API}/participantes.php`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ nombre, dni })
    });

    document.getElementById('formParticipante').reset();
    cargarParticipantes();
});

// Agregar Premio a través de la API (POST)
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

// Inicializar tablas al cargar la página
cargarParticipantes();
cargarPremios();
</script>

</body>
</html>