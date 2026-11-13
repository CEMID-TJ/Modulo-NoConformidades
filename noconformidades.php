<?php
include ("../seguridad.php");
include('../funciones.php');

// ----------------------------------------------------------------------------
// Permisos
// ----------------------------------------------------------------------------
$PuedeAdministrar = in_array($_SESSION['Usuario'], array(
    (InList($_SESSION["Perfil"], "ADMIN") ? $_SESSION['Usuario'] : "98381246"),
    '79558178',    // CAMILO VELANDIA
    '1054540722',  // Johan Rodriguez
    '1014305622',  // Laura Valentina
    '1073696610',  // Jenniffer Bello
    '52841712',    // Diana Álvarez
    '1233909122',  // Eduardo León
    '1022928448',  // JABIER BARRERA
    '1013602451'   // Thomas Barrero
));
$PuedeCerrar = InList($_SESSION["Perfil"], "DIREC", "ADMIN", "DUEPR") || $PuedeAdministrar;

$PuedeReversar = in_array($_SESSION['Usuario'], array(
    (InList($_SESSION["Perfil"], "ADMIN") ? $_SESSION['Usuario'] : "98381246"),
    '1014305622',  // Laura Valentina
    '1073696610',  // Jenniffer Bello
    '1233909122',  // Eduardo León
    '1022928448'   // JABIER BARRERA
));

$PuedeGeneral = in_array($_SESSION['Usuario'], array(
    (InList($_SESSION["Perfil"], "ADMIN") ? $_SESSION['Usuario'] : "98381246"),
    '39665007',    // MarthaCV
    '79404413',    // Alvaro Parra
    '8730314',     // JavierM
    '37829191',    // Nelcy Jerez
    '40025213',    // Consuelo Ramirez
    '41405252',    // Luz M
    '79879905',    // CarlosL
    '39668167',    // Marcela Piñeros
    '1072665948',  // Jessica Agudelo
    '52903643',    // CASALLAS DEICY
    '1032380671',  // ARDILA GISSED
    '52378375',    // CASTAÑEDA LINA
    '52226604',    // PARROQUIANO MARYSOL
    '80241390',    // ARIAS FABIO
    '52849113',    // PULIDO PEREZ CATERYN
    '1024471606',  // JUDY ANDREA RUEDA GOMEZ
    '53048628',    // FLOREZ CASTAÑEDA DIANA CAROLINA
    '52382262',    // SABOGAL CORTES FLOR XIMENA
    '52159469',    // CASTILLO MARTINEZ IVONNE ASTRID
    '53067789',    // RODRIGUEZ MUÑOZ VIVIANA EMILCE
    '1024509359',  // SEMA CASTIBLANCO MARIA TERESA
    '32764527',    // OCHOA BOHORQUEZ SANDRA YODANY
    '63347978',    // RAMIREZ TOLOZA CLARA EUGENIA
    '63489430',    // BAUTISTA GONZALEZ LILIA CELINA
    '1098682989',  // BRAVO PACHECO YANETH NATALIA
    '37556707',    // RAMIREZ CARRILLO DEISY
    '1010171514',  // CUBILLOS CABRERA PAUL BRAYAN
    '1023861416',  // ZAMBRANO SABOGAL PEDRO PABLO
    '79844012',    // ROMERO TIJO NESTOR RAUL
    '52190948',    // QUIÑONES DIAZ AIDA YANETH
    '1024539393',  // PITA FUQUENE LEIDY JOHANA
    '1073152711',  // TELLEZ LOZANO SANDRA PATRICIA
    '1023013296',  // CUBIDES MORENO NATHALIA
    '1014220582',  // SERENY JIMENEZ JULIET FERNANDA
    '1015428198',  // CONEO JARABA KARINA KAROLAY
    '1030610691',  // GONZALEZ DELGADO YESICA PAOLA
    '1022340598',  // LONDOÑO MICHAEL
    '1022415485',  // GAONA ARANGO LAURA ROCIO
    '52803785',    // CUELLAR PIZA ERIKA MILENA
    '37547875',    // CABALLERO GRATZ MARYLUZ
    '37752414',    // CASADIEGO CUAO ADELINA ESTER
    '52233576',    // PIZA CAMACHO SANDRA MILENA
    '1019050390',  // GUTIERREZ RODRIGUEZ YINETH ROCIO
    '1073696610',  // JENIFFER BELLO MAYORGA
    '1014305622',  // LAURA GONZÁLEZ
    '3276248'      // GustavoG
));

// ----------------------------------------------------------------------------
// Tipos de registro
// ----------------------------------------------------------------------------
$mTipoRegistro = array(
    'No Conformidad'       => 'Incumplimiento de un requisito Legal o de procedimientos internos que no afecta la prestación del servicio educativo o la oferta de valor con los clientes (Estudiantes y padres de familia)',
    'Servicio no Conforme' => 'Incumplimiento de requisito directamente relacionado con el servicio que se entrega al cliente, es decir, el servicio educativo, y que se presenta durante la prestación de este.',
    'Corrección'           => 'Acción para eliminar de manera inmediata un fallo o desviación detectada.',
    'Acción Preventiva'    => 'Acción desarrollada para evitar que un riesgo o desarrollo de una actividad se materialicé en No conformidad o servicio no conforme.',
    'Acción Correctiva'    => 'Acción para eliminar la causa de una no conformidad y evitar que vuelva a ocurrir',
    'Acción de Mejora'     => 'Ajuste propuesto con el fin de mejorar el desempeño, agilizar la acción o aumentar positivamente la percepción de las partes interesadas.'
);

// ----------------------------------------------------------------------------
// Conexión
// ----------------------------------------------------------------------------
include ("../conexion_servidor.php");


/* ============================================================================
   AJAX - Retornar los datos de una NoConformidad para consulta (HTML)
   ============================================================================ */
if ($_GET['TipoModificar'] == md5('AjaxMostrarNoConformidad' . date('d')) and $_GET['NumeroNC']) {

    $Queri = "SELECT NC.NumeroNC, NC.Tipo, NC.CodGestion, Ges.NomGestion, NC.Proceso, NC.FApertura, NC.DetectadaEn, NC.Descripcion, NC.Correccion,
                    CONCAT(E.Nom,' ',E.Apellido1) AS NomResponsable, NC.Causas,NC.RequiereAC,NumeroNCAccionCorrectiva,
                    NC.Accion, NC.ResultadoRevision, NC.FRevision, NC.MotivoCierre, NC.NuevaAccion, NC.FCierre, NC.RCierre
                FROM calidad.noconformidades NC
                LEFT JOIN recursos.emplea E ON NC.Responsable=E.Nit_CCE
                JOIN calidad.gestiones Ges ON NC.CodGestion=Ges.CodGestion
                WHERE NC.NumeroNC='" . $_GET['NumeroNC'] . "'
                LIMIT 1";
    $Result = $mysqli->query($Queri);

    if ($Row = $Result->fetch_assoc()) { ?>
        <div class="nc-detail">
            <div class="nc-detail-head">
                <div class="nc-detail-head-main">
                    <div class="nc-detail-title-row">
                        <span class="nc-detail-number">NC <?php echo $Row['NumeroNC']; ?></span>
                        <span class="nc-detail-badge"><?php echo $Row['Tipo']; ?></span>
                        <span class="nc-detail-badge nc-detail-status <?php echo ($Row['FCierre'] > 0 ? 'is-closed' : 'is-open'); ?>"><?php echo ($Row['FCierre'] > 0 ? 'Cerrada' : 'Abierta'); ?></span>
                    </div>
                </div>
                <?php if (file_exists("../../Archivos/NoConformidades/" . trim($_GET['NumeroNC']) . ".pdf")) { ?>
                    <a class="nc-detail-pdf" href="../file.php?Tipo=<?php echo md5("NoConformidades" . trim($Row['NumeroNC'])); ?>&mFile=<?php echo trim($Row['NumeroNC']); ?>" target="_blank"><i class="bi bi-file-earmark-pdf"></i> Ver adjunto</a>
                <?php } ?>
            </div>
            
            <div class="nc-detail-meta">
                <div class="nc-detail-field"><span class="nc-detail-label">Responsable de la apertura</span><span class="nc-detail-value"><?php echo $Row['NomResponsable']; ?></span></div>
                <div class="nc-detail-field"><span class="nc-detail-label">Gestión</span><span class="nc-detail-value"><?php echo $Row['NomGestion']; ?></span></div>
                <div class="nc-detail-field"><span class="nc-detail-label">Proceso</span><span class="nc-detail-value"><?php echo $Row['Proceso']; ?></span></div>
                <div class="nc-detail-field"><span class="nc-detail-label">Fecha de apertura</span><span class="nc-detail-value"><?php echo DarFecha($Row['FApertura']); ?></span></div>
                <div class="nc-detail-field"><span class="nc-detail-label">Detectada en</span><span class="nc-detail-value"><?php echo $Row['DetectadaEn']; ?></span></div>
                <div class="nc-detail-field"><span class="nc-detail-label">Fecha acordada para la revisión</span><span class="nc-detail-value"><?php echo DarFecha($Row['FRevision']); ?></span></div>
                <?php if ($Row['Tipo'] == 'Corrección') { ?>
                    <div class="nc-detail-field nc-detail-field--wide"><span class="nc-detail-label">¿Se garantiza que la falla no se presente de nuevo?</span><span class="nc-detail-value"><?php echo ($Row['RequiereAC'] == 1 ? 'Sí' : ($Row['RequiereAC'] == -1 ? 'No' : '—')); ?></span></div>
                    <?php if ($Row['RequiereAC'] == -1) { ?>
                        <div class="nc-detail-field"><span class="nc-detail-label">ID de la acción correctiva vinculada</span><span class="nc-detail-value"><a class="link-modern" style="cursor:pointer" onClick="MostrarDivConsultaNoConformidad(<?php echo $Row['NumeroNCAccionCorrectiva']; ?>);"><?php echo $Row['NumeroNCAccionCorrectiva']; ?></a></span></div>
                    <?php } ?>
                <?php } ?>
            </div>

            <section class="nc-detail-section"><h6 class="nc-detail-section-title">Descripción</h6><div class="nc-detail-desc"><div class="nc-detail-text"><?php echo $Row['Descripcion']; ?></div></div></section>
            <div class="nc-detail-columns">
                <section class="nc-detail-section"><h6 class="nc-detail-section-title">Relación de actividades</h6><div class="nc-detail-text"><?php echo $Row['Correccion']; ?></div></section>
                <section class="nc-detail-section"><h6 class="nc-detail-section-title">Causas o ventajas</h6><div class="nc-detail-text"><?php echo $Row['Causas']; ?></div></section>
                <section class="nc-detail-section"><h6 class="nc-detail-section-title">Objetivo principal del plan de acción</h6><div class="nc-detail-text"><?php echo $Row['Accion']; ?></div></section>
            </div>

            <?php
            $QueriNCS = "SELECT NCS.* FROM calidad.ncseguimiento NCS Where NumeroNC='" . $_GET['NumeroNC'] . "' " . ($PuedeAdministrar ? "" : "AND Borrada=0") . " ORDER BY ConsecutivoS";
            $ResultNCS = $mysqli->query($QueriNCS);
            if ($ResultNCS->num_rows > 0) { ?>
                <section class="nc-detail-section">
                    <h6 class="nc-detail-section-title">Seguimientos</h6>
                    <div class="nc-followups">
                    <?php while ($RowNCS = $ResultNCS->fetch_assoc()) { ?>
                        <article class="nc-followup">
                            <div class="nc-followup-head">
                                <span class="nc-followup-title">Seguimiento <?php echo $RowNCS['ConsecutivoS']; ?></span>
                                <?php if ($RowNCS['Borrada']) { ?><span class="nc-detail-badge nc-detail-badge-danger">Borrada</span><?php } ?>
                            </div>
                            <div class="nc-followup-grid">
                                <div class="nc-detail-field nc-detail-field--full"><span class="nc-detail-label">Acción</span><span class="nc-detail-value"><?php echo $RowNCS['Accion']; ?></span></div>
                                <div class="nc-detail-field nc-detail-field--full"><span class="nc-detail-label">Resultado esperado</span><span class="nc-detail-value"><?php echo $RowNCS['Resultado']; ?></span></div>
                                <div class="nc-detail-field"><span class="nc-detail-label">Fecha apertura</span><span class="nc-detail-value"><?php echo DarFechaHora($RowNCS["FHSeguimiento"], 3); ?></span></div>
                                <div class="nc-detail-field"><span class="nc-detail-label">Fecha seguimiento</span><span class="nc-detail-value"><?php echo DarFecha($RowNCS["Fecha"]); ?></span></div>
                                <div class="nc-detail-field"><span class="nc-detail-label">Realizado por</span><span class="nc-detail-value"><?php echo $RowNCS["RealizadoPor"]; ?></span></div>
                                <div class="nc-detail-field"><span class="nc-detail-label">Alcanzado</span><span class="nc-detail-value"><?php echo ($RowNCS["Alcanzado"] == 1 ? "Sí" : ($RowNCS["Alcanzado"] == -1 ? "No" : "—")); ?></span></div>
                            </div>
                        </article>
                    <?php } ?>
                    </div>
                </section>
            <?php } ?>

            <?php if ($Row['FCierre'] > 0) { ?>
                <section class="nc-detail-section">
                    <h6 class="nc-detail-section-title">Datos del cierre</h6>
                    <div class="nc-detail-meta">
                        <div class="nc-detail-field nc-detail-field--full"><span class="nc-detail-label">Resultados de la revisión de la eficacia</span><span class="nc-detail-value"><?php echo $Row['ResultadoRevision']; ?></span></div>
                        <div class="nc-detail-field nc-detail-field--full"><span class="nc-detail-label">Motivos del cierre</span><span class="nc-detail-value"><?php echo $Row['MotivoCierre']; ?></span></div>
                        <div class="nc-detail-field"><span class="nc-detail-label">Nueva acción</span><span class="nc-detail-value"><?php echo $Row['NuevaAccion']; ?></span></div>
                        <div class="nc-detail-field"><span class="nc-detail-label">Fecha del cierre</span><span class="nc-detail-value"><?php echo DarFecha($Row['FCierre']); ?></span></div>
                        <div class="nc-detail-field"><span class="nc-detail-label">Responsable del cierre</span><span class="nc-detail-value"><?php echo $Row['RCierre']; ?></span></div>
                    </div>
                </section>
            <?php } ?>
        </div>
    <?php } else {
        echo "<b>No existe</b>";
    }

    mysqli_close($mysqli);
    exit;
}


/* ============================================================================
   AJAX - Retornar los datos de una NoConformidad para edición (JSON)
   ============================================================================ */
elseif ($_GET['TipoModificar'] == md5('Ajax1JorA1NoConformidad' . date('d')) and $_GET['NumeroNC']) {

    $Queri = "SELECT NC.*, CONCAT(E.Nom,' ',E.Apellido1) AS NomResponsable, QQueja.IDQueja
                FROM calidad.noconformidades NC
                LEFT JOIN (SELECT NumeroNC, MAX(IDQueja) AS IDQueja FROM calidad.quejas WHERE NumeroNC='" . $_GET['NumeroNC'] . "') AS QQueja ON NC.NumeroNC=QQueja.NumeroNC
                LEFT JOIN recursos.emplea E ON NC.Responsable=E.Nit_CCE
                WHERE NC.NumeroNC='" . $_GET['NumeroNC'] . "'
                LIMIT 1";
    $Result = $mysqli->query($Queri) or die(json_encode(["status" => "error", "message" => mysqli_error($mysqli)]));

    $respuesta = array();
    if ($Row = $Result->fetch_assoc()) {
        $respuesta = array(
            'status'                   => 'OK',
            'CodGestion'               => $Row['CodGestion'],
            'Proceso'                  => $Row['Proceso'],
            'NomResponsable'           => $Row['NomResponsable'],
            'Tipo'                     => $Row['Tipo'],
            'FApertura'                => DarFecha($Row['FApertura']),
            'DetectadaEn'              => $Row['DetectadaEn'],
            'DetectadaEnOtra'          => ($Row['DetectadaEn'] <> 'Quejas' ? $Row['DetectadaEn'] : $Row['IDQueja']),
            'Descripcion'              => $Row['Descripcion'],
            'Correccion'               => $Row['Correccion'],
            'RequiereAC'               => $Row['RequiereAC'],
            'NumeroNCAccionCorrectiva' => $Row['NumeroNCAccionCorrectiva'],
            'Causas'                   => $Row['Causas'],
            'Accion'                   => $Row['Accion'],
            'FRevision'                => DarFecha($Row['FRevision']),
            'YaCargado'                => (file_exists("../../Archivos/NoConformidades/" . trim($_GET['NumeroNC']) . ".pdf") ? 1 : 0)
        );
    } else {
        $respuesta = array('status' => 'Error');
    }

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($respuesta);
    mysqli_close($mysqli);
    exit;
}


/* ============================================================================
   AJAX - GRABAR los datos de una NoConformidad (JSON)
   ============================================================================ */
