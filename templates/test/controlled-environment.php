<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Controlled Environment Test Form</title>
    <!-- Chart.js CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
        }
        .form-container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .form-section {
            margin-bottom: 30px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="number"],
        input[type="datetime-local"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .grid-4 {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
        }
        .chart-container {
            margin-top: 20px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .data-table th,
        .data-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .data-table th {
            background-color: #f5f5f5;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <form id="environmentForm" method="POST" action="/templates/test/save-data.php">
            <!-- Información General -->
            <div class="form-section">
                <h2>Test Information</h2>
                <div class="grid-2">
                    <div class="form-group">
                        <label for="reportNumber">Report Number:</label>
                        <input type="text" id="reportNumber" name="reportNumber" required>
                    </div>
                    <div class="form-group">
                        <label for="testLocation">Test Location:</label>
                        <input type="text" id="testLocation" name="testLocation" required>
                    </div>
                    <div class="form-group">
                        <label for="roomId">Room ID:</label>
                        <input type="text" id="roomId" name="roomId" required>
                    </div>
                    <div class="form-group">
                        <label for="roomClassification">Room Classification:</label>
                        <input type="text" id="roomClassification" name="roomClassification" required>
                    </div>
                </div>
            </div>

            <!-- Entrada de Datos de Monitoreo -->
            <div class="form-section">
                <h2>Monitoring Data Entry</h2>
                <div class="grid-4">
                    <div class="form-group">
                        <label for="timestamp">Timestamp:</label>
                        <input type="datetime-local" id="timestamp" name="timestamp" required>
                    </div>
                    <div class="form-group">
                        <label for="temperature">Temperature (°C):</label>
                        <input type="number" id="temperature" name="temperature" step="0.1" required>
                    </div>
                    <div class="form-group">
                        <label for="humidity">Humidity (%):</label>
                        <input type="number" id="humidity" name="humidity" step="0.1" required>
                    </div>
                    <div class="form-group">
                        <label for="pressure">Pressure (Pa):</label>
                        <input type="number" id="pressure" name="pressure" step="0.1" required>
                    </div>
                </div>
                <button type="button" onclick="addDataPoint()">Add Data Point</button>
            </div>

            <!-- Gráficas -->
            <div class="form-section">
                <h2>Monitoring Charts</h2>
                <div class="chart-container">
                    <canvas id="temperatureChart"></canvas>
                </div>
                <div class="chart-container">
                    <canvas id="humidityChart"></canvas>
                </div>
                <div class="chart-container">
                    <canvas id="pressureChart"></canvas>
                </div>
            </div>

            <!-- Tabla de Datos -->
            <div class="form-section">
                <h2>Data Points</h2>
                <table id="dataTable" class="data-table">
                    <thead>
                        <tr>
                            <th>Timestamp</th>
                            <th>Temperature (°C)</th>
                            <th>Humidity (%)</th>
                            <th>Pressure (Pa)</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

            <button type="button" onclick="submitForm()">Generate Report</button>

        </form>
    </div>

    <script>
        let monitoringData = {
            timestamps: [],
            temperatures: [],
            humidities: [],
            pressures: []
        };

        // Inicializar gráficas
        const temperatureChart = new Chart(document.getElementById('temperatureChart'), {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Temperature (°C)',
                    data: [],
                    borderColor: 'rgb(255, 99, 132)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: false
                    }
                }
            }
        });

        const humidityChart = new Chart(document.getElementById('humidityChart'), {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Humidity (%)',
                    data: [],
                    borderColor: 'rgb(54, 162, 235)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: false
                    }
                }
            }
        });

        const pressureChart = new Chart(document.getElementById('pressureChart'), {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Pressure (Pa)',
                    data: [],
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: false
                    }
                }
            }
        });

        function addDataPoint() {
            const timestamp = document.getElementById('timestamp').value;
            const temperature = parseFloat(document.getElementById('temperature').value);
            const humidity = parseFloat(document.getElementById('humidity').value);
            const pressure = parseFloat(document.getElementById('pressure').value);

            // Agregar datos a los arrays
            monitoringData.timestamps.push(timestamp);
            monitoringData.temperatures.push(temperature);
            monitoringData.humidities.push(humidity);
            monitoringData.pressures.push(pressure);

            // Actualizar gráficas
            updateCharts();
            // Actualizar tabla
            updateDataTable();
            // Limpiar campos
            clearInputs();
        }

        function updateCharts() {
            // Actualizar gráfica de temperatura
            temperatureChart.data.labels = monitoringData.timestamps;
            temperatureChart.data.datasets[0].data = monitoringData.temperatures;
            temperatureChart.update();

            // Actualizar gráfica de humedad
            humidityChart.data.labels = monitoringData.timestamps;
            humidityChart.data.datasets[0].data = monitoringData.humidities;
            humidityChart.update();

            // Actualizar gráfica de presión
            pressureChart.data.labels = monitoringData.timestamps;
            pressureChart.data.datasets[0].data = monitoringData.pressures;
            pressureChart.update();
        }

        function updateDataTable() {
            const tbody = document.querySelector('#dataTable tbody');
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${monitoringData.timestamps[monitoringData.timestamps.length - 1]}</td>
                <td>${monitoringData.temperatures[monitoringData.temperatures.length - 1]}</td>
                <td>${monitoringData.humidities[monitoringData.humidities.length - 1]}</td>
                <td>${monitoringData.pressures[monitoringData.pressures.length - 1]}</td>
            `;
            tbody.appendChild(row);
        }

        function clearInputs() {
            document.getElementById('timestamp').value = '';
            document.getElementById('temperature').value = '';
            document.getElementById('humidity').value = '';
            document.getElementById('pressure').value = '';
        }

        function submitForm() {
    const formData = new FormData(document.getElementById('environmentForm'));
    
    // Convertir monitoringData a JSON y agregarlo al formulario
    formData.append('monitoringData', JSON.stringify(monitoringData));
    
    fetch('/templates/test/save-data.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message); // Éxito
        } else {
            console.error(data.message); // Error
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}


    </script>
</body>
</html>
