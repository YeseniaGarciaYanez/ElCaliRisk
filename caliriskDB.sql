-- Active: 1730783796017@@127.0.0.1@3307@CALIRISK11
CREATE DATABASE CALIRISK11;

USE CALIRISK11;

/*--------------------------------------------------------------------------------------------------------------------------------------------*/
CREATE TABLE Sucursal(
    codigo_sucursal VARCHAR (3) PRIMARY KEY,
    nombre VARCHAR (30) NOT NULL,
    ciudad VARCHAR (30) NOT NULL
);

INSERT INTO Sucursal (codigo_sucursal, nombre, ciudad) VALUES
('TIJ', 'Sucursal Tijuana', 'Tijuana'),
('ENS', 'Sucursal Ensenada', 'Ensenada'),
('MXL', 'Sucursal Mexicali', 'Mexicali'),
('TEC', 'Sucursal Tecate', 'Tecate'),
('ROS', 'Sucursal Rosarito', 'Playas de Rosarito');

/*--------------------------------------------------------------------------------------------------------------------------------------------*/


CREATE TABLE cliente(
    codigocliente VARCHAR(20) PRIMARY KEY,
    nombre VARCHAR(20) not null,
    contacto VARCHAR(30) not null,
    numerotel VARCHAR(30) not null,
    usuario VARCHAR(20) not null UNIQUE,
    contraseña VARCHAR(10) not null,
    correoElec VARCHAR(50) not null UNIQUE
);


INSERT INTO cliente (codigocliente, nombre, contacto, numerotel, usuario, contraseña, correoElec) VALUES
('CLI0001', 'Beta Industries', 'Carlos López', '5551234567', 'indubeta01', 'passw1234!', 'clopez@betainc.com'),
('CLI0002', 'Omega Equipment', 'Ana Fernández', '5552345678', 'equomega02', 'passw5678!', 'afernandez@omegaequipment.com'),
('CLI0003', 'ServiMeasurements', 'Miguel Torres', '5553456789', 'servimed03', 'passw9242!', 'mtorres@servimeasurements.com'),
('CLI0004', 'TecniCal', 'Lucia Pérez', '5554567890', 'tecnical04', 'passw2345!', 'lperez@tecnical.com'),
('CLI0005', 'ProbarLab', 'Enrique Ramirez', '5555678901', 'probarla05', 'passw6789!', 'eramirez@probarlab.com'),
('CLI0006', 'Mex Measurements', 'Sandra Díaz', '5556789012', 'medimexi06', 'passw0123!', 'sdiaz@mexmeasurements.com'),
('CLI0007', 'PRO Calibrations', 'Javier Martínez', '5557890123', 'calibpro07', 'passw3456!', 'jmartinez@procalibrations.com'),
('CLI0008', 'Elite Testing', 'Paula García', '5558901234', 'pruelite08', 'passw7890!', 'pgarcia@elitetesting.com'),
('CLI0009', 'North Industries', 'Andrea Gutiérrez', '5559012345', 'indnorth09', 'passw1234!', 'agutierrez@northindustries.com'),
('CLI0010', 'Delta Solutions', 'Martín Castillo', '5550123456', 'soldelta10', 'passw5678!', 'mcastillo@deltasolutions.com'),
('CLI0011', 'CR Supplies', 'Ricardo Hernández', '5551236789', 'suminicr11', 'passw8901!', 'rhernandez@crsupplies.com'),
('CLI0012', 'Sure Calibrations', 'Miriam Suárez', '5552347890', 'calisure12', 'passw2345!', 'msuarez@surecalibrations.com'),
('CLI0013', 'ACME Meters', 'Francisco López', '5553458901', 'mediacme13', 'passw6789!', 'flopez@acmemeters.com'),
('CLI0014', 'AZ Controls', 'Natalia Jiménez', '5554569012', 'controle14', 'passw0123!', 'njimenez@azcontrols.com'),
('CLI0015', 'Industrial Equipment', 'Jesús Rodríguez', '5555670123', 'equipoin15', 'passw3456!', 'jrodriguez@industrialequipment.com'),
('CLI0016', 'Prime Metrology', 'Isabel Mendoza', '5556781234', 'metprime16', 'passw7890!', 'imendoza@primemetrology.com'),
('CLI0017', 'Control Test', 'Juan Ortega', '5557892345', 'conttest17', 'passw1234!', 'jortega@controltest.com'),
('CLI0018', 'Kappa Industrial', 'Silvia Flores', '5558903456', 'indkappa18', 'passw5678!', 'sflores@kappaindustrial.com'),
('CLI0019', 'Sigma Testing', 'Alberto Sánchez', '5559014567', 'prusigma19', 'passw9242!', 'asanchez@sigmatesting.com'),
('CLI0020', 'Total Calibration', 'Lorena Peña', '5550125678', 'calitota20', 'passw2345!', 'lpena@totalcalibration.com'),
('CLI0021', 'MacroSolutions', 'Roberto Vázquez', '5551236789', 'macrosol21', 'passw6789!', 'rvazquez@macrosolutions.com'),
('CLI0022', 'Exact Dimension', 'Valeria Ramos', '5552347890', 'dimenexa22', 'passw0123!', 'vramos@exactdimension.com'),
('CLI0023', 'Southeast Industrial', 'Fernando Aguilar', '5553458901', 'indusure23', 'passw3456!', 'faguilar@southeastindustrial.com'),
('CLI0024', 'Measured Services', 'Beatriz Navarro', '5554569012', 'servimed24', 'passw7890!', 'bnavarro@measuredservices.com'),
('CLI0025', 'Elite Metrology', 'Carmen Ortiz', '5555670123', 'metelite25', 'passw0123!', 'cortiz@elitemetrology.com');


DELIMITER //

CREATE TRIGGER before_insert_cliente
BEFORE INSERT ON cliente
FOR EACH ROW
BEGIN
    DECLARE prefix CHAR(4) DEFAULT 'CLI-';
    DECLARE maxCode INT;
    DECLARE nextCode INT;

    -- Seleccionar el máximo código solo de registros con el formato correcto
    SELECT MAX(CAST(SUBSTRING(codigocliente, 5) AS UNSIGNED)) INTO maxCode
    FROM cliente
    WHERE codigocliente REGEXP '^CLI-[0-9]{4}$';

    -- Si no hay códigos válidos, inicia en 1
    IF maxCode IS NULL THEN
        SET nextCode = 1;
    ELSE
        SET nextCode = maxCode + 1;
    END IF;

    -- Generar el nuevo código con formato
    SET NEW.codigocliente = CONCAT(prefix, LPAD(nextCode, 4, '0'));
END //

DELIMITER ;


/*--------------------------------------------------------------------------------------------------------------------------------------------*/
CREATE TABLE area_especialidad(
    codigo VARCHAR(3) PRIMARY KEY,
    nombre VARCHAR(40) NOT NULL UNIQUE
);

INSERT INTO area_especialidad (codigo, nombre) VALUES
('AE1', 'Equipment Calibration'),
('AE2', 'Dimensional Measurement'),
('AE3', 'Support'),
('AE4', 'Controlled Environment Testing');

/*--------------------------------------------------------------------------------------------------------------------------------------------*/
CREATE TABLE categoria(
    codigo VARCHAR(20) PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE
);

INSERT INTO categoria (codigo, nombre) VALUES
('CAL', 'Calibration Equipment'),
('MED', 'Dimensional Measurement Equipment'),
('PRU', 'Equipment for Controlled Environment Testing');


