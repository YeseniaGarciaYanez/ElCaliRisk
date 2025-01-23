<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calibration Report - Signal Analyzers</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .logo {
            max-width: 200px;
            height: auto;
        }
        .report-id {
            position: absolute;
            top: 20px;
            right: 20px;
            font-weight: bold;
        }
        .section {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ddd;
        }
        .section-title {
            font-weight: bold;
            background-color: #f5f5f5;
            padding: 5px;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
        }
        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 50px;
        }
        .signature-box {
            text-align: center;
            width: 200px;
        }
        .signature-line {
            border-top: 1px solid #000;
            margin-top: 50px;
            padding-top: 5px;
        }
    </style>
</head>
<body>
    <div class="report-id">N° CAL-SA-[NUMBER]-[YEAR]</div>
    
    <div class="header">
    <img src="../../images/logo.jpg" alt="Calirisk Logo" class="logo">
        <h1>Calibration Certificate - Power Supply</h1>
    </div>

    <form action="../../Tecnico/generateReport_signal.php" method="POST">
        <div class="section">
            <div class="section-title">1. General Information</div>
            <table>
                <tr>
                    <td><strong>Client:</strong></td>
                    <td><input type="text" name="client" value=""></td>
                    <td><strong>Calibration Date:</strong></td>
                    <td><input type="date" name="calibration_date" value=""></td>
                </tr>
                <tr>
                    <td><strong>Address:</strong></td>
                    <td><input type="text" name="client_address" value=""></td>
                    <td><strong>Interval:</strong></td>
                    <td><input type="text" name="interval"></td>
                </tr>
                <tr>
                    <td><strong>Requested by:</strong></td>
                    <td><input type="text" name="requested_by" value=""></td>
                    <td><strong>Next Calibration:</strong></td>
                    <td><input type="date" name="next_calibration" value=""></td>
                </tr>
            </table>
        </div>

        <div class="section">
            <div class="section-title">2. Instrument Information</div>
            <table>
                <tr>
                    <td><strong>Equipment:</strong></td>
                    <td><input type="text" name="equipment_name" value=""></td>
                    <td><strong>Brand:</strong></td>
                    <td><input type="text" name="brand" value=""></td>
                </tr>
                <tr>
                    <td><strong>Model:</strong></td>
                    <td><input type="text" name="model" value=""></td>
                    <td><strong>Client ID:</strong></td>
                    <td><input type="text" name="client_id" value=""></td>
                </tr>
                <tr>
                    <td><strong>Calibrated Functions:</strong></td>
                    <td><input type="text" name="calibrated_functions" value=""></td>
                    <td><strong>Measurement Range:</strong></td>
                    <td><input type="text" name="measurement_range" value=""></td>
                </tr>
            </table>
        </div>

        <div class="section">
            <div class="section-title">3. Environmental Conditions</div>
            <table>
                <tr>
                    <th>Parameter</th>
                    <th>Initial</th>
                    <th>Final</th>
                    <th>Unit</th>
                </tr>
                <tr>
                    <td>Temperature</td>
                    <td><input type="text" name="initial_temperature" value=""></td>
                    <td><input type="text" name="final_temperature" value=""></td>
                    <td>°C</td>
                </tr>
                <tr>
                    <td>Relative Humidity</td>
                    <td><input type="text" name="initial_humidity" value=""></td>
                    <td><input type="text" name="final_humidity" value=""></td>
                    <td>%RH</td>
                </tr>
            </table>
        </div>

        <div class="section">
            <div class="section-title">4. Reference Standards</div>
            <table>
                <tr>
                    <th>Equipment</th>
                    <th>Serial Number</th>
                    <th>Certificate Number</th>
                    <th>Traceability</th>
                </tr>
                <tr>
                    <td><input type="text" name="reference_equipment" value=""></td>
                    <td><input type="text" name="reference_serial_number" value=""></td>
                    <td><input type="text" name="reference_certificate_number" value=""></td>
                    <td><input type="text" name="reference_traceability" value=""></td>
                </tr>
            </table>
        </div>

        <div class="section">
            <div class="section-title">5. Calibration Results</div>
            <table>
                <tr>
                    <th>Function</th>
                    <th>Range</th>
                    <th>Reference Value</th>
                    <th>DUT Value</th>
                    <th>Error</th>
                    <th>Uncertainty (k=2)</th>
                </tr>
                <tr>
                    <td><input type="text" name="function" value=""></td>
                    <td><input type="text" name="range" value=""></td>
                    <td><input type="text" name="reference_value" value=""></td>
                    <td><input type="text" name="dut_value" value=""></td>
                    <td><input type="text" name="error" value=""></td>
                    <td><input type="text" name="uncertainty" value=""></td>
                </tr>
            </table>
            <p>The calibration was performed according to the [APPLICABLE STANDARD].</p>
        </div>

        <div class="section">
            <div class="section-title">6. Observations</div>
            <p><textarea name="observations" rows="4" cols="50"></textarea></p>
            <ul>
                <li>The expanded measurement uncertainty has been calculated by multiplying the typical uncertainty by the factor k=2, corresponding to a coverage probability of approximately 95%.</li>
                <li>This certificate cannot be reproduced partially without written authorization from the laboratory.</li>
                <li>The results contained in this certificate refer to the moment and conditions in which the measurements were made.</li>
            </ul>
        </div>

        <div class="signatures">
            <div class="signature-box">
                <div class="signature-line">Performed by</div>
                <p><input type="text" name="performed_by" value=""><br><input type="text" name="performed_by_role" value=""></p>
            </div>
            <div class="signature-box">
                <div class="signature-line">Reviewed by</div>
                <p><input type="text" name="reviewed_by" value=""><br><input type="text" name="reviewed_by_role" value=""></p>
            </div>
            <div class="signature-box">
                <div class="signature-line">Approved by</div>
                <p><input type="text" name="approved_by" value=""><br><input type="text" name="approved_by_role" value=""></p>
            </div>
        </div>

        <div class="section">
            <button type="submit">Generate Report</button>
        </div>
    </form>
</body>
</html>
