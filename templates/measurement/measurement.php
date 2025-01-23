<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Measurement Report Form</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 20px;
    }
    h1, h2, h3 {
      text-align: center;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }
    th, td {
      padding: 10px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }
    th {
      background-color: #f2f2f2;
    }
    .input-field {
      width: 100%;
      padding: 8px;
      margin-top: 5px;
      margin-bottom: 10px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
  </style>
</head>
<body>
  <h1>Measurement Report Form</h1>
  
  <form action="../../Tecnico/submit_report.php" method="post">
    
    <h2>General Information</h2>
    <table>
      <tr>
        <th>Equipment Code: </th>
        <td><input type="text" name="project_name" class="input-field" required></td>
      </tr>
      <tr>
        <th>Report Date:</th>
        <td><input type="date" name="report_date" class="input-field" required></td>
      </tr>
      <tr>
        <th>Measurement Interval (Months):</th>
        <td><input type="number" name="measurement_interval" class="input-field" required></td>
      </tr>
      <tr>
        <th>Due Date:</th>
        <td><input type="date" name="due_date" class="input-field" required></td>
      </tr>
      <tr>
        <th>Measurement Location:</th>
        <td><input type="text" name="measurement_location" class="input-field" required></td>
      </tr>
      <tr>
        <th>Measurement Performed By:</th>
        <td><input type="text" name="measured_by" class="input-field" required></td>
      </tr>
    </table>
    
    <h2>Measurement Details</h2>
    <table>
      <tr>
        <th>Parameter</th>
        <th>Measured Value</th>
        <th>Tolerance</th>
        <th>Pass/Fail</th>
      </tr>
      <tr>
        <td><input type="text" name="parameter_1" class="input-field" required></td>
        <td><input type="number" name="value_1" class="input-field" required></td>
        <td><input type="number" name="tolerance_1" class="input-field" required></td>
        <td>
          <select name="pass_fail_1" class="input-field" required>
            <option value="Pass">Pass</option>
            <option value="Fail">Fail</option>
          </select>
        </td>
      </tr>
      <tr>
        <td><input type="text" name="parameter_2" class="input-field"></td>
        <td><input type="number" name="value_2" class="input-field"></td>
        <td><input type="number" name="tolerance_2" class="input-field"></td>
        <td>
          <select name="pass_fail_2" class="input-field">
            <option value="Pass">Pass</option>
            <option value="Fail">Fail</option>
          </select>
        </td>
      </tr>
      <tr>
        <td><input type="text" name="parameter_3" class="input-field"></td>
        <td><input type="number" name="value_3" class="input-field"></td>
        <td><input type="number" name="tolerance_3" class="input-field"></td>
        <td>
          <select name="pass_fail_3" class="input-field">
            <option value="Pass">Pass</option>
            <option value="Fail">Fail</option>
          </select>
        </td>
      </tr>
    </table>
    
    <h2>Conclusion</h2>
    <textarea name="conclusion" rows="4" class="input-field" required></textarea>
    
    <h2>Signatures</h2>
    <table>
      <tr>
        <th>Prepared By:</th>
        <td><input type="text" name="prepared_by" class="input-field" required></td>
      </tr>
      <tr>
        <th>Approved By:</th>
        <td><input type="text" name="approved_by" class="input-field" required></td>
      </tr>
    </table>
    
    <div style="text-align: center; margin-top: 20px;">
      <button type="submit" style="padding: 10px 20px; font-size: 16px;">Submit Report</button>
    </div>
  </form>
</body>
</html>
