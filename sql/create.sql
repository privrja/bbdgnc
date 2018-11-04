/**
 * Create DB
 */
DROP DATABASE IF EXISTS bbdgnc;
CREATE DATABASE bbdgnc CHARACTER SET utf8 COLLATE utf8_czech_ci;
USE bbdgnc;

/**
 * Create user to use
 */
CREATE USER IF NOT EXISTS 'hedgehog'@'*' IDENTIFIED BY 'vqnp7f1r';
GRANT SELECT, UPDATE, DELETE, INSERT ON bbdgnc.* TO 'hedgehog'@'*';

/**
 * create tables
 * naming convence TYPE_TABLE_COLUMN ex.: FK_USER_CONTAINERID
 * (FK = foreign key, USER = table user, CONTAINERID = column in that table)
 */
CREATE TABLE user (
    id                  int             AUTO_INCREMENT,
    mail                varchar(60)     NOT NULL,
    password            binary(60)    NOT NULL,
    CONSTRAINT PK_USER_ID PRIMARY KEY (id)
);

CREATE TABLE container (
    id                  int             AUTO_INCREMENT,
    name                varchar(60)     NOT NULL,
    share               int             NOT NULL    DEFAULT 0,
    write_access        int             NOT NULL    DEFAULT 0,
    user_id             int,
    CONSTRAINT PK_CONTAINER_ID PRIMARY KEY (id),
    CONSTRAINT FK_CONTAINER_USERID FOREIGN KEY (user_id) REFERENCES user(id)
);

CREATE TABLE reference (
    id                  int             AUTO_INCREMENT,
    csid                int,
    cid                 int,
    nor                 int,
    pdb                 varchar(255),
    cas                 varchar(255),
    CONSTRAINT PK_REFERENCE_ID PRIMARY KEY (id)
);

CREATE TABLE block (
    id                  int             AUTO_INCREMENT,
    name                varchar(60)     NOT NULL,
    acronym             varchar(20)     NOT NULL,
    residue             varchar(255)    NOT NULL,
    mass                decimal(14, 8),
    smile               text,
    container_id        int,
    reference_id        int,
    CONSTRAINT PK_BLOCK_ID PRIMARY KEY (id),
    CONSTRAINT FK_BLOCK_CONTAINERID FOREIGN KEY (container_id) REFERENCES container(id),
    CONSTRAINT FK_BLOCK_REFERENCEID FOREIGN KEY (reference_id) REFERENCES reference(id)
);

CREATE TABLE sequence (
    id                  int             AUTO_INCREMENT,
    type                varchar(17)     NOT NULL    DEFAULT 'other',
    name                varchar(60)     NOT NULL,
    formula             varchar(255)    NOT NULL,
    mass                decimal(14, 8),
    sequence            text            NOT NULL,
    branch_modification varchar(60),
    smile               text,
    container_id        int,
    reference_id        int,
    CONSTRAINT PK_SEQUENCE_ID PRIMARY KEY (id),
    CONSTRAINT FK_SEQUENCE_CONTAINERID FOREIGN KEY (container_id) REFERENCES container(id),
    CONSTRAINT FK_SEQUENCE_REFERENCEID FOREIGN KEY (reference_id) REFERENCES reference(id)
);

CREATE TABLE modification (
    id                  int             AUTO_INCREMENT,
    name                varchar(60)     NOT NULL,
    formula             varchar(255)    NOT NULL,
    mass                decimal(14, 8),
    nterminal           TINYINT(1)      NOT NULL    DEFAULT 0,
    cterminal           TINYINT(1)      NOT NULL    DEFAULT 0,
    container_id        int,
    CONSTRAINT PK_MODIFICATION_ID PRIMARY KEY (id),
    CONSTRAINT FK_MODIFICATION_CONTAINERID FOREIGN KEY (container_id) REFERENCES container(id)
);

CREATE TABLE b2s (
    block_id            int,
    sequence_id         int,
    CONSTRAINT FK_B2S_BLOCKID FOREIGN KEY (block_id) REFERENCES block(id),
    CONSTRAINT FK_B2S_SEQUENCEID FOREIGN KEY (sequence_id) REFERENCES sequence(id)
);

/**
 * Create indexes
 */
CREATE INDEX IX_BLOCK_ACRONYM ON block (acronym);
CREATE INDEX IX_BLOCK_NAME ON block (name);
CREATE INDEX IX_SEQUENCE_NAME ON sequence (name);
CREATE INDEX IX_MODIFICATION_NAME ON modification (name);
CREATE UNIQUE INDEX UQ_USER_MAIL ON user (mail);

COMMIT;
