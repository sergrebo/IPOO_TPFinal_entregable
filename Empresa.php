<?php
class Empresa{
    private $idempresa;
    private $enombre;
    private $edireccion;
    private $mensajeoperacion;

    public function __construct(){
        $this->idempresa = 0;
        $this->enombre = "";
        $this->edireccion = "";
    }

    public function cargar($idempresa,$enombre,$edireccion){	
	    $this->setIdempresa($idempresa);
		$this->setEnombre($enombre);
		$this->setEdireccion($edireccion);
    }

    public function getIdempresa() {
        return $this->idempresa;
    }
    public function setIdempresa($idempresa){
        $this->idempresa = $idempresa;
    }

    public function getEnombre() {
        return $this->enombre;
    }
    public function setEnombre($enombre){
        $this->enombre = $enombre;
    }

    public function getEdireccion() {
        return $this->edireccion;
    }
    public function setEdireccion($edireccion){
        $this->edireccion = $edireccion;
    }

    public function getMensajeoperacion() {
        return $this->mensajeoperacion;
    }
    public function setMensajeoperacion($mensajeoperacion){
        $this->mensajeoperacion = $mensajeoperacion;
    }

    public function __toString(){
        return "ID: ". $this->getIdempresa(). " | Nombre: ". $this->getEnombre(). " | Dirección: ". $this->getEdireccion();
    }




    /**
	 * Recupera los datos de una empresa a traves del idempresa
	 * @param int $iempresa
	 * @return true en caso de encontrar los datos, false en caso contrario 
	 */		
    public function Buscar($idempresa){
		$base=new BaseDatos();
		$consultaPersona="Select * from empresa where idempresa=".$idempresa;
		$resp= false;
		if($base->Iniciar()){
			if($base->Ejecutar($consultaPersona)){
				if($row2=$base->Registro()){
				    $this->setIdempresa($idempresa);
				    $this->setEnombre($row2['enombre']);
					$this->setEdireccion($row2['edireccion']);
					$resp= true;
				}				
			
	        } else {
	            $this->setmensajeoperacion($base->getError());
			}
		} else {
		    $this->setmensajeoperacion($base->getError());
		}
		return $resp;
	}


	public function listar($condicion=""){
	    $arregloEmpresa = null;
		$base=new BaseDatos();
		$consultaEmpresa="Select * from empresa ";
		if ($condicion!=""){
		    $consultaEmpresa=$consultaEmpresa.' where '.$condicion;
		}
		$consultaEmpresa.=" order by enombre ";
		//echo $consultaPersonas;
		if($base->Iniciar()){
			if($base->Ejecutar($consultaEmpresa)){				
				$arregloEmpresa= array();
				while($row2=$base->Registro()){
				    $idempresa=$row2['idempresa'];
					$enombre=$row2['enombre'];
					$edireccion=$row2['edireccion'];
				
					$empresa=new Empresa();
					$empresa->cargar($idempresa,$enombre,$edireccion);
					array_push($arregloEmpresa,$empresa);
				}
		    } else {
		        $this->setmensajeoperacion($base->getError());
			}
		} else {
		    $this->setmensajeoperacion($base->getError());
		}	
		return $arregloEmpresa;
	}


    public function insertar(){
		$base=new BaseDatos();
		$resp= false;
		$consultaInsertar = "INSERT INTO empresa(enombre, edireccion)
				VALUES ('".$this->getEnombre()."','".$this->getEdireccion()."')";
		
		if($base->Iniciar()){

			if($id = $base->devuelveIDInsercion($consultaInsertar)){
                $this->setIdempresa($id);
			    $resp = true;
			} else {
				$this->setmensajeoperacion($base->getError());
			}
		} else {
			$this->setmensajeoperacion($base->getError());
		}
		return $resp;
	}


	public function modificar(){
	    $resp =false; 
	    $base=new BaseDatos();
		$consultaModifica="UPDATE empresa SET enombre='".$this->getEnombre()."',edireccion='".$this->getEdireccion()."' WHERE idempresa".$this->getIdempresa();
		if($base->Iniciar()){
			if($base->Ejecutar($consultaModifica)){
			    $resp = true;
			} else {
				$this->setmensajeoperacion($base->getError());
			}
		} else {
				$this->setmensajeoperacion($base->getError());
		}
		return $resp;
	}


	public function eliminar(){
		$base=new BaseDatos();
		$resp=false;
		if($base->Iniciar()){
				$consultaBorra="DELETE FROM empresa WHERE idempresa=".$this->getIdempresa();
				if($base->Ejecutar($consultaBorra)){
				    $resp=  true;
				} else {
					$this->setmensajeoperacion($base->getError());
				}
		} else {
			$this->setmensajeoperacion($base->getError());
		}
		return $resp; 
	}
}
?>