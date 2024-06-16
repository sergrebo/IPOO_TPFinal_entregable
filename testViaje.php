<?php
include_once("Viaje.php");
include_once("Responsable.php");
include_once("Pasajero.php");
include_once("Empresa.php");
include_once("BaseDatos.php");

echo "======================Bienvenido======================
     \n     [ Ingrese una de las opciones deseadas ] \n";

do {
    echo "Ingrese (1) ver informacion de un viaje \n";
    echo "Ingrese (2) para Vender pasaje \n";
    echo "Ingrese (3) ingresar un viaje \n" ;
    echo "Ingrese (4) ver viajes de la empresa \n";
    echo "Ingrese (5) ver modificar un viaje o pasajero\n";
    echo "Ingrese (6) ver eliminar un viaje o pasajero\n";
    echo "Ingrese (7) ver ingresar datos \n";
    echo "Ingrese (8) para salir \n";
    $valor=trim(fgets(STDIN));
    if ($valor==1) { //informacion viaje
        $objViaje= new Viaje();
        echo "ingrese el id del viaje";
        $idviaje=trim(fgets(STDIN));
        $objViaje-> Buscar($idviaje);
        echo $objViaje; 
    }
    if ($valor==2) { //vender viaje
        echo "ingrese el destino:  \n";
        $destino=trim(fgets(STDIN));
        $objViaje= new Viaje();
        $colViajes=$objViaje->listar("vdestino="."'$destino'");
        if ($colViajes!=null) {
            $i=1;
            foreach ($colViajes as $viaje) {
                echo "|N°".$i."|[".$viaje."]\n";
                $i+=1;
            }
            echo "ingrese el id de viaje que desea comprar:  \n";
            $id=trim(fgets(STDIN));  
            if($objViaje-> Buscar($id)){
                $objPasajero=new Pasajero();
                $colObjPasajero=$objPasajero->listar($id);
                if (count($colObjPasajero)<$objViaje->getVcantmaxpasajeros()) {
                    $asientosDisponibles=$objViaje->getVcantmaxpasajeros()-count($colObjPasajero);
                    echo "los asientos disponibles son:".$asientosDisponibles."\n";
                    echo "Ingrese el solo Nombre: \n";
                    $eNombre=trim(fgets(STDIN));
                    echo "Ingrese el apellido: \n";
                    $eApellido=trim(fgets(STDIN));
                    echo "ingrese el DNI del pasajero: \n";
                    $eDni=trim(fgets(STDIN));
                    echo "ingrese el numero de telefono: \n";
                    $eTelefono=trim(fgets(STDIN));
                    $objPasajero=new Pasajero();
                    $objPasajero->cargar($eDni,$eNombre,$eApellido,$eTelefono,$objViaje);//$idviaje
                    $objPasajero->insertar();
                    echo "\n ***Se vendio el pasaje con exito*** :v \n";
                }
            }
            else {
                echo "error al ingresar el codigo \n";
            }
            
            
        }
        else {
            echo "NO hay lugares en ese viaje para vender \n";
        }
    }
    if ($valor==3) { //ingresar viaje
        $objEmpresa=new Empresa;
        $colEmpresa=$objEmpresa->listar();
        $i=1;
        foreach ($colEmpresa as $empresa) {
            $i=1;
            echo "|N°".$i."|[".$empresa."]\n";
            $i+=1;
        }
        echo "ingrese el id de la empresa \n";
        $idEmpresa=trim(fgets(STDIN));
        if ($objEmpresa->Buscar($idEmpresa)) {  
            $objViaje=new Viaje();
            echo "Ingrese el Destino: \n";
            $destino=trim(fgets(STDIN));
            echo "ingrese la cantidad maxima de pasajeros: \n";
            $cmax=trim(fgets(STDIN));
            $objResponsable=new Responsable();
            $colResponsable=$objResponsable->listar();
            foreach ($colResponsable as $responsable) {
                $i=1;
                echo "|N°".$i."|[".$responsable."]\n";
                    $i+=1;
            }
            echo "¿el responsable esta cargado en la lista? (s para si/n para no) \n";
            $resResponsable=trim(fgets(STDIN));
            if ($resResponsable=="s") {
                echo "ingrese el Número de empleado del responsable \n";
                $idEmpleado=trim(fgets(STDIN));
                if ($objResponsable->Buscar($idEmpleado)) {
                    $objViaje->cargar($destino,$cmax,$objEmpresa,$objResponsable,5);
                    $objViaje->insertar();
                    echo "viaje cargado con exito \n";
                }
                else {
                    echo "no se encontro al empleado con el id: ".$idEmpleado;
                }
                
            }
            else {
                echo "ingrese Nombre del responsable del viaje \n";
                $rnombre=trim(fgets(STDIN));
                echo "ingrese el apellido del responsable \n";
                $rapellido=trim(fgets(STDIN));
                echo "ingrese el numero de licencia del responsable \n";
                $rNlicencia=trim(fgets(STDIN));
                echo "ingrese el numero de empleado del responsable \n";
                $rNempleado=trim(fgets(STDIN));
                $objResponsable->cargar($rNempleado,$rNlicencia,$rnombre,$rapellido);
                $objResponsable->insertar();
                $objViaje->cargar($destino,$cmax,$objEmpresa,$objResponsable,0);
                $objViaje->insertar();
                echo "viaje cargado con exito \n";
            }
        }
        else {
            echo "no se encontro ninguna empresa con el id".$idEmpresa;
        }

        //
    }
    if ($valor==4) { //ver viajes de la empresa
        $objEmpresa=new Empresa();
        $colEmpresa=$objEmpresa->listar();
        $i=1;
        foreach ($colEmpresa as $empresa) {
            echo "|N°".$i."|[".$empresa."]\n";
            $i+=1;
        }
        echo "ingrese el id de la empresa: \n";
        $idEmpresa=trim(fgets(STDIN));
        $objEmpresa->buscar($idEmpresa);
        echo "los viajes disponibles de la empresa son: \n";
        $objViaje=new Viaje();
        $colViajes=$objViaje->listar();
        $i=1;
        foreach ($colViajes as $viajes) {
            echo "|N°".$i."|[".$viajes."]\n";
            $i+=1;
        }
        
    }
    if ($valor==5){// modificar
        echo "ingrese (1) para modificar un viaje \n";
        echo "ingrese (2) para modificar un pasajero \n";
        echo "ingrese (3) para modificar al resposable de un viaje\n";
        $resp=trim(fgets(STDIN));
        if ($resp==1) {
            $objViaje=new Viaje();
            $colViajes=$objViaje->listar();
            $i=1;
                foreach ($colViajes as $viajes) {
                echo "|N°".$i."|[".$viajes."]\n";
                $i+=1;
            }
            echo "ingrese id del viaje \n";
            $respId=trim(fgets(STDIN));
            if ($objViaje->Buscar($respId)) {
                echo "ingrese (1) para modificar un el destino \n";
                echo "ingrese (2) para modificar la cantidad maxima de pasajeros \n";
                echo "ingrese (3) para modificar al resposable de un viaje \n";
                echo "ingrese (4) para modificar al importe por boleto del viaje \n";
                $modViaje=trim(fgets(STDIN));
                switch ($modViaje) {
                    case '1':
                        echo "ingrese el nuevo destino \n";
                        $nDestino=trim(fgets(STDIN));
                        $objViaje->setVdestino($nDestino);
                        $objViaje->modificar();
                        break;
                    case "2":
                        echo "ingrese el nueva cantidad de pasajeros \n";
                        $nPasajeros=trim(fgets(STDIN));
                        $objViaje->setVdestino($nPasajeros);
                        $objViaje->modificar();
                        break;
                    case "3":
                        $objResponsable=new Responsable();
                        $colResponsable=$objResponsable->listar();
                        $i=1;
                        foreach ($colResponsable as $responsable) {
                        echo "|N°".$i."|[".$responsable."]\n";
                        $i+=1;
                        }
                        echo "ingrese el id del nuevo responsable \n";
                        $idResp=trim(fgets(STDIN));
                        $objResponsable->buscar($idResp);
                        $objViaje->modificar();
                        break;
                    case "4":
                        echo "ingrese el nuevo importe del viaje \n";
                        $nImporte=trim(fgets(STDIN));
                        $objViaje->setVdestino($nImporte);
                        $objViaje->modificar();
                        break;
                    default:
                        echo "no se ingreso una opcion valida \n";
                        break;
                }
            }
            else {
                echo "no se encontro el viaje que desea modificar \n";
            }
        }
        elseif ($resp==2) {
            echo "ingrese el DNI del pasajero\n";
            $dni=trim(fgets(STDIN));
            $objPasajero= new Pasajero();
            $objPasajero->Buscar($dni);
            echo "ingrese (1) para Nombre \n";
            echo "ingrese (2) para Apellido \n";
            echo "ingrese (3) para Telefono \n";
            echo "ingrese (4) para Viaje \n";

        }
        elseif ($resp==3) {
            echo "ingrese el numero de empleado del responsables\n";
        }
        else{
            echo "no ingreso una opcion valida \n";
        }
    }
    if ($valor==6){// eliminar un viaje o pasajero

    }
    if ($valor==7){ //ingresa datos
        $objEmpresa=new Empresa();
        echo "ingrese (1) para agregar una empresa \n";
        echo "ingrese (2) para agregar un nuevo responsable\n";
        echo "ingrese (3) para modificar al resposable de un viaje)\n";
        $inDato=trim(fgets(STDIN));
        if ($inDato==1) {
            echo "ingresar Nombre de la empresa \n";
            $nombreE=trim(fgets(STDIN));
            echo "ingresar Direccion de la empresa \n";
            $direccionE=trim(fgets(STDIN));
            $objEmpresa->cargar($nombreE,$direccionE);
            $objEmpresa->insertar();
            
        }
        if ($inDato==2) {
            # code...
        }
        if ($inDato==3) {
            # code...
        }

    }
} while ($valor==1 || $valor==2 ||$valor==3 ||$valor==4||$valor==5||$valor==6 ||$valor==7);

echo "gracias por su visita";



function agregarPasajero($id){
    $exito=$objViaje-> Buscar($id);
    if($exito){
        $colObjPasajero=$objViaje->getColObjPasajeros();
        if (count($colObjPasajero)<$objViaje->getCanMaxPasajeros()) {
            echo "Ingrese el solo Nombre: \n";
            $eNombre=trim(fgets(STDIN));
            echo "Ingrese el apellido: \n";
            $eApellido=trim(fgets(STDIN));
            echo "ingrese el DNI del pasajero: \n";
            $eDni=trim(fgets(STDIN));
            echo "ingrese el numero de telefono: \n";
            $eTelefono=trim(fgets(STDIN));
            $objPasajero=new Pasajero();
            $objPasajero->cargar($eDni,$eNombre,$eApellido,$eTelefono,$objViaje);//$idviaje
            $objPasajero->insertar();
        }
    }
    return $exito;
}

