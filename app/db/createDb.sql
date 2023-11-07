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


CREATE TABLE productos (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `tipo` VARCHAR(50),
    `descripcion` VARCHAR(50),
    `tiempoPrep` int
);

INSERT INTO productos (tipo, descripcion, tiempoPrep)
VALUES
    ('TRAGOS-VINOS', 'DV-CATENA-MALBEC', 0),
    ('TRAGOS-VINOS', 'MOJITO', 5),
    ('CERVEZA', 'IPA', 5),
    ('CERVEZA', 'APA', 5),
    ('CERVEZA', 'GOLDEN', 5),
    ('PLATOS', 'MILANESA-NAPOLITANA', 25),
    ('PLATOS', 'MILANESA-CABALLO', 20),
    ('PLATOS', 'PIZZA-MUZZA', 20),
    ('PLATOS', 'PIZZA-4QUESOS', 20),
    ('PLATOS', 'PICADA-COMPLETA', 15),
    ('PLATOS', 'PICADA-CAMPO', 15),
    ('PLATOS', 'PICADA-CALIENTE', 15),
    ('PLATOS', 'HAMBURGUESA', 25),
    ('POSTRES', 'FLAN-MIXTO', 7),
    ('POSTRES', 'VOLCAN-CHOCOLATE', 18),
    ('POSTRES', 'HELADO-CHOCOLATE', 3)

