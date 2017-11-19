<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr" xml:lang="fr">

<head>
  <title>Serveur Web MobilHome200.</title>
  <?PHP 
	include_once("../sql/connection_db.php");
	include_once("../sql/member_db.php");
	testMemberGroup(3);
	require_once("../header.php");
	?> 
  </head>

<body>
<div id="header">
  <h1>Serveur Web MobilHome200 : Configuration</h1>
</div>


<div id="contenu">
	<?PHP 
	showBandeauHeaderPage("Configuration");
	?>


<div class="article">
<?PHP
echo "<h4> Configuration de la base de donn&eacute;es </h4>";
echo "<table>";
beginTableHeader();
echo "<td>Nom</td><td>Define</td><td>Value</td><td>Description</td>";
endTableHeader();
echo "<tr><td>Data Base Host  		</td>	<td>SQL_HOST		</td>		<td>".$SQL_HOST."		</td>	   	</tr>
	  <tr><td>Data Base Name  		</td>	<td>SQL_DATABASE	</td>		<td>".$SQL_DATABASE."	</td>		</tr>
  	  <tr><td>Data Base User  		</td>	<td>SQL_USER		</td>		<td>".$SQL_USER."		</td>		</tr>
  	  <tr><td>Crypted password		</td>	<td>CRYPT_PWD		</td>		<td>".$CRYPT_PWD."		</td>		<td>les mots de passe user sont il crypt&eacute;s</td></tr>
   </table>";




//php.ini
$MAX_POST_SIZE    = ini_get('post_max_size');
$file_uploads     = ini_get('file_uploads');
$max_file_uploads = ini_get('max_file_uploads');
$memory_limit        = ini_get('memory_limit');
$upload_max_filesize = ini_get('upload_max_filesize');
$max_input_vars      = ini_get('max_input_vars');
$max_execution_time  = ini_get('max_execution_time');
$max_input_time      = ini_get('max_input_time');

echo "<h4> Configuration systeme (PHP.ini)</h4>";
echo "<table>";
beginTableHeader();
echo "<td>Define</td><td>Value</td><td>Description</td>";
endTableHeader();
echo "
	  <tr><td>file_uploads  </td>           <td>$file_uploads        </td>	  	<td> file uploads on/off  1/0 </td>		</tr>
	  <tr><td>post_max_size  </td>          <td>$MAX_POST_SIZE       </td>   	<td> POST max size	        </td>		</tr>
	  <tr><td>max_file_uploads  </td>       <td>$max_file_uploads    </td>	  	<td> max file uploads	    </td>		</tr>
 
	  <tr><td>memory_limit         </td>       <td>$memory_limit           </td>	  	<td> memory limit	    </td>		</tr>
	  <tr><td>upload_max_filesize  </td>       <td>$upload_max_filesize    </td>	  	<td> upload max file size	    </td>		</tr>
	  <tr><td>max_input_vars       </td>       <td>$max_input_vars         </td>	  	<td> max input var	    </td>		</tr>
	  <tr><td>max_execution_time   </td>       <td>$max_execution_time     </td>	  	<td> max execution time	    </td>		</tr>
	  <tr><td>max_input_time       </td>       <td>$max_input_time         </td>	  	<td> max input time	    </td>		</tr>


  </table>";






// Affichage
/////////////////////////////////////
echo "<br>";
echo "<h4> Configuration de l'affichage </h4>";
echo "<table>";
beginTableHeader();
echo "<td>Define</td><td>Value</td><td>Description</td>";
endTableHeader();
echo " <tr><td>CHARSET_SERVER</td>           			<td>".$CHARSET_SERVER."</td>					
      													<td>type d'encodage</td>	</tr>
	  <tr><td>SUPPRESS_SLASH_QUOTE_URL_VARIABLE</td>    <td>".$SUPPRESS_SLASH_QUOTE_URL_VARIABLE."</td>	
	  													<td>suppression des slash quote dans les variables url</td>	</tr>
  	  		";
if (isset($NO_MSG_CHANGE_HEADER_ACTIVE)){
	echo "
  	  <tr><td>NO_MSG_CHANGE_HEADER_ACTIVE</td>           <td>".$NO_MSG_CHANGE_HEADER_ACTIVE."</td>		
												  	  		<td>
												  	  		permet de ne pas afficher les message showAction<br>
															en mode production mettre sur yes<br>
															yes or no<br>
															peut etre comment&eacute;<br>
												  	  		</td></tr>
   		";
}
else{
	echo "
  	  <tr><td>NO_MSG_CHANGE_HEADER_ACTIVE</td>           <td>''not defined''</td>		
												  	  		<td>
												  	  		permet de ne pas afficher les message showAction<br>
															en mode production mettre sur yes<br>
															yes or no<br>
															peut etre comment&eacute;<br>
												  	  		</td></tr>
   		";
	}
