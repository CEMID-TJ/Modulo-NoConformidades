<?php
/*
Script para MANEJO DE NO CONFORMIDADES
Los SuperUsuarios pueden consultar, y tramitar todos los registros
*/
include ("../seguridad.php");
include('../funciones.php');
//Registro de los Usuarios que son Administradores del sistema
$PuedeAdministrar = in_array($_SESSION['Usuario'],array((InList($_SESSION["Perfil"],"ADMIN") ? $_SESSION['Usuario'] : "98381246"),
                        '79558178',//CAMILO VELANDIA
                        '1054540722',//Johan Rodriguez
                        '1014305622',//Laura Valentina
                        '1073696610',//Jenniffer Bello
                        '52841712',//Diana Álvarez
                        '1233909122',//Eduardo León
						'1022928448'//JABIER BARRERA
						));
$PuedeReversar = in_array($_SESSION['Usuario'],array((InList($_SESSION["Perfil"],"ADMIN") ? $_SESSION['Usuario'] : "98381246"),
                        '1014305622',//Laura Valentina
                        '1073696610',//Jenniffer Bello
                        '1233909122',//Eduardo León
						'1022928448'//JABIER BARRERA
						));
$PuedeGeneral = in_array($_SESSION['Usuario'],array((InList($_SESSION["Perfil"],"ADMIN") ? $_SESSION['Usuario'] : "98381246"),
					'39665007',	  //MarthaCV
					'79404413',	  //Alvaro Parra
					'8730314',	  //JavierM
					'37829191',	  //Nelcy Jerez
					'40025213',	  //Consuelo Ramirez
					'41405252',	  //Luz M
					'79879905',	  //CarlosL
					'39668167',   //Marcela Piñeros
					'1072665948', //Jessica Agudelo
					'52903643',   //CASALLAS DEICY
					'1032380671', //ARDILA GISSED
					'52378375',   //CASTAÑEDA LINA
					'52226604',   //PARROQUIANO MARYSOL
					'80241390',   //ARIAS FABIO
					'52849113',   //PULIDO PEREZ CATERYN
					'1024471606', //JUDY ANDREA RUEDA  GOMEZ
					'53048628',   //FLOREZ CASTAÑEDA DIANA CAROLINA
					'52382262',   //SABOGAL CORTES FLOR XIMENA
					'52159469',   //CASTILLO MARTINEZ IVONNE ASTRID
					'53067789',   //RODRIGUEZ MUÑOZ VIVIANA EMILCE
					'1024509359', //SEMA CASTIBLANCO MARIA TERESA
					'32764527',   //OCHOA BOHORQUEZ SANDRA YODANY
					'63347978',   //RAMIREZ TOLOZA CLARA EUGENIA
					'63489430',   //BAUTISTA GONZALEZ LILIA CELINA
					'1098682989', //BRAVO PACHECO YANETH NATALIA
					'37556707',   //RAMIREZ CARRILLO DEISY
					'1010171514', //CUBILLOS CABRERA PAUL BRAYAN
					'1023861416', //ZAMBRANO SABOGAL PEDRO PABLO
					'79844012',   //ROMERO TIJO NESTOR RAUL
					'52190948',   //QUIÑONES DIAZ AIDA YANETH
					'1024539393', //PITA FUQUENE LEIDY JOHANA
					'1073152711', //TELLEZ LOZANO SANDRA PATRICIA
					'1023013296', //CUBIDES MORENO NATHALIA
					'1014220582', //SERENY JIMENEZ JULIET FERNANDA
					'1015428198', //CONEO JARABA KARINA KAROLAY
					'1030610691', //GONZALEZ DELGADO YESICA PAOLA
					'1022340598', //LONDOÑO MICHAEL
					'1022415485', //GAONA ARANGO LAURA ROCIO
					'52803785',   //CUELLAR PIZA ERIKA MILENA
					'37547875',   //CABALLERO GRATZ MARYLUZ
					'37752414',   //CASADIEGO CUAO ADELINA ESTER
					'52233576',   //PIZA CAMACHO SANDRA MILENA
					'1019050390', //GUTIERREZ RODRIGUEZ YINETH ROCIO
					'1073696610',//JENIFFER BELLO MAYORGA
					'1014305622',//LAURA GONZÁLEZ
					'3276248'));  //GustavoG
$mTipoRegistro=array('No Conformidad' => 'Incumplimiento de un requisito Legal o de procedimientos internos que no afecta la prestación del servicio educativo o la oferta de valor con los clientes (Estudiantes y padres de familia)',
                     'Servicio no Conforme' => 'Incumplimiento de requisito directamente relacionado con el servicio que se entrega al cliente, es decir, el servicio educativo, y que se presenta durante la prestación de este.',
                     'Corrección' => 'Acción para eliminar de manera inmediata un fallo o desviación detectada.',
                     'Acción Preventiva' => 'Acción desarrollada para evitar que un riesgo o desarrollo de una actividad se materialicé en No conformidad o servicio no conforme.',
                     'Acción Correctiva' => 'Acción para eliminar la causa de una no conformidad y evitar que vuelva a ocurrir',
                     'Acción de Mejora' => 'Ajuste propuesto con el fin de mejorar el desempeño, agilizar la acción o aumentar positivamente la percepción de las partes interesadas.'
                    );
