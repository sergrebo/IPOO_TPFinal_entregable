<?php
include_once("Viaje.php");
include_once("Responsable.php");
include_once("Pasajero.php");
include_once("Empresa.php");
include_once("BaseDatos.php");

function contarPasajeros($objPasajero, $idViaje) {
    $cantidadPasajeros = count($objPasajero->listar("idviaje = " . $idViaje));
    return $cantidadPasajeros;
}

function eliminarViajeYPasajeros($objViaje) {
    if ($objViaje != null) {
        $cantidadPasajeros = contarPasajeros(new Pasajero(), $objViaje->getIdviaje());

        if ($cantidadPasajeros == 0) {
            $objViaje->eliminar();
                echo "\n--------------------------------------\n";
                echo "| Viaje y pasajeros borrados con éxito |\n";
                echo "--------------------------------------\n\n";
        } else {
            echo "\n!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!\n";
            echo "!! Hay " .$cantidadPasajeros. " pasajero/s en el viaje que desea eliminar !!\n";
            echo "!!       ¿Desea eliminar el/los pasajero/s del viaje?       !!\n";
            echo "!!         (Ingrese 's' para sí / 'n' para no)              !!\n";
            echo "!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!\n";
            $rtaBorrarPasajeros = trim(fgets(STDIN));

            if ($rtaBorrarPasajeros == 's') {
                $objPasajero = new Pasajero();
                $pasajerosDelViaje = $objPasajero->listar("idviaje = " . $objViaje->getIdviaje());

                foreach ($pasajerosDelViaje as $pasajero) {
                    $pasajero->eliminar();
                }

                $objViaje->eliminar();
                echo "\n--------------------------------------\n";
                echo "| Viaje y pasajeros borrados con éxito |\n";
                echo "--------------------------------------\n\n";
            } else {
                echo "\n------------------------------------\n";
                echo "|Operación de eliminación cancelada|\n";
                echo "------------------------------------\n\n";
            }
        }
    } else {
        echo "\n\nEl viaje que desea eliminar ya no se encuentra en nuestra base de datos.\n\n";
    }
}

function eliminarResponsable($objResponsable) {
    if ($objResponsable != null) {
        $objViaje = new Viaje();
        $viajesDelResponsable = $objViaje->listar("rnumeroempleado = " . $objResponsable->getRnumeroempleado());
        echo "     \n--------------------------------------\n";
        echo "     ||   Viajes asociados al responsable: ||\n";
        echo "     --------------------------------------\n\n";
        mostrarColeccion($viajesDelResponsable);

        echo "\n\n¿Desea eliminar al responsable y todos sus viajes asociados? (Ingrese 's' para sí / 'n' para no)\n\n";
        $confirmacion = trim(fgets(STDIN));

        if ($confirmacion == 's') {
            foreach ($viajesDelResponsable as $viaje) {
                eliminarViajeYPasajeros($viaje);
            }

            if ($objResponsable->eliminar()) {
                echo "     !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!\n";
                echo "     !! Responsable y viajes asociados borrados con éxito !!\n";
                echo "     !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!\n\n";
            } else {
                echo "Error al eliminar responsable: " . $objResponsable->getMensajeOperacion() . "\n \n";
            }
        } else {
            echo "Operación de eliminación cancelada.\n\n";
        }
    } else {
        echo "El responsable que desea eliminar no se encuentra en nuestra base de datos.\n\n";
    }
}

function mostrarColeccion($coleccion) {
    $i = 1;
    echo "           ****************************************\n";
    foreach ($coleccion as $elemento) {
        echo "           |N°".$i."|".$elemento."\n";
        $i++;
    }
    echo "           ****************************************\n";
}

function listarSeleccionar($clase, $mensaje) {
    $objResultado = null;
    $obj = new $clase();
    $colElementos = $obj->listar();
    mostrarColeccion($colElementos);
    echo $mensaje;
    $respId = trim(fgets(STDIN));
    
    if ($obj->buscar($respId)) {
        $objResultado = $obj;
    } else {
        echo "No se encontró el elemento con ID " . $respId . ".\n\n";
    }

    return $objResultado;
}

