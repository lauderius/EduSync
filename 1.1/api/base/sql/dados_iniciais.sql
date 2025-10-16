INSERT INTO escolas (nome, provincia, endereco, telefone, email, website) VALUES
('Instituto Politécnico do Namibe','Namibe','Rua 1','222-000-001','ipn@example.ao','http://ipn.ao'),
('Instituto Médio de Saúde de Benguela','Benguela','Av. Saúde','222-000-002','imsb@example.ao','http://imsb.ao'),
('Universidade Katyavala Bwila','Benguela','Campus Central','222-000-003','ukb@example.ao','http://ukb.ao'),
('Instituto Superior Politécnico da Huíla','Huíla','Lubango','222-000-004','isph@example.ao','http://isph.ao');

INSERT INTO cursos (escola_id, nome, area, nivel, requisitos, vagas) VALUES
(2,'Enfermagem Geral','Saúde','Técnico','9ª classe + BI',60),
(3,'Enfermagem','Saúde','Superior','Ensino Médio + Exame',120),
(3,'Engenharia Informática','Tecnologia','Superior','EM Ciências e Tec + Exame',100),
(4,'Fisioterapia','Saúde','Superior','Ensino Médio + Exame',80),
(1,'Eletricidade Industrial','Engenharias','Técnico','9ª classe + BI',70);
