<?php
require '../app/core/app.php';
$app = new app();
?>

<div class="widget-box ui-sortable-handle collapsed" id="widget-box-1">
    <div class="widget-header">
        <h5 class="widget-title">Filtrar Registro</h5>

        <div class="widget-toolbar">
            <a href="#" data-action="collapse">
                <i class="ace-icon fa fa-chevron-down"></i>
            </a>
        </div>
    </div>

    <div class="widget-body" style="display: none;">
        <div class="widget-main">
            <p class="alert alert-info">
                Nunc aliquam enim ut arcu aliquet adipiscing. Fusce dignissim volutpat justo non consectetur. Nulla
                fringilla eleifend consectetur.
            </p>
            <p class="alert alert-success">
                Raw denim you probably haven't heard of them jean shorts Austin.
            </p>
        </div>
    </div>
</div>


<div class="widget-box ui-sortable-handle" id="widget-box-1">
    <div class="widget-header">
        <h5 class="widget-title"><?php echo $this->title; ?></h5>

        <div class="widget-toolbar">
            <a href="#" data-action="fullscreen" class="orange2">
                <i class="ace-icon fa fa-expand"></i>
            </a>
        </div>
    </div>

    <div class="widget-body">
        <div class="widget-main" id="listarRegistro">
            <?php include('listar.php'); ?>
        </div>
    </div>
</div>

