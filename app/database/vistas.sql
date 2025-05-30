
USE prestamos;



CREATE VIEW list_contratos AS

  SELECT c.idcontrato, 
  b.apellidos,
  b.nombres,
  c.monto, 
  c.interes,
   c.fechainicio, 
   c.diapago, 
   c.numcuotas, 
   c.estado 

  FROM contratos c
  JOIN beneficiarios b ON c.idbeneficiario = b.idbeneficiario
