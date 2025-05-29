create database db_achei;
use db_achei;

create table Usuario(
    usuario_id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    nome_usuario VARCHAR(20) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    curso_usuario VARCHAR(50) NOT NULL,
    foto_perfil VARCHAR(255),
    usuario_ativo BOOLEAN NOT NULL DEFAULT TRUE
);

create table Moderador(
    moderador_id INT PRIMARY KEY AUTO_INCREMENT,
    moderador_usuario_nome VARCHAR(20) NOT NULL UNIQUE,
    moderador_senha VARCHAR(15) NOT NULL
);


create table Postagem(
    postagem_id int PRIMARY KEY AUTO_INCREMENT,
    postagem_nome VARCHAR(100) NOT NULL,
    postagem_descricao VARCHAR(200),
    postagem_local VARCHAR(50) NOT NULL,
    postagem_cor VARCHAR(15) NOT NULL,
    postagem_categoria VARCHAR(50) NOT NULL,
    postagem_data DATE NOT NULL,
    postagem_timestamp DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
    postagem_image VARCHAR(255),
    postagem_usuario_tipo VARCHAR(10) NOT NULL,
    id_usuario INT NOT NULL,
    id_admin INT,
    FOREIGN KEY (id_usuario) REFERENCES Usuario(usuario_id),
    FOREIGN KEY (id_admin) REFERENCES Moderador(moderador_id)
    
);

create table Comentarios(
    comentario_id INT PRIMARY KEY AUTO_INCREMENT,
    comentario_data DATETIME DEFAULT CURRENT_TIMESTAMP,
    comentario_conteudo VARCHAR(500) NOT NULL,
    comentario_privado BOOLEAN,
    postagem_id INT NOT NULL,
    usuario_id INT NOT NULL, 
    FOREIGN KEY (postagem_id) REFERENCES Postagem(postagem_id),
    FOREIGN KEY (usuario_id) REFERENCES Usuario(usuario_id)
);