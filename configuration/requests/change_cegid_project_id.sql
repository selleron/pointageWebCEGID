-- changer le CEGID d'un projet
-- bascule les pointages d'un projet à un autre
-- avant d'executer positionner :
-- @ID_OLD
-- @ID_NEW  -- doit exister
-- parfois, il faut reediter le nom du projet pour que les changements soit pris en compte


SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;


set @ID_OLD="P18003";
set @ID_NEW="P18015";

-- DELETE FROM cegid_project WHERE CEGID=@ID_NEW;
UPDATE cegid_project                 	   SET CEGID=@ID_NEW WHERE CEGID=@ID_OLD;

UPDATE cegid_devis_project                 SET CEGID=@ID_NEW WHERE CEGID=@ID_OLD;
UPDATE cegid_pointage                      SET PROJECT_ID=@ID_NEW WHERE PROJECT_ID=@ID_OLD;
UPDATE cegid_pointage_previsionnel         SET PROJECT_ID=@ID_NEW WHERE PROJECT_ID=@ID_OLD;
UPDATE cegid_pointage_previsionnel_history SET PROJECT_ID=@ID_NEW WHERE PROJECT_ID=@ID_OLD;
UPDATE cegid_pointage_voulu                SET PROJECT_ID=@ID_NEW WHERE PROJECT_ID=@ID_OLD;
UPDATE cegid_project_cout                  SET PROJECT_ID=@ID_NEW WHERE PROJECT_ID=@ID_OLD;

DELETE FROM cegid_project WHERE CEGID=@ID_OLD;



SET FOREIGN_KEY_CHECKS=1;
-- ROLLBACK;
COMMIT;