/*--------------------------------------------------------------------------------------------------------------------------------------------*/
CREATE TABLE equipo(
    codigoEqp VARCHAR(20) PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    descripcion VARCHAR(100) NOT NULL,
    marca VARCHAR(20) not null,
    categoria VARCHAR(3), 
    Foreign Key (categoria) REFERENCES categoria(codigo)
);
ALTER TABLE equipo ADD COLUMN estado VARCHAR(20) NOT NULL DEFAULT 'Active';

ALTER TABLE equipo ADD COLUMN proximaRecalibracion DATE;


/* Ejemplos de marcas
 'Siemens'
 'Honeywell'
 'Fluke'
 'General Electric'
'ABB'
 'Schneider Electric'
 'Mitutoyo'
 'Yokogawa' 
'Omega Engineering'
'Keysight Technologies
*/

INSERT INTO equipo (codigoEqp, nombre, descripcion, marca, categoria) VALUES
('EQP-0001', 'Pressure Calibrator', 'Equipment for measuring and calibrating pressure in industrial systems', 'Siemens', 'CAL'),
('EQP-0002', 'Digital Multimeter', 'Instrument for measuring voltage, current, and resistance', 'Siemens', 'MED'),
('EQP-0003', 'Oscilloscope', 'Device for visualizing high-frequency electrical signals', 'Honeywell', 'MED'),
('EQP-0004', 'Sound Meter', 'Meter that detects environmental noise levels', 'Honeywell', 'PRU'),
('EQP-0005', 'Temperature Calibrator', 'Instrument for calibrating precision temperature sensors', 'Siemens', 'CAL'),
('EQP-0006', 'Humidity Meter', 'Humidity sensor for laboratory calibrations', 'ABB', 'PRU'),
('EQP-0007', 'Infrared Thermometer', 'Thermometer for non-contact temperature measurements', 'ABB', 'PRU'),
('EQP-0008', 'Vibration Sensor', 'Sensor designed to detect vibration levels', 'Mitutoyo', 'PRU'),
('EQP-0009', 'Leak Detector', 'Detector that finds leaks in gas and liquid systems', 'Mitutoyo', 'PRU'),
('EQP-0010', 'CO2 Meter', 'Environmental CO2 meter used in ventilation systems', 'Mitutoyo', 'PRU'),
('EQP-0011', 'Current Calibrator', 'Equipment for calibrating current in electronic devices', 'Schneider Electric', 'CAL'),
('EQP-0012', 'Insulation Resistance Meter', 'Meter for testing insulation resistance in cables', 'Schneider Electric', 'MED'),
('EQP-0013', 'Thermocouple', 'High-precision temperature sensor', 'Schneider Electric', 'PRU'),
('EQP-0014', 'Laser Distance Meter', 'Laser meter for long distances with high accuracy', 'Keysight', 'MED'),
('EQP-0015', 'Tachometer', 'Tachometer for measuring motor rotation speed', 'Keysight', 'MED');

INSERT INTO equipo (codigoEqp, nombre, descripcion, marca, categoria) VALUES
('EQP-0016', 'Temperature Calibrator', 'Device for precise temperature calibration in laboratories', 'Fluke', 'CAL'),
('EQP-0017', 'Flow Meter', 'Instrument for measuring fluid flow in pipelines', 'Siemens', 'MED'),
('EQP-0018', 'Pressure Calibrator', 'Equipment for measuring and calibrating pressure in industrial systems', 'WIKA', 'CAL'),
('EQP-0019', 'Sound Level Meter', 'Device for measuring noise levels in environments', 'Bruel & Kjaer', 'MED'),
('EQP-0020', 'Moisture Analyzer', 'Tool for analyzing moisture levels in materials', 'Sartorius', 'MED'),
('EQP-0021', 'Electronic Scale', 'Precision scale for weighing materials in laboratories', 'Ohaus', 'MED'),
('EQP-0022', 'Vibration Analyzer', 'Instrument for analyzing vibrations in mechanical systems', 'SKF', 'MED'),
('EQP-0023', 'Signal Generator', 'Equipment for generating electronic signals for calibration', 'Tektronix', 'CAL'),
('EQP-0024', 'Thermocouple', 'Sensor for measuring temperature in industrial processes', 'Omega', 'CAL'),
('EQP-0025', 'Torque Wrench', 'Tool for calibrating torque in industrial applications', 'Snap-on', 'CAL'),
('EQP-0026', 'Humidity Sensor', 'Sensor for monitoring and measuring humidity levels', 'Vaisala', 'MED'),
('EQP-0027', 'Pressure Transducer', 'Sensor for converting pressure into electrical signals', 'Rosemount', 'CAL'),
('EQP-0028', 'Gas Detector', 'Instrument for detecting and measuring gas levels', 'Honeywell', 'MED'),
('EQP-0029', 'Surface Roughness Tester', 'Tool for analyzing the roughness of material surfaces', 'Mitutoyo', 'MED'),
('EQP-0030', 'Power Supply Calibrator', 'Equipment for calibrating power supply units', 'BK Precision', 'CAL'),
('EQP-0031', 'Conductivity Meter', 'Equipment for measuring electrical conductivity in solutions', 'Extech', 'MED'),
('EQP-0032', 'Laser Tachometer', 'Device for measuring rotational speed without contact', 'Extech', 'MED'),
('EQP-0033', 'Temperature Data Logger', 'Device for recording and calibrating temperature data over time', 'Testo', 'CAL'),
('EQP-0034', 'Sound Calibrator', 'Tool for calibrating sound level meters in the field', 'Cirrus Research', 'CAL'),
('EQP-0035', 'Mass Flow Controller', 'Device for precise measurement and control of gas flow', 'Alicat Scientific', 'MED'),
('EQP-0036', 'Infrared Thermometer', 'Instrument for non-contact temperature measurement', 'Fluke', 'MED'),
('EQP-0037', 'Pressure Gauge Calibrator', 'Tool for calibrating pressure gauges with high accuracy', 'WIKA', 'CAL'),
('EQP-0038', 'Liquid Flow Meter', 'Device for measuring the flow of liquids in pipelines', 'Siemens', 'MED'),
('EQP-0039', 'Temperature Bath', 'Tool for calibration of thermometers and sensors', 'Julabo', 'CAL'),
('EQP-0040', 'Environmental Data Logger', 'Instrument for monitoring and measuring environmental parameters', 'Campbell Scientific', 'MED');



DELIMITER //

CREATE TRIGGER before_insert_equipo
BEFORE INSERT ON equipo
FOR EACH ROW
BEGIN
    DECLARE prefix CHAR(4) DEFAULT 'EQP-';
    DECLARE maxCode VARCHAR(20);
    DECLARE nextCode INT;

    SELECT MAX(SUBSTRING(codigoEqp, 5)) INTO maxCode FROM equipo;

    IF maxCode IS NULL THEN
        SET nextCode = 1;
    ELSE
        SET nextCode = CAST(maxCode AS UNSIGNED) + 1;
    END IF;

    SET NEW.codigoEqp = CONCAT(prefix, LPAD(nextCode, 4, '0'));
END //

DELIMITER;

/*--------------------------------------------------------------------------------------------------------------------------------------------*/
CREATE TABLE certificado(
    codigo VARCHAR(20) PRIMARY KEY,
    fecha DATE not NULL,
    intervalo INT NOT NULL
);

