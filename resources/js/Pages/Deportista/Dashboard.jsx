

import React, { useState } from "react";

export default function DashboardEstudiante() {
  const [vista, setVista] = useState("inicio");
  const [editando, setEditando] = useState(false);
  const [foto, setFoto] = useState(
    "https://cdn-icons-png.flaticon.com/512/3135/3135715.png"
  );

  const [perfil, setPerfil] = useState({
    nombre: "Juan Pérez",
    correo: "juan@email.com",
    telefono: "0999999999",
    carrera: "Ingeniería en Sistemas",
  });

  const cambiarFoto = (e) => {
    const file = e.target.files[0];
    if (file) setFoto(URL.createObjectURL(file));
  };

  const cambiarVista = (v) => setVista(v);

  return (
    <>
      <style>{`
        * {
          box-sizing: border-box;
          font-family: "Inter", system-ui, sans-serif;
        }

        body {
          margin: 0;
          background: linear-gradient(135deg,#eef2ff,#f8fafc);
        }

        /* ===== DASHBOARD ===== */
        .dashboard {
          display: flex;
          min-height: 100vh;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
          width: 280px;
          padding: 30px 20px;
          background: linear-gradient(180deg,#020617,#020617ee);
          color: white;
          display: flex;
          flex-direction: column;
          gap: 25px;
        }

        .perfil-mini {
          text-align: center;
        }

        .perfil-mini img {
          width: 110px;
          height: 110px;
          object-fit: cover;
          border-radius: 22px;
          border: 3px solid #38bdf8;
          box-shadow: 0 10px 30px rgba(56,189,248,.6);
        }

        .perfil-mini h3 {
          margin: 10px 0 0;
        }

        /* ===== MENU ===== */
        .menu button {
          width: 100%;
          padding: 15px 18px;
          border-radius: 16px;
          border: none;
          background: rgba(255,255,255,.08);
          color: #e5e7eb;
          font-size: 15px;
          cursor: pointer;
          display: flex;
          align-items: center;
          gap: 12px;
          transition: all .35s ease;
          position: relative;
          overflow: hidden;
        }

        .menu button::before {
          content: "";
          position: absolute;
          inset: 0;
          background: linear-gradient(120deg,#38bdf8,#818cf8);
          opacity: 0;
          transition: .35s;
        }

        .menu button span {
          position: relative;
          z-index: 2;
        }

        .menu button:hover::before,
        .menu button.active::before {
          opacity: 1;
        }

        .menu button:hover,
        .menu button.active {
          color: #020617;
          transform: translateX(6px);
          box-shadow: 0 12px 30px rgba(56,189,248,.35);
        }

        /* ===== CONTENIDO ===== */
        .contenido {
          flex: 1;
          padding: 50px;
          animation: fade .4s ease;
        }

        @keyframes fade {
          from { opacity: 0; transform: translateY(10px); }
          to { opacity: 1; transform: translateY(0); }
        }

        .titulo {
          font-size: 34px;
          margin-bottom: 35px;
          color: #020617;
        }

        /* ===== GRID ===== */
        .grid {
          display: grid;
          grid-template-columns: repeat(auto-fit,minmax(260px,1fr));
          gap: 28px;
        }

        .card {
          background: rgba(255,255,255,.85);
          backdrop-filter: blur(16px);
          border-radius: 26px;
          padding: 30px;
          box-shadow: 0 30px 60px rgba(0,0,0,.12);
          transition: all .35s ease;
        }

        .card:hover {
          transform: translateY(-10px) scale(1.02);
          box-shadow: 0 40px 80px rgba(0,0,0,.18);
        }

        /* ===== PERFIL ===== */
        .perfil-card {
          max-width: 1100px;
        }

        .perfil-grid {
          display: grid;
          grid-template-columns: 320px 1fr;
          gap: 45px;
        }

        .foto-perfil img {
          width: 100%;
          height: 320px;
          object-fit: cover;
          border-radius: 30px;
          border: 4px solid #38bdf8;
          box-shadow: 0 20px 40px rgba(56,189,248,.45);
        }

        .campo {
          margin-bottom: 22px;
        }

        .campo label {
          font-weight: 600;
          color: #334155;
        }

        .campo input {
          width: 100%;
          padding: 14px;
          margin-top: 6px;
          border-radius: 14px;
          border: 1px solid #cbd5e1;
          font-size: 15px;
        }

        .acciones button {
          padding: 14px 30px;
          border-radius: 16px;
          border: none;
          font-weight: 600;
          cursor: pointer;
          transition: .3s;
        }

        .editar {
          background: linear-gradient(120deg,#38bdf8,#818cf8);
        }

        .guardar {
          background: linear-gradient(120deg,#22c55e,#16a34a);
          color: white;
        }

        .acciones button:hover {
          transform: scale(1.05);
        }

        /* ===== TABLAS ===== */
        table {
          width: 100%;
          border-collapse: collapse;
        }

        th, td {
          padding: 18px;
          text-align: center;
          border-bottom: 1px solid #e5e7eb;
        }

        th {
          background: #020617;
          color: white;
        }

        .activo { color: #16a34a; font-weight: bold; }
        .finalizado { color: #dc2626; font-weight: bold; }


    

/* ===== TITULO GENERAL ===== */
.titulo {
  font-size: 34px;
  font-weight: 700;
  margin-bottom: 35px;
  color: #020617;
}

/* ===== BLOQUES DEL HISTORIAL ===== */
.historial-bloque {
  background: rgba(255, 255, 255, 0.95);
  border-radius: 30px;
  padding: 40px;
  margin-bottom: 45px;
  box-shadow: 0 25px 60px rgba(0, 0, 0, 0.15);
  animation: aparecer 0.4s ease;
}

.historial-bloque h3 {
  font-size: 24px;
  margin-bottom: 25px;
  color: #020617;
}

/* ===== CURSOS PROXIMOS A TERMINAR ===== */
.historial-bloque p {
  font-size: 18px;
  margin-bottom: 10px;
}

.terminando {
  display: inline-block;
  margin-top: 12px;
  padding: 6px 18px;
  border-radius: 20px;
  font-weight: 600;
  background: linear-gradient(120deg, #fde68a, #fb7185);
  color: #020617;
}

/* ===== BARRA DE PROGRESO ===== */
.progreso {
  width: 100%;
  height: 14px;
  background: #e5e7eb;
  border-radius: 20px;
  overflow: hidden;
  margin-top: 10px;
}

.progreso span {
  height: 100%;
  display: block;
  border-radius: 20px;
  background: linear-gradient(120deg, #facc15, #fb7185);
  animation: cargar 1s ease;
}

/* ===== TABLA HISTORIAL FINALIZADO ===== */
table {
  width: 100%;
  margin-top: 25px;
  border-collapse: collapse;
  border-radius: 18px;
  overflow: hidden;
}

thead {
  background: linear-gradient(120deg, #020617, #020617cc);
}

th {
  padding: 18px;
  color: #ffffff;
  font-size: 15px;
  text-transform: uppercase;
  letter-spacing: 1px;
}

td {
  padding: 18px;
  text-align: center;
  font-size: 16px;
  border-bottom: 1px solid #e5e7eb;
}

tbody tr:hover {
  background: #f1f5f9;
}

/* ===== ESTADOS ===== */
.finalizado {
  color: #dc2626;
  font-weight: 700;
}

.activo {
  color: #16a34a;
  font-weight: 700;
}

/* ===== ANIMACIONES ===== */
@keyframes aparecer {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes cargar {
  from {
    width: 0%;
  }
}



      `}</style>

      <div className="dashboard">
        {/* SIDEBAR */}
        <aside className="sidebar">
          <div className="perfil-mini">
            <img src={foto} />
            <h3>{perfil.nombre}</h3>
          </div>

          <div className="menu">
            <button className={vista==="inicio"?"active":""} onClick={()=>cambiarVista("inicio")}><span>🏠 Inicio</span></button>
            <button className={vista==="perfil"?"active":""} onClick={()=>cambiarVista("perfil")}><span>👤 Perfil</span></button>
            <button className={vista==="cursos"?"active":""} onClick={()=>cambiarVista("cursos")}><span>🎓 Mis Cursos</span></button>
            <button className={vista==="historial"?"active":""} onClick={()=>cambiarVista("historial")}><span>📜 Historial</span></button>
          </div>
        </aside>

        {/* CONTENIDO */}
        <main className="contenido">
          {vista==="inicio" && (
            <>
              <h2 className="titulo">Resumen del Estudiante</h2>
              <div className="grid">
                <div className="card"><h3>👤 Perfil</h3><p>{perfil.nombre}</p><p>{perfil.carrera}</p></div>
                <div className="card"><h3>🎓 Cursos Activos</h3><p>Fútbol</p><p>Vóley</p></div>
                <div className="card"><h3>⏰ Próximo Curso</h3><p>Fútbol</p><p>Lunes · 08:00</p></div>
                <div className="card"><h3>📜 Historial</h3><p>3 cursos completados</p></div>
              </div>
            </>
          )}

          {vista==="perfil" && (
            <>
              <h2 className="titulo">Perfil del Estudiante</h2>
              <div className="card perfil-card perfil-grid">
                <div className="foto-perfil">
                  <img src={foto} />
                  <input type="file" accept="image/*" onChange={cambiarFoto} />
                </div>

                <div>
                  {Object.keys(perfil).map((key)=>(
                    <div className="campo" key={key}>
                      <label>{key.toUpperCase()}</label>
                      {editando ? (
                        <input
                          value={perfil[key]}
                          onChange={(e)=>setPerfil({...perfil,[key]:e.target.value})}
                        />
                      ) : (
                        <input value={perfil[key]} disabled />
                      )}
                    </div>
                  ))}

                  <div className="acciones">
                    {!editando
                      ? <button className="editar" onClick={()=>setEditando(true)}>Editar</button>
                      : <button className="guardar" onClick={()=>setEditando(false)}>Guardar</button>
                    }
                  </div>
                </div>
              </div>
            </>
          )}

          {vista==="cursos" && (
            <>
              <h2 className="titulo">Horario de Cursos</h2>
              <div className="card">
                <table>
                  <thead>
                    <tr><th>Curso</th><th>Día</th><th>Hora</th><th>Lugar</th></tr>
                  </thead>
                  <tbody>
                    <tr><td>Fútbol</td><td>Lunes</td><td>08:00 - 10:00</td><td>Cancha 1</td></tr>
                    <tr><td>Vóley</td><td>Viernes</td><td>15:00 - 18:00</td><td>Coliseo</td></tr>
                  </tbody>
                </table>
              </div>
            </>
          )}

            {/* HISTORIAL */}
          {vista==="historial" && (
            <>
              <h2 className="titulo">Historial y Progreso</h2>

              {/* PROXIMOS A TERMINAR */}
              <div className="historial-bloque">
                <h3>⏳ Cursos próximos a terminar</h3>

                <p><b>Natación</b> · Finaliza en 5 días</p>
                <div className="progreso"><span style={{width:"85%"}}></span></div>
                <span className="terminando">Terminando</span>
              </div>

              {/* HISTORIAL FINALIZADO */}
              <div className="historial-bloque">
                <h3>📜 Cursos finalizados</h3>
                <table>
                  <thead>
                    <tr>
                      <th>Curso</th>
                      <th>Año</th>
                      <th>Estado</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>Atletismo</td>
                      <td>2023</td>
                      <td className="finalizado">Finalizado</td>
                    </tr>
                    <tr>
                      <td>Básquet</td>
                      <td>2022</td>
                      <td className="finalizado">Finalizado</td>

                    </tr>
                  </tbody>
                </table>
              </div>
            </>
          )}
        </main>
      </div>
    </>
  );
}









