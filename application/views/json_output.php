<?php

if (!isset($envia_headers) || $envia_headers == true)
{
    @ header('Content-type: application/json');
}

if (!function_exists('force_utf8_encode_all'))
{

    function force_utf8_encode_all($dat) // -- It returns $dat encoded to UTF8
    {
        if (is_string($dat))
        {
            if (mb_check_encoding($dat, 'UTF-8'))
            {
                return $dat;
            }
            else
            {
                return utf8_encode($dat);
            }
        }
        elseif (is_scalar($dat))
        {
            return $dat;
        }
        elseif (is_null($dat))
        {
            return null;
        }

        $ret = array();
        foreach ($dat as $i => $d)
        {
            $ret[$i] = force_utf8_encode_all($d);
        }

        return $ret;
    }

}

$output = force_utf8_encode_all($output);
echo json_encode($output);