INSERT INTO certificado (codigo, fecha, intervalo) VALUES
('CERT-0001', '2021-05-10', '6'),
('CERT-0002', '2021-06-15', '6'),
('CERT-0003', '2022-03-22', '6'),
('CERT-0004', '2022-07-19', '6'),
('CERT-0005', '2023-01-05', '6'),
('CERT-0006', '2023-04-28', '6'),
('CERT-0007', '2023-10-15', '6'),
('CERT-0008', '2024-02-28', '6'),
('CERT-0009', '2024-03-12', '6'),
('CERT-0010', '2024-06-01', '6'),
('CERT-0011', '2024-07-20', '6'),
('CERT-0012', '2024-08-15', '6'),
('CERT-0013', '2024-09-10', '6'),
('CERT-0014', '2024-09-25', '6'),
('CERT-0015', '2024-10-01', '6'),
('CERT-0016', '2024-10-05', '6'),
('CERT-0017', '2024-10-15', '6'),
('CERT-0018', '2024-10-20', '6'),
('CERT-0019', '2024-10-25', '6'),
('CERT-0020', '2024-10-30', '6'),
('CERT-0021', '2024-10-31', '6'),
('CERT-0022', '2024-10-05', '6'),
('CERT-0023', '2024-10-01', '6'),
('CERT-0024', '2024-10-02', '6'),
('CERT-0025', '2024-10-03', '6');


CREATE TRIGGER before_insert_certificado
BEFORE INSERT ON certificado
FOR EACH ROW
BEGIN
    DECLARE prefix CHAR(5) DEFAULT 'CERT-';
    DECLARE maxCode VARCHAR(20);
    DECLARE nextCode INT;

    SELECT MAX(SUBSTRING(codigo, 6)) INTO maxCode FROM certificado;

    IF maxCode IS NULL THEN
        SET nextCode = 1;
    ELSE
        SET nextCode = CAST(maxCode AS UNSIGNED) + 1;
    END IF;

    SET NEW.codigo = CONCAT(prefix, LPAD(nextCode, 4, '0'));
END //


/*--------------------------------------------------------------------------------------------------------------------------------------------*/

CREATE TABLE administrador(
    codigo VARCHAR(20) PRIMARY KEY,
    nombrePila VARCHAR(30) NOT NULL,
    primerApellido VARCHAR(30) NOT NULL,
    segundoApellido VARCHAR(30) null,
    usuario VARCHAR(10) NOT NULL UNIQUE,
    contraseña VARCHAR(10) NOT NULL UNIQUE
);

INSERT INTO administrador (codigo, nombrePila, primerApellido, segundoApellido, usuario, contraseña) VALUES
('ADMIN00001', 'Yesenia', 'Garcia', 'Yanez', 'yesenyanez', 'C@liRisk01'),
('ADMIN00002', 'Ana', 'Cardenas', 'Martinez', 'anacardena', 'C@liRisk02'),
('ADMIN00003', 'Luis', 'Fernandez', 'Gonzalez', 'luisfernan', 'C@liRisk03'),
('ADMIN00004', 'Maria', 'Rodriguez', 'Hernandez', 'mariarodri', 'C@liRisk04'),
('ADMIN00005', 'Santi', 'semenov', 'Castro', 'santisemen', 'C@liRisk05'),
('ADMIN00006', 'Laura', 'Torres', 'Romero', 'lauratorre', 'C@liRisk06'),
('ADMIN00007', 'David', 'Morales', 'Salazar', 'davidmoral', 'C@liRisk07'),
('ADMIN00008', 'Sofia', 'Vargas', 'Mendoza', 'sofiavarga', 'C@liRisk08'),
('ADMIN00009', 'Andres', 'Rios', 'Castro', 'andresrios', 'C@liRisk09'),
('ADMIN00010', 'Valeria', 'Diaz', 'Ponce', 'valeridiaz', 'C@liRisk10');

DELIMITER//
CREATE TRIGGER before_insert_administrador
BEFORE INSERT ON administrador
FOR EACH ROW
BEGIN
    DECLARE prefix CHAR(5) DEFAULT 'ADMIN';
    DECLARE maxCode VARCHAR(20);
    DECLARE nextCode INT;

    SELECT MAX(SUBSTRING(codigo, 6)) INTO maxCode FROM administrador;

    IF maxCode IS NULL THEN
        SET nextCode = 1;
    ELSE
        SET nextCode = CAST(maxCode AS UNSIGNED) + 1;
    END IF;

    SET NEW.codigo = CONCAT(prefix, LPAD(nextCode, 5, '0'));
END //

DELIMITER;

/*--------------------------------------------------------------------------------------------------------------------------------------------*/

CREATE TABLE servicio(
    codigo VARCHAR(20) PRIMARY KEY,
    nombre VARCHAR(50) not null,
    descripcion VARCHAR(100) NOT NULL,
    administrador VARCHAR(20),
    Foreign Key (administrador) REFERENCES administrador(codigo)
);

INSERT INTO servicio (codigo, nombre, descripcion, administrador) VALUES
('SERCAL001', 'Calibration Service', 'Calibration of industrial equipment.', 'ADMIN00001'),
('SERMES001', 'Dimensional Measurement', 'Performing precise measurements for control.', 'ADMIN00002'),
('SERTEST001', 'Environment Testing', 'Testing in controlled environments.', 'ADMIN00003'),
('SERREP001', 'Equipment Repair', 'Repair of Measurement and Calibration equipment.', 'ADMIN00004');

/*--------------------------------------------------------------------------------------------------------------------------------------------*/


CREATE TABLE herramienta(
    codigoProducto VARCHAR(10) PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    descripcion VARCHAR(50) not NULL,
    categoria VARCHAR(3),
    Foreign Key (categoria) REFERENCES categoria(codigo)
);

INSERT INTO herramienta (codigoProducto, nombre, descripcion, categoria) VALUES
('H-0001', 'Digital Calibrator', 'Tool for calibrating devices', 'CAL'),
('H-0002', 'Vernier', 'Precision measuring instrument', 'MED'),
('H-0003', 'Manometer', 'Pressure gauge for hydraulic systems', 'PRU'),
('H-0004', 'Spectrometer', 'Tool for spectral analysis', 'CAL'),
('H-0005', 'Multimeter', 'Device for measuring voltage and current', 'MED'),
('H-0006', 'Digital Thermometer', 'Tool for measuring temperature', 'PRU'),
('H-0007', 'Load Cell', 'Sensor for measuring force or weight', 'CAL'),
('H-0008', 'Flow Meter', 'Tool for measuring fluid flow rate', 'MED'),
('H-0009', 'pH Calibrator', 'Instrument for measuring acidity or alkalinity', 'PRU'),
('H-0010', 'Laboratory Balances', 'Tool for precision mass measurement', 'CAL');


DELIMITER// 
CREATE TRIGGER before_insert_herramienta
BEFORE INSERT ON herramienta
FOR EACH ROW
BEGIN
    DECLARE prefix CHAR(1) DEFAULT 'H';
    DECLARE maxCode VARCHAR(10);
    DECLARE nextCode INT;

    SELECT MAX(SUBSTRING(codigoProducto, 3)) INTO maxCode FROM herramienta;

    IF maxCode IS NULL THEN
        SET nextCode = 1;
    ELSE
        SET nextCode = CAST(maxCode AS UNSIGNED) + 1;
    END IF;

    SET NEW.codigoProducto = CONCAT(prefix, '-', LPAD(nextCode, 4, '0'));
END //

DELIMITER//

/*--------------------------------------------------------------------------------------------------------------------------------------------*/


