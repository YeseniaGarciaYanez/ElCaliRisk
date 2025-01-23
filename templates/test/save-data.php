<?php
// save_data.php
require_once '../../config.php';

header('Content-Type: application/json'); // Asegurar respuesta en formato JSON

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Validar datos obligatorios
        if (empty($_POST['reportNumber']) || empty($_POST['testLocation']) || empty($_POST['roomId']) || empty($_POST['roomClassification'])) {
            throw new Exception("Faltan datos obligatorios.");
        }

        // Insertar información general del test
        $stmt = $conn->prepare("INSERT INTO test_info (report_number, test_location, room_id, room_classification) 
                                VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", 
            $_POST['reportNumber'], 
            $_POST['testLocation'], 
            $_POST['roomId'], 
            $_POST['roomClassification']
        );
        $stmt->execute();
        $test_id = $conn->insert_id;

        $response = ['success' => true, 'message' => 'Test info saved', 'test_id' => $test_id];

        // Procesar datos de monitoreo
        if (isset($_POST['monitoringData']) && is_array($_POST['monitoringData']) && !empty($_POST['monitoringData'])) {
            $values = [];
            $placeholders = [];
            
            foreach ($_POST['monitoringData'] as $data) {
                // Validar datos de monitoreo
                if (!isset($data['timestamp'], $data['temperature'], $data['humidity'], $data['pressure'])) {
                    throw new Exception("Faltan datos de monitoreo.");
                }

                $timestamp = $data['timestamp'];
                $temperature = floatval($data['temperature']);
                $humidity = floatval($data['humidity']);
                $pressure = floatval($data['pressure']);

                // Agregar valores al arreglo
                $values[] = $test_id;
                $values[] = $timestamp;
                $values[] = $temperature;
                $values[] = $humidity;
                $values[] = $pressure;

                $placeholders[] = "(?, ?, ?, ?, ?)";
            }

            // Insertar datos en lote
            $sql = "INSERT INTO monitoring_data (test_id, timestamp, temperature, humidity, pressure) VALUES " . implode(", ", $placeholders);
            $stmt = $conn->prepare($sql);
            $stmt->bind_param(str_repeat("isddd", count($placeholders)), ...$values);
            $stmt->execute();

            $response['message'] = 'Test info and monitoring data saved successfully';
        } else {
            $response['debug_monitoring'] = 'No monitoring data received';
        }

        echo json_encode($response);
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage(),
            'post_data' => $_POST // Datos recibidos para depuración
        ]);
    }
}
?>
