PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
-- CREATE TABLE block (
--     id                  INTEGER         PRIMARY KEY,
--     name                TEXT            NOT NULL        CHECK(length(name) > 0),
--     acronym             TEXT            NOT NULL        CHECK(length(acronym) > 0),
--     residue             TEXT            NOT NULL        CHECK(length(residue) > 0),
--     mass                REAL,
--     losses              TEXT,
--     smiles              TEXT,
--     usmiles             TEXT,
--     database            INTEGER,
--     identifier          TEXT
-- );
INSERT INTO block VALUES(1,'Phenylalanine','Phe','C9H9NO',147.06841399999998998,'','NC(Cc1ccccc1)C(=O)O','NC(CC1=CC=CC=C1)C(O)=O',0,'6140');
INSERT INTO block VALUES(2,'Alanine','Ala','C3H5NO',71.037114000000002532,'','CC(N)C(=O)O','CC(N)C(O)=O',0,'5950');
INSERT INTO block VALUES(3,'Leucine','Leu','C6H11NO',113.08406399999999792,'','CC(C)CC(N)C(=O)O','CC(C)CC(N)C(O)=O',0,'6106');
INSERT INTO block VALUES(4,'Isoleucine','Ile','C6H11NO',113.08406399999999792,'','CCC(C)C(N)C(=O)O','CCC(C)C(N)C(O)=O',0,'6306');
INSERT INTO block VALUES(5,'Proline','Pro','C5H7NO',97.052763999999996256,'','O=C(O)C1CCCN1','OC(=O)C1CCCN1',0,'145742');
INSERT INTO block VALUES(6,'Valine','Val','C5H9NO',99.068414000000004195,'','CC(C)C(N)C(=O)O','CC(C)C(N)C(O)=O',0,'6287');
INSERT INTO block VALUES(7,'Arginine','Arg','C6H12N4O',156.10111100000000306,'NH3;CH2N2','NC(N)=NCCCC(N)C(=O)O','NC(CCCN=C(N)N)C(O)=O',0,'6322');
INSERT INTO block VALUES(8,'Asparagine','Asn','C4H6N2O2',114.04292700000000593,'NH3;CONH','NC(=O)CC(N)C(=O)O','NC(CC(N)=O)C(O)=O',0,'6267');
INSERT INTO block VALUES(9,'Aspartic acid','Asp','C4H5NO3',115.02694300000000282,'H2O;CO2','NC(CC(=O)O)C(=O)O','NC(CC(O)=O)C(O)=O',0,'5960');
INSERT INTO block VALUES(10,'Cysteine','Cys','C3H5NOS',103.00918400000000473,'H2S','NC(CS)C(=O)O','NC(CS)C(O)=O',0,'5862');
INSERT INTO block VALUES(11,'Glutamine','Gln','C5H8N2O2',128.05857800000001134,'NH3;CONH','NC(=O)CCC(N)C(=O)O','NC(CCC(N)=O)C(O)=O',0,'5961');
INSERT INTO block VALUES(12,'Glutamic acid','Glu','C5H7NO3',129.04259300000001076,'H2O;CO2','NC(CCC(=O)O)C(=O)O','NC(CCC(O)=O)C(O)=O',0,'33032');
INSERT INTO block VALUES(13,'Glycine','Gly','C2H3NO',57.021464000000001703,'','NCC(=O)O','NCC(O)=O',0,'750');
INSERT INTO block VALUES(14,'Histidine','His','C6H7N3O',137.0589119999999923,'','NC(Cc1cnc[nH]1)C(=O)O','NC(CC1CNC[NH1]1)C(O)=O',0,'6274');
INSERT INTO block VALUES(15,'Lysine','Lys','C6H12N2O',128.09496300000000701,'NH3','NCCCCC(N)C(=O)O','NCCCCC(N)C(O)=O',0,'5962');
INSERT INTO block VALUES(16,'Methionine','Met','C5H9NOS',131.04048499999998967,'','CSCCC(N)C(=O)O','CSCCC(N)C(O)=O',0,'6137');
INSERT INTO block VALUES(17,'Serine','Ser','C3H5NO2',87.032027999999996836,'H2O;CH2O','NC(CO)C(=O)O','NC(CO)C(O)=O',0,'5951');
INSERT INTO block VALUES(18,'Threonine','Thr','C4H7NO2',101.04767800000000476,'H2O;CH2CH2O','CC(O)C(N)C(=O)O','CC(O)C(N)C(O)=O',0,'6288');
INSERT INTO block VALUES(19,'Tryptophan','Trp','C11H10N2O',186.07931300000001328,'','NC(Cc1c[nH]c2ccccc12)C(=O)O','NC(CC1C[NH1]C2=CC=CC=C12)C(O)=O',0,'6305');
INSERT INTO block VALUES(20,'Tyrosine','Tyr','C9H9NO2',163.06332900000001018,'H2O','NC(Cc1ccc(O)cc1)C(=O)O','NC(CC1=CC=C(O)C=C1)C(O)=O',0,'6057');
INSERT INTO block VALUES(21,'10.12-dimethyl-tetradecanoic acid','C14:0-(10.12)Me','C16H30O',238.22966558510000822,'','CCC(C)CC(C)CCCCCCCCC(=O)O','CCC(C)CC(C)CCCCCCCCC(O)=O',0,'12050959');
INSERT INTO block VALUES(22,'4-hydroxy-5-ethylenediamine-ornithine','4OH-5(Eth-(1.2)NH2)-Orn','C7H16N4O2',188.12732577860001016,'C2N2H8;H2O','NCCNC(N)C(O)CC(N)C(=O)O','NCCNC(N)C(O)CC(N)C(O)=O',0,'87551590');
INSERT INTO block VALUES(23,'3-hydroxy-proline','3OH-Pro','C5H7NO2',113.04767847409999603,'H2O','O=C(O)C1NCCC1O','OC1CCNC1C(O)=O',0,'559314');
INSERT INTO block VALUES(24,'3-hydroxy-ornithine','3OH-Orn','C5H10N2O2',130.07422757559999127,'H2O;NH3','NCCC(O)C(N)C(=O)O','NCCC(O)C(N)C(O)=O',0,'3017541');
INSERT INTO block VALUES(25,'3.4-dihydroxy-homotyrosine','(3.4)OH-hTyr','C10H11NO4',209.06880784669999685,'H2O;H2O;H2O','NC(C(=O)O)C(O)C(O)c1ccc(O)cc1','NC(C(O)C(O)C1=CC=C(O)C=C1)C(O)=O',0,'191594');
INSERT INTO block VALUES(26,'4-hydroxy-proline','4OH-Pro','C5H7NO2',113.04767847409999603,'H2O','O=C(O)C1CC(O)CN1','OC1CNC(C1)C(O)=O',0,'825');
INSERT INTO block VALUES(27,'4-(5-(4-pentoxyphenyl)-1.2-oxazol-3-yl)benzoic acid','MiBr','C21H19NO3',333.13649299999997311,'','CCCCCOc3ccc(c2cc(c1ccc(C(=O)O)cc1)no2)cc3','CCCCCOC1=CC=C(C=C1)C2=CC(N=O2)C3=CC=C(C=C3)C(O)=O',0,'9975224');
INSERT INTO block VALUES(28,'4.5-hydroxy-ornithine','(4.5)OH-Orn','C5H10N2O3',146.06914219769998908,'H2O;H2O','NC(O)C(O)CC(N)C(=O)O','NC(O)C(O)CC(N)C(O)=O',0,'22996997');
INSERT INTO block VALUES(29,'3-hydroxy-4-methylproline','3OH-4Me-Pro','C6H9NO2',127.06332853829999862,'H2O','CC1CNC(C(=O)O)C1O','CC1CNC(C1O)C(O)=O',0,'567843');
INSERT INTO block VALUES(30,'3-hydroxy-glutamine','3OH-Gln','C5H8N2O3',144.05349213349998649,'H2O;NH3;CONH','NC(=O)CC(O)C(N)C(=O)O','NC(C(O)CC(N)=O)C(O)=O',0,'22592766');
INSERT INTO block VALUES(31,'3.4-dihydroxy-7-sulfoxy-homotyrosine','(3.4)OH-(7)SO4-hTyr','C10H11NO8S',305.02053702509999766,'SO3;H2O;H2O;H2O;H2O','NC(C(=O)O)C(O)C(O)c1ccc(O)c(OS(=O)(=O)O)c1','NC(C(O)C(O)C1=CC=C(O)C(=C1)OS(O)(=O)=O)C(O)=O',0,'');
INSERT INTO block VALUES(32,'4-(4-(4-pentoxyphenyl)phenyl)benzoic acid','AniBr','C24H22O2',342.16198000000002821,'','CCCCCOc3ccc(c2ccc(c1ccc(C(=O)O)cc1)cc2)cc3','CCCCCOC1=CC=C(C=C1)C2=CC=C(C=C2)C3=CC=C(C=C3)C(O)=O',0,'9798987');
-- CREATE TABLE sequence (
--     id                  INTEGER   PRIMARY KEY,
--     type                TEXT      NOT NULL      DEFAULT 'other',
--     name                TEXT      NOT NULL      CHECK(length(name) > 0),
--     formula             TEXT      NOT NULL      CHECK(length(formula) > 0),
--     mass                REAL,
--     sequence            TEXT      NOT NULL      CHECK(length(sequence) > 0),
--     smiles              TEXT,
--     database            INTEGER,
--     identifier          TEXT,
--     n_modification_id   INTEGER,
--     c_modification_id   INTEGER,
--     b_modification_id   INTEGER,
--     FOREIGN KEY (n_modification_id) REFERENCES modification(id),
--     FOREIGN KEY (c_modification_id) REFERENCES modification(id),
--     FOREIGN KEY (b_modification_id) REFERENCES modification(id)
-- );
INSERT INTO sequence VALUES(1,'3','Caspofungin','C52H88N10O15',1092.6430000000000291,'\([4OH-5(Eth-(1.2)NH2)-Orn]-[C14:0-(10.12)Me]\)[3OH-Pro]-[3OH-Orn]-[(3.4)OH-hTyr]-[4OH-Pro]-[Thr]','CCC(C)CC(C)CCCCCCCCC(=O)NC1CC(C(NC(=O)C2C(CCN2C(=O)C(NC(=O)C(NC(=O)C3CC(CN3C(=O)C(NC1=O)C(C)O)O)C(C(C4=CC=C(C=C4)O)O)O)C(CCN)O)O)NCCN)O',0,'16119814',NULL,NULL,NULL,NULL);
INSERT INTO sequence VALUES(2,'3','Micafungin','C56H71N9O23S',1269.4380000000001018,'\([(4.5)OH-Orn]-[MiBr]\)[3OH-4Me-Pro]-[3OH-Gln]-[(3.4)OH-(7)SO4-hTyr]-[4OH-Pro]-[Thr]','CCCCCOc7ccc(c6cc(c5ccc(C(=O)NC4CC(O)C(O)NC(=O)C1C(O)C(C)CN1C(=O)C(C(O)CC(N)=O)NC(=O)C(C(O)C(O)c2ccc(O)c(OS(=O)(=O)O)c2)NC(=O)C3CC(O)CN3C(=O)C(C(C)O)NC4=O)cc5)no6)cc7',0,'477468',NULL,NULL,NULL,NULL);
INSERT INTO sequence VALUES(3,'3','Anidulafungin','C58H73N7O17',1139.5060000000000854,'\([(4.5)OH-Orn]-[AniBr]\)[3OH-4Me-Pro]-[Thr]-[(3.4)OH-hTyr]-[4OH-Pro]-[Thr]','CCCCCOC1=CC=C(C=C1)C2=CC=C(C=C2)C3=CC=C(C=C3)C(=O)NC4CC(C(NC(=O)C5C(C(CN5C(=O)C(NC(=O)C(NC(=O)C6CC(CN6C(=O)C(NC4=O)C(C)O)O)C(C(C7=CC=C(C=C7)O)O)O)C(C)O)C)O)O)O',0,'166548',NULL,NULL,NULL,NULL);
-- CREATE TABLE modification (
--     id                  INTEGER      PRIMARY KEY,
--     name                TEXT         NOT NULL       CHECK(length(name) > 0),
--     formula             TEXT         NOT NULL       CHECK(length(formula) > 0),
--     mass                REAL,
--     nterminal           INTEGER      NOT NULL       DEFAULT 0,
--     cterminal           INTEGER      NOT NULL       DEFAULT 0
-- );
INSERT INTO modification VALUES(1,'Acetyl','C2H2O',42.010564686300000403,1,0);
INSERT INTO modification VALUES(2,'Amidated','HNO-1',-0.98401558479999995387,0,1);
INSERT INTO modification VALUES(3,'Ethanolamine','C2H5N',43.042199165699997821,0,1);
INSERT INTO modification VALUES(4,'Formyl','CO',27.994914622100001365,1,0);
-- CREATE TABLE b2s (
--     block_id            INTEGER,
--     sequence_id         INTEGER,
--     PRIMARY KEY (block_id, sequence_id),
--     FOREIGN KEY (block_id) REFERENCES block(id),
--     FOREIGN KEY (sequence_id) REFERENCES sequence(id)
-- );
INSERT INTO b2s VALUES(21,1);
INSERT INTO b2s VALUES(22,1);
INSERT INTO b2s VALUES(23,1);
INSERT INTO b2s VALUES(24,1);
INSERT INTO b2s VALUES(25,1);
INSERT INTO b2s VALUES(26,1);
INSERT INTO b2s VALUES(18,1);
INSERT INTO b2s VALUES(27,2);
INSERT INTO b2s VALUES(28,2);
INSERT INTO b2s VALUES(29,2);
INSERT INTO b2s VALUES(30,2);
INSERT INTO b2s VALUES(31,2);
INSERT INTO b2s VALUES(26,2);
INSERT INTO b2s VALUES(18,2);
INSERT INTO b2s VALUES(32,3);
INSERT INTO b2s VALUES(28,3);
INSERT INTO b2s VALUES(29,3);
INSERT INTO b2s VALUES(18,3);
INSERT INTO b2s VALUES(25,3);
INSERT INTO b2s VALUES(26,3);
-- CREATE UNIQUE INDEX UX_BLOCK_ACRONYM ON block(acronym);
-- CREATE INDEX IX_BLOCK_NAME ON block(name);
-- CREATE INDEX IX_BLOCK_RESIDUE ON block(residue);
-- CREATE INDEX IX_BLOCK_USMILE ON block(usmiles);
-- CREATE UNIQUE INDEX UX_SEQUENCE_NAME ON sequence(name);
-- CREATE UNIQUE INDEX UX_MODIFICATION_NAME ON modification(name);
COMMIT;
