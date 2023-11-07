--
-- ESTRUCTURA PARA LA TABLA 'USERS'
--

CREATE TABLE usuarios (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `usuario` VARCHAR(50),
    `clave` VARCHAR(50),
    `rol` VARCHAR(50),
    `estado` VARCHAR(50),
    `fechaAlta` DATE,
    `fechaBaja` DATE
);

INSERT INTO usuarios (usuario, clave, rol, estado, fechaAlta, fechaBaja)
VALUES
    ('JamesKirk', 'admin1','socio','disponible','2020-10-10',null),
    ('Spock', 'admin2','socio','disponible','2020-10-10',null);