<?php

namespace Flags;
class Flags
{
    public static function getFlags()
    {
        return  [

            'lowcost'=>
               [
                    'admin'=>['list-news','add_news','news-manager','invoice-upload', 'chamados-upload','new_user','cliente_manager','filter_clientes'],
                    '9'=>['list-news'],
                    '2'=>['list-news','invoice-upload', 'chamados-upload'],
                    '1'=>['list-news','add_news','news-manager'],
               ],
            'partner'=>
                [
                    'admin'=>['dashboard-faturamento', 'list-news','inventario','faturamento','chamados','tracking' ],
                    '9'=>['dashboard-faturamento', 'list-news','inventario','faturamento','chamados','tracking' ],
                    '2'=>['list-news'=>1,'add_news'=>0,'news-manager'=>0,'invoice-upload'=>1, 'chamados-upload'=>1,'new_user'=>0],
                    '1'=>['list-news'=>1,'add_news'=>1,'news-manager'=>1,'invoice-upload'=>0, 'chamados-upload'=>0,'new_user'=>0,'cliente_manager'=>1],
                ]

        ];
    }


    public static function getMenuFlags()
    {
        return
        [
                'lowcost'=>[
                    'admin'=>['list-news'=>1,'add_news'=>1,'news-manager'=>1,'invoice-upload'=>1, 'chamados-upload'=>1,'new_user'=>1,'cliente_manager'=>1],
                    '9'=>['list-news'=>1],
                    '2'=>['list-news'=>1,'add_news'=>0,'news-manager'=>0,'invoice-upload'=>1, 'chamados-upload'=>1,'new_user'=>0],
                    '1'=>['list-news'=>1,'add_news'=>1,'news-manager'=>1,'invoice-upload'=>0, 'chamados-upload'=>0,'new_user'=>0,'cliente_manager'=>1],
                ],
                'partner'=>
                [
                    'admin'=>['dashboard-faturamento'=>1,'dashboard-chamados'=>1, 'list-news'=>1,'inventario'=>1,'faturamento'=>1,'chamados'=>1,'tracking'=>1 ],
                    '9'=>['dashboard-faturamento'=>0, 'list-news'=>1,'inventario'=>0,'faturamento'=>0,'chamados'=>1,'tracking'=>1 ],
                    '2'=>['list-news'=>1,'add_news'=>0,'news-manager'=>0,'invoice-upload'=>1, 'chamados-upload'=>1,'new_user'=>0],
                    '1'=>['list-news'=>1,'add_news'=>1,'news-manager'=>1,'invoice-upload'=>0, 'chamados-upload'=>0,'new_user'=>0,'cliente_manager'=>1],
                ]
        ];

    }

}