//Conectar a la Base de datos
include ("../conexion_servidor.php");
/******************************************************************************************************************************************
Retornar los datos de una NoConformidad para consulta
*******************************************************************************************************************************************/
if($_GET['TipoModificar']==md5('AjaxMostrarNoConformidad'.date('d')) and $_GET['NumeroNC']){
	$Queri = "SELECT NC.NumeroNC, NC.Tipo, NC.CodGestion, Ges.NomGestion, NC.Proceso, NC.FApertura, NC.DetectadaEn, NC.Descripcion, NC.Correccion, NC.Observacion,
					CONCAT(E.Nom,' ',E.Apellido1) AS NomResponsable, NC.Causas,NC.RequiereAC,NumeroNCAccionCorrectiva,
					NC.Accion, NC.ResultadoRevision, NC.FRevision, NC.MotivoCierre, NC.NuevaAccion, NC.FCierre, NC.RCierre
				FROM calidad.noconformidades NC
				LEFT JOIN recursos.emplea E ON NC.Responsable=E.Nit_CCE
				JOIN calidad.gestiones Ges ON NC.CodGestion=Ges.CodGestion
				WHERE NC.NumeroNC='".$_GET['NumeroNC']."'
				LIMIT 1";
	$Result = $mysqli->query($Queri);
	if($Row = $Result->fetch_assoc()){?>
        <div class="col-sm-2 panel panel-default">
        <label class="label-xsmall">Consecutivo:</label> <?php echo $Row['NumeroNC'];?>
        </div>
        <div class="col-sm-4 panel panel-default">
        <label class="label-xsmall">Responsable de la apertura:</label> <?php echo $Row['NomResponsable'];?>
        </div>
        <div class="col-sm-6 panel panel-default">
        <label class="label-xsmall">Tipo:</label> <?php echo $Row['Tipo'].' - '.$mTipoRegistro[$Row['Tipo']];?>
        </div>
        <div class="clearfix"></div>
        <div class="col-sm-3 panel panel-default">
        <label class="label-xsmall">Gestión:</label> <?php echo $Row['NomGestion'];?>
        </div>
        <div class="col-sm-3 panel panel-default">
        <label class="label-xsmall">Proceso:</label> <?php echo $Row['Proceso'];?>
        </div>
        <div class="col-sm-3 panel panel-default">
        <label class="label-xsmall">Fecha de apertura:</label> <?php echo DarFecha($Row['FApertura']);?>
        </div>
        <div class="col-sm-3 panel panel-default">
        <label class="label-xsmall">Detectada en:</label> <?php echo $Row['DetectadaEn'];?>
        </div>
        <div class="clearfix"></div>
        <div class="col-sm-12 panel panel-default">
        <label class="label-xsmall">Descripción:</label> <?php echo $Row['Descripcion'];?>
        </div>
        <div class="clearfix"></div>
        <div class="col-sm-12 panel panel-default">
        <label class="label-xsmall">Relación de actividades:</label> <?php echo $Row['Correccion'];?>
        </div>
        <div class="col-sm-12 panel panel-default">
        <label class="label-xsmall">Observación:</label> <?php echo $Row['Observacion'];?>
        </div><?php
        if($Row['Tipo']=='Corrección'){?>
        <div class="clearfix"></div>
        <div class="col-sm-8 panel panel-default">
        <label class="label-xsmall">Con la acción determinada se garantiza que la falla o posible materialización del riesgo no se presente de nuevo?:</label>
            <?php echo ($Row['RequiereAC']==1 ? 'Si' : ($Row['RequiereAC']==-1 ? 'No' : ''));?>
        </div><?php
            if($Row['RequiereAC']==-1){?>
        <div class="col-sm-4 panel panel-default">
        <label class="label-xsmall">ID de la acción correctiva vinculada a esta corrección:</label>
            <a style="cursor:pointer" onClick="MostrarDivConsultaNoConformidad(<?php echo $Row['NumeroNCAccionCorrectiva'] ?>);" title="Ver <?php echo $Row['NumeroNCAccionCorrectiva'];?> acción correctiva vinculada a esta corrección"><?php echo $Row['NumeroNC'];?></a>
        </div><?php
            }
        }?>
        <div class="clearfix"></div>
        <div class="col-sm-9 panel panel-default">
        <label class="label-xsmall">Causas o Ventajas:</label> <?php echo $Row['Causas'];?>
        </div>
        <div class="col-sm-3 panel panel-default">
        <label class="label-xsmall">Fecha acordada para la revisión:</label> <?php echo DarFecha($Row['FRevision']);?>
        </div>
        <div class="clearfix"></div>
		<?php
        if(file_exists("../../Archivos/NoConformidades/".trim($_GET['NumeroNC']).".pdf")){
        echo "<a href='../file.php?Tipo=".md5("NoConformidades".trim($Row['NumeroNC']))."&mFile=".trim($Row['NumeroNC'])."' class='col-sm-12 btn btn-default btn-file' title='Ver Archivo Adjunto' target='_blank'><img src='../imagenes/pdf.png' border='0'>Ver Archivo Adjunto</a>";
		}?>
        <div class="clearfix"></div><?php
		$QueriNCS = "SELECT NCS.*
				FROM calidad.ncseguimiento NCS
				Where NumeroNC='".$_GET['NumeroNC']."' ".($PuedeAdministrar ? "" : "AND Borrada=0")."
				ORDER BY ConsecutivoS";
		$ResultNCS = $mysqli->query($QueriNCS);
		if($ResultNCS->num_rows>0){?>
        <div class="col-sm-12 panel panel-primary">
            <h5><b>SEGUIMIENTOS</b></h5><?php
		}
		while($RowNCS = $ResultNCS->fetch_assoc()){//Hay Registros  de Seguimiento?>
            <div class="col-sm-6 panel panel-default">
            <label class="label-xsmall">(<?php echo $RowNCS["ConsecutivoS"];?>)Acción (Correctiva o Preventiva)</label> <?php echo ($RowNCS['Borrada'] ? ('<b style="background:red">BORRADA '.$RowNCS['Accion'].'</b>') : $RowNCS['Accion']);?>
            </div>
            <div class="col-sm-6 panel panel-default">
            <label class="label-xsmall">Resultado Esperado</label> <?php echo $RowNCS['Resultado'];?>
            </div>
            <div class="clearfix"></div>
            <div class="col-sm-3 panel panel-default">
            <label class="label-xsmall">Fecha Apertura:</label> <?php echo DarFechaHora($RowNCS["FHSeguimiento"],3);?>
            </div>
            <div class="col-sm-3 panel panel-default">
            <label class="label-xsmall">Fecha Seguimiento:</label> <?php echo DarFecha($RowNCS["Fecha"]);?>
            </div>
            <div class="col-sm-4 panel panel-default">
            <label class="label-xsmall">Realizado Por:</label> <?php echo $RowNCS["RealizadoPor"];?>
            </div>
            <div class="col-sm-2 panel panel-default">
            <label class="label-xsmall">Alcanzado:</label> <?php echo ($RowNCS["Alcanzado"]==1 ? "Si" : ($RowNCS["Alcanzado"]==-1 ? "No" : ""));?>
            </div>
            <div class="clearfix"></div><?php
		}
		if($ResultNCS->num_rows>0){?>
        </div><?php
		}
		if($Row['FCierre']>0){?>
        <div class="col-sm-12 panel panel-primary">
            <h5><b>DATOS DEL CIERRE</b></h5>
            <div class="col-sm-6 panel panel-default">
            <label class="label-xsmall">Resultados de la revisión de la <i title="Entiéndase la eficacia como el grado en que se realizan las actividades planificadas y se logran los resultados planificados"><u>eficacia</u></i> de las acciones tomadas:</label> <?php echo $Row['ResultadoRevision'];?>
            </div>
            <div class="col-sm-6 panel panel-default">
            <label class="label-xsmall">Motivos del Cierre:</label> <?php echo $Row['MotivoCierre'];?>
            </div>
            <div class="clearfix"></div>
            <div class="col-sm-6 panel panel-default">
            <label class="label-xsmall">Nueva Acción?:</label> <?php echo $Row['NuevaAccion'];?>
            </div>
            <div class="col-sm-2 panel panel-default">
            <label class="label-xsmall">Fecha del Cierre:</label> <?php echo DarFecha($Row['FCierre']);?>
            </div>
            <div class="col-sm-4 panel panel-default">
            <label class="label-xsmall">Responsable del Cierre:</label> <?php echo $Row['RCierre'];?>
            </div>
		</div><?php
		}
	}else{
		echo "<b>No existe</b>";
	}
	mysqli_close($mysqli);
	exit;
/******************************************************************************************************************************************
Retornar los datos de una NoConformidad para edición
*******************************************************************************************************************************************/
}elseif($_GET['TipoModificar']==md5('Ajax1JorA1NoConformidad'.date('d')) and $_GET['NumeroNC']){
	$Queri = "SELECT NC.*, CONCAT(E.Nom,' ',E.Apellido1) AS NomResponsable, QQueja.IDQueja
				FROM calidad.noconformidades NC
				LEFT JOIN (SELECT NumeroNC, MAX(IDQueja) AS IDQueja FROM calidad.quejas WHERE NumeroNC='".$_GET['NumeroNC']."') AS QQueja ON NC.NumeroNC=QQueja.NumeroNC
				LEFT JOIN recursos.emplea E ON NC.Responsable=E.Nit_CCE
				WHERE NC.NumeroNC='".$_GET['NumeroNC']."'
				LIMIT 1";
	$Result = $mysqli->query($Queri) or die(json_encode(["status" => "error", "message" => mysqli_error($mysqli)]));

    $respuesta = array();
	if($Row=$Result->fetch_assoc()){
        // Asignamos los calores a un arreglo
        $respuesta = array(
            'status' => 'OK',
            'CodGestion' => $Row['CodGestion'],
            'proceso' => $Row['Proceso'],
            'NomResponsable' => $Row['NomResponsable'],
            'Tipo' => $Row['Tipo'],
            'FApertura' => DarFecha($Row['FApertura']),
            'DetectadaEn' => $Row['DetectadaEn'],
            'DetectadaEnOtra' => ($Row['DetectadaEn'] <> 'Quejas' ? $Row['DetectadaEn'] : $Row['IDQueja']),
            'Descripcion' => $Row['Descripcion'],
            'Correccion' => $Row['Correccion'],
            'Observacion' => $Row['Observacion'],
            'RequiereAC' => $Row['RequiereAC'],
            'NumeroNCAccionCorrectiva' => $Row['NumeroNCAccionCorrectiva'],
            'Causas' => $Row['Causas'],
            'Accion' => $Row['Accion'],
            'FRevision' => DarFecha($Row['FRevision']),
            'YaCargado' => (file_exists("../../Archivos/NoConformidades/".trim($_GET['NumeroNC']).".pdf") ? 1 : 0)
        );
    } else {
        $respuesta = array('status' => 'Error');
    }

    //Devolvemos los encabezados JSON y el objeto convertido
    header ('Content-Type: application/json; charset=utf-8');
    echo json_encode($respuesta);
	mysqli_close($mysqli);
	exit;
/****************************************************************************************************************************************
GRABAR los datos de una NoConformidad
****************************************************************************************************************************************/
}else if($_POST['TipoModificar']==md5('JorGrabarNoConformidad'.date('d')) and $_POST['TipoGrabar']=='GrabarNoConformidad' and $_POST['NumeroNC']){
    
    if($_POST['DetectadaEn']=='Otra'){
        $_POST['DetectadaEn'] = addslashes($_POST['DetectadaEnOtra']);
    }
    
    // TEMPORAL: Pegar la Observacion al final de la Descripcion
    if(!empty($_POST['Observacion'])){
        $_POST['Descripcion'] = $_POST['Descripcion'] . "\n\n[Observación temporal: " . $_POST['Observacion'] . "]";
    }
    
    if($_POST['NumeroNC']=='Nuevo'){
        $EsNueva=1;
        // INSERT (Ya no pide la columna Observacion)
        $QueriGrabar = "INSERT INTO calidad.noconformidades (Responsable, Tipo, CodGestion, Proceso, FApertura,DetectadaEn,Descripcion,
                                    Correccion,RequiereAC,NumeroNCAccionCorrectiva,Causas,Accion,FRevision,IPApertura,FHApertura)
                            VALUES
                        ('".$_SESSION["Usuario"]."','".$_POST['Tipo']."','".$_POST['CodGestion']."','".$_POST['Proceso']."','".DarFechaSQL($_POST['FApertura'])."','".addslashes($_POST['DetectadaEn'])."','".addslashes($_POST['Descripcion']).
                            "','".addslashes($_POST['Correccion'])."','".$_POST['RequiereAC']."','".$_POST['NumeroNCAccionCorrectiva']."','".addslashes($_POST['Causas'])."','".addslashes($_POST['Accion'])."','".DarFechaSQL($_POST['FRevision'])."','".
                            $_SESSION['IPAcceso']."',SYSDATE())";
        $ResultGrabar = $mysqli->query($QueriGrabar) or die(json_encode(["status" => "error", "message" => mysqli_error($mysqli)]));
        $_POST['NumeroNC']=$mysqli->insert_id;
    }else{
        // UPDATE (Ya no pide la columna Observacion)
        $QueriGrabar = "UPDATE calidad.noconformidades SET CodGestion = '".$_POST['CodGestion']."',
                            Tipo = '".$_POST['Tipo']."',
                            Proceso = '".$_POST['Proceso']."',
                            FApertura = '".DarFechaSQL($_POST['FApertura'])."',
                            DetectadaEn = '".addslashes($_POST['DetectadaEn'])."',
                            Descripcion = '".addslashes($_POST['Descripcion'])."',
                            Correccion = '".addslashes($_POST['Correccion'])."',
                            RequiereAC = '".$_POST['RequiereAC']."',
                            NumeroNCAccionCorrectiva = '".$_POST['NumeroNCAccionCorrectiva']."',
                            Causas = '".addslashes($_POST['Causas'])."',
                            Accion = '".addslashes($_POST['Accion'])."',
                            FRevision = '".DarFechaSQL($_POST['FRevision'])."',
                            IPApertura = IF(IPApertura<>'',IPApertura,'".$_SESSION['IPAcceso']."'),
                            FHApertura = IF(FHApertura>0,FHApertura,SYSDATE())
                        WHERE NumeroNC = ".$_POST['NumeroNC']." LIMIT 1";
        $ResultGrabar = $mysqli->query($QueriGrabar) or die(json_encode(["status" => "error", "message" => mysqli_error($mysqli)]));
    }
    
    if($_FILES['FileNoConformidad']){
        $upload_folder ='../../Archivos/NoConformidades';
        $nombre_archivo = $_POST['NumeroNC'].".pdf";
        $archivador = $upload_folder . '/' . $nombre_archivo;
        $tmp_archivo = $_FILES['FileNoConformidad']['tmp_name'];
        move_uploaded_file($tmp_archivo, $archivador);
    }
    
    if($_POST['DetectadaEn']=='Quejas' and intval($_POST['DetectadaEnOtra'])){
        $QueriGrabar = "UPDATE calidad.quejas SET
                                            NumeroNC='".$_POST['NumeroNC']."'
                                            WHERE IDQueja='".intval($_POST['DetectadaEnOtra'])."'
                                            LIMIT 1";
        $ResultGrabar = $mysqli->query($QueriGrabar);
        
        $Queri = "SELECT NC.*, CONCAT(E.Nom,' ',E.Apellido1) AS NomResponsable,
                        QQueja.IDQueja, QQueja.Queja, Pr.Dueproceso
                    FROM calidad.noconformidades NC
                    LEFT JOIN (SELECT Colegio, NumeroNC, MAX(IDQueja) AS IDQueja, Queja
                                FROM calidad.quejas WHERE NumeroNC='".$_POST['NumeroNC']."'
                            ) AS QQueja ON NC.NumeroNC=QQueja.NumeroNC
                    LEFT JOIN calidad.procesos Pr ON NC.Proceso=CONCAT(Pr.Colegio,Pr.CodProceso)
                    LEFT JOIN recursos.emplea E ON NC.Responsable=E.Nit_CCE
                    WHERE NC.NumeroNC='".$_POST['NumeroNC']."'
                    LIMIT 1";
        $Result = $mysqli->query($Queri) or die(json_encode(["status" => "error", "message" => mysqli_error($mysqli)]));
        $Row = $Result->fetch_assoc();
        
        if($Row['Dueproceso']){
            $QueriMail = "SELECT GROUP_CONCAT(EmailI SEPARATOR ';') AS Mails
                        FROM recursos.emplea
                        WHERE FIND_IN_SET(Nit_CCE,'".$Row['Dueproceso']."') AND Activo";
            $ResultMail = $mysqli->query($QueriMail) or die(json_encode(["status" => "error", "message" => mysqli_error($mysqli)]));
            $RowMail = $ResultMail->fetch_assoc();
            $Cuentas=$RowMail['Mails'];
            $CCo="";
            $Servicio='Office';
            $Subject = "MANEJO NO CONFORMIDADES";
            
            // Eliminamos la variable Observación de este correo, ya que ahora viajará dentro de la variable Descripcion
            $Mensaje="Se ha generado un nuevo registro <b>NO CONFORMIDAD</b> que corresponde a los siguientes datos:<br><br>
                    Responsable Apertura <b>".$Row['NomResponsable']."</b><br>
                    Gestión <b>".$Row['CodGestion']."</b><br>
                    Proceso <b>".$Row['Proceso']."</b><br>
                    Fecha Apertura <b>".DarFecha($Row['FApertura'])."</b><br>
                    Detectada En <b>".$Row['DetectadaEn']."</b><br>
                    Descripción <b>".$Row['Descripcion']."</b><br>
                    Relación de actividades <b>".$Row['Correccion']."</b><br>
                    Objetivo principal del plan de Acción <b>".$Row['Accion']."</b><br>
                    Causas o Ventajas <b>".$Row['Causas']."</b><br>
                    Queja Asociada <b>(".$Row['IDQueja'].")".$Row['Queja']."</b><br>
                    Éste correo se emite a manera de información para confirmar la creación del registro...<br><br>
                    Cordialmente<br><br>
                    <b>CEMID</b>";
            $mFile="";
            $Respuesta=EnviarCorreo($mysqli,$Cuentas, $Subject, $Mensaje, $Servicio, $CCo, $mFile);
        }
    }
    mysqli_close($mysqli);
    
    echo json_encode([
        "status" => "success", 
        "id" => $_POST['NumeroNC']
    ]);
    exit;
/******************************************************************************************************************************************
Retornar los Seguimientos de una NoConformidad para Edición
*******************************************************************************************************************************************/
}elseif($_GET['TipoModificar']==md5('AjaxSeguimientos'.date('d')) and $_GET['NumeroNC']){
	$QueriNCS = "SELECT NCS.*
			FROM calidad.ncseguimiento NCS
			Where Borrada=0 AND NumeroNC='".$_GET['NumeroNC']."'
			ORDER BY ConsecutivoS";
	$ResultNCS = $mysqli->query($QueriNCS);
	$mRegistro = 0;
	while($RowNCS = $ResultNCS->fetch_assoc() or $mRegistro==0){//Hay Registros  de Seguimiento
		$mRegistro++;?>
        <div class="col-sm-12 panel panel-primary" id="DivSeguimiento<?php echo $mRegistro;?>">
        <input name="Borrada<?php echo $mRegistro;?>" type="hidden" id="Borrada<?php echo $mRegistro;?>">
		<div class="col-sm-6 panel form-group">
		<label class="label-xsmall">Objetivo principal del plan de Acción</label>
        <div class="close" id="SpanSeguimiento<?php echo $mRegistro;?>"><?php echo $mRegistro;?></div>
        <textarea name="Accion<?php echo $mRegistro;?>" id="Accion<?php echo $mRegistro;?>" class="form-control label-xsmall" placeholder="Objetivo principal del plan de Acción" rows="2"><?php echo $RowNCS['Accion'];?></textarea>
		</div>
		<div class="col-sm-6 panel form-group">
		<label class="label-xsmall">Resultado Esperado</label>
        <textarea name="Resultado<?php echo $mRegistro;?>" id="Resultado<?php echo $mRegistro;?>" class="form-control label-xsmall" placeholder="Resultado Esperado" rows="2"><?php echo $RowNCS['Resultado'];?></textarea>
		</div>
        <div class="clearfix"></div>
		<div class="col-sm-3 panel form-group">
		<label class="label-xsmall">Fecha Seguimiento:</label>
        <input type="text" name="Fecha<?php echo $mRegistro;?>" id="Fecha<?php echo $mRegistro;?>" class="form-control" onChange="ValidarFecha(this)" onBlur="ValidarFecha(this)" value="<?php echo DarFecha($RowNCS["Fecha"]);?>">
		</div>
		<div class="col-sm-6 panel form-group">
		<label class="label-xsmall">Realizado Por:</label>
        <input type="text" name="RealizadoPor<?php echo $mRegistro;?>" id="RealizadoPor<?php echo $mRegistro;?>" class="form-control" value="<?php echo $RowNCS["RealizadoPor"];?>">
		</div>
		<div class="col-sm-2 panel form-group">
		<label class="label-xsmall">Alcanzado:</label>
        <select name="Alcanzado<?php echo $mRegistro;?>" class="form-control" id="Alcanzado<?php echo $mRegistro;?>">
			<option value=0 selected></option>
			<option value=1 <?php if($RowNCS["Alcanzado"]==1) echo "selected";?>>SI</option>
			<option value=-1 <?php if($RowNCS["Alcanzado"]==-1) echo "selected";?>>NO</option>
        </select>
		</div>
		<div class="col-sm-1 panel form-group">
        <button type="button" class="close" id="BotBorrarSeguimiento<?php echo $mRegistro;?>">
            <a style="cursor:pointer" onClick="BorrarSeguimiento(this);" title="Borrar este seguimiento">
            <img hspace='2' width='16' height='16' src='../imagenes/invalid.png' border='0'/>
            </a>
        </button>
        </div>
        </div><?php
	}?>
    <input name="CantSeguimientos" type="hidden" id="CantSeguimientos" value="<?php echo $mRegistro;?>"><?php
	mysqli_close($mysqli);
	exit;
/****************************************************************************************************************************************
GRABAR los Seguimientos de una NoConformidad
****************************************************************************************************************************************/
}else if($_POST['TipoModificar']==md5('JorSeguimientos'.date('d')) and $_POST['TipoGrabar']=='Seguimientos' and $_POST['NumeroNCSeguimientos']){
    
    $QueriGrabar = "UPDATE calidad.ncseguimiento
                    SET Borrada = 1
                    WHERE NumeroNC = ".$_POST['NumeroNCSeguimientos'];
    $ResultGrabar = $mysqli->query($QueriGrabar) or die(json_encode(["status" => "error", "message" => mysqli_error($mysqli)]));
    
    $mFila=0;
    for($i=1; $i<=$_POST['CantSeguimientos']; $i++){
        if($_POST['Borrada'.$i]<>1){
            $mFila++;
            $Queri = "INSERT INTO calidad.ncseguimiento (NumeroNC, ConsecutivoS, Fecha, Accion,
                            Resultado, RealizadoPor, Alcanzado, IPSeguimiento, FHSeguimiento)".
                    "VALUES(".$_POST['NumeroNCSeguimientos'].",".$mFila.",'".DarFechaSQL($_POST['Fecha'.$i])."','".addslashes($_POST['Accion'.$i])."',
                            '".addslashes($_POST['Resultado'.$i])."','".addslashes($_POST['RealizadoPor'.$i])."','".$_POST['Alcanzado'.$i]."',
                            '".$_SESSION['IPAcceso']."',SYSDATE())
                    ON DUPLICATE KEY UPDATE Fecha='".DarFechaSQL($_POST['Fecha'.$i])."',
                                        Accion='".addslashes($_POST['Accion'.$i])."',
                                        Resultado='".addslashes($_POST['Resultado'.$i])."',
                                        RealizadoPor='".addslashes($_POST['RealizadoPor'.$i])."',
                                        Alcanzado='".$_POST['Alcanzado'.$i]."',
                                        Borrada=0";
            $Result = $mysqli->query($Queri) or die(json_encode(["status" => "error", "message" => mysqli_error($mysqli)]));
        }
    }
    mysqli_close($mysqli);
    
    // RETORNO JSON
    echo json_encode([
        "status" => "success", 
        "id" => $_POST['NumeroNCSeguimientos']
    ]);
    exit;
