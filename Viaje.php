<?php
class Viaje{
    private $idviaje; 
    private $vdestino;
    private $vcantmaxpasajeros;
    private $objEmpresa; 
    private $objResponsable; 
    private $vimporte;
    private $mensajeOperacion;

    public function __construct()
    {
        $this->idviaje = 0;
        $this->vdestino = "";
        $this->vcantmaxpasajeros = 0;
        $this->vimporte = 0;
    }

    public function cargar($destino, $cantMaxPasajeros, $objEmpresa, $objResponsable, $importe) {  
        $this->setVdestino($destino);
        $this->setVcantmaxpasajeros($cantMaxPasajeros);
        $this->setObjEmpresa($objEmpresa);
        $this->setObjResponsable($objResponsable);
        $this->setVimporte($importe);
    }

    // GETTERS
    public function getIdviaje()
    {
        return $this->idviaje;
    }
    public function getVdestino()
    {
        return $this->vdestino;
    }
    public function getVcantmaxpasajeros()
    {
        return $this->vcantmaxpasajeros;
    }
    public function getObjEmpresa()
    {
        return $this->objEmpresa;
    }
    public function getObjResponsable()
    {
        return $this->objResponsable;
    }
    public function getVimporte()
    {
        return $this->vimporte;
    }
    public function getMensajeOperacion() 
    {
        return $this->mensajeOperacion;
    }

    // SETTERS
    public function setIdviaje($idviaje)
    {
        $this->idviaje = $idviaje;
    }
    public function setVdestino($vdestino)
    {
        $this->vdestino = $vdestino;
    }
    public function setVcantmaxpasajeros($vcantmaxpasajeros)
    {
        $this->vcantmaxpasajeros = $vcantmaxpasajeros;
    }
    public function setObjEmpresa($objEmpresa)
    {
        $this->objEmpresa = $objEmpresa;
    }
    public function setObjResponsable($objResponsable)
    {
        $this->objResponsable = $objResponsable;
    }
    public function setVimporte($vimporte)
    {
        $this->vimporte = $vimporte;
    }
    public function setMensajeOperacion($mensajeOperacion)
    {
        $this->mensajeOperacion = $mensajeOperacion;
    }

    public function __toString()
    {
        $cadena="[----------------------------------]\n";
        $cadena.="->Id viaje: " . $this->getIdviaje() . "\n";
        $cadena.="->Destino: " . $this->getVdestino() . "\n";
        $cadena.="->Cantidad máxima de pasajeros: " . $this->getVcantmaxpasajeros(). "\n";
        $cadena.="->Empresa: " . $this->getObjEmpresa()->getEnombre() . "\n";
        $cadena.="->Responsable del viaje:\n" . $this->getObjResponsable() ."\n"; 
        $cadena.="->Importe: " . $this->getVimporte() . "\n";
        return $cadena;
    }

    public function Buscar($idViaje) { 
		$base = new BaseDatos(); 
        $resp = false; 
		$consulta = "Select * from viaje where idviaje = " . $idViaje;
		if ($base->Iniciar()) { 
			if ($base->Ejecutar($consulta)) {
				if($row2 = $base->Registro()){	
                    $objEmpresa = new Empresa();
                    $objEmpresa->Buscar($row2["idempresa"]);
                    $objResponsable = new Responsable();
                    $objResponsable->Buscar($row2["rnumeroempleado"]);
                    $this->setIdviaje($idViaje);
                    $this->cargar($row2["vdestino"], $row2["vcantmaxpasajeros"], $objEmpresa, $objResponsable, $row2["vimporte"]);
                    $resp= true;
				}				
		 	}	
            else {
		 		$this->setMensajeOperacion($base->getError());
			}
		}
        else {
		 	$this->setMensajeOperacion($base->getError());
		}		
		
        return $resp;
	}	

    public function listar($condicion = ""){
	    $arregloViajes = null;
		$base = new BaseDatos(); 
		$consultaViajes = "Select * from viaje";
		if ($condicion != "") {
		    $consultaViajes = $consultaViajes .' where ' . $condicion;
		}
		$consultaViajes = $consultaViajes . " order by vdestino";

		if ($base->Iniciar()) {
			if ($base->Ejecutar($consultaViajes)) {				
				$arregloViajes = [];

				while($row2 = $base->Registro()) {
                    $objViaje = new Viaje();
                    $objViaje->setIdviaje($row2['idviaje']);
                    $objEmpresa = new Empresa();
                    $objEmpresa->Buscar($row2["idempresa"]);
                    $objResponsable = new Responsable();
                    $objResponsable->Buscar($row2["rnumeroempleado"]);
                    $objViaje->cargar($row2["vdestino"], $row2["vcantmaxpasajeros"], $objEmpresa, $objResponsable, $row2["vimporte"]);

					array_push($arregloViajes, $objViaje);
				}	
		 	}	
            else {
		 		$this->setMensajeOperacion($base->getError());
			}
		}	
        else {
		 	$this->setMensajeOperacion($base->getError());	
		}	
		 
        return $arregloViajes;
	}	

    public function insertar()
    {
		$base = new BaseDatos();
		$resp = false;
  
		$consultaInsertar = "INSERT INTO viaje(vdestino, vcantmaxpasajeros, idempresa, rnumeroempleado, vimporte) VALUES ('" . $this->getVdestino() . "', " . $this->getVcantmaxpasajeros() . ", " . $this->getObjEmpresa()->getIdempresa() . ", ". $this->getObjResponsable()->getRnumeroempleado() . ", " . $this->getVimporte() . ")";
		
		if ($base->Iniciar()) { 

			if ($idViaje = $base->devuelveIDInsercion($consultaInsertar)) {
                $this->setIdviaje($idViaje);
			    $resp = true;
			} 
            else {
				$this->setMensajeOperacion($base->getError());					
			}
		} 
        else {
			$this->setMensajeOperacion($base->getError());			
		}

		return $resp;
	}

    public function modificar() { 
	    $resp = false; 
	    $base = new BaseDatos();
		
        $consultaModifica = "UPDATE viaje SET vdestino = '" . $this->getVdestino() . "', vcantmaxpasajeros = " . $this->getVcantmaxpasajeros() . ", idempresa = " . $this->getObjEmpresa()->getIdempresa() . ", rnumeroempleado = " . $this->getObjResponsable()->getRnumeroempleado() . ", vimporte = " . $this->getVimporte() . " WHERE idviaje = " . $this->getIdviaje();

        if ($base->Iniciar()) {

			if ($base->Ejecutar($consultaModifica)) {
			    $resp=  true;
			} 
            else {
				$this->setMensajeOperacion($base->getError());				
			}
		} 
        else {
			$this->setMensajeOperacion($base->getError());			
		}

		return $resp;
	}

    public function eliminar() {
		$base = new BaseDatos();
		$resp = false;

		if ($base->Iniciar()) {
				$consultaBorra = "DELETE FROM viaje WHERE idviaje = " . $this->getIdviaje();
				if ($base->Ejecutar($consultaBorra)) {
				    $resp=  true;
				} 
                else{
					$this->setMensajeOperacion($base->getError());					
				}
		} 
        else {
			$this->setMensajeOperacion($base->getError());			
		}
		
        return $resp; 
	}

}

?>