<?php
class Empresa {
    private $idempresa;
    private $enombre;
    private $edireccion;
    private $mensajeOperacion;

    public function __construct() {
        $this->idempresa = 0;
        $this->enombre = "";
        $this->edireccion = "";
    }

    public function cargar($enombre, $edireccion) {    
        $this->setEnombre($enombre);
        $this->setEdireccion($edireccion);
    }

    public function getIdempresa() {
        return $this->idempresa;
    }
    public function setIdempresa($idempresa) {
        $this->idempresa = $idempresa;
    }

    public function getEnombre() {
        return $this->enombre;
    }
    public function setEnombre($enombre) {
        $this->enombre = $enombre;
    }

    public function getEdireccion() {
        return $this->edireccion;
    }
    public function setEdireccion($edireccion) {
        $this->edireccion = $edireccion;
    }

    public function getMensajeOperacion() {
        return $this->mensajeOperacion;
    }
    public function setMensajeOperacion($mensajeOperacion) {
        $this->mensajeOperacion = $mensajeOperacion;
    }

    public function __toString() {
        return "---- Información de la empresa ----\nID: " . $this->getIdempresa() . " | Nombre: " . $this->getEnombre() . " | Dirección: " . $this->getEdireccion() . "\n";
    }

    /**
     * Recupera los datos de una empresa a través del idempresa
     * @param int $idempresa
     * @return boolean true en caso de encontrar los datos, false en caso contrario 
     */        
    public function buscar($idempresa) {
        $base = new BaseDatos();
        $consulta = "SELECT * FROM empresa WHERE idempresa = $idempresa";
        $resp = false;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                if ($row = $base->Registro()) {
                    $this->setIdempresa($idempresa);
                    $this->cargar($row['enombre'], $row['edireccion']);
                    $resp = true;
                }                
            } else {
                $this->setMensajeOperacion($base->getError());
            }
        } else {
            $this->setMensajeOperacion($base->getError());
        }
        return $resp;
    }

    /**
     * Lista todas las empresas que cumplan con la condición
     * @param string $condicion
     * @return array
     */
    public function listar($condicion = "") {
        $arregloEmpresa = null;
        $base = new BaseDatos();
        $consulta = "SELECT * FROM empresa";
        if ($condicion != "") {
            $consulta .= " WHERE " . $condicion;
        }
        $consulta .= " ORDER BY enombre";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                $arregloEmpresa = array();
                while ($row = $base->Registro()) {
                    $empresa = new Empresa();
                    $empresa->cargar($row['enombre'], $row['edireccion']);
                    $empresa->setIdempresa($row['idempresa']);

                    array_push($arregloEmpresa, $empresa);
                }
            } else {
                $this->setMensajeOperacion($base->getError());
            }
        } else {
            $this->setMensajeOperacion($base->getError());
        }    
        return $arregloEmpresa;
    }

    /**
     * Inserta una nueva empresa en la base de datos
     * @return boolean
     */
    public function insertar() {
        $base = new BaseDatos();
        $resp = false;
        $consulta = "INSERT INTO empresa (enombre, edireccion) VALUES ('" . $this->getEnombre() . "', '" . $this->getEdireccion() . "')";
        if ($base->Iniciar()) {
            if ($id = $base->devuelveIDInsercion($consulta)) {
                $this->setIdempresa($id);
                $resp = true;
            } else {
                $this->setMensajeOperacion($base->getError());
            }
        } else {
            $this->setMensajeOperacion($base->getError());
        }
        return $resp;
    }

    /**
     * Modifica los datos de una empresa en la base de datos
     * @return boolean
     */
    public function modificar() {
        $resp = false;
        $base = new BaseDatos();
        $consulta = "UPDATE empresa SET enombre='" . $this->getEnombre() . "', edireccion='" . $this->getEdireccion() . "' WHERE idempresa=" . $this->getIdempresa();
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                $resp = true;
            } else {
                $this->setMensajeOperacion($base->getError());
            }
        } else {
            $this->setMensajeOperacion($base->getError());
        }
        return $resp;
    }

    /**
     * Elimina una empresa de la base de datos
     * @return boolean
     */
    public function eliminar() {
        $base = new BaseDatos();
        $resp = false;
        if ($base->Iniciar()) {
            $consulta = "DELETE FROM empresa WHERE idempresa=" . $this->getIdempresa();
            if ($base->Ejecutar($consulta)) {
                $resp = true;
            } else {
                $this->setMensajeOperacion($base->getError());
            }
        } else {
            $this->setMensajeOperacion($base->getError());
        }
        return $resp;
    }
}

?>