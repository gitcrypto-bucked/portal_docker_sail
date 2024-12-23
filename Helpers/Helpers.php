<?php

namespace Helpers;

use App\Models\ClientesModel;
use Illuminate\Support\Facades\Auth;

class Helpers
{
    public static function generateRandomString($length = 10)
    {
        return "@need to deploy";
    }


    public static function getUserCompanyLogo($id)
    {
      return self::getLogo($id);
    }

    public static function getUserCompanyNameFormated()
    {
        $name = self::getUserCompanyName(Auth::user()->cliente);
        if(in_array(strtolower($name), ['sp','mg','df','rj','sc','pr','pe','mt','ms','go','ce','ba','pb'])==true)
        {
            return self::showUF($name);
        }
        else
        {
            return  self::camelCase($name);
        }
    }

    public static function getUserCompanyName($id)
    {
        $model = new ClientesModel();
        return str_replace('ç','c',$model->getCLienteNameBiUd($id));
    }

    private static function camelCase($string) {
        $string = strtolower($string);
        return ucwords($string);
    }

    public static function showUF($word)
    {
        if(str_contains($word, 'Sp'))
        {
            return str_replace("Sp",'SP',$word);
        }
        if(str_contains($word, 'Rj'))
        {
            return str_replace("Rj",'RJ',$word);
        }
        return $word;
    }


    protected static  function getLogo($id):string
    {
        $model = new ClientesModel();
        $cliente = $model->getLogo($id)[0];
        if(strlen($cliente->logo)>0)
        {
            return  asset('storage/public/clientes/'.$cliente->logo);
        }
        else
        {
            return  asset('/public/empresas/lowcost.svg');
        }
    }


    public static function getDatesFromRange($start, $end, $format = 'M-Y'){

        // Declare an empty array
        $array = array();

        // Variable that store the date interval
        // of period 1 day
        $interval = new \DateInterval('P1D');
        $realEnd = new \DateTime($end);
        $realEnd->add($interval);
        $period = new \DatePeriod(new \DateTime($start), $interval, $realEnd);

        // Use loop to store date into array
        foreach($period as $date){
            $array[] = $date->format($format);

        }

        // Return the array elements
        return $array;

    }


    public static  function  formatCidade($cidade)
    {
        $cidade= str_replace('(sp)','',$cidade);
        $cidade= str_replace('(mg)','',$cidade);
        $cidade= str_replace('(rj)','',$cidade);
        $cidade= str_replace('(rs)','',$cidade);
        $cidade= str_replace('(mt)','',$cidade);
        $cidade= str_replace('(sc)','',$cidade);
        $cidade= str_replace('(pr)','',$cidade);
        $cidade= str_replace('(pa)','',$cidade);
        return ucfirst($cidade);
    }

    public  static  function  formatCEP($cep)
    {
        $cep = substr($cep, 0, 5) . '-' . substr($cep, 5, 3);
        return $cep;
    }

}
