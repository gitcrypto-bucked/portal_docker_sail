<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TrackingModel extends Model
{
    #status_descricao atualizacao dos correios /transpordora
    # 0 andamento,  1 entregue , 2 cancalado

    #tracking code null-> postado
    #tracking code null  & status ==2> postado   dot cancalado
    #tracking code !null & status !=1 -> transporte
    #tracking code !null & status ==2 -> transporte dot cancelado
    #status = 1  -> entregue

    public function  getTracking($clienteID)
     {
               return DB::select("SELECT
                                r.numero_pedido, r.nfe, r.data_faturamento, r.tracking_code, max(r.data_ocorrencia) as data, r.previsaoEntrega,
                                (SELECT status FROM rastreamento r2 WHERE r2.numero_pedido = r.numero_pedido ORDER BY data_ocorrencia DESC LIMIT 1) as st,
                                (SELECT id FROM rastreamento r2 WHERE r2.numero_pedido = r.numero_pedido ORDER BY data_ocorrencia DESC LIMIT 1) as id
                            FROM
                              rastreamento as r
                            JOIN localidades l ON r.responsavel = l.localidade
                            JOIN clientes c on c.idCliente = l.idCliente
                            WHERE c.id=".$clienteID."
                            GROUP BY numero_pedido, tracking_code, nfe, data_faturamento , previsaoEntrega");
     }




    public function getTrackingDetails($num_pedido)
    {
        return DB::table("rastreamento")->where("numero_pedido",$num_pedido)->orderBy('data_ocorrencia','DESC')->get();
    }
}
