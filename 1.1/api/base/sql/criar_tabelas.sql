CREATE TABLE IF NOT EXISTS escolas (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  nome        VARCHAR(200) NOT NULL,
  provincia   VARCHAR(80)  NOT NULL,
  endereco    VARCHAR(255) DEFAULT '',
  telefone    VARCHAR(60)  DEFAULT '',
  email       VARCHAR(160) DEFAULT '',
  website     VARCHAR(200) DEFAULT ''
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS cursos (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  escola_id     INT NOT NULL,
  nome          VARCHAR(200) NOT NULL,
  area          VARCHAR(120) NOT NULL,
  nivel         VARCHAR(60)  NOT NULL, -- Técnico | Superior | Profissionalizante
  requisitos    TEXT,
  vagas         INT DEFAULT 0,
  CONSTRAINT fk_cursos_escolas
    FOREIGN KEY (escola_id) REFERENCES escolas(id)
    ON DELETE CASCADE
) ENGINE=InnoDB;