elseif ($_POST['TipoModificar'] == md5('JorGrabarNoConformidad' . date('d')) and $_POST['TipoGrabar'] == 'GrabarNoConformidad' and $_POST['NumeroNC']) {

    if ($_POST['DetectadaEn'] == 'Otra') {
        $_POST['DetectadaEn'] = addslashes($_POST['DetectadaEnOtra']);
    }

    if ($_POST['NumeroNC'] == 'Nuevo') {
        $EsNueva = 1;
        $QueriGrabar = "INSERT INTO calidad.noconformidades (Responsable, Tipo, CodGestion, Proceso, FApertura,DetectadaEn,Descripcion,
                                    Correccion,RequiereAC,NumeroNCAccionCorrectiva,Causas,Accion,FRevision,IPApertura,FHApertura)
                            VALUES
                        ('" . $_SESSION["Usuario"] . "','" . $_POST['Tipo'] . "','" . $_POST['CodGestion'] . "','" . $_POST['Proceso'] . "','" . DarFechaSQL($_POST['FApertura']) . "','" . addslashes($_POST['DetectadaEn']) . "','" . addslashes($_POST['Descripcion']) .
            "','" . addslashes($_POST['Correccion']) . "','" . $_POST['RequiereAC'] . "','" . $_POST['NumeroNCAccionCorrectiva'] . "','" . addslashes($_POST['Causas']) . "','" . addslashes($_POST['Accion']) . "','" . DarFechaSQL($_POST['FRevision']) . "','" .
            $_SESSION['IPAcceso'] . "',SYSDATE())";
        $ResultGrabar = $mysqli->query($QueriGrabar) or die(json_encode(["status" => "error", "message" => mysqli_error($mysqli)]));
        $_POST['NumeroNC'] = $mysqli->insert_id;
    } else {
        $QueriGrabar = "UPDATE calidad.noconformidades SET CodGestion = '" . $_POST['CodGestion'] . "',
                            Tipo = '" . $_POST['Tipo'] . "',
                            Proceso = '" . $_POST['Proceso'] . "',
                            FApertura = '" . DarFechaSQL($_POST['FApertura']) . "',
                            DetectadaEn = '" . addslashes($_POST['DetectadaEn']) . "',
                            Descripcion = '" . addslashes($_POST['Descripcion']) . "',
                            Correccion = '" . addslashes($_POST['Correccion']) . "',
                            RequiereAC = '" . $_POST['RequiereAC'] . "',
                            NumeroNCAccionCorrectiva = '" . $_POST['NumeroNCAccionCorrectiva'] . "',
                            Causas = '" . addslashes($_POST['Causas']) . "',
                            Accion = '" . addslashes($_POST['Accion']) . "',
                            FRevision = '" . DarFechaSQL($_POST['FRevision']) . "',
                            IPApertura = IF(IPApertura<>'',IPApertura,'" . $_SESSION['IPAcceso'] . "'),
                            FHApertura = IF(FHApertura>0,FHApertura,SYSDATE())
                        WHERE NumeroNC = " . $_POST['NumeroNC'] . " LIMIT 1";
        $ResultGrabar = $mysqli->query($QueriGrabar) or die(json_encode(["status" => "error", "message" => mysqli_error($mysqli)]));
    }

    if ($_FILES['FileNoConformidad']) {
        $upload_folder  = '../../Archivos/NoConformidades';
        $nombre_archivo = $_POST['NumeroNC'] . ".pdf";
        $archivador     = $upload_folder . '/' . $nombre_archivo;
        $tmp_archivo    = $_FILES['FileNoConformidad']['tmp_name'];
        move_uploaded_file($tmp_archivo, $archivador);
    }

    if ($_POST['DetectadaEn'] == 'Quejas' and intval($_POST['DetectadaEnOtra'])) {
        $QueriGrabar = "UPDATE calidad.quejas SET
                                            NumeroNC='" . $_POST['NumeroNC'] . "'
                                            WHERE IDQueja='" . intval($_POST['DetectadaEnOtra']) . "'
                                            LIMIT 1";
        $ResultGrabar = $mysqli->query($QueriGrabar);

        $Queri = "SELECT NC.*, CONCAT(E.Nom,' ',E.Apellido1) AS NomResponsable,
                        QQueja.IDQueja, QQueja.Queja, Pr.Dueproceso
                    FROM calidad.noconformidades NC
                    LEFT JOIN (SELECT Colegio, NumeroNC, MAX(IDQueja) AS IDQueja, Queja
                                FROM calidad.quejas WHERE NumeroNC='" . $_POST['NumeroNC'] . "'
                            ) AS QQueja ON NC.NumeroNC=QQueja.NumeroNC
                    LEFT JOIN calidad.procesos Pr ON NC.Proceso=CONCAT(Pr.Colegio,Pr.CodProceso)
                    LEFT JOIN recursos.emplea E ON NC.Responsable=E.Nit_CCE
                    WHERE NC.NumeroNC='" . $_POST['NumeroNC'] . "'
                    LIMIT 1";
        $Result = $mysqli->query($Queri) or die(json_encode(["status" => "error", "message" => mysqli_error($mysqli)]));
        $Row    = $Result->fetch_assoc();

        if ($Row['Dueproceso']) {
            $QueriMail  = "SELECT GROUP_CONCAT(EmailI SEPARATOR ';') AS Mails
                        FROM recursos.emplea
                        WHERE FIND_IN_SET(Nit_CCE,'" . $Row['Dueproceso'] . "') AND Activo";
            $ResultMail = $mysqli->query($QueriMail) or die(json_encode(["status" => "error", "message" => mysqli_error($mysqli)]));
            $RowMail    = $ResultMail->fetch_assoc();
            $Cuentas    = $RowMail['Mails'];
            $CCo        = "";
            $Servicio   = 'Office';
            $Subject    = "MANEJO NO CONFORMIDADES";

            $Mensaje = "Se ha generado un nuevo registro <b>NO CONFORMIDAD</b> que corresponde a los siguientes datos:<br><br>
                    Responsable Apertura <b>" . $Row['NomResponsable'] . "</b><br>
                    Gestión <b>" . $Row['CodGestion'] . "</b><br>
                    Proceso <b>" . $Row['Proceso'] . "</b><br>
                    Fecha Apertura <b>" . DarFecha($Row['FApertura']) . "</b><br>
                    Detectada En <b>" . $Row['DetectadaEn'] . "</b><br>
                    Descripción <b>" . $Row['Descripcion'] . "</b><br>
                    Relación de actividades <b>" . $Row['Correccion'] . "</b><br>
                    Objetivo principal del plan de Acción <b>" . $Row['Accion'] . "</b><br>
                    Causas o Ventajas <b>" . $Row['Causas'] . "</b><br>
                    Queja Asociada <b>(" . $Row['IDQueja'] . ")" . $Row['Queja'] . "</b><br>
                    Éste correo se emite a manera de información para confirmar la creación del registro...<br><br>
                    Cordialmente<br><br>
                    <b>CEMID</b>";
            $mFile   = "";
            $Respuesta = EnviarCorreo($mysqli, $Cuentas, $Subject, $Mensaje, $Servicio, $CCo, $mFile);
        }
    }

    mysqli_close($mysqli);
    echo json_encode([
        "status" => "success",
        "id"     => $_POST['NumeroNC']
    ]);
    exit;
}


/* ============================================================================
   AJAX - Retornar los Seguimientos de una NoConformidad para Edición (HTML)
   ============================================================================ */
elseif ($_GET['TipoModificar'] == md5('AjaxSeguimientos' . date('d')) and $_GET['NumeroNC']) {

    $QueriNCS = "SELECT NCS.*
            FROM calidad.ncseguimiento NCS
            Where Borrada=0 AND NumeroNC='" . $_GET['NumeroNC'] . "'
            ORDER BY ConsecutivoS";
    $ResultNCS = $mysqli->query($QueriNCS);
    $mRegistro = 0;

    while ($RowNCS = $ResultNCS->fetch_assoc() or $mRegistro == 0) {
        $mRegistro++;
        ?>
        <div class="col-sm-12 panel panel-primary nc-section seguimiento-card" id="DivSeguimiento<?php echo $mRegistro; ?>">
            <input name="Borrada<?php echo $mRegistro; ?>" type="hidden" id="Borrada<?php echo $mRegistro; ?>">
            <div class="seguimiento-header">
                <span class="badge-seguimiento" id="SpanSeguimiento<?php echo $mRegistro; ?>">
                    Seguimiento <?php echo $mRegistro; ?>
                </span>
                <button
                    type="button"
                    class="action-btn action-danger btn-borrar-seguimiento"
                    id="BotBorrarSeguimiento<?php echo $mRegistro; ?>"
                    onClick="BorrarSeguimiento(this);"
                    title="Borrar este seguimiento"
                >
                    <i class="bi bi-trash3"></i>
                </button>
            </div>
            <div class="row">
                <div class="col-sm-6 form-group">
                    <label class="label-xsmall">Objetivo principal del plan de Acción</label>
                    <textarea
                        name="Accion<?php echo $mRegistro; ?>"
                        id="Accion<?php echo $mRegistro; ?>"
                        class="form-control"
                        rows="2"
                    ><?php echo $RowNCS['Accion']; ?></textarea>
                </div>
                <div class="col-sm-6 form-group">
                    <label class="label-xsmall">Resultado Esperado</label>
                    <textarea
                        name="Resultado<?php echo $mRegistro; ?>"
                        id="Resultado<?php echo $mRegistro; ?>"
                        class="form-control"
                        rows="2"
                    ><?php echo $RowNCS['Resultado']; ?></textarea>
                </div>
                <div class="col-sm-3 form-group">
                    <label class="label-xsmall">Fecha Seguimiento</label>
                    <input
                        type="text"
                        name="Fecha<?php echo $mRegistro; ?>"
                        id="Fecha<?php echo $mRegistro; ?>"
                        class="form-control"
                        placeholder="dd/mm/aaaa"
                        value="<?php echo DarFecha($RowNCS["Fecha"]); ?>"
                    >
                </div>
                <div class="col-sm-6 form-group">
                    <label class="label-xsmall">Realizado Por</label>
                    <input
                        type="text"
                        name="RealizadoPor<?php echo $mRegistro; ?>"
                        id="RealizadoPor<?php echo $mRegistro; ?>"
                        class="form-control"
                        value="<?php echo $RowNCS["RealizadoPor"]; ?>"
                    >
                </div>
                <div class="col-sm-3 form-group">
                    <label class="label-xsmall">Alcanzado</label>
                    <select
                        name="Alcanzado<?php echo $mRegistro; ?>"
                        class="form-control"
                        id="Alcanzado<?php echo $mRegistro; ?>"
                    >
                        <option value="0" <?php if ($RowNCS["Alcanzado"] == 0) echo "selected"; ?>></option>
                        <option value="1" <?php if ($RowNCS["Alcanzado"] == 1) echo "selected"; ?>>Sí</option>
                        <option value="-1" <?php if ($RowNCS["Alcanzado"] == -1) echo "selected"; ?>>No</option>
                    </select>
                </div>
            </div>
        </div>
    <?php
    } ?>
    <input name="CantSeguimientos" type="hidden" id="CantSeguimientos" value="<?php echo $mRegistro; ?>">
<?php
    mysqli_close($mysqli);
    exit;
}


/* ============================================================================
   AJAX - GRABAR los Seguimientos de una NoConformidad (JSON)
   ============================================================================ */
elseif ($_POST['TipoModificar'] == md5('JorSeguimientos' . date('d')) and $_POST['TipoGrabar'] == 'Seguimientos' and $_POST['NumeroNCSeguimientos']) {

    $QueriGrabar = "UPDATE calidad.ncseguimiento
                    SET Borrada = 1
                    WHERE NumeroNC = " . $_POST['NumeroNCSeguimientos'];
    $ResultGrabar = $mysqli->query($QueriGrabar) or die(json_encode(["status" => "error", "message" => mysqli_error($mysqli)]));

    $mFila = 0;
    for ($i = 1; $i <= $_POST['CantSeguimientos']; $i++) {
        if ($_POST['Borrada' . $i] <> 1) {
            $mFila++;
            $Queri = "INSERT INTO calidad.ncseguimiento (NumeroNC, ConsecutivoS, Fecha, Accion,
                            Resultado, RealizadoPor, Alcanzado, IPSeguimiento, FHSeguimiento)" .
                    "VALUES(" . $_POST['NumeroNCSeguimientos'] . "," . $mFila . ",'" . DarFechaSQL($_POST['Fecha' . $i]) . "','" . addslashes($_POST['Accion' . $i]) . "',
                            '" . addslashes($_POST['Resultado' . $i]) . "','" . addslashes($_POST['RealizadoPor' . $i]) . "','" . $_POST['Alcanzado' . $i] . "',
                            '" . $_SESSION['IPAcceso'] . "',SYSDATE())
                    ON DUPLICATE KEY UPDATE Fecha='" . DarFechaSQL($_POST['Fecha' . $i]) . "',
                                        Accion='" . addslashes($_POST['Accion' . $i]) . "',
                                        Resultado='" . addslashes($_POST['Resultado' . $i]) . "',
                                        RealizadoPor='" . addslashes($_POST['RealizadoPor' . $i]) . "',
                                        Alcanzado='" . $_POST['Alcanzado' . $i] . "',
                                        Borrada=0";
            $Result = $mysqli->query($Queri) or die(json_encode(["status" => "error", "message" => mysqli_error($mysqli)]));
        }
    }
    mysqli_close($mysqli);

    echo json_encode([
        "status" => "success",
        "id"     => $_POST['NumeroNCSeguimientos']
    ]);
    exit;
}


/* ============================================================================
   AJAX - Retornar los datos de una NoConformidad para Cierre (JS)
   ============================================================================ */
elseif ($_GET['TipoModificar'] == md5('AjaxCerrarNoConformidad' . date('d')) and $_GET['NumeroNC']) {

    $Queri = "SELECT NumeroNC, ResultadoRevision, MotivoCierre, NuevaAccion, FCierre, RCierre
                FROM calidad.noconformidades
                WHERE NumeroNC='" . $_GET['NumeroNC'] . "'
                LIMIT 1";
    $Result = $mysqli->query($Queri) or die(mysqli_error($mysqli));

    if ($Row = $Result->fetch_assoc()) { ?>
        document.getElementById('ResultadoRevision').value = '<?php echo $Row['ResultadoRevision']; ?>';
        document.getElementById('MotivoCierre').value = '<?php echo $Row['MotivoCierre']; ?>';
        document.getElementById('NuevaAccion').value = '<?php echo $Row['NuevaAccion']; ?>';
        document.getElementById('FCierre').value = '<?php echo DarFecha($Row['FCierre']); ?>';
        document.getElementById('RCierre').value = '<?php echo ($Row['RCierre'] ? $Row['RCierre'] : $_SESSION['NomUsuario']); ?>';
    <?php
    }
    mysqli_close($mysqli);
    exit;
}


/* ============================================================================
   AJAX - GRABAR los datos de Cierre de una NoConformidad (JSON)
   ============================================================================ */
elseif ($_POST['TipoModificar'] == md5('JorCerrarNoConformidad' . date('d')) and $_POST['TipoGrabar'] == 'CerrarNoConformidad' and $_POST['NumeroNCCerrar']) {

    $QueriGrabar = "UPDATE calidad.noconformidades SET ResultadoRevision = '" . addslashes($_POST['ResultadoRevision']) . "',
                                                MotivoCierre = '" . addslashes($_POST['MotivoCierre']) . "',
                                                NuevaAccion = '" . addslashes($_POST['NuevaAccion']) . "',
                                                FCierre = '" . DarFechaSQL($_POST['FCierre']) . "',
                                                RCierre = '" . $_SESSION['Usuario'] . "',
                                                IPCierre = '" . $_SESSION['IPAcceso'] . "',
                                                FHCierre = SYSDATE()
                                        WHERE NumeroNC = " . $_POST['NumeroNCCerrar'] . "
                                        LIMIT 1";
    $ResultGrabar = $mysqli->query($QueriGrabar) or die(json_encode(["status" => "error", "message" => mysqli_error($mysqli)]));
    mysqli_close($mysqli);
    echo json_encode(["status" => "success"]);
    exit;
}


/* ============================================================================
   AJAX - Cambio Gestión → Procesos (JSON)
   ============================================================================ */
elseif ($_GET['TipoModificar'] == md5('JorCambioGestionProceso' . date('d')) and $_GET['CodGestion']) {

    $CodGestion = $mysqli->real_escape_string($_GET['CodGestion']);
    $Usuario    = $mysqli->real_escape_string($_SESSION["Usuario"]);

    if ($PuedeAdministrar or $PuedeGeneral) {
        $QueriProc = "SELECT Colegio, CodProceso, NomProceso
                        FROM calidad.procesos
                        WHERE CodGestion='" . $CodGestion . "'
                        ORDER BY NomProceso";
    } else {
        $QueriProc = "SELECT Colegio, CodProceso, NomProceso
                        FROM calidad.procesos
                        WHERE CodGestion='" . $CodGestion . "' AND FIND_IN_SET('" . $Usuario . "',DueProceso)
                        ORDER BY NomProceso";
    }

    $ResultProc = $mysqli->query($QueriProc)
        or die(json_encode(["status" => "error", "message" => mysqli_error($mysqli)]));

    $procesos = [];
    while ($RowProc = $ResultProc->fetch_assoc()) {
        $procesos[] = [
            "codigo" => $RowProc['Colegio'] . $RowProc['CodProceso'],
            "nombre" => $RowProc['NomProceso']
        ];
    }

    echo json_encode(["status" => "success", "procesos" => $procesos], JSON_UNESCAPED_UNICODE);
    mysqli_close($mysqli);
    exit;
}


