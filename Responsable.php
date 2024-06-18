<?php

class Responsable {

    private $rnumeroempleado; 
    private $rnumerolicencia;
    private $rnombre;
    private $rapellido;
    private $mensajeOperacion;

    public function __construct()
    {
        $this->rnumeroempleado = 0;
        $this->rnumerolicencia = "";
        $this->rnombre = "";
        $this->rapellido = "";
    }

    public function cargar($numLicencia, $nombre, $apellido) { 
        $this->setRnumerolicencia($numLicencia);
        $this->setRnombre($nombre);
        $this->setRapellido($apellido);
    }


    // GETTERS
    public function getRnumeroempleado()
    {
        return $this->rnumeroempleado;
    }
    public function getRnumerolicencia()
    {
        return $this->rnumerolicencia;
    }
    public function getRnombre()
    {
        return $this->rnombre;
    }
    public function getRapellido()
    {
        return $this->rapellido;
    }
    public function getMensajeOperacion()
    {
        return $this->mensajeOperacion;
    }


    public function setRnumeroempleado($numEmpleado)
    {
        $this->rnumeroempleado = $numEmpleado;
    }
    public function setRnumerolicencia($numLicencia)
    {
        $this->rnumerolicencia = $numLicencia;
    }
    public function setRnombre($nombre)
    {
        $this->rnombre = $nombre;
    }
    public function setRapellido($apellido)
    {
        $this->rapellido = $apellido;
    }
    public function setMensajeOperacion($mensajeOperacion)
    {
        $this->mensajeOperacion = $mensajeOperacion;
    }

    public function __toString()
    {
        return "---- Información del responsable ----\nNúmero de empleado: " . $this->getRnumeroempleado() . "\nNúmero de licencia: " . $this->getRnumerolicencia() . "\nNombre: " . $this->getRnombre() . " " . $this->getRapellido() . "\n";
    }

    public function Buscar($numEmpleado) { 
		$base = new BaseDatos();  
		$consulta = "Select * from responsable where rnumeroempleado = " . $numEmpleado;
		$resp = false; 
		if ($base->Iniciar()) { 
			if ($base->Ejecutar($consulta)) {
				if ($row2 = $base->Registro()) {
                    $this->setRnumeroempleado($numEmpleado);					 
					$this->setRnumerolicencia($row2["rnumerolicencia"]);
					$this->setRnombre($row2["rnombre"]);
					$this->setRapellido($row2["rapellido"]);
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
	    $arregloResponsables = null;
		$base = new BaseDatos(); 
		$consultaResponsables = "Select * from responsable";
		if ($condicion != "") {
		    $consultaResponsables = $consultaResponsables .' where ' . $condicion;
		}
		$consultaResponsables .= " order by rapellido";

		if($base->Iniciar()) {
			if ($base->Ejecutar($consultaResponsables)) {				
				$arregloResponsables = [];
				while ($row2 = $base->Registro()) {
					$numEmpleado = $row2['rnumeroempleado'];
					$responsable = new Responsable();
					$responsable->Buscar($numEmpleado);
					array_push($arregloResponsables, $responsable);
				}
		 	}	
            else {
		 		$this->setMensajeOperacion($base->getError());
			}
		}	
        else {
		 	$this->setMensajeOperacion($base->getError());
		}	
		 
        return $arregloResponsables;
	}	

    public function insertar(){
		$base = new BaseDatos();
		$resp = false;

        $consultaInsertar = "INSERT INTO responsable (rnumerolicencia, rnombre, rapellido) VALUES (" . $this->getRnumerolicencia() . ", '" . $this->getRnombre() . "', '" . $this->getRapellido() . "')";
		
        if ($base->Iniciar()) { 
            if ($numEmpleado = $base->devuelveIDInsercion($consultaInsertar)) {
                $this->setRnumeroempleado($numEmpleado);
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
	
	public function modificar(){
	    $resp = false; 
	    $base = new BaseDatos();

        $consultaModifica = "UPDATE responsable SET rnombre = '" . $this->getRnombre() . "', rapellido = '" . $this->getRapellido() . "', rnumerolicencia = " . $this->getRnumerolicencia() . " WHERE rnumeroempleado = " . $this->getRnumeroempleado();
        
        if ($base->Iniciar()) {
			if ($base->Ejecutar($consultaModifica)) {
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

    public function eliminar(){
		$base = new BaseDatos();
		$resp = false;
		if ($base->Iniciar()) {
				$consultaBorra = "DELETE FROM responsable WHERE rnumeroempleado = " . $this->getRnumeroempleado();
				if ($base->Ejecutar($consultaBorra)) {
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

}

?>