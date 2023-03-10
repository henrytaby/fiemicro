<script type="text/javascript">
  function camelCase(str) { 
    return str 
      .replace(/\s(.)/g, function(a) { 
        return a.toUpperCase(); 
      }) 
      .replace(/\s/g, '') 
      .replace(/^(.)/, function(b) { 
        return b.toLowerCase(); 
      }); 
  }
  function transformarFecha (fecha, formato) {
    var fechaFormateada = '';
    if (fecha && fecha.length > 2) {
      if (formato === 'date') {
        fechaFormateada = fecha.substring(0, 10).split('-');
        fechaFormateada = fechaFormateada[2]+ '/' +fechaFormateada[1]+'/' +fechaFormateada[0];
      } else {
        fechaFormateada = fecha.substring(11, 16);
      }
    }
    return fechaFormateada;
  }
  var globalFormio;
  var myStorage = window.localStorage;

  function keys (object) {
    var settings = {
      readonly: object.disabled,
      required: object.validate ? object.validate.required : false,
      label: object.label,
      description: object.tooltip,
      name: object.description ? object.description : camelCase(object.label),
      key: camelCase(object.label),
      catalogo: object.attributes && object.attributes.catalogo ? object.attributes.catalogo : null,
      parent_codigo: object.attributes && object.attributes.parent_codigo ? object.attributes.parent_codigo : null,
      parent_tipo: object.attributes && object.attributes.parent_tipo ? object.attributes.parent_tipo : null
    };
    switch (object.type) {
      case 'htmlelement':
        settings.type = 'header';
        settings.values = object.label;
        settings.align = object.customClass ? object.customClass : 'left'; 
        return settings;
      break;
      case 'textfield':
        settings.type = 'text';
        settings.placeholder = object.placeholder;
        settings.defaultValue = object.defaultValue;
        settings.values = object.defaultValue;
        settings.subtype = 'text';
        settings.maxlength = object.validate ? object.validate.maxLength : null;
        settings.minlength = object.validate ? object.validate.minLength : null;
        settings.className = object.customClass;
        return settings;
      break;
      case 'password':
        settings.type = 'text';
        settings.placeholder = object.placeholder;
        settings.defaultValue = object.defaultValue;
        settings.values = object.defaultValue;
        settings.subtype = 'password';
        settings.maxlength = object.validate ? object.validate.maxLength : null;
        settings.minlength = object.validate ? object.validate.minLength : null;
        settings.className = object.customClass;
        return settings;
      break;
      case 'email':
        settings.type = 'text';
        settings.placeholder = object.placeholder;
        settings.defaultValue = object.defaultValue;
        settings.values = object.defaultValue;
        settings.subtype = 'email';
        settings.maxlength = object.validate ? object.validate.maxLength : null;
        settings.minlength = object.validate ? object.validate.minLength : null;
        settings.className = object.customClass;
        return settings;
      break;
      case 'number':
        settings.type = 'number';
        settings.placeholder = object.placeholder;
        settings.defaultValue = object.defaultValue;
        settings.values = object.defaultValue;
        settings.max = object.validate ? object.validate.max : null;
        settings.min = object.validate ? object.validate.min : null;
        settings.className = object.customClass;
        return settings;
      break;
      case 'datetime':
        settings.type = 'date';
        settings.subtype = object.format === 'dd/MM/yyyy' ? 'date' : 'time';
        settings.placeholder = object.placeholder;
        settings.defaultValue = transformarFecha(object.defaultValue, object.format === 'dd/MM/yyyy' ? 'date' : 'time');
        settings.values = transformarFecha(object.defaultValue, object.format === 'dd/MM/yyyy' ? 'date' : 'time');
        return settings;
        break;
      case 'select':
        var optionsSelect = [];
        if (object.data && object.data.values) {
          for (var i = 0; i <= object.data.values.length - 1; i++) {
            object.data.values[i].selected = false;
            optionsSelect.push(object.data.values[i]);
          }
        }
        settings.type = 'select';
        settings.placeholder = object.placeholder;
        settings.values = optionsSelect;
        return settings;
        break;
        case 'radio':
          var formateandoValores = [];
          for (var i = 0; i < object.values.length; i++) {
            var objetoRadio = object.values[i];
            objetoRadio.selected = false;
            objetoRadio.shortcut = undefined;
            if (objetoRadio.value.toString() === object.defaultValue.toString()) {
              objetoRadio.selected = true;
            }
            formateandoValores.push(objetoRadio);
          }
          settings.values = formateandoValores;
          settings.type = 'radio-group';
          settings.description = object.tooltip;
          settings.defaultValue = object.defaultValue;
        return settings;
      break;
      case 'selectboxes':
        var encontrandoSelectBoxes = [];
        if (object.defaultValue) {
          for (var i = 0; i <= object.values.length - 1; i++) {
            var value = object.values[i].value.toString();
            object.values[i].shortcut = undefined;
            if (object.defaultValue[value]) {
              object.values[i].selected = true;
            } else {
              object.values[i].selected = false;
            }
            encontrandoSelectBoxes.push(object.values[i]);
          }
        }
        settings.type = 'checkbox-group';
        var stringDefault = [];
        for (var i = 0; i <= encontrandoSelectBoxes.length - 1; i++) {
          stringDefault.push(encontrandoSelectBoxes[i].value);
        }
        settings.values = encontrandoSelectBoxes;
        settings.defaultValue = '';
        return settings;
      break;
      case 'textarea':
        settings.type = 'textarea';
        settings.subtype = 'textarea';
        settings.placeholder = object.placeholder;
        settings.rows = object.rows;
        settings.values = object.defaultValue;
        settings.maxlength = object.validate ? object.validate.maxLength : null;
        settings.minlength = object.validate ? object.validate.minLength : null;
        settings.defaultValue = object.defaultValue;
        return settings;
      break;
      case 'file':
        settings.type = 'drawable';
        settings.values = null;
        // usando este atributo para limitar el tama??o el archivo
        settings.align = object.fileMaxSize; 
        // settings.defaultValue = object.defaultValue && object.defaultValue[0] && object.defaultValue[0].url ? object.defaultValue[0].url.replace(/:/gi, '___') : '';
        return settings;
      break;
      case 'address':
        settings.type = 'map';
        settings.values = '-16.5111185, -68.1328458 ';
        settings.defaultValue = '-16.5111185, -68.1328458';
        return settings;
      break;
    }
  }
  function generateJSON (data, json)  {
    var formateado = [];
    var components = [];
    for (var i = 0; i <= json.length - 1; i++) {
      if (json[i].type !== 'button') {
        components.push(json[i]);
      }
    }
    for (var i = 0; i <= components.length - 1; i++) {
      formateado.push(keys(components[i]));
    }

    var sendData = {
      form_detalle: 'Formulario A',
      tipo_bandeja: 1,
      lista_elementos: formateado 
    }
    return sendData;
  }
  <?php echo $strValidacionJqValidate; ?>
    Elementos_Habilitar_ObjetoARefComoSubmit("btnGuardarFormulario", "FormularioCampos");
    Ajax_DarActualizarValidacionEnvioAjaxSegmentoForm("FormularioCampos", 'Formulario/Nuevo/Guardar', 'divVistaMenuPantalla', 'divErrorListaResultado');

    $("#divCargarFormulario").show();    
    $("#confirmacion").hide();

    function formatearVista () {
      var containerFormio = document.getElementById('formio');
      var botonActivar = document.getElementById('botonMobileFormulario');
      if (containerFormio.classList.contains('formatearVista')) {
        containerFormio.classList.remove('formatearVista');
        botonActivar.classList.remove('addColor');
        document.getElementsByClassName('drag-container')[0].classList.remove('addDragWidth');
      } else {
        containerFormio.classList.add('formatearVista');
        botonActivar.classList.add('addColor');
        document.getElementsByClassName('drag-container')[0].classList.add('addDragWidth');
      }
    }
    function MostrarConfirmaci??n()
    {
        $("#divCargarFormulario").hide();
        $("#confirmacion").fadeIn(500);

        var valor = $('#nombreForm').val();
        if (!valor) {
          OcultarConfirmaci??n();
          alert('El nombre del formulario no tiene que estar vacio');
          return;
        }

        globalFormio.then(function (res) {
          var components = [];
          for (var i = 0; i <= res.components.length - 1; i++) {
            components.push(res.components[i].component);
          }
          $('#json_stringify_formio').val(JSON.stringify(components));
          var data = generateJSON([], components);
          if (data.lista_elementos.length === 0) {
            OcultarConfirmaci??n();
            alert('El formulario que desea guardar no tiene campos...');
            return;
          }

          var sw = false;
          var swInterno = false;

          for (var i = 0; i <= data.lista_elementos.length; i++) {
            if (data.lista_elementos[i] && data.lista_elementos[i].type === 'radio-group') {
              for (var j = 0; j < data.lista_elementos[i].values.length; j++) {
                if (data.lista_elementos[i].values[j].label === '') {
                  swInterno = true;
                  break;
                }
              }
              if (swInterno) {
                sw = true;
                break;
              }
            }
            if (data.lista_elementos[i] && data.lista_elementos[i].type === 'checkbox-group') {
              for (var j = 0; j < data.lista_elementos[i].values.length; j++) {
                if (data.lista_elementos[i].values[j].label === '') {
                  swInterno = true;
                  break;
                }
              }
              if (swInterno) {
                sw = true;
                break;
              }
            }
            if (data.lista_elementos[i] && data.lista_elementos[i].type === 'select') {
              if (data.lista_elementos[i].values[0].label === '' && data.lista_elementos[i].catalogo === null) {
                sw = true;
                break;
              }
            }
          }

          if (sw) {
            OcultarConfirmaci??n();
            alert('Error existen componentes de tipo de radio-group, checkbox-group o select que no tiene valores');
            return;
          }

          data.formulario = {
            nombre: $('#nombreForm').val(),
            descripcion: $('#descripcionForm').val(),
            tipo_bandeja: $('#tipo_bandeja').val(),
            publicado: false
          }
          console.log('====================_MENSAJE_A_MOSTRARSE_====================');
          console.log(data);
          console.log('====================_MENSAJE_A_MOSTRARSE_====================');
          $('#json_stringify_formatted').val(JSON.stringify(data));
        })
    }
    
    function OcultarConfirmaci??n()
    {
        $("#divCargarFormulario").fadeIn(500);    
        $("#confirmacion").hide();
    }
    
    $("#conf_correo_smtp_pass").attr("type", "password");
    
    function MostrarOcultarPass()
    {
        if ($("#conf_correo_smtp_pass").attr("type") == "password") {
            $("#conf_correo_smtp_pass").attr("type", "text");
        } else {
            $("#conf_correo_smtp_pass").attr("type", "password");
        }
    }

    var espaniol = {
      april: 'Abril',
      august: 'Agosto',
      december: 'Diciembre',
      february: 'Febrero',
      january: 'Enero',
      july: 'Julio',
      june: 'Junio',
      march: 'Marzo',
      may: 'Mayo',
      november: 'Noviembre',
      day: 'Dia',
      october: 'Octubre',
      september: 'Septiembre',
      month: 'Mes',
      year: 'A??o',
      next: 'Siguiente',
      previous: 'Anterior',
      invalid_date: '{{field}} no es una fecha v??lida.',
      invalid_day: '{{field}} no es un d??a valido.',
      Submit: 'Enviar',
      Language: 'Idioma',
      Translations: 'Traducciones',
      'First Name': 'Tu nombre',
      'Last Name': 'Apellido',
      'HTML Element Component': 'T??tulo',
      'Text Field Component': 'Campo de texto',
      'Password Component': 'Contrase??a',
      'Email Component': 'Correo electr??nico',
      'Text Area Component': 'Textarea',
      'Number Component': 'Campo num??rico',
      'Date / Time Component': 'Fecha / Hora',
      'Radio Component': 'Opciones',
      'Select Component': 'Lista desplegable',
      'Select Boxes Component': 'Selecci??n M??ltiple',
      'File Component': 'Firma Electr??nica',
      'Maps Component': 'Mapa',
      'Drop files to attach': 'Agarra y suelta aqu?? la firma',
      'No choices to choose from': 'Lista vacia...',
      complete: 'Formulario correctamente configurado...',
      'Data Source Values': 'Valores',
      'Add Another': 'Agregar mas',
      'Phone Number': 'Numero telef??nico',
      Description: 'Nombre',
      'Description for this field.': 'Nombre para este campo',
      Save: 'Guardar',
      Remove: 'Borrar',
      Cancel: 'Cancelar',
      Format: 'Formato',
      Values: 'Valores',
      'Minimum Value': 'Min',
      'Maximum Value': 'Max',
      Required: '??Este campo es requerido?',
      'Minimum Date': 'Fecha m??nima',
      'Maximum Date': 'Fecha m??xima',
      'Minimum Length': 'Longitud m??nima',
      'Maximum Length': 'Longitud m??xima',
      'Default Value': 'Valor por defecto',
      ClassName: 'Clase',
      Disabled: '??Este campo estara deshabilitado?',
      Preview: 'Vista previa',
      Help: 'Ayuda',
      Display: 'Vista',
      Data: 'Datos',
      Rows: 'Filas',
      Validation: 'Validaciones',
      API: 'API',
      Tooltip: 'Descripci??n del campo',
      Conditional: 'Condicionales',
      Logic: 'Logica',
      Layout: 'Cat??logo',
      Date: 'Fecha',
      Time: 'Hora',
      Content: 'Contenido',
      minLength: 'Faltan caracteres para {{field}}',
      maxLength: '{{field}} excede la cantidad permitida de caracteres',
      'CSS Class': 'Adicionar una clase css / Alineaci??n',
      'Custom CSS Class':  'Adicionar una clase css / Alineaci??n',
      'To add a tooltip to this field, enter text here.': 'Desea agregar alguna descripci??n',
      'Enter your email address': 'Ingrese su direcci??n de correo electr??nico',
      'Enter your first name': 'Ponga su primer nombre',
      'Drag and Drop a form component': 'Arrastra y suelta en esta secci??n un campo de formulario',
      'Enter your last name': 'Ingresa tu apellido',
      'Valid Email Address': 'direcci??n de email v??lida',
      'Please correct all errors before submitting.': 'Por favor, corrija todos los errores antes de enviar.',
      required: '{{field}} es requerido.',
      invalid_email: '{{field}} debe ser un correo electr??nico v??lido.',
      error: 'Por favor, corrija los siguientes errores antes de enviar.',
      'Attribute Name': 'Atributo del catalogo',
      'Attribute Value': 'Valor del catalogo',
      'Add Attribute': 'Adicionar mas atributos',
      'Location Component': 'Ubicaci??n',
      'Address Field Component': 'Ubicaci??n',
      'File Minimum Size': 'Tama??o m??nimo que puede pesar el archivo',
      'File Maximum Size': 'Tama??o m??ximo que puede pesar el archivo',
      'File is too big; it must be at most 1MB': 'Archivo demasiado grande, adjunte un archivo de menor tama??o',
      'File is too big; it must be at most 2MB': 'Archivo demasiado grande, adjunte un archivo de menor tama??o',
      'File is too big; it must be at most 3MB': 'Archivo demasiado grande, adjunte un archivo de menor tama??o',
      'File is too big; it must be at most 4MB': 'Archivo demasiado grande, adjunte un archivo de menor tama??o',
      'File is too big; it must be at most 5MB': 'Archivo demasiado grande, adjunte un archivo de menor tama??o',
      'File is too big; it must be at most 6MB': 'Archivo demasiado grande, adjunte un archivo de menor tama??o',
      'File is too big; it must be at most 7MB': 'Archivo demasiado grande, adjunte un archivo de menor tama??o',
      'File is too big; it must be at most 8MB': 'Archivo demasiado grande, adjunte un archivo de menor tama??o'
    };

    // Configuracion del formio

      globalFormio = Formio.builder(document.getElementById('formio'), {
        components: []
      }, {
        reandOnly: false,
        language: 'es',
        i18n: {
          'es': espaniol
        },
        builder: {
          basic: false,
          advanced: false,
          data: false,
          layout: false,
          custom: {
            title: 'Componentes',
            weight: 0,
            components: {
              header: {
                title: 'T??tulo',
                key: 'titulo',
                schema: {
                  label: 'T??tulo',
                  type: 'htmlelement',
                  key: 'titulo',
                  content: '<h1>T??tulo</h1>',
                  customClass: 'left'
                }
              },
              textField: {
                title: 'Campo de texto',
                key: 'campo-texto',
                schema: {
                  label: 'Campo de texto',
                  type: 'textfield',
                  key: 'campo-texto',
                  placeholder: 'Ingrese texto',
                  defaultValue: '',
                  attributes: {
                    id: 'uno'
                  }
                }
              },
              password: {
                title: 'Contrase??a',
                key: 'contrasenia',
                schema: {
                  label: 'Contrase??a',
                  type: 'password',
                  key: 'contrasenia',
                  placeholder: 'Ingrese contrase??a',
                  defaultValue: '',
                  attributes: {
                    id: 'dos'
                  }
                }
              },
              email: {
                title: 'Correo electr??nico',
                key: 'email',
                schema: {
                  label: 'Correo electr??nico',
                  type: 'email',
                  key: 'email',
                  placeholder: 'Ingrese un correo electr??nico',
                  defaultValue: '',
                  attributes: {
                    id: 'tres'
                  }
                }
              },
              textArea: {
                title: 'Textarea',
                key: 'parrafo',
                schema: {
                  label: 'Textarea',
                  type: 'textarea',
                  key: 'parrafo',
                  placeholder: 'Ingrese algun texto',
                  defaultValue: ''
                }
              },
              number: {
                title: 'Num??rico',
                key: 'numerico',
                schema: {
                  label: 'Campo num??rico',
                  type: 'number',
                  key: 'numerico',
                  placeholder: 'Valor num??rico',
                  defaultValue: 0
                }
              },
              dateField: {
                title: 'Fecha',
                key: 'fecha',
                schema: {
                  label: 'Fecha',
                  type: 'datetime',
                  key: 'fecha',
                  format: 'dd/MM/yyyy',
                  enableTime: false,
                  placeholder: 'Ingrese una fecha',
                  defaultValue: ''
                }
              },
              timeField: {
                title: 'Hora',
                key: 'hora',
                schema: {
                  label: 'Hora',
                  type: 'datetime',
                  key: 'hora',
                  format: 'hh:mm a',
                  enableDate: false,
                  placeholder: 'Ingrese una hora',
                  defaultValue: ''
                }
              },
              select: {
                title: 'Lista desplegable',
                key: 'select',
                schema: {
                  label: 'Lista opciones',
                  type: 'select',
                  key: 'select',
                  placeholder: 'Seleccione una opci??n',
                  searchEnabled: false,
                  limit: 1000,
                  defaultValue: '',
                  attributes: {
                    catalogo: '',
                    parent_codigo: '',
                    parent_tipo: ''
                  }
                }
              },
              radio: {
                title: 'Opciones',
                key: 'radio',
                schema: {
                  label: 'Opciones excluyentes',
                  type: 'radio',
                  key: 'radio',
                  defaultValue: ''
                }
              },
              selectboxes: {
                title: 'Selecci??n m??ltiple',
                key: 'seleccion-multiple',
                schema: {
                  label: 'Selecci??n m??ltiple',
                  type: 'selectboxes',
                  key: 'seleccion-multiple',
                  defaultValue: ''
                }
              },
              firmaElectronica: {
                title: 'Firma electr??nica',
                key: 'firma-electronica',
                schema: {
                  label: 'firma electr??nica',
                  type: 'file',
                  key: 'firma-electronica',
                  defaultValue: '',
                  image: true,
                  storage: 'base64',
                  webcam: false,
                  imageSize: '5MB',
                  fileMaxSize: '1MB',
                  fileTypes: [
                    {
                      label: '',
                      value: ''
                    }
                  ]
                }
              },
              mapa: {
                title: 'Mapa',
                key: 'map',
                schema: {
                  label: 'Mapa',
                  type: 'address',
                  key: 'mapa',
                  defaultValue: ''
                }
              }
            }
          }
        },
        editForm: {
          textfield: [
            {
              key: 'display',
              ignore: false
            },
            {
              key: 'api',
              ignore: true
            },
            {
              key: 'conditional',
              ignore: true
            },
            {
              key: 'logic',
              ignore: true
            },
            {
              key: 'layout',
              ignore: true
            }
          ],
          password: [
            {
              key: 'api',
              ignore: true
            },
            {
              key: 'conditional',
              ignore: true
            },
            {
              key: 'logic',
              ignore: true
            },
            {
              key: 'layout',
              ignore: true
            }
          ],
          email: [
            {
              key: 'api',
              ignore: true
            },
            {
              key: 'conditional',
              ignore: true
            },
            {
              key: 'logic',
              ignore: true
            },
            {
              key: 'layout',
              ignore: true
            }
          ],
          textarea: [
            {
              key: 'api',
              ignore: true
            },
            {
              key: 'conditional',
              ignore: true
            },
            {
              key: 'logic',
              ignore: true
            },
            {
              key: 'layout',
              ignore: true
            }
          ],
          htmlelement: [
            {
              key: 'api',
              ignore: true
            },
            {
              key: 'validation',
              ignore: true
            },
            {
              key: 'data',
              ignore: true
            },
            {
              key: 'conditional',
              ignore: true
            },
            {
              key: 'logic',
              ignore: true
            },
            {
              key: 'layout',
              ignore: true
            }
          ],
          number: [
            {
              key: 'api',
              ignore: true
            },
            {
              key: 'conditional',
              ignore: true
            },
            {
              key: 'logic',
              ignore: true
            },
            {
              key: 'layout',
              ignore: true
            }
          ],
          datetime: [
            {
              key: 'api',
              ignore: true
            },
            {
              key: 'conditional',
              ignore: true
            },
            {
              key: 'logic',
              ignore: true
            },
            {
              key: 'layout',
              ignore: true
            },
            {
              key: 'date',
              ignore: true
            },
            {
              key: 'time',
              ignore: true
            }
          ],
          radio: [
            {
              key: 'api',
              ignore: true
            },
            {
              key: 'conditional',
              ignore: true
            },
            {
              key: 'logic',
              ignore: true
            },
            {
              key: 'layout',
              ignore: true
            }
          ],
          selectboxes: [
            {
              key: 'api',
              ignore: true
            },
            {
              key: 'conditional',
              ignore: true
            },
            {
              key: 'logic',
              ignore: true
            },
            {
              key: 'layout',
              ignore: true
            }
          ],
          file: [
            {
              key: 'api',
              ignore: true
            },
            {
              key: 'conditional',
              ignore: true
            },
            {
              key: 'logic',
              ignore: true
            },
            {
              key: 'layout',
              ignore: true
            },
            {
              key: 'file',
              ignore: false
            }
          ],
          button: [
            {
              key: 'api',
              ignore: true
            },
            {
              key: 'conditional',
              ignore: true
            },
            {
              key: 'logic',
              ignore: true
            },
            {
              key: 'layout',
              ignore: true
            }
          ],
          select: [
            {
              key: 'api',
              ignore: true
            },
            {
              key: 'conditional',
              ignore: true
            },
            {
              key: 'logic',
              ignore: true
            },
            {
              key: 'layout',
              ignore: false
            }
          ],
          address: [
            {
              key: 'api',
              ignore: true
            },
            {
              key: 'data',
              ignore: true
            },
            {
              key: 'map',
              ignore: true
            },
            {
              key: 'conditional',
              ignore: true
            },
            {
              key: 'validation',
              ignore: false
            },
            {
              key: 'logic',
              ignore: true
            },
            {
              key: 'layout',
              ignore: true
            }
          ]
        }
      })

    function Iconos () {
      document.getElementsByClassName('drag-container')[0].style.minHeight = '60vh';
      if (window.innerWidth < 600) {
        document.getElementById('group-container-custom').style.width = '48px';

        document.getElementById('builder-titulo').style.textAlign = 'center';
        document.getElementById('builder-titulo').innerHTML = '<i class="fa fa-header" style="font-size:20px" aria-hidden="true"></i>';

        document.getElementById('builder-campo-texto').style.textAlign = 'center';
        document.getElementById('builder-campo-texto').innerHTML = '<i class="fa fa-keyboard-o" style="font-size:20px" aria-hidden="true"></i>';

        document.getElementById('builder-contrasenia').style.textAlign = 'center';
        document.getElementById('builder-contrasenia').innerHTML = '<i class="fa fa-key" style="font-size:20px" aria-hidden="true"></i>';

        document.getElementById('builder-email').style.textAlign = 'center';
        document.getElementById('builder-email').innerHTML = '<i class="fa fa-envelope-o" style="font-size:20px" aria-hidden="true"></i>';

        document.getElementById('builder-parrafo').style.textAlign = 'center';
        document.getElementById('builder-parrafo').innerHTML = '<i class="fa fa-comments-o" style="font-size:20px" aria-hidden="true"></i>';

        document.getElementById('builder-numerico').style.textAlign = 'center';
        document.getElementById('builder-numerico').innerHTML = '<i class="fa fa-sort-numeric-asc" style="font-size:20px" aria-hidden="true"></i>';

        document.getElementById('builder-fecha').style.textAlign = 'center';
        document.getElementById('builder-fecha').innerHTML = '<i class="fa fa-calendar" style="font-size:20px" aria-hidden="true"></i>';

        document.getElementById('builder-hora').style.textAlign = 'center';
        document.getElementById('builder-hora').innerHTML = '<i class="fa fa-clock-o" style="font-size:20px" aria-hidden="true"></i>';

        document.getElementById('builder-select').style.textAlign = 'center';
        document.getElementById('builder-select').innerHTML = '<i class="fa fa-list" style="font-size:20px" aria-hidden="true"></i>';

        document.getElementById('builder-radio').style.textAlign = 'center';
        document.getElementById('builder-radio').innerHTML = '<i class="fa fa-bullseye" style="font-size:20px" aria-hidden="true"></i>';

        document.getElementById('builder-seleccion-multiple').style.textAlign = 'center';
        document.getElementById('builder-seleccion-multiple').innerHTML = '<i class="fa fa-check-square-o" style="font-size:20px" aria-hidden="true"></i>';

        document.getElementById('builder-firma-electronica').style.textAlign = 'center';
        document.getElementById('builder-firma-electronica').innerHTML = '<i class="fa fa-pencil" style="font-size:20px" aria-hidden="true"></i>';

        document.getElementById('builder-firma-electronica').nextSibling.style.textAlign = 'center';
        document.getElementById('builder-firma-electronica').nextSibling.innerHTML = '<i class="fa fa-map-marker" style="font-size:20px" aria-hidden="true"></i>';
      }
    }
    var isIE = /*@cc_on!@*/false || !!document.documentMode;
    setTimeout(function () {
      var button = document.querySelector('#group-panel-custom').getElementsByTagName('button');
      button[0].click();
      Iconos();
    }, 1000);
