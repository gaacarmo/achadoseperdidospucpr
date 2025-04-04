create database db_achei;
use db_achei;

create table Usuario(
    usuario_id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100),
    nome_usuario VARCHAR(20),
    senha VARCHAR(15),
    email VARCHAR(100),
    curso_usuario VARCHAR(50)
);

create table Moderador(
    moderador_id INT PRIMARY KEY AUTO_INCREMENT,
    moderador_usuario_nome VARCHAR(20),
    moderador_senha VARCHAR(15)
);


create table Postagem(
    postagem_id int PRIMARY KEY AUTO_INCREMENT,
    postagem_nome VARCHAR(100),
    postagem_descricao VARCHAR(200),
    postagem_local VARCHAR(50),
    postagem_cor VARCHAR(15),
    postagem_categoria VARCHAR(50),
    postagem_data DATE,
    postagem_image VARCHAR(100),
    postagem_usuario_tipo VARCHAR(10),
    id_usuario INT,
    FOREIGN KEY (id_usuario) REFERENCES Usuario(usuario_id)
    
);

create table Comentarios(
    comentario_id INT PRIMARY KEY AUTO_INCREMENT,
    comentario_data DATE,
    comentario_conteudo VARCHAR(500),
    postagem_id INT,
    usuario_id INT, 
    FOREIGN KEY (postagem_id) REFERENCES Postagem(postagem_id),
    FOREIGN KEY (usuario_id) REFERENCES Usuario(usuario_id)
);