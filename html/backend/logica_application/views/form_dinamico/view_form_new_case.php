<?php
  // $componentes = '[{"catalogo":null,"description":"","key":"título","label":"Este es el titulo","name":"título","parent_codigo":null,"parent_tipo":null,"readonly":false,"required":false,"type":"header","values":"Este es el titulo"},{"catalogo":null,"defaultValue":"Valor por defecto","description":"esta es una descripcion para este campo","key":"esteEsElCampoDeTexto","label":"Este es el campo de texto","maxlength":100,"minlength":10,"name":"data_nombre_completo","parent_codigo":null,"parent_tipo":null,"placeholder":"Campo placeholder","readonly":true,"required":true,"subtype":"text","type":"text","values":"Valor por defecto","className":""},{"catalogo":null,"defaultValue":"valor por defecto","description":"","key":"estaEsLaContraseña","label":"Esta es la contraseña","maxlength":"","minlength":"","name":"estaEsLaContraseña","parent_codigo":null,"parent_tipo":null,"placeholder":"Ingrese contraseña","readonly":false,"required":true,"subtype":"password","type":"text","values":"valor por defecto","className":""},{"catalogo":null,"defaultValue":"","description":"","key":"correoElectrónico","label":"Correo electrónico","maxlength":"","minlength":"","name":"correoElectrónico","parent_codigo":null,"parent_tipo":null,"placeholder":"Ingrese un correo electrónico","readonly":false,"required":false,"subtype":"email","type":"text","values":"","className":""},{"catalogo":null,"defaultValue":"Este es un valor por defecto en el textarea","description":"","key":"textarea","label":"Textarea","maxlength":100,"minlength":"","name":"textarea","parent_codigo":null,"parent_tipo":null,"placeholder":"Ingrese algun texto","readonly":false,"required":true,"rows":3,"subtype":"textarea","type":"textarea","values":"Este es un valor por defecto en el textarea","className":""},{"catalogo":null,"defaultValue":0,"description":"","key":"campoNumérico","label":"Campo numérico","max":100,"min":10,"name":"campoNumérico","parent_codigo":null,"parent_tipo":null,"placeholder":"Valor numérico","readonly":false,"required":false,"type":"number","values":0,"step":1},{"catalogo":null,"defaultValue":"11/09/2019","description":"","key":"campoFecha","label":"Campo fecha","name":"campoFecha","parent_codigo":null,"parent_tipo":null,"placeholder":"Ingrese una fecha","readonly":false,"required":true,"subtype":"date","type":"date","values":"11/09/2019"},{"catalogo":null,"defaultValue":"14:00","description":"","key":"hora","label":"Hora","name":"hora","parent_codigo":null,"parent_tipo":null,"placeholder":"Ingrese una hora","readonly":false,"required":true,"subtype":"time","type":"date","values":"14:00"},{"catalogo":null,"description":"","key":"listaDeDepartamentos","label":"Lista de departamentos","name":"listaDeDepartamentos","parent_codigo":null,"parent_tipo":null,"placeholder":"Seleccione una opción","readonly":false,"required":true,"type":"select","values":[{"label":"La Paz","value":"1","selected":false},{"label":"Oruro","value":"2","selected":true},{"label":"Santa Cruz","value":"3","selected":false}]},{"catalogo":null,"defaultValue":2,"description":"","key":"radio","label":"Radio","name":"radio","parent_codigo":null,"parent_tipo":null,"readonly":false,"required":true,"type":"radio-group","values":[{"label":"Adrian","value":"1","selected":false},{"label":"Diego","value":"2","selected":true},{"label":"Ivan","value":"3","selected":false}]},{"catalogo":null,"defaultValue":"{1:false,2:true,3:true}","description":"","key":"checks","label":"Checks","name":"checks","parent_codigo":null,"parent_tipo":null,"readonly":false,"required":true,"type":"checkbox-group","values":[{"label":"Adrian","value":"1","selected":false},{"label":"Diego","value":"2","selected":true},{"label":"Max","value":"3","selected":true}]},{"catalogo":null,"description":"","key":"firmaElectrónica","label":"firma electrónica","name":"firmaElectrónica","parent_codigo":null,"parent_tipo":null,"readonly":false,"required":true,"type":"drawable","values":null},{"catalogo":null,"defaultValue":null,"description":"","key":"mapa","label":"Mapa","name":"mapa","parent_codigo":null,"parent_tipo":null,"readonly":false,"required":false,"type":"map","values":null}]';
  echo '<script type="text/javascript">
    var COMPONENTES = '.json_encode($componentes).';
  </script>';