</script>
<div id="botonMobileFormulario" onclick="formatearVista();">
  <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
</div>
<div id="divVistaMenuPantalla" align="center">
    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br /><br />

        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_initium.png) no-repeat; background-size: contain; background-position: center;"> </div>

            <div class="FormularioSubtitulo"> <?php echo $this->lang->line('FormularioDinamicoTitulo'); ?></div>
            <div class="FormularioSubtituloComentarioNormal "><?php echo $this->lang->line('FormularioDinamicoSubtitulo'); ?></div>
        
        <div style="clear: both">
        </div>
        <div class="container">
          <div class="row">
            <div class="col-md-4 col-xs-12">
              <div class="form-group">
                <label for="nombreForm">Nombre del formulario</label>
                <input type="email" class="form-control" id="nombreForm" aria-describedby="emailHelp" placeholder="Nombre del Formulario">
              </div>
            </div>
            <div class="col-md-4 col-xs-12">
              <div class="form-group">
                <label for="descripcionForm">Descripcion</label>
                <input type="email" class="form-control" id="descripcionForm" aria-describedby="emailHelp" placeholder="Descripcion del Formulario">
              </div>
            </div>
            <?php
              $arrTipoBandeja[] = array(
                "codigo" => "1",
                "valor" => "Prospectos"
              );

              $arrTipoBandeja[] = array(
                "codigo" => "2",
                "valor" => "Mantenimientos"
              );

              echo '<div class="col-md-4 col-xs-12">';
              echo "<span><strong>Seleccione el Tipo de Bandeja:</strong></span>";
              echo html_select('tipo_bandeja', $arrTipoBandeja, 'codigo', 'valor', 'SINSELECCIONAR', '');
              echo "</div><br />";

            ?>
          </div>
        </div>
        <br />
        <div id='formio'></div>
        <form id="FormularioCampos" method="post">
          <input type="hidden" name="json_stringify_formatted" id="json_stringify_formatted" value="" />
          <input type="hidden" name="json_stringify_formio" id="json_stringify_formio" value="" />
        </form>

        <br /><br /><br />

        <div class="Botones2Opciones">
            <a onclick="Ajax_CargarOpcionMenu('Formularios/Ver');" class="BotonMinimalista"> <?php echo $this->lang->line('BotonCancelar'); ?> </a>
        </div>

        <div class="Botones2Opciones">
            <a onclick="MostrarConfirmaci??n();" class="BotonMinimalista"> <?php echo $this->lang->line('BotonAceptar'); ?> </a>
        </div>
        
        <div style="clear: both"></div>

    </div>
    
    <div id="confirmacion" class="PreguntaConfirmacion TamanoContenidoGeneral">

        <div class="FormularioSubtituloImagenPregunta"> </div>

            <div class="PreguntaTitulo"> <?php echo $this->lang->line('PreguntaTitulo'); ?></div>
            <div class="PreguntaTexto "><?php echo $this->lang->line('conf_formulario_insertar'); ?></div>

            <div style="clear: both"></div>

            <br />

        <div class="PreguntaConfirmar">
            <?php echo $this->lang->line('PreguntaContinuar'); ?>
        </div>

        <div class="Botones2Opciones">
            <a onclick="OcultarConfirmaci??n();" class="BotonMinimalista"> <?php echo $this->lang->line('BotonCancelar'); ?> </a>
        </div>

        <div class="Botones2Opciones">
            <a id="btnGuardarFormulario" class="BotonMinimalista"> <?php echo $this->lang->line('BotonAceptar'); ?> </a>
        </div>

        <div style="clear: both"></div>

		<br />

        <?php if (isset($respuesta)) { ?>
            <div class="mensajeBD"> 
                <div style="padding: 15px;">
                    <?php echo $respuesta ?>
                </div>
            </div>
        <?php } ?>

        <div id="divErrorListaResultado" class="mensajeBD"> </div>

    </div>
</div>