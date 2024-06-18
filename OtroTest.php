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
            echo "Viaje borrado con éxito.\n";
        } else {
            echo "Hay " . $cantidadPasajeros . " pasajeros en el viaje que desea eliminar.\n";
            echo "¿Desea eliminar los pasajeros del viaje? (Ingrese 's' para sí / 'n' para no)\n";
            $rtaBorrarPasajeros = trim(fgets(STDIN));

            if ($rtaBorrarPasajeros == 's') {
                $objPasajero = new Pasajero();
                $pasajerosDelViaje = $objPasajero->listar("idviaje = " . $objViaje->getIdviaje());

                foreach ($pasajerosDelViaje as $pasajero) {
                    $pasajero->eliminar();
                }

                $objViaje->eliminar();
                echo "Viaje y pasajeros borrados con éxito.\n";
            } else {
                echo "Operación de eliminación cancelada.\n";
            }
        }
    } else {
        echo "El viaje que desea eliminar ya no se encuentra en nuestra base de datos.\n";
    }
}

function eliminarResponsable($objResponsable) {
    if ($objResponsable != null) {
        $objViaje = new Viaje();
        $viajesDelResponsable = $objViaje->listar("rnumeroempleado = " . $objResponsable->getRnumeroempleado());

        echo "Viajes asociados al responsable:\n";
        mostrarColeccion($viajesDelResponsable);

        echo "¿Desea eliminar al responsable y todos sus viajes asociados? (Ingrese 's' para sí / 'n' para no)\n";
        $confirmacion = trim(fgets(STDIN));

        if ($confirmacion == 's') {
            foreach ($viajesDelResponsable as $viaje) {
                eliminarViajeYPasajeros($viaje);
            }

            if ($objResponsable->eliminar()) {
                echo "Responsable y viajes asociados borrados con éxito.\n";
            } else {
                echo "Error al eliminar responsable: " . $objResponsable->getMensajeOperacion() . "\n";
            }
        } else {
            echo "Operación de eliminación cancelada.\n";
        }
    } else {
        echo "El responsable que desea eliminar no se encuentra en nuestra base de datos.\n";
    }
}

/*
function contarPasajeros($idViaje) {
    $objBase = new BaseDatos();
    $cantidad = 0;

    if ($objBase->Iniciar()) {
        $consulta = "SELECT COUNT(*) as cantidad FROM pasajero WHERE idviaje = " . $idViaje;
        if ($objBase->Ejecutar($consulta)) {
            $resultado = $objBase->Registro();
            if ($resultado) {
                $cantidad = $resultado['cantidad'];
            } else {
                echo "Error en la consulta: " . $objBase->getError();
            }
        } else {
            echo "Error en la consulta: " . $objBase->getError();
        }
    } else {
        echo "Error al iniciar la base de datos: " . $objBase->getError();
    }

    return $cantidad;
}
*/

function mostrarColeccion($coleccion) {
    $i = 1;
    echo "****************************************************************************************\n";
    foreach ($coleccion as $elemento) {
        echo "|N°".$i."|[".$elemento."]\n";
        $i++;
    }
    echo "****************************************************************************************\n";
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
        echo "No se encontró el elemento con ID " . $respId . ".\n";
    }

    return $objResultado;
}

function modificarPasajero($objPasajero) {
    echo "Ingrese (1) para modificar el nombre.\n";
    echo "Ingrese (2) para modificar el apellido.\n";
    echo "Ingrese (3) para modificar el teléfono.\n";
    echo "Ingrese (4) para modificar el viaje.\n";
    $modPasajero = trim(fgets(STDIN));
    switch ($modPasajero) {
        case '1':
            echo "Ingrese el nuevo nombre: ";
            $nNombre = trim(fgets(STDIN));
            $objPasajero->setPnombre($nNombre);
            $objPasajero->modificar();
            break;
        case '2':
            echo "Ingrese el nuevo apellido: ";
            $nApellido = trim(fgets(STDIN));
            $objPasajero->setPapellido($nApellido);
            $objPasajero->modificar();
            break;
        case '3':
            echo "Ingrese el nuevo teléfono: ";
            $nTelefono = trim(fgets(STDIN));
            $objPasajero->setPtelefono($nTelefono);
            $objPasajero->modificar();
            break;
        case '4':
            $objViaje = listarSeleccionar('Viaje', "Ingrese el ID del nuevo viaje: ");
            if ($objViaje != null) {
                $objPasajero->setObjViaje($objViaje);
                $objPasajero->modificar();
            }
            break;        
        default:
            echo "No se ingresó una opción válida.\n";
            break;
    }
}

