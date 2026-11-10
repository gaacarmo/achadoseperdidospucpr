-- AcheiNaPuc — esquema SQLite (usado por padrão e no deploy de demonstração)
CREATE TABLE Usuario (
    usuario_id     INTEGER PRIMARY KEY AUTOINCREMENT,
    nome           VARCHAR(100) NOT NULL,
    nome_usuario   VARCHAR(20)  NOT NULL UNIQUE COLLATE NOCASE,
    senha          VARCHAR(255) NOT NULL,
    email          VARCHAR(100) NOT NULL UNIQUE COLLATE NOCASE,
    curso_usuario  VARCHAR(80)  NOT NULL,
    foto_perfil    VARCHAR(255),
    usuario_ativo  INTEGER NOT NULL DEFAULT 1
);

CREATE TABLE Moderador (
    moderador_id           INTEGER PRIMARY KEY AUTOINCREMENT,
    moderador_usuario_nome VARCHAR(20)  NOT NULL UNIQUE COLLATE NOCASE,
    moderador_senha        VARCHAR(255) NOT NULL
);

CREATE TABLE Postagem (
    postagem_id           INTEGER PRIMARY KEY AUTOINCREMENT,
    postagem_nome         VARCHAR(100) NOT NULL,
    postagem_descricao    VARCHAR(500),
    postagem_local        VARCHAR(80)  NOT NULL,
    postagem_cor          VARCHAR(30)  NOT NULL,
    postagem_categoria    VARCHAR(50)  NOT NULL,
    postagem_data         DATE NOT NULL,
    postagem_timestamp    DATETIME NOT NULL,
    postagem_image        VARCHAR(255),
    postagem_usuario_tipo VARCHAR(10)  NOT NULL,
    postagem_ativa        INTEGER NOT NULL DEFAULT 1,
    postagem_resolvida    INTEGER NOT NULL DEFAULT 0,
    id_usuario            INTEGER NOT NULL REFERENCES Usuario(usuario_id),
    id_admin              INTEGER REFERENCES Moderador(moderador_id)
);

CREATE TABLE Comentarios (
    comentario_id       INTEGER PRIMARY KEY AUTOINCREMENT,
    comentario_data     DATETIME NOT NULL,
    comentario_conteudo VARCHAR(500) NOT NULL,
    comentario_privado  INTEGER NOT NULL DEFAULT 0,
    postagem_id         INTEGER NOT NULL REFERENCES Postagem(postagem_id),
    usuario_id          INTEGER NOT NULL REFERENCES Usuario(usuario_id),
    comentario_pai_id   INTEGER REFERENCES Comentarios(comentario_id) ON DELETE CASCADE
);

CREATE TABLE Curtidas (
    curtida_id  INTEGER PRIMARY KEY AUTOINCREMENT,
    postagem_id INTEGER NOT NULL REFERENCES Postagem(postagem_id),
    usuario_id  INTEGER NOT NULL REFERENCES Usuario(usuario_id),
    UNIQUE (postagem_id, usuario_id)
);

CREATE INDEX idx_post_feed ON Postagem (postagem_ativa, postagem_id);
CREATE INDEX idx_coment_post ON Comentarios (postagem_id);
