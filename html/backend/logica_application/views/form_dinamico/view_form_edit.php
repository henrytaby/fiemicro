<?php
  // $componentes = '[{"catalogo":null,"description":"","key":"título","label":"Este es el titulo","name":"título","parent_codigo":null,"parent_tipo":null,"readonly":false,"required":false,"type":"header","values":"Este es el titulo"},{"catalogo":null,"defaultValue":"Valor por defecto","description":"esta es una descripcion para este campo","key":"esteEsElCampoDeTexto","label":"Este es el campo de texto","maxlength":100,"minlength":10,"name":"data_nombre_completo","parent_codigo":null,"parent_tipo":null,"placeholder":"Campo placeholder","readonly":true,"required":true,"subtype":"text","type":"text","values":"Valor por defecto","className":""},{"catalogo":null,"defaultValue":"valor por defecto","description":"","key":"estaEsLaContraseña","label":"Esta es la contraseña","maxlength":"","minlength":"","name":"estaEsLaContraseña","parent_codigo":null,"parent_tipo":null,"placeholder":"Ingrese contraseña","readonly":false,"required":true,"subtype":"password","type":"text","values":"valor por defecto","className":""},{"catalogo":null,"defaultValue":"","description":"","key":"correoElectrónico","label":"Correo electrónico","maxlength":"","minlength":"","name":"correoElectrónico","parent_codigo":null,"parent_tipo":null,"placeholder":"Ingrese un correo electrónico","readonly":false,"required":false,"subtype":"email","type":"text","values":"","className":""},{"catalogo":null,"defaultValue":"Este es un valor por defecto en el textarea","description":"","key":"textarea","label":"Textarea","maxlength":100,"minlength":"","name":"textarea","parent_codigo":null,"parent_tipo":null,"placeholder":"Ingrese algun texto","readonly":false,"required":true,"rows":3,"subtype":"textarea","type":"textarea","values":"Este es un valor por defecto en el textarea","className":""},{"catalogo":null,"defaultValue":0,"description":"","key":"campoNumérico","label":"Campo numérico","max":100,"min":10,"name":"campoNumérico","parent_codigo":null,"parent_tipo":null,"placeholder":"Valor numérico","readonly":false,"required":false,"type":"number","values":0,"step":1},{"catalogo":null,"defaultValue":"11/09/2019","description":"","key":"campoFecha","label":"Campo fecha","name":"campoFecha","parent_codigo":null,"parent_tipo":null,"placeholder":"Ingrese una fecha","readonly":false,"required":true,"subtype":"date","type":"date","values":"11/09/2019"},{"catalogo":null,"defaultValue":"14:00","description":"","key":"hora","label":"Hora","name":"hora","parent_codigo":null,"parent_tipo":null,"placeholder":"Ingrese una hora","readonly":false,"required":true,"subtype":"time","type":"date","values":"14:00"},{"catalogo":null,"description":"","key":"listaDeDepartamentos","label":"Lista de departamentos","name":"listaDeDepartamentos","parent_codigo":null,"parent_tipo":null,"placeholder":"Seleccione una opción","readonly":false,"required":true,"type":"select","values":[{"label":"La Paz","value":"1","selected":false},{"label":"Oruro","value":"2","selected":true},{"label":"Santa Cruz","value":"3","selected":false}]},{"catalogo":null,"defaultValue":2,"description":"","key":"radio","label":"Radio","name":"radio","parent_codigo":null,"parent_tipo":null,"readonly":false,"required":true,"type":"radio-group","values":[{"label":"Adrian","value":"1","selected":false},{"label":"Diego","value":"2","selected":true},{"label":"Ivan","value":"3","selected":false}]},{"catalogo":null,"defaultValue":"{1:false,2:true,3:true}","description":"","key":"checks","label":"Checks","name":"checks","parent_codigo":null,"parent_tipo":null,"readonly":false,"required":true,"type":"checkbox-group","values":[{"label":"Adrian","value":"1","selected":false},{"label":"Diego","value":"2","selected":true},{"label":"Max","value":"3","selected":true}]},{"catalogo":null,"description":"","key":"firmaElectrónica","label":"firma electrónica","name":"firmaElectrónica","parent_codigo":null,"parent_tipo":null,"readonly":false,"required":true,"type":"drawable","values":null},{"catalogo":null,"defaultValue":null,"description":"","key":"mapa","label":"Mapa","name":"mapa","parent_codigo":null,"parent_tipo":null,"readonly":false,"required":false,"type":"map","values":null}]';
  echo '<script type="text/javascript">
    var COMPONENTES = '.json_encode($componentes).';
  </script>';