CREATE TABLE pedido(
    CodigoPedido VARCHAR(20) PRIMARY KEY,
    fechaPedido DATE not NULL,
    fechaEjecucion DATE NOT NULL,
    estado ENUM('Pending', 'Review', 'Completed', 'Cancelled') NOT NULL DEFAULT 'Pending',
    cliente VARCHAR(20),
    servicio VARCHAR (10),
    equipo VARCHAR (10),
    Foreign Key (cliente) REFERENCES cliente(codigoCliente),
    Foreign Key (servicio) REFERENCES servicio(codigo),
    Foreign Key (equipo) REFERENCES equipo(codigoEqp)
);

ALTER TABLE pedido ADD COLUMN Resultado VARCHAR(4);

ALTER TABLE pedido
    ADD COLUMN codigoCertificado VARCHAR(20),  -- Nueva columna para almacenar el código del certificado
    ADD FOREIGN KEY (codigoCertificado) REFERENCES certificado(codigo);  -- Relación con la tabla certificado


INSERT INTO pedido (CodigoPedido, fechaPedido, fechaEjecucion, estado, cliente, servicio, equipo) VALUES
('P-001', '2024-01-05', '2024-01-08', 'Completed', 'CLI-0001', 'SERCAL001', 'EQP-0001'),
('P-002', '2024-01-20', '2024-01-23', 'Completed', 'CLI-0002', 'SERCAL001', 'EQP-0002'),
('P-003', '2024-02-10', '2024-02-13', 'Completed', 'CLI-0003', 'SERCAL001', 'EQP-0003'),
('P-004', '2024-02-25', '2024-02-28', 'Completed', 'CLI-0004', 'SERCAL001', 'EQP-0004'),
('P-005', '2024-03-05', '2024-03-08', 'Completed', 'CLI-0005', 'SERCAL001', 'EQP-0005'),
('P-006', '2024-03-18', '2024-03-21', 'Completed', 'CLI-0006', 'SERCAL001', 'EQP-0006'),
('P-007', '2024-04-02', '2024-04-05', 'Completed', 'CLI-0007', 'SERCAL001', 'EQP-0007'),
('P-008', '2024-04-16', '2024-04-19', 'Completed', 'CLI-0008', 'SERCAL001', 'EQP-0008'),
('P-009', '2024-05-01', '2024-05-04', 'Completed', 'CLI-0009', 'SERCAL001', 'EQP-0009'),
('P-010', '2024-05-20', '2024-05-23', 'Completed', 'CLI-0010', 'SERCAL001', 'EQP-0010'),
('P-011', '2024-06-04', '2024-06-07', 'Completed', 'CLI-0011', 'SERCAL001', 'EQP-0011'),
('P-012', '2024-06-18', '2024-06-21', 'Completed', 'CLI-0012', 'SERCAL001', 'EQP-0012'),
('P-013', '2024-07-03', '2024-07-06', 'Completed', 'CLI-0013', 'SERCAL001', 'EQP-0013'),
('P-014', '2024-07-17', '2024-07-20', 'Completed', 'CLI-0014', 'SERCAL001', 'EQP-0014'),
('P-015', '2024-08-01', '2024-08-04', 'Completed', 'CLI-0015', 'SERCAL001', 'EQP-0015');

INSERT INTO pedido (CodigoPedido, fechaPedido, fechaEjecucion, estado, cliente, servicio, equipo) VALUES
('P-016', '2024-11-05', '2024-12-05', 'Pendiente', 'CLI-0001', 'SERCAL001', 'EQP-0016');

INSERT INTO pedido (CodigoPedido, fechaPedido, fechaEjecucion, estado, cliente, servicio, equipo) VALUES
('P-017', '2024-11-07', '2024-12-07', 'Pendiente', 'CLI-0001', 'SERCAL001', 'EQP-0016');

DELIMITER //

CREATE TRIGGER before_insert_pedido
BEFORE INSERT ON pedido
FOR EACH ROW
BEGIN
    DECLARE prefix CHAR(2) DEFAULT 'P-';
    DECLARE maxCode VARCHAR(20);
    DECLARE nextCode INT;

    SELECT MAX(SUBSTRING(CodigoPedido, 3)) INTO maxCode FROM pedido;

    IF maxCode IS NULL THEN
        SET nextCode = 1;
    ELSE
        SET nextCode = CAST(maxCode AS UNSIGNED) + 1;
    END IF;

    SET NEW.CodigoPedido = CONCAT(prefix, LPAD(nextCode, 3, '0'));
END //

DELIMITER;

/*--------------------------------------------------------------------------------------------------------------------------------------------*/

CREATE TABLE pedido_servicio(
    pedido VARCHAR(20),
    servicio VARCHAR(20),
    PRIMARY KEY(pedido, servicio),
    Foreign Key (pedido) REFERENCES pedido(CodigoPedido),
    Foreign Key (servicio) REFERENCES servicio(codigo)
);

INSERT INTO pedido_servicio (pedido, servicio) VALUES
('P-001', 'SERCAL001'),
('P-002', 'SERMES001'),
('P-003', 'SERTEST001'),
('P-004', 'SERREP001'),
('P-005', 'SERCAL001'),
('P-006', 'SERMES001'),
('P-007', 'SERTEST001'),
('P-008', 'SERREP001'),
('P-009', 'SERCAL001'),
('P-010','SERMES001'),
('P-011', 'SERTEST001'),
('P-012', 'SERREP001'),
('P-013', 'SERCAL001'),
('P-014', 'SERMES001'),
('P-015', 'SERTEST001')


/*--------------------------------------------------------------------------------------------------------------------------------------------*/

CREATE TABLE servicio_equipo(
    servicio VARCHAR(10),
    equipo VARCHAR(10),
    cantidad int not null,
    PRIMARY KEY(servicio, equipo),
    Foreign Key (servicio) REFERENCES servicio(codigo),
    Foreign Key (equipo) REFERENCES equipo(codigoEqp)
);
INSERT INTO servicio_equipo (servicio, equipo, cantidad) VALUES
('SERCAL001', 'EQP-0001', 2),
('SERCAL001', 'EQP-0002', 1),
('SERCAL001', 'EQP-0003', 2),
('SERCAL001', 'EQP-0004', 2),
('SERCAL001', 'EQP-0005', 2),
('SERCAL001', 'EQP-0006', 1),
('SERCAL001', 'EQP-0007', 1),
('SERCAL001', 'EQP-0008', 1),
('SERCAL001', 'EQP-0009', 1),
('SERCAL001', 'EQP-0010', 2),
('SERCAL001', 'EQP-0011', 1),
('SERCAL001', 'EQP-0012', 1),
('SERCAL001', 'EQP-0013', 1),
('SERCAL001', 'EQP-0014', 1),
('SERCAL001', 'EQP-0015', 2);

/*--------------------------------------------------------------------------------------------------------------------------------------------*/

CREATE TABLE sucursal_area(
    sucursal VARCHAR(3),
    area_especialidad VARCHAR(3),
    PRIMARY KEY(sucursal, area_especialidad),
    Foreign Key (sucursal) REFERENCES Sucursal(codigo_sucursal),
    Foreign Key (area_especialidad) REFERENCES area_especialidad(codigo)
);

