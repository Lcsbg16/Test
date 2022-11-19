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
    


    public function getVolumeChuvaMinimo()
    {
        $this->db->from('estacao E')
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
                    )
                    AS vol_chuva_min
                ");
        $linha = $this->db->get()->row_array();
        return $linha['vol_chuva_min'];
    }


    public function getVolumeChuvaMaxima(){
        $this->db->from('estacao E')
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
            )
            AS vol_chuva_max
        ");
        $linha = $this->db->get()->row_array();
        return $linha['vol_chuva_max'];
    }

}
