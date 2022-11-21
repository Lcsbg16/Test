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
    
    public function getTemperaturaMinima(){
        $this->db->from('estacao E')
                ->select("
                    MIN(
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
                    AS temperatura_minima
                ");
        $linha = $this->db->get()->row_array();

        return $linha['temperatura_minima'];
    }
                            
    public function getTemperaturaMaxima(){
        $this->db->from('estacao E')
                ->select("
                    MAX(
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
                    AS temperatura_maxima
                ");
        $linha = $this->db->get()->row_array();

        return $linha['temperatura_maxima'];
    }
    
    

}
