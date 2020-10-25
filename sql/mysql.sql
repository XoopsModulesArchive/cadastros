CREATE TABLE cadastros (
    id       INT(10)      NOT NULL AUTO_INCREMENT,
    nome     VARCHAR(50)  NOT NULL DEFAULT '',
    endereco VARCHAR(100) NOT NULL DEFAULT '',
    bairro   VARCHAR(100) NOT NULL DEFAULT '',
    contato  VARCHAR(150) NOT NULL DEFAULT '',
    ativo    TINYINT(1)   NOT NULL DEFAULT '1',
    PRIMARY KEY (id),
    KEY ativo (ativo)
)
    ENGINE = ISAM;
