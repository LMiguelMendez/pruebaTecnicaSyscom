<?php
require_once __DIR__ . '/../vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class DiasHabilesCalculator
{
    private static $festivos = [];
    
    public static function calcularDiasHabiles($fechaInicio, $fechaFin = null)
    {
        if ($fechaFin === null) {
            $fechaFin = date('Y-m-d');
        }
        
        $inicio = new DateTime($fechaInicio);
        $fin = new DateTime($fechaFin);
        $fin->setTime(0, 0, 0);
        
        $year = $inicio->format('Y');
        
        self::cargarFestivos($year);
        
        $diasHabiles = 0;
        $intervalo = new DateInterval('P1D');
        $periodo = new DatePeriod($inicio, $intervalo, $fin->modify('+1 day'));
        
        foreach ($periodo as $fecha) {
            if (self::esDiaHabil($fecha)) {
                $diasHabiles++;
            }
        }
        
        return $diasHabiles;
    }
    
    private static function esDiaHabil(DateTime $fecha)
    {
        $diaSemana = (int)$fecha->format('N');
        if ($diaSemana === 6 || $diaSemana === 7) {
            return false;
        }
        
        $fechaStr = $fecha->format('Y-m-d');
        if (in_array($fechaStr, self::$festivos)) {
            return false;
        }
        
        return true;
    }
    
    private static function cargarFestivos($year)
    {
        if (!empty(self::$festivos) && isset(self::$festivos[$year])) {
            return;
        }
        
        try {
            $client = new Client([
                'timeout' => 10,
                'verify' => false
            ]);
            
            $response = $client->get("https://api-colombia.com/api/v1/holiday/year/{$year}");
            $festivosData = json_decode($response->getBody(), true);
            
            $festivosDelYear = [];
            foreach ($festivosData as $festivo) {
                if (isset($festivo['date'])) {
                    $festivosDelYear[] = $festivo['date'];
                }
            }
            
            self::$festivos[$year] = $festivosDelYear;
            
        } catch (RequestException $e) {
            // si la api falla se se desarrollo la siguiente lista por defecto
            error_log("Error al cargar festivos desde API: " . $e->getMessage());
            self::$festivos[$year] = self::getFestivosPorDefecto($year);
        }
    }

    private static function getFestivosPorDefecto($year)
    {
        $festivosFijos = [
            '01-01', 
            '01-06', 
            '03-19', 
            '05-01', 
            '06-29', 
            '07-20', 
            '08-07', 
            '08-15',
            '10-12',
            '11-01', 
            '11-11',
            '12-08',
            '12-25' 
        ];
        
        $festivosMovibles = self::calcularFestivosMovibles($year);
        
        $todosFestivos = array_merge($festivosFijos, $festivosMovibles);

        $festivosFormateados = [];
        foreach ($todosFestivos as $festivo) {
            $festivosFormateados[] = "{$year}-{$festivo}";
        }
        
        return $festivosFormateados;
    }
    
    private static function calcularFestivosMovibles($year)
    {
        $a = $year % 19;
        $b = floor($year / 100);
        $c = $year % 100;
        $d = floor($b / 4);
        $e = $b % 4;
        $f = floor(($b + 8) / 25);
        $g = floor(($b - $f + 1) / 3);
        $h = (19 * $a + $b - $d - $g + 15) % 30;
        $i = floor($c / 4);
        $k = $c % 4;
        $l = (32 + 2 * $e + 2 * $i - $h - $k) % 7;
        $m = floor(($a + 11 * $h + 22 * $l) / 451);
        $mes = floor(($h + $l - 7 * $m + 114) / 31);
        $dia = (($h + $l - 7 * $m + 114) % 31) + 1;
        
        $pascua = DateTime::createFromFormat('Y-m-d', "{$year}-{$mes}-{$dia}");
        
        return [
            $pascua->modify('-3 days')->format('m-d'),  
            $pascua->modify('+1 days')->format('m-d'),  
            $pascua->modify('+40 days')->format('m-d'), 
            $pascua->modify('+11 days')->format('m-d'),
            $pascua->modify('+11 days')->format('m-d')  
        ];
    }
}
