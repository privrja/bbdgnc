BEGIN TRANSACTION;

CREATE TABLE block (
    id                  INTEGER         PRIMARY KEY,
    name                TEXT            NOT NULL        CHECK(length(name) > 0),
    acronym             TEXT            NOT NULL        CHECK(length(acronym) > 0),
    residue             TEXT            NOT NULL        CHECK(length(residue) > 0),
    mass                REAL,
    losses              TEXT,
    smiles              TEXT,
    usmiles             TEXT,
    database            INTEGER,
    identifier          TEXT
);

CREATE TABLE sequence (
    id                  INTEGER   PRIMARY KEY,
    type                TEXT      NOT NULL      DEFAULT 'other',
    name                TEXT      NOT NULL      CHECK(length(name) > 0),
    formula             TEXT      NOT NULL      CHECK(length(formula) > 0),
    mass                REAL,
    sequence            TEXT,
    smiles              TEXT,
    database            INTEGER,
    identifier          TEXT,
    decays              TEXT,
    n_modification_id   INTEGER,
    c_modification_id   INTEGER,
    b_modification_id   INTEGER,
    FOREIGN KEY (n_modification_id) REFERENCES modification(id),
    FOREIGN KEY (c_modification_id) REFERENCES modification(id),
    FOREIGN KEY (b_modification_id) REFERENCES modification(id)
);

CREATE TABLE modification (
    id                  INTEGER      PRIMARY KEY,
    name                TEXT         NOT NULL       CHECK(length(name) > 0),
    formula             TEXT         NOT NULL       CHECK(length(formula) > 0),
    mass                REAL,
    nterminal           INTEGER      NOT NULL       DEFAULT 0,
    cterminal           INTEGER      NOT NULL       DEFAULT 0
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
    PRIMARY KEY (block_id, sequence_id),
    FOREIGN KEY (block_id) REFERENCES block(id),
    FOREIGN KEY (sequence_id) REFERENCES sequence(id)
);

CREATE UNIQUE INDEX UX_BLOCK_ACRONYM ON block(acronym);
CREATE INDEX IX_BLOCK_NAME ON block(name);
CREATE INDEX IX_BLOCK_RESIDUE ON block(residue);
CREATE INDEX IX_BLOCK_USMILE ON block(usmiles);
CREATE UNIQUE INDEX UX_SEQUENCE_NAME ON sequence(name);
CREATE UNIQUE INDEX UX_MODIFICATION_NAME ON modification(name);

PRAGMA foreign_keys = ON;

COMMIT;