echo " <tr><td>NO_MSG_CHANGE_HEADER</td>           <td>".$NO_MSG_CHANGE_HEADER."</td>
												  	  		<td> permet de ne pas afficher les message showAction<br>
															en mode production mettre sur yes<br>
															yes or no <br> </td></tr>	";

echo "
  	  		<tr></tr>
	<tr>	<td> SHOW_INCLUDE		</td>	<td> $SHOW_INCLUDE			</td>	<td>pas encore utilise				</td></tr>
	<tr>	<td> SHOW_SQL_ACTION	</td>	<td> $SHOW_SQL_ACTION		</td>	<td>trace ou non les actions SQL	</td></tr>
	<tr>	<td> SHOW_MENU_TRACE	</td>	<td> $SHOW_MENU_TRACE		</td>	<td>trace dans les menus			</td></tr>
	<tr>	<td> SHOW_CONNECTION_ID	</td>	<td> $SHOW_CONNECTION_ID	</td>	<td>trace ou no l'id de connexion	</td></tr>
	<tr>	<td> SHOW_FUNCION_JAVA	</td>	<td> $SHOW_FUNCION_JAVA		</td>	<td>trace java script function		</td></tr>
	<tr>	<td> TRACE_POST			</td>	<td> $TRACE_POST			</td>	<td>trace POST						</td></tr>
	<tr>	<td> TRACE_FILE			</td>	<td> $TRACE_FILE			</td>	<td>trace file						</td></tr>
	<tr>	<td> SHOW_FILE_ACTION	</td>	<td> $SHOW_FILE_ACTION		</td>	<td>trace file action				</td></tr>
";


echo "</table>";



//annee pour les reservations
//$ANNEE_RESERVATION=2015;
//$ANNEE_MAX=2015;



//root et directory
echo "<br>";
echo "<h4> Root et repertoire </h4>";
echo "<table>";
beginTableHeader();
echo "<td>Nom</td><td>Value</td><td>Define</td><td>Description</td>";
endTableHeader();
echo" <tr><td>Configuration file	</td>           <td>".$CONFIGURATION_FILE."</td>		<td>CONFIGURATION_FILE</td>	</tr>
	  <tr><td>URL Root de CEGID     </td>           <td>".$URL_ROOT_POINTAGE."</td>		    <td>URL_ROOT_POINTAGE</td>	</tr>
	  <tr><td>Local Path du serveur </td>           <td>".$PATH_WEB_DIRECTORY."</td>	    <td>PATH_WEB_DIRECTORY</td>	</tr>
	  <tr><td>Path Root CEGID       </td>           <td>".$PATH_ROOT_DIRECTORY."</td>		<td>PATH_ROOT_DIRECTORY</td>	</tr>
	  <tr><td>url images            </td>           <td>".$URL_IMAGES."</td>	            <td>URL_IMAGES</td>	</tr>
  	  <tr><td>repertoire upload     </td>           <td>".$PATH_UPLOAD_DIRECTORY."</td>		<td>PATH_UPLOAD_DIRECTORY</td>	</tr>
  	  <tr><td>url upload            </td>           <td>".$URL_UPLOAD_DIRECTORY."</td>		<td>URL_UPLOAD_DIRECTORY</td>
  	  <tr><td>Path depot file (from)</td>           <td>".$DIR_DEPOT_FROM."</td>		    <td>$DIR_DEPOT_FROM</td>
  	  <tr><td>Path stockage file    </td>           <td>".$DIR_DEPOT."</td>	         	    <td>$DIR_DEPOT</td>
  	  <tr><td>size blob             </td>           <td>".$SQL_COL_SIZEMAX_BLOB."</td>		<td>SQL_COL_SIZEMAX_BLOB</td>
   </table>";


//configuration export
echo "<br>";
echo "<h4> Export </h4>";
echo "<table>";
beginTableHeader();
echo "<td>Nom</td><td>Value</td><td>Define</td><td>Description</td>";
endTableHeader();
echo" <tr><td>separateur pour l'export          </td>           <td>".$EXPORT_DELIMITER."</td>		    <td>EXPORT_DELIMITER</td>	</tr>
	  <tr><td>separateur decimal pour l'export  </td>           <td>".$EXPORT_DECIMAL."</td>	        <td>EXPORT_DECIMAL</td>	</tr>
   </table>";



?>
<br/>
</div> <!-- article -->



</div> <!-- contenu -->

  <?PHP include("../menu.php"); ?> 
  <?PHP include("../sql/deconnection_db.php"); ?>
  <?PHP include("../footer.php"); ?> 

</body>
</html>