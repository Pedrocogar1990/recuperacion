create table usuarios(
id int auto_increment primary key,
email varchar(45) unique not null,
perfil enum('Administrador', 'Usuario'),
ciudad varchar(50),
pass varchar(128) not null
); 


-- nos creamos un usuario administrador para poder funcionar pedrocogar@gmail.com,almeria,Administrador,secret0