function modificarResponsable($objResponsable) {
    echo "Ingrese (1) para modificar el número de licencia.\n";
    echo "Ingrese (2) para modificar el nombre.\n";
    echo "Ingrese (3) para modificar el apellido.\n";
    $resp = trim(fgets(STDIN));
    switch ($resp) {
        case '1':
            echo "Ingrese el nuevo número de licencia: ";
            $nNumLicencia = trim(fgets(STDIN));
            $objResponsable->setRnumerolicencia($nNumLicencia);
            $objResponsable->modificar();
            break;
        case '2':
            echo "Ingrese el nuevo nombre: ";
            $nNombre = trim(fgets(STDIN));
            $objResponsable->setRnombre($nNombre);
            $objResponsable->modificar();
            break;
        case '3':
            echo "Ingrese el nuevo apellido: ";
            $nApellido = trim(fgets(STDIN));
            $objResponsable->setRapellido($nApellido);
            $objResponsable->modificar();
            break;
        default:
            echo "No se ingresó una opción válida.\n";
            break;
    }
}

function modificarDatos() {
    echo "Ingrese (1) para modificar un viaje.\n";
    echo "Ingrese (2) para modificar un pasajero.\n";
    echo "Ingrese (3) para modificar al resposable de un viaje.\n";
    $resp = trim(fgets(STDIN));
    switch ($resp) {
        case '1':
            $objViaje = listarSeleccionar('Viaje', "Ingrese el ID del viaje: ");
            if ($objViaje != null) {
                echo "Ingrese (1) para modificar el destino.\n";
                echo "Ingrese (2) para modificar la cantidad máxima de pasajeros.\n";
                echo "Ingrese (3) para modificar al responsable del viaje.\n";
                echo "Ingrese (4) para modificar el importe por boleto del viaje.\n";
                $modViaje = trim(fgets(STDIN));
                switch ($modViaje) {
                    case '1':
                        echo "Ingrese el nuevo destino: ";
                        $nDestino = trim(fgets(STDIN));
                        $objViaje->setVdestino($nDestino);
                        $objViaje->modificar();
                        break;
                    case "2":
                        echo "Ingrese la nueva cantidad de pasajeros: ";
                        $nPasajeros = trim(fgets(STDIN));
                        $objViaje->setVcantmaxpasajeros($nPasajeros);
                        $objViaje->modificar();
                        break;
                    case "3":
                        $objResponsable = listarSeleccionar('Responsable', "Ingrese el ID del nuevo responsable: ");
                        if ($objResponsable != null) {
                            $objViaje->setObjResponsable($objResponsable);
                            $objViaje->modificar();
                        }
                        break;
                    case "4":
                        echo "Ingrese el nuevo importe del viaje: ";
                        $nImporte = trim(fgets(STDIN));
                        $objViaje->setVimporte($nImporte);
                        $objViaje->modificar();
                        break;
                    default:
                        echo "No se ingreso una opcion válida.\n";
                        break;
                }
            }
            else {
                echo "No se encontro el viaje que desea modificar.\n";
            }
            break;
        case '2':
            $objPasajero = listarSeleccionar('Pasajero', 'Ingrese el DNI del pasajero: ');
            if ($objPasajero) {
                modificarPasajero($objPasajero);
            }
            else {
                echo "No se encontro el pasajero que desea modificar.\n";
            }
            break;
        case '3':
            $objResponsable = listarSeleccionar('Responsable', 'Ingrese el número del empleado responsable: ');
            if ($objResponsable) {
                modificarResponsable($objResponsable);
            }
            else {
                echo "No se encontro el responsable que desea modificar.\n";
            }
            break;
        default:
            echo "Opción inválida.\n";
            break;
    }
}

