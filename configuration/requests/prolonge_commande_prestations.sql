-- prolongation des commandes de prestations des prestataires
--  la commande est definie avec le status "Demande"
--  condition :
--     il faut qu'il y ait encore une commande marquee "Cree" 
--     il ne faut pas qu'une commande "cree" soit encore en cours à la date donnée (FIN_OLD)
--
--
-- script version 1.1 du 2020-12-13
--  @FIN_OLD date de fin declenchant la creation d'une nouvelle commande
--  @FIN_NEW date de fin
--  @UO_NEW  nombre d'UO à positionner
--
--


-- reactiver Insert pour reellement faire l'insert
-- reactiver Update pour clore les prestations ayant depassee la date now()
-- reactiver COMMIT

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;


set @FIN_OLD="2021-01-01";
set @FIN_NEW="2021-03-31";
set @UO_NEW=55;



--  Insert into cegid_commande_prestataire ( ID,   USER_ID,     SOCIETE, TEAM, PROFIL, STATUS,  COMMANDE, DEBUT, FIN,  TARIF_ACHAT, TARIF_VENTE, UO, COUT, VISIBLE, COMMENTAIRE)
  SELECT concat("CP_",cp1.USER_ID,"_",cp2count), cp1.USER_ID, SOCIETE, TEAM, PROFIL, "Demande", COMMANDE, 
    addtime( FIN,'1 0:0:0'), @FIN_NEW, 
    TARIF_ACHAT, TARIF_VENTE, @UO_NEW, @UO_NEW*TARIF_ACHAT, VISIBLE, COMMENTAIRE
  FROM cegid_commande_prestataire cp1,
       ( SELECT count(*) as cp2count, USER_ID 
	     FROM cegid_commande_prestataire cp2
		 GROUP BY USER_ID
		) cp2
  WHERE 
        fin <  @FIN_OLD
    AND   STATUS = "Cree"
    AND   cp1.user_id NOT IN ( select USER_ID from cegid_commande_prestataire where fin > @FIN_OLD )
	AND   cp1.user_id = cp2.user_id;
	

-- UPDATE cegid_commande_prestataire  set STATUS = "Clos" WHERE STATUS = "Cree" And now() > FIN;


SET FOREIGN_KEY_CHECKS=1;
ROLLBACK;
-- COMMIT;

