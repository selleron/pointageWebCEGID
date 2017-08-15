<?php

// Gestion order (sql)
class ORDER_ENUM{
	const ORDER_GET = "order";
	const ORDER_DIRECTION = "order_direction";
	const ORDER_DIRECTION_ASC = "asc";
	const ORDER_DIRECTION_DESC = "desc";
	const ORDER_DIRECTION_NO = "no";
}

// param table
class PARAM_TABLE_TABLE {
    const TABLE_SIZE = "table_size";
    const TABLE_ID = "table_id";
    const TABLE_PRIMARY = "table_primary";
    const TABLE_OTHER = "table_other";
    const TABLE_FORM_INFO = "TABLE_FORM_INFO";
}

$TABLE_SIZE = PARAM_TABLE_TABLE::TABLE_SIZE;
$TABLE_ID = PARAM_TABLE_TABLE::TABLE_ID;
$TABLE_OTHER = PARAM_TABLE_TABLE::TABLE_OTHER;
$TABLE_FORM_INFO = PARAM_TABLE_TABLE::TABLE_FORM_INFO;

class PARAM_TABLE {
    const COLUMNS_SUMMARY = "columns_summary";
    
}
// gestion table pour sql
$CONDITION_GET = "condition";
$SHOW_COL_COUNT = "show_col_count";
$COLUMNS_SUMMARY = PARAM_TABLE::COLUMNS_SUMMARY;
$COLUMNS_FILTER = "columns_filtered";
$TABLE_NAME = "TABLE_NAME";
$TABLE_ROW_FIRST = "TABLE_ROW_FIRST";
$TABLE_ROW_LIMIT = "TABLE_ROW_LIMIT";
$TABLE_WHERE_ID_KEY = "TABLE_WHERE_KEY";
$TABLE_WHERE_ID_VALUE = "TABLE_WHERE_VALUE";
$TABLE_WHERE_CONDITION = "TABLE_WHERE_CONDITION";
$TABLE_WHERE_CONDITION_FOR_UPDATE = "TABLE_WHERE_CONDITION_FOR_UPDATE";
$TABLE_DISTINCT = "USE_SELECT_DISTINCT";

class PARAM_TABLE_FORM {
    const TABLE_FORM_NAME_INSERT = "TABLE_FORM_NAME";   
}

$TABLE_FORM_NAME_INSERT = PARAM_TABLE_FORM::TABLE_FORM_NAME_INSERT;

//gestion PARAM_TABLE_ACTION parametrage pour les action
class PARAM_TABLE_ACTION {
    const TABLE_INSERT = "TABLE_INSERT";
    const TABLE_UPDATE = "TABLE_UPDATE";
    const TABLE_DELETE = "TABLE_DELETE";
    const TABLE_EDIT = "TABLE_EDIT";
    const TABLE_DELETE_BY_ROW = "TABLE_DELETE_BY_ROW";
    const TABLE_EDIT_BY_ROW = "TABLE_EDIT_BY_ROW";
    const TABLE_EXPORT_CSV = "TABLE_EXPORT_CSV";
    const TABLE_COMMNAND = "TABLE_COMMAND";
}

// gestion table insert,update,delete
$TABLE_INSERT        = PARAM_TABLE_ACTION::TABLE_INSERT;
$TABLE_UPDATE        = PARAM_TABLE_ACTION::TABLE_UPDATE;
$TABLE_DELETE        = PARAM_TABLE_ACTION::TABLE_DELETE;
$TABLE_EDIT          = PARAM_TABLE_ACTION::TABLE_EDIT;
$TABLE_DELETE_BY_ROW = PARAM_TABLE_ACTION::TABLE_DELETE_BY_ROW;
$TABLE_EDIT_BY_ROW   = PARAM_TABLE_ACTION::TABLE_EDIT_BY_ROW;
$TABLE_EXPORT_CSV    = PARAM_TABLE_ACTION::TABLE_EXPORT_CSV;


//gestion parametrage pour une commande
class PARAM_TABLE_COMMAND {
    const URL="URL";
    const NAME="NAME";
    const ACTION="ACTION";
    const REFERENCE="REFERENCE";
}

?>