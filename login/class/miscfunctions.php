<?php
/**
* Miscellaneous utility functions
*/
class MiscFunctions
{
    public static function mySqlErrors($response)
    {
        //Returns custom error messages instead of MySQL errors

        switch (substr($response, 0, 22)) {

            case 'Error: SQLSTATE[23000]':
                echo "<div class=\"alert alert-danger alert-dismissable\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Username or email already exists</div>";
                break;

            default:
                echo $response."<div class=\"alert alert-danger alert-dismissable\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>An error occurred... try again</div>";

        }
    }

    public static function assembleUids($uid_string)
    {
        $uid_array = json_decode($uid_string);

        foreach ($uid_array as $id) {
            if (isset($uids)) {
                $uids = $uids.", '".$id."'";
            } else {
                $uids = "'".$id."'";
            };
        };

        return $uids;
    }

    // Check if url is relative or absolute
    public static function isAbsUrl($url)
    {
        if ($url[0] == '/') {
            return true;
        }

        if (strpos($url, "://")) {
            return true;
        }

        return false;
    }


    /**
     * DATATABLES FUNCTIONS
     */
    public static function dt_limit($request, $columns)
    {
        $limit = '';
        if (isset($request['start']) && $request['length'] != -1) {
            $limit = "LIMIT ".intval($request['start']).", ".intval($request['length']);
        }
        return $limit;
    }

    public static function dt_order($request, $columns)
    {
        $order = '';
        if (isset($request['order']) && count($request['order'])) {
            $orderBy = array();
            $dtColumns = self::pluck($columns, 'dt');
            for ($i=0, $ien=count($request['order']) ; $i<$ien ; $i++) {
                // Convert the column index into the column data property
                $columnIdx = intval($request['order'][$i]['column']);
                $requestColumn = $request['columns'][$columnIdx];
                $columnIdx = array_search($requestColumn['data'], $dtColumns);
                $column = $columns[ $columnIdx ];
                if ($requestColumn['orderable'] == 'true') {
                    $dir = $request['order'][$i]['dir'] === 'asc' ?
                        'ASC' :
                        'DESC';
                    $orderBy[] = '`'.$column['db'].'` '.$dir;
                }
            }
            $order = 'ORDER BY '.implode(', ', $orderBy);
        }
        return $order;
    }

    public static function dt_filter($request, $columns, &$bindings)
    {
        $globalSearch = array();
        $columnSearch = array();
        $dtColumns = self::pluck($columns, 'dt');
        if (isset($request['search']) && $request['search']['value'] != '') {
            $str = $request['search']['value'];
            for ($i=0, $ien=count($request['columns']) ; $i<$ien ; $i++) {
                $requestColumn = $request['columns'][$i];
                $columnIdx = array_search($requestColumn['data'], $dtColumns);
                $column = $columns[ $columnIdx ];
                if ($requestColumn['searchable'] == 'true') {
                    $binding = self::bind($bindings, '%'.$str.'%', PDO::PARAM_STR);
                    $globalSearch[] = "`".$column['db']."` LIKE ".$binding;
                }
            }
        }
        // Individual column filtering
        if (isset($request['columns'])) {
            for ($i=0, $ien=count($request['columns']) ; $i<$ien ; $i++) {
                $requestColumn = $request['columns'][$i];
                $columnIdx = array_search($requestColumn['data'], $dtColumns);
                $column = $columns[ $columnIdx ];
                $str = $requestColumn['search']['value'];
                if ($requestColumn['searchable'] == 'true' &&
       $str != '') {
                    $binding = self::bind($bindings, '%'.$str.'%', PDO::PARAM_STR);
                    $columnSearch[] = "`".$column['db']."` LIKE ".$binding;
                }
            }
        }
        // Combine the filters into a single string
        $where = '';
        if (count($globalSearch)) {
            $where = '('.implode(' OR ', $globalSearch).')';
        }
        if (count($columnSearch)) {
            $where = $where === '' ?
      implode(' AND ', $columnSearch) :
      $where .' AND '. implode(' AND ', $columnSearch);
        }
        if ($where !== '') {
            $where = 'WHERE '.$where;
        }
        return $where;
    }

    public static function pluck($a, $prop)
    {
        $out = array();
        for ($i=0, $len=count($a) ; $i<$len ; $i++) {
            $out[] = $a[$i][$prop];
        }
        return $out;
    }

    public static function bind(&$a, $val, $type)
    {
        $key = ':binding_'.count($a);
        $a[] = array(
            'key' => $key,
            'val' => $val,
            'type' => $type
        );
        return $key;
    }
    public static function data_output($columns, $data)
    {
        $out = array();
        for ($i=0, $ien=count($data) ; $i<$ien ; $i++) {
            $row = array();
            for ($j=0, $jen=count($columns) ; $j<$jen ; $j++) {
                $column = $columns[$j];
                // Is there a formatter?
                if (isset($column['formatter'])) {
                    $row[ $column['dt'] ] = $column['formatter']($data[$i][ $column['db'] ], $data[$i]);
                } else {
                    $row[ $column['dt'] ] = $data[$i][ $columns[$j]['db'] ];
                }
            }
            $out[] = $row;
        }
        return $out;
    }
}
