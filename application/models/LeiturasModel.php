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

    public function getUltimasLeituras()
    {
        $this->db->select('L.*')
                ->from('leitura L');
    }

}
