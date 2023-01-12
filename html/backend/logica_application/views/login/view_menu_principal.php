<?php
/**
 * @file 
 * Banner Arriba Pantalla  
 * @author JRAD
 * @date Mar 24, 2015
 * @copyright OWNER
 */
?> 

<div id="divVistaMenuPrincipal" >
    
    <script type="text/javascript">
    
        function MenuEfecto() {
            
            $('#slider_menu').toggleClass('control-sidebar-open');
        }

    </script>
    
    <?php if (isset($_SESSION["session_informacion"])) { ?>              
        <div class="MenuBarraPrinc">
            <div class="FondoBannerImagen_opciones" onclick="MenuEfecto();"> </div>
            
            <div class="FondoBannerImagen_texto"> 
            
                <?php echo $this->lang->line('NombreSistema');?>
                
            </div>
            
        </div>
    
        <aside id="slider_menu" class="control-sidebar" >

		<div class="DatosLogin_movil">                    
                    
                    <!-- Datos del Usuario-->
                    <i class="fa fa-user-circle-o" aria-hidden="true" style="display: inline !important;"></i> <?php echo $this->lang->line('LoginBienvenida');?> <?php echo $_SESSION["session_informacion"]["nombre"]; ?>
                    <br />
                    <i class="fa fa-users" aria-hidden="true" style="display: inline !important;"></i> Rol: <?php echo $_SESSION["session_informacion"]["rol"]; ?>
                    
                    <hr>
                    <!-- Opciones del Usuario -->
                    
                    <?php
                    
                    // Validación auxiliar sobre la renovación del password y AD activado
                    if($_SESSION["session_informacion"]["ad_active"] == 1)
                    {
                        $_SESSION["session_informacion"]["password_reset"] = 0;
                    }
                    
                    $sw_auxiliar = 0;
                    
                    if($_SESSION["session_informacion"]["codigo"] == 1 || $_SESSION["session_informacion"]["login"] == 'rpa.onboarding')
                    {
                        $sw_auxiliar = 1;
                    }
                    
                    
                    if($_SESSION["session_informacion"]["dias_cambio_password"] == 0 && $sw_auxiliar != 1 && $_SESSION["session_informacion"]["ad_active"] == 0)
                    {
                        echo $this->lang->line('RequiereRenovarPass');
                    }
                    elseif($_SESSION["session_informacion"]["password_reset"] == 1 && $sw_auxiliar != 1)
                    {
                        echo "ESTIMADO USUARIO, PARA UTILIZAR EL SISTEMA POR FAVOR DEBE RENOVAR SU CONTRASEÑA.";
                    }
                    else
                    {
                    ?>                    
                        <a onclick="Ajax_CargarOpcionMenu('Menu/Principal');"> <?php echo $this->lang->line('MenuPrincipal');?> </a>

                        <?php

                            if(isset($arrRespuesta[0]))
                            {
                                
                                $arrMenu = $this->mfunciones_generales->ArrGroupBy($arrRespuesta, 'menu_orden');
                                
                                foreach ($arrMenu as $key => $values) 
                                {
                                    echo "<br />";
                                    
                                    $menu_header = '';
                                    
                                    switch ($key) {
                                        case 0:

                                            $menu_header = 'Administración y Configuración <i class="fa fa-cogs" aria-hidden="true"></i>';
                                            
                                            break;
                                        
                                        case 1:

                                            $menu_header = 'Módulos de Parametrización  <i class="fa fa-pencil-square-o" aria-hidden="true"></i>';
                                            
                                            break;

                                        case 2:

                                            $menu_header = 'Módulos de Lógica de Negocio <i class="fa fa-lightbulb-o" aria-hidden="true"></i>';
                                            
                                            break;
                                        
                                        case 3:

                                            $menu_header = 'Consultas, Seguimiento y Reportería <i class="fa fa-line-chart" aria-hidden="true"></i>';
                                            
                                            break;
                                        
                                        case 4:

                                            $menu_header = 'Onboarding Digital <i class="fa fa-user-circle" aria-hidden="true"></i>';
                                            
                                            break;
                                        
                                        default:
                                            break;
                                    }
                                    
                                    echo "<span style='font-style: italic; font-size: 0.9em;'>" . $menu_header . " &nbsp;&nbsp; </span>";
                                    
                                    foreach ($values as $value) 
                                    {
                                        echo "<br /> <a onclick=\"Ajax_CargarOpcionMenu('" . $value["menu_ruta"] . "');\"> " . $value["menu_nombre"] . " </a>";
                                    }
                                }
                            }
                            
                        ?>
                    
                    <?php                    
                    }                    
                    ?>
                        
                    <hr>
                    <!-- Opciones de la cuenta -->
                    <?php if($_SESSION["session_informacion"]["ad_active"] == 0){?>
                        <a onclick="Ajax_CargarOpcionMenu('Usuario/Cambiar/Pass');"> <?php echo $this->lang->line('CambiarPass');?> 
                        <i class="fa fa-key" aria-hidden="true"></i></a>
                    <?php }?>
                    <br /><a onclick="Ajax_CerrarLogin();"> <i class="fa fa-sign-out" aria-hidden="true" style="display: inline !important;"></i> <?php echo $this->lang->line('LoginSalir');?></a>

                </div>

	</aside>
    
    </div>
<?php } ?> 