INSERT INTO sucursal_area (sucursal, area_especialidad) VALUES
('TIJ', 'AE1'),
('TIJ', 'AE2'),
('TIJ', 'AE3'),
('TIJ', 'AE4'),
('ENS', 'AE1'),
('ENS', 'AE2'),
('ENS', 'AE3'),
('ENS', 'AE4'),
('MXL', 'AE1'),
('MXL', 'AE2'),
('MXL', 'AE3'),
('MXL', 'AE4'),
('TEC', 'AE1'),
('TEC', 'AE2'),
('TEC', 'AE3'),
('TEC', 'AE4'),
('ROS', 'AE1'),
('ROS', 'AE2'),
('ROS', 'AE3'),
('ROS', 'AE4');

/*--------------------------------------------------------------------------------------------------------------------------------------------*/



CREATE TABLE pago(
    codigo VARCHAR(20) PRIMARY KEY,
    montoPago INT NOT NULL,
    fechapago DATE NOT NULL,
    concepto VARCHAR(30) NOT NULL,
    pedido VARCHAR(20),
    Foreign Key (pedido) REFERENCES pedido(CodigoPedido)
);

INSERT INTO pago (codigo, montoPago, fechapago, concepto, pedido) VALUES
('PAG-0001', '1200', '2024-01-05', 'Calibration Service', 'P-001'),
('PAG-0002', '1800', '2024-01-10', 'Dimensional Measurement', 'P-002'),
('PAG-0003', '5000', '2024-02-15', 'Measurement Equipment Sales', 'P-003'),
('PAG-0004', '2500', '2024-03-01', 'Environment Testing', 'P-004'),
('PAG-0005', '1200', '2024-03-20', 'Calibration Service', 'P-005'),
('PAG-0006', '1800', '2024-04-05', 'Dimensional Measurement', 'P-006'),
('PAG-0007', '4700', '2024-05-01', 'Testing Equipment Sales', 'P-007'),
('PAG-0008', '2500', '2024-05-20', 'Environment Testing', 'P-008'),
('PAG-0009', '1200', '2024-06-10', 'Calibration Service', 'P-009'),
('PAG-0010', '1800', '2024-07-02', 'Dimensional Measurement', 'P-010'),
('PAG-0011', '5000', '2024-07-18', 'Measurement Equipment Sales', 'P-011'),
('PAG-0012', '2500', '2024-08-05', 'Environment Testing', 'P-012'),
('PAG-0013', '1200', '2024-08-20', 'Calibration Service', 'P-013'),
('PAG-0014', '1800', '2024-09-03', 'Dimensional Measurement', 'P-014'),
('PAG-0015', '4700', '2024-09-18', 'Testing Equipment Sales', 'P-015')





DELIMITER// 
CREATE TRIGGER before_insert_pago
BEFORE INSERT ON pago
FOR EACH ROW
BEGIN
    DECLARE prefix CHAR(4) DEFAULT 'PAG-';
    DECLARE maxCode VARCHAR(20);
    DECLARE nextCode INT;

    SELECT MAX(SUBSTRING(codigo, 5)) INTO maxCode FROM pago;

    IF maxCode IS NULL THEN
        SET nextCode = 1;
    ELSE
        SET nextCode = CAST(maxCode AS UNSIGNED) + 1;
    END IF;

    SET NEW.codigo = CONCAT(prefix, LPAD(nextCode, 4, '0'));
END //

DELIMITER//

/*--------------------------------------------------------------------------------------------------------------------------------------------*/