?>
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

  function reverse (object) {
    var settings = {
      disabled: object.readonly === "true" || object.readonly === true || object.readonly === '1'  ? true : false,
      validate: {
        required: object.required && object.required === '1' ? true : false
      },
      label: object.label ? object.label : '',
      tooltip: object.description ? object.description : '',
      attributes: {
        catalogo: object.catalogo ? object.catalogo : null,
        parent_codigo: object.parent_codigo ? object.parent_codigo : null,
        parent_tipo: object.parent_tipo ? object.parent_tipo : null
      },
      id: null,
      idComponente: object.id,
      key: object.label ? camelCase(object.label) : '',
      description: object.name,
      placeholder: object.placeholder ? object.placeholder : '',
      defaultValue: object.defaultValue ? object.defaultValue : '',
      customClass: object.className ? object.className : ''
    };
    switch (object.type) {
      case 'header':
          settings.type = 'htmlelement';
          settings.customClass = object.align ? object.align : 'left';
          settings.content = "<h1>"+ object.label ? object.label : '' +"</h1>";
          return settings;
        break;
      case 'text':
        var tipo; 
        if (object.subtype === 'text') {
          tipo = 'textfield';
          } else {
          if (object.subtype === 'password') {
            tipo = 'password';
          } else {
            tipo = 'email';
          }
        }
        settings.type = tipo;
        settings.validate = {
          required: object.required && object.required === '1' ? true : false,
          maxLength: object.maxlength,
          minLength: object.minlength
        };
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
        var revisandoValores = [];
        var valoresFinales = [];
        for (var i = 0; i < object.values.length; i++) {
          if (revisandoValores.indexOf(object.values[i].label) === -1) {
            revisandoValores.push(object.values[i].label);
            valoresFinales.push(object.values[i]);
          }
        }
        settings.data = {
          values: valoresFinales
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
        settings.defaultValue = miPosicionDefault;
        if (miPosicionDefault && miPosicionDefault[object.name]) {
          settings.defaultValue = miPosicionDefault[object.name].lat + ', ' + miPosicionDefault[object.name].lng;
        }
        settings.type = 'address';
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
  console.log('-----------COMPONENTES SIN ALTERAR ESTRUCTURA DESDE EDIT-------------------------');
  console.log(COMPONENTES);
  console.log('------------------------------------');

  function transformarFormio (COMPONENTES) {
    var components = [];
    for (var i = 0; i <= COMPONENTES.length - 1; i++) {
      if (COMPONENTES[i] && COMPONENTES[i].type) {
        components.push(reverse(COMPONENTES[i]));
      }
    }
    return components;
  }

  function keys (object) {
      var settings = {
        idComponente: object.idComponente,
        readonly: object.disabled,
        required: object.validate ? object.validate.required : false,
        label: object.label,
        description: object.tooltip,
        name: object.description ? object.description : camelCase(object.label),
        key: object.label ? camelCase(object.label) : '',
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
          settings.min = object.validate ? object.validate.min : 1;
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
    function generateJSON (data, json) {
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
        formulario: {
          id: <?=$formulario->id?>,
          nombre: $('#nombreForm').val(),
          descripcion: $('#descripcionForm').val(),
          'tipo_bandeja': $('#tipo_bandeja').val(),
          publicado: <?=$formulario->publicado?>
        },
        lista_elementos: formateado
      }
      return sendData;
    }
   <?php echo $strValidacionJqValidate; ?>
    Elementos_Habilitar_ObjetoARefComoSubmit("btnGuardarFormulario", "FormularioCampos");
    Ajax_DarActualizarValidacionEnvioAjaxSegmentoForm("FormularioCampos", 'Formularios/guardar', 'divVistaMenuPantalla', 'divErrorListaResultado');

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
    function customSort(arr){
      for(var i =0;i<arr.length;i++){
        for(var j= i+1;j<arr.length;j++){
          if(arr[i]>arr[j]){
            var swap = arr[i];
            arr[i] = arr[j];
            arr[j] = swap;
          }
        }
      }
    return arr;
    }

    function MostrarConfirmación()
    {
        $("#divCargarFormulario").hide();
        $("#confirmacion").fadeIn(500);
        globalFormio.then(function (res) {
          var components = [];
          for (var i = 0; i <= res.components.length - 1; i++) {
            components.push(res.components[i].component);
          }
          var ids = [];
          for (var i = 0; i <= components.length; i++) {
            if (components[i]) {
              var valorIdComponente = parseInt(components[i].idComponente);
              if (isNaN(valorIdComponente)) {
                valorIdComponente = 10000000000000;
              } 
              ids.push(valorIdComponente);
            }
          }
          var idsOrdenados = customSort(ids);
          $('#json_stringify_formio').val(JSON.stringify(components));
          var data = generateJSON([], components);
          // for (var i = 0; i <= data.lista_elementos.length; i++) {
          //   if (data.lista_elementos[i]) {
          //     var idAsignado = idsOrdenados[i];
          //     if (idAsignado === 10000000000000) {
          //       idAsignado = null;
          //     }
          //     data.lista_elementos[i].idComponente = idAsignado;
          //   }
          // }
          for (var i = 0; i <= data.lista_elementos.length; i++) {
            if (data.lista_elementos[i]) {
              var idAsignado = null;
              data.lista_elementos[i].idComponente = idAsignado;
            }
          }
          if (data.lista_elementos.length === 0) {
            OcultarConfirmación();
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
            OcultarConfirmación();
            alert('Error existen componentes de tipo de radio-group, checkbox-group o select que no tiene valores');
            return;
          }

          console.log('====================_MENSAJE_A_MOSTRARSE_====================')
          console.log(data)
          console.log('====================_MENSAJE_A_MOSTRARSE_====================')
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
      Layout: 'Catálogo',
      Date: 'Fecha',
      Time: 'Hora',
      Content: 'Contenido',
      minLength: 'Faltan caracteres para {{field}}',
      maxLength: '{{field}} excede la cantidad permitida de caracteres',
      'CSS Class': 'Adicionar una clase css / Alineación',
      'Custom CSS Class':  'Adicionar una clase css / Alineación',
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
      'Attribute Name': 'Atributo del catalogo',
      'Attribute Value': 'Valor del catalogo',
      'Add Attribute': 'Adicionar mas atributos',
      'Location Component': 'Ubicación',
      'Address Field Component': 'Ubicación',
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

    // Configuracion del formio
      var dataTransformada = transformarFormio(COMPONENTES);
      console.log('---------COMPONENTES FORMATEADOS---------------------------');
      console.log(dataTransformada);
      console.log('------------------------------------');
      globalFormio = Formio.builder(document.getElementById('formio'), {
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
            title: 'Componentes',
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
                title: 'Correo electrónico',
                key: 'email',
                schema: {
                  label: 'Correo electrónico',
                  type: 'email',
                  key: 'email',
                  placeholder: 'Ingrese un correo electrónico',
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
                title: 'Selección múltiple',
                key: 'seleccion-multiple',
                schema: {
                  label: 'Selección múltiple',
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
              ignore: false,
              components: {
                label: {
                  required: true
                },
                placeholder: {
                  required: true
                }
              }
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
        <div style="clear: both"></div>
        <div class="container">
          <div class="row">
            <div class="col-md-4 col-xs-12">
              <div class="form-group">
                <label for="nombreForm">Nombre del formulario</label>
                <input type="email" class="form-control" id="nombreForm" aria-describedby="emailHelp" placeholder="Nombre del Formulario" value="<?=$formulario->nombre?>">
              </div>
            </div>
            <div class="col-md-4 col-xs-12">
              <div class="form-group">
                <label for="descripcionForm">Descripcion</label>
                <input type="email" class="form-control" id="descripcionForm" aria-describedby="emailHelp" placeholder="Descripcion del Formulario" value="<?=$formulario->descripcion?>">
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
              echo html_select('tipo_bandeja', $arrTipoBandeja, 'codigo', 'valor', 'SINSELECCIONAR', $formulario->tipo_bandeja);
              echo "</div><br />";

              // Listado de Códigos de Registro

              // $arrCodigos_Registro = $this->mfunciones_logica->ObtenerProspectosEjecutivo(3);
              // $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrCodigos_Registro);
              
              // if (isset($arrCodigos_Registro[0]))
              // {
              //   echo '<div class="col-md-3 col-xs-12">';
              //   echo "<span><strong>Seleccione el Código de Registro:</strong></span>";
              //   echo html_select('codigo_registro', $arrCodigos_Registro, 'prospecto_id', 'prospecto_nombre_cliente', 'SINSELECCIONAR', $formulario->codigo_registro);
              //   echo "</div><br />";
              // }
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