/******************************************************************************************************************************************
Retornar los datos de una NoConformidad para Cierre
*******************************************************************************************************************************************/
}elseif($_GET['TipoModificar']==md5('AjaxCerrarNoConformidad'.date('d')) and $_GET['NumeroNC']){
	$Queri = "SELECT NumeroNC, ResultadoRevision, MotivoCierre, NuevaAccion, FCierre, RCierre
				FROM calidad.noconformidades
				WHERE NumeroNC='".$_GET['NumeroNC']."'
				LIMIT 1";
	$Result = $mysqli->query($Queri) or die(mysqli_error($mysqli));
	if($Row=$Result->fetch_assoc()){?>
        document.getElementById('ResultadoRevision').value = '<?php echo $Row['ResultadoRevision'];?>';
        document.getElementById('MotivoCierre').value = '<?php echo $Row['MotivoCierre'];?>';
        document.getElementById('NuevaAccion').value = '<?php echo $Row['NuevaAccion'];?>';
        document.getElementById('FCierre').value = '<?php echo DarFecha($Row['FCierre']);?>';
        document.getElementById('RCierre').value = '<?php echo ($Row['RCierre'] ? $Row['RCierre'] : $_SESSION['NomUsuario']);?>';
	<?php	//Ojo con el enter para el espacio
	}
	mysqli_close($mysqli);
	exit;
/****************************************************************************************************************************************
GRABAR los datos de Cierre de una NoConformidad
****************************************************************************************************************************************/
}else if($_POST['TipoModificar']==md5('JorCerrarNoConformidad'.date('d')) and $_POST['TipoGrabar']=='CerrarNoConformidad' and $_POST['NumeroNCCerrar']){
	$QueriGrabar = "UPDATE calidad.noconformidades SET ResultadoRevision = '".addslashes($_POST['ResultadoRevision'])."',
												MotivoCierre = '".addslashes($_POST['MotivoCierre'])."',
												NuevaAccion = '".addslashes($_POST['NuevaAccion'])."',
												FCierre = '".DarFechaSQL($_POST['FCierre'])."',
												RCierre = '".$_SESSION['Usuario']."',
												IPCierre = '".$_SESSION['IPAcceso']."',
												FHCierre = SYSDATE()
										WHERE NumeroNC = ".$_POST['NumeroNCCerrar']."
										LIMIT 1";
	$ResultGrabar = $mysqli->query($QueriGrabar) or die(json_encode(["status" => "error", "message" => mysqli_error($mysqli)]));
	mysqli_close($mysqli);
    echo json_encode(["status" => "success"]);
	exit;
/******************************************************************************************************************************************
CambioGestionProceso
*******************************************************************************************************************************************/
}elseif($_GET['TipoModificar']==md5('JorCambioGestionProceso'.date('d')) and $_GET['CodGestion']){
	echo 'mProceso='.($_GET['Proceso'] ? "'".$_GET['Proceso']."'" : 'document.getElementById("Proceso").value').';document.getElementById("Proceso").length=1;';
	//Obtengo el listado de los procesos
	if($PuedeAdministrar or $PuedeGeneral){
		$QueriProc = "SELECT Colegio, CodProceso, NomProceso
						FROM calidad.procesos
						WHERE CodGestion='".$_GET['CodGestion']."'
						ORDER BY NomProceso";
	}else{
		$QueriProc = "SELECT Colegio, CodProceso, NomProceso
						FROM calidad.procesos
						WHERE CodGestion='".$_GET['CodGestion']."' AND FIND_IN_SET('".$_SESSION["Usuario"]."',DueProceso)
						ORDER BY NomProceso";
	}
	$ResultProc = $mysqli->query($QueriProc) or die(mysqli_error($mysqli));
	while($RowProc=$ResultProc->fetch_assoc()){
		echo 'document.getElementById("Proceso").add(new Option("'.$RowProc['NomProceso'].'", "'.$RowProc['Colegio'].$RowProc['CodProceso'].'", null));';
	}
	echo 'if(mProceso){document.getElementById("Proceso").value=mProceso;}';
	mysqli_close($mysqli);
	exit;
/******************************************************************************************************************************************
Reportes
*******************************************************************************************************************************************/
}elseif($_GET['TipoModificar']=='EnviarReportes' and $_GET['TipoGrabar']==md5('JorEnviarReportes'.date('d'))){
	set_time_limit(0);
	ini_set('memory_limit','-1');
	
	if($_GET['TipoReporte']=='ReporteNoConformidades'){
        if($PuedeAdministrar){
            $Filtrico = "WHERE NC.NumeroNC";
        }elseif($PuedeGeneral){
            $Filtrico = "WHERE NC.Borrada=0";
        }else{
            $Filtrico = "WHERE NC.Borrada=0 and (Pr.DueProceso LIKE '%".$_SESSION["Usuario"]."%' or NC.Responsable='".$_SESSION['Usuario']."' )";
        }
        if(!empty($_GET['ReportProceso'])){
            $Filtrico .= " AND NC.Proceso = '".$_GET['ReportProceso']."'";
        }
        
        $Queri = "SELECT NC.NumeroNC, NC.CodGestion, Pr.NomProceso, NC.Tipo, CONCAT(E.Nom,' ',E.Apellido1) AS NomResponsable,
                        (TIMESTAMPDIFF(DAY, '1900-01-01', NC.FApertura)+2) AS FApertura, NC.DetectadaEn, NC.Descripcion, NC.Correccion,
                        IF(NC.Tipo='Corrección' AND NC.RequiereAC=1,'Si',IF(NC.Tipo='Corrección' AND NC.RequiereAC=-1,'No','')) AS GarantiaNoReinsidencia,
                        NC.NumeroNCAccionCorrectiva,NC.Causas, NC.Accion, NC.ResultadoRevision,
                        (TIMESTAMPDIFF(DAY, '1900-01-01', NC.FRevision)+2) AS FRevision,
                        NC.MotivoCierre, NC.NuevaAccion, (TIMESTAMPDIFF(DAY, '1900-01-01', NC.FCierre)+2) AS FCierre,
                        NC.RCierre, IF(NC.Borrada,'Si','No') AS Borrada
                    FROM calidad.procesos Pr
                    JOIN calidad.noconformidades NC ON Pr.CodGestion=NC.CodGestion AND CONCAT(Pr.Colegio,Pr.CodProceso)=NC.Proceso
                    LEFT JOIN recursos.emplea E ON NC.Responsable=E.Nit_CCE ".$Filtrico;
		
        $Result = $mysqli->query($Queri) or die(json_encode([["Codigo"=>"Error", "Mensaje"=>mysqli_error($mysqli)]]));
        
        $Campos="rows = prez.map(row => ({NumeroNC:row.NumeroNC,".
                                        "CodGestion:row.CodGestion,".
                                        "NomProceso:row.NomProceso,".
                                        "Tipo:row.Tipo,".
                                        "NomResponsable:row.NomResponsable,".
                                        "FApertura:(isNaN(parseInt(row.FApertura,10)) ? '' :parseInt(row.FApertura,10)),".
                                        "DetectadaEn:row.DetectadaEn,".
                                        "Descripcion:row.Descripcion,".
                                        "'Actividades (Correccion)':row.Correccion,".
                                        "'Acción Garantiza No Reinsidencia':row.GarantiaNoReinsidencia,".
                                        "'ID Acción Correctiva':row.NumeroNCAccionCorrectiva,".
                                        "'Causas o Ventajas':row.Causas,".
                                        "'Objetivo plan Acción':row.Accion,".
                                        "ResultadoRevision:row.ResultadoRevision,".
                                        "FRevision:(isNaN(parseInt(row.FRevision,10)) ? '' :parseInt(row.FRevision,10)),".
                                        "MotivoCierre:row.MotivoCierre,".
                                        "NuevaAccion:row.NuevaAccion,".
                                        "'Responsable Cierre':row.RCierre,".
                                        "FCierre:(isNaN(parseInt(row.FCierre,10)) ? '' :parseInt(row.FCierre,10)),".
                                        "Borrada:row.Borrada".
                                        "}));";
        
        $Formato="formatColumn(worksheet, 5, 'dd/mm/yyyy');";
        $Formato.="formatColumn(worksheet, 14, 'dd/mm/yyyy');";
        $Formato.="formatColumn(worksheet, 18, 'dd/mm/yyyy');";
        
        $mArrayCampos = array(
            'Codigo' => 'Campos',
            'Campos' => $Campos,
            'Formato' => $Formato
        );
        
        $respuestaFinal = array();
        $respuestaFinal[] = $mArrayCampos; // El primer elemento son las configuraciones
        
		while($Row=$Result->fetch_assoc()){
            $Array = array();
            foreach ($Row as $Clave => $Valor){
                // Validación para evitar que PHP arroje warnings con datos nulos
                if($Valor !== null && strlen((string)$Valor)==10 && strpos($Valor, '-') !== false && DarFecha($Valor)>'0-0-0'){
                    $Array[$Clave]=str_replace('/','-',DarFecha($Valor));
                }else{
                    $Array[$Clave]=trim((string)$Valor);
                }
            }
            $respuestaFinal[] = $Array;
		}
        
        // Retornamos TODO el JSON de una sola vez de forma estructurada y segura
        echo json_encode($respuestaFinal, JSON_UNESCAPED_UNICODE);
        mysqli_close($mysqli);
        exit;
    }
	mysqli_close($mysqli);
	exit;
} elseif($_POST['TipoModificar']==md5('JorReversarNoConformidad'.date('d')) and $_POST['TipoGrabar']=='ReversarNoConformidad' and $_POST['NumeroNC']){
    
    if(!$PuedeReversar){
        echo json_encode(["status" => "error", "message" => "No tiene permisos para reversar no conformidades"]);
    } else {
        $QueriReversar = "UPDATE calidad.noconformidades SET 
                            FCierre = NULL,
                            RCierre = NULL,
                            IPCierre = NULL,
                            FHCierre = NULL,
                            ResultadoRevision = '',
                            MotivoCierre = '',
                            NuevaAccion = ''
                        WHERE NumeroNC = ".$_POST['NumeroNC']."
                        AND FCierre IS NOT NULL
                        LIMIT 1";
        
        $ResultReversar = $mysqli->query($QueriReversar) or die(json_encode(["status" => "error", "message" => mysqli_error($mysqli)]));
        
        if($mysqli->affected_rows == 0){
            echo json_encode(["status" => "error", "message" => "No se pudo reversar la no conformidad. Verifique que esté cerrada."]);
        } else {
            echo json_encode(["status" => "success"]);
        }
    }
    
    mysqli_close($mysqli);
    exit;
}

