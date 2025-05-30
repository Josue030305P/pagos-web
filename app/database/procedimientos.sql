
USE prestamos;

DELIMITER //
CREATE PROCEDURE sp_add_beneficiario(
IN apellidos_ VARCHAR(50),
IN nombres_ VARCHAR(50),
IN dni_ CHAR(8),
IN telefono_ CHAR(9),
IN direccion_ VARCHAR(90)
)
BEGIN
        INSERT INTO beneficiarios(apellidos,nombres,dni,telefono,direccion)
            VALUES(apellidos_,nombres_,dni_,telefono_,direccion_);

END //

DELIMITER ;


-- UPDATE BENEFICIARIO

DELIMITER //
CREATE PROCEDURE sp_update_beneficiario(
IN idbeneficiario_ INT,
IN apellidos_ VARCHAR(50),
IN nombres_ VARCHAR(50),
IN dni_ CHAR(8),
IN telefono_ CHAR(9),
IN direccion_ VARCHAR(90)
)
BEGIN
        UPDATE beneficiarios SET
            apellidos = apellidos_,
            nombres = nombres_,
            dni = dni_,
            telefono = telefono_,
            direccion = direccion_,
            modificado = NOW()
            WHERE idbeneficiario = idbeneficiario_ ;

END //

DELIMITER ;



DELIMITER //
CREATE PROCEDURE sp_getBYDNI_beneficiario(
IN dni_ char(8)
)
BEGIN
       SELECT idbeneficiario, apellidos, nombres, dni FROM beneficiarios WHERE dni = dni_;

END //

DELIMITER ;


CALL sp_getBYDNI_beneficiario('71774455');


DELIMITER //
CREATE PROCEDURE sp_getAll_beneficiarios()
BEGIN

    SELECT idbeneficiario, apellidos, nombres, dni, telefono, direccion FROM beneficiarios;

END //

DELIMITER ;

CALL sp_getAll_beneficiarios();




DELIMITER //
CREATE PROCEDURE sp_getBYID_beneficiarios(
IN idbeneficiario_ INT 
)
BEGIN

    SELECT idbeneficiario, apellidos, nombres, dni, telefono, direccion FROM beneficiarios WHERE idbeneficiario = idbeneficiario_;

END //

DELIMITER ;

CALL sp_getBYID_beneficiarios(1);



-- CONTRATOS


DELIMITER //

CREATE PROCEDURE sp_add_contrato(
    IN idbeneficiario_ INT,
    IN monto_ DECIMAL(7,2),
    IN interes_ DECIMAL(5,2),
    IN fechainicio_ DATE,
    IN diapago_ TINYINT,
    IN numcuotas_ TINYINT
)
BEGIN 
    INSERT INTO contratos(idbeneficiario, monto, interes, fechainicio, diapago, numcuotas)
            VALUES(idbeneficiario_, monto_, interes_, fechainicio_, diapago_, numcuotas_);
        SELECT  LAST_INSERT_ID() AS idcontrato;
END //

DELIMITER  ;

    
CALL sp_add_contrato (5,3000,5,'2025-30-05','29',12);
DROP PROCEDURE sp_add_contrato;


-- DELIMITER //
-- CREATE PROCEDURE sp_add_pago(
--     IN idbeneficiario_ INT,
--     IN monto_ DECIMAL(7,2),
--     IN interes_ DECIMAL(5,2),
--     IN fechainicio_ DATE,
--     IN diapago_ TINYINT,
--     IN numcuotas_ TINYINT
-- )
-- BEGIN 
--     INSERT INTO contratos(idbeneficiario, monto, interes, fechainicio, diapago, numcuotas)
--             VALUES(idbeneficiario_, monto_, interes_, fechainicio_, diapago_, numcuotas_);
--         SELECT  LAST_INSERT_ID() AS idcontrato;
-- END //

-- DELIMITER  ;

    
-- CALL sp_add_contrato (5,3000,5,'2025-30-05','29',12);
-- DROP PROCEDURE sp_add_contrato;





   