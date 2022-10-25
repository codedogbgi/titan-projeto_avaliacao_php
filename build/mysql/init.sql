CREATE DATABASE titansoftware;

USE titansoftware;

CREATE TABLE tbl_empresa (
    id_empresa INT NOT NULL AUTO_INCREMENT,
    nome VARCHAR(255) NOT NULL,
    PRIMARY KEY (id_empresa)
);

CREATE TABLE tbl_conta_pagar (
    id_conta_pagar INT NOT NULL AUTO_INCREMENT,
    valor DECIMAL(10,2) NOT NULL,
    data_pagar DATE NOT NULL,
    pago tinyint(1) DEFAULT 0,
    id_empresa INT NOT NULL,
    CONSTRAINT PK_tbl_usuario PRIMARY KEY (id_conta_pagar),
    FOREIGN KEY (id_empresa) REFERENCES tbl_empresa(id_empresa)
);