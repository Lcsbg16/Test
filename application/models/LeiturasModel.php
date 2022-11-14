<?php

require_once 'BaseModel.php';

class LeiturasModel extends BaseModel
{
        public function getUltimaTemperaturaMedia()
    {
        $this->db->from('estacao E')
                ->select("
                    AVG(
                        (
                            SELECT
                                    temperatura
                            FROM
                                    leitura L1
                            WHERE
                                    L1.estacao_id = E.id
                                    AND datahora >= '" . date('Y-m-d') . " 00:00:00'
                            ORDER BY
                                    datahora DESC
                            LIMIT 1
                        )
                    )
                    AS temperatura_media
                ");
        $linha = $this->db->get()->row_array();
        return $linha['temperatura_media'];
    }
    
    public function getVolumeChuvaMinimo(){
        $this->db->from("Leitura L")
                ->select("
                    MIN(
                        (
                            SELECT
                                    volume_chuva
                            FROM
                                    leitura L1
                            WHERE
                                    L1.estacao_id = E.id
                                    AND datahora >= '" . date('Y-m-d') . " 00:00:00'
                            ORDER BY
                                    datahora DESC
                            LIMIT 1                                    
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
                                    leitura L1
                            WHERE    
                                    L1.estacao_id = E.id
                                    AND datahora >= '" . date('Y-m-d') . " 00:00:00'
                            ORDER BY
                                    datahora DESC
                            LIMIT 1
                    )
                    AS volume_maxima
                    ");
                    $linha = $this->db->get()->row_array();

                    return $linha["volume_maxima"];
    }

}
