
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

    




   