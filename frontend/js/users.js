var IS_ADMIN = true; // o false, según corresponda
var itiDetalle, itiCrear;

$(document).ready(function () {
  // Mostrar mensaje en modal
  function showModal(message) {
    $('#modalMessageContent').text(message);
    $('#modalMessage').modal('show');
    // Ocultar otros modales
    $('#detalleUsuarioModal').modal('hide');
    $('#crearUsuarioModal').modal('hide');
    $('#confirmDeleteModal').modal('hide');
    // Remueve manualmente la clase del backdrop si queda atascada
    document.addEventListener('hidden.bs.modal', function () {
      document.body.classList.remove('modal-open');
      document.querySelectorAll('.modal-backdrop').forEach(function (el) {
        el.remove();
      });
    });

    // También puedes añadirlo específicamente cuando cierras tu modal de mensaje
    const modalMessage = document.getElementById('modalMessage');
    modalMessage.addEventListener('hidden.bs.modal', function () {
      document.body.classList.remove('modal-open');
      document.querySelectorAll('.modal-backdrop').forEach(function (el) {
        el.remove();
      });
    });
  }
  
  // Llenar campos del modal de usuario con datos
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

    for (const id in campos) {
      if ($(`#${id}`).length) {
        $(`#${id}`).val(campos[id]);
      }
    }

    if ($("#modalUserExistingPicture").length) {
      $("#modalUserExistingPicture").attr("src", user.profile_picture);
    }

    // Eliminar prefijos duplicados y separar prefijo y número
    let phone = user.phone || "";
    let prefix = "";
    let number = phone;
    let match = phone.match(/^(\+\d{1,4})/);
    if (match) {
        prefix = match[1];
        number = phone.slice(prefix.length);
    }

    // Asignar a los inputs correspondientes
    $("#modalUserPhonePrefix").val(prefix);
    $("#modalUserPhone").val(number);

    document.getElementById("modalUserPhone").addEventListener("input", function () {
      const prefix = document.getElementById("modalUserPhonePrefix").value;
      let number = this.value;

      // Normalizar prefijos como +1-268, +1-876, etc.
      const normalizedPrefix = prefix.replace(/[^+\d]/g, "");

      switch (normalizedPrefix) {
        case "+34": // España
          if (number.startsWith("0")) {
            number = number.slice(1);
          }
          break;
        case "+52": // México
          if (number.startsWith("044") || number.startsWith("045")) {
            number = number.slice(3);
          } else if (number.startsWith("1") && number.length === 11) {
            number = number.slice(1);
          }
          break;
        case "+1": // Estados Unidos y Canadá (incluye +1-xxx)
        case "+1242": case "+1268": case "+1345": case "+1441":
        case "+1473": case "+1649": case "+1664": case "+1670":
        case "+1671": case "+1684": case "+1758": case "+1767":
        case "+1784": case "+1787": case "+1809": case "+1868":
        case "+1876": // Países con código +1
          if (number.startsWith("1") && number.length === 11) {
            number = number.slice(1);
          }
          break;
        case "+91": // India
          if (number.startsWith("0")) {
            number = number.slice(1);
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
            number = number.slice(1);
          }
          break;
      }

      const fullNumber = normalizedPrefix + number;
      // console.log("Número completo:", fullNumber);
    });

    if (IS_ADMIN) {
        $("#currentPasswordGroup").hide();
        $("#modalUserCurrentPassword").prop('required', false);
    } else {
        $("#currentPasswordGroup").show();
        $("#modalUserCurrentPassword").prop('required', true);
    }
    $("#modalUserCurrentPassword").val('');

    if (itiDetalle) {
        itiDetalle.setNumber(user.phone || "");
    }
  }


  // Listar usuarios en DataTable
  async function ListUsers() {
    try {
      const response = await fetch('/trsi/backend/api/list_users.php', {
        method: 'GET'
      });
      const json = await response.json();
      // console.log(json);
      $('#tablaUsuarios').DataTable({
        destroy: true,
        data: json.data,
        columns: [
          { data: 'user_id', title: 'ID' },
          { data: 'first_name', title: 'First Name' },
          { data: 'last_name', title: 'Last Name' },
          { data: 'username', title: 'Username' },
          { data: 'email', title: 'Email' },
          { data: 'phone', title: 'Phone' },
          { data: 'country', title: 'Country' },
          { data: 'city', title: 'City' },
          { data: 'status_id', title: 'Status' },
          { data: 'role_id', title: 'Role' },
          {
            data: null,
            title: 'Actions',
            orderable: false,
            render: (data, type, row) => `
              <button class="btn btn-uam btn-sm me-2" onclick="verDetallesUsuario(${row.user_id})">Detalles</button>
              <button class="btn btn-uam btn-sm" onclick="eliminarUsuario(${row.user_id})">Eliminar</button>
            `
          }
        ]
      });
    } catch (err) {
      console.error(err);
      showModal('Error al obtener los usuarios: ' + err.message);
    }

  }

  // Ver detalles del usuario
  window.verDetallesUsuario = function (user_id) {
    $.ajax({
        url: '/trsi/backend/api/det_user.php',
        method: 'POST',
        data: { user_id: user_id },
        dataType: 'json',
        success: function (response) {
            if (response.status === "success") {
                rellenarCamposUsuario(response.data);
                $('#detalleUsuarioModal').modal('show');
            } else {
                showModal(response.message || "Error al obtener los detalles del usuario");
            }
        },
        error: function (xhr, status, error) {
            console.error('Error:', xhr.responseText);
            showModal("Error al obtener los detalles del usuario: " + error);
        }
    });
  };

  // Eliminar usuario
  window.eliminarUsuario = function (user_id) {
    $('#confirmDeleteModal').modal('show');
    $('#confirmDeleteBtn').off('click').on('click', function () {
      $.ajax({
        url: '/trsi/backend/api/delete_user.php',
        method: 'POST',
        data: { user_id: user_id },
        dataType: 'json',
        success: function(response) {
          if (response.status === "success") {
            showModal(response.message);
            $('#confirmDeleteModal').modal('hide');
            ListUsers();
          } else {
            showModal(response.message || "Error desconocido al eliminar el usuario.");
          }
        },
        error: function(xhr, status, error) {
          showModal("Error en la petición: " + error);
        }
      });
    });
  };

  // Crear usuario
  $("#btnCrearUsuario").on("click", function () {
    const form = $('#formCrearUsuario')[0];
    const formData = new FormData(form);
    
    // Validación de campos
    for (const [key, value] of Object.entries(formData)) {
        if ((value + "").trim() === "") {
            showModal(`Complete el campo: ${key}`);
            return;
        }
    }

    // Usa el valor internacional del plugin
    if (itiCrear) {
        let fullPhone = itiCrear.getNumber();
        formData.set('phone', fullPhone);
    }

    $.ajax({
        url: '/trsi/backend/api/cr_user.php', // Cambiado a la ruta correcta
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function (response) {
            if (response.status === "success") {
                showModal(response.message);
                $('#crearUsuarioModal').modal('hide');
                ListUsers();
            } else {
                showModal(response.message || "Error al crear el usuario");
            }
        },
        error: function (xhr, status, error) {
            console.error('Error:', xhr.responseText); // Para debug
            showModal("Error conectando con el servidor: " + error);
        }
    });
  });

  // Actualizar usuario
  $("#btnUpdateUser").on("click", function () {
    let form = $('#formUpdateUsuario')[0];
    let formData = new FormData(form);

    // Añade el campo is_admin
    formData.append('is_admin', IS_ADMIN ? '1' : '0');

    // Solo valida la contraseña actual si NO es admin
    if (!IS_ADMIN && !formData.get('current_password')) {
        showModal("Debes ingresar tu contraseña actual para actualizar los datos.");
        return;
    }

    // Usa el valor internacional del plugin
    if (itiDetalle) {
        let fullPhone = itiDetalle.getNumber();
        formData.set('phone', fullPhone);
    }

    $.ajax({
        url: '/trsi/backend/api/up_user.php',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function (response) {
            if (response.status === "success") {
                showModal(response.message);
                ListUsers();
                $('#detalleUsuarioModal').modal('hide');
                $('.modal-backdrop').remove();
                document.body.classList.remove('modal-open');
                document.body.style.removeProperty('padding-right');
            } else {
                showModal(response.message || "Error desconocido");
            }
        },
        error: function (xhr, status, error) {
            let msg = "Error conectando con el servidor: " + error;
            if (xhr.responseJSON && xhr.responseJSON.message) {
                msg = xhr.responseJSON.message;
            } else if (xhr.responseText) {
                try {
                    let json = JSON.parse(xhr.responseText);
                    if (json.message) msg = json.message;
                } catch (e) {
                    if (xhr.responseText.trim() !== "") {
                        msg = xhr.responseText;
                    }
                }
            }
            showModal(msg);
        }
    });
  });

  // Imagen redimensionada
  $("#modalUserExistingPicture", "#createUserProfilePicture").on("change", function (event) {
    const file = event.target.files[0];
    if (!file || !file.type.startsWith("image/")) return;

    const maxWidth = 200, maxHeight = 200;
    const reader = new FileReader();
    reader.onload = function (e) {
      const img = new Image();
      img.onload = function () {
        let width = img.width, height = img.height;
        if (width > maxWidth || height > maxHeight) {
          const scale = Math.min(maxWidth / width, maxHeight / height);
          width = Math.round(width * scale);
          height = Math.round(height * scale);
        }

        const canvas = document.createElement("canvas");
        canvas.width = width;
        canvas.height = height;
        const ctx = canvas.getContext("2d");
        ctx.drawImage(img, 0, 0, width, height);

        canvas.toBlob(function (blob) {
          const resizedFile = new File([blob], file.name, {
            type: file.type,
            lastModified: Date.now()
          });

          const dataTransfer = new DataTransfer();
          dataTransfer.items.add(resizedFile);
          event.target.files = dataTransfer.files;
        }, file.type, 0.9);
      };
      img.src = e.target.result;
    };
    reader.readAsDataURL(file);
  });

  $("#modalUserCountry").on("change", function() {
    if ($(this).val() === "Colombia") {
        $("#ciudadColombiaDiv").show();
        $("#ciudadOtroDiv").hide();
        $("#modalUserCityOtro").val('');
    } else if ($(this).val() !== "") {
        $("#ciudadColombiaDiv").hide();
        $("#ciudadOtroDiv").show();
        $("#modalUserCity").val('');
    } else {
        $("#ciudadColombiaDiv").hide();
        $("#ciudadOtroDiv").hide();
        $("#modalUserCity").val('');
        $("#modalUserCityOtro").val('');
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

});
