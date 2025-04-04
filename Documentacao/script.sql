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

create table Moderador(
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome_usuario VARCHAR(20),
    senha VARCHAR(15)
  
);