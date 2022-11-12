<?php

require_once 'BaseModel.php';

class LeiturasModel extends BaseModel
{

    public function getVolumeChuvaMinimo(){
        $this->db->from("Leitura L")
                ->select("
                    MIN(
                        (
                            SELECT
                                volume_chuva
                            FROM
                                leitura L
                    )
                    AS volume_minimo
                    ");
                    $linha = $this->db->get()->row_array();

                    return $linha["volume_minimo"];
    }

    public function getVolumeChuvaMaxima(){
        $this->db->from("Leitura L")
                ->select("
                    MAX(
                        (
                            SELECT
                                volume_chuva
                            FROM
                                leitura L
                    )
                    AS volume_maxima
                    ");
                    $linha = $this->db->get()->row_array();

                    return $linha["volume_maxima"];
    }

}
