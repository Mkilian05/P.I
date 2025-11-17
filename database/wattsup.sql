-- Primeiro, crie o banco de dados e selecione-o
CREATE DATABASE IF NOT EXISTS wattsup;
USE wattsup;

-- 1. Tabelas que não dependem de ninguém
CREATE TABLE usuarios(
    id_usuario INT(11) NOT NULL AUTO_INCREMENT,
    email VARCHAR(225) NOT NULL,
    senha VARCHAR(255) NOT NULL,
    nome_usuario VARCHAR(225) NOT NULL,
    is_deleted BOOLEAN,
    deleted_at DATE,
    tipo ENUM,
    PRIMARY KEY (id_usuario)
);

CREATE TABLE categoria_dispositivos(
    id_categoria INT(3) NOT NULL AUTO_INCREMENT,
    nome_categoria VARCHAR(225) NOT NULL,
    PRIMARY KEY (id_categoria)
);

-- 2. Tabelas que dependem das primeiras
CREATE TABLE casa(
    id_casa INT(11) NOT NULL AUTO_INCREMENT,
    nome_casa VARCHAR(225) NOT NULL,
    numero_casa CHAR(5) NOT NULL,
    sigla_estado ENUM('AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN', 'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO') NOT NULL,
    cep VARCHAR(10) NOT NULL, 
    cidade VARCHAR(225) NOT NULL,
    bairro VARCHAR(225) NOT NULL,
    is_deleted BOOLEAN,
    deleted_at DATE, 
    fk_usuario_id_usuario INT(11) NOT NULL,
    PRIMARY KEY (id_casa),
    FOREIGN KEY (fk_usuario_id_usuario) REFERENCES usuarios(id_usuario)
);

CREATE TABLE dispositivos(
    id_dispositivo INT(3) NOT NULL AUTO_INCREMENT,
    nome_dispositivo VARCHAR(50) NOT NULL,
    potencia_dispositivo DOUBLE NOT NULL, -- Removido (5) que era inválido para DOUBLE
    fk_categoria_dispositivo_id_categoria INT(3) NOT NULL,
    is_deleted BOOLEAN,
    deleted_at DATE,
    PRIMARY KEY (id_dispositivo),
    FOREIGN KEY (fk_categoria_dispositivo_id_categoria) REFERENCES categoria_dispositivos(id_categoria)
);

-- 3. Tabelas que dependem das anteriores
CREATE TABLE ambiente(
    id_ambiente INT(3) NOT NULL AUTO_INCREMENT,
    nome_ambiente VARCHAR(100) NOT NULL,
    fk_casa_id_casa INT(11) NOT NULL, -- Ajustado para ser igual a casa.id_casa
    is_deleted BOOLEAN,
    deleted_at DATE,
    PRIMARY KEY(id_ambiente),
    FOREIGN KEY (fk_casa_id_casa) REFERENCES casa(id_casa)
);

-- 4. Agora sim, as tabelas de ligação e histórico
CREATE TABLE ambiente_dispositivo(
    id_ambiente_dispositivo INT(3) NOT NULL AUTO_INCREMENT,
    fk_ambiente_id_ambiente INT(3) NOT NULL,
    fk_dispositivo_id_dispositivo INT(3) NOT NULL,
    quantidade INT(3),
    PRIMARY KEY (id_ambiente_dispositivo),
    FOREIGN KEY (fk_ambiente_id_ambiente) REFERENCES ambiente(id_ambiente),
    FOREIGN KEY (fk_dispositivo_id_dispositivo) REFERENCES dispositivos(id_dispositivo)
);

CREATE TABLE historico_consumo(
    id_historico INT(3) NOT NULL AUTO_INCREMENT,
    data DATE NOT NULL,
    consumo DOUBLE NOT NULL, -- Removido (4) que era inválido para DOUBLE
    tempo_uso TIME NOT NULL,
    fk_ambiente_historico INT(3) NOT NULL,
    fk_dispositivo_historico INT(3) NOT NULL,
    PRIMARY KEY (id_historico),
    FOREIGN KEY (fk_ambiente_historico) REFERENCES ambiente(id_ambiente),
    FOREIGN KEY (fk_dispositivo_historico) REFERENCES dispositivos(id_dispositivo)
);