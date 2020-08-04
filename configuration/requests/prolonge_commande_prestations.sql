-- prolongation des prestations
--  @FIN_NEW date de fin
-- UO_NEW nombre d'UO

-- reactiver Inert pour reellement faire l'insert
-- reactiver COMMIT

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;


set @FIN_NEW="2020-12-31";
set @UO_NEW=60;



-- Insert into cegid_commande_prestataire ( ID, USER_ID, SOCIETE, GROUPE, STATUS, COMMANDE, DEBUT, FIN,  TARIF_ACHAT, TARIF_VENTE, UO, COUT, VISIBLE, COMMENTAIRE)
  SELECT concat("CP_",cp1.USER_ID,"_",cp2count), cp1.USER_ID, SOCIETE, GROUPE, "Demande", COMMANDE, 
    addtime( FIN,'1 0:0:0'), @FIN_NEW, 
    TARIF_ACHAT, TARIF_VENTE, @UO_NEW, @UO_NEW*TARIF_ACHAT, VISIBLE, COMMENTAIRE
  FROM cegid_commande_prestataire cp1,
       ( SELECT count(*) as cp2count, USER_ID 
	     FROM cegid_commande_prestataire cp2
		 GROUP BY USER_ID
		) cp2
  WHERE 
        fin < now()
    AND   STATUS = "Cree"
    AND   cp1.user_id NOT IN ( select USER_ID from cegid_commande_prestataire where fin > now())
	AND   cp1.user_id = cp2.user_id
	

-- UPDATE cegid_commande_prestataire  set STATUS = "Clos" WHERE STATUS = "Cree" And now() > FIN;


SET FOREIGN_KEY_CHECKS=1;
ROLLBACK;
-- COMMIT;

