<?

class Pasajero {
    private $pdocumento;
    private $pnombre;
    private $papellido;
    private $ptelefono;
    private $objViaje;
    private $mensajeOperacion;

    public function __construct()
    {
        $this->pdocumento = "";
        $this->pnombre = "";
        $this->papellido = "";
        $this->ptelefono = "";
    }

    public function cargar($nroDoc, $nombre, $apellido, $telefono, $objViaje)
    {
        $this->setPdocumento($nroDoc);
        $this->setPnombre($nombre);
        $this->setPapellido($apellido);
        $this->setPtelefono($telefono);
        $this->setObjViaje($objViaje);
    }

    // GETTERS
    public function getPdocumento()
    {
        return $this->pdocumento;
    }
    public function getPnombre()
    {
        return $this->pnombre;
    }
    public function getPapellido()
    {
        return $this->papellido;
    }
    public function getPtelefono()
    {
        return $this->ptelefono;
    }
    public function getObjViaje()
    {
        return $this->objViaje;
    }
    public function getMensajeOperacion() {
        return $this->mensajeOperacion;
    }

    // SETTERS
    public function setPdocumento($nroDoc)
    {
        $this->pdocumento = $nroDoc;
    }
    public function setPnombre($nombre)
    {
        $this->pnombre = $nombre;
    }
    public function setPapellido($apellido)
    {
        $this->papellido = $apellido;
    }
    public function setPtelefono($telefono)
    {
        $this->ptelefono = $telefono;
    }
    public function setObjViaje($objViaje)
    {
        $this->objViaje = $objViaje;
    }
    public function setMensajeOperacion($mensaje)
    {
        $this->mensajeOperacion = $mensaje;
    }

    public function __toString()
    {
        return "---- Información del pasajero ----\nNúmero de documento: " . $this->getPdocumento() . "\nNombre: " . $this->getPnombre() . " " . $this->getPapellido() . "\nTeléfono: " . $this->getPtelefono() . "\nInformación del pasaje:\n" . $this->getObjViaje();
    }

    public function Buscar($dni) { 
		$base = new BaseDatos();  
		$consulta = "Select * from pasajero where pdocumento = " . $dni;
		$resp = false; 
		if ($base->Iniciar()) { 
			if ($base->Ejecutar($consulta)) {
				if ($row2 = $base->Registro()) {					 
				    $this->setPdocumento($dni);
					$this->setPnombre($row2["pnombre"]);
					$this->setPapellido($row2["papellido"]);
					$this->setPtelefono($row2["ptelefono"]);
                    $idviaje = ($row2["idviaje"]);
					$objViaje = new Viaje();
					$objViaje->Buscar($idviaje);
					$this->setObjViaje($objViaje);
                    $resp = true;
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
	    $arregloPasajeros = null;
		$base = new BaseDatos(); 
		$consulta = "Select * from pasajero";
		if ($condicion != ""){
		    $consulta = $consulta .' where '. $condicion;
		}
		$consulta .= " order by papellido ";

		if ($base->Iniciar()) {
			if ($base->Ejecutar($consulta)) {				
				$arregloPasajeros = [];

				while($row2 = $base->Registro()) {
					$pdocumento = $row2['pdocumento'];
					$objPasajero = new Pasajero();
                    $objPasajero->Buscar($pdocumento);
					array_push($arregloPasajeros, $objPasajero);
				}
		 	}	
            else {
		 		$this->setMensajeOperacion($base->getError());	
			}
		}	
        else {
		 	$this->setMensajeOperacion($base->getError());
		}	
		
        return $arregloPasajeros;
	}	

    public function insertar() {
		$base = new BaseDatos();
		$resp = false; 
        $consultaInsertar = "INSERT INTO pasajero(pdocumento, pnombre, papellido, ptelefono, idviaje) VALUES ('" . $this->getPdocumento() . "', '" . $this->getPnombre() . "', '" . $this->getPapellido() . "', '" . $this->getPtelefono() . "', " . $this->getObjViaje()->getIdviaje() . ")";
	
		if ($base->Iniciar()) { 
			if ($base->Ejecutar($consultaInsertar)) {
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
	
	public function modificar() {
	    $resp = false; 
	    $base = new BaseDatos();
		$consultaModifica = "UPDATE pasajero SET pnombre = '" . $this->getPnombre() . "', papellido = '" . $this->getPapellido() . "', ptelefono = '" . $this->getPtelefono() . "', idviaje = " . $this->getObjViaje()->getIdviaje() . " WHERE pdocumento = " . $this->getPdocumento();
		if($base->Iniciar()){
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
				$consultaBorra = "DELETE FROM pasajero WHERE pdocumento = " . $this->getPdocumento();
				if ($base->Ejecutar($consultaBorra)) {
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


}

?>