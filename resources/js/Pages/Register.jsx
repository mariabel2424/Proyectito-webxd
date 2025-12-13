import React, { useEffect, useState } from "react";
import "../../css/register.css";

export default function Register() {
  const [roles, setRoles] = useState([]);
  const [cargandoRoles, setCargandoRoles] = useState(true);
  
  const [formData, setFormData] = useState({
    nombre: "",
    apellido: "",
    email: "",
    telefono: "",
    direccion: "",
    password: "",
    password_confirmation: "",
    id_rol: "",
  });

  const [mensaje, setMensaje] = useState("");
  const [errores, setErrores] = useState([]);
  const [cargando, setCargando] = useState(false);

  // ================================
  //  Cargar roles del backend
  // ================================
  useEffect(() => {
    const cargarRoles = async () => {
      try {
        setCargandoRoles(true);
        console.log("🔄 Intentando cargar roles desde: http://localhost:8000/api/roles");
        
        const response = await fetch("http://localhost:8000/api/roles", {
          method: "GET",
          headers: {
            "Accept": "application/json",
            "Content-Type": "application/json",
          },
        });

        console.log("📡 Response status:", response.status);
        console.log("📡 Response ok:", response.ok);

        if (!response.ok) {
          const errorText = await response.text();
          console.error("❌ Error response:", errorText);
          throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        console.log("✅ Datos recibidos:", data);
        console.log("✅ Tipo de datos:", typeof data, Array.isArray(data));

        // Verificar si la respuesta tiene la estructura correcta
        if (Array.isArray(data)) {
          console.log("✅ Es un array directo, cantidad de roles:", data.length);
          setRoles(data);
        } else if (data.data && Array.isArray(data.data)) {
          console.log("✅ Es un objeto con propiedad data (paginado), cantidad de roles:", data.data.length);
          // Filtrar solo roles activos si vienen paginados
          const rolesActivos = data.data.filter(rol => rol.activo !== false);
          setRoles(rolesActivos);
        } else {
          console.error("❌ Estructura de datos inesperada:", data);
          setRoles([]);
        }

      } catch (error) {
        console.error("❌ Error completo:", error);
        console.error("❌ Error name:", error.name);
        console.error("❌ Error message:", error.message);
        
        // Mostrar error más específico
        if (error.name === 'TypeError' && error.message.includes('fetch')) {
          setErrores(prev => [...prev, "No se puede conectar al servidor. ¿Está corriendo Laravel en http://localhost:8000?"]);
        } else {
          setErrores(prev => [...prev, `Error al cargar roles: ${error.message}`]);
        }
        
        setRoles([]);
      } finally {
        setCargandoRoles(false);
      }
    };

    cargarRoles();
  }, []);

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData((prev) => ({
      ...prev,
      [name]: value,
    }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();

    const nuevosErrores = [];

    if (!formData.nombre) nuevosErrores.push("El nombre es obligatorio.");
    if (!formData.apellido) nuevosErrores.push("El apellido es obligatorio.");
    if (!formData.email.includes("@")) nuevosErrores.push("El email no es válido.");
    if (!formData.id_rol) nuevosErrores.push("Debes seleccionar un rol.");
    if (formData.password.length < 8)
      nuevosErrores.push("La contraseña debe tener mínimo 8 caracteres.");
    if (formData.password !== formData.password_confirmation)
      nuevosErrores.push("Las contraseñas no coinciden.");

    if (nuevosErrores.length > 0) {
      setErrores(nuevosErrores);
      return;
    }

    setCargando(true);
    setErrores([]);
    setMensaje("");

    try {
      console.log("📤 Enviando datos:", formData);

      const response = await fetch("http://localhost:8000/api/register", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json",
        },
        body: JSON.stringify(formData),
      });

      const data = await response.json();
      console.log("📥 Respuesta del servidor:", data);

      if (!response.ok) {
        const erroresServidor = [];

        if (data.errors) {
          for (const campo in data.errors) {
            erroresServidor.push(data.errors[campo][0]);
          }
        } else if (data.message) {
          erroresServidor.push(data.message);
        } else {
          erroresServidor.push("Error desconocido al registrar");
        }

        setErrores(erroresServidor);
      } else {
        setMensaje("✔ Registro exitoso");
        setErrores([]);

        // Limpiar formulario
        setFormData({
          nombre: "",
          apellido: "",
          email: "",
          telefono: "",
          direccion: "",
          password: "",
          password_confirmation: "",
          id_rol: "",
        });

        // Opcional: redirigir después de 2 segundos
        setTimeout(() => {
         window.location.href = "/login";
         }, 2000);
      }

    } catch (error) {
      console.error("❌ Error de conexión:", error);
      setErrores(["Error de conexión con el servidor. Verifica que el backend esté corriendo."]);
    } finally {
      setCargando(false);
    }
  };

  return (
    <div className="login-horizontal-container">
      <div className="login-left">
        <h2>Crear Cuenta</h2>

        {mensaje && <p className="mensaje">{mensaje}</p>}
        {errores.length > 0 && (
          <div className="alert">
            <ul>
              {errores.map((error, i) => (
                <li key={i}>{error}</li>
              ))}
            </ul>
          </div>
        )}

        <form onSubmit={handleSubmit}>
          <input
            type="text"
            name="nombre"
            placeholder="Nombre"
            value={formData.nombre}
            onChange={handleChange}
          />

          <input
            type="text"
            name="apellido"
            placeholder="Apellido"
            value={formData.apellido}
            onChange={handleChange}
          />

          {/* SELECT DE ROLES - CON DEBUGGING */}
          <select
            name="id_rol"
            value={formData.id_rol}
            onChange={handleChange}
            disabled={cargandoRoles}
            style={{ 
              backgroundColor: cargandoRoles ? '#f0f0f0' : 'white',
              cursor: cargandoRoles ? 'wait' : 'pointer'
            }}
          >
            <option value="">
              {cargandoRoles 
                ? "Cargando roles..." 
                : roles.length > 0 
                  ? "Selecciona un rol" 
                  : "⚠️ No hay roles disponibles"}
            </option>
            {roles.length > 0 ? (
              roles.map((rol) => (
                <option key={rol.id_rol} value={rol.id_rol}>
                  {rol.nombre}
                </option>
              ))
            ) : null}
          </select>
          
          {/* Mensaje de ayuda si no hay roles */}
          {!cargandoRoles && roles.length === 0 && (
            <small style={{ color: 'red', display: 'block', marginTop: '-10px', marginBottom: '10px' }}>
              ⚠️ Verifica que Laravel esté corriendo en http://localhost:8000
            </small>
          )}

          <input
            type="email"
            name="email"
            placeholder="Correo electrónico"
            value={formData.email}
            onChange={handleChange}
          />

          <input
            type="text"
            name="telefono"
            placeholder="Teléfono"
            value={formData.telefono}
            onChange={handleChange}
          />

          <input
            type="text"
            name="direccion"
            placeholder="Dirección"
            value={formData.direccion}
            onChange={handleChange}
          />

          <input
            type="password"
            name="password"
            placeholder="Contraseña"
            value={formData.password}
            onChange={handleChange}
          />

          <input
            type="password"
            name="password_confirmation"
            placeholder="Confirmar contraseña"
            value={formData.password_confirmation}
            onChange={handleChange}
          />

          <button type="submit" className="botones" disabled={cargando}>
            {cargando ? "Registrando..." : "Registrarse"}
          </button>
        </form>
        
        {/* Indicador de estado del servidor */}
        <div style={{ marginTop: '20px', fontSize: '12px', color: '#666' }}>
          Estado: {cargandoRoles ? '🔄 Cargando...' : roles.length > 0 ? '✅ Conectado' : '❌ Desconectado'}
        </div>
      </div>

      <div className="login-right">
        <img
          src="https://www.teatrocentrodearte.org/images/files/2024/0f2f6cd5-31d9-44e4-9c83-e95bf046cb9d.webp"
          alt="Registro"
        />
        <div className="info-text">
          <h1>Bienvenido</h1>
          <p>Regístrate para continuar</p>
        </div>
      </div>
    </div>
  );
}
