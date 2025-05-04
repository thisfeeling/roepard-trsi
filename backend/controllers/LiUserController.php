<?php
// Incluir un archivo de conexion a la base de datos
require_once '../core/DBConfig.php';
// Crear instancia de conexión a la base de datos
$auth = new DBconfig();
$db = $auth->getConnection();

$sql = "SELECT 
    users.user_id,
	users.profile_picture,
    users.first_name,
    users.last_name,
    users.email,
    users.role_id, 
    users.username,
	users.phone,
	users.password,
	users.country,
	users.city,
    users.status_id,
    users.birthdate,
	users.created_at,
	users.updated_at,
	users.password,
    roles.role_name AS rol 
FROM users 
INNER JOIN roles ON users.role_id = roles.role_id";
$query = $db->prepare($sql);
$query->execute();

// Tabla de usuarios
if ($query->rowCount() > 0) {
	$output = "";
	$output .= "<table class='table align-items-center mb-0  table-hover table-sm'>
		<thead class='table-dark'>
		<tr class='text-center '>
		  <th class='text-uppercase text-secondary text-xxs font-weight-bolder opacity-7'>Status/ID</th>
		  <th class='text-uppercase text-secondary text-xxs font-weight-bolder opacity-7'>Profile picture</th>
		  <th class='text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2'>Username/Email/Phone/Password</th>
		  <th class='text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2'>Location</th>
		  <th class='text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7'>Full name/Rol</th>
		  <th class='text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7'>Created at/Update at/Birthdate</th>
		  <th class='text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7'>Action</th>
		  <th class='text-secondary opacity-7'></th>
		</tr>
	  </thead>";
	while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
		$statusClass = ($row['status_id'] === '1') ? 'bg-danger' : 'bg-success';
		$output .= "<tbody class='table-dark'>
					<tr>
					<td class='align-middle text-center text-sm'>
					  <span class='badge badge-sm {$statusClass}'>{$row['status_id']}</span>
					  <span class='badge badge-sm {$statusClass}'>{$row['user_id']}</span>
					</td>
					<td class='align-middle text-center text-sm'>
					  <p class='text-xs font-weight-bold mb-0'>{$row['profile_picture']}</p>
					</td>
					<td class='text-center'>
					  <div class='d-flex px-2 py-1'>
						<div class='d-flex flex-column justify-content-center'>
						  <p class='text-xs font-weight-bold mb-0'>{$row['username']}</p>
						  <p class='text-xs text-secondary mb-0'>{$row['email']}</p>
						  <p class='text-xs text-secondary mb-0'>{$row['phone']}</p>
						  <p class='text-xs text-secondary mb-0'>{$row['password']}</p>
						</div>
					  </div>
					</td>
					<td>
					  <div class='d-flex px-2 py-1'>
						<div class='d-flex flex-column justify-content-center'>
						  <p class='text-xs font-weight-bold mb-0'>{$row['country']}</p>
						  <p class='text-xs text-secondary mb-0'>{$row['city']}</p>
						</div>
					  </div>
					</td>
					<td class='text-center '>
					  <h6 class='mb-0 text-sm'>{$row['first_name']} {$row['last_name']}</h6>
					  <p class='text-xs text-secondary mb-0'>{$row['rol']}</p>
					</td>
					<td class='align-middle text-center'>
                      <div class='d-flex flex-column justify-content-center'>
						  <p class='text-xs font-weight-bold mb-0'>{$row['created_at']}</p>
						  <p class='text-xs text-secondary mb-0'>{$row['updated_at']}</p>
						  <p class='text-xs text-secondary mb-0'>{$row['birthdate']}</p>
						</div>
					</td>
					<td class='align-middle text-center'>
					 <button type='button' class='btn btn-primary btn-sm' data-bs-toggle='modal' data-bs-target='#detalleUsuarioModal' onclick='mostrarDetallesUsuario({$row['user_id']})'>
						Details
					 </button>
					<button type='button' class='btn btn-danger btn-sm my-2' onclick='eliminarUsuario({$row['user_id']})'>
						Delete
					</button>
				    </td>
				  </tr>
			</tbody>";
	}
	$output .= "</table>";
	echo $output;
} else {
	echo "<h5>No record was found.</h5>";
}