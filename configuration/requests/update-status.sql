-- Update Status Devis
-- 
-- script version 1.1 du 2020-06-23

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;


# cegid devis

UPDATE  cegid_devis_project set status_commande = "Recu" where commande != "" and status_commande = "Neant";
UPDATE  cegid_devis_project set status_devis = "Accepte" where status_devis = "Envoye" and status_commande = "Recu" ;
UPDATE  cegid_devis_project set status_devis = "Accepte" where status_devis = "Envoye" and status_commande = "A/R Signe"; 
UPDATE  cegid_devis_project set status_cegid = "Cree" where status_cegid = "Demande" and status_commande = "Recu";
UPDATE  cegid_devis_project set status_cegid = "Cree" where status_cegid = "Demande" and status_commande = "A/R Signe";
#UPDATE  cegid_devis_project set status_commande = "A/R Signe" where status_commande = "Recu";
UPDATE  cegid_devis_project set status_cegid = "Cree" where status_cegid = "Neant" and status_commande = "A/R Signe" and cegid != "";

#cegid project

UPDATE cegid_commande_prestataire  set STATUS = "Cree" WHERE STATUS = "Demande" And year(now()) >= year(FIN) AND month(now()) >= month(FIN);
UPDATE cegid_commande_prestataire  set STATUS = "Clos" WHERE STATUS = "Cree" And now() > FIN;


# commande

UPDATE  cegid_devis_project set status_cegid = "Cree" where status_cegid = "Demande" and status_commande = "A/R Signe";


# cegid_file

Update cegid_file cf set CODE = "CMD" 
where 
  cf.ID in  (select cf.id from files f 
                    where f.id = cf.FILE
                       and cf.CODE="UNDEF"
					   and locate("CMD",f.title)=1
                  );

Update cegid_file cf set CODE = "CMD" 
where 
  cf.ID in  (select cf.id from files f 
                    where f.id = cf.FILE
                       and cf.CODE="UNDEF"
					   and locate("PO",f.title)=1
                  );

Update cegid_file cf set CODE = "AR_CMD" 
where 
  cf.ID in  (select cf.id from files f 
                    where f.id = cf.FILE
                       and cf.CODE="UNDEF"
					   and locate("ARCMD",f.title)=1
                  );

Update cegid_file cf set CODE = "AR_CMD" 
where 
  cf.ID in  (select cf.id from files f 
                    where f.id = cf.FILE
                       and cf.CODE="UNDEF"
					   and locate("AR_CMD",f.title)=1
                  );

Update cegid_file cf set CODE = "AR_CMD" 
where 
  cf.ID in  (select cf.id from files f 
                    where f.id = cf.FILE
                       and cf.CODE="UNDEF"
					   and locate("AR",f.title)=1
                  );


Update cegid_file cf set CODE = "CDC" 
where 
  cf.ID in  (select cf.id from files f 
                    where f.id = cf.FILE
                       and cf.CODE="UNDEF"
					   and locate("CDC",f.title)=1
                  );


Update cegid_file cf set CODE = "PTF" 
where 
  cf.ID in  (select cf.id from files f 
                    where f.id = cf.FILE
                       and cf.CODE="UNDEF"
					   and locate("PROP",f.title)=1
                  );

Update cegid_file cf set CODE = "PTF" 
where 
  cf.ID in  (select cf.id from files f 
                    where f.id = cf.FILE
                       and cf.CODE="UNDEF"
					   and locate("PTF",f.title)=1
                  );

Update cegid_file cf set CODE = "PTF" 
where 
  cf.ID in  (select cf.id from files f 
                    where f.id = cf.FILE
                       and cf.CODE="UNDEF"
					   and locate("Devis",f.title)=1
					   and RIGHT(f.title,4)= ".pdf"
                  );


Update cegid_file cf set CODE = "PT" 
where 
  cf.ID in  (select cf.id from files f 
                    where f.id = cf.FILE
                       and cf.CODE="UNDEF"
					   and locate("PT",f.title)=1
                  );

Update cegid_file cf set CODE = "PF" 
where 
  cf.ID in  (select cf.id from files f 
                    where f.id = cf.FILE
                       and cf.CODE="UNDEF"
					   and locate("PF",f.title)=1
                  );


Update cegid_file cf set CODE = "PF" 
where 
  cf.ID in  (select cf.id from files f 
                    where f.id = cf.FILE
                       and cf.CODE="UNDEF"
					   and locate("PC",f.title)=1
                  );



Update cegid_file cf set CODE = "CHIFF" 
where 
  cf.ID in  (select cf.id from files f 
                    where f.id = cf.FILE
                       and cf.CODE="UNDEF"
					   and locate("Chiffrage",f.title)>0
					   and RIGHT(f.title,5)= ".xlsx"
                  );

Update cegid_file cf set CODE = "EMAIL" 
where 
  cf.ID in  (select cf.id from files f 
                    where f.id = cf.FILE
                       and cf.CODE="UNDEF"
					   and RIGHT(f.title,4)= ".msg"
                  );



# update trace version
UPDATE `version` SET DATE = now(), description = 'update witch update-status.sql script', value="1.1" WHERE id="data";

INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES
('update data 2', 300, now(), 'update witch update-status.sql script', '1.1');




SET FOREIGN_KEY_CHECKS=1;
ROLLBACK;
-- COMMIT;