echo "====================== Bienvenido ======================\n";
echo "[ Ingrese una de las opciones deseadas ]\n";

do {
    echo "Ingrese (1) para ver información de un viaje.\n";
    echo "Ingrese (2) para vender pasaje.\n";
    echo "Ingrese (3) para ingresar un viaje.\n";
    echo "Ingrese (4) para ver viajes de la empresa.\n";
    echo "Ingrese (5) para modificar viaje, pasajero o responsable del viaje.\n";
    echo "Ingrese (6) para eliminar un viaje o un pasajero.\n";
    echo "Ingrese (7) para ingresar empresa o responsable.\n";
    echo "Ingrese (8) para salir.\n";
    $valor = trim(fgets(STDIN));

    switch ($valor) {
        case '1':
            // Información de un viaje.
            $objViaje = new Viaje();
            $colViajes = $objViaje->listar();
            mostrarColeccion($colViajes);
            echo "Ingrese el ID del viaje: ";
            $idviaje = trim(fgets(STDIN));
            if ($objViaje->Buscar($idviaje)) {
                echo $objViaje;
            } else {
                echo "Viaje no encontrado.\n";
            }
            break;
        case '2':
            echo "Ingrese el destino:  \n";
            $destino = trim(fgets(STDIN));
            $objViaje = new Viaje();
            $colViajes = $objViaje->listar("vdestino="."'$destino'");
            if ($colViajes != null) {
                mostrarColeccion($colViajes);
                echo "Ingrese el id de viaje que desea comprar: ";
                $id = trim(fgets(STDIN));  
                if ($objViaje->Buscar($id)) {
                    $cantidadPasajeros = contarPasajeros(new Pasajero(), $id);
                    if ($cantidadPasajeros < $objViaje->getVcantmaxpasajeros()) {
                        $asientosDisponibles = $objViaje->getVcantmaxpasajeros() - $cantidadPasajeros;
                        echo "Hay " . $asientosDisponibles . " asientos disponibles.\n";
                        echo "Ingrese el nombre: ";
                        $nombre = trim(fgets(STDIN));
                        echo "Ingrese el apellido: ";
                        $apellido = trim(fgets(STDIN));
                        echo "Ingrese el DNI del pasajero: ";
                        $dni = trim(fgets(STDIN));
                        echo "Ingrese el numero de telefono: ";
                        $telefono = trim(fgets(STDIN));
                        $objPasajero = new Pasajero();
                        $objPasajero->cargar($dni, $nombre, $apellido, $telefono, $objViaje);
                        $objPasajero->insertar();
                        echo "\n *** Se vendio el pasaje con éxito *** :v \n";
                    }
                    else {
                        echo "No hay lugares en ese viaje para vender.\n";
                    }
                }
                else {
                    echo "Error al ingresar el codigo.\n";
                }
            }
            else {
                echo "No hay lugares en ese viaje para vender.\n";
            }
            break;
        case '3':
            $objEmpresa = new Empresa();
            $colEmpresa = $objEmpresa->listar();
            mostrarColeccion($colEmpresa);
            echo "Ingrese el ID de la empresa: ";
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
                echo "¿El responsable está cargado en la lista? (s para sí / n para no)\n";
                $resResponsable = trim(fgets(STDIN));
                if ($resResponsable == "s") {
                    echo "Ingrese el número del empleado responsable: ";
                    $idEmpleado = trim(fgets(STDIN));
                    if ($objResponsable->Buscar($idEmpleado)) {
                        $objViaje->cargar($destino, $cmax, $objEmpresa, $objResponsable, $importe);
                        if ($objViaje->insertar()) {
                            echo "Viaje cargado con éxito.\n";
                        } else {
                            echo "Error al insertar el viaje: " . $objViaje->getMensajeOperacion() . "\n";
                        }
                    } else {
                        echo "No se encontró al empleado con el ID: " . $idEmpleado . ".\n";
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
                            echo "Viaje cargado con éxito.\n";
                        } else {
                            echo "Error al insertar el viaje: " . $objViaje->getMensajeOperacion() . "\n";
                        }
                    } else {
                        echo "Error al insertar el responsable: " . $objResponsable->getMensajeOperacion() . "\n";
                    }
                }
            } else {
                echo "No se encontró ninguna empresa con el ID " . $idEmpresa . ".\n";
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
                echo "No se encontró la empresa con ID $idEmpresa.\n";
            }
            break;
        case '5':
            modificarDatos();
            break;
        case '6':
            // Elimina un viaje o un pasajero.
            echo "Ingrese (1) para eliminar un viaje.\n";
            echo "Ingrese (2) para eliminar un pasajero.\n";
            echo "Ingrese (3) para eliminar un responsable.\n";
            $opcionEliminar = trim(fgets(STDIN));
            switch ($opcionEliminar) {
                case '1':
                    $objViaje = listarSeleccionar('Viaje', "Ingrese el ID del viaje que desea eliminar: ");
                    /*
                    if ($objViaje != null) {
                        //$cantidadPasajeros = contarPasajeros($objViaje->getIdViaje());
                        $cantidadPasajeros = contarPasajeros(new Pasajero(), $objViaje->getIdviaje());
                        if ($cantidadPasajeros == 0) {
                            $objViaje->eliminar();
                            echo "Viaje borrado con éxito.\n";
                        } else {
                            echo "Hay " . $cantidadPasajeros . " pasajeros en el viaje que desea eliminar.\n";
                            echo "¿Desea eliminar los pasajeros del viaje? (Ingrese 's' para sí / 'n' para no)\n";
                            $rtaBorrarPasajeros = trim(fgets(STDIN));
                            if ($rtaBorrarPasajeros == 's') {
                                $objPasajero = new Pasajero();
                                $pasajerosDelViaje = $objPasajero->listar("idviaje = " . $objViaje->getIdviaje());
                                foreach ($pasajerosDelViaje as $pasajero) {
                                    $pasajero->eliminar();
                                }
                                $objViaje->eliminar();
                                echo "Viaje y pasajeros borrados con éxito.\n";
                            } else {
                                echo "No se desea borrar pasajeros, por ende no se puede eliminar el viaje.\n";
                            }
                        }
                    } else {
                        echo "El viaje que desea eliminar ya no se encuentra en nuestra base de datos.\n";
                    }*/
                    eliminarViajeYPasajeros($objViaje);
                    break;
                case '2':
                    $objPasajero = listarSeleccionar('Pasajero', "Ingrese el DNI del pasajero que desea eliminar: ");
                    if ($objPasajero != null) {
                        if ($objPasajero->eliminar()) {
                            echo "Pasajero borrado con éxito.\n";
                        }
                    }
                    else {
                        echo "El pasajero que desea eliminar ya no se encuentra en nuestra base de datos.\n";
                    }
                    break;
                case '3':
                    $objResponsable = listarSeleccionar('Responsable', "Ingrese el número de empleado del responsable que desea eliminar: ");
                    /*
                    if ($objResponsable != null) {
                        if ($objResponsable->eliminar()) {
                            echo "Responsable borrado con éxito.\n";
                        }
                        else {
                            echo $objResponsable->getMensajeOperacion();
                        }
                    }
                    else {
                        echo "El responsable del viaje que desea eliminar ya no se encuentra en nuestra base de datos.\n";
                    }*/
                    eliminarResponsable($objResponsable);
                    break;
                default:
                    echo "Opción inválida.\n";
                    break;
            }
            break;
        case '7':
            echo "Ingrese (1) para agregar una empresa.\n";
            echo "Ingrese (2) para agregar un nuevo responsable.\n";
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
                        echo "Empresa agregada exitosamente.\n";
                    } else {
                        echo "Error al agregar la empresa.\n";
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
                        echo "Responsable agregado exitosamente.\n";
                    }
                    else {
                        echo "Error al agregar al responsable.\n";
                    }
                    break;
                default:
                    echo "Opción inválida.\n";
                    break;
            }
            break;
        case '8':
            echo "Gracias por su visita.\n";
            break;
        default:
            echo "Opción inválida.\n";
            break;
    }
} while ($valor != '8');
?>
