--
-- ESTRUCTURA PARA LA TABLA 'USUARIOS'
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
    ('JamesKirk', 'admin1','SOCIO','disponible','2020-10-10',null),
    ('Spock', 'admin2','SOCIO','disponible','2020-10-10',null),
    ('Uhura', 'admin3','SOCIO','disponible','2020-10-10',null),
    ('Empedocles', 'mozo1','MOZO','disponible','2020-10-10',null),
    ('Anaximenes', 'mozo2','MOZO','disponible','2020-10-10',null),
    ('Heraclito', 'mozo3','MOZO','disponible','2020-10-10',null),
    ('Caligula', 'sommelier1','SOMMELIER','disponible','2020-10-10',null),
    ('Betular', 'repostero1','REPOSTERO','disponible','2020-10-10',null),
    ('MarcoApicio', 'cheff1','CHEFF','disponible','2020-10-10',null),
    ('Anaximandro', 'cervecero1','CERVECERO','disponible','2020-10-10',null);

--
-- ESTRUCTURA PARA LA TABLA 'PRODUCTOS'
--
CREATE TABLE productos (
    `id` INT(5) AUTO_INCREMENT PRIMARY KEY,
    `tipo` VARCHAR(50),
    `descripcion` VARCHAR(50)
);

INSERT INTO productos (tipo, descripcion)
VALUES
    ('TRAGOS-VINOS', 'DV-CATENA-MALBEC'),
    ('TRAGOS-VINOS', 'DAIKIRI'),
    ('CERVEZA', 'IPA'),
    ('CERVEZA', 'APA'),
    ('CERVEZA', 'CORONA'),
    ('PLATOS', 'MILANESA-NAPOLITANA'),
    ('PLATOS', 'MILANESA-CABALLO'),
    ('PLATOS', 'PIZZA-MUZZA'),
    ('PLATOS', 'PIZZA-4QUESOS'),
    ('PLATOS', 'PICADA-COMPLETA'),
    ('PLATOS', 'PICADA-CAMPO'),
    ('PLATOS', 'PICADA-CALIENTE'),
    ('PLATOS', 'HAMBURGUESA'),
    ('PLATOS', 'HAMBURGUESA-GARBANZO'),
    ('POSTRES', 'FLAN-MIXTO'),
    ('POSTRES', 'VOLCAN-CHOCOLATE'),
    ('POSTRES', 'HELADO-CHOCOLATE');

--
-- ESTRUCTURA PARA LA TABLA 'MESAS'
--
CREATE TABLE mesas (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `idPedido` int
    `idUsuario` int
    `estado` VARCHAR(50),
    `nombreCliente` VARCHAR(50),
    FOREIGN KEY (idUsuario) REFERENCES usuarios(id),
    FOREIGN KEY (idPedido) REFERENCES pedidos(id)
);

INSERT INTO mesas (idUsuario, estado, nombreCliente)
VALUES
    (4, 'ESPERANDO', 'Bartolo'),
    (4, 'COMIENDO', 'Margaret'),
    (null, 'VACIA', ''),
    (null, 'VACIA', ''),
    (null, 'VACIA', ''),
    (null, 'VACIA', ''),
    (null, 'VACIA', ''),
    (null, 'VACIA', ''),
    (null, 'VACIA', ''),
    (null, 'VACIA', '');

--
-- ESTRUCTURA PARA LA TABLA 'PEDIDOS'
--
CREATE TABLE pedidos (
    `id` INT(5) AUTO_INCREMENT UNSIGNED ZEROFILL PRIMARY KEY,
    `idMesa` int,
    `idProductos` int,
    `idUsuario` int,
    `estado` VARCHAR(50),
    `tiempo` DATE,
    FOREIGN KEY (idUsuario) REFERENCES usuarios(id),
    FOREIGN KEY (idMesa) REFERENCES mesas(id),
    FOREIGN KEY (idProductos) REFERENCES productos(id)
);

INSERT INTO pedidos (idMesa, idProductos, idUsuario, estado, tiempo)
VALUES
    (1, 2, 4,'PENDIENTE', "00: 00"),
    (2,'PREPARACION', 1, 3, "00: 15");