CREATE TABLE tecnico(
    codigo VARCHAR(10) PRIMARY KEY,
    nombre VARCHAR(20) not null,
    primerApell VARCHAR(30) not null,
    segundoApell VARCHAR(30)  null,
    numTel VARCHAR(10) not null,
    usuario VARCHAR(20) not null UNIQUE,
    contraseña VARCHAR(10) not null,
    area_especialidad VARCHAR(3),
    Foreign Key (area_especialidad) REFERENCES area_especialidad(codigo)
);
INSERT INTO tecnico (codigo, nombre, primerApell, segundoApell, numTel, usuario, contraseña, area_especialidad) VALUES
('TEC01', 'Edoardo', 'Sanchez', 'Rodríguez', '5551234567', 'carlos123', 'edoardosan', 'AE1'),
('TEC02', 'Ana', 'Hernández', 'García', '5559876543', 'ana123', 'anahernand', 'AE2'),
('TEC03', 'Luis', 'Pérez', 'Sánchez', '5552468109', 'luis321', 'luisperez1', 'AE3'),
('TEC04', 'Marta', 'Torres', 'Ramírez', '5551357913', 'marta321', 'martatorre', 'AE4'),
('TEC05', 'Jorge', 'González', 'Castro', '5551122334', 'jorge234', 'jorgegonza', 'AE1'),
('TEC06', 'Rosa', 'López', 'Mendoza', '5552233445', 'rosa567', 'rlopez', 'AE2'),
('TEC07', 'José', 'Jiménez', 'Ortega', '5553344556', 'jose789', 'jjimenez', 'AE3'),
('TEC08', 'Sofía', 'Ramírez', 'Vargas', '5554455667', 'sofia101', 'sramirez', 'AE4'),
('TEC09', 'Daniel', 'Navarro', 'Pacheco', '5555566778', 'daniel456', 'dnavarro', 'AE1'),
('TEC10', 'Laura', 'Morales', 'Ruiz', '5556677889', 'laura654', 'lmorales', 'AE2'),
('TEC11', 'Arturo', 'Paredes', 'Rivera', '5557788990', 'arturo890', 'aparedes', 'AE3'),
('TEC12', 'Elena', 'Salinas', 'Reyes', '5558899001', 'elena234', 'esalinas', 'AE4'),
('TEC13', 'Pablo', 'Muñoz', 'Ramírez', '5559900012', 'pablo567', 'pmunoz', 'AE1'),
('TEC14', 'Cristina', 'Castillo', 'Fuentes', '5551010103', 'cris321', 'ccastillo', 'AE2'),
('TEC15', 'Ricardo', 'Álvarez', 'Herrera', '5552020204', 'rick789', 'ralvarez', 'AE3'),
('TEC16', 'Diana', 'Sánchez', 'Rodríguez', '5553030305', 'diana456', 'dsanchez', 'AE4'),
('TEC17', 'Javier', 'Vargas', 'Gómez', '5554040406', 'javier987', 'jvargas', 'AE1'),
('TEC18', 'Paula', 'Flores', 'Morales', '5555050507', 'paula654', 'pflores', 'AE2'),
('TEC19', 'Alberto', 'Gutiérrez', 'Naranjo', '5556060608', 'alberto123', 'agutierrez', 'AE3'),
('TEC20', 'Mariana', 'Aguilar', 'Ortiz', '5557070709', 'mariana890', 'maguilar', 'AE4'),
('TEC21', 'Emilio', 'Domínguez', 'Ponce', '5558080801', 'emilio234', 'edominguez', 'AE1'),
('TEC22', 'Lucía', 'Cruz', 'Villanueva', '5559090902', 'lucia567', 'luciacruzv', 'AE2'),
('TEC23', 'Fernando', 'Velázquez', 'Silva', '5550101013', 'fernando890', 'fvelazquez', 'AE3'),
('TEC24', 'Verónica', 'León', 'Pérez', '5551212124', 'vero321', 'veronicale', 'AE4'),
('TEC25', 'Eduardo', 'Soto', 'Méndez', '5552222225', 'eduardo234', 'eduardosot', 'AE1'),
('TEC26', 'Patricia', 'Rojas', 'Salazar', '5553333336', 'patricia567', 'projas', 'AE2'),
('TEC27', 'Miguel', 'Miranda', 'Martínez', '5554444447', 'miguel789', 'mmiranda', 'AE3'),
('TEC28', 'Gabriela', 'Cruz', 'Herrera', '5555555558', 'gabi456', 'gcruz', 'AE4'),
('TEC29', 'Raúl', 'Reyes', 'Ortega', '5556666669', 'raul987', 'rreyes', 'AE1'),
('TEC30', 'Sara', 'Lozano', 'Gómez', '5557777770', 'sara654', 'slozano', 'AE2'),
('TEC31', 'Ernesto', 'Molina', 'Torres', '5558888881', 'ernesto123', 'emolina', 'AE3'),
('TEC32', 'Fabiola', 'Chávez', 'Ríos', '5559999992', 'fabi234', 'fchavez', 'AE4'),
('TEC33', 'Oscar', 'Bautista', 'Sánchez', '5550101113', 'oscar567', 'obautista', 'AE1'),
('TEC34', 'Nancy', 'Robles', 'Rodríguez', '5551212124', 'nancy321', 'nrobles', 'AE2'),
('TEC35', 'Andrés', 'Ponce', 'Muñoz', '5552323235', 'andres234', 'aponce', 'AE3'),
('TEC36', 'Cecilia', 'Mena', 'Salinas', '5553434346', 'ceci789', 'cmena1', 'AE4'),
('TEC37', 'Germán', 'Marín', 'Rivera', '5554545457', 'german456', 'gmarin', 'AE1'),
('TEC38', 'Karla', 'Ortiz', 'Fernández', '5555656568', 'karla101', 'kortiz', 'AE2'),
('TEC39', 'Ramón', 'Ramírez', 'Núñez', '5556767679', 'ramon890', 'rramirez', 'AE3'),
('TEC40', 'Liliana', 'Herrera', 'Gómez', '5557878780', 'lili567', 'lherrera', 'AE4'),
('TEC41', 'Hugo', 'Ruiz', 'Márquez', '5558989891', 'hugo2346', 'hruiz', 'AE1'),
('TEC42', 'Claudia', 'Silva', 'Rodríguez', '5559090902', 'claudia789', 'csilva', 'AE2'),
('TEC43', 'Joaquín', 'Escobar', 'Paredes', '5550102023', 'joaquin321', 'jescobar', 'AE3'),
('TEC44', 'Marisol', 'Montero', 'Torres', '5551213134', 'marisol890', 'mmontero', 'AE4'),
('TEC45', 'Samuel', 'Rivera', 'Ortega', '5552324245', 'samuel654', 'srivera', 'AE1'),
('TEC46', 'Brenda', 'Figueroa', 'Núñez', '5553435356', 'brenda456', 'brendafigu', 'AE2'),
('TEC47', 'Diego', 'Aguilar', 'López', '5554546467', 'diego123', 'daguilar', 'AE3'),
('TEC48', 'Teresa', 'Moreno', 'Álvarez', '5555657578', 'teresa567', 'tmoreno', 'AE4'),
('TEC49', 'Alfonso', 'Mendoza', 'Castillo', '5556768689', 'alfonso321', 'amendoza', 'AE1'),
('TEC50', 'Julia', 'Castro', 'Mendoza', '5557879790', 'julia890', 'jcastro', 'AE2'),
('TEC51', 'Martín', 'Flores', 'Moreno', '5558980801', 'martin234', 'mflores', 'AE3'),
('TEC52', 'Yessica', 'Soto', 'Sánchez', '5559091912', 'yessi321', 'ysoto', 'AE4'),
('TEC53', 'Sergio', 'Salazar', 'Pérez', '5550102024', 'sergio456', 'ssalazar', 'AE1'),
('TEC54', 'Nadia', 'Cabrera', 'Martínez', '5551213135', 'nadia789', 'ncabrera', 'AE2'),
('TEC55', 'Fernando', 'Olivares', 'Reyes', '5552324246', 'fernando123', 'folivares', 'AE3'),
('TEC56', 'Esteban', 'Palacios', 'Hernández', '5553435357', 'esteban567', 'epalacios', 'AE4'),
('TEC57', 'Oscar', 'Pérez', 'Muñoz', '5554546468', 'oscar234', 'operez', 'AE1'),
('TEC58', 'Isabel', 'Martínez', 'Ramírez', '5555657579', 'isabel567', 'imartinez', 'AE2'),
('TEC59', 'Jorge', 'Ramírez', 'Ortega', '5556768680', 'jorge321', 'jramirez', 'AE3'),
('TEC60', 'Fernanda', 'Vargas', 'Ríos', '5557879791', 'fernanda890', 'fvargas', 'AE4'),
('TEC61', 'Carlos', 'López', 'Gómez', '5558980802', 'carlos789', 'clopez', 'AE1'),
('TEC62', 'Beatriz', 'Sánchez', 'Castillo', '5559091913', 'beatriz123', 'bsanchez', 'AE2'),
('TEC63', 'Luis', 'Ortega', 'Núñez', '5550102025', 'luis456', 'lortega', 'AE3'),
('TEC64', 'Susana', 'Castillo', 'Márquez', '5551213136', 'susana890', 'scastillo', 'AE4'),
('TEC65', 'Armando', 'Ortega', 'Pérez', '5552324247', 'armando567', 'aortega', 'AE1'),
('TEC66', 'Camila', 'Mena', 'Herrera', '5553435358', 'camila101', 'cmena', 'AE2'),
('TEC67', 'Francisco', 'Navarro', 'Ramírez', '5554546469', 'fran234', 'fnavarro', 'AE3'),
('TEC68', 'Elena', 'Muñoz', 'Gutiérrez', '5555657570', 'elena321', 'emunoz', 'AE4'),
('TEC69', 'Vicente', 'Villanueva', 'Herrera', '5556768681', 'vicente890', 'vvilla', 'AE1'),
('TEC70', 'Valeria', 'Escamilla', 'Morales', '5557879792', 'valeria456', 'vescami', 'AE2'),
('TEC71', 'Raúl', 'Meza', 'Mendoza', '5558980803', 'raul654', 'rmeza', 'AE3'),
('TEC72', 'Mónica', 'Gálvez', 'Rivera', '5559091914', 'monica789', 'mgalvez', 'AE4'),
('TEC73', 'Pedro', 'Núñez', 'Sánchez', '5550102026', 'pedro123', 'pnunez', 'AE1'),
('TEC74', 'Alicia', 'Morales', 'Torres', '5551213137', 'alicia890', 'amorales', 'AE2'),
('TEC75', 'Hugo', 'Paredes', 'Rivas', '5552324248', 'hugo2345', 'hparedes', 'AE3'),
('TEC76', 'Silvia', 'Torres', 'Reyes', '5553435359', 'silvia567', 'storres', 'AE4'),
('TEC77', 'Rolando', 'Estrada', 'Molina', '5554546460', 'rolo321', 'restrada', 'AE1'),
('TEC78', 'Alicia', 'Flores', 'Guzmán', '5555657571', 'alicia456', 'aflores', 'AE2'),
('TEC79', 'Tomás', 'Luna', 'Rocha', '5556768682', 'tomas789', 'tluna', 'AE3'),
('TEC80', 'Daniela', 'Rivera', 'Ortega', '5557879793', 'daniela101', 'drivera', 'AE4'),
('TEC81', 'Edoardo', 'Sanchez', 'Rivera', '5557879433', 'edoardo101', 'drivera', 'AE1'),
('TEC82', 'Hugo', 'Aguirre', 'González', '5558889894', 'hugo234', 'haguirre', 'AE2'),
('TEC83', 'Patricia', 'Serrano', 'Ramos', '5559990005', 'patricia456', 'pserrano', 'AE3'),
('TEC84', 'Gerardo', 'Salazar', 'Silva', '5550001116', 'gerardo123', 'gsalazar', 'AE4'),
('TEC85', 'Valeria', 'Ramos', 'Hernández', '5551112227', 'valeria890', 'vramos', 'AE1'),
('TEC86', 'Martín', 'Castro', 'Paredes', '5552223338', 'martin567', 'mcastro', 'AE2'),
('TEC87', 'Camilo', 'Torres', 'Lira', '5553334449', 'camilo101', 'ctorres', 'AE3'),
('TEC88', 'Mariana', 'Medina', 'Córdova', '5554445550', 'mariana123', 'mmedina', 'AE4'),
('TEC89', 'Esteban', 'Méndez', 'López', '5555556661', 'esteban234', 'emendez', 'AE1'),
('TEC90', 'Claudia', 'Gutiérrez', 'Lazo', '5556667772', 'claudia78', 'cgutierrez', 'AE2'),
('TEC91', 'Manuel', 'Ibarra', 'Mena', '5557778883', 'manuel567', 'mibarra', 'AE3'),
('TEC92', 'Andrea', 'Noguera', 'Hernández', '5558889994', 'andrea101', 'anoguera', 'AE4'),
('TEC93', 'Gerardo', 'Cortés', 'Romero', '5559990005', 'gerardo234', 'gcortes', 'AE1'),
('TEC94', 'Teresa', 'Ayala', 'Villanueva', '5550101116', 'teresa56', 'tayala', 'AE2'),
('TEC95', 'Rubén', 'Rivas', 'Santamaría', '5551212127', 'ruben890', 'rrivas', 'AE3'),
('TEC96', 'Nayeli', 'Carrillo', 'Arriaga', '5552323238', 'nayeli321', 'ncarrillo', 'AE4'),
('TEC97', 'César', 'Salgado', 'Morales', '5553434349', 'cesar456', 'csalgado', 'AE1'),
('TEC98', 'Berenice', 'Lima', 'Castillo', '5554545450', 'berenice789', 'blima', 'AE2'),
('TEC99', 'Enrique', 'Mejía', 'Méndez', '5555656561', 'enrique123', 'emeji', 'AE3'),
('TEC100', 'Luciana', 'Ramírez', 'Torres', '5556767672', 'luciana234', 'lramirez', 'AE4');



 
DELIMITER //
CREATE TRIGGER before_insert_tecnico
BEFORE INSERT ON tecnico
FOR EACH ROW
BEGIN
    DECLARE prefix CHAR(3) DEFAULT 'TEC';
    DECLARE maxCode INT;
    DECLARE nextCode INT;

    -- Asegúrate de que solo se consideren los números al final del código.
    SELECT MAX(CAST(SUBSTRING(codigo, 4) AS UNSIGNED)) INTO maxCode
    FROM tecnico
    WHERE codigo REGEXP '^TEC[0-9]{4}$';

    IF maxCode IS NULL THEN
        SET nextCode = 1;
    ELSE
        SET nextCode = maxCode + 1;
    END IF;

    SET NEW.codigo = CONCAT(prefix, LPAD(nextCode, 4, '0'));
