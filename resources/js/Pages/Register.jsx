import React, { useState } from "react";
import "../../css/register.css";

export default function Register() {

  const [formData, setFormData] = useState({
    nombre: "",
    apellido: "",
    cedula: "",
    categoria: "",
    horario: "",
    email: "",
    telefono: "",
    direccion: "",
    password: "",
    password_confirmation: "",
  });

  const [pagoRealizado, setPagoRealizado] = useState("");
  const [comprobante, setComprobante] = useState(null);

  const [mensaje, setMensaje] = useState("");
  const [errores, setErrores] = useState([]);
  const [cargando, setCargando] = useState(false);

  const handleRegresar = () => {
    window.history.back();
  };

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
    if (!formData.cedula || formData.cedula.length !== 10)
      nuevosErrores.push("La cédula debe tener 10 dígitos.");
    if (!formData.categoria) nuevosErrores.push("Seleccione una categoría.");
    if (!formData.horario) nuevosErrores.push("Seleccione un horario.");
    if (!formData.email.includes("@"))
      nuevosErrores.push("El email no es válido.");
    if (formData.password.length < 8)
      nuevosErrores.push("La contraseña debe tener mínimo 8 caracteres.");
    if (formData.password !== formData.password_confirmation)
      nuevosErrores.push("Las contraseñas no coinciden.");

    if (!pagoRealizado)
      nuevosErrores.push("Debe indicar si realizó el pago.");

    if (pagoRealizado === "si" && !comprobante)
      nuevosErrores.push("Debe adjuntar el comprobante de pago.");

    if (nuevosErrores.length > 0) {
      setErrores(nuevosErrores);
      return;
    }

    setCargando(true);
    setErrores([]);
    setMensaje("");

    try {
      const formDataToSend = new FormData();

      for (const key in formData) {
        formDataToSend.append(key, formData[key]);
      }

      formDataToSend.append("pago_realizado", pagoRealizado);

      if (comprobante) {
        formDataToSend.append("comprobante", comprobante);
      }

      const response = await fetch("http://localhost:8000/api/register", {
        method: "POST",
        body: formDataToSend,
      });

      const data = await response.json();

      if (!response.ok) {
        const erroresServidor = [];
        if (data.errors) {
          for (const campo in data.errors) {
            erroresServidor.push(data.errors[campo][0]);
          }
        } else {
          erroresServidor.push("Error al registrar");
        }
        setErrores(erroresServidor);
      } else {
        setMensaje("✔ Registro exitoso");

        setFormData({
          nombre: "",
          apellido: "",
          cedula: "",
          categoria: "",
          horario: "",
          email: "",
          telefono: "",
          direccion: "",
          password: "",
          password_confirmation: "",
        });

        setPagoRealizado("");
        setComprobante(null);

        setTimeout(() => {
          window.location.href = "/login";
        }, 2000);
      }

    } catch (error) {
      setErrores(["Error de conexión con el servidor."]);
    } finally {
      setCargando(false);
    }
  };

  return (
    <div className="login-horizontal-container">
      <div className="login-left">
        <h2>Registro al Curso</h2>

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

        <form onSubmit={handleSubmit} encType="multipart/form-data">

          <input type="text" name="nombre" placeholder="Nombre" value={formData.nombre} onChange={handleChange} />
          <input type="text" name="apellido" placeholder="Apellido" value={formData.apellido} onChange={handleChange} />
          <input type="text" name="cedula" placeholder="Cédula" value={formData.cedula} onChange={handleChange} />

          <select name="categoria" value={formData.categoria} onChange={handleChange}>
            <option value="">Seleccione una categoría</option>
            <option value="niños">Niños</option>
            <option value="jovenes">Jóvenes</option>
            <option value="adultos">Adultos</option>
          </select>

          <select name="horario" value={formData.horario} onChange={handleChange}>
            <option value="">Seleccione horario</option>
            <option value="mañana">Mañana (08h00 - 10h00)</option>
            <option value="tarde">Tarde (14h00 - 16h00)</option>
            <option value="noche">Noche (18h00 - 20h00)</option>
          </select>

          <input type="email" name="email" placeholder="Correo electrónico" value={formData.email} onChange={handleChange} />
          <input type="text" name="telefono" placeholder="Teléfono" value={formData.telefono} onChange={handleChange} />
          <input type="text" name="direccion" placeholder="Dirección" value={formData.direccion} onChange={handleChange} />
          <input type="password" name="password" placeholder="Contraseña" value={formData.password} onChange={handleChange} />
          <input type="password" name="password_confirmation" placeholder="Confirmar contraseña" value={formData.password_confirmation} onChange={handleChange} />

          {/* 💳 PAGO */}
          <select value={pagoRealizado} onChange={(e) => setPagoRealizado(e.target.value)}>
            <option value="">¿Ha realizado el pago?</option>
            <option value="si">Sí, ya pagué</option>
            <option value="no">No, aún no he pagado</option>
          </select>

          {pagoRealizado === "no" && (
            <div className="info-pago">
              <p><strong>Datos para realizar el pago:</strong></p>
              <p>Banco: Banco Pichincha</p>
              <p>N° Cuenta: 1234567890</p>
              <p>Tipo: Ahorros</p>
              <p>Nombre: Liga Cantonal Montecristi</p>
            </div>
          )}

          {pagoRealizado === "si" && (
            <div>
              <label>Adjuntar comprobante de pago:</label>
              <input
                type="file"
                accept="image/*,.pdf"
                onChange={(e) => setComprobante(e.target.files[0])}
              />
            </div>
          )}

          <div style={{ display: "flex", gap: "10px", marginTop: "10px" }}>
            <button type="button" className="botones secundario" onClick={handleRegresar}>
              ⬅ Regresar
            </button>

            <button type="submit" className="botones" disabled={cargando}>
              {cargando ? "Registrando..." : "Registrarse"}
            </button>
          </div>

        </form>
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
