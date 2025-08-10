<?php
    function get_select($table, $data='', $where='', $orderby='', $join=array())
    {
        global $dbname, $mysqli, $log_file_sql;
    
        $sql = "SELECT ";
        
        $select_sql="";
        $where_sql="";
        
        if($data) {
            foreach($data as $key=>$value){
                if($select_sql) $upt_sql .= ",";
                $select_sql .= $key."='$value'";
            }
            $sql .=  " FROM ".$dbname.'.'.$table;
        } else {
           $sql .=  "* FROM ".$dbname.'.'.$table;
        }
       
	if ($where) {
            $sql .= ' WHERE ';
            foreach ($where as $key => $value) {
                if ($where_sql) {
                    $where_sql .= " and ";
                }
                $where_sql .= $key . "='$value'";
            }
            $sql .= $where_sql;
        }   	
 
        if ($orderby) {
            $sql .=  " order by ".$orderby;
        }
        
        if($join) {
            $sql .= $join[0].' '.$join[1].' on '.$join[2];
        }
        
        baro_write_log('----- '.$table.' SELECT SQL -----', $log_file_sql, array($sql));
    
        $result = $mysqli->query($sql);
        
        return $result;
    }
    
    function get_insert($table,$insert_data)
    {
        global $dbname, $mysqli, $log_file_sql;

        $ins_sql = "";
        foreach ($insert_data as $key => $value) {
            if ($ins_sql) {
                $ins_sql .= ",";
            }
            $ins_sql .= $key . "='$value'";
        }
    
        $sql = "INSERT INTO $dbname.$table set $ins_sql";
        baro_write_log('----- '.$table.' INSERT SQL -----', $log_file_sql, array($sql));
        $result = $mysqli->query($sql);
        return $result;
    }
    
    function get_update($table, $idx_data, $upt_data)
    {
        global $dbname, $mysqli, $log_file_sql;
    
        $upt_sql = "";
        foreach ($upt_data as $key => $value) {
            if ($upt_sql) {
                $upt_sql .= ",";
            }
	    if($value == null) {
                $upt_sql .= $key . "=null";
            } else {
                $upt_sql .= $key . "='$value'";
            }
        }
    
        $idx_key = $idx_data['key'];
        $idx_value = $idx_data['value'];
    
    
        $sql = "update $dbname.$table set $upt_sql where $idx_key = $idx_value";
    
        baro_write_log('----- '.$table.' UPDATE SQL -----', $log_file_sql, array($sql));
    
	$result = $mysqli->query($sql);
    
        return $result;
    }
    

    function get_delete($table, $idx_data)
    {
        global $dbname, $mysqli, $log_file_sql;
    
        $idx_key = $idx_data['key'];
        $idx_value = $idx_data['value'];
    
    
        $sql = "delete $dbname.$table where $idx_key = $idx_value";
    
        baro_write_log('----- '.$table.' DELETE SQL -----', $log_file_sql, array($sql));
    
        $mysqli->query($sql);
    
        return true;
    }

     function get_update2($table, $idx_data, $idx_data2, $upt_data)
        {
            global $dbname, $mysqli, $log_card_info_file_sql;
        
            $upt_sql = "";
            foreach ($upt_data as $key => $value) {
                if ($upt_sql) {
                    $upt_sql .= ",";
                }
                if($value == null) {
                    $upt_sql .= $key . "=null";
                } else {
                    $upt_sql .= $key . "='$value'";
                }
            }
        
            $idx_key = $idx_data['key'];
            $idx_value = $idx_data['value'];
            $idx_key2 = $idx_data2['key'];
            $idx_value2 = $idx_data2['value'];
        
        
            $sql = "update $dbname.$table set $upt_sql where $idx_key = $idx_value and $idx_key2 = $idx_value2";
        
            baro_write_log('----- '.$table.' UPDATE SQL -----', $log_card_info_file_sql, array($sql));
        
            $result = $mysqli->query($sql);
        
            return $result;
        }