END //
DELIMITER ;



/*--------------------------------------------------------------------------------------------------------------------------------------------*/


CREATE TABLE cliente_servicio(
    cliente VARCHAR(20),
    servicio VARCHAR(20),
    PRIMARY KEY(cliente, servicio),
    Foreign Key (cliente) REFERENCES cliente(codigocliente),
    Foreign Key (servicio) REFERENCES servicio(codigo)
);


INSERT INTO cliente_servicio (cliente, servicio)VALUES
('CLI-0001', 'SERCAL001'),
('CLI-0002', 'SERMES001'),
('CLI-0003', 'SERTEST001'),
('CLI-0004', 'SERREP001'),
('CLI-0005', 'SERCAL001'),
('CLI-0006', 'SERMES001'),
('CLI-0007', 'SERTEST001'),
('CLI-0008', 'SERREP001'),
('CLI-0009', 'SERCAL001'),
('CLI-0010', 'SERMES001'),
('CLI-0011', 'SERTEST001'),
('CLI-0012', 'SERREP001'),
('CLI-0013', 'SERCAL001'),
('CLI-0014', 'SERMES001'),
('CLI-0015', 'SERTEST001'),
('CLI-0016', 'SERREP001'),
('CLI-0017', 'SERCAL001'),
('CLI-0018', 'SERMES001'),
('CLI-0019', 'SERTEST001'),
('CLI-0020', 'SERREP001'),
('CLI-0021', 'SERCAL001'),
('CLI-0022', 'SERMES001'),
('CLI-0023', 'SERTEST001'),
('CLI-0024', 'SERREP001'),
('CLI-0025', 'SERCAL001');

/*--------------------------------------------------------------------------------------------------------------------------------------------*/

CREATE TABLE tipo_certificado(
    certificado VARCHAR(20),
    equipo VARCHAR(10),
    tipo VARCHAR(30) not null,
    PRIMARY KEY(certificado, equipo),
    Foreign Key (certificado) REFERENCES certificado(codigo),
    Foreign Key (equipo) REFERENCES equipo(codigoEqp)
);


INSERT INTO tipo_certificado (certificado, equipo, tipo) VALUES
('CERT-0001', 'EQP-0001', 'Calibration Certificate'),
('CERT-0002', 'EQP-0002', 'Calibration Certificate'),
('CERT-0003', 'EQP-0003', 'Calibration Certificate'),
('CERT-0004', 'EQP-0004', 'Calibration Certificate'),
('CERT-0005', 'EQP-0005', 'Calibration Certificate'),
('CERT-0006', 'EQP-0006', 'Calibration Certificate'),
('CERT-0007', 'EQP-0007', 'Calibration Certificate'),
('CERT-0008', 'EQP-0008', 'Calibration Certificate'),
('CERT-0009', 'EQP-0009', 'Calibration Certificate'),
('CERT-0010', 'EQP-0010', 'Calibration Certificate'),
('CERT-0011', 'EQP-0011', 'Calibration Certificate'),
('CERT-0012', 'EQP-0012', 'Calibration Certificate'),
('CERT-0013', 'EQP-0013', 'Calibration Certificate'),
('CERT-0014', 'EQP-0014', 'Calibration Certificate'),
('CERT-0015', 'EQP-0015', 'Calibration Certificate');


/*--------------------------------------------------------------------------------------------------------------------------------------------*/


CREATE TABLE reportes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo VARCHAR(50) NOT NULL,
    fechaGeneracion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    archivo VARCHAR(255) NOT NULL
);


INSERT INTO reportes (tipo, archivo) VALUES
('Equipment Due for Calibration', 'reporte_proximos.pdf'),
('Equipment with Expired Calibration', 'reporte_vencidos.pdf');

/*--------------------------------------------------------------------------------------------------------------------------------------------*/

