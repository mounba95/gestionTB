INSERT INTO gestionTB.`user` (email,roles,password,is_active,second_password,nom,prenom,adresse,telephone,nom_utilisateur) VALUES 
(NULL,'a:1:{i:0;s:10:"ROLE_ADMIN";}','$argon2id$v=19$m=65536,t=4,p=1$xOtjxd+ad5wrVChHhGSWfQ$pNIFpfaJfZCF+jmQkpbGDdrCZwlrewDpAvR50INAsyc',1,NULL,'Mamane Arzika','Mouhamadou Rabiou','Boukoki 4','90407146','admin')
,(NULL,'a:2:{i:0;s:10:"ROLE_ADMIN";i:1;s:9:"ROLE_USER";}','$argon2id$v=19$m=65536,t=4,p=1$I/Pa6fbOOWz/1FKKAc037g$U2MWnhEHNLtEYpX0/AAI1eNIPdgX0gUBRfKOumCO8sU',1,NULL,'Arzika','Rabiou','1234','89898989','rmag')
,(NULL,'a:1:{i:0;s:9:"ROLE_USER";}','$argon2id$v=19$m=65536,t=4,p=1$xC15lYrgX5YvPQmtpgZLgQ$XL7Uj7h5/qwpgRZ/F3WrUzvnH1wiibp5P8myODPD4qw',1,NULL,'Amadou','Souleymane','Talladjé','90569090','amadousouleymane')
;