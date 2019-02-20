BEGIN TRANSACTION;

CREATE TABLE user (
    id                  INTEGER       PRIMARY KEY,
    mail                TEXT          NOT NULL,
    password            BLOB          NOT NULL
);

CREATE TABLE container (
    id                  INTEGER       PRIMARY KEY,
    name                TEXT          NOT NULL,
    share               INTEGER       NOT NULL    DEFAULT 0,
    write_access        INTEGER       NOT NULL    DEFAULT 0,
    user_id             INTEGER,
    FOREIGN KEY (user_id) REFERENCES user(id)
);

CREATE TABLE block (
    id                  INTEGER         PRIMARY KEY,
    name                TEXT            NOT NULL,
    acronym             TEXT            NOT NULL,
    residue             TEXT            NOT NULL,
    mass                REAL,
    smiles              TEXT,
    usmiles             TEXT,
    database            INTEGER,
    identifier          TEXT,
    container_id        INTEGER,
    FOREIGN KEY (container_id) REFERENCES container(id)
);

CREATE TABLE sequence (
    id                  INTEGER   PRIMARY KEY,
    type                TEXT      NOT NULL    DEFAULT 'other',
    name                TEXT      NOT NULL,
    formula             TEXT      NOT NULL,
    mass                REAL,
    sequence            TEXT      NOT NULL,
    smiles              TEXT,
    database            INTEGER,
    identifier          TEXT,
    container_id        INTEGER,
    FOREIGN KEY (container_id) REFERENCES container(id)
);

CREATE TABLE modification (
    id                  INTEGER      PRIMARY KEY,
    name                TEXT         NOT NULL,
    formula             TEXT         NOT NULL,
    mass                REAL,
    nterminal           INTEGER      NOT NULL    DEFAULT 0,
    cterminal           INTEGER      NOT NULL    DEFAULT 0,
    container_id        INTEGER,
    nblock_id           INTEGER,
    cblock_id           INTEGER,
    bblock_id           INTEGER,
    FOREIGN KEY (container_id) REFERENCES container(id),
    FOREIGN KEY (nblock_id) REFERENCES block(id),
    FOREIGN KEY (cblock_id) REFERENCES block(id),
    FOREIGN KEY (bblock_id) REFERENCES block(id)
);

-- CREATE TABLE losses (
--     id        INTEGER   PRIMARY_KEY,
--     name      TEXT      NOT NULL,
--     block_id  INTEGER,
--     FOREIGN KEY (block_id) REFERENCES block(id)
--
-- );

CREATE TABLE b2s (
    block_id            INTEGER,
    sequence_id         INTEGER,
    FOREIGN KEY (block_id) REFERENCES block(id),
    FOREIGN KEY (sequence_id) REFERENCES sequence(id)
);

CREATE UNIQUE INDEX UX_USER_MAIL ON user(mail);
CREATE INDEX IX_BLOCK_ACRONYM ON block(acronym);
CREATE INDEX IX_BLOCK_NAME ON block(name);
CREATE INDEX IX_BLOCK_RESIDUE ON block(residue);
CREATE INDEX IX_BLOCK_USMILE ON block(usmiles);
CREATE INDEX IX_SEQUENCE_NAME ON sequence(name);
CREATE INDEX IX_MODIFICATION_NAME ON modification(name);

PRAGMA foreign_keys = ON;

COMMIT;