function modificarPasajero($objPasajero) {
    echo "     [--------------------------------------------------]\n";
    echo "     [***    Ingrese (1) para modificar el nombre    ***]\n";
    echo "     [***    Ingrese (2) para modificar el apellido  ***]\n";
    echo "     [***    Ingrese (3) para modificar el teléfono  ***]\n";
    echo "     [***    Ingrese (4) para modificar el viaje     ***]\n";
    echo "     [--------------------------------------------------]\n";
    echo "Opcion elejida: ";
    $modPasajero = trim(fgets(STDIN));
    switch ($modPasajero) {
        case '1':
            echo "Ingrese el nuevo nombre: ";
            $nNombre = trim(fgets(STDIN));
            $objPasajero->setPnombre($nNombre);
            if ($objPasajero->modificar()) {
                echo "\n-------------- ------------\n";
                echo "| Dato modificado con exito |\n";
                echo "-----------------------------\n\n";
            }
            else {
                echo "Algo salio mal :´V ";
            }
            break;
        case '2':
            echo "Ingrese el nuevo apellido: ";
            $nApellido = trim(fgets(STDIN));
            $objPasajero->setPapellido($nApellido);
            if ($objPasajero->modificar()) {
                echo "\n-------------- ------------\n";
                echo "| Dato modificado con exito |\n";
                echo "-----------------------------\n\n";
            }
            else {
                echo "Algo salio mal :´V ";
            }
            break;
        case '3':
            echo "Ingrese el nuevo teléfono: ";
            $nTelefono = trim(fgets(STDIN));
            $objPasajero->setPtelefono($nTelefono);
            if ($objPasajero->modificar()) {
                echo "\n-------------- ------------\n";
                echo "| Dato modificado con exito |\n";
                echo "-----------------------------\n\n";
            }
            else {
                echo "Algo salio mal :´V ";
            }
            break;
        case '4':
            $objViaje = listarSeleccionar('Viaje', "Ingrese el ID del nuevo viaje: ");
            if ($objViaje != null) {
                $objPasajero->setObjViaje($objViaje);
                $objPasajero->modificar();
                echo "\n";
                echo "------------------------------------\n";
                echo "|  Se actualizo el dato con éxito  |\n";
                echo "------------------------------------\n\n";
            }
            else{
                echo "Algo salio mal :´V ";
            }
            break;        
        default:
            echo "Lo sentimos, no se ingresó una opción válida.\n\n";
            break;
    }
}

function modificarResponsable($objResponsable) {
    echo "     [----------------------------------------------------------]\n";
    echo "     [***  Ingrese (1) para modificar el número de licencia  ***]\n";
    echo "     [***  Ingrese (2) para modificar el nombre              ***]\n";
    echo "     [***  Ingrese (3) para modificar el apellido            ***]\n";
    echo "     [----------------------------------------------------------]\n";
    echo "Opcion elejida: ";
    $resp = trim(fgets(STDIN));
    switch ($resp) {
        case '1':
            echo "Ingrese el nuevo número de licencia: ";
            $nNumLicencia = trim(fgets(STDIN));
            $objResponsable->setRnumerolicencia($nNumLicencia);
            if ($objResponsable->modificar()) {
                echo "\n-------------- ------------\n";
                echo "| Dato modificado con exito |\n";
                echo "-----------------------------\n\n";
            }
            break;
        case '2':
            echo "Ingrese el nuevo nombre: ";
            $nNombre = trim(fgets(STDIN));
            $objResponsable->setRnombre($nNombre);
            if ($objResponsable->modificar()) {
                echo "\n-------------- ------------\n";
                echo "| Dato modificado con exito |\n";
                echo "-----------------------------\n\n";
            }
            break;
        case '3':
            echo "Ingrese el nuevo apellido: ";
            $nApellido = trim(fgets(STDIN));
            $objResponsable->setRapellido($nApellido);
            if ($objResponsable->modificar()) {
                echo "\n-------------- ------------\n";
                echo "| Dato modificado con exito |\n";
                echo "-----------------------------\n\n";
            }
            break;
        default:
            echo "No se ingresó una opción válida.\n";
            break;
    }
}

