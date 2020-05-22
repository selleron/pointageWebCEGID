-- Update Status Devis
-- 

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;



UPDATE  cegid_devis_project set status_commande = "Recu" where commande != "" and status_commande = "Neant";

UPDATE  cegid_devis_project set status_devis = "Accepte" where status_devis = "Envoye" and status_commande = "Recu" ;
UPDATE  cegid_devis_project set status_devis = "Accepte" where status_devis = "Envoye" and status_commande = "A/R Signe"; 
UPDATE  cegid_devis_project set status_cegid = "Cree" where status_cegid = "Demande" and status_commande = "Recu";
UPDATE  cegid_devis_project set status_cegid = "Cree" where status_cegid = "Demande" and status_commande = "A/R Signe";

#UPDATE  cegid_devis_project set status_commande = "A/R Signe" where status_commande = "Recu";

UPDATE  cegid_devis_project set status_cegid = "Cree" where status_cegid = "Neant" and status_commande = "A/R Signe" and cegid != "";

UPDATE cegid_project pj set pj.status="En cours" WHERE pj.CEGID in (  SELECT DISTINCT p.PROJECT_ID FROM cegid_pointage p WHERE pj.status = "Prevision" and p.PROJECT_ID = pj.cegid)



SET FOREIGN_KEY_CHECKS=1;
ROLLBACK;
-- COMMIT;