//Obtengo el listado de las Gestiones
$QueriGes = "SELECT * FROM calidad.gestiones ORDER BY NomGestion";
$ResultGes = $mysqli->query($QueriGes);?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<title>MANEJO DE NO CONFORMIDADES Y MEJORAS</title>
<link rel="stylesheet" href="../librerias/jquery/jquery-ui.css">
<script src="../librerias/jquery/jquery-1.10.2.js"></script>
<script src="../librerias/jquery/jquery-ui.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.sheetjs.com/xlsx-latest/package/dist/xlsx.full.min.js"></script>
<script src="../funciones.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<style>
:root {
    --primary-dark: #1A2B4C;
    --accent-gold: #EAA315;
    --bg-light: #F4F7F6;
}
body {
    background-color: var(--bg-light);
    color: #333;
    font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    padding-top: 20px !important; 
}
/* Tarjeta Fija de Filtros COMPACTA */
.filter-card {
    background: white;
    border-radius: 8px;
    border-left: 4px solid var(--accent-gold);
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    padding: 0.3rem 1rem !important; 
    margin-bottom: 0.4rem !important;
    z-index: 1020;
}
.filter-label {
    font-size: 0.65rem;
    font-weight: 600;
    color: #6c757d;
    margin-bottom: 0 !important; 
    line-height: 1.2;
}
/* Reducir la altura de inputs y botones a 28px */
.filter-card .form-select-sm, 
.filter-card .form-control-sm,
.filter-card .btn {
    min-height: 28px !important;
    height: 28px !important;
    padding-top: 0.1rem !important;
    padding-bottom: 0.1rem !important;
    font-size: 0.75rem !important;
    display: flex;
    align-items: center;
}
/* Reducir la altura del buscador Select2 */
.filter-card .select2-container--bootstrap-5 .select2-selection {
    min-height: 28px !important;
    height: 28px !important;
    padding: 0.1rem 0.5rem !important;
    font-size: 0.75rem !important;
}
.filter-card .select2-container--bootstrap-5 .select2-selection__rendered {
    line-height: 24px !important;
}

.input-dashed {
    border: 1.5px dashed #adb5bd !important;
    border-radius: 8px;
    color: #6c757d;
}

.select2-container { 
    width: 100% !important; 
}

.select2-is-invalid .select2-selection { 
    border-color: #dc3545 !important; 
    background-color: #fff8f8 !important; 
}

