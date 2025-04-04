create database db_achei;
use db_achei;

create table Usuario(
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100),
    nome_usuario VARCHAR(20),
    senha VARCHAR(15),
    email VARCHAR(100),
    curso_usuario VARCHAR(50)
);
/*temos que alterar a tabela do moderador, colocar a foreign key do usuario -> usuario_id */
create table Moderador(
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome_usuario VARCHAR(20),
    senha VARCHAR(15),
    usuario_id INT,
    FOREIGN KEY (usuario_id) REFERENCES Usuario(id)
);