function modificarDatos() {
    echo "     [------------------------------------------------------------]\n";
    echo "     [**  Ingrese (1) para modificar un viaje                   **]\n";
    echo "     [**  Ingrese (2) para modificar un pasajero                **]\n";
    echo "     [**  Ingrese (3) para modificar al resposable de un viaje  **]\n";
    echo "     [------------------------------------------------------------]\n";
    echo "     Opcion elejida: ";
    $resp = trim(fgets(STDIN));
    switch ($resp) {
        case '1':
            $objViaje = listarSeleccionar('Viaje', "Ingrese el ID del viaje: ");
            if ($objViaje != null) {
                echo "     [------------------------------------------------------------------]\n";
                echo "     [**  Ingrese (1) para modificar el destino                       **]\n";
                echo "     [**  Ingrese (2) para modificar la cantidad máxima de pasajeros  **]\n";
                echo "     [**  Ingrese (3) para modificar al responsable del viaje         **]\n";
                echo "     [**  Ingrese (4) para modificar el importe por boleto del viaje  **]\n";
                echo "     [------------------------------------------------------------------]\n";
                echo "     Opcion elejida: ";
                $modViaje = trim(fgets(STDIN));
                switch ($modViaje) {
                    case '1':
                        echo "Ingrese el nuevo destino: ";
                        $nDestino = trim(fgets(STDIN));
                        $objViaje->setVdestino($nDestino);
                        if ($objViaje->modificar()) {
                            echo "\n-------------- ------------\n";
                            echo "| Dato modificado con exito |\n";
                            echo "-----------------------------\n\n";
                        }
                        break;
                    case "2":
                        echo "Ingrese la nueva cantidad de pasajeros: ";
                        $nPasajeros = trim(fgets(STDIN));
                        $objViaje->setVcantmaxpasajeros($nPasajeros);
                        if ($objViaje->modificar()) {
                            echo "\n-------------- ------------\n";
                            echo "| Dato modificado con exito |\n";
                            echo "-----------------------------\n\n";
                        }
                        break;
                    case "3":
                        $objResponsable = listarSeleccionar('Responsable', "Ingrese el ID del nuevo responsable: ");
                        if ($objResponsable != null) {
                            $objViaje->setObjResponsable($objResponsable);
                            if ($objViaje->modificar()) {
                                echo "\n-------------- ------------\n";
                                echo "| Dato modificado con exito |\n";
                                echo "-----------------------------\n\n";
                            }
                        }
                        break;
                    case "4":
                        echo "Ingrese el nuevo importe del viaje: ";
                        $nImporte = trim(fgets(STDIN));
                        $objViaje->setVimporte($nImporte);
                        if ($objViaje->modificar()) {
                            echo "\n-------------- ------------\n";
                            echo "| Dato modificado con exito |\n";
                            echo "-----------------------------\n\n";
                        }
                        break;
                    default:
                        echo "No se ingreso una opcion válida.\n\n";
                        break;
                }
            }
            else {
                echo "No se encontro el viaje que desea modificar.\n\n";
            }
            break;
        case '2':
            $objPasajero = listarSeleccionar('Pasajero', 'Ingrese el DNI del pasajero: ');
            if ($objPasajero) {
                modificarPasajero($objPasajero);
            }
            else {
                echo "No se encontro el pasajero que desea modificar.\n\n";
            }
            break;
        case '3':
            $objResponsable = listarSeleccionar('Responsable', 'Ingrese el número del empleado responsable: ');
            if ($objResponsable) {
                modificarResponsable($objResponsable);
            }
            else {
                echo "No se encontro el responsable que desea modificar.\n\n";
            }
            break;
        default:
            echo "Opción inválida.\n\n";
            break;
    }
}

echo "\n[============================== Bienvenido ==============================]
     \n           [ Ingrese una de las opciones deseadas ] \n";