/* Botones Personalizados */
.btn-dark-blue { background-color: var(--primary-dark); color: white; border-radius: 8px; }
.btn-dark-blue:hover { background-color: #121f3a; color: white; }
.btn-gold { background-color: var(--accent-gold); color: white; border-radius: 8px; }
.btn-gold:hover { background-color: #d19112; color: white; }
.btn-outline-gray { border: 1px solid #dee2e6; color: #495057; background: white; border-radius: 8px; }
/* Etiquetas (Badges) */
.badge-version { background-color: #e9ecef; color: #495057; font-weight: 600; padding: 0.35em 0.8em; border-radius: 20px; font-size: 0.75rem; }
.badge-code { color: var(--accent-gold); font-weight: 700; font-size: 0.8rem; }

/* Tabla con Encabezado Fijo, Scroll Horizontal y ALTURA FORZADA al 100% */
.tableFixHead {
    height: calc(100vh - 115px);
    overflow-y: auto;
    overflow-x: auto;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    width: 100%;
}
/* Forzar que la tabla respete los porcentajes y no se aplaste */
.tableFixHead .table {
    width: 100%;
    min-width: 900px; /* Ancho mínimo para que las letras nunca se apilen */
    table-layout: fixed; 
    margin-bottom: 0;
}

.tableFixHead thead th {
    position: sticky;
    top: 0;
    background-color: var(--primary-dark) !important;
    color: white !important;
    z-index: 3;
    font-weight: 600;
    font-size: 0.75rem; 
    padding: 0.6rem 0.8rem; /* Un poco más de aire en el encabezado */
    border-bottom: none;
}
.tableFixHead thead th:first-child { border-top-left-radius: 8px; }
.tableFixHead thead th:last-child { border-top-right-radius: 8px; }

.table tbody td {
    padding: 0.4rem 0.8rem; /* Celdas con un respiro vertical */
    vertical-align: middle;
    font-size: 0.75rem; 
    border-bottom: 1px solid #f0f4f8;
    word-wrap: break-word; /* Evita que palabras muy largas rompan la tabla */
}

/* Corrección del corte de texto en la descripción */
.desc-limitada {
    display: -webkit-box;
    -webkit-line-clamp: 2; /* Límite de 2 líneas */
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
    line-height: 1.4; /* Altura de línea definida */
    max-height: 2.8em; /* Exactamente el doble del line-height para que no corte letras */
    white-space: normal;
}
/* Ajuste Select2 al nuevo diseño */
.select2-container--bootstrap-5 .select2-selection { border-radius: 8px; font-size: 0.85rem; border-color: #dee2e6; padding: 0.15rem; }
.ui-autocomplete { z-index: 2147483647 !important; }
.myAlert-top { position: fixed; top: 15px; left: 50%; transform: translateX(-50%); width: 90%; max-width: 600px; z-index: 1055; box-shadow: 0 4px 12px rgba(0,0,0,0.15); border-radius: 6px; }
</style>
<script type="text/javascript">
$(function(){//Para Fechas
	$('#DivDetectadaEnOtra,#DivRequiereAC,#DivNumeroNCAccionCorrectiva').hide();
	$.datepicker.regional['es'] = {
        closeText: 'Cerrar',
        prevText: '&#x3c;Ant',
        nextText: 'Sig&#x3e;',
        currentText: 'Hoy',
        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
        dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
        dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Juv', 'Vie', 'Sáb'],
        dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'],
        weekHeader: 'Sm',
        dateFormat: 'dd/mm/yy',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ''
    }
	$.datepicker.setDefaults($.datepicker.regional['es']);
    $( "#FApertura,#FRevision,#FCierre").datepicker({ dateFormat: 'dd-mm-yy' });

    // Aplicar Select2 a los filtros de la barra superior
    $('#FiltroCodGestion, #FiltroProceso, #FiltroTipo, #FiltroTipoAcciones').select2({
        theme: 'bootstrap-5', // Usa el diseño de Bootstrap 5
        language: "es",       // Para que diga "No se encontraron resultados" en español
        width: 'resolve'      // Respeta el tamaño del contenedor
    });

    // Aplicar Select2 a los selects dentro de la ventana de "Crear"
    $('#CodGestion, #Proceso, #DetectadaEn, #Tipo').select2({
        theme: 'bootstrap-5',
        dropdownParent: $('#ModalEditarNoConformidad'),
        width: '100%' 
    });
});

function MostrarDatoObser(msg, esError = false){
    let mDiv = $("#DatoObser");
    mDiv.hide();
    if(msg){
        mDiv.html((esError ? "⚠️ " : "✅ ") + msg);
    }
    if(esError){
        mDiv.removeClass("alert-success").addClass("alert-danger fw-bold");
    } else {
        mDiv.removeClass("alert-danger fw-bold").addClass("alert-success");
    }
    mDiv.show();
    setTimeout(function(){ mDiv.hide(); }, 3500); 
}

function ValidarDetectadaEn(Valorcito){
	if(Valorcito == "Otra" || Valorcito == "Quejas"){
		document.FrmNoConformidad.DetectadaEnOtra.value='';
		$('#DivDetectadaEnOtra').show();
	}else{
		$('#DivDetectadaEnOtra').hide();
	}
}
function MostrarDivConsultaNoConformidad(mNumeroNC){
	$( "#ModalConsultaNoConformidadBody" ).load("noconformidades.php?TipoModificar=<?php echo md5('AjaxMostrarNoConformidad'.date('d'));?>&NumeroNC="+mNumeroNC,
						$("#ModalConsultaNoConformidad").modal('show'));
}

function MostrarDivEditarNoConformidad(mNumeroNC){
	document.FrmNoConformidad.reset();
	document.getElementById('NumeroNC').value=mNumeroNC;
	$("#FrmNoConformidad").find("input:text,select,textarea").removeClass("alert-danger is-invalid");
	
	if(mNumeroNC=='Nuevo'){
		document.getElementById('Responsable').value = '<?php echo $_SESSION['NomUsuario'];?>';
		document.getElementById('FApertura').value = '<?php echo date('d/m/Y');?>';
		document.getElementById('FileNoConformidad').dataset.yacargado=0;
        
        // Limpiamos ocultando campos dependientes por seguridad al crear uno nuevo
		$('#DivDetectadaEnOtra, #DivRequiereAC, #DivNumeroNCAccionCorrectiva').hide();
		document.getElementById('Proceso').length = 1;

		$("#ModalEditarNoConformidad").modal('show');
	}else{
		$.ajax({
			type: "GET",
			url: 'noconformidades.php',
			data: {
                TipoModificar : '<?php echo md5('Ajax1JorA1NoConformidad'.date('d'));?>',
				NumeroNC: mNumeroNC
            },
			dataType: "json", // <--- Pide el JSON al servidor
			cache: false,
		}).done(function(data){
			if(data.status === "Error" || data === "Error"){
				MostrarDatoObser("Se presento un error");
				return false;
			}else{
				// Asignamos los datos del JSON a los campos del formulario
				document.getElementById('CodGestion').value = data.CodGestion;
				CambioGestionProceso(data.Proceso);
				
				document.getElementById('Responsable').value = data.NomResponsable;
				document.getElementById('Tipo').value = data.Tipo;
				document.getElementById('FApertura').value = data.FApertura;
				
				let detectadaEn = document.getElementById('DetectadaEn');
				detectadaEn.value = data.DetectadaEn;
				if(detectadaEn.selectedIndex <= 0 && data.DetectadaEn !== ''){
					detectadaEn.value = 'Otra';
				}
				ValidarDetectadaEn(detectadaEn.value);
				
				document.getElementById('DetectadaEnOtra').value = data.DetectadaEnOtra;
				document.getElementById('Descripcion').value = data.Descripcion;
				document.getElementById('Correccion').value = data.Correccion;
				document.getElementById('Observacion').value = data.Observacion || "";
				document.getElementById('RequiereAC').value = data.RequiereAC;
				
				CambioTipo();
				
				document.getElementById('NumeroNCAccionCorrectiva').value = data.NumeroNCAccionCorrectiva;
				document.getElementById('Causas').value = data.Causas;
				document.getElementById('Accion').value = data.Accion;
				document.getElementById('FRevision').value = data.FRevision;
				document.getElementById('FileNoConformidad').dataset.yacargado = data.YaCargado;
				
                $('#CodGestion, #Proceso, #DetectadaEn, #Tipo').trigger('change');

				$("#ModalEditarNoConformidad").modal('show');
			}
		});
	}
}

function GrabarNoConformidad(){
    MostrarDatoObser('Procesando...', false);
    mNumeroNC = document.FrmNoConformidad.NumeroNC.value;
    $("#BotsNoConformidad").hide();
    mRetorno=true;
    
    // Validación de campos obligatorios
    $("#Responsable,#Tipo,#CodGestion,#Proceso,#FApertura,#DetectadaEn,#Descripcion,#FRevision").each(function(){
        if($(this).val()){
            $(this).removeClass("is-invalid alert-danger"); 
            if($(this).hasClass('select2-hidden-accessible')) {
                $(this).next('.select2-container').removeClass('select2-is-invalid');
            }
        }else{
            mRetorno=false;
            $(this).addClass("is-invalid");
            if($(this).hasClass('select2-hidden-accessible')) {
                $(this).next('.select2-container').addClass('select2-is-invalid'); 
            }
        }
    });
    
    if(mRetorno){
        var input = document.getElementById("FileNoConformidad");
        if(input.files.length>0){
            if(!(input.files[0].type=="application/pdf")){
                $("#BotsNoConformidad").show();
                MostrarDatoObser("¡Error! Recuerde que solo se admiten archivos en formato PDF", true);
                return false;
            }
        }
    }
    
    if(mRetorno){
        document.FrmNoConformidad.TipoGrabar.value='GrabarNoConformidad';
        document.FrmNoConformidad.TipoModificar.value='<?php echo md5('JorGrabarNoConformidad'.date('d'));?>';
        var f = new FormData(document.getElementById('FrmNoConformidad'));
        
        $.ajax({
            url: "noconformidades.php",
            type:'post',
            cache: false,
            data: f,
            dataType: "json", // Trabaja con JSON
            processData: false,
            contentType: false
        }).done(function(response){
            if(response.status ==='success'){
                MostrarDatoObser("Los datos se grabaron correctamente.", false);
                $("#FiltroNumeroNC").val(response.id);
                setTimeout("document.FrmFiltrar.submit()", 800);
            }else{
                $("#BotsNoConformidad").show();
                MostrarDatoObser("Error: " + (response.message || "No se pudo guardar"), true);
            }
        }).fail(function(jqXHR, textStatus, errorThrown){
            $("#BotsNoConformidad").show();
            MostrarDatoObser("Error de conexión o formato inválido en el servidor.", true);
        });
    }else{
        $("#BotsNoConformidad").show();
        MostrarDatoObser("Los campos resaltados en rojo son obligatorios.", true);
    }
}

function MostrarDivSeguimientos(mNumeroNCSeguimientos){
	document.FrmSeguimientos.reset();
	document.getElementById('NumeroNCSeguimientos').value=mNumeroNCSeguimientos;
	$("#FrmSeguimientos").find("input:text,select,textarea").removeClass( "alert-danger");
	$("#DivSeguimientos").load("noconformidades.php?TipoModificar=<?php echo md5('AjaxSeguimientos'.date('d'));?>&NumeroNC="+mNumeroNCSeguimientos,function(){
						$("#ModalSeguimientos").modal('show');
						$("#DivSeguimientos input[id^='Fecha']").datepicker({ dateFormat: 'dd-mm-yy' });
						});
}
function BorrarSeguimiento(Obj){
	mID=Obj.parentElement.id.replace("BotBorrarSeguimiento", "")
	document.getElementById('Borrada'+mID).value=1;
	$("#DivSeguimiento"+mID).hide();
}
function AgregarSeguimiento(){
	mRegNew = parseInt(document.getElementById('CantSeguimientos').value,10);
	if(isNaN(mRegNew)){
		mRegNew=1;
	}else{
		mRegNew++;
	}
	document.getElementById('CantSeguimientos').value=mRegNew;
	$('#DivSeguimiento1').clone().find("input,select,a,button,textarea,div").each(function(){
		$(this).attr({
			'id': function(_, id) { return (id ? id.replace(1, mRegNew) : null)},
			'name': function(_, name) { return (name ? name.replace(1, mRegNew) : null)},
			'checked': false
		});
		$(this).val(($(this).is(':checkbox') ? 1 : ''));
	}).end().appendTo('#DivSeguimientos');
	$('#SpanSeguimiento'+mRegNew).html(mRegNew);
	$('#DivSeguimientos div[id^="DivSeguimiento1"]:last').show();
	$('#DivSeguimientos div[id^="DivSeguimiento1"]:last').attr( "id", "DivSeguimiento"+mRegNew);
	$("#Fecha"+mRegNew).datepicker({dateFormat: 'dd-mm-yy'});
}
function GrabarSeguimientos(){
	MostrarDatoObser('Procesando');
	mNumeroNCSeguimientos = document.getElementById('NumeroNCSeguimientos').value;
	$("#BotsSeguimientos").hide();
	mRetorno=true;
	mCantSeguimientos=document.getElementById('CantSeguimientos').value;
	for(NumSeguimiento=1; NumSeguimiento<=mCantSeguimientos; NumSeguimiento++){
		if(document.getElementById('Borrada'+NumSeguimiento) && document.getElementById('Borrada'+NumSeguimiento).value!=1){
			$("#Accion"+NumSeguimiento+",#Resultado"+NumSeguimiento+",#Fecha"+NumSeguimiento+",#RealizadoPor"+NumSeguimiento).each(function(){
				if($(this).val()){
					$(this).removeClass("alert-danger");
				}else{
					mRetorno=false;
					$(this).addClass("alert-danger");
				}
			});
		}
	}
	if(mRetorno){
		document.FrmSeguimientos.TipoGrabar.value='Seguimientos';
		document.FrmSeguimientos.TipoModificar.value='<?php echo md5('JorSeguimientos'.date('d'));?>';
		var f =  new FormData(document.getElementById('FrmSeguimientos'));
		$.ajax({
			url: "noconformidades.php",
			type:'post',
			cache: false,
			data: f,
			dataType: "json", // Ahora usa JSON
			processData: false,
			contentType: false
		}).done(function(response){
			if(response.status === 'success'){
				MostrarDatoObser("Los datos se grabaron.",false);
				setTimeout("location.reload()", 500);
			}else{
				$("#BotsSeguimientos").show();
				MostrarDatoObser(response.message || "Error al guardar", true);
			}
		});
	}else{
		$("#BotsSeguimientos").show();
		MostrarDatoObser("Los campos resaltados son obligatorios.", true);
	}
}
function MostrarDivCerrarNoConformidad(mNumeroNCCerrar){
	document.FrmCerrarNoConformidad.reset();
	document.getElementById('NumeroNCCerrar').value=mNumeroNCCerrar;
	$("#FrmCerrarNoConformidad").find("input:text,select,textarea").removeClass( "alert-danger");
	$.ajax({
		type: "GET",
		url: 'noconformidades.php',
		data: {TipoModificar : '<?php echo md5('AjaxCerrarNoConformidad'.date('d'));?>',
				NumeroNC:mNumeroNCCerrar},
		cache: false,
	}).done(function(html){
		if(html=="Error"){
			MostrarDatoObser("Se presentó un error", true);
			return false;
		}else{
			eval(html);
			$("#ModalCerrarNoConformidad").modal('show');
		}
	});
}
function GrabarCerrarNoConformidad(){
	MostrarDatoObser('Procesando');
	mNumeroNCCerrar = document.getElementById('NumeroNCCerrar').value;
	$("#BotsCerrarNoConformidad").hide();
	mRetorno=true;
	$("#ResultadoRevision,#MotivoCierre,#NuevaAccion,#FCierre,#RCierre").each(function(){
		if($(this).val()){
			$(this).removeClass("alert-danger");
		}else{
			mRetorno=false;
			$(this).addClass("alert-danger");
		}
	});
	if(mRetorno){
		document.FrmCerrarNoConformidad.TipoGrabar.value='CerrarNoConformidad';
		document.FrmCerrarNoConformidad.TipoModificar.value='<?php echo md5('JorCerrarNoConformidad'.date('d'));?>';
		var f =   new FormData(document.getElementById('FrmCerrarNoConformidad'));
		$.ajax({
			url: "noconformidades.php",
			type:'post',
			cache: false,
			data: f,
			dataType: "json", // Ahora usa JSON
			processData: false,
			contentType: false
		}).done(function(response){
			if(response.status === 'success'){
				MostrarDatoObser("Los datos se grabaron.",false);
				setTimeout("location.reload()", 500);
			}else{
				$("#BotsCerrarNoConformidad").show();
				MostrarDatoObser(response.message || "Error", true);
			}
		});
	}else{
		$("#BotsCerrarNoConformidad").show();
		MostrarDatoObser("Los campos resaltados son obligatorios.", true);
	}
}
function CambioGestionProceso(mProceso=''){
	mCodGestion=document.getElementById('CodGestion').value;
	if(mCodGestion){
		$.ajax({
			type: "GET",
			url: 'noconformidades.php',
			data: {TipoModificar : '<?php echo md5('JorCambioGestionProceso'.date('d'));?>',
					CodGestion:	mCodGestion,
					Proceso:mProceso},
			cache: false,
		}).done(function(html){
			if(html=="Error"){
				MostrarDatoObser("Se presentó un error");
				return false;
			}else{
				eval(html);
			}
		});
	}else{
		document.getElementById("Proceso").length=1;
	}
}
function CambioTipo(){
	mCodTipo=document.getElementById('Tipo').value;
	mRequiereAC=document.getElementById('RequiereAC').value;
	if(mCodTipo=='Corrección'){
        $("#DivRequiereAC").show();
        if(mRequiereAC=='-1'){
            $("#DivNumeroNCAccionCorrectiva").show();
        }else{
            $("#DivNumeroNCAccionCorrectiva").hide();
        }
	}else{
        $("#DivRequiereAC,#DivNumeroNCAccionCorrectiva").hide();
	}
}
function MostrarDivReportes(){
	$("#ModalReportes").modal('show');
}
function EnviarReportes(){
	MostrarDatoObser('Generando Reporte...', false);
	$("#BotsReportes").hide();
	mRetorno=true;
    
    if($("#TipoReporte").val()=='ReporteNoConformidades'){
        mCampos="#TipoReporte";
    }else{
        mCampos="#TipoReporte";
    }
    
	$(mCampos).each(function(){
		if($(this).val()){
			$(this).removeClass("alert-danger is-invalid");
		}else{
			mRetorno=false;
			$(this).addClass("alert-danger is-invalid");
		}
	});
    
	if(mRetorno){
        (async() => {
            try {
                const url = "noconformidades.php?TipoModificar=EnviarReportes&TipoGrabar=<?php echo md5('JorEnviarReportes'.date('d'));?>"+
                      "&TipoReporte="+$("#TipoReporte").val()+
                      "&ReportProceso="+$("#ReportProceso").val();
                
                const response = await fetch(url);
                if(!response.ok) throw new Error("Fallo de red en el servidor");
                
                const raw_data = await response.json();
                
                // Si el servidor devolvió un error de base de datos controlado
                if(raw_data[0] && raw_data[0].Codigo === 'Error'){
                    throw new Error(raw_data[0].Mensaje);
                }

                mCampos = raw_data[0]; // Extrae configuraciones
                raw_data.shift(); // Quita configuraciones, deja solo los datos
                const prez = raw_data;
                
                eval(mCampos['Campos']); // Procesa la estructura del Excel
                const worksheet = XLSX.utils.json_to_sheet(rows);
                
                eval(mCampos['Formato']); // Procesa las fechas
                const workbook = XLSX.utils.book_new();
                XLSX.utils.book_append_sheet(workbook, worksheet, "Datos");
                
                XLSX.writeFile(workbook, "Reporte_NoConformidades.xlsx", { compression: true });
                
                MostrarDatoObser("Reporte descargado exitosamente", false);
                $("#BotsReportes").show();
                
            } catch(e) {
                console.error("Error al generar Excel: ", e);
                $("#BotsReportes").show();
                MostrarDatoObser("Ocurrió un error generando el archivo. Revise la conexión.", true);
            }
        })();
	}else{
		$("#BotsReportes").show();
		MostrarDatoObser("El tipo de reporte es obligatorio.", true);
	}
}
function formatColumn(worksheet, col, fmt) {
    const range = XLSX.utils.decode_range(worksheet['!ref'])
    for (let row = range.s.r + 1; row <= range.e.r; ++row) {
        const ref = XLSX.utils.encode_cell({ c: col, r: row})
        if (worksheet[ref] && worksheet[ref].t === 'n') {
            worksheet[ref].z = fmt
        }
    }
}
function CambioTipoReporte(Obj){
	$("#DivFechasReporte").hide();
    if(['ReporteNoConformidades'].indexOf(Obj.value)>=0){
		$("#DivFechasReporte").show();
	}
}

function ReversarNoConformidad(mNumeroNC){
    if(confirm('¿Está seguro que desea reversar esta no conformidad? Esto borrará la fecha de cierre y permitirá editarla nuevamente.')){
        MostrarDatoObser('Procesando');
        $.ajax({
            type: "POST",
            url: 'noconformidades.php',
            data: {
                TipoModificar: '<?php echo md5('JorReversarNoConformidad'.date('d'));?>',
                TipoGrabar: 'ReversarNoConformidad',
                NumeroNC: mNumeroNC
            },
            dataType: 'json', // JSON
            cache: false,
        }).done(function(response){
            if(response.status === 'success'){
                MostrarDatoObser("No conformidad reversada exitosamente.", false);
                setTimeout("location.reload()", 1000);
            }else{
                MostrarDatoObser("Error al reversar: " + (response.message || ""), true);
            }
        });
    }
}
</script>
</head>
<body style="padding-top: 60px;" <?php if($_GET['DatoObser']){?>onLoad="MostrarDatoObser('<?php echo $_GET['DatoObser'];?>',false);"<?php }?>>
<div class="myAlert-top alert alert-success" id="DatoObser" style="z-index:1000000; display:none;"></div>
<div class="container-fluid px-1">
<div class="container-fluid px-2 px-md-3 pb-4">
    <!-- TARJETA FIJA DE FILTROS ULTRA COMPACTA -->
    <div class="sticky-top filter-card" style="top: 5px;">
        
        <!-- Cabecera: Título y Controles Superiores -->
        <div class="d-flex justify-content-between align-items-center mb-1">
            
            <!-- Lado Izquierdo: Título y Versión -->
            <div class="d-flex align-items-center gap-2">
                <h6 class="mb-0 fw-bold" style="color: var(--primary-dark); font-size: 0.9rem;">No Conformidades & Mejoras</h6>
                <span class="badge-version" style="font-size: 0.6rem; padding: 0.15em 0.6em;">Versión 2</span>
                <span class="badge-code" style="font-size: 0.65rem;">Código PGGC-04-R-01</span>
            </div>
            
            <!-- Lado Derecho: Botones PC + Hamburguesa Móvil -->
            <div class="d-flex align-items-center gap-2">
                
                <!-- BOTONES VERSIÓN PC (Visibles en pantallas grandes, ocultos en móvil) -->
                <div class="d-none d-xl-flex gap-1">
                    <button class="btn btn-dark-blue justify-content-center p-0" type="button" onClick="document.getElementById('FrmFiltrar').submit();" style="width: 28px;" title="Buscar">
                        <svg width="12" height="12" fill="currentColor" viewBox="0 0 16 16"><path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/></svg>
                    </button>
                    <a onClick="MostrarDivEditarNoConformidad('Nuevo');" class="btn btn-gold justify-content-center p-0" style="width: 28px; cursor:pointer;" title="Nuevo Registro">
                        <svg width="15" height="15" fill="currentColor" viewBox="0 0 16 16"><path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/></svg>
                    </a>
                    <button class="btn btn-outline-gray justify-content-center p-0" type="button" onClick="MostrarDivReportes();" style="width: 28px;" title="Reportes">
                        <svg width="12" height="12" fill="currentColor" viewBox="0 0 16 16"><path d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm15 2h-4v3h4V4zm0 4h-4v3h4V8zm0 4h-4v3h3a1 1 0 0 0 1-1v-2zm-5 3v-3H6v3h4zm-5 0v-3H1v2a1 1 0 0 0 1 1h3zm-4-4h4V8H1v3zm0-4h4V4H1v3zm5-3v3h4V4H6zm4 4H6v3h4V8z"/></svg>
                    </button>
                    <a href="../indexadmon.php" class="btn btn-outline-gray justify-content-center p-0" style="width: 28px;" title="Inicio">
                        <svg width="13" height="13" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M2 13.5V7h1v6.5a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5V7h1v6.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5zm11-11V6l-2-2V2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5z"/><path fill-rule="evenodd" d="M7.293 1.5a1 1 0 0 1 1.414 0l6.647 6.646a.5.5 0 0 1-.708.708L8 2.207 1.354 8.854a.5.5 0 1 1-.708-.708L7.293 1.5z"/></svg>
                    </a>
                </div>

                <!-- Botón Hamburguesa (Visible en móvil, oculto en PC) -->
                <button class="btn btn-light border d-xl-none p-1 shadow-sm" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFiltros" style="width: 28px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#6c757d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
                </button>
            </div>
        </div>

        <!-- Contenedor Colapsable (Filtros y botones móviles) -->
        <div class="collapse d-xl-block" id="collapseFiltros">
            <form method="get" class="d-flex flex-wrap gap-2" name="FrmFiltrar" id="FrmFiltrar">
                <input name="Filtrar" type="hidden" id="Filtrar" value="1">
                
                <div style="flex: 1; min-width: 120px;">
                    <div class="filter-label">Gestión</div>
                    <select name="FiltroCodGestion" class="form-select form-select-sm" id="FiltroCodGestion">
                        <option value="">Gestión</option><?php
                        mysqli_data_seek($ResultGes, 0);
                        for ($j = 1; $j <= $ResultGes->num_rows; $j++){
                            $RowGes = $ResultGes->fetch_assoc();?>
                            <option value="<?php echo $RowGes['CodGestion'];?>" <?php if ($_GET['FiltroCodGestion']==$RowGes['CodGestion'])echo "selected";?>><?php echo $RowGes['NomGestion'];?></option><?php
                        }?>
                    </select>
                </div>

                <div style="flex: 1; min-width: 140px;">
                    <div class="filter-label">Proceso</div>
                    <select name="FiltroProceso" class="form-select form-select-sm" id="FiltroProceso">
                        <option value="" selected>Cualquier proceso</option><?php
                        if($PuedeAdministrar or $PuedeGeneral){
                            $QueriProc = "SELECT CodGestion, Colegio, CodProceso, NomProceso FROM calidad.procesos ORDER BY CodGestion, NomProceso";
                        }else{
                            $QueriProc = "SELECT DISTINCT CodGestion, Colegio, CodProceso, NomProceso FROM calidad.procesos WHERE FIND_IN_SET('".$_SESSION["Usuario"]."',DueProceso) ORDER BY CodGestion, NomProceso";
                        }
                        $ResultProc = $mysqli->query($QueriProc);
                        while($RowProc = $ResultProc->fetch_assoc()){?>
                        <option value="<?php echo $RowProc['Colegio'].$RowProc['CodProceso'];?>" <?php if ($_GET['FiltroProceso']==$RowProc['Colegio'].$RowProc['CodProceso'])echo "selected";?>><?php echo $RowProc['CodGestion'].' '.$RowProc['NomProceso'];?></option><?php
                        }?>
                    </select>
                </div>

                <div style="flex: 1; min-width: 120px;">
                    <div class="filter-label">Tipo de acción</div>
                    <select name="FiltroTipo" class="form-select form-select-sm" id="FiltroTipo">
                        <option value="" selected>Tipo Registro</option><?php
                        foreach ($mTipoRegistro as $Clave => $Valor){?>
                            <option value='<?php echo $Clave;?>' <?php if ($_GET['FiltroTipo']==$Clave)echo "selected";?>><?php echo $Clave;?></option><?php
                        }?>
                    </select>
                </div>

                <div style="flex: 1; min-width: 110px;">
                    <div class="filter-label">Estado</div>
                    <select name="FiltroTipoAcciones" class="form-select form-select-sm" id="FiltroTipoAcciones">
                        <option value="" selected>Todas</option>
                        <option value=1 <?php if ($_GET['FiltroTipoAcciones']==1)echo "selected";?>>Sin Acciones</option>
                        <option value=2 <?php if ($_GET['FiltroTipoAcciones']==2)echo "selected";?>>Con Acciones</option>
                        <option value=3 <?php if ($_GET['FiltroTipoAcciones']==3)echo "selected";?>>Por Cerrar</option>
                        <option value=4 <?php if ($_GET['FiltroTipoAcciones']==4)echo "selected";?>>Cerradas</option>
                    </select>
                </div>

                <div style="flex: 1; min-width: 130px;">
                    <div class="filter-label">Orden</div>
                    <select name="Ordencito" class="form-select form-select-sm" id="Ordencito">
                        <option value=2 <?php if ($_GET['Ordencito']==2)echo "selected";?>>Ordenar por Fecha</option>
                        <option value=1 <?php if ($_GET['Ordencito']==1)echo "selected";?>>Ordenar por Número</option>
                    </select>
                </div>

                <div style="width: 70px;">
                    <div class="filter-label">N° reg</div>
                    <input type="text" name="FiltroNumeroNC" id="FiltroNumeroNC" class="form-control form-control-sm text-center input-dashed" value="<?php echo $_GET['FiltroNumeroNC'];?>">
                </div>
                
                <!-- BOTONES VERSIÓN MÓVIL (Visibles en móvil, ocultos en PC) -->
                <div class="d-flex d-xl-none gap-1 w-100 mt-2 justify-content-end border-top pt-2">
                    <button class="btn btn-dark-blue justify-content-center p-0" type="button" onClick="document.getElementById('FrmFiltrar').submit();" style="width: 32px; height: 32px;" title="Buscar">
                        <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/></svg>
                    </button>
                    <a onClick="MostrarDivEditarNoConformidad('Nuevo');" class="btn btn-gold justify-content-center p-0" style="width: 32px; height: 32px; cursor:pointer;" title="Nuevo Registro">
                        <svg width="17" height="17" fill="currentColor" viewBox="0 0 16 16"><path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/></svg>
                    </a>
                    <button class="btn btn-outline-gray justify-content-center p-0" type="button" onClick="MostrarDivReportes();" style="width: 32px; height: 32px;" title="Reportes">
                        <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm15 2h-4v3h4V4zm0 4h-4v3h4V8zm0 4h-4v3h3a1 1 0 0 0 1-1v-2zm-5 3v-3H6v3h4zm-5 0v-3H1v2a1 1 0 0 0 1 1h3zm-4-4h4V8H1v3zm0-4h4V4H1v3zm5-3v3h4V4H6zm4 4H6v3h4V8z"/></svg>
                    </button>
                    <a href="../indexadmon.php" class="btn btn-outline-gray justify-content-center p-0" style="width: 32px; height: 32px;" title="Inicio">
                        <svg width="15" height="15" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M2 13.5V7h1v6.5a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5V7h1v6.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5zm11-11V6l-2-2V2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5z"/><path fill-rule="evenodd" d="M7.293 1.5a1 1 0 0 1 1.414 0l6.647 6.646a.5.5 0 0 1-.708.708L8 2.207 1.354 8.854a.5.5 0 1 1-.708-.708L7.293 1.5z"/></svg>
                    </a>
                </div>
            </form>
        </div>
    </div>
<?php
/************************************************************************************************************************************************
Consultar listado de No Conformidades
************************************************************************************************************************************************/
if($_GET['Filtrar']==1){?>
<div class="row sidenav"><?php
	if($PuedeAdministrar){
		$Filtrico = "WHERE NC.NumeroNC";//Todas, hasta las Borradas
	}elseif($PuedeGeneral){
		$Filtrico = "WHERE NC.Borrada=0";//Todas menos las borradas
	}else{
		$Filtrico = "WHERE NC.Borrada=0 and P.DueProceso LIKE '%".$_SESSION["Usuario"]."%'";
	}
	//Defino el FiltroNumeroNC
	if($_GET['FiltroNumeroNC']){
        $Filtrico .= " AND FIND_IN_SET(NC.NumeroNC,'".$_GET['FiltroNumeroNC']."')";
    }else{
        if($_GET['FiltroTipo']){
            $Filtrico.= " AND NC.Tipo = '".$_GET['FiltroTipo']."'";
        }
        if($_GET['FiltroTipoAcciones']=="" or $_GET['FiltroTipoAcciones']==0){$Filtrico = $Filtrico;}//Por ahora igual
        elseif($_GET['FiltroTipoAcciones']==1){$Filtrico.= " AND NC.Accion = '' or isnull(NC.Accion)";}
        elseif($_GET['FiltroTipoAcciones']==2){$Filtrico.= " AND NC.Accion <> '' and !(isnull(NC.Accion))";}
        elseif($_GET['FiltroTipoAcciones']==3){$Filtrico.= " AND NC.FCierre = '' or isnull(NC.FCierre)";}
        elseif($_GET['FiltroTipoAcciones']==4){$Filtrico.= " AND NC.FCierre <> '' and !(isnull(NC.FCierre))";
        }
        //Defino el FiltroProceso del Queri
        if($_GET['FiltroProceso']){
            $Filtrico = $Filtrico." AND NC.Proceso = '".$_GET['FiltroProceso']."'";
        }
    }
	//Defino el Orden de los resultados
	if ($_GET['Ordencito']==1){
		$Ordenacioncita = " ORDER BY NC.NumeroNC DESC";
	}else{
		$Ordenacioncita = " ORDER BY NC.FApertura DESC";
	}
	//Defino y Ejecuto el Queri
	$Queri = "SELECT NC.*, CONCAT(E.Nom,' ',E.Apellido1) AS NomResponsable
				FROM calidad.procesos P
				JOIN calidad.noconformidades NC ON P.CodGestion=NC.CodGestion AND CONCAT(P.Colegio,P.CodProceso)=NC.Proceso
				LEFT JOIN recursos.emplea E ON NC.Responsable=E.Nit_CCE ".$Filtrico.$Ordenacioncita;
    $Result = $mysqli->query($Queri) or die(mysqli_error($mysqli));?>
	<!-- Contenedor con scroll vertical y altura máxima de 70vh -->
	<div class="tableFixHead border-0">
        <table class="table table-hover table-borderless align-middle mb-0">
            <thead>
                <tr>
                    <th scope="col" width="10%">Fecha / ID</th>
                    <th scope="col" width="17%">Responsable apertura</th>
                    <th scope="col" width="12%">Proceso</th>
                    <th scope="col" width="41%">Descripción</th>
                    <th scope="col" width="10%">Detectada en</th>
                    <th scope="col" width="10%" class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody id="TBodyNoConformidad" class="bg-white">
	<?php
	while($Row = $Result->fetch_assoc()){?>
    <TR align=left>
      <TD><?php echo DarFechaHora($Row['FApertura'],1);?>
		(<a style="cursor:pointer" onClick="MostrarDivConsultaNoConformidad(<?php echo $Row['NumeroNC'] ?>);" title="Ver <?php echo $Row['NumeroNC'];?>"><?php echo $Row['NumeroNC'];?></a>)</TD>
      <TD><?php echo $Row['NomResponsable'];?></TD>
      <TD><?php echo $Row['Proceso'].' ('.$Row['CodGestion'].')';?></TD>
      <TD>
    <div class="desc-limitada" title="<?php echo htmlspecialchars($Row['Descripcion']); ?>">
        <?php echo ($Row['Borrada'] ? ('<b style="background:red">BORRADA '.$Row['Descripcion'].'</b>') : $Row['Descripcion']);?>
    </div></TD>
      <TD><?php echo $Row['DetectadaEn'];?></TD>
      <TD><?php
		if($Row['FCierre']>0){
			echo DarFecha($Row['FCierre']);
            if($PuedeReversar){?>
            <a style="cursor:pointer;" onClick="ReversarNoConformidad(<?php echo $Row['NumeroNC'];?>)" title="Reversar no conformidad"><svg width="20px" height="20px" viewBox="0 0 21 21" xmlns="http://www.w3.org/2000/svg" fill="#000000" stroke="#000000" stroke-width="1.932"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g fill="none" fill-rule="evenodd" stroke="#db7500" stroke-linecap="round" stroke-linejoin="round" transform="matrix(0 1 1 0 2.5 2.5)"> <path d="m3.98652376 1.07807068c-2.38377179 1.38514556-3.98652376 3.96636605-3.98652376 6.92192932 0 4.418278 3.581722 8 8 8s8-3.581722 8-8-8"></path> <path d="m4 1v4h-4" transform="matrix(1 0 0 -1 0 6)"></path> </g> </g></svg></a><?php
			}
		}else{?>
			<a style="cursor:pointer" onClick="MostrarDivEditarNoConformidad(<?php echo $Row['NumeroNC'];?>);" title="Ver <?php echo $Row['NumeroNC'];?>"><img hspace='2' width='16' height='16' src='../imagenes/editar.png' border='0'/></a>
			<a style="cursor:pointer" onClick="MostrarDivSeguimientos(<?php echo $Row['NumeroNC'];?>);" title="Editar Seguimientos ID <?php echo $Row['NumeroNC'];?>"><img hspace='2' width='16' height='16' src='../imagenes/nuevoregistro.png' border='0'/></a><?php
			if(InList($_SESSION["Perfil"],"DIREC","ADMIN","DUEPR")){?>
			<a style="cursor:pointer" onClick="MostrarDivCerrarNoConformidad(<?php echo $Row['NumeroNC'];?>)" title="Cerrar registro"><img hspace='2' width='16' height='16' src='../imagenes/terminar.png' border='0' /></a><?php
			}
		}
		?></TD>
    </TR><?php
	}//fin del while?>
    </tbody>
    </table>
	</div>
</div><?php
}
?>
</div>
<!--VENTANA MODAL EDICIÓN NO CONFORMIDADES-->
<div class="modal fade" id="ModalEditarNoConformidad" tabindex="-1" aria-labelledby="ModalEditarNoConformidadTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
        <div class="modal-content shadow-lg border-0">
            <!-- Header moderno -->
            <div class="modal-header bg-light border-bottom">
                <h5 class="modal-title fw-bold text-primary d-flex align-items-center" id="ModalEditarNoConformidadTitle">
                    <svg width="24" height="24" class="me-2" fill="currentColor" viewBox="0 0 16 16"><path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h13zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13z"/><path d="M5 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 5 8zm0-2.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm0 5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm-1-5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0zM4 8a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0zm0 2.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0z"/></svg>
                    EDITAR/CREAR NO CONFORMIDAD
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body p-4" id="DivBodyNoConformidad">
              <form method="post" enctype="multipart/form-data" name="FrmNoConformidad" id="FrmNoConformidad" autocomplete="off">
                <input name="TipoGrabar" type="hidden" id="TipoGrabar">
                <input name="TipoModificar" type="hidden" id="TipoModificar">
                <input name="NumeroNC" type="hidden" id="NumeroNC">
                
                <div class="row g-4">
                    
                    <!-- Columna 1: Datos Generales -->
                    <div class="col-lg-4">
                        <div class="bg-light border rounded p-3 h-100">
                            <h6 class="text-secondary border-bottom pb-2 mb-3">Datos Generales</h6>
                            
                            <div class="mb-3">
                                <label for="Responsable" class="form-label text-muted small mb-1 fw-bold">Responsable de la apertura</label>
                                <input type="text" name="Responsable" id="Responsable" class="form-control form-control-sm w-100" readonly>
                            </div>
                            
                            <div class="mb-3">
                                <label for="Tipo" class="form-label text-muted small mb-1 fw-bold">Tipo</label>
                                <select name="Tipo" class="form-select form-select-sm w-100" id="Tipo" onChange="CambioTipo()">
                                    <option value="">...</option><?php
                                    foreach ($mTipoRegistro as $Clave => $Valor){?>
                                    <option value='<?php echo $Clave;?>'><?php echo $Clave.' - '.$Valor;?></option><?php
                                    }?>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="CodGestion" class="form-label text-muted small mb-1 fw-bold">Gestión</label>
                                <select name="CodGestion" class="form-select form-select-sm w-100" id="CodGestion" onChange="CambioGestionProceso();">
                                    <option value=""></option><?php
                                    mysqli_data_seek($ResultGes, 0);
                                    for ($j = 1; $j <= $ResultGes->num_rows; $j++){
                                        $RowGes = $ResultGes->fetch_assoc();?>
                                        <option value="<?php echo $RowGes['CodGestion'];?>"><?php echo $RowGes['NomGestion'];?></option><?php
                                    }?>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="Proceso" class="form-label text-muted small mb-1 fw-bold">Proceso en donde se detecta</label>
                                <select name="Proceso" class="form-select form-select-sm w-100" id="Proceso">
                                    <option value="" selected>...</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="FApertura" class="form-label text-muted small mb-1 fw-bold">Fecha de apertura</label>
                                <input type="text" name="FApertura" id="FApertura" class="form-control form-control-sm w-100" onChange="ValidarFecha(this)" onBlur="ValidarFecha(this)">
                            </div>
                            
                            <div class="mb-3">
                                <label for="DetectadaEn" class="form-label text-muted small mb-1 fw-bold">Detectada en</label>
                                <select name="DetectadaEn" class="form-select form-select-sm w-100" id="DetectadaEn" onChange="ValidarDetectadaEn(this.value);">
                                      <option value="">...</option>
                                      <option value="Auditoría Interna">Auditoría Interna</option>
                                      <option value="Auditoría Externa">Auditoría Externa</option>
                                      <option value="Control de proceso">Control de proceso</option>
                                      <option value="Encuesta de satisfacción">Encuesta de satisfacción</option>
                                      <option value="Quejas">Quejas</option>
                                      <option value="Revisión por la dirección">Revisión por la dirección</option>
                                      <option value="Otra">Otra</option>
                                </select>
                            </div>
                            
                            <div class="mb-3" id="DivDetectadaEnOtra">
                                <label class="form-label text-muted small mb-1 fw-bold">¿Cual?</label>
                                <input name="DetectadaEnOtra" class="form-control form-control-sm w-100" id="DetectadaEnOtra" maxlength="30">
                            </div>
                        </div>
                    </div>

                    <!-- Columna 2: Detalles de la Acción -->
                    <div class="col-lg-4">
                        <div class="border rounded p-3 h-100">
                            <h6 class="text-secondary border-bottom pb-2 mb-3">Descripción de la Acción</h6>
                            <div class="mb-3">
                                <label for="Descripcion" class="form-label text-muted small mb-1 fw-bold">Descripción</label>
                                <div class="alert alert-warning py-1 px-2 mb-2" style="font-size: 0.75rem;">Realice una descripción de la Acción que se materializó o de la acción que desea potencializarse.</div>
                                <textarea name="Descripcion" id="Descripcion" class="form-control form-control-sm" rows="3"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="Correccion" class="form-label text-muted small mb-1 fw-bold">Relación de actividades:</label>
                                <textarea name="Correccion" id="Correccion" class="form-control form-control-sm" placeholder="Describa las actividades..." rows="3"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="Observacion" class="form-label text-muted small mb-1 fw-bold">Observación:</label>
                                <textarea name="Observacion" id="Observacion" class="form-control form-control-sm" placeholder="Ingrese sus observaciones aquí..." rows="3"></textarea>
                            </div>
                            <div class="mb-3" id="DivRequiereAC">
                                <label for="RequiereAC" class="form-label text-muted small mb-1 fw-bold">¿Garantiza que la falla no se presente de nuevo?</label>
                                <select name="RequiereAC" class="form-select form-select-sm" id="RequiereAC" onChange="CambioTipo();">
                                      <option value="">...</option>
                                      <option value="1">Si</option>
                                      <option value="-1">No</option>
                                </select>
                            </div>
                            <div class="mb-3" id="DivNumeroNCAccionCorrectiva">
                                <label for="NumeroNCAccionCorrectiva" class="form-label text-muted small mb-1 fw-bold">ID de la acción correctiva vinculada</label>
                                <input name="NumeroNCAccionCorrectiva" class="form-control form-control-sm" id="NumeroNCAccionCorrectiva" maxlength="11" onBlur="ValidarNumeros(this);">
                            </div>
                        </div>
                    </div>

                    <!-- Columna 3: Plan y Archivos -->
                    <div class="col-lg-4">
                        <div class="bg-light border rounded p-3 h-100">
                            <h6 class="text-secondary border-bottom pb-2 mb-3">Plan de Acción y Adjuntos</h6>
                            <div class="mb-3">
                                <label for="Causas" class="form-label text-muted small mb-1 fw-bold">Causas o Ventajas</label>
                                <textarea name="Causas" id="Causas" class="form-control form-control-sm" rows="3"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="Accion" class="form-label text-muted small mb-1 fw-bold">Objetivo principal del plan de Acción</label>
                                <textarea name="Accion" id="Accion" class="form-control form-control-sm" rows="3"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="FRevision" class="form-label text-muted small mb-1 fw-bold">Fecha acordada para la revisión</label>
                                <input type="text" name="FRevision" id="FRevision" class="form-control form-control-sm" onChange="ValidarFecha(this)" onBlur="ValidarFecha(this)">
                            </div>
                            
                            <div class="mb-3 mt-4">
                                <label for="FileNoConformidad" class="form-label text-muted small mb-1 fw-bold">Adjuntar Archivo PDF (Max 4MB)</label>
                                <input class="form-control form-control-sm" name="FileNoConformidad" id="FileNoConformidad" type="file" accept="application/pdf">
                            </div>
                        </div>
                    </div>
                    
                </div>
              </form>
            </div>
            <div class="modal-footer bg-light" id="BotsNoConformidad">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary px-4" onClick="GrabarNoConformidad();">Guardar Registro</button>
            </div>
        </div>
    </div>
</div>
<!--VENTANA MODAL SEGUIMIENTOS-->
<div class="<?php echo "modal fade";?>" id="ModalSeguimientos" tabindex="-1" role="dialog" aria-labelledby="ModalSeguimientosTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document" >
        <div class="modal-content">
            <div class="modal-header">
            	<div class="col-sm-11">
            	<h4 class="modal-title" id="ModalSeguimientosTitle">EDITAR SEGUIMIENTOS CONFORMIDAD</h4>
                </div>
            	<div class="col-sm-1">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body row" id="DivBodySeguimientos">
              <form  method="post" enctype="multipart/form-data" class="form_validar" name="FrmSeguimientos" id="FrmSeguimientos" autocomplete="off">
                <input name="TipoGrabar" type="hidden" id="TipoGrabar">
                <input name="TipoModificar" type="hidden" id="TipoModificar">
                <input name="NumeroNCSeguimientos" type="hidden" id="NumeroNCSeguimientos">
                <div class="col-sm-12" id="DivSeguimientos"></div>
              </form>
            </div>
            <div class="modal-footer" id="BotsSeguimientos">
                <input type="button" class="btn btn-primary" onClick="AgregarSeguimiento();" value="Otro Seguimiento">
                <input type="button" class="btn btn-primary" onClick="GrabarSeguimientos();" value="Aceptar">
            	<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!--VENTANA MODAL CERRAR NO CONFORMIDADES-->
<div class="<?php echo "modal fade";?>" id="ModalCerrarNoConformidad" tabindex="-1" role="dialog" aria-labelledby="ModalCerrarNoConformidadTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document" >
        <div class="modal-content">
            <div class="modal-header">
            	<h3 class="modal-title col-sm-11" id="ModalCerrarNoConformidadTitle">DATOS DEL CIERRE DEL REGISTRO</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body row" id="DivBodyCerrarNoConformidad">
              <form  method="post" enctype="multipart/form-data" class="form_validar" name="FrmCerrarNoConformidad" id="FrmCerrarNoConformidad" autocomplete="off">
                <input name="TipoGrabar" type="hidden" id="TipoGrabar">
                <input name="TipoModificar" type="hidden" id="TipoModificar">
                <input name="NumeroNCCerrar" type="hidden" id="NumeroNCCerrar">
                <div class="col-sm-6 form-group">
                    <label for="ResultadoRevision" class="label-xsmall">Resultados de la revisión de la eficacia</label>
                    <textarea name="ResultadoRevision" id="ResultadoRevision" class="form-control small" rows="3" ></textarea>
                </div>
                <div class="col-sm-6 form-group">
                    <label for="MotivoCierre" class="label-xsmall">Motivos del Cierre</label>
                    <textarea name="MotivoCierre" id="MotivoCierre" class="form-control small" rows="3" ></textarea>
                </div>
                <div class="col-sm-6 form-group">
                    <label for="NuevaAccion" class="label-xsmall">Nueva Acción?</label>
                    <textarea name="NuevaAccion" id="NuevaAccion" class="form-control small" rows="3" ></textarea>
                </div>
				<div class="col-sm-2 form-group">
					<label for="FCierre" class="label-xsmall">Fecha del Cierre</label>
					<input type="text" name="FCierre" id="FCierre" class="form-control" onChange="ValidarFecha(this)" onBlur="ValidarFecha(this)">
				</div>
                <div class="col-sm-4 form-group">
                    <label for="RCierre" class="label-xsmall">Responsable del Cierre</label>
                    <input type="text" name="RCierre" id="RCierre" class="form-control">
                </div>
              </form>
            </div>
            <div class="modal-footer" id="BotsCerrarNoConformidad">
                <input type="button" class="btn btn-primary" onClick="GrabarCerrarNoConformidad();" value="Aceptar">
            	<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!--VENTANA MODAL REPORTES-->
<div class="<?php echo "modal fade";?>" id="ModalReportes" tabindex="-1" role="dialog" aria-labelledby="ModalReportesTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalReportesTitle"><b>Consulta Reportes</b></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body row">
              <form method="post"  enctype="multipart/form-data" class="form_validar" name="FrmReportes" id="FrmReportes" autocomplete="off" target="_blank">
                <input name="TipoGrabar" type="hidden" id="TipoGrabar">
                <input name="TipoModificar" type="hidden" id="TipoModificar">
                <div class="col-sm-4 form-group">
                <label class="label-xsmall">Tipo Reporte</label>
                <select name="TipoReporte" class="form-control" id="TipoReporte" onChange="CambioTipoReporte(this);">
                    <option value="" >...</option>
                    <option value="ReporteNoConformidades">Todos los registros</option>
                </select>
                </div>
                <div class="col-sm-8 form-group" id="DivFechasReporte">
                    <div class="col-sm-4 form-group" title="Proceso"><br>
                    <select name="ReportProceso" id="ReportProceso" class="form-control">
                        <option value="">Proceso(Todos)</option><?php
                    if($PuedeAdministrar or $PuedeGeneral){
                        $QueriProc = "SELECT CodGestion, Colegio, CodProceso, NomProceso FROM calidad.procesos ORDER BY CodGestion, NomProceso";
                    }else{
                        $QueriProc = "SELECT DISTINCT CodGestion, Colegio, CodProceso, NomProceso FROM calidad.procesos WHERE FIND_IN_SET('".$_SESSION["Usuario"]."',DueProceso) ORDER BY CodGestion, NomProceso";
                    }
                    $ResultProc = $mysqli->query($QueriProc);
                    while($RowProc = $ResultProc->fetch_assoc()){?>
                    <option value="<?php echo $RowProc['Colegio'].$RowProc['CodProceso'];?>" <?php if ($_GET['FiltroProceso']==$RowProc['Colegio'].$RowProc['CodProceso'])echo "selected";?>><?php echo $RowProc['CodGestion'].' '.$RowProc['NomProceso'];?></option><?php
                    }?>
                    </select>
                    </div>
                </div>
                <div class="clearfix"></div>
              </form>
            </div>
            <div class="modal-footer" id="BotsReportes">
                <input type="button" class="btn btn-primary" onClick="EnviarReportes();" value="Aceptar">
            	<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
</body>
</html><?php
mysqli_close($mysqli);
?>