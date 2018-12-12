-- changer le CEGID d'un projet
-- bascule les pointages d'un projet a un autre
-- avant d'executer positionner :
-- @ID_OLD  -- sera positionné sur Annule
-- @ID_NEW  -- sera créé
-- parfois, il faut reediter le nom du projet pour que les changements soit pris en compte


SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;


set @ID_OLD="P18xxx";
set @ID_NEW="P18yyy";
-- set @ID_OLD="P18028";
-- set @ID_NEW="P18040";

-- creation nouveau projet par copie
INSERT INTO `cegid_project` (`CEGID`, `NAME`, `PRIX_VENTE`, `DEBUT`, `FIN`, `FIN_GARANTIE`, `STATUS`, `TYPE`, `GROUPE`, `COMMENTAIRE`, `VISIBLE`) 
SELECT @ID_NEW, concat(`NAME`,"_"), `PRIX_VENTE`, `DEBUT`, `FIN`, `FIN_GARANTIE`, `STATUS`, `TYPE`, `GROUPE`, `COMMENTAIRE`, `VISIBLE` FROM `cegid_project`
WHERE CEGID=@ID_OLD;

-- deplacement des références
UPDATE cegid_devis_project                 SET CEGID=@ID_NEW WHERE CEGID=@ID_OLD;
UPDATE cegid_pointage                      SET PROJECT_ID=@ID_NEW WHERE PROJECT_ID=@ID_OLD;
UPDATE cegid_pointage_previsionnel         SET PROJECT_ID=@ID_NEW WHERE PROJECT_ID=@ID_OLD;
UPDATE cegid_pointage_previsionnel_history SET PROJECT_ID=@ID_NEW WHERE PROJECT_ID=@ID_OLD;
UPDATE cegid_pointage_voulu                SET PROJECT_ID=@ID_NEW WHERE PROJECT_ID=@ID_OLD;
UPDATE cegid_project_cout                  SET PROJECT_ID=@ID_NEW WHERE PROJECT_ID=@ID_OLD;
UPDATE cegid_project_cout_history          SET PROJECT_ID=@ID_NEW WHERE PROJECT_ID=@ID_OLD;
UPDATE cegid_file                          SET REFERENCE=@ID_NEW  WHERE REFERENCE=@ID_OLD;
UPDATE cegid_frais_mission                 SET PROJECT_ID=@ID_NEW WHERE PROJECT_ID=@ID_OLD;

-- desactivation de l'ancien projet
UPDATE `cegid_project` SET `STATUS` = 'Annule' WHERE `cegid_project`.`CEGID` = @ID_OLD;

-- suppression de l'ancien projet
-- dangereux à cause des delete cascade
-- DELETE FROM cegid_project WHERE CEGID=@ID_OLD;


SET FOREIGN_KEY_CHECKS=1;
ROLLBACK;
-- COMMIT;