?>
<script type="text/javascript">
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
function validateEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}
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
  var globalFormio;
  var myStorage = window.localStorage;

  function reverse (object) {
    var settings = {
      disabled: object.readonly === "true" || object.readonly === true || object.readonly === '1'  ? true : false,
      validate: {
        required: object.required && object.required === '1' ? true : false
      },
      label: object.label ? object.label : '',
      tooltip: object.description ? object.description : '',
      attributes: {
        name: object.name ? object.name : '',
        align: object.align ? object.align : '',
        catalogo: object.catalogo ? object.catalogo : null,
        parent_codigo: object.parent_codigo ? object.parent_codigo : null,
        parent_tipo: object.parent_tipo ? object.parent_tipo : null
      },
      idComponente: object.id,
      key: camelCase(object.name),
      description: object.name,
      placeholder: object.placeholder ? object.placeholder : '',
      defaultValue: object.defaultValue ? object.defaultValue : '',
      customClass: object.className ? object.className : ''
    };
    switch (object.type) {
      case 'header':
          settings.type = 'htmlelement';
          settings.content = "<h1>"+ object.label ? object.label : '' +"</h1>";
          return settings;
        break;
      case 'text':
        var tipo; 
        if (object.subtype === 'text') {
          tipo = 'textfield';
          settings.validate = {
            required: object.required && object.required === '1' ? true : false,
            maxLength: object.maxlength && object.maxlength.length > 0 ? object.maxlength : null,
            minLength: object.minlength && object.minlength.length > 0 ? object.minlength : null
          };
          } else {
          if (object.subtype === 'password') {
            tipo = 'password';
            settings.validate = {
              required: object.required && object.required === '1' ? true : false,
              maxLength: object.maxlength && object.maxlength.length > 0 ? object.maxlength : null,
              minLength: object.minlength && object.minlength.length > 0 ? object.minlength : null
            };
          } else {
            tipo = 'email';
            settings.validate = {
              required: object.required && object.required === '1' ? true : false
            };
          }
        }
        settings.type = tipo;
        return settings;
        break;
      case 'number':
        settings.type = 'number';
        settings.defaultValue = isNaN(object.defaultValue) ? 0 : parseInt(object.defaultValue);
        settings.validate = {
          required: object.required && object.required === '1' ? true : false,
          min: object.min ? object.min : null,
          max: object.max ? object.max : null
        };
        return settings;
        break;
      case 'date':
        var defaultValue;
        var formatDate;
        var enabledDate;
        var enabledTime;
        if (object.subtype === 'time') {
          // "2019-09-02T06:10:00-04:00"
          // 2019-09-02T08:30:00-04:00
          enabledDate = false;
          enabledTime = true;
          formatDate = 'hh:mm a';
          if (object.defaultValue) {
            var anio = 2019;
            var mes = 10;
            var dia = 10;
            defaultValue = anio + '-' + mes + '-' + dia + 'T' + object.defaultValue + ':00-04:00';
          }
        } else {
          formatDate = 'dd/MM/yyyy';
          enabledDate = true;
          enabledTime = false;
          if (object.defaultValue) {
            var fechaFormateada = object.defaultValue.split('/'); // 12/01/1991
            defaultValue = fechaFormateada[2] + '-' + fechaFormateada[1] + '-' + fechaFormateada[0] +'T00:00:00-04:00';
          }
        }
        settings.type = 'datetime';
        settings.format = formatDate;
        settings.enableTime = enabledTime;
        settings.enableDate = enabledDate;
        settings.widget = {
          format: formatDate
        };
        settings.defaultValue = defaultValue;
        return settings;
        break;
      case 'select':
        settings.type = 'select';
        settings.searchEnabled = false;
        settings.data = {
          values: object.values ? object.values : []
        };
        return settings;
        break;
      case 'radio-group':
        settings.type = 'radio';
        settings.values = object.values;
        settings.defaultValue = object.defaultValue;
        return settings;
        break;
      case 'checkbox-group':
        var valores = [];
        var objectDefaultValue = {};
        for (var i = 0; i <= object.values.length - 1; i++) {
          object.values[i].shortcut = undefined;
          if (object.values[i].selected === '0') {
            object.values[i].selected = false;
          } else {
            object.values[i].selected = true;
          }
          objectDefaultValue[object.values[i].value] = object.values[i].selected;
          valores.push(object.values[i]);
        }
        settings.type = 'selectboxes';
        settings.values = valores;
        settings.defaultValue = objectDefaultValue;
        return settings;
        break;
      case 'textarea':
        settings.type = 'textarea';
        settings.rows = object.rows ? object.rows : 3;
        settings.validate = {
          required: object.required && object.required === '1' ? true : false,
          maxLength: object.maxlength,
          minLength: object.minlength
        };
        return settings;
        break;
      case 'map':
        var miPosicionDefault = object.defaultValue;
        settings.defaultValue = object.defaultValue;
        if (miPosicionDefault && miPosicionDefault[object.name]) {
          settings.defaultValue = miPosicionDefault[object.name].lat + ', ' + miPosicionDefault[object.name].lng;
        }
        settings.type = 'location';
        return settings;
        break;
      case 'drawable':
        settings.type = 'file';
        settings.image = true;
        settings.storage = 'base64';
        settings.webcam = false;
        settings.fileMaxSize = object.align; 
        // settings.defaultValue = [{
        //   storage: 'base64',
        //   url: object.defaultValue ? object.defaultValue.replace(/___/gi, ':') : ''  
        // }];
        return settings;
        break;
    }
  }

  COMPONENTES = JSON.parse(COMPONENTES);

  console.log('-----------COMPONENTES SIN ALTERAR ESTRUCTURA DESDE NEW CASE-------------------------');
  console.log(COMPONENTES);
  console.log('------------------------------------');

  function transformarFormio (COMPONENTES) {
    var components = [];
    // Ajax();
    for (var i = 0; i <= COMPONENTES.length - 1; i++) {
      components.push(reverse(COMPONENTES[i]));
    }
    return components;
  }

  function keys (object) {
    var settings = {
      name: object.attributes ? object.attributes.name : '',
      value: object.value
    };
    switch (object.type) {
      case 'htmlelement':
        settings.type = 'header';
        settings.label = object.content.replace(/<(.|\n)*?>/g, "");
        settings.value = object.label;
        return settings;
        break;
      case 'textfield':
        settings.type = 'text';
        settings.subtype = 'text';
        return settings;
        break;
      case 'password':
        settings.type = 'text';
        settings.subtype = 'password';
        return settings;
        break;
      case 'email':
        settings.type = 'text';
        settings.subtype = 'email';
        return settings;
        break;
      case 'number':
        settings.type = 'number';
        return settings;
        break;
      case 'datetime':
        settings.type = 'date';
        settings.subtype = object.format === 'dd/MM/yyyy' ? 'date' : 'time';
        settings.value = transformarFecha(object.value, object.format === 'dd/MM/yyyy' ? 'date' : 'time');
        return settings;
        break;
      case 'select':
        settings.type = 'select';
        var labelEncontrado;
        for (var i = 0; i < object.data.values.length; i++) {
          if (object.data.values[i].value.toString() === object.value.toString()) {
            labelEncontrado = object.data.values[i].label;
            break;
          }
        }
        settings.value = object.value ? [{ label: labelEncontrado, value: object.value}] : [];
        return settings;
        break;
      case 'radio':
        var encontrandoLabel;
        for (var i = 0; i < object.values.length; i++) {
          if (object.values[i].value.toString() === object.value.toString()) {
            encontrandoLabel = object.values[i].label;
            break;
          }
        }
        settings.label = object.value ? encontrandoLabel : null;
        settings.type = 'radio-group';
        settings.value = object.value ? [{ label: encontrandoLabel, value: object.value}] : [];
        return settings;
        break;
      case 'selectboxes':
        settings.type = 'checkbox-group';
        var encontrandoLabel = [];
        for (var i = 0; i < object.values.length; i++) {
          if (object.value[object.values[i].value]) {
            encontrandoLabel.push({ label: object.values[i].label, value: object.values[i].value});
          }
        }
        settings.label = '';
        settings.value = encontrandoLabel;
        return settings;
        break;
      case 'textarea':
        settings.type = 'textarea';
        return settings;
        break;
      case 'file':
        // enviando solo el archivo en base 64
        var imagen = object.value && object.value[0] && object.value[0].url ? object.value[0].url.replace('data:image/png;base64,', '') : '';
        settings.type = 'drawable';
        settings.value = imagen;
        return settings;
        break;
      case 'location':
        var puntoMarker = JSON.parse(window.localStorage.getItem('position_map'));
        settings.type = 'map';
        settings.value = puntoMarker[object.attributes.name] ? puntoMarker[object.attributes.name].lat + ', ' +puntoMarker[object.attributes.name].lng : '';
        return settings;
        break;
    }
  }

  function generateJSON (data, json) {
    var formateado = [];
    var components = [];
    var sw = false;
    for (var i = 0; i <= json.length - 1; i++) {
      if (json[i].type !== 'button') {
        components.push(json[i]);
      }
    }
    for (var i = 0; i <= components.length - 1; i++) {
      var component = components[i];
      component.value = data[component.key];
      if (component.validate && component.validate.required) {
        if (data[component.key].toString().length === 0 && component.type !== 'location' && component.type !== 'selectboxes') {
          alert('Falta llenar ' + component.label + ', no puede estar vacio');
          sw = true;
          break;
        }
        if (component.type === 'location') {
          var puntos = JSON.parse(window.localStorage.getItem('position_map'));
          var getPunto = puntos[component.key];
          if (!getPunto) {
            sw = true;
            alert('Falta llenar ' + component.label + ', no puede estar vacio');
            break;
          }
        }
        if (component.type === 'selectboxes') {
          var valoresSeleccionados = JSON.stringify(component.value).indexOf('true');
          if (valoresSeleccionados === -1) {
            sw = true;
            alert('Falta llenar ' + component.label + ', no puede estar vacio');
            break;
          }
        }
      }
      if (component.type == 'file' && component.value && component.value[0]) {
        if (component.value[0].url.indexOf('image/png') === -1) {
          alert('Solo se pueden subir archivo con extensión png');
          sw = true;
          break;
        }
      }
      if (component.validate && component.validate.maxLength && component.validate.maxLength.length > 0 && component.value.length > 0) {
        var maxLongitud = parseInt(component.validate.maxLength);
        if (component.value.toString().length > maxLongitud) {
          alert('La cantidad de carácteres de ' + component.label + ' excede a lo establecido que es ' + component.validate.maxLength);
          sw = true;
          break;
        }
      }
      if (component.validate && component.validate.minLength && component.validate.minLength.length > 0 && component.value.length > 0) {
        var minLongitud = parseInt(component.validate.minLength);
        if (component.value.toString().length < minLongitud) {
          alert('La cantidad de carácteres de ' + component.label + ' es mucho menor a lo establecido que es '+ component.validate.minLength);
          sw = true;
          break;
        }
      }
      if (component.validate && component.validate.min && component.validate.min.length > 0) {
        var minLongitud = parseInt(component.validate.min);
        if (parseInt(component.value) < minLongitud) {
          alert('El valor que tiene ' + component.label + ' es menor a ' + component.validate.min);
          sw = true;
          break;
        }
      }
      if (component.validate && component.validate.max && component.validate.max.length > 0) {
        var maxLongitud = parseInt(component.validate.max);
        if (parseInt(component.value) > maxLongitud) {
          alert('El valor que tiene ' + component.label + ' es mayor a ' + component.validate.max);
          sw = true;
          break;
        }
      }
      if (component.type === 'email' && component.value && component.value.length > 0) {
        if (!validateEmail(data[component.key])) {
          alert(component.label + ' tiene un correo no válido');
          sw = true;
          break;
        }
      }
      formateado.push(keys(component));
    }
    if (sw) {
      OcultarConfirmación();
      return;
    }
    var sendData = {
      crear: true,
      parametros: {
        crear: true,
        codigo_registro: Math.floor(Math.random() * 1000 + 1),
        form_id: '<?=$formulario->id?>',
        arrElementos: formateado 
      }
    }
    return sendData;
  };
  <?php echo $strValidacionJqValidate; ?>
    Elementos_Habilitar_ObjetoARefComoSubmit("btnGuardarFormulario", "FormularioCampos");
    Ajax_DarActualizarValidacionEnvioAjaxSegmentoForm("FormularioCampos", 'Formularios/guardar_instancia', 'divVistaMenuPantalla', 'divErrorListaResultado');

    $("#divCargarFormulario").show();    
    $("#confirmacion").hide();

    function MostrarConfirmación()
    {
      $("#divCargarFormulario").hide();
      $("#confirmacion").fadeIn(500);
      globalFormio.then(function (res) {
        var components = [];
        for (var i = 0; i <= res.components.length - 1; i++) {
          components.push(res.components[i].component);
        }
        $('#json_stringify_formio').val(JSON.stringify(components));
        var data = generateJSON(res.data, components);
        // delete item.key;
        // item.id = null; eliminar para el orden
        console.log('====================_MENSAJE_A_MOSTRARSE_====================');
        console.log(data);
        console.log('====================_MENSAJE_A_MOSTRARSE_====================');
        $('#json_stringify_formatted').val(JSON.stringify(data));
      })
    }
    
    function OcultarConfirmación()
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

