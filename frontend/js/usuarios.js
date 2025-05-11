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
      modalUserRole: user.role_id
    };

    for (const id in campos) {
      if ($(`#${id}`).length) {
        $(`#${id}`).val(campos[id]);
      }
    }

    if ($("#modalUserExistingPicture").length) {
      $("#modalUserExistingPicture").attr("src", user.profile_picture);
    }

    // Eliminar prefijos duplicados en el teléfono
    let phone = user.phone;
    if (phone.startsWith("+00")) {
      phone = phone.slice(3); // Eliminar el prefijo redundante "+00"
    } else if (phone.startsWith("+000")) {
      phone = phone.slice(4); // Eliminar el prefijo redundante "+000"
    }

    // Asignar el número limpio al input del teléfono
    $("#modalUserPhone").val(phone);

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
      console.log("Número completo:", fullNumber);
    });
  }


  // Listar usuarios en DataTable
  async function ListUsers() {
    try {
      const response = await fetch('/trsi/backend/controllers/ListUserController.php', {
        method: 'POST'
      });
      const json = await response.json();
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
          { data: 'status_name', title: 'Status' },
          { data: 'role_name', title: 'Role' },
          {
            data: null,
            title: 'Actions',
            orderable: false,
            render: (data, type, row) => `
              <button class="btn btn-info btn-sm" onclick="verDetallesUsuario(${row.user_id})">Detalles</button>
              <button class="btn btn-danger btn-sm" onclick="eliminarUsuario(${row.user_id})">Eliminar</button>
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
    $.post('/trsi/backend/controllers/DetUserController.php', { user_id }, function (response) {
      let user = JSON.parse(response);
      if (user.error) {
        showModal(user.error);
      } else {
        rellenarCamposUsuario(user);
        $('#detalleUsuarioModal').modal('show');
      }
    }).fail(function () {
      showModal("Error al obtener los detalles del usuario.");
    });
  };

  // Eliminar usuario
  window.eliminarUsuario = function (user_id) {
    $('#confirmDeleteModal').modal('show');
    $('#confirmDeleteBtn').off('click').on('click', function () {
      $.post('/trsi/backend/controllers/DelUserController.php', { user_id }, function (response) {
        let result = JSON.parse(response);
        if (result.success) {
          showModal(result.success);
          $('#confirmDeleteModal').modal('hide');
          ListUsers();
        } else {
          showModal(result.error || "Error desconocido al eliminar el usuario.");
        }
      }).fail(function () {
        showModal("Error conectando con el servidor.");
      });
    });
  };

  // Crear usuario
  $("#btnCrearUsuario").on("click", function () {
    const form = $('#formCrearUsuario')[0]; // Asegúrate de que el formulario tenga este ID
    const formData = new FormData(form);
    for (const [key, value] of Object.entries(formData)) {
      if ((value + "").trim() === "") {
        showModal(`Complete el campo: ${key}`);
        return;
      }
    }

    $.ajax({
      url: '/trsi/backend/controllers/CrUserController.php',
      method: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      success: function (response) {
        let result = JSON.parse(response);
        showModal(result.message || "Usuario creado.");
        if (result.success) {
          $('#crearUsuarioModal').modal('hide');
          ListUsers();
        }
      },
      error: function () {
        showModal("Error conectando con el servidor.");
      }
    });

  });

  // Actualizar usuario
  $("#btnUpdateUser").on("click", function () {
    let form = $('#formUpdateUsuario')[0];
    let formData = new FormData(form);
    let user_id = $("#modalUserUser_id").val();

    if (!user_id) {
      showModal("ID de usuario no válido.");
      return;
    }

    $.ajax({
      url: '/trsi/backend/controllers/UpUserController.php',
      method: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      success: function (response) {
        let result = JSON.parse(response);
        if (result.success) {
          showModal(result.success);
          ListUsers();
          $('#detalleUsuarioModal').modal('hide');
          $('.modal-backdrop').remove();
          document.body.classList.remove('modal-open');
          document.body.style.removeProperty('padding-right');
        } else {
          showModal(result.error || "Error desconocido");
        }
      },
      error: function () {
        showModal("Error conectando con el servidor.");
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

  // Inicializar
  ListUsers();

});
