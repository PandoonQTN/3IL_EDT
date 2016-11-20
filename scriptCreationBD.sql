------------ CLIENT -----------------------------------------------
-- Table: client

-- DROP TABLE client;

CREATE TABLE client
(
  cli_id integer NOT NULL,
  cli_a_saam boolean NOT NULL DEFAULT false,
  cli_login character varying(255),
  cli_pwd character varying(255),
  cli_actif boolean,
  cli_diff_active smallint,
  cli_nom character varying(300),
  CONSTRAINT pk_client PRIMARY KEY (cli_id)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE client
  OWNER TO postgres;

-- Index: client_pk

-- DROP INDEX client_pk;

CREATE UNIQUE INDEX client_pk
  ON client
  USING btree
  (cli_id);



------------ POSTE ------------------------------------------------

-- Table: poste

-- DROP TABLE poste;

CREATE TABLE poste
(
  poste_id serial NOT NULL,
  cli_id integer NOT NULL,
  runtime text NOT NULL,
  ram_totale text,
  processeur text,
  modele text,
  fabricant text,
  userg5 text,
  os text,
  versionrt text,
  cheminrt text,
  horodate timestamp without time zone,
  useros text,
  CONSTRAINT poste_pkey PRIMARY KEY (poste_id),
  CONSTRAINT poste_cli_id_fkey FOREIGN KEY (cli_id)
      REFERENCES client (cli_id) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE
)
WITH (
  OIDS=FALSE
);
ALTER TABLE poste
  OWNER TO postgres;

-- Index: fki_poste_cli_id_fkey

-- DROP INDEX fki_poste_cli_id_fkey;

CREATE INDEX fki_poste_cli_id_fkey
  ON poste
  USING btree
  (cli_id);

-- Index: i_poste_fabricant

-- DROP INDEX i_poste_fabricant;

CREATE INDEX i_poste_fabricant
  ON poste
  USING btree
  (fabricant);

-- Index: i_poste_modele

-- DROP INDEX i_poste_modele;

CREATE INDEX i_poste_modele
  ON poste
  USING btree
  (modele);

-- Index: i_poste_os

-- DROP INDEX i_poste_os;

CREATE INDEX i_poste_os
  ON poste
  USING btree
  (os);

-- Index: i_poste_processeur

-- DROP INDEX i_poste_processeur;

CREATE INDEX i_poste_processeur
  ON poste
  USING btree
  (processeur);

-- Index: i_poste_runtime

-- DROP INDEX i_poste_runtime;

CREATE INDEX i_poste_runtime
  ON poste
  USING btree
  (runtime);

-- Index: i_poste_versionrt

-- DROP INDEX i_poste_versionrt;

CREATE INDEX i_poste_versionrt
  ON poste
  USING btree
  (versionrt);


 
------------ HARDWARE ---------------------------------------------

-- Table: hardware_champs

-- DROP TABLE hardware_champs;

CREATE TABLE hardware_champs
(
  id serial NOT NULL,
  poste_id integer,
  categorie text,
  cle text,
  valeur text,
  horodate timestamp without time zone,
  CONSTRAINT hardware_champs_pkey PRIMARY KEY (id),
  CONSTRAINT hardware_champs_poste_id_fkey FOREIGN KEY (poste_id)
      REFERENCES poste (poste_id) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE
)
WITH (
  OIDS=FALSE
);
ALTER TABLE hardware_champs
  OWNER TO postgres;

-- Index: fki_hardware_champs_poste_id_fkey

-- DROP INDEX fki_hardware_champs_poste_id_fkey;

CREATE INDEX fki_hardware_champs_poste_id_fkey
  ON hardware_champs
  USING btree
  (poste_id);



------------ LOGICIEL ---------------------------------------------
-- Table: logiciel_champs

-- DROP TABLE logiciel_champs;

CREATE TABLE logiciel_champs
(
  id serial NOT NULL,
  poste_id integer,
  categorie text,
  cle text,
  valeur text,
  horodate timestamp without time zone,
  CONSTRAINT logiciel_champs_pkey PRIMARY KEY (id),
  CONSTRAINT logiciel_champs_poste_id_fkey FOREIGN KEY (poste_id)
      REFERENCES poste (poste_id) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE
)
WITH (
  OIDS=FALSE
);
ALTER TABLE logiciel_champs
  OWNER TO postgres;

-- Index: fki_logiciel_champs_poste_id_fkey

-- DROP INDEX fki_logiciel_champs_poste_id_fkey;

CREATE INDEX fki_logiciel_champs_poste_id_fkey
  ON logiciel_champs
  USING btree
  (poste_id);



------------ POSTGRESQL -------------------------------------------

-- Table: postgresql

-- DROP TABLE postgresql;

CREATE TABLE postgresql
(
  id serial NOT NULL,
  cli_id integer NOT NULL,
  port integer NOT NULL,
  adresse text NOT NULL,
  horodate timestamp without time zone,
  CONSTRAINT postgresql_pkey PRIMARY KEY (id),
  CONSTRAINT postgresql_cli_id_fkey FOREIGN KEY (cli_id)
      REFERENCES client (cli_id) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE
)
WITH (
  OIDS=FALSE
);
ALTER TABLE postgresql
  OWNER TO postgres;

-- Index: fki_postgresql_cli_id_fkey

-- DROP INDEX fki_postgresql_cli_id_fkey;

CREATE INDEX fki_postgresql_cli_id_fkey
  ON postgresql
  USING btree
  (cli_id);

-- Index: i_postgresql_adresse

-- DROP INDEX i_postgresql_adresse;

CREATE INDEX i_postgresql_adresse
  ON postgresql
  USING btree
  (adresse);

-- Index: i_postgresql_port

-- DROP INDEX i_postgresql_port;

CREATE INDEX i_postgresql_port
  ON postgresql
  USING btree
  (port);




------------ PG_CHAMPS --------------------------------------------

-- Table: pg_champs

-- DROP TABLE pg_champs;

CREATE TABLE pg_champs
(
  id serial NOT NULL,
  id_pg integer,
  categorie text,
  cle text,
  valeur text,
  horodate timestamp without time zone,
  CONSTRAINT pg_champs_pkey PRIMARY KEY (id),
  CONSTRAINT pg_champs_id_pg_fkey FOREIGN KEY (id_pg)
      REFERENCES postgresql (id) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE
)
WITH (
  OIDS=FALSE
);
ALTER TABLE pg_champs
  OWNER TO postgres;

-- Index: fki_pg_champs_id_pg_fkey

-- DROP INDEX fki_pg_champs_id_pg_fkey;

CREATE INDEX fki_pg_champs_id_pg_fkey
  ON pg_champs
  USING btree
  (id_pg);



------------ BDD --------------------------------------------------

-- Table: bdd

-- DROP TABLE bdd;

CREATE TABLE bdd
(
  id serial NOT NULL,
  id_pg integer NOT NULL,
  nom text,
  id_interne text NOT NULL,
  horodate timestamp without time zone,
  versioncl text,
  versionsaam text,
  volume text,
  table_space text,
  chemin text,
  CONSTRAINT bdd_pkey PRIMARY KEY (id),
  CONSTRAINT bdd_id_pg_fkey FOREIGN KEY (id_pg)
      REFERENCES postgresql (id) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE
)
WITH (
  OIDS=FALSE
);
ALTER TABLE bdd
  OWNER TO postgres;

-- Index: fki_bdd_id_pg_fkey

-- DROP INDEX fki_bdd_id_pg_fkey;

CREATE INDEX fki_bdd_id_pg_fkey
  ON bdd
  USING btree
  (id_pg);

-- Index: i_bdd_idinterne

-- DROP INDEX i_bdd_idinterne;

CREATE INDEX i_bdd_idinterne
  ON bdd
  USING btree
  (id_interne);

-- Index: i_bdd_nom

-- DROP INDEX i_bdd_nom;

CREATE INDEX i_bdd_nom
  ON bdd
  USING btree
  (nom);

-- Index: i_bdd_versioncl

-- DROP INDEX i_bdd_versioncl;

CREATE INDEX i_bdd_versioncl
  ON bdd
  USING btree
  (versioncl);

-- Index: i_bdd_versionsaam

-- DROP INDEX i_bdd_versionsaam;

CREATE INDEX i_bdd_versionsaam
  ON bdd
  USING btree
  (versionsaam);




------------ BDD_CHAMPS -------------------------------------------

-- Table: bdd_champs

-- DROP TABLE bdd_champs;

CREATE TABLE bdd_champs
(
  id serial NOT NULL,
  bdd_id integer,
  categorie text,
  cle text,
  valeur text,
  horodate timestamp without time zone,
  CONSTRAINT bdd_champs_pkey PRIMARY KEY (id),
  CONSTRAINT bdd_champs_bdd_id_fkey FOREIGN KEY (bdd_id)
      REFERENCES bdd (id) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE
)
WITH (
  OIDS=FALSE
);
ALTER TABLE bdd_champs
  OWNER TO postgres;

-- Index: fki_bdd_champs_bdd_id_fkey

-- DROP INDEX fki_bdd_champs_bdd_id_fkey;

CREATE INDEX fki_bdd_champs_bdd_id_fkey
  ON bdd_champs
  USING btree
  (bdd_id);



------------ SAAM -------------------------------------------------

-- Table: saam

-- DROP TABLE saam;

CREATE TABLE saam
(
  id serial NOT NULL,
  cli_id integer NOT NULL,
  nom text,
  os text,
  horodate timestamp without time zone NOT NULL,
  version text,
  dateinstal date,
  CONSTRAINT saam_pkey PRIMARY KEY (id),
  CONSTRAINT saam_cli_id_fkey FOREIGN KEY (cli_id)
      REFERENCES client (cli_id) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE
)
WITH (
  OIDS=FALSE
);
ALTER TABLE saam
  OWNER TO postgres;

-- Index: fki_saam_cli_id_fkey

-- DROP INDEX fki_saam_cli_id_fkey;

CREATE INDEX fki_saam_cli_id_fkey
  ON saam
  USING btree
  (cli_id);

-- Index: i_saam_dateinstal

-- DROP INDEX i_saam_dateinstal;

CREATE INDEX i_saam_dateinstal
  ON saam
  USING btree
  (dateinstal);

-- Index: i_saam_nom

-- DROP INDEX i_saam_nom;

CREATE INDEX i_saam_nom
  ON saam
  USING btree
  (nom);

-- Index: i_saam_os

-- DROP INDEX i_saam_os;

CREATE INDEX i_saam_os
  ON saam
  USING btree
  (os);

-- Index: i_saam_version

-- DROP INDEX i_saam_version;

CREATE INDEX i_saam_version
  ON saam
  USING btree
  (version);


------------ SAAM_CHAMPS ------------------------------------------

-- Table: saam_champs

-- DROP TABLE saam_champs;

CREATE TABLE saam_champs
(
  id serial NOT NULL,
  id_saam integer,
  categorie text,
  cle text,
  valeur text,
  horodate timestamp without time zone,
  CONSTRAINT saam_champs_pkey PRIMARY KEY (id),
  CONSTRAINT saam_champs_id_saam_fkey FOREIGN KEY (id_saam)
      REFERENCES saam (id) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE
)
WITH (
  OIDS=FALSE
);
ALTER TABLE saam_champs
  OWNER TO postgres;

-- Index: fki_saam_champs_id_saam_fkey

-- DROP INDEX fki_saam_champs_id_saam_fkey;

CREATE INDEX fki_saam_champs_id_saam_fkey
  ON saam_champs
  USING btree
  (id_saam);


