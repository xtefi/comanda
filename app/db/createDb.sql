--
-- ESTRUCTURA PARA LA TABLA 'USERS'
--

CREATE TABLE users (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user` VARCHAR(50),
    `pass` VARCHAR(50),
    `rol` VARCHAR(50),
    `stat` VARCHAR(50),
    `hired` DATE,
    `fired` DATE
);

INSERT INTO users (user, pass, rol, stat, hired, fired)
VALUES
    ('JamesKirk', 'admin1','socio','disponible','2020-10-10',null),
    ('Spock', 'admin2','socio','disponible','2020-10-10',null);