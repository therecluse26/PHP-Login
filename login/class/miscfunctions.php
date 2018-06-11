<?php
/**
* PHPLogin\MiscFunctions
*/
namespace PHPLogin;

/**
* Miscellaneous utility functions
*/
class MiscFunctions
{
    /**
     * Formats MySQL errors for human-readability
     *
     * @param  string $response MySQL error response
     *
     * @return string Echoed response string
     */
    public static function mySqlErrors($response)
    {
        //Returns custom error messages instead of MySQL errors
        switch (substr($response, 0, 22)) {

            case 'Error: SQLSTATE[23000]':
                echo "<div class=\"alert alert-danger alert-dismissable\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>".$response."</div>";
                break;

            default:
                echo $response."<div class=\"alert alert-danger alert-dismissable\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>An error occurred... try again</div>";

        }
    }

    /**
     * Formats PDO errors for human-readability
     *
     * @param  string $error MySQL error response
     *
     * @return string Echoed response string
     */
    public static function FormatPDOError($error)
    {
        if (strstr($error->getMessage(), 'SQLSTATE[')) {
            $format = preg_replace('/SQLSTATE\[.+\]:/', '', $error->getMessage());
            $format = preg_replace('/<+.+>+:.[0-9]+[ ]/', '', $format);
            $format = trim($format);
            return $format;
        } else {
            return $e->getMessage();
        }
    }

    /**
     * Concatenates array to string for prepared statements
     *
     * @param  array $record     Array of records to generate placeholders for
     * @param  string $separator Separator value. Defaults to ","
     * @param  [type] $bind_id   [description]
     *
     * @return [type]            [description]
     */
    public static function placeholders($record, $separator= ",", $bind_id)
    {
        $string = '';

        foreach ($record as $key => $val) {
            $string .= '(\''.$val .'\''. $separator . $bind_id.'),';
        }

        return substr($string, 0, -1);
    }


    /**
     * Checks if url is absolute
     *
     * @param  string  $url URL to check
     *
     * @return boolean Returns if URL is absolute or not
     */
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

    /**
     * DataTables number of records to display
     *
     * @param array $request DataTables request
     * @param array $columns DataTables columns
     *
     * @return string Query string returned
     */
    public static function dt_limit($request, $columns)
    {
        $limit = '';
        if (isset($request['start']) && $request['length'] != -1) {
            $limit = "LIMIT ".intval($request['start']).", ".intval($request['length']);
        }
        return $limit;
    }

    /**
     * DataTables order of records
     *
     * @param array $request DataTables request
     * @param array $columns DataTables columns
     *
     * @return string Query string returned
     */
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

    /**
     * DataTables filtering
     *
     * @param array $request DataTables request
     * @param array $columns DataTables columns
     * @param array $bindings DataTables bindings
     *
     * @return string Query string returned
     */
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
                    $binding = self::bind($bindings, '%'.$str.'%', \PDO::PARAM_STR);
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
                    $binding = self::bind($bindings, '%'.$str.'%', \PDO::PARAM_STR);
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

    /**
     * DataTables pluck function (used by `dt_order` and `dt_filter`)
     *
     * @param array $a
     * @param string $prop
     *
     * @return array
     */
    public static function pluck($a, $prop)
    {
        $out = array();
        for ($i=0, $len=count($a) ; $i<$len ; $i++) {
            $out[] = $a[$i][$prop];
        }
        return $out;
    }

    /**
     * DataTables bind function (used by `dt_filter`)
     *
     * @param array $a
     * @param string $val
     * @param string $type
     *
     * @return array
     */
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

    /**
     * DataTables data output
     *
     * @param array $columns DataTables columns
     * @param array $data DataTables data
     *
     * @return array
     */
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
