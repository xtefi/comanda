<?php
require_once './models/Log.php';
require './fpdf/fpdf.php';

class LogsController extends Log
{
    private static function EscribirCsv($fileContent, $fileName = "./logs.csv") {
        $directory = dirname($fileName, 1);
        $success = false;
        try {
            if(!file_exists($directory)){
                mkdir($directory, 0777, true);
            }
            $file = fopen($fileName, "w");
            if ($file) {
                foreach ($fileContent as $entity) {
                    $line = $entity->id . "," . $entity->id_usuario . "," . $entity->usuario . ",". $entity->rol . "," . $entity->entidad . "," . $entity->operacion . "," . $entity->datos_operacion . "," . $entity->datos_resultado_operacion . PHP_EOL;
                    fwrite($file, $line);
                    $success = true;
                }
            }
        } catch (\Throwable $th) {
            echo "Error al guardar el archivo the file";
        }finally{
            fclose($file);
        }
        return $success;
    }

    private static function LeerCsv($filename="./logs.csv"){
        $file = fopen($filename, "r");
        $datos = array();       
        if (file_exists($filename) && filesize($filename) > 0) {
            $file = fopen($filename, "r");
            while(!feof($file)) {
                $linea = fgets($file);
                if(!empty($linea)){                 
                    $linea = str_replace(PHP_EOL, "", $linea);
                    $csvData = explode(",", $linea);
                    $auxSurv = Log::ConstructorLogs( intval($csvData[0]), ($csvData[1]), ($csvData[2]), ($csvData[3]), ($csvData[4]), 
                    ($csvData[5]) , ($csvData[6]) , $csvData[7]);
                    array_push($datos, $auxSurv);
                }
            }
            fclose($file);
        }
        return $datos;
    }

    public function EndPointEscribirCsv($request, $response, $args) {        
        $logs = Log::obtenerTodos();
        if( (count($logs) > 0) && LogsController::EscribirCsv($logs)) {
            $payload = json_encode(array("Logs" => $logs));
            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(201);
        } else {
            $payload = json_encode(array("ERROR" => "No se pudo guardar el archivo"));
            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(500);
        }
    }

    public static function EndPointEscribirPDF ($request, $response, $args) {
        
        $pdf = new FPDF('P', 'mm', 'A3');
        $pdf->AddPage();
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(30,10,'Logs');
        $pdf->Ln();
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(30,10,'ID');
        $pdf->Cell(30,10,'ID Usuario');
        $pdf->Cell(30,10,'Usuario');
        $pdf->Cell(30,10,'Rol');
        $pdf->Cell(30,10,'Entidad');
        $pdf->Cell(30,10,'Operacion');
        $pdf->Cell(30,10,'Datos operacion');
        $pdf->Cell(30,10,'Resultados operacion');
        $pdf->Ln();
        $pdf->SetFont('Arial','',12);

        $arrayFile = LogsController::LeerCsv();

        foreach ($arrayFile as $entity) {
            $pdf->Cell(30,10,$entity->id);
            $pdf->Cell(30,10,$entity->id_usuario);
            $pdf->Cell(30,10,$entity->usuario);
            $pdf->Cell(30,10,$entity->rol);
            $pdf->Cell(30,10,$entity->entidad);
            $pdf->Cell(30,10,$entity->operacion);
            $pdf->Cell(30,10,$entity->datos_operacion);
            $pdf->Cell(30,10,$entity->datos_resultado_operacion);
            $pdf->Ln();
        }
        $pdf->Output('I', 'logs.pdf');

        return $response->withHeader('Content-Type', 'application/pdf')
        ->withStatus(201);
    }
}




// H. (GET) Descargar un archivo CSV con la información de todos los logs creados en
// el punto G.
// I. (GET) Descargar un archivo PDF con la información de todos los logs creados en
// el punto F, pasando un parámetro para listarlo ascendente o descendente.