do {
    echo "\n";
    echo "[******    Ingrese (1) ver informacion de un viaje                 ******] \n";
    echo "[******    Ingrese (2) para Vender pasaje                          ******] \n";
    echo "[******    Ingrese (3) ingresar un viaje                           ******] \n" ;
    echo "[******    Ingrese (4) ver viajes de una empresa                   ******] \n";
    echo "[******    Ingrese (5) ver modificar viaje, pasajero o responsable ******] \n";
    echo "[******    Ingrese (6) ver eliminar un viaje o pasajero            ******] \n";
    echo "[******    Ingrese (7) ver ingresar datos                          ******] \n";
    echo "[******    Ingrese (8) para salir                                  ******] \n";
    $valor = trim(fgets(STDIN));

    switch ($valor) {
        case '1':
            // Información de un viaje.
            $objViaje = listarSeleccionar("Viaje", "Ingrese el ID del viaje: ");
            if ($objViaje != null) {
                echo $objViaje;
            }
            else {
                echo "Viaje no encontrado.\n";
            }
            break;
        case '2':
                $objViaje = new Viaje();
                $colViajes = $objViaje->listar();
                $objPasajero = new Pasajero();
    
                echo "Destinos disponibles:\n";
                $colViajesDisponible = [];
                foreach ($colViajes as $viaje) {
                    $idviaje = $viaje->getIdviaje();
                    $colPasajerosViaje = $objPasajero->listar('idviaje ='. $idviaje);
                    $cantAsientosDisponible = $viaje->getVcantmaxpasajeros() - count($colPasajerosViaje);
                    if ($cantAsientosDisponible > 0) {
                        echo $viaje;
                        echo "     [-----------------------------]\n";
                        echo "     ! Hay ". ($cantAsientosDisponible) . " asientos disponibles !\n";
                        echo "     [-----------------------------]\n";
                        array_push($colViajesDisponible, $viaje);
                    }
                }
    
                echo "Ingrese el destino:  \n";
                $destino = trim(fgets(STDIN));
                $colViajesDestino = [];
                foreach ($colViajesDisponible as $viaje) {
                    if ($viaje->getVdestino() == $destino) {
                        $colViajesDestino[] = $viaje;
                    }
                }
                mostrarColeccion($colViajesDestino);
                echo "Ingrese el id de viaje que desea comprar: ";
                $id = trim(fgets(STDIN));
                if ($objViaje->Buscar($id)) {
                    echo "Ingrese el nombre: ";
                    $nombre = trim(fgets(STDIN));
                    echo "Ingrese el apellido: ";
                    $apellido = trim(fgets(STDIN));
                    echo "Ingrese el DNI del pasajero: ";
                    $dni = trim(fgets(STDIN));
                    echo "Ingrese el numero de telefono: ";
                    $telefono = trim(fgets(STDIN));
                    $objPasajero->cargar($dni, $nombre, $apellido, $telefono, $objViaje);
                    if ($objPasajero->insertar()) {
                        echo "     [---------------------------------------]\n";
                        echo "     [  Se vendio el pasaje con éxito :v ] \n";
                        echo "     [---------------------------------------]\n";
                    }
                    else {
                        echo "No se cargo correctamente. Intente nuevamente.\n";
                    }
                }
                else {
                    echo "Error al ingresar el id: " . $id . "\n";
                }
                break;
        case '3':
            $objEmpresa = new Empresa();
            $colEmpresa = $objEmpresa->listar();
            mostrarColeccion($colEmpresa);
            echo "Ingrese el ID de la empresa: ";
            echo "id: ";
            $idEmpresa = trim(fgets(STDIN));
            if ($objEmpresa->Buscar($idEmpresa)) {  
                $objViaje = new Viaje();
                echo "Ingrese el destino: ";
                $destino = trim(fgets(STDIN));
                echo "Ingrese la cantidad máxima de pasajeros: ";
                $cmax = trim(fgets(STDIN));
                echo "Ingrese el importe del viaje: ";
                $importe = trim(fgets(STDIN));
                $objResponsable = new Responsable();
                $colResponsable = $objResponsable->listar();
                mostrarColeccion($colResponsable);
                echo "     !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!\n";
                echo "     ! ¿El responsable está cargado en la lista? (s para sí / n para no) !\n";
                echo "     !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!\n";
                $resResponsable = trim(fgets(STDIN));
                if ($resResponsable == "s") {
                    echo "Ingrese el número del empleado responsable: ";
                    $idEmpleado = trim(fgets(STDIN));
                    if ($objResponsable->Buscar($idEmpleado)) {
                        $objViaje->cargar($destino, $cmax, $objEmpresa, $objResponsable, $importe);
                        if ($objViaje->insertar()) {
                            echo "\n";
                            echo "        ---------------------------\n";
                            echo "       |  Viaje cargado con éxito  |\n";
                            echo "        ---------------------------\n\n";
                        } else {
                            echo "Error al insertar el viaje: " . $objViaje->getMensajeOperacion() . "\n\n";
                        }
                    } else {
                        echo "No se encontró al empleado con el ID: " . $idEmpleado . ".\n\n";
                    }
                } else {
                    echo "Ingrese el nombre del responsable del viaje: ";
                    $rnombre = trim(fgets(STDIN));
                    echo "Ingrese el apellido del responsable: ";
                    $rapellido = trim(fgets(STDIN));
                    echo "Ingrese el número de licencia del responsable: ";
                    $rNlicencia = trim(fgets(STDIN));
                    $objResponsable->cargar($rNlicencia, $rnombre, $rapellido);
                    if ($objResponsable->insertar()) {
                        $objViaje->cargar($destino, $cmax, $objEmpresa, $objResponsable, $importe);
                        if ($objViaje->insertar()) {
                            echo "\n";
                            echo "        ---------------------------\n";
                            echo "       |  Viaje cargado con éxito  |\n";
                            echo "        ---------------------------\n\n";;
                        } else {
                            echo "Error al insertar el viaje: " . $objViaje->getMensajeOperacion() . "\n\n";
                        }
                    } else {
                        echo "Error al insertar el responsable: " . $objResponsable->getMensajeOperacion() . "\n\n";
                    }
                }
            } else {
                echo "No se encontró ninguna empresa con el ID " . $idEmpresa . ".\n\n";
            }
            break;
        case '4':
            $objEmpresa = new Empresa();
            $colEmpresa = $objEmpresa->listar();
            mostrarColeccion($colEmpresa);
            echo "Ingrese el id de la empresa: ";
            $idEmpresa = trim(fgets(STDIN));
            if ($objEmpresa->buscar($idEmpresa)) {
                echo "Los viajes disponibles de la empresa son: \n";
                $objViaje = new Viaje();
                $colViajes = $objViaje->listar("idempresa=" . $idEmpresa);
                mostrarColeccion($colViajes);
            } else {
                echo "No se encontró la empresa con ID $idEmpresa.\n\n";
            }
            break;
        case '5':
            modificarDatos();
            break;
        case '6':
            echo "     [------------------------------------------------]\n";
            echo "     [**  Ingrese (1) para eliminar un viaje        **]\n";
            echo "     [**  Ingrese (2) para eliminar un pasajero     **]\n";
            echo "     [**  Ingrese (3) para eliminar un responsable  **]\n";
            echo "     [------------------------------------------------]\n";
            $opcionEliminar = trim(fgets(STDIN));
            switch ($opcionEliminar) {
                case '1':
                    $objViaje = listarSeleccionar('Viaje', "Ingrese el ID del viaje que desea eliminar: ");
                    eliminarViajeYPasajeros($objViaje);
                    break;
                case '2':
                    $objPasajero = listarSeleccionar('Pasajero', "Ingrese el DNI del pasajero que desea eliminar: ");
                    if ($objPasajero != null) {
                        if ($objPasajero->eliminar()) {
                            echo "    \n°---------------------------°\n";
                            echo "    | Pasajero borrado con éxito |\n";
                            echo "    °----------------------------°\n\n";
                        }
                    }
                    else {
                        echo "El pasajero que desea eliminar ya no se encuentra en nuestra base de datos.\n\n";
                    }
                    break;
                case '3':
                    $objResponsable = listarSeleccionar('Responsable', "Ingrese el número de empleado del responsable que desea eliminar: ");
                    eliminarResponsable($objResponsable);
                    break;
                default:
                    echo "Opción inválida.\n\n";
                    break;
            }
            break;
        case '7':
            echo "     [----------------------------------------------------]\n";
            echo "     [**  Ingrese (1) para agregar una empresa          **]\n";
            echo "     [**  Ingrese (2) para agregar un nuevo responsable **]\n";
            echo "     [----------------------------------------------------]\n";
            echo "     Opcion elejida: ";
            $opciionAgrega = trim(fgets(STDIN));
            switch ($opciionAgrega) {
                case '1':
                    echo "Ingresar nombre de la empresa: ";
                    $nombreE = trim(fgets(STDIN));
                    echo "Ingresar dirección de la empresa: ";
                    $direccionE = trim(fgets(STDIN));
                    $objEmpresa = new Empresa();
                    $objEmpresa->cargar($nombreE, $direccionE);
                    if ($objEmpresa->insertar()) {
                        echo "    \n°------------------------------°\n";
                        echo "    | Empresa agregada exitosamente |\n";
                        echo "    °-------------------------------°\n\n";
                    } else {
                        echo "Error al agregar la empresa.\n\n";
                    }
                    break;
                case '2':
                    echo "Ingrese el número de licencia del empleado responsable: ";
                    $numLicencia = trim(fgets(STDIN));
                    echo "Ingrese el nombre: ";
                    $nombre = trim(fgets(STDIN));
                    echo "Ingrese el apellido: ";
                    $apellido = trim(fgets(STDIN));
                    $objResponsable = new Responsable();
                    $objResponsable->cargar($numLicencia, $nombre, $apellido);
                    if ($objResponsable->insertar()) {
                        echo "    \n°---------------------------------°\n";
                        echo "    | Responsable agregada exitosamente |\n";
                        echo "    °-----------------------------------°\n\n";
                    }
                    else {
                        echo "Error al agregar al responsable.\n\n";
                    }
                    break;
                default:
                    echo "Opción inválida.\n\n";
                    break;
            }
            break;
        case '8':
            echo " ____________________________________________________\n";
            echo "!                                                    !\n";
            echo "!              Gracias por su visita                 !\n";
            echo "!____________________________________________________!\n";
            break;
        default:
            echo "Opción inválida.\n\n";
            break;
    }
} while ($valor != '8');
?>