CREATE TABLE test_info (
    test_id INT PRIMARY KEY AUTO_INCREMENT,
    report_number VARCHAR(50) NOT NULL,
    test_location VARCHAR(100) NOT NULL,
    room_id VARCHAR(50) NOT NULL,
    room_classification VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


/*--------------------------------------------------------------------------------------------------------------------------------------------*/

-- Tabla para los datos de monitoreo
CREATE TABLE monitoring_data (
    data_id INT PRIMARY KEY AUTO_INCREMENT,
    test_id INT NOT NULL,
    timestamp DATETIME NOT NULL,
    temperature DECIMAL(5,2) NOT NULL,  -- Temperatura en °C
    humidity DECIMAL(5,2) NOT NULL,     -- Humedad en %
    pressure DECIMAL(8,2) NOT NULL,     -- Presión en Pa
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (test_id) REFERENCES test_info(test_id)
);

/*--------------------------------------------------------------------------------------------------------------------------------------------*/

CREATE TABLE solicitudes(
    numero_solicitud INT AUTO_INCREMENT PRIMARY KEY,
    descripcion VARCHAR(255),
    fecha_timestamp TIMESTAMP
    );
    ALTER TABLE solicitudes MODIFY COLUMN descripcion VARCHAR(1000);

/*--------------------------------------------------------------------------------------------------------------------------------------------*/



    
CREATE TABLE solicitud_cliente (
    solicitud INT AUTO_INCREMENT,
    cliente VARCHAR(20),
    PRIMARY KEY (solicitud, cliente),
    FOREIGN KEY (solicitud) REFERENCES solicitudes(numero_solicitud),
    FOREIGN KEY (cliente) REFERENCES cliente(codigocliente)
);

/*--------------------------------------------------------------------------------------------------------------------------------------------*/


/*CONSULTAS USADAS EN LA PAGINA*/

/*--------------------------------------------------------------------------------------------------------------------------------------------*/

SELECT descripcion FROM solicitudes WHERE numero_solicitud = 2;

/*--------------------------------------------------------------------------------------------------------------------------------------------*/

SELECT sc.solicitud, sc.cliente 
            FROM solicitud_cliente sc
            INNER JOIN solicitudes s ON sc.solicitud = s.numero_solicitud
            INNER JOIN cliente c ON sc.cliente = c.codigocliente
            
/*--------------------------------------------------------------------------------------------------------------------------------------------*/

SELECT COUNT(*) as count FROM equipo WHERE codigoEqp = ?

/*--------------------------------------------------------------------------------------------------------------------------------------------*/

 SELECT 
            c.codigo AS codigoCertificado, 
            tc.equipo AS codigoEquipo, 
            c.fecha AS fechaExpedicion, 
            c.intervalo AS fechaVencimiento
        FROM certificado c
        JOIN tipo_certificado tc ON c.codigo = tc.certificado
        WHERE c.codigo = ?

/*--------------------------------------------------------------------------------------------------------------------------------------------*/

SELECT p.CodigoPedido, tc.certificado AS codigo_certificado, tc.equipo AS codigo_equipo, cert.fecha AS fecha_certificado
          FROM pedido p
          JOIN tipo_certificado tc ON tc.certificado = p.codigoCertificado
          JOIN certificado cert ON cert.codigo = tc.certificado
          WHERE p.cliente = ?

/*--------------------------------------------------------------------------------------------------------------------------------------------*/

SELECT 
        c.codigo AS codigoCertificado, 
        tc.equipo AS codigoEquipo, 
        c.fecha AS fechaExpedicion, 
        DATE_ADD(c.fecha, INTERVAL c.intervalo MONTH) AS fechaVencimiento
    FROM certificado c
    JOIN tipo_certificado tc ON c.codigo = tc.certificado

/*--------------------------------------------------------------------------------------------------------------------------------------------*/

SELECT codigo FROM certificado WHERE fecha = ?  ORDER BY codigo DESC LIMIT 1

SELECT pedido.CodigoPedido, pedido.fechaPedido, pedido.estado, equipo.codigoEqp AS equipo, categoria.nombre AS categoria 
          FROM pedido
          JOIN equipo ON pedido.equipo = equipo.codigoEqp
          JOIN categoria ON equipo.categoria = categoria.codigo
          WHERE pedido.estado IN ('Pending', 'Review')

/*--------------------------------------------------------------------------------------------------------------------------------------------*/
SELECT codigoCliente, nombre FROM cliente

/*--------------------------------------------------------------------------------------------------------------------------------------------*/
SELECT codigoEqp, nombre FROM equipo

/*--------------------------------------------------------------------------------------------------------------------------------------------*/
SELECT codigo, nombre FROM servicio

/*--------------------------------------------------------------------------------------------------------------------------------------------*/
SELECT * FROM pedido

/*--------------------------------------------------------------------------------------------------------------------------------------------*/
SELECT * FROM servicio

/*--------------------------------------------------------------------------------------------------------------------------------------------*/
SELECT nombrePila, usuario, 'administrador' AS tipo FROM administrador;

/*--------------------------------------------------------------------------------------------------------------------------------------------*/
SELECT nombre, usuario, 'cliente' AS tipo FROM cliente;

/*--------------------------------------------------------------------------------------------------------------------------------------------*/
SELECT nombre, usuario, 'tecnico' AS tipo FROM tecnico;

/*--------------------------------------------------------------------------------------------------------------------------------------------*/

SELECT codigoEqp FROM equipo WHERE nombre = ? AND categoria = ?

/*--------------------------------------------------------------------------------------------------------------------------------------------*/

SELECT nombre FROM categoria WHERE codigo = ?

/*--------------------------------------------------------------------------------------------------------------------------------------------*/

SELECT codigo, fecha, DATE_ADD(fecha, INTERVAL intervalo MONTH) AS vence
FROM certificado 
WHERE DATE_ADD(fecha, INTERVAL intervalo MONTH) < CURDATE()

/*--------------------------------------------------------------------------------------------------------------------------------------------*/

SELECT codigoEqp, nombre, proximaRecalibracion
FROM equipo
WHERE estado = 'Activo' AND proximaRecalibracion < CURDATE()

/*--------------------------------------------------------------------------------------------------------------------------------------------*/

SELECT codigo, fecha, DATE_ADD(fecha, INTERVAL intervalo MONTH) AS vence
FROM certificado 
WHERE DATE_ADD(fecha, INTERVAL intervalo MONTH) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY)

/*--------------------------------------------------------------------------------------------------------------------------------------------*/

SELECT codigoEqp, nombre, proximaRecalibracion
FROM equipo
WHERE estado = 'Activo' AND proximaRecalibracion BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY)

/*--------------------------------------------------------------------------------------------------------------------------------------------*/

SELECT equipo.codigoEqp, equipo.nombre, equipo.descripcion, equipo.marca, categoria.nombre AS categoria
FROM equipo
LEFT JOIN categoria ON equipo.categoria = categoria.codigo

/*--------------------------------------------------------------------------------------------------------------------------------------------*/

SELECT * FROM pedido WHERE CodigoPedido = ?

/*--------------------------------------------------------------------------------------------------------------------------------------------*/
SELECT CodigoPedido, cliente, fechaPedido, estado FROM pedido

/*--------------------------------------------------------------------------------------------------------------------------------------------*/
SELECT codigo, nombrePila, contraseña FROM administrador WHERE usuario = ?

/*--------------------------------------------------------------------------------------------------------------------------------------------*/

SELECT codigocliente, nombre, contraseña FROM cliente WHERE usuario = ?

/*--------------------------------------------------------------------------------------------------------------------------------------------*/

SELECT codigo, nombre, contraseña FROM tecnico WHERE usuario = ?

/*--------------------------------------------------------------------------------------------------------------------------------------------*/



ALTER TABLE cliente MODIFY COLUMN contraseña VARCHAR(255);
ALTER TABLE tecnico MODIFY COLUMN contraseña VARCHAR(255);
ALTER TABLE administrador MODIFY COLUMN contraseña VARCHAR(255);
