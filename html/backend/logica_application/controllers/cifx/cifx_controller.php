<?php
/**
 * @file 
 * Codigo que implementa el controlador para la gestión de Prospectos
 * @brief BANDEJA DE LA INSTANCIA
 * @author JRAD
 * @date 2017
 * @copyright 2017 JRAD
 */
/**
 * Controlador para la gestión de Prospectos - BACKOFFICE
 * @brief BANDEJA DE LA INSTANCIA
 * @class Conf_catalogo_controller 
 */
class Cifx_controller extends CI_Controller {

    function __construct() {
        parent::__construct();
    }
    
    /**
     * carga la vista para el formulario de configuración
     * @brief CARGAR CONFIGURACIÓN
     * @author JRAD
     * @date 2017
     */
    
    public function CifradoConexionDB() {

        $this->lang->load('general', 'castellano');
        
        if(php_sapi_name() === 'cli')
        {
            try
            {
                $this->load->library('encrypt');
                $this->load->helper('file');

                // Definir la ruta y configuracion del Llavero

                $llavero = LLAVERO_RUTA;
                $llavero_split = LLAVERO_SPLIT;
                
                $archivo_llavero = $llavero . LLAVERO_LLAVERO;
                $archivo_llave = $llavero . LLAVERO_KEY;
                
                // Lectura del archivo 
                
                if(!$fh = @fopen($archivo_llavero, "r"))
                {
                    echo 'No fue posible abrir el archivo del llavero o no existe. Por favor revise los logs del sistema';
                    exit();
                }
                else
                {
                    $data_capturada = file_get_contents($archivo_llavero);
                    $data_capturada = explode($llavero_split, $data_capturada);

                    if(count($data_capturada) != 2)
                    {
                        echo 'El archivo llavero no esta correctamente formado. Por favor revise la guía de uso';
                        exit();
                    }
                    else
                    {
                        $user_recibido = $data_capturada[0];
                        $pass_recibido = $data_capturada[1];
                    }
                }
                
                // Cifrar usuario y Contraseña recibidas

                $user = $this->encrypt->encode($user_recibido);
                $pass = $this->encrypt->encode($pass_recibido);

                // Crear archivo txt

                $data = $user . '[:::]' . $pass;
                if ( ! write_file($archivo_llave, $data))
                {
                    echo 'No fue posible la generacion del cifrado en la ruta \'' . ($llavero == '' ? 'DocumentRoot' : $llavero) . '\'. Por favor revise los logs del sistema.';
                    exit();
                }
                else
                {
                    echo 'Cifrado procesado correctamente. Generado en la ruta \'' . ($llavero == '' ? '[DocumentRoot]' : $llavero) . '\'.';
                }
            }
            catch (Exception $e) 
            {
                js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                    Ocurrio un evento inesperado, intentelo mas tarde.</span>");
                exit();
            }
            
        }
        else
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
    }
}
?>