</script>
  <script>
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
      year: 'Año',
      next: 'Siguiente',
      previous: 'Anterior',
      invalid_date: '{{field}} no es una fecha válida.',
      invalid_day: '{{field}} no es un día valido.',
      Submit: 'Enviar',
      Language: 'Idioma',
      Translations: 'Traducciones',
      'First Name': 'Tu nombre',
      'Last Name': 'Apellido',
      'HTML Element Component': 'Título',
      'Text Field Component': 'Campo de texto',
      'Password Component': 'Contraseña',
      'Email Component': 'Correo electrónico',
      'Text Area Component': 'Textarea',
      'Number Component': 'Campo numérico',
      'Date / Time Component': 'Fecha / Hora',
      'Radio Component': 'Opciones',
      'Select Component': 'Lista desplegable',
      'Select Boxes Component': 'Selección Múltiple',
      'File Component': 'Firma Electrónica',
      'Maps Component': 'Mapa',
      'Drop files to attach': 'Agarra y suelta aquí la firma',
      'No choices to choose from': 'Lista vacia...',
      complete: 'Formulario correctamente configurado...',
      'Data Source Values': 'Valores',
      'Add Another': 'Agregar mas',
      'Phone Number': 'Numero telefónico',
      Description: 'Nombre',
      'Description for this field.': 'Nombre para este campo',
      Save: 'Guardar',
      Remove: 'Borrar',
      Cancel: 'Cancelar',
      Format: 'Formato',
      Values: 'Valores',
      'Minimum Value': 'Min',
      'Maximum Value': 'Max',
      Required: '¿Este campo es requerido?',
      'Minimum Date': 'Fecha mínima',
      'Maximum Date': 'Fecha máxima',
      'Minimum Length': 'Longitud mínima',
      'Maximum Length': 'Longitud máxima',
      'Default Value': 'Valor por defecto',
      ClassName: 'Clase',
      Disabled: '¿Este campo estara deshabilitado?',
      Preview: 'Vista previa',
      Help: 'Ayuda',
      Display: 'Vista',
      Data: 'Datos',
      Rows: 'Filas',
      Validation: 'Validaciones',
      API: 'API',
      Tooltip: 'Descripción del campo',
      Conditional: 'Condicionales',
      Logic: 'Logica',
      Layout: 'Atributos',
      Date: 'Fecha',
      Time: 'Hora',
      Content: 'Contenido',
      minLength: 'Faltan caracteres para {{field}}',
      maxLength: '{{field}} excede la cantidad permitida de caracteres',
      'CSS Class': 'Adicionar una clase css',
      'Custom CSS Class': 'Adicionar una clase css',
      'To add a tooltip to this field, enter text here.': 'Desea agregar alguna descripción',
      'Enter your email address': 'Ingrese su dirección de correo electrónico',
      'Enter your first name': 'Ponga su primer nombre',
      'Drag and Drop a form component': 'Arrastra y suelta en esta sección un campo de formulario',
      'Enter your last name': 'Ingresa tu apellido',
      'Valid Email Address': 'dirección de email válida',
      'Please correct all errors before submitting.': 'Por favor, corrija todos los errores antes de enviar.',
      required: '{{field}} es requerido.',
      invalid_email: '{{field}} debe ser un correo electrónico válido.',
      error: 'Por favor, corrija los siguientes errores antes de enviar.',
      'File Minimum Size': 'Tamaño mínimo que puede pesar el archivo',
      'File Maximum Size': 'Tamaño máximo que puede pesar el archivo',
      'File is too big; it must be at most 1MB': 'Archivo demasiado grande, adjunte un archivo de menor tamaño',
      'File is too big; it must be at most 2MB': 'Archivo demasiado grande, adjunte un archivo de menor tamaño',
      'File is too big; it must be at most 3MB': 'Archivo demasiado grande, adjunte un archivo de menor tamaño',
      'File is too big; it must be at most 4MB': 'Archivo demasiado grande, adjunte un archivo de menor tamaño',
      'File is too big; it must be at most 5MB': 'Archivo demasiado grande, adjunte un archivo de menor tamaño',
      'File is too big; it must be at most 6MB': 'Archivo demasiado grande, adjunte un archivo de menor tamaño',
      'File is too big; it must be at most 7MB': 'Archivo demasiado grande, adjunte un archivo de menor tamaño',
      'File is too big; it must be at most 8MB': 'Archivo demasiado grande, adjunte un archivo de menor tamaño'
    };

    // Configuraicon del formio
      var dataTransformada = transformarFormio(COMPONENTES);
      globalFormio = Formio.createForm(document.getElementById('formio'), {
        components: dataTransformada
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
            title: 'Campos de formulario',
            weight: 0,
            components: {
              header: {
                title: 'Título',
                key: 'titulo',
                schema: {
                  label: 'Título',
                  type: 'htmlelement',
                  key: 'titulo',
                  content: '<h1>Título</h1>'
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
                title: 'Contraseña',
                key: 'contrasenia',
                schema: {
                  label: 'Contraseña',
                  type: 'password',
                  key: 'contrasenia',
                  placeholder: 'Ingrese contraseña',
                  defaultValue: '',
                  attributes: {
                    id: 'dos'
                  }
                }
              },
              email: {
                title: 'Correo electronico',
                key: 'email',
                schema: {
                  label: 'Correo electronico',
                  type: 'email',
                  key: 'email',
                  placeholder: 'Ingrese un correo electronico',
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
                title: 'Numérico',
                key: 'numerico',
                schema: {
                  label: 'Campo numérico',
                  type: 'number',
                  key: 'numerico',
                  placeholder: 'Valor numérico',
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
                  placeholder: 'Seleccione una opción',
                  searchEnabled: false,
                  limit: 1000,
                  defaultValue: ''
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
                title: 'Selección multiple',
                key: 'seleccion-multiple',
                schema: {
                  label: 'Selección multiple',
                  type: 'selectboxes',
                  key: 'seleccion-multiple',
                  defaultValue: ''
                }
              },
              firmaElectronica: {
                title: 'Firma electrónica',
                key: 'firma-electronica',
                schema: {
                  label: 'firma electrónica',
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
                key: 'mapa',
                schema: {
                  label: 'Mapa',
                  type: 'location',
                  key: 'mapa',
                  defaultValue: ''
                }
              }
            }
          }
        }
      })
  	</script>
<div id="divVistaMenuPantalla" align="center">
    
    <div id="divCargarFormulario" class="TamanoContenidoGeneral">
        <br /><br />

        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_initium.png) no-repeat; background-size: contain; background-position: center;"> </div>

            <div class="FormularioSubtitulo"> <?php echo $this->lang->line('FormularioDinamicoTitulo'); ?></div>
            <div class="FormularioSubtituloComentarioNormal "><?php echo $this->lang->line('FormularioDinamicoSubtitulo'); ?></div>
        <br/>
        <?php
          $arrCodigos_Registro = $this->mfunciones_logica->ObtenerProspectosEjecutivo(3);
          $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrCodigos_Registro);
          
          if (isset($arrCodigos_Registro[0]))
          {
            echo '<div class="col-xs-12" style="display: none">';
            echo "<span style='display: none'><strong>Seleccione el Código de Registro:</strong></span>";
            echo html_select('codigo_registro', $arrCodigos_Registro, 'prospecto_id', 'prospecto_nombre_cliente', 'SINSELECCIONAR', '');
            echo "</div><br />";
          }
        ?>
        <div style="clear: both"></div>        
        
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
            <a onclick="MostrarConfirmación();" class="BotonMinimalista"> <?php echo $this->lang->line('BotonAceptar'); ?> </a>
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
            <a onclick="OcultarConfirmación();" class="BotonMinimalista"> <?php echo $this->lang->line('BotonCancelar'); ?> </a>
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