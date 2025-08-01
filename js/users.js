var IS_ADMIN = true; // Variable que indica si el usuario es administrador
var itiDetalle, itiCrear;

$(document).ready(function () {
  // Función para mostrar un modal con un mensaje
  function showModal(message) {
    $('#modalMessageContent').text(message); // Establecer el contenido del modal
    $('#modalMessage').modal('show'); // Mostrar el modal
    // Ocultar otros modales abiertos
    $('#detalleUsuarioModal').modal('hide');
    $('#crearUsuarioModal').modal('hide');
    $('#confirmDeleteModal').modal('hide');
    // Remover la clase de fondo modal si queda atascada
    document.addEventListener('hidden.bs.modal', function () {
      document.body.classList.remove('modal-open'); // Remover clase de modal abierto
      document.querySelectorAll('.modal-backdrop').forEach(function (el) {
        el.remove(); // Remover el fondo modal
      });
    });
  }
  
  // Función para llenar los campos del modal de usuario con datos
  function rellenarCamposUsuario(user) {
    const campos = {
      modalUserUser_id: user.user_id,
      modalUserProfilePicture: user.profile_picture,
      modalUserFirstName: user.first_name,
      modalUserLastName: user.last_name,
      modalUserUsername: user.username,
      modalUserEmail: user.email,
      modalUserPhone: user.phone,
      modalUserCountry: user.country,
      modalUserCity: user.city,
      modalUserBirthdate: user.birthdate,
      modalUserStatus: user.status_id,
      modalUserRole: user.role_id,
    };

    // Llenar los campos del modal con los datos del usuario
    for (const id in campos) {
      if ($(`#${id}`).length) {
        $(`#${id}`).val(campos[id]); // Establecer el valor en el campo correspondiente
      }
    }

    // Establecer la imagen de perfil si existe
    if ($("#modalUserExistingPicture").length) {
      $("#modalUserExistingPicture").attr("src", user.profile_picture);
    }

    // Obtener y separar el número de teléfono en prefijo y número
    let phone = user.phone || "";
    let prefix = "";
    let number = phone;
    let match = phone.match(/^(\+\d{1,4})/); // Obtener el prefijo
    if (match) {
        prefix = match[1];
        number = phone.slice(prefix.length); // Obtener el número sin el prefijo
    }

    // Asignar el prefijo y el número a los campos correspondientes
    $("#modalUserPhonePrefix").val(prefix);
    $("#modalUserPhone").val(number);

    // Manejo de la entrada del número de teléfono
    document.getElementById("modalUserPhone").addEventListener("input", function () {
      const prefix = document.getElementById("modalUserPhonePrefix").value;
      let number = this.value;

      // Normalizar prefijos de teléfono
      const normalizedPrefix = prefix.replace(/[^+\d]/g, "");

      switch (normalizedPrefix) {
        case "+34": // España
          if (number.startsWith("0")) {
            number = number.slice(1); // Quitar un 0 si lo tiene
          }
          break;
        case "+52": // México
          if (number.startsWith("044") || number.startsWith("045")) {
            number = number.slice(3); // Quitar prefijo
          } else if (number.startsWith("1") && number.length === 11) {
            number = number.slice(1); // Quitar prefijo
          }
          break;
        case "+1": // Estados Unidos y Canadá
        case "+1242": case "+1268": case "+1345": case "+1441":
        case "+1473": case "+1649": case "+1664": case "+1670":
        case "+1671": case "+1684": case "+1758": case "+1767":
        case "+1784": case "+1787": case "+1809": case "+1868":
        case "+1876": // Países con código +1
          if (number.startsWith("1") && number.length === 11) {
            number = number.slice(1); // Quitar prefijo
          }
          break;
        case "+91": // India
          if (number.startsWith("0")) {
            number = number.slice(1); // Quitar un 0 si lo tiene
          }
          break;
        case "+57": // Colombia
          if (number.startsWith("03")) {
            number = number.slice(1); // Quitar un 0 si lo tiene
          }
          break;
        default:
          // Para la mayoría de países, si el número empieza con 0, quitarlo
          if (number.startsWith("0")) {
            number = number.slice(1); // Quitar un 0 si lo tiene
          }
          break;
      }

      const fullNumber = normalizedPrefix + number; // Número completo
    });

    // Mostrar u ocultar el grupo de contraseña actual según si es admin
    if (IS_ADMIN) {
        $("#currentPasswordGroup").hide(); // Ocultar grupo de contraseña actual si es admin
        $("#modalUserCurrentPassword").prop('required', false); // No requerir contraseña actual
    } else {
        $("#currentPasswordGroup").show(); // Mostrar grupo de contraseña actual si no es admin
        $("#modalUserCurrentPassword").prop('required', true); // Requerir contraseña actual
    }
    $("#modalUserCurrentPassword").val(''); // Limpiar campo de contraseña actual

    // Establecer el número en el plugin si existe
    if (itiDetalle) {
        itiDetalle.setNumber(user.phone || "");
    }
  }

  // Función para listar usuarios en DataTable
  async function ListUsers() {
    try {
      const response = await fetch('../api/list_users.php', {
        method: 'GET' // Método de la solicitud
      });
      const json = await response.json(); // Convertir respuesta a JSON
      $('#tablaUsuarios').DataTable({
        destroy: true, // Permitir destruir la tabla anterior
        data: json.data, // Datos a mostrar en la tabla
        columns: [
          { data: 'user_id', title: 'ID' },
          { data: 'first_name', title: 'Nombre' },
          { data: 'last_name', title: 'Apellido' },
          { data: 'username', title: 'Usuario' },
          { data: 'email', title: 'Email' },
          { data: 'phone', title: 'Telefono' },
          { data: 'country', title: 'Pais' },
          { data: 'city', title: 'Ciudad' },
          { data: 'status_id', title: 'Estado' },
          { data: 'role_id', title: 'Role' },
          {
            data: null,
            title: 'Acciones',
            orderable: false,
            render: (data, type, row) => `
              <button class="btn btn-uam btn-sm me-2" onclick="verDetallesUsuario(${row.user_id})">Detalles</button>
              <button class="btn btn-uam btn-sm" onclick="eliminarUsuario(${row.user_id})">Eliminar</button>
            ` // Botones para ver detalles y eliminar usuario
          }
        ]
      });
    } catch (err) {
      console.error(err);
      showModal('Error al obtener los usuarios: ' + err.message); // Mensaje de error
    }
  }

  // Función para ver detalles del usuario
  window.verDetallesUsuario = function (user_id) {
    $.ajax({
        url: '../api/det_user.php',
        method: 'POST',
        data: { user_id: user_id }, // Enviar ID del usuario
        dataType: 'json',
        success: function (response) {
            if (response.status === "success") {
                rellenarCamposUsuario(response.data); // Llenar campos con datos del usuario
                $('#detalleUsuarioModal').modal('show'); // Mostrar modal de detalles
            } else {
                showModal(response.message || "Error al obtener los detalles del usuario"); // Mensaje de error
            }
        },
        error: function (xhr, status, error) {
            console.error('Error:', xhr.responseText); // Mostrar error en consola
            showModal("Error al obtener los detalles del usuario: " + error); // Mensaje de error
        }
    });
  };

  // Función para eliminar usuario
  window.eliminarUsuario = function (user_id) {
    $('#confirmDeleteModal').modal('show'); // Mostrar modal de confirmación
    $('#confirmDeleteBtn').off('click').on('click', function () {
      $.ajax({
        url: '../api/delete_user.php',
        method: 'POST',
        data: { user_id: user_id }, // Enviar ID del usuario a eliminar
        dataType: 'json',
        success: function(response) {
          if (response.status === "success") {
            showModal(response.message); // Mensaje de éxito
            $('#confirmDeleteModal').modal('hide'); // Cerrar modal de confirmación
            ListUsers(); // Recargar lista de usuarios
          } else {
            showModal(response.message || "Error desconocido al eliminar el usuario."); // Mensaje de error
          }
        },
        error: function(xhr, status, error) {
          showModal("Error en la petición: " + error); // Mensaje de error
        }
      });
    });
  };

  // Función para crear usuario
  $("#btnCrearUsuario").on("click", function () {
    const form = $('#formCrearUsuario')[0]; // Obtener formulario
    const formData = new FormData(form); // Crear objeto FormData
    
    // Validación de campos
    for (const [key, value] of Object.entries(formData)) {
        if ((value + "").trim() === "") {
            showModal(`Complete el campo: ${key}`); // Mensaje de error
            return;
        }
    }

    // Usa el valor internacional del plugin
    if (itiCrear) {
        let fullPhone = itiCrear.getNumber(); // Obtener número completo
        formData.set('phone', fullPhone); // Establecer número en el FormData
    }

    // Enviar datos de creación al servidor
    $.ajax({
        url: '../api/cr_user.php', // URL del API
        method: 'POST',
        data: formData,
        processData: false, // No procesar los datos
        contentType: false, // No establecer el tipo de contenido
        dataType: 'json',
        success: function (response) {
            if (response.status === "success") {
                showModal(response.message); // Mensaje de éxito
                $('#crearUsuarioModal').modal('hide'); // Cerrar modal de creación
                ListUsers(); // Recargar lista de usuarios
            } else {
                showModal(response.message || "Error al crear el usuario"); // Mensaje de error
            }
        },
        error: function (xhr, status, error) {
            console.error('Error:', xhr.responseText); // Mostrar error en consola
            showModal("Error conectando con el servidor: " + error); // Mensaje de error
        }
    });
  });

  // Función para actualizar usuario
  $("#btnUpdateUser").on("click", function () {
    let form = $('#formUpdateUsuario')[0]; // Obtener formulario
    let formData = new FormData(form); // Crear objeto FormData

    // Añade el campo is_admin
    formData.append('is_admin', IS_ADMIN ? '1' : '0'); // Establecer si es admin

    // Solo valida la contraseña actual si NO es admin
    if (!IS_ADMIN && !formData.get('current_password')) {
        showModal("Debes ingresar tu contraseña actual para actualizar los datos."); // Mensaje de error
        return;
    }

    // Usa el valor internacional del plugin
    if (itiDetalle) {
        let fullPhone = itiDetalle.getNumber(); // Obtener número completo
        formData.set('phone', fullPhone); // Establecer número en el FormData
    }

    // Enviar datos de actualización al servidor
    $.ajax({
        url: '../api/up_user.php', // URL del API
        method: 'POST',
        data: formData,
        processData: false, // No procesar los datos
        contentType: false, // No establecer el tipo de contenido
        dataType: 'json',
        success: function (response) {
            if (response.status === "success") {
                showModal(response.message); // Mensaje de éxito
                ListUsers(); // Recargar lista de usuarios
                $('#detalleUsuarioModal').modal('hide'); // Cerrar modal de detalles
                $('.modal-backdrop').remove(); // Remover backdrop
                document.body.classList.remove('modal-open'); // Remover clase de modal abierto
                document.body.style.removeProperty('padding-right'); // Remover padding derecho
            } else {
                showModal(response.message || "Error desconocido"); // Mensaje de error
            }
        },
        error: function (xhr, status, error) {
            let msg = "Error conectando con el servidor: " + error; // Mensaje de error
            if (xhr.responseJSON && xhr.responseJSON.message) {
                msg = xhr.responseJSON.message; // Mensaje del backend
            } else if (xhr.responseText) {
                try {
                    let json = JSON.parse(xhr.responseText);
                    if (json.message) msg = json.message; // Mensaje del backend
                } catch (e) {
                    if (xhr.responseText.trim() !== "") {
                        msg = xhr.responseText; // Mensaje de error
                    }
                }
            }
            showModal(msg); // Mostrar mensaje de error
        }
    });
  });

  // Manejo del cambio en el input de imagen de perfil
  $("#modalUserExistingPicture", "#createUserProfilePicture").on("change", function (event) {
    const file = event.target.files[0]; // Obtener archivo
    if (!file || !file.type.startsWith("image/")) return; // Validar tipo de archivo

    const maxWidth = 200, maxHeight = 200; // Ancho y alto máximo
    const reader = new FileReader();
    reader.onload = function (e) {
      const img = new Image();
      img.onload = function () {
        let width = img.width;
        let height = img.height;
        // Redimensionar si es necesario
        if (width > maxWidth || height > maxHeight) {
          const scale = Math.min(maxWidth / width, maxHeight / height);
          width = Math.round(width * scale);
          height = Math.round(height * scale);
        }

        const canvas = document.createElement("canvas");
        canvas.width = width;
        canvas.height = height;
        const ctx = canvas.getContext("2d");
        ctx.drawImage(img, 0, 0, width, height); // Dibujar imagen en el canvas

        // Convertir a blob y reemplazar el archivo en el input
        canvas.toBlob(function (blob) {
          const resizedFile = new File([blob], file.name, {
            type: file.type,
            lastModified: Date.now()
          });

          const dataTransfer = new DataTransfer();
          dataTransfer.items.add(resizedFile);
          event.target.files = dataTransfer.files; // Actualizar archivos en el input
        }, file.type, 0.9);
      };
      img.src = e.target.result; // Cargar imagen
    };
    reader.readAsDataURL(file); // Leer archivo como URL de datos
  });

  // Manejo del cambio en el país seleccionado
  $("#modalUserCountry").on("change", function() {
    if ($(this).val() === "Colombia") {
        $("#ciudadColombiaDiv").show(); // Mostrar div de ciudad para Colombia
        $("#ciudadOtroDiv").hide(); // Ocultar div de ciudad para otros países
        $("#modalUserCityOtro").val(''); // Limpiar campo de ciudad
    } else if ($(this).val() !== "") {
        $("#ciudadColombiaDiv").hide(); // Ocultar div de ciudad para Colombia
        $("#ciudadOtroDiv").show(); // Mostrar div de ciudad para otros países
        $("#modalUserCity").val(''); // Limpiar campo de ciudad
    } else {
        $("#ciudadColombiaDiv").hide(); // Ocultar div de ciudad para Colombia
        $("#ciudadOtroDiv").hide(); // Ocultar div de ciudad para otros países
        $("#modalUserCity").val(''); // Limpiar campo de ciudad
        $("#modalUserCityOtro").val(''); // Limpiar campo de ciudad
    }
  });
  
  $("#createUserCountry").on("change", function() {
    if ($(this).val() === "Colombia") {
        $("#CreateciudadColombiaDiv").show();
        $("#CreateciudadOtroDiv").hide();
        $("#CreateUserCityOtro").val('');
    } else if ($(this).val() !== "") {
        $("#CreateciudadColombiaDiv").hide();
        $("#CreateciudadOtroDiv").show();
        $("#createUserCity").val('');
    } else {
        $("#CreateciudadColombiaDiv").hide();
        $("#CreateciudadOtroDiv").hide();
        $("#createUserCity").val('');
        $("#CreateUserCityOtro").val('');
    }
  });

  // Para el modal de detalles
  var inputDetalle = document.querySelector("#modalUserPhone");
  if (inputDetalle) {
    itiDetalle = window.intlTelInput(inputDetalle, {
      initialCountry: "auto",
      nationalMode: false,
      utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/18.1.1/js/utils.js"
    });
  }

  // Para el modal de crear usuario
  var inputCrear = document.querySelector("#createUserPhone");
  if (inputCrear) {
    itiCrear = window.intlTelInput(inputCrear, {
      initialCountry: "auto",
      nationalMode: false,
      utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/18.1.1/js/utils.js"
    });
  }

  // Inicializar
  ListUsers();

  // Agregar evento al botón de recarga
  $("#btnRecargarUsuarios").on("click", function () {
    ListUsers(); // Llama a la función para recargar la lista de usuarios
  });

});