/* ============================================================================
   AJAX - Reportes (JSON con estructura para Excel)
   ============================================================================ */
elseif ($_GET['TipoModificar'] == 'EnviarReportes' and $_GET['TipoGrabar'] == md5('JorEnviarReportes' . date('d'))) {

    set_time_limit(0);
    ini_set('memory_limit', '-1');

    if ($_GET['TipoReporte'] == 'ReporteNoConformidades') {

        if ($PuedeAdministrar) {
            $Filtrico = "WHERE NC.NumeroNC";
        } elseif ($PuedeGeneral) {
            $Filtrico = "WHERE NC.Borrada=0";
        } else {
            $Filtrico = "WHERE NC.Borrada=0 and (Pr.DueProceso LIKE '%" . $_SESSION["Usuario"] . "%' or NC.Responsable='" . $_SESSION['Usuario'] . "' )";
        }
        if (!empty($_GET['ReportProceso'])) {
            $Filtrico .= " AND NC.Proceso = '" . $_GET['ReportProceso'] . "'";
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
                    LEFT JOIN recursos.emplea E ON NC.Responsable=E.Nit_CCE " . $Filtrico;

        $Result = $mysqli->query($Queri) or die(json_encode([["Codigo" => "Error", "Mensaje" => mysqli_error($mysqli)]]));

        $Campos = "rows = prez.map(row => ({NumeroNC:row.NumeroNC," .
                "CodGestion:row.CodGestion," .
                "NomProceso:row.NomProceso," .
                "Tipo:row.Tipo," .
                "NomResponsable:row.NomResponsable," .
                "FApertura:(isNaN(parseInt(row.FApertura,10)) ? '' :parseInt(row.FApertura,10))," .
                "DetectadaEn:row.DetectadaEn," .
                "Descripcion:row.Descripcion," .
                "'Actividades (Correccion)':row.Correccion," .
                "'Acción Garantiza No Reinsidencia':row.GarantiaNoReinsidencia," .
                "'ID Acción Correctiva':row.NumeroNCAccionCorrectiva," .
                "'Causas o Ventajas':row.Causas," .
                "'Objetivo plan Acción':row.Accion," .
                "ResultadoRevision:row.ResultadoRevision," .
                "FRevision:(isNaN(parseInt(row.FRevision,10)) ? '' :parseInt(row.FRevision,10))," .
                "MotivoCierre:row.MotivoCierre," .
                "NuevaAccion:row.NuevaAccion," .
                "'Responsable Cierre':row.RCierre," .
                "FCierre:(isNaN(parseInt(row.FCierre,10)) ? '' :parseInt(row.FCierre,10))," .
                "Borrada:row.Borrada" .
                "}));";

        $Formato  = "formatColumn(worksheet, 5, 'dd/mm/yyyy');";
        $Formato .= "formatColumn(worksheet, 14, 'dd/mm/yyyy');";
        $Formato .= "formatColumn(worksheet, 18, 'dd/mm/yyyy');";

        $mArrayCampos = array(
            'Codigo'  => 'Campos',
            'Campos'  => $Campos,
            'Formato' => $Formato
        );

        $respuestaFinal   = array();
        $respuestaFinal[] = $mArrayCampos;

        while ($Row = $Result->fetch_assoc()) {
            $Array = array();
            foreach ($Row as $Clave => $Valor) {
                if ($Valor !== null && strlen((string)$Valor) == 10 && strpos($Valor, '-') !== false && DarFecha($Valor) > '0-0-0') {
                    $Array[$Clave] = str_replace('/', '-', DarFecha($Valor));
                } else {
                    $Array[$Clave] = trim((string)$Valor);
                }
            }
            $respuestaFinal[] = $Array;
        }

        echo json_encode($respuestaFinal, JSON_UNESCAPED_UNICODE);
        mysqli_close($mysqli);
        exit;
    }
    mysqli_close($mysqli);
    exit;
}


/* ============================================================================
   AJAX - Reversar una NoConformidad cerrada (JSON)
   ============================================================================ */
elseif ($_POST['TipoModificar'] == md5('JorReversarNoConformidad' . date('d')) and $_POST['TipoGrabar'] == 'ReversarNoConformidad' and $_POST['NumeroNC']) {

    if (!$PuedeReversar) {
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
                        WHERE NumeroNC = " . $_POST['NumeroNC'] . "
                        AND FCierre IS NOT NULL
                        LIMIT 1";

        $ResultReversar = $mysqli->query($QueriReversar) or die(json_encode(["status" => "error", "message" => mysqli_error($mysqli)]));

        if ($mysqli->affected_rows == 0) {
            echo json_encode(["status" => "error", "message" => "No se pudo reversar la no conformidad. Verifique que esté cerrada."]);
        } else {
            echo json_encode(["status" => "success"]);
        }
    }

    mysqli_close($mysqli);
    exit;
}


/* ============================================================================
   AJAX - ELIMINAR ARCHIVO ADJUNTO DE UNA NO CONFORMIDAD (JSON)
   ============================================================================ */
elseif ($_POST['TipoModificar'] == md5('JorEliminarArchivo' . date('d')) and $_POST['NumeroNC']) {

    try {
        $numNC = intval($_POST['NumeroNC']);
        if ($numNC <= 0) {
            echo json_encode(['status' => 'error', 'message' => 'Registro inválido.']);
            mysqli_close($mysqli);
            exit;
        }

        $pdfPath = "../../Archivos/NoConformidades/" . $numNC . ".pdf";
        if (!is_file($pdfPath)) {
            echo json_encode(['status' => 'error', 'message' => 'El archivo adjunto no existe.']);
            mysqli_close($mysqli);
            exit;
        }

        if (!unlink($pdfPath)) {
            echo json_encode(['status' => 'error', 'message' => 'No se pudo eliminar el archivo adjunto.']);
            mysqli_close($mysqli);
            exit;
        }

        echo json_encode(['status' => 'success']);
        mysqli_close($mysqli);
        exit;
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        mysqli_close($mysqli);
        exit;
    }
}


/* ============================================================================
   DATOS PARA LA VISTA PRINCIPAL
   ============================================================================ */

// Listado de gestiones (para combos del filtro y del modal de creación)
$QueriGes  = "SELECT * FROM calidad.gestiones ORDER BY NomGestion";
$ResultGes = $mysqli->query($QueriGes);

// Listado de No Conformidades (consumido por la vista a través de $Result)
// Solo se consulta cuando el usuario envía una búsqueda (Filtrar=1).
$Buscar = (isset($_GET['Filtrar']) && (int)$_GET['Filtrar'] === 1);

if ($Buscar) {

    if ($PuedeAdministrar) {
        $Filtrico = "WHERE NC.NumeroNC";      // Todas, hasta las Borradas
    } elseif ($PuedeGeneral) {
        $Filtrico = "WHERE NC.Borrada=0";     // Todas menos las borradas
    } else {
        $Filtrico = "WHERE NC.Borrada=0 AND P.DueProceso LIKE '%" . $_SESSION["Usuario"] . "%'";
    }

    // Filtro por número específico
    if (!empty($_GET['FiltroNumeroNC'])) {
        $Filtrico .= " AND FIND_IN_SET(NC.NumeroNC, '" . $mysqli->real_escape_string($_GET['FiltroNumeroNC']) . "')";
    } else {
        // Filtro por Gestión
        if (!empty($_GET['FiltroCodGestion'])) {
            $Filtrico .= " AND NC.CodGestion = '" . $mysqli->real_escape_string($_GET['FiltroCodGestion']) . "'";
        }
        if (!empty($_GET['FiltroTipo'])) {
            $Filtrico .= " AND NC.Tipo = '" . $mysqli->real_escape_string($_GET['FiltroTipo']) . "'";
        }
        if ($_GET['FiltroTipoAcciones'] == 1) {
            $Filtrico .= " AND (NC.Accion = '' OR NC.Accion IS NULL)";
        } elseif ($_GET['FiltroTipoAcciones'] == 2) {
            $Filtrico .= " AND (NC.Accion <> '' AND NC.Accion IS NOT NULL)";
        } elseif ($_GET['FiltroTipoAcciones'] == 3) {
            $Filtrico .= " AND (NC.FCierre = '' OR NC.FCierre IS NULL)";
        } elseif ($_GET['FiltroTipoAcciones'] == 4) {
            $Filtrico .= " AND (NC.FCierre <> '' AND NC.FCierre IS NOT NULL)";
        }
        if (!empty($_GET['FiltroProceso'])) {
            $Filtrico .= " AND NC.Proceso = '" . $mysqli->real_escape_string($_GET['FiltroProceso']) . "'";
        }
    }

    // Ordenación
    $Ordenacioncita = ($_GET['Ordencito'] == 1) ? " ORDER BY NC.NumeroNC DESC" : " ORDER BY NC.FApertura DESC";

    // Paginación
    $UrlPagina = function ($n) {
        $p = $_GET;
        $p['Filtrar'] = 1;
        $p['pagina']  = $n;
        return basename(__FILE__) . '?' . http_build_query($p);
    };

    $QueriBase = " FROM calidad.procesos P
                  JOIN calidad.noconformidades NC ON P.CodGestion = NC.CodGestion AND CONCAT(P.Colegio, P.CodProceso) = NC.Proceso
                  LEFT JOIN recursos.emplea E ON NC.Responsable = E.Nit_CCE " . $Filtrico;
    $regPorPagina  = 20;
    $paginaActual  = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
    if ($paginaActual < 1) {
        $paginaActual = 1;
    }
    $CountRes        = $mysqli->query("SELECT COUNT(*) AS total" . $QueriBase);
    $CountRow        = $CountRes ? $CountRes->fetch_assoc() : array('total' => 0);
    $totalRegistros  = (int)$CountRow['total'];
    $totalPaginas    = max(1, (int)ceil($totalRegistros / $regPorPagina));
    if ($paginaActual > $totalPaginas) {
        $paginaActual = $totalPaginas;
    }
    $offset = ($paginaActual - 1) * $regPorPagina;

    $Queri = "SELECT NC.*, CONCAT(E.Nom, ' ', E.Apellido1) AS NomResponsable" . $QueriBase . $Ordenacioncita . " LIMIT " . (int)$offset . ", " . (int)$regPorPagina;
    $Result = $mysqli->query($Queri) or die(mysqli_error($mysqli));
    $mContadorFilas = 0;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, viewport-fit=cover">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="theme-color" content="#eb2525">
    <title>MANEJO DE NO CONFORMIDADES Y MEJORAS</title>

    <link rel="stylesheet" href="../librerias/jquery/jquery-ui.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <script src="../librerias/jquery/jquery-1.10.2.js"></script>
    <script src="../librerias/jquery/jquery-ui.js"></script>

    <link rel="stylesheet" href="../librerias/bootstrap-3.3.7-dist/css/bootstrap.min.css">
    <script src="../librerias/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.sheetjs.com/xlsx-latest/package/dist/xlsx.full.min.js"></script>
    <script src="../funciones.js"></script>

    <!-- Select2 (Autocompletado predictivo al escribir) -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/i18n/es.js"></script>

    <style>
        /* ===== Variables y base ===== */
        :root {
            --bg-body: #e9eef4;
            --bg-card: #ffffff;
            --border: #b7c3d2;
            --border-soft: #cbd5e1;
            --border-strong: #94a3b8;
            --text: #0b1322;
            --text-muted: #334155;
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --primary-soft: #eff6ff;
            --success: #16a34a;
            --success-hover: #15803d;
            --success-bg: #dcfce7;
            --success-border: #86efac;
            --danger: #dc2626;
            --danger-hover: #b91c1c;
            --danger-bg: #fee2e2;
            --danger-border: #fca5a5;
            --info: #0284c7;
            --info-bg: #e0f2fe;
            --info-border: #7dd3fc;
            --purple: #7c3aed;
            --purple-bg: #f5f3ff;
            --shadow-sm: 0 1px 3px rgba(11, 19, 34, .08);
            --shadow: 0 4px 14px rgba(11, 19, 34, .10);
            --shadow-lg: 0 20px 60px rgba(11, 19, 34, .25);
            --navbar-height: 112px;
        }

        html { background: var(--bg-body); overflow-y: scroll !important; scrollbar-gutter: stable; }
        body {
            padding-right: 0 !important;
            background: var(--bg-body);
            color: var(--text);
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Inter', Roboto, sans-serif;
            font-size: 14px;
            line-height: 1.5;
        }
        body.modal-open { padding-right: 0 !important; }
        .container { width: 96%; max-width: 1600px; margin: 0 auto; background: transparent; padding: 0 16px; }

        /* ===== Navbar ===== */
        .navbar-default {
            background: #fff !important; border: none !important;
            box-shadow: var(--shadow-sm); padding: 8px 16px; min-height: 64px;
        }
        .navbar-brand {
            color: var(--text) !important; font-weight: 700; font-size: 15px;
            display: flex; align-items: center; gap: 10px;
            height: auto; padding: 10px 12px; line-height: 1.2; float: left;
        }
        .navbar-brand .brand-icon {
            color: var(--primary); font-size: 25px;
            display: inline-flex; align-items: center; justify-content: center;
            width: 28px; height: 28px; flex: 0 0 28px;
        }
        .navbar-brand .label-xsmall {
            display: block; font-size: 10px; color: var(--text-muted);
            font-weight: 600; margin-top: 3px; letter-spacing: .5px;
            text-transform: uppercase; vertical-align: baseline;
        }
        .navbar .form-control {
            border: 1.5px solid var(--border) !important; border-radius: 8px !important;
            height: 38px !important; font-size: 13px; background: #f7f9fc;
            color: var(--text); padding: 6px 10px;
        }
        .navbar .form-control:focus {
            background: #fff; border-color: var(--primary) !important;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, .18) !important;
        }

        .actions-box {
            display: flex; align-items: center; gap: 3px; padding: 3px;
            background: #eef2f7; border: 1.5px solid var(--border); border-radius: 9px;
            box-shadow: inset 0 1px 2px rgba(11, 19, 34, .04);
            height: 40px; width: fit-content;
        }
        /* Centra la botonera de acciones dentro de su columna del navbar */
        .navbar form > .col-sm-2 { display: flex; align-items: center; justify-content: center; }
        .btn-nav {
            width: 32px; height: 32px; padding: 0 !important;
            border-radius: 6px !important; display: inline-flex !important;
            align-items: center; justify-content: center;
            background: #fff !important; border: 1px solid var(--border) !important;
            color: var(--text-muted) !important; font-size: 14px;
            transition: all .15s; margin: 0; line-height: 1; touch-action: manipulation;
        }
        .btn-nav:hover {
            background: var(--primary-soft) !important; border-color: var(--primary) !important;
            color: var(--primary) !important; transform: translateY(-1px);
        }
        .btn-nav-primary { background: var(--primary) !important; border-color: var(--primary) !important; color: #fff !important; }
        .btn-nav-primary:hover { background: var(--primary-hover) !important; border-color: var(--primary-hover) !important; color: #fff !important; }

        /* ===== Select2 Personalizado ===== */
        .select2-container .select2-selection--single {
            height: 38px !important;
            border: 1.5px solid var(--border) !important;
            border-radius: 8px !important;
            background-color: #f7f9fc !important;
            display: flex !important;
            align-items: center;
        }
        .select2-container .select2-selection--single .select2-selection__rendered {
            color: var(--text) !important;
            font-size: 13px !important;
            padding-left: 10px !important;
            line-height: 36px !important;
        }
        .select2-container .select2-selection--single .select2-selection__arrow {
            height: 36px !important;
            right: 8px !important;
        }
        .select2-container--open .select2-selection--single {
            border-color: var(--primary) !important;
            background-color: #fff !important;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, .18) !important;
        }
        .select2-dropdown {
            border: 1.5px solid var(--border) !important;
            border-radius: 8px !important;
            box-shadow: var(--shadow) !important;
            font-size: 13px !important;
            z-index: 9999999 !important;
        }
        .select2-results__option--highlighted[aria-selected] {
            background-color: var(--primary) !important;
            color: #fff !important;
        }
        .select2-search--dropdown .select2-search__field {
            border: 1.5px solid var(--border) !important;
            border-radius: 6px !important;
            padding: 6px 10px !important;
            font-size: 13px !important;
        }

        /* ===== Formularios ===== */
        .form-control {
            border: 1.5px solid var(--border) !important; border-radius: 8px !important;
            box-shadow: none !important; height: 40px; padding: 8px 12px;
            font-size: 13px; background: #fff; color: var(--text); transition: all .15s;
        }
        .form-control:focus {
            border-color: var(--primary) !important;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, .18) !important; outline: none;
        }
        input.form-control[readonly] { background: #f1f5f9 !important; cursor: pointer; }
        textarea.form-control { height: auto; min-height: 80px; resize: vertical; }
        select.form-control { height: 40px; padding: 8px 12px; }

        label, .label-xsmall {
            font-weight: 700; font-size: 11.5px; color: var(--text-muted);
            text-transform: uppercase; letter-spacing: .4px; margin-bottom: 6px !important;
            display: inline-flex; align-items: center; gap: 5px; vertical-align: baseline;
        }
        label i, .label-xsmall i { color: var(--primary); font-size: 12px; }

        .form-control.alert-danger, select.form-control.alert-danger,
        textarea.form-control.alert-danger, input.form-control.alert-danger {
            border-color: var(--danger) !important; background-color: #fef2f2 !important;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, .18) !important;
            animation: shakeError .35s ease;
        }
        @keyframes shakeError {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-4px); }
            50% { transform: translateX(4px); }
            75% { transform: translateX(-3px); }
        }

        /* ===== Botones ===== */
        .btn {
            border-radius: 8px; font-weight: 600; font-size: 13px; padding: 8px 16px;
            border: 1px solid transparent; transition: all .15s;
            display: inline-flex; align-items: center; gap: 6px; touch-action: manipulation;
        }
        .btn:focus, .btn:active { outline: none !important; box-shadow: 0 0 0 3px rgba(37, 99, 235, .2) !important; }
        .btn-primary { background: var(--primary); border-color: var(--primary); color: #fff; }
        .btn-primary:hover { background: var(--primary-hover); border-color: var(--primary-hover); color: #fff; }
        .btn-success { background: var(--success); border-color: var(--success); color: #fff; }
        .btn-success:hover { background: var(--success-hover); border-color: var(--success-hover); color: #fff; }
        .btn-danger { background: var(--danger); border-color: var(--danger); color: #fff; }
        .btn-danger:hover { background: var(--danger-hover); border-color: var(--danger-hover); color: #fff; }
        .btn-secondary { background: #f1f5f9; border-color: var(--border); color: var(--text); }
        .btn-secondary:hover { background: #e2e8f0; border-color: var(--border-strong); color: var(--text); }
        .btn-default { background: #fff; border-color: var(--border); color: var(--text); }
        .btn-default:hover { background: #f8fafc; border-color: var(--primary); color: var(--primary); }

        /* ===== Paneles ===== */
        .panel {
            border: 1.5px solid var(--border) !important; border-radius: 12px !important;
            box-shadow: var(--shadow-sm) !important; margin-bottom: 14px !important; background: #fff !important;
        }
        .panel-primary { border-color: #93c5fd !important; background: #f5f9ff !important; }
        .panel-primary h5 {
            margin: 4px 0 12px; color: var(--primary); font-weight: 700;
            font-size: 13px; text-transform: uppercase; letter-spacing: .5px;
            display: flex; align-items: center; gap: 8px;
        }
        .nc-field { padding: 12px 16px !important; }
        .nc-field .label-xsmall { margin-bottom: 4px !important; }
        .nc-value { color: var(--text); font-size: 13.5px; line-height: 1.5; word-break: break-word; }
        .nc-section { padding: 16px 20px !important; }

        /* ===== Detalle de consulta ===== */
        .nc-detail {
            display: flex; flex-direction: column; gap: 18px;
            width: 100%; max-width: 1200px; margin: 0 auto;
        }
        .nc-detail-head {
            display: flex; align-items: flex-start; justify-content: space-between;
            gap: 12px; flex-wrap: wrap;
        }
        .nc-detail-head-main { min-width: 0; }
        .nc-detail-title-row { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
        .nc-detail-number { color: var(--text); font-size: 20px; font-weight: 800; line-height: 1.1; }
        .nc-detail-badge {
            display: inline-flex; align-items: center; max-width: 100%;
            padding: 4px 9px; border-radius: 999px; background: #eef2f7; color: #334155;
            font-size: 10.5px; font-weight: 700; line-height: 1.35; overflow-wrap: anywhere;
        }
        .nc-detail-status.is-open { background: var(--primary-soft); color: var(--primary); }
        .nc-detail-status.is-closed { background: #f1f5f9; color: #475569; }
        .nc-detail-badge-danger { background: var(--danger-bg); color: var(--danger); }
        .nc-detail-pdf {
            display: inline-flex; align-items: center; gap: 6px; padding: 6px 10px;
            border: 1px solid var(--danger-border); border-radius: 8px;
            background: var(--danger-bg); color: var(--danger); font-size: 12px;
            font-weight: 600; text-decoration: none !important; white-space: nowrap;
        }
        .nc-detail-pdf:hover { background: #fecaca; color: var(--danger); }
        .nc-detail-meta {
            display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 14px 20px;
        }
        .nc-detail-field { display: flex; flex-direction: column; gap: 2px; min-width: 0; }
        .nc-detail-field--wide { grid-column: span 2; }
        .nc-detail-field--full { grid-column: 1 / -1; }
        .nc-detail-label {
            color: #64748b; font-size: 10.5px; font-weight: 700;
            letter-spacing: .2px; text-transform: none;
        }
        .nc-detail-value { color: var(--text); font-size: 13px; line-height: 1.45; overflow-wrap: anywhere; }
        .nc-detail-empty { color: #94a3b8; }
        .nc-detail-section { display: flex; flex-direction: column; gap: 8px; min-width: 0; }
        .nc-detail-section-title {
            margin: 0; padding-bottom: 6px; border-bottom: 1px solid #e2e8f0;
            color: var(--text); font-size: 12.5px; font-weight: 700;
        }
        .nc-detail-text {
            color: var(--text); font-size: 13px; line-height: 1.6;
            white-space: pre-line; overflow-wrap: anywhere;
        }
        .nc-detail-desc {
            padding: 12px 14px; border: 1px solid var(--border-soft);
            border-radius: 10px; background: #fff;
        }
        .nc-detail-columns { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 24px; }
        .nc-detail-columns > .nc-detail-section:only-child { grid-column: 1 / -1; }
        .nc-followups { display: flex; flex-direction: column; gap: 10px; }
        .nc-followup { padding: 12px 14px; border: 1px solid #e2e8f0; border-radius: 8px; background: #fff; }
        .nc-followup-head { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-bottom: 10px; }
        .nc-followup-title { color: var(--text); font-size: 12px; font-weight: 700; }
        .nc-followup-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 10px 18px; }

        /* ===== Seguimiento header ===== */
        .seguimiento-header {
            display: flex; align-items: center; justify-content: flex-start;
            gap: 8px; margin: -4px 0 12px 0; padding: 0 4px 8px 4px;
            border-bottom: 1px solid rgba(37, 99, 235, .15);
        }
        .badge-seguimiento {
            display: inline-flex; align-items: center; gap: 6px;
            background: var(--primary); color: #fff; border-radius: 999px;
            padding: 3px 14px; font-size: 11px; font-weight: 700; line-height: 1.5;
            letter-spacing: .3px; text-transform: uppercase;
            box-shadow: 0 1px 4px rgba(37, 99, 235, .25);
        }
        .seguimiento-card {
            transition: border-color .3s ease, box-shadow .3s ease;
            border: 1.5px solid var(--border-soft) !important;
            padding: 24px 28px !important;
            border-radius: 12px;
            background: #fff !important;
        }
        .seguimiento-card:hover {
            border-color: var(--primary) !important;
            box-shadow: 0 4px 12px rgba(37, 99, 235, .15);
        }
        .seguimiento-card.card-eliminar-hover {
            border-color: var(--danger) !important;
            box-shadow: 0 4px 12px rgba(220, 38, 38, .15);
        }
        #DivSeguimientos .form-group { padding-left: 8px !important; padding-right: 8px !important; }
        .btn-borrar-seguimiento {
            display: flex !important; align-items: center; justify-content: center;
            width: 100%; height: 40px; background: transparent; border: none;
            padding: 0; margin: 0; cursor: pointer; text-decoration: none !important;
        }
        .btn-borrar-seguimiento:hover { background: transparent; }
        .link-modern { color: var(--primary); text-decoration: none; font-weight: 600; }
        .link-modern:hover { text-decoration: underline; }

        /* ===== Tabla ===== */
        .row.sidenav { padding: 0px 0; }
        .tableFixHead.table-responsive {
            position: relative; background: #fff; border: 1.5px solid var(--border);
            border-radius: 14px; box-shadow: var(--shadow); padding: 0;
            margin: 0 0 15px 0; display: block;
            max-height: calc(100vh - var(--navbar-height, 112px) - 100px);
            overflow: auto; overscroll-behavior: contain; scrollbar-gutter: stable;
        }
        .tableFixHead table { width: 100%; margin: 0; }
        .tableFixHead thead th {
            position: sticky; top: 0;
            z-index: 5; background: #eef2f7 !important; color: #1e293b !important;
            font-size: 11px; text-transform: uppercase; letter-spacing: .5px;
            font-weight: 700; text-align: center !important; vertical-align: middle !important;
            border-bottom: 1.5px solid var(--border) !important; padding: 14px 16px !important;
        }
        .tableFixHead tbody td { text-align: left; }
        .table { margin-bottom: 0; border-collapse: separate; border-spacing: 0; }
        .table > tbody > tr > td, .table > thead > tr > th {
            border-color: var(--border-soft) !important; vertical-align: middle;
            line-height: 1.2; padding: 12px 16px !important;
        }
        .table-striped > tbody > tr:nth-of-type(odd) { background: #fafbfc; }
        .table tbody tr { transition: background .12s; }
        .table tbody tr:hover { background: #f0f7ff !important; }

        .badge-borrada {
            display: inline-block; background: var(--danger-bg); color: var(--danger);
            border: 1px solid var(--danger-border); padding: 2px 8px; border-radius: 6px;
            font-size: 10.5px; font-weight: 700; letter-spacing: .3px; margin-right: 6px;
        }
        .acciones-cell {
            display: flex; align-items: center; justify-content: center;
            gap: 4px; flex-wrap: nowrap;
        }
        .fecha-cierre { font-weight: 600; font-size: 12px; color: var(--text-muted); white-space: nowrap; }

        .action-btn {
            display: inline-flex; align-items: center; justify-content: center;
            width: 32px; height: 32px; border-radius: 8px; font-size: 15px;
            cursor: pointer; margin: 0 2px; transition: all .15s;
            border: 1px solid transparent; text-decoration: none !important;
            vertical-align: middle; line-height: 1; touch-action: manipulation;
        }
        .action-btn i { display: inline-flex; align-items: center; justify-content: center; line-height: 1; font-size: 15px; }
        .action-view { color: var(--primary); background: var(--primary-soft); }
        .action-view:hover { background: #dbeafe; border-color: #bfdbfe; color: var(--primary-hover); }
        .action-edit { color: var(--primary); background: var(--primary-soft); }
        .action-edit:hover { background: #dbeafe; border-color: #bfdbfe; color: var(--primary-hover); }
        .action-list { color: var(--purple); background: var(--purple-bg); }
        .action-list:hover { background: #ede9fe; border-color: #ddd6fe; color: #6d28d9; }
        .action-success { color: var(--success); background: var(--success-bg); }
        .action-success:hover { background: #bbf7d0; border-color: var(--success-border); color: var(--success-hover); }
        .action-danger { color: var(--danger); background: var(--danger-bg); }
        .action-danger:hover { background: #fecaca; border-color: var(--danger-border); color: var(--danger-hover); }

        /* ===== Alertas ===== */
        .myAlert-top {
            position: fixed; top: 90px; left: 50%; transform: translateX(-50%);
            width: auto; min-width: 340px; max-width: 90%; z-index: 1000000;
            border-radius: 10px; padding: 14px 22px; font-weight: 600;
            box-shadow: var(--shadow-lg); display: none; text-align: center;
            font-size: 13.5px; border-width: 1.5px; border-style: solid;
        }
        #DatoObser.alert-danger { background: var(--danger-bg) !important; border-color: var(--danger-border) !important; color: #111 !important; }
        #DatoObser.alert-success { background: var(--success-bg) !important; border-color: var(--success-border) !important; color: #111 !important; }
        #DatoObser.alert-info { background: var(--info-bg) !important; border-color: var(--info-border) !important; color: #111 !important; }

        /* ===== Modales ===== */
        .modal-content { border-radius: 14px !important; border: none !important; box-shadow: var(--shadow-lg) !important; overflow: hidden; }
        .modal-header {
            display: flex; align-items: center; justify-content: space-between; gap: 12px;
            background: #eef2f7; border-bottom: 1.5px solid var(--border) !important;
            padding: 16px 22px !important;
        }
        .modal-title {
            font-weight: 700; color: var(--text); font-size: 15px;
            display: inline-flex; align-items: center; gap: 9px; flex: 1 1 auto;
            min-width: 0; margin: 0;
        }
        .modal-title i { color: var(--primary); font-size: 18px; }
        .modal-header .close {
            opacity: .55; font-size: 24px; font-weight: 300; color: var(--text-muted);
            float: none; flex: 0 0 auto; margin: 0; padding: 0 4px;
            transition: all .15s; text-shadow: none !important;
        }
        .modal-header .close:hover { opacity: 1; color: var(--danger); transform: rotate(90deg); }
        .modal-body { padding: 22px !important; background: #f8fafc; }
        .modal-footer {
            padding: 14px 22px !important; background: #eef2f7;
            border-top: 1.5px solid var(--border) !important;
            display: flex; justify-content: flex-end; gap: 10px;
        }
        .modal-footer .btn { min-width: 110px; justify-content: center; }
        .modal-dialog-scrollable .modal-body { max-height: calc(100vh - 170px); overflow-y: auto; }

        /* ===== Adjuntar archivo ===== */
        .btn-upload-file {
            display: flex !important; align-items: center; justify-content: center;
            gap: 10px; padding: 12px 16px !important;
            border: 1.5px solid var(--danger) !important; background: var(--danger-bg) !important;
            color: var(--danger) !important; border-radius: 8px !important;
            width: 100%; font-weight: 600; font-size: 13px; transition: all .2s ease;
            text-decoration: none !important; text-align: center !important; cursor: pointer;
        }
        .btn-upload-file i { font-size: 18px; }
        .btn-upload-file:hover { background: var(--primary-soft) !important; border-color: var(--primary-hover) !important; color: var(--primary-hover) !important; }
        .btn-upload-file.archivo-cargado { background: var(--success) !important; border-color: var(--success) !important; color: #fff !important; }
        .btn-upload-file.archivo-cargado:hover { background: var(--success-hover) !important; border-color: var(--success-hover) !important; color: #fff !important; }
        .nc-file-row { display: flex; align-items: center; gap: 8px; }
        .nc-file-row .btn-upload-file { flex: 1 1 auto; margin-bottom: 0 !important; }
        .nc-file-row .action-btn { flex: 0 0 32px; }
        .d-none { display: none !important; }

        /* ===== Formulario de NC ===== */
        #FrmNoConformidad {
            display: flex; flex-wrap: wrap; gap: 14px; width: 100%; margin: 0; align-items: stretch;
        }
        #FrmNoConformidad > .col-sm-4 {
            flex: 1 1 0; float: none; width: auto; max-width: none; min-width: 260px;
            padding: 0; margin: 0; display: flex;
        }
        #FrmNoConformidad > .col-sm-4 > .nc-box { height: 100%; }
        .nc-box {
            background: #fff; border: 1.5px solid #b7c3d2; border-radius: 12px;
            padding: 18px 16px; box-shadow: 0 1px 3px rgba(11, 19, 34, .06);
            display: flex; flex-direction: column; gap: 12px; flex: 1; width: 100%;
        }
        .nc-box .form-group { padding: 0 !important; margin: 0 !important; width: 100%; }
        .nc-box label { display: block; margin-bottom: 4px; }
        .nc-box .nc-row { display: flex; gap: 10px; width: 100%; }
        .nc-box .nc-cell { min-width: 0; }
        .nc-box .nc-cell-4 { flex: 0 0 32%; }
        .nc-box .nc-cell-6 { flex: 0 0 48%; }
        .nc-box .nc-cell-8 { flex: 1; }
        .nc-box input.form-control, .nc-box select.form-control, .nc-box textarea.form-control { width: 100%; }
        #FrmNoConformidad textarea.form-control { min-height: 88px; }
        #FrmNoConformidad .form-group { margin-bottom: 0 !important; }
        #FrmCerrarNoConformidad .form-group { margin-bottom: 14px; }
        .table td:last-child { white-space: nowrap; }

        /* ===== Modal de Reportes ===== */
        .nc-modal-center { display: flex; justify-content: center; width: 100%; padding: 4px 0; }
        .nc-modal-center .nc-box { max-width: 640px; width: 100%; padding: 22px 20px; gap: 16px; }
        .nc-modal-center .nc-box .form-group { padding: 0 !important; margin: 0 !important; width: 100%; }

        /* ===== Tabla sin resultados ===== */
        .sin-resultados { text-align: center; padding: 44px 20px !important; color: var(--text-muted); font-size: 14px; }
        .sin-resultados i { font-size: 38px; color: var(--border-strong); display: block; margin-bottom: 10px; }
        .sin-resultados b { display: block; color: var(--text); font-size: 15px; margin-bottom: 4px; }

        /* ===== Estado vacío: datos ocultos hasta buscar ===== */
        .nc-empty-state {
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            text-align: center; gap: 8px;
            min-height: calc(100vh - var(--navbar-height, 112px) - 210px);
            padding: 40px 24px; margin: 0 0 15px;
            background: #fff; border: 1.5px solid var(--border); border-radius: 14px;
            box-shadow: var(--shadow);
        }
        .nc-empty-state .nc-empty-icon { font-size: 56px; color: var(--primary); opacity: .85; }
        .nc-empty-state h6 { font-size: 17px; font-weight: 800; margin: 4px 0 0; color: var(--text); }
        .nc-empty-state p { max-width: 520px; margin: 0; color: var(--text-muted); font-size: 13.5px; }
        .nc-empty-state ul { list-style: none; padding: 0; margin: 8px 0 0; display: grid; gap: 6px; text-align: left; }
        .nc-empty-state li { font-size: 12.5px; color: var(--text-muted); }
        .nc-empty-state li i { color: var(--primary); margin-right: 6px; }

        /* ===== Paginación ===== */
        .nc-pagination {
            display: flex; align-items: center; justify-content: space-between;
            gap: 12px; flex-wrap: wrap; padding: 12px 16px; background: #fff;
            border: 1.5px solid var(--border); border-radius: 12px;
            box-shadow: var(--shadow-sm); margin: 0 0 16px 0;
        }
        .nc-pagination-info { font-size: 12.5px; color: var(--text-muted); font-weight: 600; }
        .nc-pagination-controls { display: flex; align-items: center; gap: 4px; flex-wrap: wrap; }
        .page-btn {
            display: inline-flex; align-items: center; justify-content: center;
            min-width: 34px; height: 34px; padding: 0 10px; border-radius: 8px;
            border: 1.5px solid var(--border); background: #fff; color: var(--text);
            font-size: 12.5px; font-weight: 600; text-decoration: none;
            transition: all .15s; line-height: 1;
        }
        .page-btn:hover { border-color: var(--primary); color: var(--primary); background: var(--primary-soft); text-decoration: none; }
        .page-btn.active { background: var(--primary); border-color: var(--primary); color: #fff; }
        .page-btn.disabled { opacity: .45; pointer-events: none; }
        .page-ellipsis { padding: 0 4px; color: var(--text-muted); }

        /* ===== Pista de navegación móvil ===== */
        .desc-hint { display: none; }

        /* ===== RESPONSIVE: tablet (hasta 991 px) ===== */
        @media (max-width: 991px) {
            .container { width: 100%; padding: 0 12px; }
            .btn-nav { width: 34px; height: 34px; }
            .nc-box .nc-row { flex-direction: column; gap: 8px; }
            .nc-box .nc-cell-4, .nc-box .nc-cell-6, .nc-box .nc-cell-8 { flex: 1 1 100%; width: 100%; }
            .tableFixHead tbody tr[data-id] { cursor: pointer; }
            .tableFixHead tbody tr[data-id]:active,
            .tableFixHead tbody tr[data-id].fila-seleccionada {
                border-color: var(--primary) !important;
                box-shadow: 0 0 0 2px rgba(37, 99, 235, .22), var(--shadow-sm) !important;
            }
        }

        /* ===== RESPONSIVE: móvil (hasta 767 px) ===== */
        @media (max-width: 767px) {
            body { font-size: 13px; }
            .container { width: 100%; padding: 0 8px; }
            .navbar-default { padding: 6px 10px; min-height: 52px; }
            .navbar-brand { font-size: 12px; padding: 8px 10px; gap: 6px; }
            .navbar-brand .brand-icon { font-size: 18px; width: 22px; height: 22px; flex: 0 0 22px; }
            .navbar-brand .label-xsmall { font-size: 9px; margin-top: 2px; }
            .navbar-toggle { margin: 6px 0; padding: 7px 9px; }
            .navbar .collapse { border-top: 1px solid var(--border); margin-top: 6px; padding-top: 6px; }
            .navbar .collapse form > .col-sm-10 { width: 100%; float: none; padding: 0 !important; }
            .navbar .collapse form > .col-sm-10::after { content: ""; display: table; clear: both; }
            .navbar .collapse form > .col-sm-10 > .form-group { width: 50%; float: left; padding: 2px 4px !important; }
            .navbar .collapse form > .col-sm-2 { width: 100%; float: none; padding: 4px !important; clear: both; }
            .navbar .form-control { height: 36px !important; font-size: 12px; padding: 5px 8px; }

            .actions-box { width: 100%; height: auto; min-height: 52px; padding: 4px; gap: 4px; justify-content: center; box-sizing: border-box; }
            .btn-nav { width: 44px; height: 44px; font-size: 18px; }

            .tableFixHead.table-responsive {
                max-height: calc(100vh - var(--navbar-height, 112px) - 130px);
                border: none; border-radius: 0; box-shadow: none; background: transparent;
                margin-bottom: 10px; overflow: auto; -webkit-overflow-scrolling: touch;
            }
            .tableFixHead thead { display: none; }
            .tableFixHead table, .tableFixHead tbody, .tableFixHead tr, .tableFixHead td { display: block; width: 100%; }
            .tableFixHead tbody tr {
                background: #fff !important; border: 1.5px solid var(--border); border-radius: 10px;
                padding: 12px 14px; margin-bottom: 10px; box-shadow: var(--shadow-sm); cursor: pointer;
            }
            .table-striped > tbody > tr:nth-of-type(odd) { background: #fff !important; }
            .tableFixHead tbody tr:hover { background: #fafcff !important; }
            .tableFixHead tbody tr td {
                padding: 6px 0 !important; border: none !important;
                text-align: left !important; font-size: 12.5px; word-break: break-word;
            }
            .tableFixHead tbody td::before {
                content: attr(data-label); display: block; font-size: 10px; font-weight: 700;
                text-transform: uppercase; letter-spacing: .4px; color: var(--text-muted); margin-bottom: 2px;
            }
            .tableFixHead tbody td.sin-resultados { text-align: center !important; padding: 24px 8px !important; background: #fff; border: 1.5px solid var(--border); border-radius: 10px; }
            .tableFixHead tbody td.sin-resultados::before { content: none; }

            .nc-empty-state { min-height: calc(100vh - var(--navbar-height, 112px) - 220px); padding: 28px 16px; }
            .nc-empty-state .nc-empty-icon { font-size: 44px; }
            .nc-empty-state h6 { font-size: 15px; }
            .nc-empty-state p { font-size: 12.5px; }
            .tableFixHead tbody tr td:last-child { border-top: 1px dashed var(--border) !important; padding-top: 10px !important; margin-top: 6px; }

            .tableFixHead tbody td.desc-cell { cursor: pointer; }
            .tableFixHead tbody td.desc-cell .desc-text {
                display: -webkit-box; -webkit-box-orient: vertical; -webkit-line-clamp: 3;
                overflow: hidden; text-overflow: ellipsis; line-height: 1.4;
            }
            .tableFixHead tbody td.desc-cell .desc-hint {
                display: inline-block; margin-top: 4px; font-size: 10.5px; font-weight: 700;
                color: var(--primary); text-transform: uppercase; letter-spacing: .3px;
            }
            .tableFixHead tbody td.desc-cell .desc-hint::after { content: 'Toca para ver el registro completo ›'; }
            .tableFixHead tbody tr.fila-seleccionada {
                border-color: var(--primary) !important;
                box-shadow: 0 0 0 2px rgba(37, 99, 235, .22), var(--shadow-sm) !important;
            }

            .nc-pagination { padding: 10px 12px; flex-direction: column; align-items: stretch; gap: 10px; }
            .nc-pagination-info { text-align: center; }
            .nc-pagination-controls { justify-content: center; }
            .page-btn { min-width: 36px; height: 36px; }

            .acciones-cell { justify-content: center; gap: 6px; flex-wrap: wrap; }
            .tableFixHead tbody td[data-label="Acciones"] { text-align: center !important; }
            .tableFixHead tbody td[data-label="Acciones"]::before { content: none; display: none; }
            .action-btn { width: 40px; height: 40px; font-size: 16px; }
            .action-btn i { font-size: 16px; }
            .fecha-cierre { display: inline-flex; align-items: center; height: 40px; margin-right: 0; }

            .modal-dialog { margin: 8px auto !important; width: calc(100% - 16px) !important; max-width: none !important; }
            .modal-content { border-radius: 10px !important; }
            .modal-header { padding: 12px 14px !important; }
            .modal-title { font-size: 13px; }
            .modal-title i { font-size: 15px; }
            .modal-body { padding: 12px !important; }
            .modal-footer { padding: 10px 12px !important; flex-wrap: wrap; }
            .modal-footer .btn { flex: 1 1 auto; min-width: 0; padding: 10px 14px; font-size: 12px; justify-content: center; }
            .modal-dialog-scrollable .modal-body { max-height: calc(100vh - 140px); }

            #ModalConsultaNoConformidad .modal-body { padding: 14px !important; }
            .nc-detail { gap: 14px; }
            .nc-detail-head { flex-direction: column; align-items: stretch; }
            .nc-detail-pdf { align-self: flex-start; }
            .nc-detail-number { font-size: 18px; }
            .nc-detail-meta, .nc-detail-columns, .nc-followup-grid { grid-template-columns: 1fr; }
            .nc-detail-field--wide, .nc-detail-field--full { grid-column: auto; }
            .nc-detail-columns > .nc-detail-section:only-child { grid-column: auto; }
            .nc-detail-desc { padding: 10px 12px; }
            .nc-detail-section-title { font-size: 12px; }

            #FrmNoConformidad { gap: 10px; }
            #FrmNoConformidad > .col-sm-4 { flex: 1 1 100%; min-width: 100%; width: 100%; }
            #FrmNoConformidad textarea.form-control { min-height: 70px; }
            .nc-box { padding: 14px 12px; gap: 10px; }

            .nc-section, .seguimiento-card { padding: 12px 10px !important; }
            .seguimiento-header { margin: 0 0 8px 0; padding: 0 0 6px 0; }
            .badge-seguimiento { font-size: 10px; padding: 2px 10px; }
            .btn-borrar-seguimiento { height: 42px; margin: 6px 0 0 0; }
            .btn-borrar-seguimiento .action-btn { width: 42px; height: 42px; }

            .btn-upload-file { padding: 12px 14px !important; font-size: 12px; }
            .myAlert-top { min-width: auto; width: 92%; font-size: 12px; padding: 12px 16px; top: 70px; }
            textarea.form-control { min-height: 70px; }
            .nc-field { padding: 10px 12px !important; }
            .nc-value { font-size: 13px; }
            .d-none-mobile { display: none !important; }
        }

        /* ===== RESPONSIVE: móvil compacto (hasta 480 px) ===== */
        @media (max-width: 480px) {
            .navbar .collapse form > .col-sm-10 > .form-group { width: 100%; float: none; }
            .modal-footer { flex-direction: column; }
            .modal-footer .btn { width: 100%; }
            #FrmCerrarNoConformidad .col-sm-6, #FrmCerrarNoConformidad .col-sm-2, #FrmCerrarNoConformidad .col-sm-4 { width: 100%; float: none; }
            .actions-box { flex-wrap: wrap; }
            .btn-nav { flex: 1 1 0; min-width: 40px; }
        }

        /* ===== RESPONSIVE: ajuste del detalle en tablet ===== */
        @media (min-width: 768px) and (max-width: 991px) {
            #ModalConsultaNoConformidad .modal-body { padding: 18px !important; }
            .nc-detail-head { flex-direction: row; align-items: flex-start; }
            .nc-detail-pdf { align-self: auto; }
            .nc-detail-meta, .nc-followup-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .nc-detail-field--wide, .nc-detail-field--full { grid-column: 1 / -1; }
            .nc-detail-columns > .nc-detail-section:only-child { grid-column: 1 / -1; }
        }

        /* ===== RESPONSIVE: pantallas anchas (1600 px en adelante) ===== */
        @media (min-width: 1600px) {
            /* 1. El contenido deja de estar clavado en 1600 px */
            .container { max-width: 2400px; }

            /* 2. La navbar fija se alinea con el contenido (mismos márgenes) */
            .navbar-default {
                padding-left: calc((100vw - min(96vw, 2400px)) / 2 + 16px);
                padding-right: calc((100vw - min(96vw, 2400px)) / 2 + 16px);
            }

            /* 3. Cada modal con un ancho cómodo, no gigante */
            .modal-dialog { max-width: 1900px; }
            #ModalEditarNoConformidad .modal-dialog,
            #ModalCrearNoConformidad .modal-dialog { max-width: 1700px; }
            #ModalSeguimientos .modal-dialog { max-width: 1500px; }
            #ModalCerrarNoConformidad .modal-dialog { max-width: 1400px; }

            /* 4. El detalle de la consulta aprovecha el modal */
            .nc-detail { max-width: 1700px; }

            /* 5. Un poco más de aire en la tabla */
            .table > tbody > tr > td,
            .table > thead > tr > th { padding: 14px 20px !important; }
        }

        /* ===== RESPONSIVE: pantallas muy anchas (2200 px en adelante) ===== */
        @media (min-width: 2200px) {
            .container { max-width: 2600px; }
            .navbar-default {
                padding-left: calc((100vw - min(96vw, 2600px)) / 2 + 16px);
                padding-right: calc((100vw - min(96vw, 2600px)) / 2 + 16px);
            }
            body { font-size: 15px; }
            .nc-detail { max-width: 2000px; }
            .nc-detail-value, .nc-detail-text { font-size: 14px; }
        }

        /* Evita que el encabezado de la tabla se superponga a los modales. */
        body.modal-open .tableFixHead thead th { position: static !important; top: auto !important; }
    </style>

    <script type="text/javascript">
        $(function () {
            $('#DivDetectadaEnOtra, #DivRequiereAC, #DivNumeroNCAccionCorrectiva').hide();

            $.datepicker.regional['es'] = {
                closeText: 'Cerrar', prevText: '&#x3c;Ant', nextText: 'Sig&#x3e;', currentText: 'Hoy',
                monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
                monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
                dayNames: ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'],
                dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
                dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
                weekHeader: 'Sm', dateFormat: 'dd/mm/yy', firstDay: 1, isRTL: false,
                showMonthAfterYear: false, yearSuffix: ''
            };
            $.datepicker.setDefaults($.datepicker.regional['es']);

            $("#FApertura, #FRevision, #FCierre").datepicker({ dateFormat: 'dd-mm-yy', showOn: 'focus' });

            // Inicializar Select2 en los selectores superiores (Búsqueda en tiempo real)
            $('#FiltroCodGestion, #FiltroProceso, #FiltroTipo, #FiltroTipoAcciones, #Ordencito').select2({
                language: 'es',
                width: '100%',
                placeholder: function(){
                    return $(this).data('placeholder');
                },
                allowClear: false
            });

            ConfigurarSelectoresNoConformidad('ModalEditarNoConformidad');
            $('#ModalCrearNoConformidad').on('hidden.bs.modal', function () {
                UbicarFormularioNoConformidad('ModalEditarNoConformidad');
            });

            // Inicializar Select2 en el modal de reportes
            $('#TipoReporte, #ReportProceso').select2({
                language: 'es',
                dropdownParent: $('#ModalReportes'),
                width: '100%'
            });

            function AjustarAlturaMenu() {
                var nav = document.querySelector('.navbar-fixed-top');
                if (!nav) return;
                if (nav.querySelector('.navbar-collapse.in')) {
                    return;
                }
                var alto = nav.offsetHeight;
                document.body.style.paddingTop = alto + 'px';
                document.documentElement.style.setProperty('--navbar-height', alto + 'px');
            }
            AjustarAlturaMenu();
            $(window).on('load', AjustarAlturaMenu);
            $(window).on('resize', AjustarAlturaMenu);
            $('.navbar-collapse').on('hidden.bs.collapse', AjustarAlturaMenu);
            $(document).on('shown.bs.modal hidden.bs.modal', function () { AjustarAlturaMenu(); });
        });

        function SoloDigitos(e) { e.value = e.value.replace(/[^0-9]/g, ''); }

        function ConfigurarSelectoresNoConformidad(idModal) {
            var $selectores = $('#CodGestion, #Proceso, #Tipo, #DetectadaEn, #RequiereAC');
            $selectores.each(function () {
                if ($(this).hasClass('select2-hidden-accessible')) {
                    $(this).select2('destroy');
                }
            });
            $selectores.select2({
                language: 'es',
                dropdownParent: $('#' + idModal),
                width: '100%'
            });
        }

        function UbicarFormularioNoConformidad(idModal) {
            var destino = idModal === 'ModalCrearNoConformidad' ? '#DivBodyCrearNoConformidad' : '#DivBodyNoConformidad';
            $('#FrmNoConformidad').appendTo(destino);
            ConfigurarSelectoresNoConformidad(idModal);
        }

        function ObtenerBotonesNoConformidad() {
            return $('#ModalCrearNoConformidad').is(':visible') ? '#BotsCrearNoConformidad' : '#BotsEditarNoConformidad';
        }

        function restaurarBotonArchivo() {
            var label = document.getElementById('LabelFileNoConformidad');
            var span = document.getElementById('SpanFileNoConformidad');
            var btn = document.getElementById('BtnEliminarArchivo');

            if (btn.dataset.existente === '1') {
                span.innerHTML = 'Reemplazar PDF existente';
                label.classList.add('archivo-cargado');
                btn.classList.remove('d-none');
            } else {
                span.innerHTML = 'Seleccionar archivo PDF';
                label.classList.remove('archivo-cargado');
                btn.classList.add('d-none');
            }
        }

        function ValidarArchivo(input) {
            var file = input.files[0];
            if (!file) { restaurarBotonArchivo(); return; }

            if (file.type !== "application/pdf") {
                MostrarDatoObser("¡Error! Solo se admiten archivos en formato PDF");
                input.value = ''; restaurarBotonArchivo(); return;
            }
            if (file.size > 4 * 1024 * 1024) {
                MostrarDatoObser("¡Error! El archivo supera el tamaño máximo permitido (4MB)");
                input.value = ''; restaurarBotonArchivo(); return;
            }

            var nombre = file.name;
            if (nombre.length > 35) nombre = nombre.substring(0, 32) + '...';
            document.getElementById('SpanFileNoConformidad').innerHTML = nombre;
            document.getElementById('LabelFileNoConformidad').classList.add('archivo-cargado');
            document.getElementById('BtnEliminarArchivo').classList.remove('d-none');
        }

        function EliminarArchivoNoConformidad() {
            var input = document.getElementById('FileNoConformidad');
            var btn = document.getElementById('BtnEliminarArchivo');

            if (input.files && input.files.length > 0) {
                input.value = '';
                restaurarBotonArchivo();
                return;
            }

            if (btn.dataset.existente !== '1') { return; }

            var numeroNC = document.getElementById('NumeroNC').value;
            if (!numeroNC || numeroNC === 'Nuevo') { return; }
            if (!confirm('¿Eliminar el PDF adjunto? Esta acción no se puede deshacer.')) { return; }

            btn.disabled = true;
            $.ajax({
                type: 'POST', url: "<?php echo basename(__FILE__); ?>", 
                data: { TipoModificar: '<?php echo md5('JorEliminarArchivo' . date('d')); ?>', NumeroNC: numeroNC }, 
                dataType: 'json', cache: false
            }).done(function (response) {
                if (response.status === 'success') {
                    btn.dataset.existente = '0';
                    restaurarBotonArchivo();
                    MostrarDatoObser('PDF eliminado correctamente.', true);
                } else {
                    MostrarDatoObser(response.message || 'No se pudo eliminar el PDF.');
                }
            }).fail(function () {
                MostrarDatoObser('Error de conexión al eliminar el PDF.');
            }).always(function () {
                btn.disabled = false;
            });
        }

        function MostrarDatoObser(msg, mTipo) {
            var el = document.getElementById('DatoObser');
            var cls = 'alert-danger';
            if (mTipo === true || mTipo === 'success') { cls = 'alert-success'; }
            else if (mTipo === 'info' || msg === 'Procesando') { cls = 'alert-info'; }
            el.className = 'myAlert-top ' + cls;
            if (msg) { el.innerHTML = msg; }
            $("#DatoObser").show();
            setTimeout(function () { $("#DatoObser").hide(); }, 3000);
        }

        function ValidarFiltros() {
            document.FrmFiltrar.submit();
        }

        function ScrollAlPrimerError(contenedor) {
            var $err = $(contenedor).find('.alert-danger').filter(':visible').first();
            if ($err.length) {
                var dom = $err[0];
                try { dom.scrollIntoView({ behavior: 'smooth', block: 'center' }); }
                catch (e) { dom.scrollIntoView(); }
                setTimeout(function () { try { dom.focus(); } catch (e) {} }, 250);
            }
        }

        /* ===== Interacciones responsive ===== */
        function EsVistaMovil() { return window.matchMedia && window.matchMedia('(max-width: 767px)').matches; }
        function EsVistaResponsive() { return window.matchMedia && window.matchMedia('(max-width: 991px)').matches; }

        // Muestra la indicación solo cuando el texto de descripción queda truncado.
        function AjustarHintsDescripcion() {
            if (!EsVistaMovil()) { return; }
            $('.desc-cell').each(function () {
                var $txt = $(this).find('.desc-text');
                var $hint = $(this).find('.desc-hint');
                if (!$txt.length || !$hint.length) { return; }
                $hint.css('display', ($txt[0].scrollHeight - $txt[0].clientHeight > 2) ? '' : 'none');
            });
        }
        $(window).on('load resize', function () { AjustarHintsDescripcion(); });

        // Mantiene visible qué tarjeta está seleccionada durante la interacción táctil.
        $(document).on('touchstart mousedown', '#TBodyNoConformidad tr[data-id]', function () {
            if (!EsVistaResponsive()) { return; }
            var $tr = $(this);
            $('#TBodyNoConformidad tr').removeClass('fila-seleccionada');
            $tr.addClass('fila-seleccionada');
        });

        // En tablet y móvil, tocar una fila abre su consulta completa, excepto en controles interactivos.
        $(document).on('click', '#TBodyNoConformidad tr[data-id]', function (e) {
            if (!EsVistaResponsive()) { return; }
            var $tr = $(this);
            if ($(e.target).closest('a, button, input, select, textarea, .acciones-cell, .fecha-cierre').length) { return; }
            var selection = window.getSelection();
            if (selection && !selection.isCollapsed && this.contains(selection.anchorNode)) { return; }
            var numeroNC = $tr.data('id');
            if (numeroNC) { MostrarDivConsultaNoConformidad(numeroNC); }
        });

        function ValidarDetectadaEn(Valorcito) {
            if (Valorcito == "Otra" || Valorcito == "Quejas") {
                document.FrmNoConformidad.DetectadaEnOtra.value = '';
                $('#DivDetectadaEnOtra').show();
            } else { $('#DivDetectadaEnOtra').hide(); }
        }

        function MostrarDivConsultaNoConformidad(mNumeroNC) {
            $("#ModalConsultaNoConformidadBody").load(
                "<?php echo basename(__FILE__); ?>?TipoModificar=<?php echo md5('AjaxMostrarNoConformidad' . date('d')); ?>&NumeroNC=" + mNumeroNC,
                function() {
                    $("#ModalConsultaNoConformidad").modal('show');
                }
            );
        }

        function MostrarDivEditarNoConformidad(mNumeroNC) {
            var esNuevo = mNumeroNC == 'Nuevo';
            var modalId = esNuevo ? 'ModalCrearNoConformidad' : 'ModalEditarNoConformidad';
            UbicarFormularioNoConformidad(modalId);
            document.FrmNoConformidad.reset();
            document.getElementById('NumeroNC').value = mNumeroNC;
            $("#FrmNoConformidad").find("input:text,select,textarea").removeClass("alert-danger");
            $('#BotsEditarNoConformidad, #BotsCrearNoConformidad').hide();
            $(esNuevo ? '#BotsCrearNoConformidad' : '#BotsEditarNoConformidad').show();
            
            document.getElementById('FileNoConformidad').value = '';
            document.getElementById('BtnEliminarArchivo').dataset.existente = '0';
            restaurarBotonArchivo();

            if (esNuevo) {
                document.getElementById('Responsable').value = '<?php echo $_SESSION['NomUsuario']; ?>';
                document.getElementById('FApertura').value = '<?php echo date('d/m/Y'); ?>';
                $('#DivDetectadaEnOtra, #DivRequiereAC, #DivNumeroNCAccionCorrectiva').hide();
                document.getElementById('Proceso').length = 1;
                $('#CodGestion, #Proceso, #Tipo, #DetectadaEn, #RequiereAC').val('').trigger('change');
                $("#ModalCrearNoConformidad").modal('show');
            } else {
                $("#TituloModalNoConformidad").html('<i class="bi bi-pencil-square"></i> Editar No Conformidad #' + mNumeroNC);
                $("#BtnAceptarNoConformidad").html('<i class="bi bi-save"></i> Guardar Cambios');

                $.ajax({
                    type: "GET", url: '<?php echo basename(__FILE__); ?>',
                    data: { TipoModificar: '<?php echo md5('Ajax1JorA1NoConformidad' . date('d')); ?>', NumeroNC: mNumeroNC },
                    dataType: "json", cache: false
                }).done(function (data) {
                    if (data.status === "Error") { MostrarDatoObser("Se presentó un error"); return false; }
                    $('#CodGestion').val(data.CodGestion).trigger('change');
                    CambioGestionProceso(data.Proceso);
                    document.getElementById('Responsable').value = data.NomResponsable;
                    $('#Tipo').val(data.Tipo).trigger('change');
                    document.getElementById('FApertura').value = data.FApertura;
                    
                    var detectadaVal = data.DetectadaEn;
                    if ($('#DetectadaEn option[value="' + detectadaVal + '"]').length === 0 && detectadaVal !== '') {
                        detectadaVal = 'Otra';
                    }
                    $('#DetectadaEn').val(detectadaVal).trigger('change');
                    ValidarDetectadaEn(detectadaVal);

                    document.getElementById('DetectadaEnOtra').value = data.DetectadaEnOtra || '';
                    document.getElementById('Descripcion').value = data.Descripcion || '';
                    document.getElementById('Correccion').value = data.Correccion || '';
                    $('#RequiereAC').val(data.RequiereAC).trigger('change');
                    CambioTipo();
                    document.getElementById('NumeroNCAccionCorrectiva').value = data.NumeroNCAccionCorrectiva || '';
                    document.getElementById('Causas').value = data.Causas || '';
                    document.getElementById('Accion').value = data.Accion || '';
                    document.getElementById('FRevision').value = data.FRevision || '';

                    document.getElementById('BtnEliminarArchivo').dataset.existente = (data.YaCargado === 1) ? '1' : '0';
                    restaurarBotonArchivo();
                    
                    $("#ModalEditarNoConformidad").modal('show');
                });
            }
        }

        function GrabarNoConformidad() {
            MostrarDatoObser('Procesando');
            var mNumeroNC = document.FrmNoConformidad.NumeroNC.value;
            var selectorBotones = ObtenerBotonesNoConformidad();
            $(selectorBotones).hide();
            var mRetorno = true;
            var $camposObligatorios = $("#Responsable, #Tipo, #CodGestion, #Proceso, #FApertura, #DetectadaEn, #Descripcion, #FRevision");
            $camposObligatorios.each(function () {
                $(this).removeClass("alert-danger");
                if (!$(this).val() || $(this).val().trim() === '') { mRetorno = false; $(this).addClass("alert-danger"); }
            });
            if ($("#DetectadaEn").val() === 'Otra' || $("#DetectadaEn").val() === 'Quejas') {
                if (!$("#DetectadaEnOtra").val() || $("#DetectadaEnOtra").val().trim() === '') { mRetorno = false; $("#DetectadaEnOtra").addClass("alert-danger"); }
                else { $("#DetectadaEnOtra").removeClass("alert-danger"); }
            } else { $("#DetectadaEnOtra").removeClass("alert-danger"); }
            
            if ($("#Tipo").val() === 'Corrección' && $("#RequiereAC").val() === '-1') {
                if (!$("#NumeroNCAccionCorrectiva").val() || $("#NumeroNCAccionCorrectiva").val().trim() === '') { mRetorno = false; $("#NumeroNCAccionCorrectiva").addClass("alert-danger"); }
                else { $("#NumeroNCAccionCorrectiva").removeClass("alert-danger"); }
            }
            
            if (mRetorno) {
                document.FrmNoConformidad.TipoGrabar.value = 'GrabarNoConformidad';
                document.FrmNoConformidad.TipoModificar.value = '<?php echo md5('JorGrabarNoConformidad' . date('d')); ?>';
                var f = new FormData(document.getElementById('FrmNoConformidad'));
                $.ajax({
                    url: "<?php echo basename(__FILE__); ?>", type: 'post', cache: false, data: f,
                    dataType: "json", processData: false, contentType: false
                }).done(function (response) {
                    if (response.status === 'success') {
                        MostrarDatoObser("Los datos se grabaron.", true);
                        $("#FiltroNumeroNC").val(response.id);
                        setTimeout(function() { document.FrmFiltrar.submit(); }, 500);
                    } else { $(selectorBotones).show(); MostrarDatoObser(response.message || "No se pudo guardar el registro."); }
                }).fail(function () { $(selectorBotones).show(); MostrarDatoObser("Error de conexión o formato inválido."); });
            } else {
                $(selectorBotones).show();
                MostrarDatoObser('<i class="bi bi-exclamation-triangle"></i> Complete los campos resaltados en rojo.');
                ScrollAlPrimerError('#FrmNoConformidad');
            }
        }

        function MostrarDivSeguimientos(mNumeroNCSeguimientos) {
            document.FrmSeguimientos.reset();
            document.getElementById('NumeroNCSeguimientos').value = mNumeroNCSeguimientos;
            $("#BotsSeguimientos").show(); $("#FrmSeguimientos").find("input:text,select,textarea").removeClass("alert-danger");
            $("#DivSeguimientos").load("<?php echo basename(__FILE__); ?>?TipoModificar=<?php echo md5('AjaxSeguimientos' . date('d')); ?>&NumeroNC=" + mNumeroNCSeguimientos, function () {
                $("#ModalSeguimientos").modal('show');
                $("#DivSeguimientos input[id^='Fecha']").datepicker({ dateFormat: 'dd-mm-yy', showOn: 'focus' });
            });
        }

        function BorrarSeguimiento(Obj) {
            var btn = Obj.closest('button') || Obj.parentElement;
            var mID = btn.id.replace("BotBorrarSeguimiento", "");
            document.getElementById('Borrada' + mID).value = 1; $("#DivSeguimiento" + mID).hide();
        }

        function AgregarSeguimiento() {
            var mRegNew = parseInt(document.getElementById('CantSeguimientos').value, 10);
            if (isNaN(mRegNew)) { mRegNew = 1; } else { mRegNew++; }
            document.getElementById('CantSeguimientos').value = mRegNew;

            var $nuevoBloque = $('#DivSeguimiento1').clone();
            $nuevoBloque.find("input,select,a,button,textarea,div,span").each(function () {
                $(this).attr({
                    'id': function (_, id) { return (id ? id.replace('1', mRegNew) : null); },
                    'name': function (_, name) { return (name ? name.replace('1', mRegNew) : null); },
                    'checked': false
                });
                if ($(this).is('input,select,textarea')) { $(this).val(''); }
            });

            $nuevoBloque.find("input[id^='Fecha']").removeClass('hasDatepicker').removeData('datepicker').unbind();
            
            $nuevoBloque.find('#SpanSeguimiento' + mRegNew).text('Seguimiento ' + mRegNew);
            
            $nuevoBloque.attr("id", "DivSeguimiento" + mRegNew);
            $nuevoBloque.show(); $nuevoBloque.appendTo('#DivSeguimientos');
            $("#Fecha" + mRegNew).datepicker({ dateFormat: 'dd/mm/yy', showOn: 'focus' });
        }

        $(document).on('mouseenter', '.btn-borrar-seguimiento', function() {
            $(this).closest('.seguimiento-card').addClass('card-eliminar-hover');
        }).on('mouseleave', '.btn-borrar-seguimiento', function() {
            $(this).closest('.seguimiento-card').removeClass('card-eliminar-hover');
        });

        function GrabarSeguimientos() {
            MostrarDatoObser('Procesando');
            var mNumeroNCSeguimientos = document.getElementById('NumeroNCSeguimientos').value;
            $("#BotsSeguimientos").hide();
            var mRetorno = true, mAlMenosUno = false;
            var mCantSeguimientos = document.getElementById('CantSeguimientos').value;
            
            for (var NumSeguimiento = 1; NumSeguimiento <= mCantSeguimientos; NumSeguimiento++) {
                if (document.getElementById('Borrada' + NumSeguimiento) && document.getElementById('Borrada' + NumSeguimiento).value != 1) {
                    mAlMenosUno = true;
                    $("#Accion" + NumSeguimiento + ", #Resultado" + NumSeguimiento + ", #Fecha" + NumSeguimiento + ", #RealizadoPor" + NumSeguimiento).each(function () {
                        $(this).removeClass("alert-danger");
                        if (!$(this).val() || $(this).val().trim() === '') { mRetorno = false; $(this).addClass("alert-danger"); }
                    });
                }
            }
            if (!mAlMenosUno) { mRetorno = false; }

            if (mRetorno) {
                document.FrmSeguimientos.TipoGrabar.value = 'Seguimientos';
                document.FrmSeguimientos.TipoModificar.value = '<?php echo md5('JorSeguimientos' . date('d')); ?>';
                var f = new FormData(document.getElementById('FrmSeguimientos'));
                $.ajax({
                    url: "<?php echo basename(__FILE__); ?>", type: 'post', cache: false, data: f, dataType: "json", processData: false, contentType: false
                }).done(function (response) {
                    if (response.status === 'success') {
                        MostrarDatoObser("Los datos se grabaron.", true); setTimeout(function () { location.reload(); }, 500);
                    } else { $("#BotsSeguimientos").show(); MostrarDatoObser(response.message || "Error al guardar."); }
                });
            } else {
                $("#BotsSeguimientos").show();
                if (!mAlMenosUno) { MostrarDatoObser('<i class="bi bi-exclamation-triangle"></i> Debe registrar al menos un seguimiento.'); }
                else { MostrarDatoObser('<i class="bi bi-exclamation-triangle"></i> Complete los campos resaltados en rojo.'); ScrollAlPrimerError('#FrmSeguimientos'); }
            }
        }

        function MostrarDivCerrarNoConformidad(mNumeroNCCerrar) {
            document.FrmCerrarNoConformidad.reset();
            document.getElementById('NumeroNCCerrar').value = mNumeroNCCerrar;
            $("#BotsCerrarNoConformidad").show(); $("#FrmCerrarNoConformidad").find("input:text,select,textarea").removeClass("alert-danger");
            $.ajax({
                type: "GET", url: '<?php echo basename(__FILE__); ?>',
                data: { TipoModificar: '<?php echo md5('AjaxCerrarNoConformidad' . date('d')); ?>', NumeroNC: mNumeroNCCerrar }, cache: false
            }).done(function (html) {
                if (html == "Error") { MostrarDatoObser("Se presentó un error"); return false; }
                else { eval(html); $("#ModalCerrarNoConformidad").modal('show'); }
            });
        }

        function GrabarCerrarNoConformidad() {
            MostrarDatoObser('Procesando');
            var mNumeroNCCerrar = document.getElementById('NumeroNCCerrar').value;
            $("#BotsCerrarNoConformidad").hide(); var mRetorno = true;
            $("#ResultadoRevision, #MotivoCierre, #NuevaAccion, #FCierre, #RCierre").each(function () {
                $(this).removeClass("alert-danger");
                if (!$(this).val() || $(this).val().trim() === '') { mRetorno = false; $(this).addClass("alert-danger"); }
            });
            if (mRetorno) {
                document.FrmCerrarNoConformidad.TipoGrabar.value = 'CerrarNoConformidad';
                document.FrmCerrarNoConformidad.TipoModificar.value = '<?php echo md5('JorCerrarNoConformidad' . date('d')); ?>';
                var f = new FormData(document.getElementById('FrmCerrarNoConformidad'));
                $.ajax({
                    url: "<?php echo basename(__FILE__); ?>", type: 'post', cache: false, data: f, dataType: "json", processData: false, contentType: false
                }).done(function (response) {
                    if (response.status === 'success') {
                        MostrarDatoObser("Los datos se grabaron.", true); setTimeout(function () { location.reload(); }, 500);
                    } else { $("#BotsCerrarNoConformidad").show(); MostrarDatoObser(response.message || "Error al guardar."); }
                });
            } else {
                $("#BotsCerrarNoConformidad").show(); MostrarDatoObser('<i class="bi bi-exclamation-triangle"></i> Complete los campos resaltados en rojo.'); ScrollAlPrimerError('#FrmCerrarNoConformidad');
            }
        }

        var ultimaPeticionProceso = 0;
        var procesoPendiente = '';
        function CambioGestionProceso(mProceso) {
            if (typeof mProceso === 'undefined') mProceso = '';
            var sel = document.getElementById('Proceso');
            var mCodGestion = document.getElementById('CodGestion').value;
            if (mProceso) procesoPendiente = mProceso;
            var seleccionado = procesoPendiente || sel.value;
            var id = ++ultimaPeticionProceso;
            sel.length = 1;
            if (!mCodGestion) { procesoPendiente = ''; $('#Proceso').val('').trigger('change'); return; }
            $.ajax({
                type: "GET", url: "<?php echo basename(__FILE__); ?>", dataType: "json", cache: false,
                data: { TipoModificar: '<?php echo md5('JorCambioGestionProceso' . date('d')); ?>', CodGestion: mCodGestion }
            }).done(function (r) {
                if (id !== ultimaPeticionProceso) return;
                if (r.status !== 'success') { MostrarDatoObser(r.message || "Se presentó un error"); return; }
                sel.length = 1;
                r.procesos.forEach(p => sel.add(new Option(p.nombre, p.codigo)));
                if (seleccionado && Array.from(sel.options).some(o => o.value === seleccionado)) { sel.value = seleccionado; }
                procesoPendiente = ''; $('#Proceso').trigger('change');
            });
        }

        function CambioTipo() {
            var mCodTipo = document.getElementById('Tipo').value;
            var mRequiereAC = document.getElementById('RequiereAC').value;
            if (mCodTipo == 'Corrección') {
                $("#DivRequiereAC").show();
                if (mRequiereAC == '-1') { $("#DivNumeroNCAccionCorrectiva").show(); }
                else { $("#DivNumeroNCAccionCorrectiva").hide(); }
            } else { $("#DivRequiereAC, #DivNumeroNCAccionCorrectiva").hide(); }
        }

        function MostrarDivReportes() { $("#ModalReportes").modal('show'); }

        function EnviarReportes() {
            var mRetorno = true;
            $("#TipoReporte").removeClass("alert-danger");
            if (!$("#TipoReporte").val()) { mRetorno = false; $("#TipoReporte").addClass("alert-danger"); }
            if (!mRetorno) { MostrarDatoObser('<i class="bi bi-exclamation-triangle"></i> Seleccione el tipo de reporte.'); ScrollAlPrimerError('#FrmReportes'); return; }
            
            MostrarDatoObser('Procesando');
            var $btnGenerar = $("#BtnGenerarReporte"); $btnGenerar.prop('disabled', true);
            (async () => {
                try {
                    var url = "<?php echo basename(__FILE__); ?>?TipoModificar=EnviarReportes&TipoGrabar=<?php echo md5('JorEnviarReportes' . date('d')); ?>" +
                              "&TipoReporte=" + encodeURIComponent($("#TipoReporte").val()) +
                              "&ReportProceso=" + encodeURIComponent($("#ReportProceso").val() || '');
                    const response = await fetch(url);
                    if (!response.ok) { throw new Error('Error HTTP ' + response.status); }
                    const raw_data = await response.json();
                    if (!Array.isArray(raw_data)) { MostrarDatoObser('<i class="bi bi-exclamation-triangle"></i> ' + (raw_data.error || 'No se pudo generar el reporte.')); return; }
                    if (raw_data.length < 2 || !raw_data[0] || !raw_data[0]['Campos']) { MostrarDatoObser('<i class="bi bi-exclamation-triangle"></i> No hay datos para el reporte.'); return; }
                    let mCampos = raw_data[0]; raw_data.shift();
                    const prez = raw_data; var rows = [];
                    if (prez.length === 0) { MostrarDatoObser('<i class="bi bi-exclamation-triangle"></i> No hay datos.'); return; }
                    eval(mCampos['Campos']);
                    const worksheet = XLSX.utils.json_to_sheet(rows);
                    eval(mCampos['Formato']);
                    const workbook = XLSX.utils.book_new();
                    XLSX.utils.book_append_sheet(workbook, worksheet, "Datos");
                    XLSX.writeFile(workbook, "datos.xlsx", { compression: true });
                    MostrarDatoObser('<i class="bi bi-check-circle"></i> Reporte generado correctamente.', true);
                } catch (err) { MostrarDatoObser('<i class="bi bi-exclamation-triangle"></i> Error al generar reporte: ' + err.message);
                } finally { $btnGenerar.prop('disabled', false); }
            })();
        }

        function formatColumn(worksheet, col, fmt) {
            if (!worksheet['!ref']) return;
            const range = XLSX.utils.decode_range(worksheet['!ref']);
            for (let row = range.s.r + 1; row <= range.e.r; ++row) {
                const ref = XLSX.utils.encode_cell({ c: col, r: row });
                if (worksheet[ref] && worksheet[ref].t === 'n') { worksheet[ref].z = fmt; }
            }
        }

        function CambioTipoReporte(Obj) {
            $("#DivFechasReporte").hide();
            if (['ReporteNoConformidades'].indexOf(Obj.value) >= 0) { $("#DivFechasReporte").show(); }
        }

        function ReversarNoConformidad(mNumeroNC) {
            if (confirm('¿Está seguro que desea reversar esta no conformidad? Esto borrará la fecha de cierre y permitirá editarla nuevamente.')) {
                MostrarDatoObser('Procesando');
                $.ajax({
                    type: "POST", url: "<?php echo basename(__FILE__); ?>", data: { TipoModificar: '<?php echo md5('JorReversarNoConformidad' . date('d')); ?>', TipoGrabar: 'ReversarNoConformidad', NumeroNC: mNumeroNC }, dataType: 'json', cache: false
                }).done(function (response) {
                    if (response.status === 'success') {
                        MostrarDatoObser("No conformidad reversada exitosamente.", true); setTimeout(function () { location.reload(); }, 1000);
                    } else { MostrarDatoObser("Error al reversar: " + (response.message || '')); }
                });
            }
        }
    </script>
</head>

<body style="padding-top: 96px;" <?php if ($_GET['DatoObser']) { ?>onLoad="MostrarDatoObser('<?php echo $_GET['DatoObser']; ?>', true);"<?php } ?>>

<div class="myAlert-top" id="DatoObser" style="display:none"><b>Danger!</b> ...</div>

<div class="container">

    <!-- NAVBAR / FILTROS -->
    <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex6-collapse">
                <span class="sr-only">Desplegar navegación</span>
                <span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">
                <span class="brand-icon"><i class="bi bi-clipboard-check"></i></span>
                <span>No Conformidades & Mejoras
                    <span class="label-xsmall">Versión 2 &nbsp;·&nbsp; Código PGGC-04-R-01</span>
                </span>
            </a>
        </div>

        <div class="collapse navbar-collapse navbar-ex6-collapse">
            <form method="get" enctype="multipart/form-data" class="form_validar" name="FrmFiltrar" id="FrmFiltrar">
                <input name="Filtrar" type="hidden" id="Filtrar" value="1">

                <div class="col-sm-10 form-group" style="padding-top:6px">
                    <div class="col-sm-2 form-group">
                        <select name="FiltroCodGestion" class="form-control" id="FiltroCodGestion">
                            <option value="" selected>Gestión (Todas)</option>
                            <?php
                            mysqli_data_seek($ResultGes, 0);
                            for ($j = 1; $j <= $ResultGes->num_rows; $j++) {
                                $RowGes = $ResultGes->fetch_assoc();
                                ?>
                                <option value="<?php echo $RowGes['CodGestion']; ?>" <?php if ($_GET['FiltroCodGestion'] == $RowGes['CodGestion']) echo "selected"; ?>><?php echo $RowGes['NomGestion']; ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-sm-2 form-group">
                        <select name="FiltroProceso" class="form-control" id="FiltroProceso">
                            <option value="" selected>Cualquier proceso</option>
                            <?php
                            if ($PuedeAdministrar or $PuedeGeneral) {
                                $QueriProc = "SELECT CodGestion, Colegio, CodProceso, NomProceso FROM calidad.procesos ORDER BY CodGestion, NomProceso";
                            } else {
                                $QueriProc = "SELECT DISTINCT CodGestion, Colegio, CodProceso, NomProceso FROM calidad.procesos WHERE FIND_IN_SET('" . $_SESSION["Usuario"] . "', DueProceso) ORDER BY CodGestion, NomProceso";
                            }
                            $ResultProc = $mysqli->query($QueriProc);
                            while ($RowProc = $ResultProc->fetch_assoc()) {
                                ?>
                                <option value="<?php echo $RowProc['Colegio'] . $RowProc['CodProceso']; ?>" <?php if ($_GET['FiltroProceso'] == $RowProc['Colegio'] . $RowProc['CodProceso']) echo "selected"; ?>><?php echo $RowProc['CodGestion'] . ' ' . $RowProc['NomProceso']; ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-sm-2 form-group" title="Tipo de Registro">
                        <select name="FiltroTipo" class="form-control" id="FiltroTipo">
                            <option value="" selected>Tipo Registro (Todos)</option>
                            <?php foreach ($mTipoRegistro as $Clave => $Valor) { ?>
                                <option value='<?php echo $Clave; ?>' <?php if ($_GET['FiltroTipo'] == $Clave) echo "selected"; ?>><?php echo $Clave; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-sm-2 form-group">
                        <select name="FiltroTipoAcciones" class="form-control" id="FiltroTipoAcciones">
                            <option value="" selected>Todas las acciones</option>
                            <option value=1 <?php if ($_GET['FiltroTipoAcciones'] == 1) echo "selected"; ?>>Sin Acciones</option>
                            <option value=2 <?php if ($_GET['FiltroTipoAcciones'] == 2) echo "selected"; ?>>Con Acciones</option>
                            <option value=3 <?php if ($_GET['FiltroTipoAcciones'] == 3) echo "selected"; ?>>Por Cerrar</option>
                            <option value=4 <?php if ($_GET['FiltroTipoAcciones'] == 4) echo "selected"; ?>>Cerradas</option>
                        </select>
                    </div>
                    <div class="col-sm-2 form-group">
                        <select name="Ordencito" class="form-control" id="Ordencito">
                            <option value=2 <?php if ($_GET['Ordencito'] == 2) echo "selected"; ?>>Ordenar por Fecha</option>
                            <option value=1 <?php if ($_GET['Ordencito'] == 1) echo "selected"; ?>>Ordenar por Número</option>
                        </select>
                    </div>
                    <div class="col-sm-2 form-group">
                        <input type="text" name="FiltroNumeroNC" id="FiltroNumeroNC" class="form-control" placeholder="N° reg" value="<?php echo $_GET['FiltroNumeroNC']; ?>">
                    </div>
                </div>

                <div class="col-sm-2 form-group" style="padding-top:6px">
                    <div class="actions-box">
                        <button class="btn btn-nav" type="button" onClick="ValidarFiltros();" title="Filtrar datos"><i class="bi bi-search"></i></button>
                        <a onClick="MostrarDivEditarNoConformidad('Nuevo');" class="btn btn-nav btn-nav-primary" title="Crear registro"><i class="bi bi-plus-lg"></i></a>
                        <button class="btn btn-nav" type="button" onClick="MostrarDivReportes();" title="Generar Reportes"><i class="bi bi-file-earmark-excel"></i></button>
                        <a href="../indexadmon.php" class="btn btn-nav" title="Regresar"><i class="bi bi-house"></i></a>
                    </div>
                </div>
            </form>
        </div>
    </nav>

    <!-- TABLA + PAGINACIÓN -->
    <div class="row sidenav">

        <?php if ($Buscar) { ?>
        <div class="tableFixHead table-responsive">
            <table class="table table-striped table-bordered small">
                <thead>
                    <TR>
                        <TH width="10%">Fecha / ID solicitud</TH>
                        <TH width="17%">Responsable Apertura</TH>
                        <TH width="7%">Proceso</TH>
                        <TH width="46%">Descripción</TH>
                        <TH width="10%">Detectada en</TH>
                        <TH width="10%">Acciones</TH>
                    </TR>
                </thead>
                <tbody id="TBodyNoConformidad">
                    <?php while ($Row = $Result->fetch_assoc()) { $mContadorFilas++; ?>
                        <TR align="left" data-id="<?php echo $Row['NumeroNC']; ?>">
                            <TD data-label="Fecha / ID SOLICITUD">
                                <?php echo DarFechaHora($Row['FApertura'], 1); ?>
                                (<a class="link-modern" style="cursor:pointer" onClick="MostrarDivConsultaNoConformidad(<?php echo $Row['NumeroNC'] ?>);"><?php echo $Row['NumeroNC']; ?></a>)
                            </TD>
                            <TD data-label="Responsable"><?php echo $Row['NomResponsable']; ?></TD>
                            <TD data-label="Proceso"><?php echo $Row['Proceso'] . ' (' . $Row['CodGestion'] . ')'; ?></TD>
                            <TD data-label="Descripción" class="desc-cell">
                                <div class="desc-text"><?php if ($Row['Borrada']) { ?><span class="badge-borrada">BORRADA</span> <?php } ?><span class="desc-contenido"><?php echo $Row['Descripcion']; ?></span></div>
                                <span class="desc-hint"></span>
                            </TD>
                            <TD data-label="Detectada en"><?php echo $Row['DetectadaEn']; ?></TD>
                            <TD data-label="Acciones">
                                <div class="acciones-cell">
                                    <a class="action-btn action-view" onClick="MostrarDivConsultaNoConformidad(<?php echo $Row['NumeroNC']; ?>);" title="Ver detalles del registro" aria-label="Ver detalles del registro"><i class="bi bi-eye"></i></a>
                                    <?php
                                    if ($Row['FCierre'] > 0) {
                                        echo '<span class="fecha-cierre">' . DarFecha($Row['FCierre']) . '</span>';
                                        if ($PuedeReversar) {
                                            ?>
                                            <a class="action-btn action-danger" onClick="ReversarNoConformidad({{ $Row['NumeroNC'] }})" title="Reversar no conformidad"><i class="bi bi-arrow-counterclockwise"></i></a>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <a class="action-btn action-edit" onClick="MostrarDivEditarNoConformidad(<?php echo $Row['NumeroNC']; ?>);" title="Editar"><i class="bi bi-pencil-square"></i></a>
                                        <a class="action-btn action-list" onClick="MostrarDivSeguimientos(<?php echo $Row['NumeroNC'] ?>);" title="Seguimientos"><i class="bi bi-list-check"></i></a>
                                        <?php
                                        if ($PuedeCerrar) {
                                            ?>
                                            <a class="action-btn action-success" onClick="MostrarDivCerrarNoConformidad(<?php echo $Row['NumeroNC'] ?>)" title="Cerrar seguimiento" aria-label="Cerrar seguimiento"><i class="bi bi-check2-circle"></i></a>
                                            <?php
                                        }
                                    }
                                    ?>
                                </div>
                            </TD>
                        </TR>
                    <?php } ?>

                    <?php if ($mContadorFilas == 0) { ?>
                        <TR>
                            <TD colspan="6" class="sin-resultados">
                                <i class="bi bi-inbox"></i>
                                <b>No se encontraron registros con los criterios actuales.</b>
                                <small>Modifique los filtros o use <b>"N° reg"</b> para buscar por número.</small>
                            </TD>
                        </TR>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <?php if (isset($totalRegistros) && $totalRegistros > 0) { ?>
        <div class="nc-pagination">
            <div class="nc-pagination-info">
                Mostrando <?php echo ($offset + 1); ?>&ndash;<?php echo min($offset + $regPorPagina, $totalRegistros); ?> de <?php echo $totalRegistros; ?> registros
            </div>
            <div class="nc-pagination-controls">
                <a class="page-btn <?php echo ($paginaActual <= 1 ? 'disabled' : ''); ?>" href="<?php echo ($paginaActual > 1 ? htmlspecialchars($UrlPagina($paginaActual - 1)) : '#'); ?>">&laquo; Anterior</a>
                <?php
                $rangoPag = 2;
                $inicioPag = max(1, $paginaActual - $rangoPag);
                $finPag = min($totalPaginas, $paginaActual + $rangoPag);
                if ($inicioPag > 1) {
                    echo '<a class="page-btn" href="' . htmlspecialchars($UrlPagina(1)) . '">1</a>';
                    if ($inicioPag > 2) { echo '<span class="page-ellipsis">&hellip;</span>'; }
                }
                for ($p = $inicioPag; $p <= $finPag; $p++) {
                    $act = ($p == $paginaActual) ? ' active' : '';
                    echo '<a class="page-btn' . $act . '" href="' . htmlspecialchars($UrlPagina($p)) . '">' . $p . '</a>';
                }
                if ($finPag < $totalPaginas) {
                    if ($finPag < $totalPaginas - 1) { echo '<span class="page-ellipsis">&hellip;</span>'; }
                    echo '<a class="page-btn" href="' . htmlspecialchars($UrlPagina($totalPaginas)) . '">' . $totalPaginas . '</a>';
                }
                ?>
                <a class="page-btn <?php echo ($paginaActual >= $totalPaginas ? 'disabled' : ''); ?>" href="<?php echo ($paginaActual < $totalPaginas ? htmlspecialchars($UrlPagina($paginaActual + 1)) : '#'); ?>">Siguiente &raquo;</a>
            </div>
        </div>
        <?php } ?>
        <?php } else { ?>
        <div class="nc-empty-state">
            <i class="bi bi-search nc-empty-icon"></i>
            <h6>Consulta de No Conformidades</h6>
            <p>Los registros están ocultos. Use los filtros de la barra superior y presione el botón <b>Buscar</b> para consultarlos. Si no selecciona ningún filtro, se mostrarán todos los registros.</p>
            <ul>
                <li><i class="bi bi-hash"></i> <b>N° reg:</b> abre un registro por su número.</li>
                <li><i class="bi bi-funnel"></i> <b>Gestión / Proceso / Tipo:</b> filtran el listado.</li>
                <li><i class="bi bi-search"></i> <b>Buscar:</b> lupa de la barra superior.</li>
            </ul>
        </div>
        <?php } ?>
    </div>

</div>

<!-- ===================== MODALES ===================== -->

<!-- MODAL CONSULTA -->
<div class="modal fade" id="ModalConsultaNoConformidad" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document" style="width:98%">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalConsultaNoConformidadTitle"><i class="bi bi-eye"></i> Consulta Registro de No Conformidad</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body row">
                <div class="col-sm-12" id="ModalConsultaNoConformidadBody">...</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="BotCancelarModal" data-dismiss="modal"><i class="bi bi-x-lg"></i> Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL EDITAR -->
<div class="modal fade" id="ModalEditarNoConformidad" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document" style="width:98%">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="TituloModalNoConformidad"><i class="bi bi-pencil-square"></i> Editar No Conformidad</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body row" id="DivBodyNoConformidad">
                <form method="post" enctype="multipart/form-data" class="form_validar" name="FrmNoConformidad" id="FrmNoConformidad" autocomplete="off">
                    <input name="TipoGrabar" type="hidden" id="TipoGrabar">
                    <input name="TipoModificar" type="hidden" id="TipoModificar">
                    <input name="NumeroNC" type="hidden" id="NumeroNC">

                    <div class="col-sm-4">
                        <div class="nc-box">
                            <div class="form-group">
                                <label for="Responsable">Responsable de la apertura</label>
                                <input type="text" name="Responsable" id="Responsable" class="form-control" readonly>
                            </div>
                            <div class="nc-row">
                                <div class="nc-cell nc-cell-4">
                                    <label for="Tipo">Tipo</label>
                                    <select name="Tipo" class="form-control" id="Tipo" onChange="CambioTipo()">
                                        <option value="">...</option>
                                        <?php foreach ($mTipoRegistro as $Clave => $Valor) { ?>
                                            <option value='<?php echo $Clave; ?>'><?php echo $Clave; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="nc-cell nc-cell-8">
                                    <label for="Gestion">Gestión</label>
                                    <select name="CodGestion" class="form-control" id="CodGestion" onChange="CambioGestionProceso();">
                                        <option value=""></option>
                                        <?php
                                        mysqli_data_seek($ResultGes, 0);
                                        for ($j = 1; $j <= $ResultGes->num_rows; $j++) {
                                            $RowGes = $ResultGes->fetch_assoc();
                                            ?>
                                            <option value="<?php echo $RowGes['CodGestion']; ?>"><?php echo $RowGes['NomGestion']; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="Proceso">Proceso en donde se detecta</label>
                                <select name="Proceso" class="form-control" id="Proceso"><option value="" selected>...</option></select>
                            </div>
                            <div class="form-group">
                                <label for="FApertura">Fecha de apertura</label>
                                <input type="text" name="FApertura" id="FApertura" class="form-control" placeholder="dd/mm/aaaa" onChange="ValidarFecha(this)" onBlur="ValidarFecha(this)">
                            </div>
                            <div class="nc-row">
                                <div class="nc-cell nc-cell-8">
                                    <label for="DetectadaEn">Detectada en</label>
                                    <select name="DetectadaEn" class="form-control" id="DetectadaEn" onChange="ValidarDetectadaEn(this.value);">
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
                                <div class="nc-cell nc-cell-4" id="DivDetectadaEnOtra">
                                    <label for="DetectadaEnOtra">Cual?</label>
                                    <input name="DetectadaEnOtra" class="form-control" id="DetectadaEnOtra" maxlength="30">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="nc-box">
                            <div class="form-group">
                                <label for="Descripcion">Descripción</label>
                                <textarea name="Descripcion" id="Descripcion" class="form-control" placeholder="Descripción" rows="3"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="Correccion">Relación de actividades</label>
                                <textarea name="Correccion" id="Correccion" class="form-control" placeholder="Describa las actividades que se desarrollaron o están desarrollando para evitar o potencializar lo enunciado en el campo Descripción" rows="3"></textarea>
                            </div>
                            <div class="nc-row">
                                <div class="nc-cell nc-cell-8" id="DivRequiereAC">
                                    <label for="RequiereAC">¿Con la acción determinada se garantiza que la falla no se presente de nuevo?</label>
                                    <select name="RequiereAC" class="form-control" id="RequiereAC" onChange="CambioTipo();">
                                        <option value="">...</option>
                                        <option value="1">Si</option>
                                        <option value="-1">No</option>
                                    </select>
                                </div>
                                <div class="nc-cell nc-cell-4" id="DivNumeroNCAccionCorrectiva">
                                    <label for="NumeroNCAccionCorrectiva">ID acción correctiva</label>
                                    <input name="NumeroNCAccionCorrectiva" class="form-control" id="NumeroNCAccionCorrectiva"
                                           maxlength="11" inputmode="numeric" onInput="SoloDigitos(this)">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="nc-box">
                            <div class="form-group">
                                <label for="Causas">Causas o Ventajas</label>
                                <textarea name="Causas" id="Causas" class="form-control" placeholder="Causas o Ventajas" rows="3"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="Accion">Objetivo principal del plan de Acción</label>
                                <textarea name="Accion" id="Accion" class="form-control" placeholder="Describa el objetivo del plan de acción implementado o a implementar. ¿Qué quiero lograr?" rows="3"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="FRevision">Fecha acordada para la revisión</label>
                                <input type="text" name="FRevision" id="FRevision" class="form-control" placeholder="dd/mm/aaaa" onChange="ValidarFecha(this)" onBlur="ValidarFecha(this)">
                            </div>
                            <div class="form-group">
                                <label>Documento Adjunto</label>
                                <div class="nc-file-row">
                                    <label for="FileNoConformidad" class="btn btn-upload-file flex-grow-1 d-flex align-items-center justify-content-center gap-2 py-2 mb-0" id="LabelFileNoConformidad">
                                        <i class="bi bi-file-earmark-pdf-fill fs-5"></i>
                                        <span id="SpanFileNoConformidad">Seleccionar archivo PDF</span>
                                    </label>
                                    <button type="button" class="action-btn action-danger flex-shrink-0 d-none" id="BtnEliminarArchivo" data-existente="0" onClick="EliminarArchivoNoConformidad();" title="Eliminar PDF adjunto">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </div>
                                <input type="file" id="FileNoConformidad" name="FileNoConformidad" class="d-none" accept="application/pdf" onChange="ValidarArchivo(this)">
                                <small class="text-muted d-block mt-1" style="font-size: 11px;">Máximo 4MB. Si ya hay uno cargado, se reemplazará.</small>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer" id="BotsEditarNoConformidad">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="bi bi-x-lg"></i> Cerrar</button>
                <button type="button" class="btn btn-success" id="BtnAceptarNoConformidad" onClick="GrabarNoConformidad();"><i class="bi bi-check2"></i> Guardar Cambios</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL CREAR -->
<div class="modal fade" id="ModalCrearNoConformidad" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document" style="width:98%">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-plus-circle"></i> Crear No Conformidad</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body row" id="DivBodyCrearNoConformidad"></div>
            <div class="modal-footer" id="BotsCrearNoConformidad">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="bi bi-x-lg"></i> Cerrar</button>
                <button type="button" class="btn btn-success" id="BtnCrearNoConformidad" onClick="GrabarNoConformidad();"><i class="bi bi-check2"></i> Crear Registro</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL SEGUIMIENTOS -->
<div class="modal fade" id="ModalSeguimientos" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document" style="width:98%">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalSeguimientosTitle"><i class="bi bi-list-check"></i> Seguimientos de No Conformidad</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body row" id="DivBodySeguimientos">
                <form method="post" enctype="multipart/form-data" class="form_validar" name="FrmSeguimientos" id="FrmSeguimientos" autocomplete="off">
                    <input name="TipoGrabar" type="hidden" id="TipoGrabar">
                    <input name="TipoModificar" type="hidden" id="TipoModificar">
                    <input name="NumeroNCSeguimientos" type="hidden" id="NumeroNCSeguimientos">
                    <div class="col-sm-12" id="DivSeguimientos"></div>
                </form>
            </div>
            <div class="modal-footer" id="BotsSeguimientos">
                <button type="button" class="btn btn-secondary" onClick="AgregarSeguimiento();"><i class="bi bi-plus-lg"></i> Otro Seguimiento</button>
                <div style="flex:1"></div>
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="bi bi-x-lg"></i> Cerrar</button>
                <button type="button" class="btn btn-success" onClick="GrabarSeguimientos();"><i class="bi bi-check2"></i> Aceptar</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL CERRAR -->
<div class="modal fade" id="ModalCerrarNoConformidad" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document" style="width:98%">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalCerrarNoConformidadTitle"><i class="bi bi-check2-circle"></i> Cierre del Registro de No Conformidad</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body row" id="DivBodyCerrarNoConformidad">
                <form method="post" enctype="multipart/form-data" class="form_validar" name="FrmCerrarNoConformidad" id="FrmCerrarNoConformidad" autocomplete="off">
                    <input name="TipoGrabar" type="hidden" id="TipoGrabar">
                    <input name="TipoModificar" type="hidden" id="TipoModificar">
                    <input name="NumeroNCCerrar" type="hidden" id="NumeroNCCerrar">

                    <div class="col-sm-6 form-group">
                        <label for="ResultadoRevision">Resultados de la revisión de la eficacia</label>
                        <textarea name="ResultadoRevision" id="ResultadoRevision" class="form-control small" placeholder="Resultados de la revisión de la eficacia de las acciones tomadas" rows="3"></textarea>
                    </div>
                    <div class="col-sm-6 form-group">
                        <label for="MotivoCierre">Motivos del Cierre</label>
                        <textarea name="MotivoCierre" id="MotivoCierre" class="form-control small" placeholder="Motivos del Cierre" rows="3"></textarea>
                    </div>
                    <div class="col-sm-6 form-group">
                        <label for="NuevaAccion">Nueva Acción</label>
                        <textarea name="NuevaAccion" id="NuevaAccion" class="form-control small" placeholder="Nueva Acción?" rows="3"></textarea>
                    </div>
                    <div class="col-sm-2 form-group">
                        <label for="FCierre">Fecha del Cierre</label>
                        <input type="text" name="FCierre" id="FCierre" class="form-control" placeholder="dd/mm/aaaa" onChange="ValidarFecha(this)" onBlur="ValidarFecha(this)">
                    </div>
                    <div class="col-sm-4 form-group">
                        <label for="RCierre">Responsable del Cierre</label>
                        <input type="text" name="RCierre" id="RCierre" class="form-control">
                    </div>
                    <div class="clearfix"></div>
                </form>
            </div>
            <div class="modal-footer" id="BotsCerrarNoConformidad">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="bi bi-x-lg"></i> Cerrar</button>
                <button type="button" class="btn btn-success" onClick="GrabarCerrarNoConformidad();"><i class="bi bi-check2"></i> Aceptar</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL REPORTES -->
<div class="modal fade" id="ModalReportes" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document" style="width:98%;max-width:900px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalReportesTitle"><i class="bi bi-file-earmark-excel"></i> Consulta Reportes</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body row" id="ModalReportesBody">
                <form method="post" enctype="multipart/form-data" class="form_validar" name="FrmReportes" id="FrmReportes" autocomplete="off" target="_blank" style="width:100%">
                    <input name="TipoGrabar" type="hidden" id="TipoGrabar">
                    <input name="TipoModificar" type="hidden" id="TipoModificar">

                    <div class="nc-modal-center">
                        <div class="nc-box">
                            <div class="form-group">
                                <label for="TipoReporte">Tipo Reporte</label>
                                <select name="TipoReporte" class="form-control" id="TipoReporte" onChange="CambioTipoReporte(this);">
                                    <option value="">...</option>
                                    <option value="ReporteNoConformidades">Todos los registros</option>
                                </select>
                            </div>
                            <div class="form-group" id="DivFechasReporte">
                                <label for="ReportProceso">Proceso</label>
                                <select name="ReportProceso" id="ReportProceso" class="form-control">
                                    <option value="">Todos los procesos</option>
                                    <?php
                                    if ($PuedeAdministrar or $PuedeGeneral) {
                                        $QueriProc = "SELECT CodGestion, Colegio, CodProceso, NomProceso FROM calidad.procesos ORDER BY CodGestion, NomProceso";
                                    } else {
                                        $QueriProc = "SELECT DISTINCT CodGestion, Colegio, CodProceso, NomProceso FROM calidad.procesos WHERE FIND_IN_SET('" . $_SESSION["Usuario"] . "', DueProceso) ORDER BY CodGestion, NomProceso";
                                    }
                                    $ResultProc = $mysqli->query($QueriProc);
                                    while ($RowProc = $ResultProc->fetch_assoc()) {
                                        ?>
                                        <option value="<?php echo $RowProc['Colegio'] . $RowProc['CodProceso']; ?>" <?php if ($_GET['FiltroProceso'] == $RowProc['Colegio'] . $RowProc['CodProceso']) echo "selected"; ?>><?php echo $RowProc['CodGestion'] . ' ' . $RowProc['NomProceso']; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </form>
            </div>
            <div class="modal-footer" id="BotsReportes">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="bi bi-x-lg"></i> Cerrar</button>
                <button type="button" class="btn btn-success" id="BtnGenerarReporte" onClick="EnviarReportes();"><i class="bi bi-file-earmark-arrow-down"></i> Generar</button>
            </div>
        </div>
    </div>
</div>

</body>
</html>
<?php
mysqli_close($mysqli);
?>