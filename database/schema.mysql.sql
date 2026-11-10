-- AcheiNaPuc — esquema MySQL/MariaDB (modelo original do projeto, revisado)
-- Uso: mysql -u root -p < database/schema.mysql.sql && DB_DRIVER=mysql php database/seed.php
CREATE DATABASE IF NOT EXISTS db_achei CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE db_achei;

CREATE TABLE Usuario (
    usuario_id     INT PRIMARY KEY AUTO_INCREMENT,
    nome           VARCHAR(100) NOT NULL,
    nome_usuario   VARCHAR(20)  NOT NULL UNIQUE,
    senha          VARCHAR(255) NOT NULL,
    email          VARCHAR(100) NOT NULL UNIQUE,
    curso_usuario  VARCHAR(80)  NOT NULL,
    foto_perfil    VARCHAR(255),
    usuario_ativo  BOOLEAN NOT NULL DEFAULT TRUE
);

CREATE TABLE Moderador (
    moderador_id           INT PRIMARY KEY AUTO_INCREMENT,
    moderador_usuario_nome VARCHAR(20)  NOT NULL UNIQUE,
    moderador_senha        VARCHAR(255) NOT NULL
);

CREATE TABLE Postagem (
    postagem_id           INT PRIMARY KEY AUTO_INCREMENT,
    postagem_nome         VARCHAR(100) NOT NULL,
    postagem_descricao    VARCHAR(500),
    postagem_local        VARCHAR(80)  NOT NULL,
    postagem_cor          VARCHAR(30)  NOT NULL,
    postagem_categoria    VARCHAR(50)  NOT NULL,
    postagem_data         DATE NOT NULL,
    postagem_timestamp    DATETIME NOT NULL,
    postagem_image        VARCHAR(255),
    postagem_usuario_tipo VARCHAR(10)  NOT NULL,
    postagem_ativa        BOOLEAN NOT NULL DEFAULT TRUE,
    postagem_resolvida    BOOLEAN NOT NULL DEFAULT FALSE,
    id_usuario            INT NOT NULL,
    id_admin              INT,
    FOREIGN KEY (id_usuario) REFERENCES Usuario(usuario_id),
    FOREIGN KEY (id_admin) REFERENCES Moderador(moderador_id),
    INDEX idx_post_feed (postagem_ativa, postagem_id)
);

CREATE TABLE Comentarios (
    comentario_id       INT PRIMARY KEY AUTO_INCREMENT,
    comentario_data     DATETIME NOT NULL,
    comentario_conteudo VARCHAR(500) NOT NULL,
    comentario_privado  BOOLEAN NOT NULL DEFAULT FALSE,
    postagem_id         INT NOT NULL,
    usuario_id          INT NOT NULL,
    comentario_pai_id   INT DEFAULT NULL,
    FOREIGN KEY (comentario_pai_id) REFERENCES Comentarios(comentario_id) ON DELETE CASCADE,
    FOREIGN KEY (postagem_id) REFERENCES Postagem(postagem_id),
    FOREIGN KEY (usuario_id) REFERENCES Usuario(usuario_id)
);

CREATE TABLE Curtidas (
    curtida_id  INT PRIMARY KEY AUTO_INCREMENT,
    postagem_id INT NOT NULL,
    usuario_id  INT NOT NULL,
    UNIQUE KEY uq_curtida (postagem_id, usuario_id),
    FOREIGN KEY (postagem_id) REFERENCES Postagem(postagem_id),
    FOREIGN KEY (usuario_id) REFERENCES Usuario(usuario_id)
);
