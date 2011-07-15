<?php
function table_print($array, $style)
{
    echo "<table class=" . $style . " cellpadding=2 cellspacing=0 border=1>";
    foreach ($array as $value => $row) {
        echo "<tr>";
        foreach ($row as $value => $data) {
            echo "<td>" . $data . "</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
}
function list_print($array, $list_type, $special)
{
    echo "<" . $list_type . ">";
    foreach ($array as $value => $row) {
        echo "<li>";
        if ($special) {
            foreach ($row as $value => $data) {
                print $data;
            }
        } elseif (is_array($row)) {
            list_print($row, $list_type);
        } else {
            echo $row;
        }
        echo "</li>";
    }
    echo "</" . $list_type . ">";
}

function tr($array, $element, $class)
{
    if ($element == "td") {
        $tr = "<tr class=\"$class\"><" . $element . ">" . implode("</" . $element . "><" . $element . ">", $array) . "</" . $element . "></tr>";
    } else {
        $tr = "<tr class=\"$class\"><" . $element . ">" . implode("</" . $element . "><" . $element . ">", $array) . "</" . $element . "></tr>";
    }
    return $tr;
}

function SQL_table_query($statement, $id)
{
    if ($result = mysql_query($statement)) {
        echo "<table id=\"results\" width=98% cellpadding=0 cellspacing=0 border=0>";
        $numfields = mysql_num_fields($result);
        $headerData[] = "#";
        //global $class_type;
        global $order_by;
        global $order_by2;
        global $order_dir;
        global $order_dir2;
        $ordery_dir = $order_dir;
        $ordery_by = $order_by;
        for ($i = 0; $i < $numfields; $i++) {
            // Header
            $field_name = mysql_field_name($result, $i);
            if ($id == "active") {
                $ordery_dir = $order_dir2;
                $ordery_by = $order_by2;
            }
            if (($field_name == $ordery_by) && ($ordery_dir == "ASC")) {
                $ordery_dir = "DESC";
            } elseif (($field_name == $ordery_by) && ($ordery_dir == "DESC")) {
                $ordery_dir = "ASC";
            }
            if (($field_name == $ordery_by) && ($ordery_dir == "ASC")) {
                $headerData[] = "<a class=\"orderUP\" onclick=\"javascript:query_linky('" . $id . "', '" . $field_name . "', '" . $ordery_dir . "');\">" . mysql_field_name($result, $i) . " ^</a>";
            } elseif (($field_name == $ordery_by) && ($ordery_dir == "DESC")) {
                $headerData[] = "<a class=\"orderDOWN\" onclick=\"javascript:query_linky('" . $id . "', '" . $field_name . "', '" . $ordery_dir . "');\">" . mysql_field_name($result, $i) . " v</a>";
            } else {
                //global $class_type;
                $headerData[] = "<a onclick=\"javascript:query_linky('" . $id . "', '" . $field_name . "', '" . $ordery_dir . "');\">" . mysql_field_name($result, $i) . " v</a>";
            }
        }
        echo tr($headerData, "th", "");
        $j = 1;
        while ($db_field = mysql_fetch_assoc($result)) {
            unset($rowData);
            $rowData[] = $j;
            $j++;
            foreach ($db_field as $i => $value) {
                $rowData[] = $db_field[$i];
                if ($i == "contribution") {
                    if ($value == "helps") {
                        $class = $alt . "helps";
                    } elseif ($value == "hurts") {
                        $class = $alt . "hurts";
                    }
                    if ($alt == "alt") {
                        $alt = "";
                    } else {
                        $alt = "alt";
                    }
                }
            }
            echo tr($rowData, "td", $class);
        }
        echo "</table>";
    } else {
        die('Error: ' . mysql_error());
    }
}
?>
